<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use Auth;
use App\Http\Requests\OneTimePaymentRequest;

class PaymentController extends Controller
{
    /**
     * Show payment detail page.
     *
     * @return void
     */
    public function payment()
    {
        $user = Auth::user();
        $intent = $user->createSetupIntent();
        return view('stripe.payment', compact('intent', 'user'));
    }

    /**
     * Process one time charge with stripe
     *
     * @param OneTimePaymentRequest $request
     * @return void
     */
    public function processPayment(OneTimePaymentRequest $request)
    {
        $user          = $request->user();
        $paymentMethod = $request->input('payment_method');
        $amount = $request->input('amount');

        try {
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethod);
            $user->charge($amount * 100, $paymentMethod, [
                'return_url' => route('payment.success'), // Replace with the route to your payment completion page
            ]);
            return back()->with('message', 'One time payment successfully created.');
        } catch (\Exception $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
