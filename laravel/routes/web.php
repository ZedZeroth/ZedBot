<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PaymentController;

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

/* Welcome page */
Route::get('/', function () {return view('welcome');});

/* Currencies */
Route::get('/currencies', [CurrencyController::class, 'showAll']);
Route::get('/currency/{code}',[CurrencyController::class, 'show']);

/* Payments */
Route::get('/payments', [PaymentController::class, 'showAll']);
Route::get('/payment/{id}',[PaymentController::class, 'show']);
