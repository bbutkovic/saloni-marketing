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
    <div class="text-center loginscreen passwordBox animated fadeInDown">
        <div class="row">
            <div class="col-md-12">
                <div class="ibox-content">
                    <h1 class="logo-name text-uppercase">{{ trans('auth.login') }}</h1>
                    <div class="row">
                        <div class="col-lg-12"> 
                        {{ Form::open(array('route' => 'password.email', 'id' => 'login-form', 'autocomplete' => 'on', 'role' => 'form', 'class' => 'm-t')) }}
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="control-label">E-Mail Address</label>
                            <div>
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
                                @if($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" onclick="sendPasswordReset()">{{ trans('auth.send_reset_link') }}</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    function sendPasswordReset() {
        toastr.success("Password link has been sent to your email address!");
    }
</script>

</body>

</html>
