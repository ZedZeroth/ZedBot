<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\ChartController;
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
Route::get('/', function () {
    return view('welcome');
});

/* Currencies */
Route::get('/currencies', [CurrencyController::class, 'showAll']);
Route::get('/currency/{code}', [CurrencyController::class, 'show']);

/* Payments */
Route::get('/payment/networks', [PaymentController::class, 'viewNetworks']);
Route::get('/{network}/payments', [PaymentController::class, 'viewPaymentsOnNetwork']);
Route::get('/payment/{id}', [PaymentController::class, 'viewById']);

/* Accounts */
Route::get('/account/networks', [AccountController::class, 'viewNetworks']);
Route::get('/{network}/accounts', [AccountController::class, 'viewAccountsOnNetwork']);
Route::get('/account/{identfier}', [AccountController::class, 'viewByIdentifier']);

/* Charts */
Route::get('/chart', [ChartController::class, 'view']);
