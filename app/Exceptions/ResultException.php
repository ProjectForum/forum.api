<?php


namespace App\Exceptions;

use App\Libs\Result;
use Exception;
use Illuminate\Http\Response;
use Throwable;

class ResultException extends Exception
{
    protected $status;

    public function __construct($message = "",
                                $code = Result::INTERNAL_SERVER_ERROR,
                                $status = Response::HTTP_INTERNAL_SERVER_ERROR,
                                Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }
}
