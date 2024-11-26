<?php

namespace App\Http\Controllers\Stripe;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
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
     * Get all the plan list
     *
     * @return void
     */
    public function index()
    {
        $plans = $this->planRepository->all();
        return view('stripe.index', compact('plans'));
    }

    /**
     * Get plan detail.
     *
     * @param int $planId,
     * @param Request $request,
     * @return void
     */
    public function show($planId, Request $request)
    {
        $plan = $this->planRepository->find($planId);
        $intent = auth()->user()->createSetupIntent();
        return view('stripe.subscription', compact('plan', 'intent'));
    }
}
