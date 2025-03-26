<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\OfferRepositoryInterface;
use App\Models\Offer;
use App\Models\Post;
use App\Models\Vendor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $offerRepository;

    public function __construct(OfferRepositoryInterface $repository) {
        $this->offerRepository = $repository;
    }
    public function index(Request $request)
    {
        $vendors = Vendor::where(['status'=> "1", 'verified_badge'=> 1])
                        ->with('logo')
                        ->get();

        $posts = Post::where(['status'=> "1", 'type'=> 'blog'])
                    ->with(['admin','category'])
                     ->latest()
                     ->take(12)
                     ->get();

        $products = $this->offerRepository->all($request);


        return view('frontend.home',compact('vendors','posts','products'));
    }
}
