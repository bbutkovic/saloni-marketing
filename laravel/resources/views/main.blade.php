<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saloni marketing</title>
    <meta name="_token" content="{{ csrf_token() }}"/>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/plugins/toastr/toastr.min.css') }}
    {{ HTML::style('css/plugins/sweetalert/sweetalert.css') }}
    {{ HTML::style('css/plugins/switchery/switchery.css') }}
    {{ HTML::style('font-awesome/css/font-awesome.min.css') }}
    @yield('styles')
    {{ HTML::style('css/style.css') }}
    {{ HTML::style('css/custom.css') }}


    {{ HTML::script('js/jquery-3.1.1.min.js') }}
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/plugins/toastr/toastr.min.js') }}
    {{ HTML::script('js/plugins/switchery/switchery.js') }}
    {{ HTML::script('js/plugins/metisMenu/jquery.metisMenu.js') }}
    {{ HTML::script('js/plugins/slimscroll/jquery.slimscroll.min.js') }}
    {{ HTML::script('js/plugins/pace/pace.min.js') }}
    {{ HTML::script('js/inspinia.js') }}
    @yield('scripts')
    {{ HTML::script('js/main.js') }}
    {{ HTML::script('js/ajax.js') }}
    {{ HTML::script('js/plugins/sweetalert/sweetalert.min.js') }}

    <script type="text/javascript">
        var ajax_url = '<?php echo URL::to('/'); ?>/';
    </script>
</head>
<body data-lang="{{ App::getLocale() }}">

<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span><img alt="image" class="img-circle user-avatar-menu" src="{{ URL::to('/') . '/' . Auth::user()->user_extras->photo }}"></span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                                <span class="block m-t-xs"><strong class="font-bold">{{ $username }}</strong></span>
                                <span class="text-muted text-xs block">{{ trans('main.user_options') }}
                                    <b class="caret"></b>
                                </span>
                            </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="{{ route('update-info') }}">{{ trans('main.account_settings') }}</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ route('logout') }}">{{ trans('auth.logout') }}</a></li>
                        </ul>
                    </div>
                </li>
                @include($menu)
            </ul>
        </div>
    </nav>
    
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-default" href="#"><i class="fa fa-bars"></i> </a>
                    <select id="switch-lang" class="m-b btn btn-default select-lang pull-left" onchange="switchLang()">
                        <option value="0">{{ trans('main.select_language') }}</option>
                        @foreach($languages as $key=>$language)
                            <option value="{{ $key }}" @if($user->language === $key) ? selected : null @endif>{{ $language }}</option>
                        @endforeach
                    </select>
                    @if(Auth::user()->hasRole('salonadmin') && $locations_admin['status'] != 0)
                    <select id="switchLocation" class="m-b btn btn-default select-lang pull-left" onchange="adminSwitchLocation()">
                        @foreach($locations_admin['location_list'] as $local)
                        <option value="{{ $local->id }}" @if($user->location->id === $local->id) ? selected : null @endif>{{ $local->location_name }}</option>
                        @endforeach
                    </select>
                    @endif
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <!--<li class="dropdown mobile-profile">
                        <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#" aria-expanded="false">
                            <i class="fa fa-user"></i>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><p>{{ $username }}</p></li>
                            @if ($user_role_id == 2)
                                <li><a href="#">{{ trans('main.company_info') }}</a></li>
                            @endif
                            <li><a href="#">{{ trans('main.support') }}</a></li>
                            <li class="divider"></li>
                            <li><a href="{{ route('logout') }}">{{ trans('auth.logout') }}</a></li>
                        </ul>
                    </li>-->
                    <li>
                        <a href="{{ route('logout') }}">
                            <i class="fa fa-sign-out"></i> {{ trans('auth.logout') }}
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        @yield('content')
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
{{ HTML::script('js/gdpr.js') }}
@yield('scripts-footer')

</body>

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

<script>
    var validation_error = '{{ trans('errors.validation_error') }}';
    var error = '{{ trans('errors.error') }}';

    var alert_title = '{{ trans('main.alert_title') }}';
    var alert_text = '{{ trans('main.alert_text') }}';
    var alert_confirm = '{{ trans('main.alert_confirm') }}';
    var alert_cancel = '{{ trans('main.alert_cancel') }}';
    
    var swal_unsaved = '{{ trans('main.unsaved_data')  }}';
    var swal_unsaved_desc = '{{ trans('main.unsaved_desc')  }}';
    var swal_confirm = '{{ trans('main.confirm')  }}';
</script>

</html>