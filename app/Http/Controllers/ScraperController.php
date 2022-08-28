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
    public function index()
    {
        $client = new Client();
        $crawler = $client->request('GET', 'https://news.ycombinator.com/');
        do {
            $crawler->filter('.rank')->each(function ($node) {
                $this->infoArray['rank'][] = (int) $node->text();
            });
            $crawler->filter('.titlelink')->each(function ($node) {
                $this->infoArray['title'][] = $node->text();
            });
            $crawler->filter('.titlelink')->each(function ($node) {
                $this->infoArray['link'][] = $node->attr('href');
            });
            $crawler->filter('.score')->each(function ($node) {
                $this->infoArray['score'][] = $node->text();
            }); 
            $crawler->filter('.age')->each(function ($node) {
                $this->infoArray['age'][] = $node->attr('title');
            }); 
            $crawler->filter('.athing')->each(function ($node) {
                $this->infoArray['unique_id'][] = $node->attr('id');
            }); 
            dd($this->infoArray['rank']);
            try {
                // Contrinue scraping to the next page
                $link = $crawler->filter('.morelink')->link();
            } catch(Exception $ex) {
                // Stop scraping on the last page
                break;
            }
            // Sleep to prevent being timed out of the website
            sleep(2);
            $this->counter++;
            $crawler = $client->click($link);
        } while($link);
        dump($this->infoArray['title']);
        dump($this->infoArray['link']);
        dump($this->infoArray['score']);
        dump($this->infoArray['age']);
        dump($this->infoArray['unique_id']);
        dd($this->infoArray['rank']);
    }
}