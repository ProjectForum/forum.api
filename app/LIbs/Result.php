<?php


namespace App\Libs;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Response;

class Result
{
    public static function create(int $errorCode, string $message = '', int $httpStatus = 200, $payload = []): Response
    {
        return response(
            [
                'code' => $errorCode,
                'message' => $message,
                'payload' => $payload
            ],
            $httpStatus
        );
    }

    public static function error(int $errorCode, string $message, int $httpStatus, array $payload = []): Response
    {
        return self::create($errorCode, $message, $httpStatus, $payload);
    }

    public static function success(string $message, $payload = []): Response
    {
        return self::create(self::SUCCESS, $message, 200, $payload);
    }

    public static function pagination(LengthAwarePaginator $paginator, string $message = '')
    {
        return self::success(
            $message,
            [
                'list' => $paginator->items(),
                'pagination' => [
                    'total' => $paginator->total(),
                    'size' => $paginator->perPage(),
                    'currentPage' => $paginator->currentPage(),
                    'lastPage' => $paginator->lastPage(),
                ],
            ]
        );
    }

    const SUCCESS = 1;
    const INTERNAL_SERVER_ERROR = 1001; // 服务器内部错误
    const REQUEST_FIELD_VALIDATION_FAIL = 1002; // 请求字段验证失败
    const RESOURCE_NOT_FOUND = 1003; // 资源不存在
    const USER_UNAUTHORIZED = 2001; // 用户JWT鉴权失败
    const USER_NOT_FOUND = 2002; // 没有找到该用户
    const USER_PASSWORD_ERROR = 2003; // 用户密码错误
}
