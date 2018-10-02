@extends('main')


@section('styles')
{{ HTML::style('css/plugins/spectrum/spectrum.css') }}
{{ HTML::style('css/plugins/duallistbox/duallistbox.css') }}
@endsection

@section('scripts')
{{ HTML::script('js/plugins/spectrum/spectrum.js') }}
{{ HTML::script('js/plugins/dataTables/datatables.min.js') }}
{{ HTML::script('js/salon/salonService.js') }}
{{ HTML::script('js/plugins/nestable/nestable.js') }}
{{ HTML::script('js/plugins/duallistbox/duallistbox.js') }}
{{ HTML::script('js/plugins/sortable/html5sortable.min.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.location_services') }}</h2>
            <a href="#" class="btn btn-default new-location-btn new-service-btn pull-right"><i class="fa fa-plus"></i> {{ trans('salon.add_new_service') }}</a>
            <select id="importFromLocation" class="btn btn-default import-services-btn pull-right">
                <option value="0" disabled selected>{{ trans('salon.import_services') }}</option>
                @foreach($salon->locations as $single_location)
                    @if($single_location->id != $location->id)
                        <option value="{{ $single_location->id }}">{{ $single_location->location_name }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
    
    <div id="location-options" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class="active"><a data-toggle="tab" href="#tab-1">{{ trans('salon.service_options') }}</a></li>
                    <li id="tab-2-li" class=""><a data-toggle="tab" href="#tab-2">{{ trans('salon.service_order') }}</a></li>
                    <li id="tab-3-li" class=""><a data-toggle="tab" href="#tab-3">{{ trans('salon.service_discounts') }}</a></li>
                </ul>
                <div class="tab-content services-options">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <div class="col-lg-6">
                                    <h1>{{ trans('salon.location_categories') }}</h1>
                                    <div class="ibox-content service-overview">
                                        <div class="dd" id="nestable">
                                            <ol class="dd-list">
                                                @foreach($category_list as $category)
                                                <li class="dd-item" data-id="{{ $category->id }}">
                                                    <div class="dd-handle" style="background-color: {{ $category->cat_color }}; color: #fff">{{ $category->name }}</div>
                                                    <ol class="dd-list">
                                                        <li class="dd-item" data-id="null">
                                                            <div class="dd-handle">{{ trans('salon.without_group') }}</div>
                                                            <ol class="dd-list">
                                                                @foreach($no_group_services as $service_no_group)
                                                                    @if($service_no_group['category_id'] === $category->id)
                                                                    <li class="dd-item service-no-group" data-id="{{ $service_no_group['service_id'] }}">
                                                                        <div class="dd-handle">
                                                                            <p class="pull-left">{{ $service_no_group['name'] }}</p>
                                                                            <div class="pull-right">
                                                                                <a href="#" onclick="editService({{ $service_no_group['service_id'] }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.edit_service') }}"><i class="fa fa-pencil"></i></a>
                                                                                <a href="#" onclick="editStaff({{ $service_no_group['service_id'] }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.add_staff_to_service') }}"><i class="fa fa-users"></i></a>
                                                                                <a href="#" onclick="deleteServiceById({{ $service_no_group['service_id'] }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.delete_service') }}"><i class="fa fa-trash"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    @endif
                                                                @endforeach
                                                            </ol>
                                                        </li>
                                                        @foreach($category->group as $group)
                                                        <li class="dd-item" data-id="{{ $group->id }}">
                                                            <div class="dd-handle" style="background-color: {{ $group->group_color }}; color: #fff">{{ $group->name }}</div>
                                                            <ol class="dd-list">
                                                                @foreach($group->service as $service)
                                                                    @if($service->sub_group == null)
                                                                    <li class="dd-item" data-id="{{ $service->id }}">
                                                                        <div class="dd-handle" style="background-color: {{ $group->group_color }}; color: #fff">
                                                                            <p class="pull-left">{{ $service->service_details->name }}</p>
                                                                            <div class="pull-right">
                                                                                <a href="#" onclick="editService({{ $service->id }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.edit_service') }}"><i class="fa fa-pencil"></i></a>
                                                                                <a href="#" onclick="editStaff({{ $service->id }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.add_staff_to_service') }}"><i class="fa fa-users"></i></a>
                                                                                <a href="#" onclick="deleteServiceById({{ $service->id }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.delete_service') }}"><i class="fa fa-trash"></i></a>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    @endif
                                                                @endforeach
                                                            </ol>
                                                            <ol class="dd-list dd-service">
                                                                @foreach($group->subcategory as $subcategory)
                                                                <li class="dd-item" data-id="{{ $subcategory->id }}">
                                                                    <div class="dd-handle" style="background-color: {{ $subcategory->subgroup_color }}; color: #fff">{{ $subcategory->name }}</div>
                                                                    <ol class="dd-list">
                                                                    @foreach($group->service as $service)
                                                                        @if($service->sub_group === $subcategory->id)
                                                                        <li class="dd-item" data-id="{{ $service->id }}">
                                                                            <div class="dd-handle" style="background-color: {{ $subcategory->subgroup_color }}; color: #fff">
                                                                                <p class="pull-left">{{ $service->service_details->name }}</p>
                                                                                <div class="pull-right">
                                                                                    <a href="#" onclick="editService({{ $service->id }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.edit_service') }}"><i class="fa fa-pencil"></i>
                                                                                    <a href="#" onclick="editStaff({{ $service->id }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.add_staff_to_service') }}"><i class="fa fa-users"></i></a></a>
                                                                                    <a href="#" onclick="deleteServiceById({{ $service->id }})" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('salon.delete_service') }}"><i class="fa fa-trash"></i></a>
                                                                                </div>
                                                                            </div>
                                                                        </li>
                                                                        @endif
                                                                    @endforeach
                                                                    </ol>
                                                                </li>
                                                                @endforeach
                                                            </ol>
                                                        </li>
                                                        @endforeach
                                                    </ol>
                                                </li>
                                                @endforeach
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h1>{{ trans('salon.edit_categories') }}</h1>
                                    <small class="text-muted m-b">{{ trans('salon.edit_categories_desc') }}</small>
                                    <div class="row">
                                        <a href="#" class="btn btn-default new-category-btn m-l"><i class="fa fa-plus"></i> {{ trans('salon.add_new_category') }}</a>
                                    </div>
                                    <div class="ibox-content">
                                        <div class="list-group">
                                            @foreach($category_list as $category)
                                            <a href="#" class="list-group-item active" onclick="categoryEdit({{ $category->id }})" style="background-color: {{ $category->cat_color }}">{{ $category->name }}</a>
                                                <a href="#" class="list-group-item new-group" data-id="{{ $category->id }}"><i class="fa fa-plus"></i> {{ trans('salon.add_new_group') }}</a>
                                                @foreach($category->group as $group)
                                                <a href="#" class="list-group-item" onclick="editGroup({{ $group->id }})">{{ $group->name }}</a>
                                                    <a href="#" class="list-group-item new-subcategory m-l" data-id="{{ $group->id }}"><i class="fa fa-plus"></i> {{ trans('salon.add_new_subcategory') }}</a>
                                                    @foreach($group->subcategory as $subcategory)
                                                    <a href="#" class="list-group-item m-l" onclick="editSubCategory({{ $subcategory->id }})">{{ $subcategory->name }}</a>
                                                    @endforeach
                                                @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="ibox-content">
                                <h2 class="text-muted">{{ trans('salon.drag_service') }}</h2>
                                <div class="col-lg-6">
                                    <ul class="js-sortable-connected list list-reset">
                                    @foreach($category_list as $category)
                                        @foreach($category->group as $group)
                                        @if($group->service->isNotEmpty())
                					    <li class="p1 mb1 dd-handle"><strong>{{ $group->name }}</strong> ({{ $category->name }})</li>
                    					    @foreach($group_services as $service)
                        					    @if($service['group_id'] === $group->id)
                        					    <ul class="js-sortable-inner-connected list list-reset mb0 py1">
                        							<li class="p1 mb1 item dd-handle" data-group="{{ $group->id }}" data-subgroup="null" data-id="{{ $service['service_id'] }}"><span class="js-inner-handle px1"><i class="fa fa-arrows-v drag-indicator"></i></span>{{ $service['name'] }}</li>
                        						</ul>
                        						@endif
                    						@endforeach
                    						@foreach($group->subcategory as $subgroup)
                                                @if($subgroup->service->isNotEmpty())
                        					    <li class="p1 mb1 dd-handle"><strong>{{ $subgroup->name }}</strong> ({{ $category->name }})</li>
                            					    @foreach($subgroup_services as $service)
                                					    @if($service['group_id'] === $group->id && $service['subgroup_id'] === $subgroup->id)
                                					    <ul class="js-sortable-inner-connected list py1">
                                							<li class="p1 mb1 item dd-handle" data-group="{{ $group->id }}" data-subgroup="{{ $subgroup->id }}" data-id="{{ $service['service_id'] }}"><span class="js-inner-handle px1"><i class="fa fa-arrows-v drag-indicator"></i></span>{{ $service['name'] }}</li>
                                						</ul>
                                						@endif
                            						@endforeach
                            					@endif
                        				    @endforeach
                    					@endif
                					    @endforeach
                                        <li class="p1 mb1 dd-handle"><strong>{{ trans('salon.without_group') }}</strong> ({{ $category->name }})</li>
                                            @foreach($no_group_services as $service)
                                                @if($service['category_id'] === $category->id)
                                                    <ul class="js-sortable-inner-connected list list-reset mb0 py1">
                                                        <li class="p1 mb1 item dd-handle" data-subgroup="null" data-id="{{ $service['service_id'] }}"><span class="js-inner-handle px1"><i class="fa fa-arrows-v drag-indicator"></i></span>{{ $service['name'] }}</li>
                                                    </ul>
                                                @endif
                                            @endforeach
                					@endforeach
                				    </ul>
                				    
                				    <button class="js-serialize-button btn btn-success">{{ trans('salon.submit') }}</button>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">
                            <div class="ibox-content m-b">
                                <small class="text-muted m-t m-b">{{ trans('salon.service_discount_desc') }}</small>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th class="text-center">{{ trans('salon.service') }}</th>
                                                <th class="text-center">{{ trans('salon.allow_discounts') }}</th>
                                                <th class="text-center">{{ trans('salon.award_points') }}</th>
                                                <th class="text-center">{{ trans('salon.points_awarded') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($service_list as $service)
                                                <tr>
                                                    <td class="text-center">{{ $service['service_name'] }} <br> {{ $service['category']['name'] }} {{ !empty($service['group']) ? ' - ' .  $service['group']['name'] : '' }}</td>
                                                    <td class="text-center">
                                                        <input class="service-discounts" @if($service['allow_discounts'] === 1) checked @endif type="checkbox" name="{{ $service['id'] }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input class="service-points-check" @if($service['award_points'] === 1) checked @endif type="checkbox" name="{{ $service['id'] }}">
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="text" name="{{ $service['id'] }}" @if(isset($service['points_awarded'])) value="{{ $service['points_awarded'] }}" @else value="0" @endif id="servicePoints{{ $service['id'] }}" class="form-control service-points">
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">
                                    <button type="button" class="btn btn-success m-l" onclick="updateServiceLoyalty()">{{ trans('salon.update') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

@include('partials.services.newService')
@include('partials.services.newCategory')
@include('partials.services.newSubCategory')
@include('partials.services.newGroup')
@include('partials.services.importServices')

<script>

    var service_deleted = '{{ trans('salon.delete_failed') }}';
    var delete_failed = '{{ trans('salon.delete_failed') }}';
    var select_group_s = '{{ trans('salon.select_group_s') }}';
    var select_subgroup_s = '{{ trans('salon.select_subgroup_s') }}';
    var error_updating = '{{ trans('salon.error_updating') }}';
    var order_updated = '{{ trans('salon.order_updated') }}';
    var trans_points_awarded = '{{ trans('salon.points_awarded') }}';
    var trans_delete_check = '{{ trans('salon.trans_delete_check') }}';
    var sortOrder = [];

    var cat_colors = [];
    @foreach($colors['cat_colors'] as $cat_color)
        cat_colors.push('{{ $cat_color }}');
    @endforeach

    var group_colors = [];
    @foreach($colors['group_colors'] as $group_color)
        group_colors.push('{{ $group_color }}');
    @endforeach

    var subgroup_colors = [];
    @foreach($colors['subgroup_colors'] as $subgroup_color)
        subgroup_colors.push('{{ $subgroup_color }}');
    @endforeach

	sortable('.js-sortable-inner-connected', {
		forcePlaceholderSize: true,
		connectWith: 'js-sortable-inner-connected',
		items: '.item',
		placeholderClass: 'border border-white bg-orange mb1',
	});

    $('.js-serialize-button').on('click', function() {
        sortOrder = [];
        
        $.each($('ul.js-sortable-inner-connected li'), function(index, val) {

            sortOrder.push({'group': $(this).data('group'), 'subgroup': $(this).data('subgroup'), 'id': $(this).data('id')});
        });
        
        $.ajax({
          type: 'post',
          url: ajax_url + 'ajax/change-order',
          dataType: 'json',
          beforeSend: function(request) {
             return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
          },
          data: {'order': sortOrder},
          success: function(data) {
             if(data.status === 1) {
                window.location.reload();
             } else {
                toastr.error(error_updating);
             }
          }
        });
    });
    
	
</script>
@endsection

