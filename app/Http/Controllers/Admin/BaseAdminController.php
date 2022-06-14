<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BaseAdminController extends Controller
{
    public function __construct()
    {
    	$this->user = auth('admin')->user();
    }
}
