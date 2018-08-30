<div class="modal inmodal fade" id="invoiceModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="invoice-modal m">
                <div class="row">
                    <div class="col-sm-6">
                        <h5>{{ trans('salon.from') }}:</h5>
                        <address class="company-address"></address>
                    </div>

                    <div class="col-sm-6 text-right">
                        {{--<h4>Invoice No.</h4>
                        <h4 class="text-navy">INV-000567F7-00</h4>--}}
                        <span>To:</span>
                        <address class="client-address"></address>
                        <p>
                            <span><strong>Invoice Date:</strong> <span class="invoice-date"></span></span><br/>
                        </p>
                    </div>
                </div>

                <div class="table-responsive m-t">
                    <table class="table invoice-table">
                        <thead>
                        <tr>
                            <th>{{ trans('salon.item_list') }}</th>
                            <th>{{ trans('salon.quantity') }}</th>
                            <th>{{ trans('salon.base_price') }}</th>
                            <th>{{ trans('salon.tax') }}</th>
                            <th>{{ trans('salon.total_price') }}<br>{{ trans('salon.total_price_desc') }}</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <table class="table invoice-total">
                    <tbody>
                    <tr>
                        <td><strong>Sub Total :</strong></td>
                        <td class="base-price">$1026.00</td>
                    </tr>
                    <tr>
                        <td><strong>TAX :</strong></td>
                        <td class="tax">$235.98</td>
                    </tr>
                    <tr>
                        <td><strong>TOTAL :</strong></td>
                        <td class="total-price">$1261.98</td>
                    </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
