$(document).ready(function() {
    $('.online-payments-radio').on('change', function() {
        var value = $(this).val();

        if(value == 1) {
            //load supported payment options based on salon location
            $('.payment-options-wrap').removeClass('hidden');
            $.ajax({
                type: 'get',
                url: ajax_url + 'salon/payment-options',
                success: function(data) {
                    if(data.status === 1) {
                        if(data.payment_options['paypal'] === 1) {
                            $('#paypalPayment').removeClass('hidden')
                        }
                        if(data.payment_options['stripe'] === 1) {
                            $('#stripePayment').removeClass('hidden')
                        }
                        if(data.payment_options['wspay'] === 1) {
                            $('#wspayPayment').removeClass('hidden')
                        }
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        } else {
            $('.payment-options-wrap').addClass('hidden');
        }
    });

    $('.paypal-radio').on('change', function() {
        var value = $(this).val();
        if(value == 1) {
            $('.paypal-key-wrap').removeClass('hidden');
        } else {
            $('.paypal-key-wrap').addClass('hidden');
        }
    });

    $('.stripe-radio').on('change', function() {
        var value = $(this).val();
        if(value == 1) {
            $('.stripe-key-wrap').removeClass('hidden');
        } else {
            $('.stripe-key-wrap').addClass('hidden');
        }
    });

    $('.wspay-radio').on('change', function() {
        var value = $(this).val();
        if(value == 1) {
            $('.wspay-key-wrap').removeClass('hidden');
        } else {
            $('.wspay-key-wrap').addClass('hidden');
        }
    });
});

function submitPaymentSettings() {
    if($('.online-radio-on').is(':checked')) {
        var payments = 1;
    } else {
        var payments = 0;
    }
    if($('.paypal-radio-on').is(':checked')) {
        var paypal_status = 1;
    } else {
        var paypal_status = 0;
    }
    var paypal_public = $('#PayPalPublic').val();
    var paypal_private = $('#PayPalPrivate').val();
    if($('.stripe-radio-on').is(':checked')) {
        var stripe_status = 1;
    } else {
        var stripe_status = 0;
    }
    var stripe_public = $('#StripePublishableKey').val();
    var stripe_private = $('#StripeSecretKey').val();
    if($('.wspay-radio-on').is(':checked')) {
        var wspay_status = 1;
    } else {
        var wspay_status = 0;
    }
    var wspay_public = $('#WsPayShopId').val();
    var wspay_private = $('#WsPayPrivate').val();

    $.ajax({
        type: 'post',
        url: ajax_url + 'salon/payment/update',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {'payments':payments,'paypal_status':paypal_status,'stripe_status':stripe_status,'wspay_status':wspay_status,'paypal_public':paypal_public,'paypal_private':paypal_private,'stripe_public':stripe_public,'stripe_private':stripe_private,'wspay_public':wspay_public,'wspay_private':wspay_private},
        success: function(data) {
            if(data.status === 1) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }
    });
}
