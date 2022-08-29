<?php

namespace App\Http\Controllers;

use Exception;
use Goutte\Client;
use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    // Scrape data from website
    public static function store()
    {
        $infoArray = [];
        $queryArray = [];
        // Initate scraping from the website
        $client = new Client();
        $crawler = $client->request('GET', 'https://news.ycombinator.com/');
        do {
            // Scrape post ranks and convert to integer
            $crawler->filter('.rank')->each(function ($node) {
                $infoArray['rank'][] = (int) $node->text();
            });
            // Scrape post title
            $crawler->filter('.titlelink')->each(function ($node) {
                $infoArray['title'][] = $node->text();
            });
            // Scrape post link
            $crawler->filter('.titlelink')->each(function ($node) {
                $infoArray['link'][] = $node->attr('href');
            });
            // Scrape points and convert to integer
            $crawler->filter('.subtext')->each(function ($node) {
                $allText = $node->text();
                // If the listing is an add (without points) the points are set to 0
                if (strpos($allText, 'point') !== false) {
                    $infoArray['score'][] = (int) substr($allText, 0, strpos($allText, 'point'));
                } else {
                    $infoArray['score'][] = 0;
                }
            });
            // Scrape timestamp
            $crawler->filter('.age')->each(function ($node) {
                $infoArray['age'][] = str_replace('T', ' ', $node->attr('title'));
            });
            // Scrape unique id and convert to integer
            $crawler->filter('.athing')->each(function ($node) {
                $infoArray['id'][] = (int) $node->attr('id');
            });
            try {
                // Contrinue scraping to the next page
                $link = $crawler->filter('.morelink')->link();
            } catch (Exception $ex) {
                // Stop scraping on the last page
                break;
            }
            // Sleep to prevent being timed out of the website with random intervals
            sleep(rand(3, 15));
            // Transition to the next page
            $crawler = $client->click($link);
        } while ($link);
        // Store the data
        for ($i = 0; $i < count($infoArray['id']); $i++) {
            $queryArray[] = [
                'id' => $infoArray['id'][$i],
                'rank' => $infoArray['rank'][$i],
                'title' => $infoArray['title'][$i],
                'link' => $infoArray['link'][$i],
                'score' => $infoArray['score'][$i],
                'created_at' => $infoArray['age'][$i]
            ];
        }
        Listing::createMany([$queryArray]);
        dd("Data is scraped succesfully");
    }
}
