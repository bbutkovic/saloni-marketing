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
    {{ HTML::style('css/plugins/lightbox/lightbox.css') }}
    
    <script type="text/javascript">
        var ajax_url = '<?php echo URL::to('/'); ?>/';
    </script>
</head>

<body id="page-top" class="landing-page no-skin-config">
    <div class="top-navbar">
        <div class="container">
            <span class="open-time"><i class="fa fa-clock-o"></i> {{ $open_status }}</span>
            <span class="contact-number"><i class="fa fa-phone"></i> {{ $selected_location->business_phone }}<span class="phone-spacing"></span>{{ $selected_location->mobile_phone }}</span>
        </div>
    </div>
    <nav class="header-navigation navbar navbar-default location-navbar navbar-fixed-top">
        <div class="header-wrap">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <div class="header-logo">
                    <a href="{{ URL::to('/').'/'.$salon->unique_url . '/' . $selected_location->unique_url }}">
                        @if(isset($selected_location->location_extras->location_photo))
                            <img src="{{ URL::to('/').'/images/location-logo/' . $selected_location->location_extras->location_photo }}" alt="{{ $selected_location->location_name }}">
                        @else
                            <img src="{{ URL::to('/').'/images/salon-logo/'.$salon->logo }}" alt="{{ $salon->business_name }}">
                        @endif
                    </a>
                </div>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-main nav-header">
                    <li class="active">
                        <li><a class="page-scroll" href="{{ URL::to('/').'/'.$salon->unique_url }}">{{ trans('salon.home') }}</a></li>
                        <li><a class="page-scroll" href="#services">{{ trans('salon.services') }}</a></li>
                        <li><a class="page-scroll" href="#gallery">{{ trans('salon.gallery') }}</a></li>
                        <li><a href="{{ route('clientBooking', [$salon->unique_url, $selected_location->unique_url]) }}" id="bookNowBtn" style="background-color: {{ $salon->website_content->book_btn_bg }}; color: {{ $salon->website_content->book_btn_color }}">{{ $salon->website_content->book_btn_text }}</a></li>
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
    
    <section id="locationInfo" class="section-open-hours">
        <div class="location-info-container">
            <div class="col-sm-6 col-sm-push-6">
                <div id="locationMap"></div>
            </div>
            <div class="col-sm-6 col-sm-pull-6 open-hours-wrap">
                <h3 class="text-center">{{ trans('salon.open_hours') }}</h3>
                @foreach($open_hours as $hour)
                <span class="location-hours"><h2>{{ trans('salon.'.$hour->dayname) }}</h2><h3>{{ $hour->start_time }} - {{ $hour->closing_time }}</h3></span>
                @endforeach
            </div>
        </div>
    </section>
    
    <section id="services" class="section-services">
        <div class="container">
            <div class="services-heading-wrap">
                <h1 class="section-heading pull-left text-uppercase">{{ trans('salon.services') }}</h1>
                <div class="category-selection pull-right">
                    @foreach($categories as $category)
                    <button type="button" class="service-category-btn" data-category="{{ $category->id }}">{{ $category->name }}</button>
                    @endforeach
                </div>
            </div>
            <p class="services-description">
                {{ $website_content->website_service_text }}
            </p>
            <div class="services-wrap">
            </div>
        </div>
    </section>
    
    <section id="info" class="section-alt">
        <div class="location-info-wrap container">
            @if($selected_location->location_extras->accessible_for_disabled === 1)
            <div class="benefit">
                <span class="icon"><i class="fa fa-wheelchair"></i></span>
                <p class="benefit-desc">Accessible</p>
            </div>
            @endif
            @if($selected_location->location_extras->parking === 1)
            <div class="benefit">
                <span class="icon"><i class="fa fa-car"></i></span>
                <p class="benefit-desc">Parking</p>
            </div>
            @endif
            @if($selected_location->location_extras->credit_cards === 1)
            <div class="benefit">
                <span class="icon"><i class="fa fa-credit-card"></i></span>
                <p class="benefit-desc">Cards accepted</p>
            </div>
            @endif
            @if($selected_location->location_extras->wifi === 1)
            <div class="benefit">
                <span class="icon"><i class="fa fa-wifi"></i></span>
                <p class="benefit-desc">Wi-Fi</p>
            </div>
            @endif
            @if($selected_location->location_extras->pets === 1)
            <div class="benefit">
                <span class="icon"><i class="fa fa-paw"></i></span>
                <p class="benefit-desc">Pets friendly</p>
            </div>
            @endif
        </div>
    </section>
    
    <section id="gallery" class="gallery-section">
        <div class="container">
            <h1 class="section-heading text-uppercase text-left">{{ trans('salon.gallery') }}</h1>
            <div class="gallery-wrap">
                <div class="masonry">
                @foreach($location_photos as $photo)
                   <a href="{{ URL::to('/').'/images/salon-websites/gallery/'.$photo->name }}" data-lightbox="roadtrip"><div class="brick"><img src="{{ URL::to('/').'/images/salon-websites/gallery/'.$photo->name }}"></div></a>
                @endforeach
                </div>
            </div>
        </div>
    </section>

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
                        <div class="form-group col-md-6">
                            <label for="name">{{ trans('salon.name') }}</label>
                            {{ Form::text('name', null, array('id' => 'name', 'class' => 'form-control')) }}
                        </div>
                        <div class="form-group col-md-6">
                            <label for="email">{{ trans('salon.email') }}</label>
                            {{ Form::text('email', null, array('id' => 'email', 'class' => 'form-control')) }}
                        </div>
                        <div class="form-group col-xs-12">
                            <label for="message text-left">{{ trans('salon.message') }}</label>
                            {{ Form::textarea('message', null, array('id' => 'message', 'class' => 'form-control', 'size' => '30x5')) }}
                        </div>
                        <div class="btn-wrap-mail text-right">
                            <button type="button" class="btn-send-message">{{ trans('salon.send_message') }}</button>
                        </div>
                    {{ Form::close() }}
                </div>
                @if($salon->website_content->facebook_link != null || $salon->website_content->twitter_link != null || $salon->website_content->instagram_link != null || $salon->website_content->pinterest_link != null)
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
                    @if($salon->website_content->pinterest_link != null)
                        <li><a href="{{ $salon->website_content->pinterest_link }}"><i class="fa fa-pinterest-p"></i></a></li>
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

    {{ HTML::script('js/jquery-3.1.1.min.js') }}
    {{ HTML::script('js/plugins/lightbox/lightbox.js') }}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyColmGdPetW0zIga7qqyByHrll4kMzJVJE"></script>
    {{ HTML::script('js/bootstrap.min.js') }}
    {{ HTML::script('js/website/salonWebsite.js') }}

