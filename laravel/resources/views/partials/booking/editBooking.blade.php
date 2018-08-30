<div class="modal fade" id="addCustomerNote" tabindex="-1" role="dialog" aria-labelledby="addCustomerNote" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('salon.add_customer_note') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addClientNote', 'method' => 'post', 'id' => 'addCustomerNote')) }}
                {{ Form::hidden('booking_id', null, array('id' => 'bookingNoteId')) }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label for="customerNote">{{ trans('salon.customer_note') }}</label>
                                {{ Form::textarea('customer_note', null, array('id' => 'customerNoteArea', 'class' => 'form-control')) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                    <button type="submit" class="btn btn-success">{{ trans('salon.submit') }}</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

