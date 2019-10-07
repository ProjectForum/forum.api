<?php


namespace App\Supports\Helpers;


use Illuminate\Http\Response;

class ResultCreator
{
    /**
     * 创建响应
     *
     * @param int $errorCode
     * @param string $message
     * @param int $httpStatus
     * @param array $payload
     * @return Response
     */
    public static function create(int $errorCode, string $message = '', int $httpStatus = 200, $payload = []): Response
    {
        return response(
            [
                'code' => $errorCode,
                'msg' => $message,
                'data' => $payload
            ],
            $httpStatus
        );
    }

    public static function error(
        int $errorCode,
        string $message,
        int $httpStatus,
        array $payload = []
    ): Response
    {
        return self::create($errorCode, $message, $httpStatus, $payload);
    }

    public static function success(string $message, $payload = []): Response
    {
        return self::create(self::SUCCESS, $message, 200, $payload);
    }

    const SUCCESS = 0;
    const INTERNAL_SERVER_ERROR = 1001; // 服务器内部错误
    const REQUEST_FIELD_VALIDATION_FAIL = 1002; // 请求字段验证失败
    const RESOURCE_NOT_FOUND = 1003; // 资源不存在
    const DATABASE_QUERY_FAILED = 1004; // 数据库查询失败

    const USER_UNAUTHORIZED = 2001; // 用户JWT鉴权失败
    const USER_NOT_FOUND = 2002; // 没有找到该用户
}