<?php

namespace App\Supports\GraphQL\Definition;

use App\Exceptions\AuthException;
use App\Supports\GraphQL\Entity\GraphTypeAttrs;
use \GraphQL\Type\Definition\ResolveInfo;
use \GraphQL\Type\Definition\ObjectType as GraphQLObjectType;

abstract class ObjectType extends GraphQLObjectType
{
    public $typeConfig;
    /**
     * 是否需要认证
     * @var bool
     */
    protected $needAuth = true;

    public function __construct($args)
    {
        $this->typeConfig = $args;
        // 获取属性
        $attrs = $this->attrs();
        $self = $this;

        $config = [
            'name' => array_key_exists('name', $args) ? $args['name'] : $attrs->name,
            'description' => array_key_exists('desc', $args) ? $args['desc'] : $attrs->desc,
            'fields' => function () use ($self, $args) {
                // 判断是否从args传入
                if (array_key_exists('fields', $args)) {
                    $fields = array_merge($self->fields(), $args['fields']);
                } else {
                    $fields = $self->fields();
                }

                foreach ($fields as $key => &$field) {
                    if (is_array($field)) {
                        // 过滤fields简写
                        if (array_key_exists('desc', $field)) {
                            $field['description'] = $field['desc'];
                        }
                        // 过滤args简写
                        if (array_key_exists('args', $field) && is_array($field['args'])) {
                            foreach ($field['args'] as &$arg) {
                                if (is_array($arg) && array_key_exists('desc', $arg)) {
                                    $arg['description'] = $arg['desc'];
                                }
                            }
                        }
                    }
                }

                return $fields;
            },
            'resolveField' => function ($val, $args, $context, ResolveInfo $info) {
                $fieldName = $info->fieldName;
                $authFields = $this->authFields();

                if (is_array($authFields) && count($authFields) > 0) {
                    if (in_array($fieldName, $authFields) && !auth()->check()) {
                        // 需要登录认证
                        throw new AuthException();
                    } elseif (array_key_exists($fieldName, $authFields) && !auth()->check()) {
                        // 用于更详细的身份认证
                        throw new AuthException();
                    }
                } elseif (is_bool($authFields) && $authFields && !auth()->check()) {
                    throw new AuthException();
                }

                // 如果定义了resolveField则使用它
                if (method_exists($this, 'resolveField')) {
                    return $this->resolveField($val, $args, $context, $info);
                }

                // 处理fieldsMap
                $fieldsMap = $this->fieldsMap();
                if (array_key_exists($fieldName, $fieldsMap)) {
                    $fieldName = $fieldsMap[$fieldName];
                }

                // 替换fieldName中的_下划线
                $methodName = "resolve" . str_replace('_', '', $fieldName);

                if (method_exists($this, $methodName)) {
                    return $this->{$methodName}($val, $args, $context, $info);
                } else {
                    if (is_object($val)) {
                        return isset($val->{$fieldName}) ? $val->{$fieldName} : null;
                    } else if (is_array($val)) {
                        return array_key_exists($fieldName, $val) ? $val[$fieldName] : null;
                    } else {
                        return null;
                    }
                }
            }
        ];

        parent::__construct($config);
    }

    abstract public function attrs(): GraphTypeAttrs;

    abstract public function fields(): array;

    public function fieldsMap()
    {
        return [];
    }

    /**
     * 需要登录、权限认证的字段
     * @return array|bool
     */
    public function authFields()
    {
        return [];
    }

    /**
     * 创建Graph类型属性
     * @param string $name
     * @param string $desc
     * @return GraphTypeAttrs
     */
    protected function createAttr(string $name, string $desc = ''): GraphTypeAttrs
    {
        return new GraphTypeAttrs([
            'name' => $name,
            'desc' => $desc,
        ]);
    }
}
