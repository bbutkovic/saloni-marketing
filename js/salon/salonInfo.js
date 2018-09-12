$(document).ready(function() {
    $('#salonLogo').on('change', function() {
        readIMG(this);
    });

    $('#locationLogo').on('change', function() {
        readIMG(this);
    });

});

function submitSalonInfo() {
    var form = new FormData();
    form.append('salon_name', $('#businessName').val());
    form.append('contact_name', $('#businessContactName').val());
    form.append('salon_email', $('#emailAddress').val());
    form.append('salon_phone', $('#businessPhone').val());
    form.append('salon_mobile', $('#businessMobile').val());
    form.append('salon_type', $('#businessType').val());
    form.append('salon_address', $('#businessAddress').val());
    form.append('salon_city', $('#businessCity').val());
    form.append('salon_country', $('#businessCountry').val());
    form.append('salon_zip', $('#businessZip').val());
    form.append('time_format', $('#timeFormat1').is(':checked') ? 'time-24' : 'time-ampm');
    form.append('salon_currency', $('#businessCurrency').val());
    form.append('week_start', $('#weekStart').val());
    form.append('time_zone', $('#timeZone').val());
    form.append('salon_logo', $('#salonLogo')[0].files[0]);

    $.ajax({
        type: 'post',
        url: salon_info_route,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: form,
        success: function(data) {
            unsaved = false;

            if(data.status === 1) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }
    });
}

function submitBillingInfo() {
    var site_location = $('#siteLocation').val();
    var input_id = $('#inputId').val();
    var billing_address = $('#billingAddress').val();
    var billing_city = $('#billingCity').val();
    var billing_zip = $('#billingZip').val();
    var billing_country = $('#billingCountry').val();
    var oib = $('#billingOib').val();
    var iban = $('#billingIban').val();
    var swift = $('#billingSwift').val();
    var tax = $('#billingTax').val();

    $.ajax({
        type: 'post',
        url: billing_info_route,
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {site_location:site_location,input_id:input_id,billing_address:billing_address,billing_city:billing_city,
               billing_zip:billing_zip,billing_country:billing_country,billing_oib:oib,billing_iban:iban,billing_swift:swift,tax:tax},
        success: function(data) {
            unsaved = false;
            if(data.status === 1) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }
    });
}

function submitLocationInfo() {
    updateLocation();
    var form = new FormData();
    form.append('location_id', $('#locationId').val());
    form.append('location_name', $('#locationName').val());
    form.append('location_phone', $('#locationPhone').val());
    form.append('location_mobile_phone', $('#locationMobile').val());
    form.append('location_email', $('#locationEmail').val());
    form.append('location_address', $('#locationAddress').val());
    form.append('location_city', $('#locationCity').val());
    form.append('location_zip', $('#locationZip').val());
    form.append('location_country', $('#locationCountry').val());
    form.append('parking', $('#parkingRadio1').is(':checked') ? 1 : 0);
    form.append('credit_cards', $('#creditCards1').is(':checked') ? 1 : 0);
    form.append('disabled_access', $('#disabled1').is(':checked') ? 1 : 0);
    form.append('wifi', $('#wifi1').is(':checked') ? 1 : 0);
    form.append('pets', $('#pets1').is(':checked') ? 1 : 0);
    form.append('location_lat', $('#location_lat').val());
    form.append('location_lng', $('#location_lng').val());
    form.append('location_logo', $('#locationLogo')[0].files[0]);

    $.ajax({
        type: 'post',
        url: location_info_route,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: form,
        success: function(data) {
            unsaved = false;
            if(data.status === 1) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }
    });
}

function submitOpenHours() {
    var location_id = $('#locationId').val();
    var time_format = $('#time-format').val();
    var open_m = $('#open_m').is(':checked') ? 'on' : 'off';
    var time_start_m = $('#time_start_m').val();
    var time_end_m = $('#time_end_m').val();
    var open_t = $('#open_t').is(':checked') ? 'on' : 'off';
    var time_start_t = $('#time_start_t').val();
    var time_end_t = $('#time_end_t').val();
    var open_w = $('#open_w').is(':checked') ? 'on' : 'off';
    var time_start_w = $('#time_start_w').val();
    var time_end_w = $('#time_end_w').val();
    var open_th = $('#open_th').is(':checked') ? 'on' : 'off';
    var time_start_th = $('#time_start_th').val();
    var time_end_th = $('#time_end_th').val();
    var open_f = $('#open_f').is(':checked') ? 'on' : 'off';
    var time_start_f = $('#time_start_f').val();
    var time_end_f = $('#time_end_f').val();
    var open_sat = $('#open_sat').is(':checked') ? 'on' : 'off';
    var time_start_sat = $('#time_start_sat').val();
    var time_end_sat = $('#time_end_sat').val();
    var open_sun = $('#openSun').is(':checked') ? 'on' : 'off';
    var time_start_sun = $('#time_start_sun').val();
    var time_end_sun = $('#time_end_sun').val();

    $.ajax({
        type: 'post',
        url: open_hours_route,
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {location_id:location_id,time_format:time_format,open_m:open_m,time_start_m:time_start_m,
               time_end_m:time_end_m,open_t:open_t,time_start_t:time_start_t,time_end_t:time_end_t,
               open_w:open_w,time_start_w:time_start_w,time_end_w:time_end_w,open_th:open_th,
               time_start_th:time_start_th,time_end_th:time_end_th,open_f:open_f,time_start_f:time_start_f,
               time_end_f:time_end_f,open_sat:open_sat,time_start_sat:time_start_sat,time_end_sat:time_end_sat,
               open_sun:open_sun,time_start_sun:time_start_sun,time_end_sun:time_end_sun},
        success: function(data) {
            unsaved = false;
            if(data.status === 1) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }
    });
}

function readIMG(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.salon-logo-img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}