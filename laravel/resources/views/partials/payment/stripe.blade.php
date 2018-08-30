<div class="modal fade" id="stripeFormModal" tabindex="-1" role="dialog" aria-labelledby="stripeFormModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('salon.payment') }}</h4>
            </div>
            {{ Form::open(array('route' => 'stripePayment', 'id' => 'stripePaymentForm')) }}
                {{ Form::hidden('booking_id', null, array('id' => 'stripePaymentId')) }}
                <div class="group">
                    <label>
                        <span>Name</span>
                        <input name="cardholder-name" class="field" placeholder="Jane Doe" />
                    </label>
                    <label>
                        <span>Phone</span>
                        <input class="field" placeholder="(123) 456-7890" type="tel" />
                    </label>
                </div>
                <div class="group">
                    <label>
                        <span>Card</span>
                        <div id="card-element" class="field"></div>
                    </label>
                </div>
                <div class="outcome">
                    <div class="error"></div>
                    <div class="success">
                        Success! Your Stripe token is <span class="token"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                    <button type="submit" class="btn confirm-payment">{{ trans('salon.submit_payment') }}</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>