<div class="modal fade" id="slowDayHour" tabindex="-1" role="dialog" aria-labelledby="slowDayHour" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.voucher') }}</h4>
            </div>
            {{ Form::open(array('route' => 'createNewVoucher', 'id' => 'voucherForm')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="row m-t">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="couponName">{{ trans('salon.coupon_name') }}</label>
                                {{ Form::text('coupon_name', null, array('id' => 'couponName','class' => 'form-control', 'required')) }}
                            </div>
                            <div class="form-group">
                                <label for="couponNumber">{{ trans('salon.coupon_amount') }}</label>
                                {{ Form::text('coupon_amount', null, array('id' => 'voucherAmount','class' => 'form-control', 'required')) }}
                            </div>
                            <div class="form-group">
                                <label for="discount">{{ trans('salon.coupon_discount') }}</label>
                                {{ Form::text('discount', null, array('id' => 'voucherDiscount', 'class' => 'form-control', 'required')) }}
                            </div>
                            <div class="form-group">
                                <label for="expireDate">{{ trans('salon.expire_date') }}</label>
                                {{ Form::text('expire_date', null, array('id' => 'expireDatePicker', 'class' => 'form-control datepicker', 'required')) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="submit" class="btn btn-primary">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

<div class="modal fade" id="editVoucherModal" tabindex="-1" role="dialog" aria-labelledby="editVoucherModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.voucher') }}</h4>
            </div>
            {{ Form::open(array('id' => 'editVoucher')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="row m-t">
                        <div class="col-xs-12">
                            {{ Form::hidden('voucher_id', null, array('id' => 'voucherId')) }}
                            <div class="form-group">
                                <label for="editCouponName">{{ trans('salon.coupon_name') }}</label>
                                {{ Form::text('coupon_name', null, array('id' => 'editCouponName','class' => 'form-control', 'required')) }}
                            </div>
                            <div class="form-group">
                                <label for="editVoucherAmount">{{ trans('salon.coupon_amount') }}</label>
                                {{ Form::text('coupon_amount', null, array('id' => 'editVoucherAmount','class' => 'form-control', 'required')) }}
                            </div>
                            <div class="form-group">
                                <label for="editExpireDate">{{ trans('salon.expire_date') }}</label>
                                {{ Form::text('expire_date', null, array('id' => 'editExpireDate', 'class' => 'form-control datepicker', 'required')) }}
                            </div>
                            <div class="form-group">
                                <label for="editVoucherDiscount">{{ trans('salon.coupon_discount') }}</label>
                                {{ Form::text('discount', null, array('id' => 'editVoucherDiscount', 'class' => 'form-control', 'required')) }}
                            </div>
                            <div class="form-group">
                                <label for="editVoucherCode">{{ trans('salon.promo_code') }}</label>
                                {{ Form::text('code', null, array('id' => 'editVoucherCode', 'class' => 'form-control', 'required')) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-primary" onclick="updateVoucher()">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>