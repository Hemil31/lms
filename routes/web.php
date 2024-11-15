<?php

use App\Http\Controllers\Admin\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/payment/{uuid}', [PaymentController::class,'showPaymentForm'])->name('payment.form');
Route::get('/create-checkout-session/{borrow}/{amount}', [PaymentController::class, 'createCheckoutSession'])->name('checkout.session');
Route::post('/webhook', [PaymentController::class, 'webhook']);
