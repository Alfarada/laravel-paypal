<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ResultController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/results', [ResultController::class, 'results'])->name('results');

//Payment

Route::get('/paypal/pay', [PaymentController::class, 'payWithPaypal'])->name('paypal.pay');

Route::get('/paypal/status', [PaymentController::class, 'paypalStatus'])->name('paypal.status');
