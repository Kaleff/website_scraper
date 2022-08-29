<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Goutte\Client;

class ScraperController extends Controller
{
    //
    protected $counter = 1;
    protected $infoArray = [];

    private $queryArray = [];
    public function store()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://news.ycombinator.com/');
        do {
            // Scrape post ranks and convert to integer
            $crawler->filter('.rank')->each(function ($node) {
                $this->infoArray['rank'][] = (int) $node->text();
            });
            // Scrape post title
            $crawler->filter('.titlelink')->each(function ($node) {
                $this->infoArray['title'][] = $node->text();
            });
            // Scrape post link
            $crawler->filter('.titlelink')->each(function ($node) {
                $this->infoArray['link'][] = $node->attr('href');
            });
            // Scrape points and convert to integer
            $crawler->filter('.subtext')->each(function ($node) {
                $allText = $node->text();
                // If the listing is an add (without points) the points are set to 0
                if (strpos($allText, 'point') !== false) {
                    $this->infoArray['score'][] = (int) substr($allText, 0, strpos($allText, 'point'));
                } else {
                    $this->infoArray['score'][] = 0;
                }
            });
            // Scrape timestamp
            $crawler->filter('.age')->each(function ($node) {
                $this->infoArray['age'][] = str_replace('T', ' ', $node->attr('title'));
            });
            // Scrape unique id and convert to integer
            $crawler->filter('.athing')->each(function ($node) {
                $this->infoArray['id'][] = (int) $node->attr('id');
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
            $this->counter++;
            $crawler = $client->click($link);
        } while ($link);
        // Re
        for ($i = 0; $i < count($this->infoArray['id']); $i++) {
            $this->queryArray[] = [
                'id' => $this->infoArray['id'][$i],
                'rank' => $this->infoArray['rank'][$i],
                'title' => $this->infoArray['title'][$i],
                'link' => $this->infoArray['link'][$i],
                'score' => $this->infoArray['score'][$i],
                'created_at' => $this->infoArray['age'][$i]
            ];
        }
        dd($this->queryArray);
    }
}
