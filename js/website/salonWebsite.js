$(document).ready(function() {

    var about_img_height = $('.about-salon-image img').height();
    
    if($(window).width() > 550) {   
        $('.about-salon-desc').css('height', about_img_height);
    }
    
    $('.carousel-indicators li:first-child').addClass('active');
    $('.carousel-inner .item:first-child').addClass('active');

    if(typeof location_id != undefined && location_id != null) {
        getServicesForLocation(location_id);
    }
    
    $('.service-category-btn').on('click', function() {
        var category = $(this).data('category');
        
        $('.service-category-btn').each(function() {
            $(this).removeClass('active');
        });
        
        $(this).addClass('active');
        
        $('.service').each(function() {
            $(this).removeClass('active');    
        });
        
        $('#service'+category).addClass('active');
        
    });

    if(typeof locations_lat_lng != 'undefined' && locations_lat_lng.length > 0) {
        initializeMap(locations_lat_lng);
    } else {
        initializeMap();
    }
});

$(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();

    $('html, body').animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 500);
});

function initializeMap(locations_lat_lng) {

    var zoom = 18;

    var init_location = {lat: parseFloat(lat), lng: parseFloat(lng)};

    var map = new google.maps.Map(document.getElementById('salonLocationsMap'), {
        zoom: 10,
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
        ]
    });

    if(locations_lat_lng.length > 1) {
        var bounds = new google.maps.LatLngBounds ();

        for(var i=0; i<locations_lat_lng.length; i++) {

            var content = '<h1><a href="{{ $salon->unique_url."/" }}' + locations_lat_lng[i]["unique_url"] + '">' + locations_lat_lng[i]["location_name"] + '</a></h1><h2 class="popup-location-info">' + locations_lat_lng[i]["address"] + '</h2><h2 class="popup-location-info">' + locations_lat_lng[i]["phone"] + '</h2><h2 class="popup-location-info">' + locations_lat_lng[i]["email"] + '</h2>';

            var init_location = {lat: parseFloat(locations_lat_lng[i]['lat']), lng: parseFloat(locations_lat_lng[i]['lng'])};

            bounds.extend (init_location);
            var marker = new google.maps.Marker({
                position: init_location,
                map: map
            });

            var infowindow = new google.maps.InfoWindow();

            google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){
                return function() {
                    infowindow.setContent(content);
                    infowindow.open(map,marker);
                };
            })(marker,content,infowindow));

        }

        map.fitBounds(bounds);
    } else {

        var content = '<h1><a href="{{ $salon->unique_url."/" }}' + locations_lat_lng[0]["unique_url"] + '">' + locations_lat_lng[0]["location_name"] + '</a></h1><h2 class="popup-location-info">' + locations_lat_lng[0]["address"] + '</h2><h2 class="popup-location-info">' + locations_lat_lng[0]["phone"] + '</h2><h2 class="popup-location-info">' + locations_lat_lng[0]["email"] + '</h2>';

        var init_location = {lat: parseFloat(locations_lat_lng[0]['lat']), lng: parseFloat(locations_lat_lng[0]['lng'])};

        var marker = new google.maps.Marker({
            position: init_location,
            map: map
        });

        var infowindow = new google.maps.InfoWindow();

        google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){
            return function() {
                infowindow.setContent(content);
                infowindow.open(map,marker);
            };
        })(marker,content,infowindow));
    }
}

function getServicesForLocation(location_id) {
    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/location/' + location_id + '/services',
        success: function(data) {
            console.log(data);
            $.each(data.services, function(index, value) {
                console.log(value.name);
               $('.services-wrap').append('<div id="service' + index + '" class="service" data-category="' + index + '"></div>');

               $.each(value, function(i, v) {
                  $('#service' + index).append('<p class="service-dotted"><span class="service-name">' + v.name + '</span><span class="service-price">' + v.price + '</span></p><p class="service-description">' + v.desc + '</p>'); 
               });
               
            });
            
            $('.services-wrap').find('>:first-of-type').addClass('active');
        }
    });
}

$(window).on('resize', function() {
    
    var about_img_height = $('.about-salon-image img').height();
   
    if($(window).width() > 550) {   
        $('.about-salon-desc').css('height', about_img_height);
    }
    
});

$(window).on('scroll', function() {
   if(window.pageYOffset > 1) {
       $('.location-navbar.navbar-fixed-top').css('top', '0');
   } else {
       $('.location-navbar.navbar-fixed-top').css('top', '30px');
   }
});

function loadMorePosts(salon_id) {
    
    var page = $('.button-load-more button').attr('data-page');
    
    $.ajax({
        
       type: 'get',
       url: ajax_url + 'ajax/fetch-blogposts/' + salon_id + '/' + page,
       
       success: function(data) {
           
           $('.button-load-more button').attr('data-page', data.page_count);
            
           $.each(data.posts, function(index, value) {
               $('.recent-posts').append('<div class="col-md-6 blog-post-wrap"><div class="inner-wrapper">'
                                        +'<div class="blog-post-image" style="background-image: url(../images/salon-websites/blog-images/' + value.featured_image + ')"></div>'
                                        +'<div class="blog-post-info"><h6 class="post-date">' + posted_on_trans + ' ' + value.created_at + '</h6><h4><a class="post-link" href="../blog/' + data.salon_url + '/' + value.unique_url + '">' + value.title + '</a></h4><p>' + value.description + '</p></div></div></div>')
           });  
           
       }
    });
    
}