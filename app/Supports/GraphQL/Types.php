<?php


namespace App\Supports\GraphQL;


use Exception;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;

class Types
{
    /**
     * GraphQL的配置
     * @var array
     */
    protected $config;

    /**
     * 存储已经生成的type
     * @var array
     */
    protected $typeList = [];

    /**
     * 存储Key
     * @var string
     */
    protected $configTypesKey = 'types';

    public function __construct()
    {
        $this->config = config('graph');
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic($name, $arguments)
    {
        return app(self::class)->handleCallStatic($name, $arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function handleCallStatic(string $name, array $arguments)
    {
        $argumentsCount = count($arguments);
        if ($argumentsCount > 0) {
            $typeName = $name;
            $directive = 'query';
            $typeConfig = [];

            if ($argumentsCount == 1) {
                if (is_string($arguments[0])) {
                    $directive = $arguments[0];
                } elseif (is_array($arguments[0])) {
                    $typeConfig = $arguments[0];
                    if (array_key_exists('name', $typeConfig)) {
                        $typeName = $typeConfig['name'];
                    }
                }
            } elseif ($argumentsCount == 2) {
                $typeConfig = $arguments[1];
                // 如果设置了TypeName 则改变TypeName
                if (array_key_exists('name', $typeConfig)) {
                    $typeName = $typeConfig['name'];
                }
            }

            return $this->getType($name, $typeName, $directive, $typeConfig);
        } else {
            return $this->getType($name, $name, 'query', []);
        }
    }

    /**
     * 获取Type
     *
     * @param string $className
     * @param string $typeName
     * @param string $directive
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    protected function getType($className, $typeName, $directive, $arguments)
    {
        $typeListKey = "{$typeName}_{$directive}";
        // 如果已经创建该Type则直接从TypeList中返回
        if (array_key_exists($typeListKey, $this->typeList)) {
            return $this->typeList[$typeListKey];
        }

        // 创建Type
        if (!array_key_exists($className, $this->config[$this->configTypesKey])) {
            throw new Exception("Type '{$typeName}' 不存在", 1);
        }

        // 获取当前Class对应的Type列表
        $typeClassList = $this->config[$this->configTypesKey][$className];

        // 如果不为array的话视为query
        if (gettype($typeClassList) != 'array') {
            // 如果当前指令为query 则直接使用该Class
            if ($directive != 'query') {
                throw new Exception("Type '{$typeName}' 中不存在指令 {$directive}", 1);
            }
            return $this->typeList[$typeListKey] = app($typeClassList, [
                'args' => $arguments,
            ]);
        }

        // 从 Type 通过 directive（指令） 找到对应的 TypeClass
        if (!array_key_exists($directive, $typeClassList)) {
            throw new Exception("Type '{$typeName}' 中不存在指令 {$directive}", 1);
        }
        return $this->typeList[$typeListKey] = app($typeClassList[$directive], [
            'args' => $arguments,
        ]);
    }

    public static function boolean()
    {
        return Type::boolean();
    }

    public static function float()
    {
        return Type::float();
    }

    public static function id()
    {
        return Type::id();
    }

    public static function int()
    {
        return Type::int();
    }

    public static function string()
    {
        return Type::string();
    }

    public static function nonNull($type)
    {
        return new NonNull($type);
    }

    public static function listOf($type)
    {
        return new ListOfType($type);
    }
}
