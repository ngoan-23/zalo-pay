<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

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

Route::get('zalo/payment', [PaymentController::class, 'getForm']);
Route::post('zalo/payment', [PaymentController::class, 'payment'])->name('zalo.payment');
Route::get('zalo/payment/callback', [PaymentController::class, 'callback'])->name('zalo.payment.callback');


