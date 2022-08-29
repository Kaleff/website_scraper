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
        dd($listings);
    }

    public function show($page) {
        $listings = Listing::orderBy('rank')
                        ->take(10)
                        ->offset(10*($page-1))
                        ->get();
        dd($listings);
    }
}
