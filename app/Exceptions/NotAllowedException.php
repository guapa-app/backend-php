<?php

namespace App\Exceptions;

use Exception;

class NotAllowedException extends Exception
{
    public function render($request)
    {
        return response()->json([
            "success" => false,
            "message" => __('api.not_allowed'),
//            "errors" => __('api.not_allowed')
        ], 401);
    }
}
