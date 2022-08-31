<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\ListingController;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('scrape', function () {
    $listings = new ListingController;
    $listings->store();
})->purpose('Scrape data');

Artisan::command('update {page}', function () {
    // Output error message in case of invalid argument
    $page = (int) $this->argument('page');
    if ($page < 1) {
        dd('Invalid argument, please input valid page number');
    }
    $listings = new ListingController;
    $listings->update($page);
})->purpose('Update points on the desired page');
