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
        $subscription = Subscription::where('ends_at',null)->find($id);
        $user->subscription($subscription->type)->cancel();
        $subscription->update(['stripe_status' => 'inactive']);
        return redirect()->route('home')->with('message', 'Subscription has been cancelled.');
    }


    // /**
    //  * Swap the subscription plan for the user.
    //  *
    //  * @param Request $request
    //  * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
    //  */
    // public function subscriptionSwap(Request $request)
    // {
    //     try {
    //         $user = Auth::user();
    //         $newPlan = Plan::where('name', $request->plan)->first();
    //         if (!$newPlan) {
    //             return redirect()->route('home')->with('error', 'Invalid plan selected.');
    //         }
    //         $user->subscription($request->current_plan)
    //             ->swap($newPlan->stripe_plan);

    //         return redirect()->route('home')->with('message', 'Subscription plan updated successfully.');
    //     } catch (\Exception $e) {
    //         $error = $e->getMessage();
    //         return view('stripe.error', compact('error'));
    //     }
    // }
}