<script>
    var location_id = '{{ $selected_location->id }}';
    function initializeMap() {

        var zoom = 18;
        var lat = '{{ $selected_location->lat }}';
        var lng = '{{ $selected_location->lng }}';

        var init_location = {lat: parseFloat(lat), lng: parseFloat(lng)};

        var map = new google.maps.Map(document.getElementById('locationMap'), {
          zoom: 16,
          center: init_location,
          styles: [
                {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
                {
                  featureType: 'administrative.locality',
                  elementType: 'labels.text.fill',
                  stylers: [{color: '#d59563'}]
                },
                {
                  featureType: 'poi',
                  elementType: 'labels.text.fill',
                  stylers: [{color: '#d59563'}]
                },
                {
                  featureType: 'poi.park',
                  elementType: 'geometry',
                  stylers: [{color: '#263c3f'}]
                },
                {
                  featureType: 'poi.park',
                  elementType: 'labels.text.fill',
                  stylers: [{color: '#6b9a76'}]
                },
                {
                  featureType: 'road',
                  elementType: 'geometry',
                  stylers: [{color: '#38414e'}]
                },
                {
                  featureType: 'road',
                  elementType: 'geometry.stroke',
                  stylers: [{color: '#212a37'}]
                },
                {
                  featureType: 'road',
                  elementType: 'labels.text.fill',
                  stylers: [{color: '#9ca5b3'}]
                },
                {
                  featureType: 'road.highway',
                  elementType: 'geometry',
                  stylers: [{color: '#746855'}]
                },
                {
                  featureType: 'road.highway',
                  elementType: 'geometry.stroke',
                  stylers: [{color: '#1f2835'}]
                },
                {
                  featureType: 'road.highway',
                  elementType: 'labels.text.fill',
                  stylers: [{color: '#f3d19c'}]
                },
                {
                  featureType: 'transit',
                  elementType: 'geometry',
                  stylers: [{color: '#2f3948'}]
                },
                {
                  featureType: 'transit.station',
                  elementType: 'labels.text.fill',
                  stylers: [{color: '#d59563'}]
                },
                {
                  featureType: 'water',
                  elementType: 'geometry',
                  stylers: [{color: '#17263c'}]
                },
                {
                  featureType: 'water',
                  elementType: 'labels.text.fill',
                  stylers: [{color: '#515c6d'}]
                },
                {
                  featureType: 'water',
                  elementType: 'labels.text.stroke',
                  stylers: [{color: '#17263c'}]
                }
            ],
            zoomControl: true,
            mapTypeControl: true,
            scaleControl: false,
            streetViewControl: false,
            fullscreenControl: false
        });
        
        var marker = new google.maps.Marker({
            position: init_location,
            map: map
        });

    }
    
    initializeMap();
        
    $(document).ready(function () {

        $('body').scrollspy({
            target: '.navbar-fixed-top',
            offset: 80
        });

        // Page scrolling feature
        $('a.page-scroll').bind('click', function(event) {
            var link = $(this);
            $('html, body').stop().animate({
                scrollTop: $(link.attr('href')).offset().top - 50
            }, 500);
            event.preventDefault();
            $("#navbar").collapse('hide');
        });
    });
    
    </script>

</body>
</html>