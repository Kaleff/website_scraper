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

    // Store the scraped data in database
    public function store()
    {
        $data = $this->scrapeData();
        Listing::insert($data);
        dd('News listings are successfully stored');
    }

    //Update the points of the page
    public function update($page)
    {
        $listings = Listing::orderBy('rank')
            ->take(10)
            ->offset(10 * ($page - 1))
            ->pluck('score', 'id');
        $client = new Client();
        foreach ($listings as $id => $score) {
            // Skips the ads that do not have points
            if ($score != 0) {
                // Scrape the data from pages
                $link = 'https://news.ycombinator.com/item?id=' . $id;
                $crawler = $client->request('GET', $link);
                $newScore = (int) $crawler->filter('.score')->text();
                $listings[$id] = $newScore;
                // Rewrite new data
                Listing::where('id', $id)
                    ->update(['score' => $newScore]);
                // Sleep at random intervals to prevent getting timed out
                sleep(rand(3, 6));
            }
        }
        dd('Listings were updated!');
    }

    // Get the data of the first page of listings for the home page
    public function index()
    {
        $listings = Listing::orderBy('rank')
            ->take(10)
            ->get();
        return view('listings', ['listingsArray' => $listings, 'page' => 1]);
    }

    // Get the data of specific page
    public function show($page)
    {
        // Throw an error in case of invalid parameter
        $page = (int) $page;
        if ($page <= 1) {
            abort(404);
        }
        $listings = Listing::orderBy('rank')
            ->take(10)
            ->offset(10 * ($page - 1))
            ->get();
        return view('listings', ['listingsArray' => $listings, 'page' => $page]);
    }
    
    // Delete the listing and return to the homepage
    public function delete($id)
    {
        Listing::destroy($id);
        return redirect('/')->with('message', 'Listing deleted!');
    }
}
