<!DOCTYPE html>
<html lang="en">

<head>
    <title>Stripe Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <button id="checkout-button" data-borrow-id="{{ $borrow->uuid }}" data-penalty ="{{ $penalty }}">Pay Late Fee</button>
    <h4>Amount: {{ $penalty }}</h4>

    <script type="text/javascript">
        const stripe = Stripe('{{config('services.stripe.public')}}');
        document.getElementById('checkout-button').addEventListener('click', async (e) => {
            const borrowId = e.target.getAttribute('data-borrow-id');
            const penaltys = e.target.getAttribute('data-penalty');
            borrowIds = 800;
            try {
                const response = await fetch(`/create-checkout-session/${borrowId}/${penaltys}`, {
                    method: "GET",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-Token": "{{ csrf_token() }}"
                    }
                });
                const session = await response.json();
                if (session.id) {
                    stripe.redirectToCheckout({
                        sessionId: session.id
                    });
                } else {
                    console.error("Failed to create a session:", session.error);
                    alert("There was an error. Please try again.");
                }
            } catch (error) {
                console.error("Error occurred:", error);
                alert("An unexpected error occurred. Please try again.");
            }
        });
    </script>
</body>

</html>
