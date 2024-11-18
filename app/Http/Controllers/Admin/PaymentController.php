<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BorrowingRecords;
use App\Models\StripPayment;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Webhook;

/**
 * PaymentController
 *
 * Handles requests related to payments.
 */
class PaymentController extends Controller
{
    use JsonResponseTrait;

    /**
     * Displays the payment form for a given borrowing record.
     *
     * @param string $borrowId The UUID of the borrowing record.
     */
    public function showPaymentForm(string $borrowId)
    {
        $response = null;
        try {
            $borrow = BorrowingRecords::where('uuid', $borrowId)->first();
            if ($borrow->returned_at) {
                $response = 'book.already_returned';
            }
            $returnedAt = now();
            $penalty = $returnedAt->diffInDays($borrow->due_date) * Config::get('library.penalty_fee');
            if ($penalty <= 0) {
                $response = 'book.no_penalty';
            }
            if ($response) {
                return $this->errorResponse($response);
            }
            return view('payments.payment', compact('borrow', 'penalty'));
        } catch (\Exception $e) {
            return $this->errorResponse('An error occurred during fetch' . $e->getMessage(), statusCode: 500);
        }
    }


    /**
     * Creates a Stripe Checkout Session for the given borrowing record and penalty amount.
     *
     * @param \App\Models\BorrowingRecords $borrow The borrowing record.
     * @param float $amount The penalty amount.
     */
    public function createCheckoutSession($borrow, $amount)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        try {
            $checkout_session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Late Fee Payment',
                            ],
                            'unit_amount' => $amount * 100, // Convert amount to cents
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => route('payment.success'),
                'cancel_url' => route('payment.failed'),
                'metadata' => [
                    'borrow_id' => $borrow,
                    'penalty_amount' => $amount,
                ],
            ]);
            return ['id' => $checkout_session->id];
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Handles Stripe webhook events.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $endpoint_secret = config('services.stripe.webhook_secret'); // Set this in your env file
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            \Log::info( $event->type);
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                $borrowId = $session->metadata->borrow_id;
                $penaltyAmount = $session->metadata->penalty_amount;
                $borrow = BorrowingRecords::where('uuid', $borrowId)->first();
                if ($borrow) {
                    $borrow->update([
                        'penalty' => $penaltyAmount,
                        'returned_at' => now()
                    ]);
                }
                StripPayment::create([
                    'payment_id' => $session->payment_intent,
                    'user_id' => $borrow->user_id,
                    'amount' => $penaltyAmount,
                    'currency' => $session->currency,
                    'status' => $session->payment_status,
                    'payment_date' => now(),
                ]);
            }
            return response('Webhook handled', 200);
        } catch (\Exception $e) {
            return response('Webhook error', 400);
        }
    }

    /*
     * Payment success pages
     * @return \Illuminate\Http\JsonResponse
     */
    public function success(): JsonResponse
    {
        return $this->successResponse(null, 'message.payment_success', statusCode: 200);
    }

    /*
     * Payment failed pages
     * @return \Illuminate\Http\JsonResponse
     */
    public function failed(): JsonResponse
    {
        return $this->errorResponse('message.payment_failed', statusCode: 500);
    }

}
