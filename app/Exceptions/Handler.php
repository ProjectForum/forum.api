<?php

namespace App\Exceptions;

use App\Supports\Helpers\ResultCreator;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
            return ResultCreator::error(
                $exception->getCode(),
                $exception->getMessage(),
                $exception->getStatus()
            );
        } elseif ($exception instanceof ValidationException) {
            return ResultCreator::error(
                ResultCreator::REQUEST_FIELD_VALIDATION_FAIL,
                $exception->validator->errors()->first(),
                $exception->status
            );
        } elseif ($exception instanceof AuthException) {
            return ResultCreator::error(
                $exception->getCode(),
                $exception->getMessage() ?: '请登录后再进行操作',
                Response::HTTP_UNAUTHORIZED
            );
        }

        if (env('APP_DEBUG') || app('request')->has('debug')) {
            return parent::render($request, $exception);
        }

        return ResultCreator::error(
            ResultCreator::INTERNAL_SERVER_ERROR,
            '系统错误，请稍后再试',
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
