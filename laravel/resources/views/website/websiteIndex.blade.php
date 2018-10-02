<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $salon->business_name }}</title>

    {{ HTML::style('css/bootstrap.min.css') }}
    {{ HTML::style('font-awesome/css/font-awesome.min.css') }}
    {{ HTML::style('css/salon-website.css') }}
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
</head>

<body id="home" class="landing-page no-skin-config" data-lang="{{ $salon->country }}">
    <nav class="header-navigation navbar navbar-default navbar-fixed-top">
        <div class="header-wrap">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                @if($salon->logo != null)
                <div class="header-logo">
                    <a href="{{ URL::to('/').'/'.$salon->unique_url }}">
                        <img src="{{ URL::to('/').'/images/salon-logo/'.$salon->logo }}" alt="{{ $salon->business_name }}">
                    </a>
                </div>
                @endif
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-main nav-header">
                    <li class="active">
                        <li><a class="page-scroll" href="#home">{{ trans('salon.home') }}</a></li>
                        @if($latest_news->isNotEmpty())<li><a class="page-scroll" href="{{ route('salonBlog', $salon->unique_url) }}">{{ trans('salon.news') }}</a></li>@endif
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
                        <li><a class="page-scroll" href="#about">{{ trans('salon.about_salon') }}</a></li>
                        @if(isset($salon->website_content) && $salon->website_content->website_service_text === 1)
                        <li><a class="page-scroll" href="#services">{{ trans('salon.services') }}</a></li>
                        @endif
                        @if(isset($salon->website_content) && $salon->website_content->display_pricing === 1)
                            <li><a class="page-scroll" href="#pricing">{{ trans('salon.pricing') }}</a></li>
                        @endif
                        @if(isset($salon->website_content) && $salon->website_content->website_products_text != null)
                            <li><a class="page-scroll" href="#products">{{ trans('salon.products') }}</a></li>
                        @endif
                        <li><a class="page-scroll" href="#contact">{{ trans('salon.contact') }}</a></li>
                    @endif
                    @if(isset($salon->website_content) && $salon->website_content->display_booking_btn != 0)
                    <li>
                        <a href="{{ route('clientBooking', $salon->unique_url) }}" id="bookNowBtn" style="background-color: {{ $salon->website_content->book_btn_bg }}; color: {{ $salon->website_content->book_btn_color }}">{{ $salon->website_content->book_btn_text }}</a>
                    </li>
                    @endif
                    @if($salon->website_content->facebook_link != null)
                        <li><a class="header-social-icons" href="{{ $salon->website_content->facebook_link }}"><i class="fa fa-facebook-f"></i></a></li>
                    @endif
                    @if($salon->website_content->twitter_link != null)
                        <li><a class="header-social-icons" href="{{ $salon->website_content->twitter_link }}"><i class="fa fa-twitter"></i></a></li>
                    @endif
                    @if($salon->website_content->instagram_link != null)
                        <li><a class="header-social-icons" href="{{ $salon->website_content->instagram_link }}"><i class="fa fa-instagram"></i></a></li>
                    @endif
                </ul>
            </div>
          </div>
        </div>
    </nav>

    <div id="headerSlider" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($salon->slider_images as $key=>$website_image)
            <li data-target="#headerSlider" data-slide-to="{{ $key }}"></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach($salon->slider_images as $key=>$web_image)
            <div class="item">
                <img src="{{ URL::to('/').'/images/salon-websites/slider-images/'.$web_image->image->image_name }}" alt="Slider">
                @if(isset($salon->slider_promos[$key]) && $salon->slider_promos[$key]['active'])
                <div class="carousel-caption-alt">
                    <h3>{{ $salon->slider_promos[$key]->title }}</h3>
                    <p>{{ $salon->slider_promos[$key]->text }}</p>
                    <br />
                    @if($salon->website_content->display_booking_btn && isset($salon->slider_promos[$key]) && $salon->slider_promos[$key]->include_btn === 1)
                    <a href="{{ route('clientBooking', $salon->unique_url) }}"><button type="button" class="book-now-btn" style="background-color: {{ $salon->website_content->book_btn_bg }}; color: {{ $salon->website_content->book_btn_color }}">{{ $salon->website_content->book_btn_text }}</button></a>
                    @endif
                </div>
                @endif
            </div>
            @endforeach
            </div>
        </div>
    </div>

    <section id="about" class="about container-fluid">
        <div class="row">
            @if(isset($salon->website_content) && $salon->website_content->about_image != null)
            <div class="col-xs-5 col-sm-6 about-salon-image">
                <img src="{{ URL::to('/').'/images/salon-websites/about-image/'.$salon->website_content->about_image }}" alt="About salon" />
            </div>
            @endif
            <div class="@if(isset($salon->website_content) && $salon->website_content->about_image != null) col-xs-7 col-sm-6 text-left @else text-center container-text @endif about-salon-desc">
                <h1 class="section-heading">{{ trans('salon.about') }}</h1>
                @if(isset($website_content->company_introduction)){!! $website_content->company_introduction !!}@endif
                @if(isset($website_content->website_about_text))
                <div class="about-salon-text @if(isset($salon->website_content) && $salon->website_content->about_image === null) about-salon-noimg @endif">
                    <p class="m-t m-b">{!! $website_content->website_about_text !!}</p>
                </div>
                @endif
            </div>
        </div>
    </section>

    @if(count($salon->locations) < 2)
    <section id="locationInfo" class="section-open-hours">
        <div class="location-info-container">
            <div class="col-sm-6 col-sm-push-6">
                <div id="salonLocationsMap"></div>
            </div>
            <div class="col-sm-6 col-sm-pull-6">
                <h3 class="text-center">{{ trans('salon.open_hours') }}</h3>
                @foreach($open_hours as $hour)
                    <span class="location-hours"><h2>{{ trans('salon.'.$hour->dayname) }}</h2><h3>@if($hour->start_time != '00:00' && $hour->closing_time != '00:00'){{ $hour->start_time }} - {{ $hour->closing_time }}@else {{ trans('salon.salon_closed') }}@endif</h3></span>
                @endforeach
            </div>
        </div>
    </section>

    @if(isset($website_content) && $website_content->web_service_text != null)
    <section id="services" class="section-services">
        <div class="container">
            <h1 class="section-heading text-uppercase text-center">{{ trans('salon.services') }}</h1>
            <p class="services-description text-center">
                {!! $website_content->website_service_text !!}
            </p>
        </div>
    </section>
    @endif

    @if(isset($website_content) && $website_content->display_pricing === 1)
    <section id="pricing" class="section-pricing">
        <div class="container">
            <div class="services-heading-wrap">
                <h1 class="section-heading text-uppercase text-center">{{ trans('salon.pricing') }}</h1>
                <div class="category-selection text-center">
                    @foreach($categories as $category)
                        <button type="button" class="service-category-btn" data-category="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>
            <div class="services-wrap">
            </div>
        </div>
    </section>
    @endif

    @if(isset($website_content) && $website_content->website_products_text != null)
    <section id="products" class="section-products">
        <div class="container">
            <div class="products-heading-wrap">
                <h1 class="section-heading text-uppercase text-center">{{ trans('salon.products') }}</h1>
            </div>
            {!! $website_content->website_products_text !!}
        </div>
    </section>
    @endif

    @else
    <section id="locations" class="gray-section">
        <div id="salonLocationsMap"></div>
    </section>
    @endif

    @if($latest_news->isNotEmpty())
    <section id="news" class="news-section">
        <div class="container">
            <div class="row">
                <h1 class="section-news-heading m-b text-muted">{{ trans('salon.latest_news') }}</h1>
                <div class="latest-news-container">
                    @foreach($latest_news as $recent_post)
                    <div class="col-md-6 blog-post-wrap">
                        <div class="inner-wrapper">
                            <div class="blog-post-image" style="background-image: url('{{ URL::to('/').'/images/salon-websites/blog-images/'.$recent_post->featured_image }}')"></div>
                            <div class="blog-post-info">
                                <h6 class="post-date">{{ trans('salon.posted_on') }} {{ \Carbon\Carbon::parse($recent_post->created_at)->formatLocalized('%A, %d %B %Y') }}</h6>
                                <a class="post-link" href="{{ URL::to('/').'/blog/'.$salon->unique_url.'/'.$recent_post->unique_url }}"><h4>{{ $recent_post->title }}</h4></a>
                                <p>{{ $recent_post->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="btn-wrap text-center">
                    <a href="{{ route('salonBlog', $salon->unique_url) }}" class="news-all-btn">{{ trans('salon.all_news') }}</a>
                </div>
            </div>
        </div>
    </section>
    @endif

    <section id="contact" class="section-alt section-footer">
        <div class="container">
            <div class="row m-b-lg">
                <div class="col-lg-12 text-center">
                    <div class="navy-line"></div>
                    <h1>{{ trans('salon.contact_us') }}</h1>
                </div>
            </div>
            <div class="row m-b-lg">
                <div class="text-center">
                    <address>
                        <strong><span class="navy">{{ $salon->business_name }}</span></strong><br/>
                        {{ $salon->address }},<br/>
                        {{ $salon->country_name->country_local_name }}<br/>
                        <abbr title="Phone"><i class="fa fa-phone"></i></abbr> {{ $salon->business_phone }}
                    </address>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-left">
                    {{ Form::open(array('id' => 'sendEmail')) }}
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="name">{{ trans('salon.name') }}</label>
                                {{ Form::text('name', null, array('id' => 'name', 'class' => 'form-control')) }}
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="email">{{ trans('salon.email') }}</label>
                                {{ Form::text('email', null, array('id' => 'email', 'class' => 'form-control')) }}
                            </div>
                            <div class="form-group col-xs-12">
                                <label for="message text-left">{{ trans('salon.message') }}</label>
                                {{ Form::textarea('message', null, array('id' => 'message', 'class' => 'form-control', 'size' => '30x5')) }}
                            </div>
                        </div>
                        <div class="row gdpr-consent">
                            <div class="i-checks m-l">
                                <label><input type="checkbox" name="consent" id="gdprConsent"><i></i> {{ trans('salon.gdpr_trans') }}<a href="{{ route('privacyPolicy', $salon->unique_url) }}">{{ trans('salon.terms_and_conditions') }}</a></label>
                            </div>
                        </div>
                        <div class="btn-wrap-mail text-right">
                            <button type="button" class="btn-send-message">{{ trans('salon.send_message') }}</button>
                        </div>
                    {{ Form::close() }}
                </div>
                @if($salon->website_content->facebook_link != null || $salon->website_content->twitter_link != null || $salon->website_content->instagram_link != null)
                    <p class="m-t-sm">{{ trans('salon.follow_us') }}</p>
                @endif
                <ul class="list-inline social-icon">
                    @if($salon->website_content->facebook_link != null)
                        <li><a href="{{ $salon->website_content->facebook_link }}"><i class="fa fa-facebook-f"></i></a></li>
                    @endif
                    @if($salon->website_content->twitter_link != null)
                        <li><a href="{{ $salon->website_content->twitter_link }}"><i class="fa fa-twitter"></i></a></li>
                    @endif
                    @if($salon->website_content->instagram_link != null)
                        <li><a href="{{ $salon->website_content->instagram_link }}"><i class="fa fa-instagram"></i></a></li>
                    @endif
                </ul>
            </div>
        </div>
    </section>

    <footer class="section-alt">
        <div class="text-center m-t-lg">
            <p><strong>&copy; <?= date('Y'); ?> {{ $salon->business_name }}</strong></p>
        </div>
    </footer>

    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
    {{ HTML::script('js/jquery-3.1.1.min.js') }}
    {{ HTML::script('js/gdpr.js') }}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyColmGdPetW0zIga7qqyByHrll4kMzJVJE"></script>
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/website/salonWebsite.js') }}

    <script>
        var locations_lat_lng = [];
        var location_id = null;
        var services_for_location = @if(count($salon->locations) < 2) {{ $salon->locations[0]->id }} @else null @endif;
        var privacy_policy = '{{ route('privacyPolicy', $salon->unique_ur) }}';
        var ajax_url = '<?php echo URL::to('/'); ?>/';

        @foreach($location_markers as $marker)
            var location_markers = {
                location_name: '{{  $marker["location_name"] }}',
                address: '{{ $marker["address"] }}',
                city: '{{ $marker["city"] }}',
                phone: '{{ $marker["phone"] }}',
                email: '{{ $marker["email"] }}',
                lat: '{{ $marker["lat"] }}',
                lng: '{{ $marker["lng"] }}',
                unique_url: '{{ $marker["unique_url"] }}',
            };
            locations_lat_lng.push(location_markers);
        @endforeach
        var lat = locations_lat_lng[0]['lat'];
        var lng = locations_lat_lng[0]['lng'];

    </script>

</body>
</html>