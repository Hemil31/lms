<?php

use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Stripe\BillingPortalController;
use App\Http\Controllers\Stripe\PlanController;
use App\Http\Controllers\Stripe\StripeCheckoutController;
use App\Http\Controllers\Stripe\StripeWebhookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Stripe\PaymentController as StripePaymentController;

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

Route::view('/', 'welcome');
Route::get('/payment/{uuid}', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
Route::get('/create-checkout-session/{borrow}/{amount}', [PaymentController::class, 'createCheckoutSession'])->name('checkout.session');
Route::post('/webhook', [PaymentController::class, 'webhook']);

Auth::routes();


Route::post('stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('cashier.webhook')
    ->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

Route::middleware("auth")->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // subscription routes
    Route::get('plans', [PlanController::class, 'index'])->name('plans');
    Route::get('plans/{plan}', [PlanController::class, 'show'])->name("plans.show");

    Route::post('/checkout', [StripeCheckoutController::class, 'subscription'])->name('subscription');
    Route::get('/cancel/{sub}', [StripeCheckoutController::class, 'subscriptionCancel'])->name('subscription-cancel');

    // Onetime Payment
    Route::get('/payment', [StripePaymentController::class, 'payment'])->name('payment');
    Route::post('/payment', [StripePaymentController::class, 'processPayment'])->name('process-payment');

    Route::get('/subscription/swap', [StripeCheckoutController::class, 'subscriptionSwap'])->name('subscription.swap');
});
