<?php

namespace App\Supports\GraphQL;

use App\Http\Controllers\Controller;
use App\Supports\GraphQL\Definition\ObjectType;
use App\Supports\Helpers\ResultCreator;
use Config;
use GraphQL\Error\Debug;
use GraphQL\Error\Error;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class GraphController extends Controller
{
    /**
     * @var array
     */
    protected $graphConfig;

    /**
     * @var array
     */
    protected $graphTypes;

    /**
     * 默认Scheme入口
     * @var string
     */
    protected $defaultAction = 'root';

    public function __construct()
    {
        $this->graphConfig = Config::get('graph');
        $this->graphTypes = $this->getGraphTypes();
    }

    /**
     * 获取当前控制前的 GraphTypes
     * @return array
     */
    abstract protected function getGraphTypes(): array;

    /**
     * 获取 GraphType
     * @param string $type
     * @param string $directive
     * @return ObjectType
     */
    abstract protected function getGraphType(string $type, string $directive): ObjectType;

    public function action(Request $request, $action = '')
    {
        // 设置默认入口
        if (empty($action)) {
            $action = $this->defaultAction;
        }
        // 获取schema
        $schemaTypes = [];

        if (in_array($action, $this->graphConfig['schema']) || array_key_exists($action, $this->graphConfig['schema'])) {
            // 如果为key则使用其value作为action
            if (array_key_exists($action, $this->graphConfig['schema'])) {
                $action = $this->graphConfig['schema'][$action];
            }
        } else {
            return ResultCreator::error(
                ResultCreator::RESOURCE_NOT_FOUND,
                "[$action] 未在 schema 中定义",
                Response::HTTP_NOT_FOUND
            );
        }

        // 判断action是否在types中
        if (!array_key_exists($action, $this->graphTypes)) {
            return ResultCreator::error(
                ResultCreator::RESOURCE_NOT_FOUND,
                "Type [$action] 未在 types 中定义",
                Response::HTTP_NOT_FOUND
            );
        }

        // 构建当前action对应的获取schema
        if (gettype($this->graphTypes[$action]) == 'array') {
            foreach ($this->graphTypes[$action] as $key => $typeClass) {
                $schemaTypes[$key] = $this->getGraphType($action, $key);
            }
        } else {
            $schemaTypes['query'] = $this->getGraphType($action, 'query');
        }

        $schema = new Schema($schemaTypes);

        // 从请求中获取数据
        $input = json_decode($request->getContent(), true);
        $query = $input['query'];
        $variables = !empty($input) && array_key_exists('variables', $input) ? $input['variables'] : null;
        $rootValue = [];

        if (empty($query)) {
            return ResultCreator::error(
                ResultCreator::REQUEST_FIELD_VALIDATION_FAIL,
                '请求内容为空',
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        // 执行请求
        $isDebugMode = $request->has('debug');
        $output = GraphQL::executeQuery($schema, $query, $rootValue, [], $variables)
            ->setErrorsHandler(function (array $errors, callable $formatter) use ($isDebugMode) {
                if ($isDebugMode) {
                    return array_map($formatter, $errors);
                } else {
                    /** @var Error $error */
                    $error = array_pop($errors);
                    if (empty($error->getPrevious())) {
                        throw $error;
                    } else {
                        throw $error->getPrevious();
                    }
                }
            })
            ->toArray(
                $isDebugMode ? Debug::INCLUDE_DEBUG_MESSAGE | Debug::RETHROW_INTERNAL_EXCEPTIONS : false
            );

        if (!array_key_exists('data', $output)) {
            if ($isDebugMode) {
                return ResultCreator::error(
                    ResultCreator::INTERNAL_SERVER_ERROR,
                    '系统错误，请稍后再试',
                    Response::HTTP_INTERNAL_SERVER_ERROR,
                    $output
                );
            } else {
                return ResultCreator::error(
                    ResultCreator::INTERNAL_SERVER_ERROR,
                    '系统错误，请稍后再试',
                    Response::HTTP_INTERNAL_SERVER_ERROR
                );
            }
        }

        return ResultCreator::success(
            'succeed',
            $output['data']
        );
    }
}
