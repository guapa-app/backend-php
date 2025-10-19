<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\PostController as ApiPostController;
use Illuminate\Http\Request;

class PostController extends ApiPostController
{
    public function index(Request $request)
    {
        return response()->json(parent::index($request));
    }

    public function single($id)
    {
        return response()->json(parent::single($id));
    }
}
