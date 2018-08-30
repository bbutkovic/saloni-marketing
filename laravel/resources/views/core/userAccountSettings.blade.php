@extends('main')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endsection

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="section-heading text-center">{{ trans('main.account_settings_profile') }}</h2>
    </div>
</div>

<div class="user-settings-wrapper">
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('main.user_account_settings') }}</h5>
                        {{ Form::open(array('route' => 'updateUser', 'id' => 'account-settings', 'class' => 'm-t')) }}
                        {{ Form::hidden('user_id', $user->id) }}
                        @if($user->facebook_id == null)
                        <div class="form-group">
                            <label for="sign_in_email">{{ trans('main.email_address') }}</label>
                            {{ Form::email('email', $user->email, array('id' => 'sign_in_email', 'class' => 'form-control', 'required')) }}
                            <small class="text-danger">{{ $errors->first('sign_in_email') }}</small>
                        </div>
                        @endif
                        <div class="form-group">
                            <label for="new_password">{{ trans('main.new_password') }}</label>
                            {{ Form::password('password', array('id' => 'password_new', 'class' => 'form-control', 'placeholder' => 'Password')) }}
                            <small class="text-danger">{{ $errors->first('password_new') }}</small>
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirm">{{ trans('auth.password_confirmation') }}</label>
                            {{ Form::password('password_confirmation', array('id' => 'new_password_confirm', 'class' => 'form-control', 'placeholder' => trans('auth.password_confirmation'))) }}
                            <small class="text-danger">{{ $errors->first('new_password_confirm') }}</small>
                        </div>

                        <button type="submit" class="btn btn-success">{{ trans('main.update_account') }}</button>
                        
                        {{ Form::close() }}
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{ trans('main.user_avatar') }}</h5>
                        <h6 class="text-muted text-center">{{ trans('main.minimum_size') }}100x100px</h6>
                        <img class="user-avatar" src="{{ URL::to('/').'/'.$user->user_extras->photo }}" alt="User profile picture">
                        {{ Form::open(array('route' => 'updateProfilePicture', 'id' => 'change-avatar', 'class' => 'm-t text-center', 'files' => 'true')) }}
                        
                        {{ Form::hidden('user_id', $user->id) }}
                        <div class="row">
                            <img class="salon-logo-img" @if(isset($salon->logo)) ? src="{{ URL::to('/').'/images/salon-logo/'.$salon->logo }}" : src="{{ URL::to('/').'/images/user_placeholder.png' }}" @endif>
                            <label class="text-center" id="new-image-label" for="image-file">{{ trans('salon.update_salon_logo') }}</label>
                            <div class="image-container">
                                <input class="image-file" id="my-file" type="file" name="user_avatar">
                                <label tabindex="0" for="my-file" class="image-change">{{ trans('salon.select_file') }}</label>
                            </div>
                            <small class="text-danger">{{ $errors->first('user_avatar') }}</small>
                        </div>
                        <button type="submit" class="btn btn-success m-t">{{ trans('main.update_account') }}</button>
                        
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        
                        <h5>{{ trans('main.profile_settings') }}</h5>
                        
                        {{ Form::open(array('route' => 'updateUserProfile', 'id' => 'profile-settings', 'class' => 'm-t')) }}
                        {{ Form::hidden('user_id', $user->id) }}
                        <div class="form-group">
                            <label for="update-first-name">{{ trans('auth.first_name') }}</label>
                            {{ Form::text('first_name', $user->user_extras->first_name, array('id' => 'update-first-name', 'class' => 'form-control')) }}
                        </div>
                        
                        <div class="form-group">
                            <label for="update-last-name">{{ trans('auth.last_name') }}</label>
                            {{ Form::text('last_name', $user->user_extras->last_name, array('id' => 'update-last-name', 'class' => 'form-control')) }}
                        </div>

                        <div class="form-group">
                            <label for="update-birthday">{{ trans('salon.birthday') }}</label>
                            {{ Form::text('birthday', $user->user_extras->birthday, array('id' => 'update-birthday', 'class' => 'form-control date-picker', 'placeholder' => trans('main.select_birthday'))) }}
                        </div>
                        
                        <div class="form-group">
                            <label for="update-phone_number">{{ trans('main.phone_number') }}</label>
                            {{ Form::text('phone', $user->user_extras->phone_number, array('id' => 'update-phone_number', 'class' => 'form-control', 'placeholder' => trans('main.phone_number'))) }}
                        </div>
                        
                        <div class="form-group">
                            <label for="update-address">{{ trans('main.address') }}</label>
                            {{ Form::text('address', $user->user_extras->address, array('id' => 'update-address', 'class' => 'form-control', 'placeholder' => trans('main.address'))) }}
                        </div>
                        
                        <div class="form-group">
                            <label for="update-city">{{ trans('main.city') }}</label>
                            {{ Form::text('city', $user->user_extras->city, array('id' => 'update-city', 'class' => 'form-control', 'placeholder' => trans('main.city'))) }}
                        </div>
                        
                        <button type="submit" class="btn btn-success">{{ trans('main.update_profile') }}</button>
                        
                        {{ Form::close() }}
                        
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <div class="row text-center">
                            <button class="btn btn-danger" onclick="deleteUserAccount({{ $user->id }})">{{ trans('salon.delete_account') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var prompt = '{{ trans('salon.are_you_sure') }}';
    var delete_user_account = '{{ route('deleteUserAccount') }}';

    $(".date-picker").each(function() {
        flatpickr(this, {
            enableTime: false,
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
    });
</script>
@endsection