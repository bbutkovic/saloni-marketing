@extends('main')

@section('styles')
@endsection

<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId            : '387717418355900',
            autoLogAppEvents : true,
            xfbml            : true,
            version          : 'v3.1'
        });
    };

    (function(d, s, id){
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

@section('scripts-footer')
    {{ HTML::script('js/campaigns/facebook.js') }}
@endsection

@section('content')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-12">
            <h2 class="section-heading pull-left">{{ trans('salon.facebook_campaigns') }}</h2>
        </div>
    </div>

    <div id="facebookCampaigns" class="user-settings-wrapper">
        <div class="wrapper wrapper-content">
            <div class="tabs-container">
                <ul class="nav nav-tabs">
                    <li id="tab-1-li" class=""><a data-toggle="tab" href="#tab-1">{{ trans('salon.campaign_list') }}</a></li>
                    <li id="tab-2-li" class="active"><a data-toggle="tab" href="#tab-2">{{ trans('salon.add_new_campaign') }}</a></li>
                </ul>
                <div class="tab-content">

                    <div id="tab-1" class="tab-pane">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="tab-2" class="tab-pane active">
                        <div class="panel-body">
                            <div class="col-lg-12">
                                <div class="ibox float-e-margins">
                                    <div class="ibox-content">
                                        {{ Form::open(array('id' => 'newFacebookAdCampaign')) }}
                                            <div class="form-group">
                                                <label for="campaignName">{{ trans('salon.campaign_name') }}</label>
                                                {{ Form::text('campaign_name', null, array('id' => 'campaignName', 'class' => 'form-control', 'required')) }}
                                            </div>
                                            <div class="form-group">
                                                <label for="campaignObjective">{{ trans('salon.campaign_objective') }}</label>
                                                {{ Form::text('campaign_objective', null, array('id' => 'campaignObjective', 'class' => 'form-control', 'required')) }}
                                            </div>
                                            <div class="form-group">
                                                <label for="campaignStatus">{{ trans('salon.campaign_status') }}</label>
                                                {{ Form::text('campaign_status', null, array('id' => 'campaignStatus', 'class' => 'form-control', 'required')) }}
                                            </div>
                                            <div class="row text-center">
                                                <button class="btn btn-primary">{{ trans('salon.create_campaign') }}</button>
                                            </div>
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
@endsection

<script>

</script>