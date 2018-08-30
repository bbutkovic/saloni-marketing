<div class="modal fade" id="showAppointmentActions" tabindex="-1" role="dialog" aria-labelledby="showAppointmentActions" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="ibox-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('salon.appointment_options') }}</h4>
                </div>
                <div class="modal-body">
                    <div id="appointmentOptionsModal">
                        <div class="row appointment-info">
                            <a class="customer-link" href="#"><h2 class="customer-name"></h2></a>
                            <h3 class="client-note"></h3>
                            <h3 class="service-data"></h3>
                            <h3 class="service-price"></h3>
                            <h3 class="service-date"></h3>
                            <h3 class="service-creation-info"></h3>
                            <br>
                            <h3 class="client-email"></h3>
                            <h3 class="client-phone"></h3>
                            <h3 class="client-address"></h3>
                            <h3 class="client-referrer"></h3>
                        </div>
                        <div class="row calendar-actions m-b">
                            <a id="googleCalendarLink" href="#"><button class="btn btn-default"><i class="fa fa-google"></i> {{ trans('salon.add_to_gcal') }}</button></a>
                            <a id="iCalLink" href="#"><button class="btn btn-default">{{ trans('salon.download_ics') }}</button></a>
                            <a id="yahooCalLink" href="#"><button class="btn btn-default"><i class="fa fa-yahoo"></i> {{ trans('salon.add_to_yahoo') }}</button></a>
                        </div>
                        <h2 class="status-schedule"></h2>
                        <hr>
                        <div class="row appointment-actions">
                            <p class="hidden hidden-booking-id"></p>
                            <div class="col-lg-6 col-md-6">
                                <div class="actions-wrap">
                                    <label>{{ trans('salon.actions') }}</label>
                                    <button id="createInvoice" type="button" class="btn btn-default">{{ trans('salon.create_invoice') }}</button>
                                    <button id="addClientNote" type="button" class="btn btn-default">{{ trans('salon.client_note') }}</button>
                                    <div class="appointment-status">
                                        <button type="button" class="btn btn-danger" data-status="delete">{{ trans('salon.delete_booking') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 appointment-status">
                                <div class="actions-wrap">
                                    <label>{{ trans('salon.change_status') }}</label>
                                    <button type="button" class="btn btn-default" data-status="status_arrived">{{ trans('salon.arrived') }}</button>
                                    <button type="button" class="btn btn-default" data-status="status_cancelled">{{ trans('salon.booking_cancel') }}</button>
                                    <button type="button" class="btn btn-default" data-status="status_noshow">{{ trans('salon.no_show') }}</button>
                                    <button type="button" class="btn btn-default" data-status="status_complete">{{ trans('salon.finish_booking') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="appointmentSelectModal" class="hidden">
                        <h5 class="text-muted">{{ trans('salon.select_services_action') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>