@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                @if ($subscriptions->isNotEmpty())
                    <div class="card-header">Your subscriptions</div>
                @endif

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="row">
                        @forelse ( $subscriptions as $subscription )
                            <div class="col-md-4">
                                    <div class="card mb-4">
                                        <div class="card-header">
                                            Subscription: {{ $subscription->name }}
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">Status:
                                                @if (!empty($subscription->ends_at))
                                                    Cancelled
                                                @else
                                                    {{ $subscription->stripe_status }}
                                                @endif
                                            </h5>
                                            @if (empty($subscription->ends_at))
                                            <h5 class="card-title">
                                                @if (!empty($subscription->trial_ends_at))
                                                    Trail EndDate: {{ $subscription->trial_ends_at }}
                                                @else
                                                    Start Date: {{ $subscription->created_at }}
                                                @endif
                                            </h5>
                                            @endif
                                            @if (!empty($subscription->ends_at))
                                            <h5 class="card-title">Cancelled At: {{ $subscription->ends_at }}</h5>
                                            @endif
                                            @if (empty($subscription->ends_at))
                                            <a href="{{ route('subscription-cancel', $subscription->id) }}" class="btn btn-primary pull-right">Cancel Subscription</a>
                                            @endif
                                    </div>
                                    </div>
                                </div>
                            @empty
                            @include('stripe.index', ['plans' => $plans])
                        @endforelse()
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
