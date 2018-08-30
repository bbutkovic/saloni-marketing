<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ trans('auth.app_name') }}</title>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/css/font-awesome.min.css') }}
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/custom.css') }}
    {{ HTML::style('css/plugins/toastr/toastr.min.css') }}

</head>

<body class="gray-bg" data-lang="en">

<section id="login-section">
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div class="wrapper wrapper-content">
            <div class="ibox float-e-margins">
                <div class="ibox-title login-wrapper">
                    <h1 class="logo-name text-uppercase">{{ trans('auth.login') }}</h1>
                    <div> 
                        {{ Form::open(array('route' => 'postLogin', 'id' => 'login-form', 'autocomplete' => 'on', 'role' => 'form', 'class' => 'm-t')) }}
                        <div class="form-group">
                            {{ Form::email('email', null, array('id' => 'sign_in_email', 'class' => 'form-control', 'placeholder' => 'E-mail', 'required')) }}
                            <small class="text-danger">{{ $errors->first('sign_in_email') }}</small>
                        </div>
                        <div class="form-group">
                            {{ Form::password('password', array('id' => 'sign_in_password', 'class' => 'form-control', 'placeholder' => 'Password', 'required')) }}
                            <small class="text-danger">{{ $errors->first('sign_in_password') }}</small>
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b">{{ trans('auth.login') }}</button>
                        
                        <div class="social-login">
                            <button type="button" class="btn btn-success block full-width m-b" onclick="window.location.href='{{ url('/auth/facebook') }}'"><i class="fa fa-facebook"></i>{{ trans('auth.facebook_signin') }}</button>
                            <button type="button" class="btn block full-width m-b" onclick="window.location.href='{{ url('/auth/google') }}'"><i class="fa fa-google"></i>{{ trans('auth.google_signin') }}</button>
                        </div>
            
                        <a href="{{ route('forgotPassword') }}"><small>Forgot password?</small></a>
                        <p class="text-muted text-center"><small>Do not have an account?</small></p>
                        <a class="btn btn-sm btn-white btn-block" href="{{ route('signup') }}">Create an account</a>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{ HTML::script('js/jquery-3.1.1.min.js') }}
{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/plugins/toastr/toastr.min.js') }}
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
{{ HTML::script('js/gdpr.js') }}

<script>
    var privacy_policy_en =  '{{ route('privacyPolicyAPP') }}';
</script>

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
