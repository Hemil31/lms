<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Cashier\Exceptions\IncompletePayment;

class StripeCheckoutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(public Plan $planRepository)
    {
    }

    /**
     * Process subscription with selected plan.
     *
     * @param Request $request
     * @return void
     */
    public function subscription(Request $request)
    {
        try {
            $plan = Plan::where('name', $request->plan)->first();
            $request->user()
                ->newSubscription($request->plan, $plan->stripe_plan)
                ->create($request->token);
            return redirect()->route('home');
        } catch (IncompletePayment $e) {
            $error = $e->getMessage();
            return view('stripe.error', compact('error'));
        } catch (\Exception $e) {
            $error = $e->getMessage();
            return view('stripe.error', compact('error'));
        }
    }

    /**
     * Cancel stripe subscription.
     *
     * @param Request $request,
     * @param Int $id,
     * @return void
     */
    public function subscriptionCancel($id)
    {
        $user = Auth::user();
        $subscription = Subscription::find($id);
        $user->subscription($subscription->type)->cancel();
        return redirect()->route('home')->with('message', 'Subscription has been cancelled.');
    }
}
