<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $message;
    protected $code;
    public function __construct($message = "", $code = 0)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function render($request)
    {
        return response()->json([
            "success" => false,
            "message" => $this->message,
//            "errors" => $this->message
        ], $this->code);
    }
}
