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
    
    {{ HTML::script('js/jquery-3.1.1.min.js') }}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyColmGdPetW0zIga7qqyByHrll4kMzJVJE"></script>
    
    <script type="text/javascript">
        var ajax_url = '<?php echo URL::to('/'); ?>/';
    </script>
</head>

<body id="page-top" class="landing-page no-skin-config">
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

<section id="blogPost">
    <div class="container">
        <div class="col-md-12 single-post-wrap">
            <div class="recent-posts">
                <h1 class="recent-posts-heading">{{ trans('salon.recent_posts') }}</h1>
                @foreach($recent_posts as $recent_post)
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
        </div>
        <div class="button-load-more text-center">
            <button type="button" onclick="loadMorePosts({{ $salon->id }})" data-page="1">{{ trans('salon.read_more') }}</button>
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

{{ HTML::script('js/bootstrap.min.js') }}
{{ HTML::script('js/website/salonWebsite.js') }}

<script>
    
    var posted_on_trans = '{{ trans('salon.posted_on') }}';
        
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