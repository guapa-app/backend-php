<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Post;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $vendors = Vendor::where(['status'=> 1, 'verified_badge'=> 1])
                        ->with('logo')
                        ->get();
//
        $posts = Post::where('status', 1)
                     ->latest()
                     ->take(12)
                     ->get();
//
//        $offers = Offer::where('status', 1)
//                      ->where('end_date', '>', now())
//                      ->withSingleRelations()
//                      ->latest()
//                      ->take(4)
//                      ->get();

        return view('frontend.home',compact('vendors','posts'));
    }
}
