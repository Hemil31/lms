<?php

namespace App\Http\Controllers\Stripe;

use Illuminate\Http\Request;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

class StripeWebhookController extends Controller
{
    /**
     * Handle successful payment event
     *
     * @param array payload
     */
    public function handleInvoicePaymentSucceeded($payload) {
        // Handle successful payment event
    }
}
