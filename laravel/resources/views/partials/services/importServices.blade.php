<div class="modal fade" id="importServicesModal" tabindex="-1" role="dialog" aria-labelledby="importServices" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="importServicesTitle">{{ trans('salon.select_services_to_import') }}</h4>
            </div>
            {{ Form::open(array('route' => 'importServices', 'id' => 'importServicesForm')) }}
            <div class="modal-body">
                <div class="row services-list">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('salon.close') }}</button>
                <button type="button" class="btn btn-success" onclick="submitServices()">{{ trans('salon.submit') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>