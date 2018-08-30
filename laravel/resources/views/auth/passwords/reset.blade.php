<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ trans('auth.app_name') }}</title>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/css/font-awesome.min.css') }}

    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/custom.css') }}
    {{ HTML::style('css/plugins/toastr/toastr.min.css') }}

    {{ HTML::script('js/jquery-3.1.1.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/plugins/toastr/toastr.min.js') }}

</head>

<body class="gray-bg">

<section id="login-section">
    <div class="middle-box text-center loginscreen   animated fadeInDown">
        <div class="wrapper wrapper-content">
            <div class="ibox float-e-margins">
                <div class="ibox-title login-wrapper">
                    <h1 class="logo-name text-uppercase">{{ trans('auth.login') }}</h1>
                    <div> 
                        {{ Form::open(array('route' => 'password.request', 'id' => 'login-form', 'autocomplete' => 'on', 'role' => 'form', 'class' => 'm-t')) }}
                        {{ Form::hidden('token', $token) }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <!--<label for="email" class="control-label">E-Mail Address</label>-->

                            <div>
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" placeholder="E-mail address" required autofocus>

                                @if($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <!--<label for="password" class="control-label">Password</label>-->
                            <div>
                                <input id="password" type="password" class="form-control" name="password" placeholder="New password" required>

                                @if($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <!--<label for="password-confirm" class="control-label">Confirm Password</label>-->
                            <div>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" required>
                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary">{{ trans('auth.reset_password') }}</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if (session('success_message'))
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.success("{{ session('success_message') }}");
        });
    </script>
@endif

@if (session('info_message'))
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.info("{{ session('info_message') }}");
        });
    </script>
@endif

@if (session('warning_message'))
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.warning("{{ session('warning_message') }}");
        });
    </script>
@endif

@if (session('error_message'))
    <script type="text/javascript">
        $(document).ready(function() {
            toastr.error("{{ session('error_message') }}");
        });
    </script>
@endif

</body>

</html>