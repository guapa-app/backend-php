<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V3\ShareLinkController;

Route::get('/{identifier}/', [ShareLinkController::class, 'getAppLinkData'])->name('v3.sharelinks.app-data'); 