@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Choose Subscription plan:</div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($plans as $plan)
                                <div class="col-md-4">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            @if ($plan->interval >= 1 && $plan->interval <= 12)
                                                ${{ $plan->price }}/Mo
                                            @else
                                                ${{ $plan->price }}/Year
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $plan->name }}</h5>
                                            <p class="card-text">{{ $plan->description }}
                                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                                                Lorem Ipsum has been the industry's standard dummy text ever since,
                                            </p>
                                            @if ($plan->interval >= 1)
                                            <a href="{{ route('plans.show', $plan->id) }}"
                                                class="btn btn-primary pull-right">Choose</a>
                                            @else
                                            <a href="{{ route('payment') }}"
                                                class="btn btn-primary pull-right">Choose</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
