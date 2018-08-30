<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ trans('core.title') }}</title>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/css/font-awesome.min.css') }}
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
    {{ HTML::style('css/animate.css') }}
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/custom.css') }}

</head>

<body class="gray-bg" data-lang="en">

<section id="login-section">
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div class="wrapper wrapper-content">
            <div class="ibox float-e-margins">
                <div class="ibox-title login-wrapper">
                    <h1 class="logo-name text-uppercase">{{ trans('auth.register') }}</h1>
                        <div>
                        {{ Form::open(array('route' => 'postRegister', 'id' => 'login-form', 'class' => 'm-t')) }}
                            <div class="form-group">
                                {{ Form::select('language', [null => 'Select language'] + $language_list, null, ['class' => 'form-control']) }}
                                <small class="text-danger">{{ $errors->first('language') }}</small>
                            </div>
                
                            <div class="form-group">
                                {{ Form::text('first_name', null, array('id' => 'signup_first_name', 'class' => 'form-control', 'placeholder' => trans('auth.first_name'))) }}
                                <small class="text-danger">{{ $errors->first('first_name') }}</small>
                            </div>
                
                            <div class="form-group">
                                {{ Form::text('last_name', null, array('id' => 'signup_last_name', 'class' => 'form-control', 'placeholder' => trans('auth.last_name'), 'required')) }}
                                <small class="text-danger">{{ $errors->first('last_name') }}</small>
                            </div>
                
                            <div class="form-group">
                                {{ Form::email('email', null, array('id' => 'sign_in_email', 'class' => 'form-control', 'placeholder' => trans('auth.email'))) }}
                                <small class="text-danger">{{ $errors->first('email') }}</small>
                            </div>
                
                            <div class="form-group">
                                {{ Form::password('password', array('id' => 'sign_in_password', 'class' => 'form-control', 'placeholder' => trans('auth.password'), 'required')) }}
                                <small class="text-danger">{{ $errors->first('password') }}</small>
                            </div>
                
                            <div class="form-group">
                                {{ Form::password('password_confirmation', array('id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('auth.password_confirmation'), 'required')) }}
                                <small class="text-danger">{{ $errors->first('password_confirmation') }}</small>
                            </div>

                            <div class="row gdpr-consent">
                                <div class="i-checks">
                                    <label><input type="checkbox" name="consent" id="gdprConsent"><i></i> {{ trans('salon.gdpr_trans') }}<a href="'+privacy_policy_route+'">{{ trans('salon.terms_and_conditions') }}</a></label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary block full-width m-b">{{ trans('auth.register') }}</button>
                            
                            <p class="text-muted text-center"><small>{{ trans('auth.already_registered') }}</small></p>
                            <a class="btn btn-sm btn-white btn-block" href="{{ route('signin') }}">{{ trans('auth.login') }}</a>
                        {{ Form::close() }}

                        </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/jquery-3.1.1.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
{{ HTML::script('js/gdpr.js') }}
<script src="js/bootstrap.min.js"></script>

<script>
    var privacy_policy_en =  '{{ route('privacyPolicyAPP') }}';
</script>

</body>

</html>
