<div class="modal fade" id="newService" tabindex="-1" role="dialog" aria-labelledby="newService" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_new_service') }}</h4>
            </div>
            {{ Form::open(array('route' => 'addNewService', 'id' => 'addNewService')) }}
            <div class="modal-body">
                <div class="row">
                    <div class="form-group text-center">
                        <label for="category_id">{{ trans('salon.select_category') }}</label>
                        <select name="category_id" class="form-control modal-select select-category" id="selectCategory" required>
                            <option value="" default>{{ trans('salon.select_category_s') }}</option>
                            @foreach($category_list as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group text-center group-block">
                        <label for="group_id">{{ trans('salon.select_group') }}</label>
                        <select name="group_id" class="form-control modal-select select-group" id="selectGroup">
                        </select>
                    </div>
                    <div class="form-group text-center subgroup-block">
                        <label for="subgroup_id">{{ trans('salon.select_subgroup') }}</label>
                        <select name="subgroup_id" class="form-control modal-select select-subgroup" id="selectSubGroup">
                        </select>
                    </div>
                    <hr>
                    <div class="col-lg-6">
                        <div class="form-group text-center">
                            <label for="service_name">{{ trans('salon.service_name') }}</label>
                            {{ Form::text('service_name', null, array('class' => 'form-control modal-input', 'required')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="service_desc">{{ trans('salon.service_desc') }}</label>
                            {{ Form::text('service_desc', null, array('class' => 'form-control modal-input')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="service_code">{{ trans('salon.service_code') }}</label>
                            {{ Form::text('service_code', null, array('class' => 'form-control modal-input input-service-code')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="service_barcode">{{ trans('salon.service_barcode') }}</label>
                            {{ Form::text('service_barcode', null, array('class' => 'form-control modal-input input-service-barcode')) }}
                        </div>
                    </div>
                    <div class="col-lg-6 services-alt">
                        <div class="form-group text-center">
                            <label for="service_length">{{ trans('salon.service_duration') }}</label>
                            {{ Form::select('service_length', ['00:05' => '5 min', '00:10' => '10 min', '00:15' => '15 min', '00:20' => '20 min', '00:25' => '25 min', '00:30' => '30 min', '00:35' => '35 min', '00:40' => '40 min', '00:45' => '45 min', '00:50' => '50 min', '00:55' => '55 min', '01:00' => '60 min', '01:15' => '1h 15 min', '01:30' => '1h 30 min', '01:45' => '1h 45 min', '02:00' => '2h'], null, array('class' => 'form-control modal-input', 'required')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="vat">{{ trans('salon.vat') }}</label>
                            {{ Form::text('vat', null, array('class' => 'form-control modal-input', 'required')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="service_cost">{{ trans('salon.service_cost') }}</label>
                            {{ Form::text('service_cost', null, array('class' => 'form-control modal-input', 'required')) }}
                        </div>
                        <div class="form-group">
                            <label for="service_available">{{ trans('salon.service_available') }}</label>
                            <input type="checkbox" name="service_available" id="serviceAvailable">
                        </div>
                        <div class="form-group">
                            <label for="service_available">{{ trans('salon.allow_discounts') }}</label>
                            <input type="checkbox" name="allow_discounts" id="allowDiscounts">
                        </div>
                        <div class="form-group">
                            <label for="service_available">{{ trans('salon.award_points') }}</label>
                            <input type="checkbox" name="award_points" id="awardPoints">
                        </div>
                        <div class="form-group points-awarded hidden">
                            <label for="pointsAwarded">{{ trans('salon.points_awarded') }}</label>
                            <input type="text" class="form-control" name="points_awarded" id="pointsAwarded">
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

<div class="modal fade" id="editService" tabindex="-1" role="dialog" aria-labelledby="editService" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.edit_service') }}</h4>
            </div>
            {{ Form::open(array('route' => 'editService', 'id' => 'editServiceForm')) }}
            {{ Form::hidden('service_id', null, array('id' => 'serviceId')) }}
            <div class="modal-body">
                <div class="row text-center">
                    <div class="form-group text-center">
                        <label for="category_id">{{ trans('salon.select_category') }}</label>
                        <select name="category_id" class="form-control modal-select select-category" id="editCategory">
                            <option value="0" selected disabled>{{ trans('salon.select_category_s') }}</option>
                            @foreach($category_list as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group text-center group-block">
                        <label for="group_id">{{ trans('salon.select_group') }}</label>
                        <select name="group_id" class="form-control modal-select select-group" id="editGroup">
                        </select>
                    </div>
                    <div class="form-group text-center subgroup-block">
                        <label for="subgroup_id">{{ trans('salon.select_subgroup') }}</label>
                        <select name="subgroup_id" class="form-control modal-select select-subgroup" id="editSubGroup">
                        </select>
                    </div>
                    <hr>
                    <div class="col-lg-6">
                        <div class="form-group text-center">
                            <label for="service_name">{{ trans('salon.service_name') }}</label>
                            {{ Form::text('service_name', null, array('class' => 'form-control edit-input', 'id' => 'editServiceName', 'required')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="service_desc">{{ trans('salon.service_desc') }}</label>
                            {{ Form::text('service_desc', null, array('class' => 'form-control edit-input', 'id' => 'editServiceDesc')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="service_code">{{ trans('salon.service_code') }}</label>
                            {{ Form::text('service_code', null, array('class' => 'form-control edit-input', 'id' => 'editServiceCode')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="service_barcode">{{ trans('salon.service_barcode') }}</label>
                            {{ Form::text('service_barcode', null, array('class' => 'form-control edit-input', 'id' => 'editServiceBarcode')) }}
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group text-center">
                            <label for="service_length">{{ trans('salon.service_duration') }}</label>
                            {{ Form::select('service_length', ['00:05' => '5 min', '00:10' => '10 min', '00:15' => '15 min', '00:20' => '20 min', '00:25' => '25 min', '00:30' => '30 min', '00:35' => '35 min', '00:40' => '40 min', '00:45' => '45 min', '00:50' => '50 min', '00:55' => '55 min', '01:00' => '60 min', '01:15' => '1h 15 min', '01:30' => '1h 30 min', '01:45' => '1h 45 min', '02:00' => '2h'], null, array('class' => 'form-control edit-input', 'id' => 'editServiceDuration', 'required')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="vat">{{ trans('salon.vat') }}</label>
                            {{ Form::text('vat', null, array('id' => 'serviceVat', 'class' => 'form-control modal-input', 'required')) }}
                        </div>
                        <div class="form-group text-center">
                            <label for="service_cost">{{ trans('salon.service_cost') }}</label>
                            {{ Form::text('service_cost', null, array('class' => 'form-control edit-input', 'id' => 'editServicePrice', 'required')) }}
                        </div>
                        <div class="form-group">
                            <label for="service_available">{{ trans('salon.service_available') }}</label>
                            <input type="checkbox" name="service_available" id="editServiceAvailability">
                        </div>
                        <div class="form-group">
                            <label for="service_available">{{ trans('salon.allow_discounts') }}</label>
                            <input type="checkbox" name="allow_discounts" id="editAllowDiscounts">
                        </div>
                        <div class="form-group">
                            <label for="service_available">{{ trans('salon.award_points') }}</label>
                            <input type="checkbox" name="award_points" id="editAwardPoints">
                        </div>
                        <div class="form-group">
                            <label for="pointsAwarded">{{ trans('salon.points_awarded') }}</label>
                            <input type="text" class="form-control" name="points_awarded" id="editPointsAwarded" required>
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

<div class="modal fade" id="editStaff" tabindex="-1" role="dialog" aria-labelledby="editStaff" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ trans('salon.add_staff_to_service') }}</h4>
            </div>
            {{ Form::open(array('route' => 'editServiceStaff', 'id' => 'editStaffService')) }}
            {{ Form::hidden('service_id', null, array('id' => 'serviceNum')) }}
            <div class="modal-body">
                <div id="dual-list-box" class="form-group row">
                    <select multiple="multiple" class="dual-listbox" name="selected_staff[]">
                    </select>
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