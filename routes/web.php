<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Main page and 1st listings page
Route::get('/', [ListingController::class, 'index'])->middleware('auth');
// Listings page
Route::get('/listings/{page}', [ListingController::class, 'show'])->where(['page' => '[0-9]+'])->middleware('auth');
// Delete listing page
Route::get('/delete/{id}', [ListingController::class, 'delete'])->where(['id' => '[0-9]+'])->middleware('auth');
// User login form
Route::get('/login', [UserController::class, 'login'])->name('login');
// User authentication
Route::post('/users/auth', [UserController::class, 'authenticate']);
// User logout
Route::post('/logout', [UserController::class, 'logout']);