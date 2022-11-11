<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChartController;

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
Route::get('/', function () {
    return view('welcome');
});

/* Currencies */
Route::get('/currencies', [CurrencyController::class, 'showAll']);
Route::get('/currency/{code}', [CurrencyController::class, 'show']);
Route::get('/currencies/populate', function () {
    Artisan::call('currencies:populate');
    return Redirect::back();
});

/* Payments */
Route::get('/payments', [PaymentController::class, 'viewAll']);
Route::get('/payment/{id}', [PaymentController::class, 'viewById']);
Route::get('/payments/fetch', function () {
    Artisan::call('payments:fetch');
    return Redirect::back();
});

/* Charts */
Route::get('/chart', [ChartController::class, 'view']);
