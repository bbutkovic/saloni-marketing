$(document).ready(function() {
    
    $('#addClientNote').on('click', function() {
        $('#bookingNoteId').val('');
        var booking_id = $('.hidden').text();
        
        $('#showAppointmentActions').modal('hide');
        $('#addCustomerNote').modal('show');
        $('#bookingNoteId').val(booking_id);
    });

    $('#exportToCalendar').on('click', function() {
        window.location.href = ajax_url + 'calendar/export';
    });

    var settings_form = $('#updateCalendar');
    var colors_form = $('#updateCalendarColors');

    settings_form.on('submit', function(ev) {
        ev.preventDefault();

        var appointment_interval = $('#appointmentInterval').val();
        var default_tab = $('#defaultTab').val();
        var appointment_colors = $('#appointmentColors').val();
        var appointment_number = $('#appointmentNumber').val();
        var staff_photo = $('#staffPhoto1').is(':checked') ? 1 : 0;
        var drag_and_drop = $('#dragAndDrop1').is(':checked') ? 0 : 1;
        var waiting_list = $('#waitingList').is(':checked') ? 1 : 0;
        var client_notes = $('#clientNotes').is(':checked') ? 1 : 0;
        var phone_number = $('#phoneNumber').is(':checked') ? 1 : 0;
        var email = $('#email').is(':checked') ? 1 : 0;
        var address = $('#address').is(':checked') ? 1 : 0;
        var new_client_indicator = $('#newClientIndicator').is(':checked') ? 1 : 0;
        var referrer = $('#referrer').is(':checked') ? 1 : 0;

        $.ajax({
            type: 'post',
            url: update_settings_route,
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            dataType: 'json',
            data: {
                appointment_interval: appointment_interval,
                default_tab: default_tab,
                appointment_colors: appointment_colors,
                appointment_number: appointment_number,
                staff_photo: staff_photo,
                drag_and_drop: drag_and_drop,
                waiting_list: waiting_list,
                client_notes: client_notes,
                phone_number: phone_number,
                email: email,
                address: address,
                new_client_indicator: new_client_indicator,
                referrer: referrer
            },
            success: function (data) {
                unsaved = false;
                if (data.status === 1) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            }
        });
    });

    colors_form.on('submit', function(ev) {
        ev.preventDefault();

        var status_booked = $('#spectrumBooked').val();
        var status_confirmed = $('#spectrumConfirmed').val();
        var status_complete = $('#spectrumComplete').val();
        var status_cancelled = $('#spectrumCancelled').val();
        var status_waiting_list = $('#spectrumOnline').val();
        var status_rebooked = $('#spectrumRebokeed').val();
        var status_arrived = $('#spectrumArrived').val();
        var status_noshow = $('#spectrumNoShow').val();
        var status_paid = $('#spectrumPaid').val();

        $.ajax({
            type: 'post',
            url: update_colors_route,
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            dataType: 'json',
            data: {
                status_booked: status_booked,
                status_confirmed: status_confirmed,
                status_complete: status_complete,
                status_cancelled: status_cancelled,
                status_waiting_list: status_waiting_list,
                status_rebooked: status_rebooked,
                status_arrived: status_arrived,
                status_noshow: status_noshow,
                status_paid: status_paid
            },
            success: function (data) {
                unsaved = false;
                if (data.status === 1) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            }
        });
    });
});

function getFullBooking(id,action) {
    $.ajax({
        type: 'get',
        url: ajax_url + 'booking/get/' + id,
        success: function(data) {
            if(data.status === 1) {
                $('#appointmentOptionsModal').addClass('hidden');
                $('#appointmentSelectModal').removeClass('hidden');
                $.each(data.booking.booking, function(index,value) {
                   $('#appointmentSelectModal').append('<div class="service-wrap appended-status-value">' +
                       '<div class="col-xs-1 checkbox checkbox-primary">' +
                       '<input type="checkbox" id="checkbox' + value.id + '" class="service-checkbox" data-id="' + value.id + '" data-service="' + value.service + '" data-price="' + value.price + '" name="select-' + value.id + '">' +
                       '<label for="checkbox' + value.id + '"></label></div><div class="col-xs-5 service-name"><h2>' + value.service + '</h2></div>' +
                       '<div class="col-xs-5 service-price"><h2>' + value.price + '</h2></div></div>');
                });
                $('#appointmentSelectModal').append('<div class="row appended-status-value m-t-lg">' +
                    '<button type="button" class="btn btn-danger m-l m-r" onclick="goBackToOptions()"><i class="fa fa-arrow-left"></i> ' + trans_back + '</button>' +
                    '<button type="button" id="selectedAction" data-action="' + action + '" class="btn btn-success" onclick="submitAppointmentChanges()"> ' + trans_submit + '</button></div>');
            } else {
                toastr.error(data.message);
            }
        }
    });

}

function goBackToOptions() {
    $('.appended-status-value').each(function() {
       $(this).remove();
    });
    $('#appointmentSelectModal').addClass('hidden');
    $('#appointmentOptionsModal').removeClass('hidden');
}

function submitAppointmentChanges() {
    var id = $('.hidden-booking-id').text();
    var action = $('#selectedAction').data('action');
    var booking = [];

    $('.service-checkbox').each(function() {
        if($(this).is(':checked')) {
            booking.push($(this).data('id'));
        }
    });

    if(action == 'create_invoice') {
        $.ajax({
            type: 'post',
            url: ajax_url + 'booking/create-invoice',
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            dataType: 'json',
            data: {'id':id,'services':booking},
            success: function(data) {
                if(data.status != 1) {
                    toastr.error(data.message);
                }
            },
            async: false
        });
    }
    $.ajax({
        type: 'post',
        url: ajax_url + 'booking/status/update',
        beforeSend: function (request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        dataType: 'json',
        data: {'id':id,'action':action,'services':booking},
        success: function(data) {
            if(data.status === 1) {
                toastr.success(data.message);
                $('#calendar').fullCalendar('removeEvents');
                var events = [];
                $.each(data.events, function(i,v) {
                    var booking = {
                        id: v.id, type: v.type, type_id: v.type_id, client_id: v.client_id, price: v.price, duration: v.duration,
                        staff_id: v.staff_id, staff_first_name: v.staff_first_name, staff_last_name: v.staff_last_name, title: v.title,
                        start: v.start, end: v.end, status: v.status, status_trans: v.status_trans, color: v.color, cust_id: v.client_id,
                        cust_first_name: v.customer_first_name, cust_last_name: v.customer_last_name, cust_phone: v.customer_phone,
                        cust_email: v.customer_email, cust_address: v.customer_address, cust_label: v.customer_label,
                        cust_label_color: v.customer_label_color, custom_field_1: v.custom_field_1, custom_field_2: v.custom_field_2,
                        custom_field_3: v.custom_field_3, custom_field_4: v.custom_field_4, customer_note: v.customer_note,
                        created_at: v.created_at, updated_at: v.updated_at, created_by: v.created_by
                    };
                    events.push(booking);
                });
                $('#calendar').fullCalendar('addEventSource', events);
                $('#calendar').fullCalendar('rerenderEvents');

                $('.appended-status-value').each(function() {
                    $(this).remove();
                });
                $('#appointmentSelectModal').addClass('hidden');
                $('#appointmentOptionsModal').removeClass('hidden');
                $('#showAppointmentActions').modal('hide');
            } else {
                toastr.error(data.message);
            }
        }
    });
}