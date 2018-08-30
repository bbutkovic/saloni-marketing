@extends('main')

@section('styles')
{{ HTML::style('css/plugins/dataTables/datatables.min.css') }}
{{ HTML::style('css/plugins/dataTables/datatables.min.css') }}
@endsection

@section('content')

<script>
    var change_perm_route = '{{ route("changePermission") }}';
</script>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="text-center section-heading">{{ trans('main.user_list') }}</h2>
    </div>
</div>

<div class="user-settings-wrapper">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab-1">{{ trans('main.user_list') }}</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-2">{{ trans('main.manage_role') }}</a></li>
                    <li class=""><a data-toggle="tab" href="#tab-3">{{ trans('main.add_new_admin') }}</a></li>
                </ul>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane active">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover d-table">
                                                <thead>
                                                <tr>
                                                    <th>{{ trans('salon.email') }}</th>
                                                    <th>{{ trans('salon.first_name') }}</th>
                                                    <th>{{ trans('salon.last_name') }}</th>
                                                    <th>{{ trans('salon.user_role') }}</th>
                                                    <th>{{ trans('main.user_actions') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody class="user-table">
                                                @foreach($users as $user)
                                                    @if(!$user->hasRole('superadmin'))
                                                    <tr class="user" data-id="user-{{ $user->id }}">
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->user_extras->first_name }}</td>
                                                        <td>{{ $user->user_extras->last_name }}</td>
                                                        <td>{{ $user->roles[0]->name }}</td>
                                                        <td class="user-options">
                                                            <a href="#" onclick="deleteUser({{ $user->id }})">
                                                                <i class="fa fa-trash table-delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('main.delete_user') }}"></i>
                                                            </a>
                                                            <a href="{{ route('signInAsUser', $user->id) }}">
                                                                <i class="fa fa-sign-in table-signin" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('main.sign_as') . ' ' . $user->user_extras->first_name}} "></i>
                                                            </a>
                                                            <a href="profile/{{$user->id}}">
                                                                <i class="fa fa-user table-profile" data-toggle="tooltip" data-placement="top" title="" data-original-title="{{ trans('main.view_profile') }}"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                    
                    <div id="tab-2" class="tab-pane">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                    <th class="text-center">{{ trans('main.permission_type') }}</th>
                                                    @foreach($permissions["manage-salon"] as $role=>$val)
                                                        @if($role != 1 && $role != 4)
                                                        <th class="text-center">{{ trans('main.'.$role) }}</th>
                                                        @endif
                                                    @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody class="user-table">
                                                    @foreach($permissions as $perm => $role)
                                                    <tr class="user">
                                                        <td class="text-center">{{trans('main.'.$perm)}}</td>
                                                        @foreach($role as $rola)
                                                        <td class="text-center">
                                                            <input data-role="{{ $rola['id'] }}" value="1" data-perm="{{ $perm }}" @if($rola['selected'] == 1) checked @endif class="permission-checkbox checkbox" type="checkbox">
                                                        </td>
                                                        @endforeach
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
                    
                    <div id="tab-3" class="tab-pane">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-title">
                                        <h3 class="text-center">{{ trans('main.new_user') }}</h3>
                                    </div>
                                    <div class="ibox-content">
                                        {{ Form::open(array('id' => 'new-user', 'class' => 'm-t', 'autocomplete' => 'off')) }}
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="email">{{ trans('main.email_address') }}*</label>
                                                {{ Form::email('email', null, array('id' => 'email_address', 'class' => 'form-control', 'required')) }}
                                            </div>
                                        
                                            <div class="form-group">
                                                <label for="password">{{ trans('auth.password') }}*</label>
                                                {{ Form::password('password', array('id' => 'password', 'class' => 'form-control', 'placeholder' => trans('auth.password'), 'required')) }}
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="new_password_confirm">{{ trans('auth.password_confirmation') }}*</label>
                                                {{ Form::password('password_confirm', array('id' => 'password_confirm', 'class' => 'form-control', 'placeholder' => trans('auth.password_confirmation'), 'required')) }}
                                            </div>
                                        </div>
                                        
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="first_name">{{ trans('auth.first_name') }}</label>
                                                {{ Form::text('first_name', null, array('id' => 'first_name', 'value' => '', 'class' => 'form-control', 'required')) }}
                                            </div>
                                        
                                            <div class="form-group">
                                                <label for="last_name">{{ trans('auth.last_name') }}</label>
                                                {{ Form::text('last_name', null, array('id' => 'last_name', 'value' => '', 'class' => 'form-control', 'required')) }}
                                            </div>
                                            
                                            <div class="form-group">
                                                <label for="last_name">{{ trans('main.select_role') }}*</label>
                                                <select id="select-role" class="form-control">
                                                    @foreach($user_roles as $role=>$val)
                                                        <option value="{{ $role }}">{{ $val }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <button type="button" id="add-new-user" class="btn btn-success">{{ trans('salon.submit') }}</button>
                                    
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{ HTML::script('js/plugins/dataTables/datatables.min.js') }}
{{ HTML::script('js/salon/salonUserManagement.js') }}
@endsection