<?php


namespace App\Exceptions;


use App\Supports\Helpers\ResultCreator;
use Exception;
use Throwable;

class AuthException extends Exception
{
    public function __construct(
        $message = '',
        $code = ResultCreator::USER_UNAUTHORIZED,
        Throwable $previous = null
    )
    {
        parent::__construct($message, $code, $previous);
    }
}
