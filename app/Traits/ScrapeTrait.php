<?php

namespace App\Traits;

use Exception;
use Goutte\Client;
use Illuminate\Http\Request;

trait ScrapeTrait {

    /**
     * @return array
     */

    // Create empty variables
    protected $infoArray = [];

    private $queryArray = [];
    public function scrapeData() {
        // Initate scraping from the website
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
            $crawler = $client->click($link);
        } while ($link);
        // Store the data
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
        return($this->queryArray);
    }
}