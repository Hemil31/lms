<?php

namespace App\Console\Commands;

use App\Models\Plan;
use Illuminate\Console\Command;
use Laravel\Cashier\Cashier;

class RefreshStripePlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:refresh-stripe-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch stripe plans and insert into the system.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching products and price from stripe...');
        $stripe = Cashier::stripe();
        $plans = $stripe->products->all();
        Plan::truncate();
        if (!empty($plans)) {
            foreach ($plans as $plan) {
                $price = $stripe->prices->all(
                    ['product' => $plan->id]
                )->first();
                Plan::updateOrCreate(
                    ['stripe_plan' => $plan->default_price],
                    [
                        'name' => $plan->name,
                        'slug' => $plan->name,
                        'stripe_plan' => $plan->default_price,
                        'price' => $price->unit_amount / 100,
                        'interval' => $price->recurring->interval_count??0,
                        'description' => $plan->description,
                    ]
                );
            }
        }
        $this->info('Stripe products and price fetching completed.');
    }
}
