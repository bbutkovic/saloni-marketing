$(document).ready(function() {
    var current_date = new Date();
    var month = current_date.getMonth() + 1;
    var date = current_date.getFullYear() + '-' + month + '-' + current_date.getDate();

    $.ajax({
        method: 'POST',
        url: ajax_url + 'my-appointments/upcoming-bookings',
        dataType: 'json',
        beforeSend: function (request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {'date':date, 'time':current_date.toLocaleTimeString()},
        success: function (data) {
            if(data.status === 1) {
                if(data.upcoming_bookings.length > 0) {
                    $.each(data.upcoming_bookings, function(i,v) {
                        $('.upcoming-bookings-wrap').append('<h5><strong>' + v.date + '</strong> (' + v.time + ') - ' + v.service + '</h5>');
                    });
                } else {
                    $('.upcoming-bookings-wrap').append('<h5 class="text-muted">' + no_upcoming_bookings + '</h5>');
                }

            }
        }
    });

    $('.d-table').DataTable({
        pageLength: 20,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
        ]
    });

});

function rescheduleBooking(id) {
    var staff = [];
    var staff2 = [];
    var services = [];
    var location = null;

    $.ajax({
        type: 'get',
        url: ajax_url + 'booking/get/' + id,
        success: function(data) {
            if(data.status === 1) {
                $.each(data.booking.booking, function(index,value) {
                    staff.push({'staff':value.staff});
                    staff2.push(value.staff);
                    location = value.location;
                    var service = 'service-' + value.service_id;
                    services.push(service);
                    $('.service-list').append(value.service + ', ');
                });
                $('#bookingId').val(id);

                $.ajax({
                    type: 'post',
                    url: ajax_url + 'ajax/staff/schedule',
                    beforeSend: function (request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                    },
                    dataType: 'json',
                    data: {'location': location, 'staff': staff},
                    success: function (data) {
                        if (data.status != 0) {
                            var date = new Date();

                            date.setDate(date.getDate());

                            $('#datepicker').datepicker({
                                keyboardNavigation: false,
                                forceParse: false,
                                startDate: date,
                                datesDisabled: data.disabled_dates
                            }).on('changeDate', function () {
                                $('.table-available-hours').removeClass('hidden');
                                $('#selectedDate').val(
                                    $('#datepicker').datepicker('getFormattedDate')
                                );

                                var selected_date = $('#datepicker').datepicker('getFormattedDate');
                                $('#bookingDate').val(selected_date);

                                $.ajax({
                                    type: 'post',
                                    url: ajax_url + 'ajax/schedule/get-schedule',
                                    beforeSend: function(request) {
                                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                                    },
                                    data: {'location':location,'staff':staff2,'service':services,'selected_date':selected_date},
                                    success: function(data) {
                                        $.each(data, function(index, value) {
                                            $('.table-schedule').append('<tr class="available-time" data-id="time-' + index + '" onclick="selectTime(' + index + ')"><td class="time-from text-center" data-start="' + value.from + '" data-end="' + value.to + '">' + value.from + ' - ' + value.to + '</td></tr>');
                                        });
                                    }
                                });
                            });

                            $('.datepicker').removeClass('datepicker-inline');
                        }
                    }
                });

            } else {
                toastr.error(data.message);
            }
        }
    });

    $('#rescheduleBookingModal').modal('show');
}

function selectTime(index) {
    var el = $('tr[data-id=time-' + index + ']');
    var from = el.find('.time-from').data('start');
    var to = el.find('.time-from').data('end');
    $('#bookingFrom').val(from);
    $('#bookingTo').val(to);
    $('.new-time').html(new_time_trans + ' ' + from + ' - ' + to);
}

