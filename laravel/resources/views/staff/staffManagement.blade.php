@extends('main')

@section('content')
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="section-heading pull-left">{{ trans('salon.staff_management') }}</h2>
        <button type="button" class="btn btn-success new-location-btn" data-toggle="modal" data-target="#newStaffMember"><i class="fa fa-plus"></i> {{ trans('salon.add_new_staff') }}</button>
    </div>
</div>
    
<div id="location-options" class="user-settings-wrapper">
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="staff-management-box ibox-content">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover d-table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>{{ trans('auth.first_name') }}</th>
                                    <th>{{ trans('auth.last_name') }}</th>
                                    <th>{{ trans('salon.location_mobile_phone') }}</th>
                                    <th>{{ trans('salon.staff_level') }}</th>
                                    <th>{{ trans('salon.staff_location') }}</th>
                                    <th>{{ trans('salon.options') }}</th>
                                </tr>
                                </thead>
                                <tbody class="staff-table">
                                @foreach($employees as $employee)
                                <tr class="staff-info" data-toggle="tooltip" data-id="user-{{$employee->id}}" data-placement="top" title="" data-original-title="{{ trans('main.view_profile') }}">
                                    <td onclick="visitProfile({{ $employee->id }})"><img class="staff-photo" src="{{ URL::to('/').$employee->user_extras->photo }}" alt="staff-photo"></td>
                                    <td onclick="visitProfile({{ $employee->id }})">{{ $employee->user_extras->first_name }}</td>
                                    <td onclick="visitProfile({{ $employee->id }})">{{ $employee->user_extras->last_name }}</td>
                                    <td onclick="visitProfile({{ $employee->id }})">{{ $employee->user_extras->phone_number }}</td>
                                    <td>
                                        @if($employee->roles[0]->id === 2)
                                        Salon administrator
                                        @else
                                        <select class="form-control user-role-{{ $employee->id }}" onchange="updateUserRole({{ $employee->id }})">
                                            <option value="9" selected disabled>{{ $employee->roles[0]->name }}</option>
                                            @foreach($user_roles as $key=>$user_role_single)
                                            <option value="{{ $key }}" class="staff-role">{{ $user_role_single }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </td>
                                    <td>
                                        @if($employee->roles[0]->id === 2)
                                        all
                                        @else
                                        <select class="form-control user-location-{{ $employee->id }}" onchange="updateUserLocation({{ $employee->id }})">
                                            <option value="0" selected disabled>{{ trans('salon.select_location') }}</option>
                                            @foreach($locations as $location)
                                            <option value="{{ $location->id }}" class="location-option" @if($employee->location_id == $location->id) ? selected : null @endif>{{ $location->location_name }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </td>
                                    <td class="user-options">
                                        @if($employee->roles[0]->id != 2)
                                        <a href="{{ route('getFullSchedule', $employee->id) }}" data-placement="top" title="" data-original-title="{{ trans('main.view_profile') }}">
                                            <i class="fa fa-pencil table-profile"></i>
                                        </a>
                                        <a href="#" data-id="user-{{ $employee->id }}" onclick="deleteUser({{ $employee->id }})">
                                            <i class="fa fa-trash table-delete"></i>
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('partials.newStaff')
<script>
    var user_deleted = '{{ trans('main.user_deleted') }}';
</script>
@endsection

@section('scripts')
{{ HTML::script('js/plugins/dataTables/datatables.min.js') }}
{{ HTML::script('js/salon/salonService.js') }}
@endsection