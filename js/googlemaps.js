function initializeMap(location, latitude, longitude) {
    var zoom = 18;
    var lat = $('#location_lat').val();
    var lng = $('#location_lng').val();
    if(lat != '') {
        var center = new google.maps.LatLng(lat, lng); 
    } else {
        var center = new google.maps.LatLng(45.81, 15.98); 
    }
   

    var mapOptions = {
        zoom: zoom,
        center: center,
    };

    map = new google.maps.Map(document.getElementById('salon-location-map'), mapOptions);

    showMap(location, latitude, longitude);
}

function showMap(location, latitude, longitude) {
    var marker_image = new google.maps.MarkerImage(ajax_url + 'img/marker.svg');

    updateLocation();

    google.maps.event.addListener(map, 'click', function (e) {
        placeMarker(e.latLng);
    });
}

function placeMarker(position) {
    for (j = 0; j < active_markers.length; j++) {
        active_markers[j].setMap(null);
    }

    marker = new google.maps.Marker({
        position: position,
        map: map
    });

    map.panTo(position);

    active_markers.push(marker);
}

var active_markers = [];

initializeMap('F');

function updateLocation() {
    var geocoder = new google.maps.Geocoder();

    var address = $('#locationAddress').val() + ', ' + $('#locationCity').val() + ', ' + $('#locationZip').val() + ', ' + $('#locationCountry').val();
    geocoder.geocode({'address': address}, function(results, status) {
        if (status === 'OK') {
            var lat = results[0].geometry.location.lat();
            var lng = results[0].geometry.location.lng();
            $('#location_lat').val(lat);
            $('#location_lng').val(lng);
            var location = {'lat':lat, 'lng':lng};
            placeMarker(location);
        }
    });
}

