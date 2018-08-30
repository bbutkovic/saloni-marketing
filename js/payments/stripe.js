var stripe = Stripe(stripe_key);

var elements = stripe.elements();

var card = elements.create('card', {
    style: {
        base: {
            iconColor: '#666EE8',
            color: '#31325F',
            lineHeight: '40px',
            fontWeight: 300,
            fontFamily: 'Helvetica Neue',
            fontSize: '15px',

            '::placeholder': {
                color: '#CFD7E0',
            },
        },
    }
});

card.mount('#card-element');

function setOutcome(result) {
    var successElement = document.querySelector('.success');
    var errorElement = document.querySelector('.error');
    successElement.classList.remove('visible');
    errorElement.classList.remove('visible');

    if (result.token) {
        // Use the token to create a charge or a customer
        // https://stripe.com/docs/charges
        successElement.querySelector('.token').textContent = result.token.id;
        successElement.classList.add('visible');

        stripeTokenHandler(result.token);

    } else if (result.error) {
        errorElement.textContent = result.error.message;
        errorElement.classList.add('visible');
    }
}

card.on('change', function(event) {
    setOutcome(event);
});

document.getElementById('stripePaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var form = document.getElementById('stripePaymentForm');
    var extraDetails = {
        name: form.querySelector('input[name=cardholder-name]').value,
    };
    stripe.createToken(card, extraDetails).then(setOutcome);
});

function stripeTokenHandler(token) {
    // Insert the token ID into the form so it gets submitted to the server
    console.log(token);
    var form = document.getElementById('stripePaymentForm');
    var hiddenInput = document.createElement('input');
    hiddenInput.setAttribute('type', 'hidden');
    hiddenInput.setAttribute('name', 'stripeToken');
    hiddenInput.setAttribute('value', token.id);
    form.appendChild(hiddenInput);

    // Submit the form
    form.submit();
}