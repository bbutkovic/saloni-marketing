<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="_token" content="{{ csrf_token() }}"/>

    <title>Privacy Policy</title>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('css/salon-website.css') }}
    {{ HTML::style('css/alt-page.css') }}

</head>

<body id="privacyPolicy">
<nav class="header-navigation navbar navbar-default navbar-fixed-top">
    <div class="header-wrap">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <div class="header-logo">
                <a href="{{ URL::to('/').'/'.$salon->unique_url }}">
                    <img src="{{ URL::to('/').'/images/salon-logo/'.$salon->logo }}" alt="{{ $salon->business_name }}">
                </a>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-main nav-header">
                <li class="active">
                <li><a class="page-scroll" href="{{ URL::to('/').'/'.$salon->unique_url }}">{{ trans('salon.home') }}</a></li>
                <li><a class="page-scroll" href="{{ route('salonBlog', $salon->unique_url) }}">{{ trans('salon.news') }}</a></li>
                </li>
                @if(count($salon->locations) > 1)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ trans('salon.locations') }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @foreach($salon->locations as $location)
                                <li class="dropdown-link"><a href="{{ URL::to('/').'/'.$salon->unique_url.'/'.$location->unique_url }}">{{ $location->location_name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li><a class="page-scroll" href="{{ URL::to('/').'/'.$salon->unique_url.'/'.$salon->locations[0]['unique_url'] }}">{{ trans('salon.about_salon') }}</a></li>
                @endif
                <li>
                    <a href="{{ route('clientBooking', $salon->unique_url) }}" id="bookNowBtn" style="background-color: {{ $salon->website_content->book_btn_bg }}; color: {{ $salon->website_content->book_btn_color }}">{{ $salon->website_content->book_btn_text }}</a>
                </li>
            </ul>

            <ul class="nav navbar-nav navbar-right nav-header">
                @if($salon->website_content->facebook_link != null)
                    <li><a href="{{ $salon->website_content->facebook_link }}"><i class="fa fa-facebook-f"></i></a></li>
                @endif
                @if($salon->website_content->twitter_link != null)
                    <li><a href="{{ $salon->website_content->twitter_link }}"><i class="fa fa-twitter"></i></a></li>
                @endif
                @if($salon->website_content->instagram_link != null)
                    <li><a href="{{ $salon->website_content->instagram_link }}"><i class="fa fa-instagram"></i></a></li>
                @endif
                @if($salon->website_content->pinterest_link != null)
                    <li><a href="{{ $salon->website_content->pinterest_link }}"><i class="fa fa-pinterest-p"></i></a></li>
                @endif
            </ul>
        </div>
    </div>
    </div>
</nav>
<h1 class="policy-header text-center">Privacy Policy</h1>
<div class="privacy-policy-wrapper text-left">
    {!! $website_content->terms_and_conditions !!}
</div>

</body>

</html>
