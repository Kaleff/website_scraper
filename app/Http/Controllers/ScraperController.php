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
        $this->infoArray = [];
        $crawler = $client->request('GET', 'https://news.ycombinator.com/');
        $crawler->filter('.titlelink')->each(function ($node) {
            echo $node->text()."<br>";
            $this->infoArray[0]['title'] = $node->text();
        });
        $crawler->filter('.subtext')->each(function ($node) {
            echo $node->text()."<br>";
            $this->infoArray[0]['desc'] = $node->text();
        });
        dump($this->infoArray[0]);
        $link = $crawler->filter('.morelink')->link();
        while($link) {
            $crawler = $client->click($link);
            $crawler->filter('.titlelink')->each(function ($node) {
                echo $node->text()."<br>";
                $this->infoArray[$this->counter]['title'] = $node->text();
            });
            $crawler->filter('.subtext')->each(function ($node) {
                echo $node->text()."<br>";
                $this->infoArray[$this->counter]['desc'] = $node->text();
            });
            try {
                $link = $crawler->filter('.morelink')->link();
            } catch(Exception $ex) {
                break;
            }
            // Sleep to prevent being timed out of the website
            sleep(2);
            $this->counter++;
        }
    }
}
