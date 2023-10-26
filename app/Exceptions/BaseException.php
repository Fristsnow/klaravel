<?php

namespace App\Exceptions;

use Exception;

class BaseException extends Exception
{
    //
    public function __construct(int $code = 0,string $message = "", $data = null,  ?Throwable $previous = null)
    {
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }
}
