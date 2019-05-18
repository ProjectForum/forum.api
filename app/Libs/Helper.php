<?php


namespace App\Libs;


class Helper
{
    /**
     * 创建用于请求验证的规则
     * @param array $fields
     * @return array
     */
    public static function buildValidateRules($fields)
    {
        $rules = [];
        $errors = [];

        foreach ($fields as $field => $title) {
            if ($title === null) {
                // 为空的话则不添加任何规则
                $options = [
                    'rule' => '',
                    'errors' => [],
                ];
            } else if (is_string($title)) {
                // 字符串情况下则添加唯一
                $options = [
                    'rule' => 'required',
                    'errors' => [
                        'required' => $title . '不能为空',
                    ],
                ];
            } else if (is_array($title)) {
                // 数组的话则直接设置为options
                $options = $title;
            }

            if (isset($options)) {
                $rules[$field] = $options['rule'];
                foreach ($options['errors'] as $error => $message) {
                    $errors["{$field}.{$error}"] = $message;
                }
            }
            unset($options);
        }

        return [$rules, $errors];
    }
}
