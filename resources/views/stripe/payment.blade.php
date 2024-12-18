@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        One time Payment
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-body">
                        @if (session('message'))
                            <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                        @endif
                        <form method="POST" action="{{ route('process-payment') }}" class="card-form mt-3 mb-3">
                            @csrf
                            <input type="hidden" name="payment_method" class="payment-method">
                            <input type="number" name="amount" placeholder="Enter amount">
                            <input class="StripeElement mb-3" name="card_holder_name" placeholder="Card holder name"
                                required>
                            <div class="col-lg-4 col-md-6">
                                <div id="card-element"></div>
                            </div>
                            <div id="card-errors" role="alert"></div>
                            <div class="form-group mt-3">
                                <button type="submit" class="btn btn-primary pay">
                                    Purchase
                                </button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://js.stripe.com/v3/"></script>
    <script>
        let stripe = Stripe("{{ config('cashier.key') }}")
        let elements = stripe.elements()
        let style = {
            base: {
                color: '#32325d',
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: 'antialiased',
                fontSize: '16px',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
        let card = elements.create('card', {
            style: style
        })
        card.mount('#card-element')
        let paymentMethod = null
        $('.card-form').on('submit', function(e) {
            $('button.pay').attr('disabled', true)
            if (paymentMethod) {
                return true
            }
            stripe.confirmCardSetup(
                "{{ $intent->client_secret }}", {
                    payment_method: {
                        card: card,
                        billing_details: {
                            name: $('.card_holder_name').val()
                        }
                    }
                }
            ).then(function(result) {
                if (result.error) {
                    $('#card-errors').text(result.error.message)
                    $('button.pay').removeAttr('disabled')
                } else {
                    paymentMethod = result.setupIntent.payment_method
                    $('.payment-method').val(paymentMethod)
                    $('.card-form').submit()
                }
            })
            return false
        })
    </script>
@endsection
