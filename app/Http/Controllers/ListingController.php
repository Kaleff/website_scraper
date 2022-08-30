<?php

namespace App\Http\Controllers;

use Exception;
use Goutte\Client;
use App\Models\Listing;
use App\Traits\ScrapeTrait;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    // Scrape data from website°
    use ScrapeTrait;
    
    public function store()
    {
        $data = $this->scrapeData();
        Listing::insert($data);
        dd('News listings are successfully stored');
    }

    public function index() {
        $listings = Listing::orderBy('rank')
                        ->take(10)
                        ->get();
        return view('listings', ['listingsArray' => $listings, 'page' => 1]);
    }

    public function show($page) {
        // Throw an error in case of invalid parameter
        $page = (int) $page;
        if($page <= 1) {
            abort(404);
        }
        $listings = Listing::orderBy('rank')
                        ->take(10)
                        ->offset(10*($page-1))
                        ->get();
        return view('listings', ['listingsArray' => $listings, 'page' => $page]);
    }
}
