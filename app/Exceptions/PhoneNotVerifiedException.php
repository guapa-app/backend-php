<?php

namespace App\Exceptions;

use Exception;

class PhoneNotVerifiedException extends Exception
{
    public function render($request)
    {
        return response()->json([
            "success" => false,
            'phone_verified' => false,
            "message" => __('api.phone_not_verified'),
//            "errors" => __('api.phone_not_verified')
        ], 401);
    }
}
