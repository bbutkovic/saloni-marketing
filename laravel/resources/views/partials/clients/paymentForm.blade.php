<div class="modal fade" id="paymentFormModal" tabindex="-1" role="dialog" aria-labelledby="paymentFormModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.payment') }}</h4>
            </div>
            {{ Form::open(array('route' => 'payWithPaypal', 'id' => 'payment-form')) }}
            {{ Form::hidden('booking_id', null, array('id' => 'bookingPaymentId')) }}
            <div class="form-group services-list">
                <h4 class="text-center m-t">{{ trans('salon.payment_info') }}</h4>
            </div>
            <div class="payment-method-wrap">
                <hr>
                <h3 class="text-center">{{ trans('salon.choose_payment') }}</h3>
                <hr>
                <div class="form-group checkout-options text-center">
                    <a href="#" id="paypalPayment" class="payment-option m-r hidden" onclick="submitPayPalPayment()">
                        <img src="{{ URL::to('/').'/images/payment/PayPal.png' }}" alt="PayPal Checkout">
                    </a>
                    <a href="#" id="stripePayment" class="payment-option m-l hidden" onclick="submitStripePayment()">
                        <img src="{{ URL::to('/').'/images/payment/creditcards.jpg' }}" alt="Credit Cards Checkout">
                    </a>
                    <a href="#" id="wsPayPayment" class="payment-option m-l hidden" onclick="submitWsPayPayment()">
                        <img src="{{ URL::to('/').'/images/payment/creditcards.jpg' }}" alt="Credit Cards Checkout">
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@include('partials.payment.stripe')