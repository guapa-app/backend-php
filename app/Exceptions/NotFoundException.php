<?php

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'success' => false,
            'message' => __('api.not_found'),
//            "errors" => __('api.not_found')
        ], 404);
    }
}
