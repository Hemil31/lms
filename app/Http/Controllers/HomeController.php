<?php

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Plan;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if($user->role_id != RoleEnum::UserID->value) {
            return view('dashboard');
        }
        $subscriptions = $user->subscriptions;
        $plans = Plan::all();
        return view('home', compact('user', 'subscriptions', 'plans'));
    }
}
