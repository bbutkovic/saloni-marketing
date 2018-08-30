<div class="modal fade" id="calendarOptionsModal" tabindex="-1" role="dialog" aria-labelledby="calendarOptionsModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('salon.add_to_calendar') }}</h4>
            </div>
            <div class="modal-body">
                <div class="row calendar-export-options m-b">
                    <a id="googleCalendarLink" href="#"><button class="btn btn-default"><i class="fa fa-google"></i> {{ trans('salon.add_to_gcal') }}</button></a>
                    <a id="iCalLink" href="#"><button class="btn btn-default">{{ trans('salon.download_ics') }}</button></a>
                    <a id="yahooCalLink" href="#"><button class="btn btn-default"><i class="fa fa-yahoo"></i> {{ trans('salon.add_to_yahoo') }}</button></a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
            </div>
        </div>
    </div>
</div>