function submitReschedule() {
    clearAppendedValues();
    var date = $('#bookingDate').val();
    var id = $('#bookingId').val();
    var from = $('#bookingFrom').val();
    var to = $('#bookingTo').val();

    $.ajax({
        type: 'post',
        url: ajax_url + 'ajax/reschedule/booking',
        beforeSend: function (request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        dataType: 'json',
        data: {'id':id,'date':date,'from':from,'to':to},
        success: function (data) {
            if (data.status === 1) {
                window.location.reload();
            } else {
                toastr.error(data.message);
            }
        }
    });
}

function cancelBooking(id) {
    clearAppendedValues();

    $('#bookingIdCancel').val(id);

    $.ajax({
        type: 'get',
        url: ajax_url + 'booking/get/' + id,
        success: function(data) {
            if(data.status === 1) {
                $.each(data.booking.booking, function(index,value) {
                    $('.services-list').append('<div class="service-wrap appended-status-value">' +
                        '<div class="col-xs-1 checkbox checkbox-primary">' +
                        '<input type="checkbox" id="checkbox' + value.id + '" class="service-checkbox" data-id="' + value.id + '" data-service="' + value.service + '" data-price="' + value.price + '" name="select-' + value.id + '">' +
                        '<label for="checkbox' + value.id + '"></label></div><div class="col-xs-5 service-name"><h2>' + value.service + '</h2></div>' +
                        '<div class="col-xs-5 service-price"><h2>' + value.price + '</h2></div></div>');
                });
            }
        }
    });

    $('#cancelBookingModal').modal('show');
}

function submitCancel() {

    clearAppendedValues();

    var id = $('#bookingIdCancel').val();
    var action = 'status_cancelled';
    var booking = [];

    $('.service-checkbox').each(function() {
        if($(this).is(':checked')) {
            booking.push($(this).data('id'));
        }
    });

    $.ajax({
        type: 'post',
        url: ajax_url + 'booking/status/update',
        beforeSend: function (request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        dataType: 'json',
        data: {'id':id,'action':action,'services':booking},
        success: function(data) {
            $('.appended-status-value').remove();
            $('#cancelBookingModal').modal('hide');
            toastr.success(data.message);
            if(data.status === 1) {
                window.location.reload();
            }

        }
    });
}

function openPaymentForm(id) {
    clearAppendedValues();
    $('#bookingPaymentId').val(id);
    $('#stripePaymentId').val(id);
    $.ajax({
        type: 'get',
        url: ajax_url + 'booking/payment/' + id,
        success: function(data) {
            if(data.status === 1) {
                $.each(data.payment_info, function(index,value) {
                    $('.services-list').append('<h3 class="text-center m-t m-b">' + value.service + ' - <strong>' + value.price + ' ' + value.currency +'</strong></h3>');
                });
                $.each(data.payment_options, function(index,value) {
                    console.log(value.payment_gateway);
                    if(value.payment_gateway == 'paypal') {
                        $('#payPalPayment').removeClass('hidden');
                    }
                    if(value.payment_gateway == 'stripe') {
                        $('#stripePayment').removeClass('hidden');
                    }
                    if(value.payment_gateway == 'wspay') {
                        $('#wsPayPayment').removeClass('hidden');
                    }
                });
            }
        }
    });

    $('#paymentFormModal').modal('show');
}

function clearAppendedValues() {
    $('.appended-status-value').each(function() {
        $(this).remove();
    });
}

function submitPayPalPayment() {
    $('#payment-form').submit();
}

function submitStripePayment() {
    $('#stripeFormModal').modal('show');
}

function submitWsPayPayment() {
    var booking_id = $('#bookingPaymentId').val();
    window.location.href = ajax_url + 'payment/wspay/' + booking_id;
}

function addBookingToCalendar(id) {

    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/calendar/' + id + '/links',
        success: function(data) {
            if(data.status === 1) {
                $('#googleCalendarLink').attr('href', data.calendar_links.google);
                $('#iCalLink').attr('href', data.calendar_links.ics);
                $('#yahooCalLink').attr('href', data.calendar_links.yahoo);
            } else {
                toastr.error(data.message);
            }
        }
    });

    $('#calendarOptionsModal').modal('show');
}

function submitPrivacySettings() {
    var sms_reminders = $('#smsReminders').is(':checked') ? 1 : 0;
    var sms_marketing = $('#smsMarketing').is(':checked') ? 1 : 0;
    var email_reminders = $('#emailReminders').is(':checked') ? 1 : 0;
    var email_marketing = $('#emailMarketing').is(':checked') ? 1 : 0;
    var viber_reminders = $('#viberReminders').is(':checked') ? 1 : 0;
    var viber_marketing = $('#viberMarketing').is(':checked') ? 1 : 0;
    var facebook_reminders = $('#facebookReminders').is(':checked') ? 1 : 0;
    var facebook_marketing = $('#facebookMarketing').is(':checked') ? 1 : 0;

    $.ajax({
        type: 'post',
        url: ajax_url + 'privacy/update',
        beforeSend: function (request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        dataType: 'json',
        data: {'sms_reminders':sms_reminders,'sms_marketing':sms_marketing,'email_reminders':email_reminders,'email_marketing':email_marketing,'viber_reminders':viber_reminders,'viber_marketing':viber_marketing,'facebook_reminders':facebook_reminders,'facebook_marketing':facebook_marketing},
        success: function(data) {
            if(data.status === 1) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }

        }
    });
}