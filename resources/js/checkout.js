document.addEventListener('DOMContentLoaded', function () {
    const stripe = Stripe(stripeKey);
    const elements = stripe.elements();

    const cardElement = elements.create('card', {
        style: {
            base: {
                fontFamily: "'Nunito', sans-serif",
                fontSize: '15px',
                color: '#3D1C02',
                '::placeholder': { color: '#8B4513' }
            },
            invalid: { color: '#dc2626' }
        }
    });

    cardElement.mount('#card-element');

    cardElement.on('change', function (event) {
        const errorEl = document.getElementById('card-errors');
        errorEl.textContent = event.error ? event.error.message : '';
    });

    const form = document.getElementById('payment-form');
    const btnPagar = document.getElementById('btn-pagar');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        btnPagar.disabled = true;
        btnPagar.textContent = 'Procesando...';

        const { setupIntent, error } = await stripe.confirmCardSetup(clientSecret, {
            payment_method: { card: cardElement }
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
            btnPagar.disabled = false;
            btnPagar.textContent = 'Pagar';
        } else {
            document.getElementById('payment_method').value = setupIntent.payment_method;
            form.submit();
        }
    });
});
