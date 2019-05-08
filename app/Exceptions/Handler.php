<?php

namespace App\Exceptions;

use App\Libs\Result;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ResultException) {
            return Result::error(
                $exception->getCode(),
                $exception->getMessage(),
                $exception->getStatus()
            );
        } else if ($exception instanceof ValidationException) {

            $error = $exception->validator->errors()->first();

            // 如果错误是字符串则使用默认返回体
            if (is_string($error)) {
                return Result::error(
                    Result::REQUEST_FIELD_VALIDATION_FAIL,
                    $error,
                    Response::HTTP_BAD_REQUEST
                );
            }

            return $error;
        } else {
            if (config('app.debug')) {
                return parent::render($request, $exception);
            }

            return Result::error(
                Result::INTERNAL_SERVER_ERROR,
                '系统错误，请稍后再试',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
