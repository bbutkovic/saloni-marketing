@extends('main')


@section('styles')
{{ HTML::style('css/plugins/fullcalendar/fullcalendar.min.css') }}
{{ HTML::style('css/plugins/datepicker/datepicker.css') }}
@endsection

@section('scripts')
{{ HTML::script('js/calendar.js') }}
{{ HTML::script('js/plugins/datepicker/datepicker.js') }}
{{ HTML::script('js/plugins/momentjs/moment.min.js') }}
{{ HTML::script('js/plugins/fullcalendar/fullcalendar.js') }}
{{ HTML::script('js/plugins/fullcalendar/locale-all.js') }}
@endsection

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-12">
        <h2 class="section-heading pull-left calendar-title">{{ trans('salon.appointment_view') }}</h2>
        <select id="staffPicker" class="btn btn-default select-lang section-heading m-b m-l" onchange="getStaffBooking()">
            <option value="">{{ trans('salon.all_staff') }}</option>
            @foreach($staff_list as $staff)
            <option value="{{ $staff->id }}" @if($selected_staff == $staff->id) ? selected : null @endif>{{ $staff->user_extras->first_name }} {{ $staff->user_extras->last_name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="wrapper wrapper-content">
    <div class="row animated fadeInDown">
        <div class="col-lg-10">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="ibox float-e-margins">
                <div class="ibox-content m-b">
                    <div id="exportToCalendar">
                        <img class="calendar-icon" src="{{ URL::to('/').'/images/gcal.png' }}" alt="Google Calendar">
                        <h3 class="text-muted text-center">{{ trans('salon.sync_with_gcal') }}</h3>
                    </div>
                </div>
                <div class="ibox-content">
                    <h5 class="text-center">{{ trans('salon.calendar_colors') }}</h5>
                    <hr>
                    @if($calendar_options->appointment_colors === 'status')
                    <small class="text-muted">{{ trans('salon.status') }}</small>
                    <div class="colors-wrap m-t">
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $calendar_colors->status_booked }};"></span><span class="color-status">{{ trans('salon.status_booked') }}</span>
                        </div>
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $calendar_colors->status_complete }};"></span><span class="color-status">{{ trans('salon.status_complete') }}</span>
                        </div>
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $calendar_colors->status_waiting_list }};"></span><span class="color-status">{{ trans('salon.status_waiting_list') }}</span>
                        </div>
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $calendar_colors->status_arrived }};"></span><span class="color-status">{{ trans('salon.status_arrived') }}</span>
                        </div>
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $calendar_colors->status_confirmed }};"></span><span class="color-status">{{ trans('salon.status_confirmed') }}</span>
                        </div>
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $calendar_colors->status_cancelled }};"></span><span class="color-status">{{ trans('salon.status_cancelled') }}</span>
                        </div>
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $calendar_colors->status_rebooked }};"></span><span class="color-status">{{ trans('salon.status_rebooked') }}</span>
                        </div>
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $calendar_colors->status_noshow }};"></span><span class="color-status">{{ trans('salon.status_noshow') }}</span>
                        </div>
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $calendar_colors->status_paid }};"></span><span class="color-status">{{ trans('salon.status_paid') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('calendarSettings') }}" class="btn btn-default m-t edit-colors-btn">{{ trans('salon.edit_colors') }}</a>
                    @elseif ($calendar_options->appointment_colors === 'category')
                    <small class="text-muted">{{ trans('salon.category') }}</small>
                    <div class="colors-wrap m-t">
                        @foreach($category_list as $category)
                        <div class="color">
                            <span class="color-shape" style="background-color: {{ $category->cat_color }};"></span><span class="color-status">{{ $category->name }}</span>
                        </div>
                            @foreach($category->group as $group)
                            <div class="color m-l">
                                <span class="color-shape" style="background-color: {{ $group->group_color }};"></span><span class="color-status">{{ $group->name }}</span>
                            </div>
                                @foreach($group->subcategory as $subgroup)
                                <div class="color m-l">
                                    <span class="color-shape" style="background-color: {{ $subgroup->subgroup_color }};"></span><span class="color-status">{{ $subgroup->name }}</span>
                                </div>
                                @endforeach
                            @endforeach

                        @endforeach
                    </div>
                    <a href="{{ route('salonServices') }}" class="btn btn-default m-t edit-colors-btn">{{ trans('salon.edit_colors') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.appointmentActions')

@include('partials.booking.editBooking')

<script>

    var events = [];
    var openCloseTimes = [];
    var hidden_days = [];
    var staff_selection = {{ $booking_options->staff_selection }};
    var overlaping_booking = '{{ trans('salon.booking_already_exists') }}';
    var estimated_price = '{{ trans('salon.estimated_price') }}';
    var booking_created_at = '{{ trans('salon.booking_created_at') }}';
    var booking_updated_at = '{{ trans('salon.booking_updated_at') }}';
    var booking_created_by = '{{ trans('salon.booking_created_by') }}';
    var reschedule_booking = '{{ trans('salon.reschedule_booking') }}';
    var booking_rescheduled = '{{ trans('salon.booking_rescheduled') }}';
    var reschedule_error = '{{ trans('salon.reschedule_error') }}';
    var booking_exists = '{{ trans('salon.booking_exists') }}';
    var user_location = {{ Auth::user()->location_id }};
    var select_service_trans = '{{ trans('salon.select_service') }}';
    var display_first_name = {{ $booking_options->first_name_only }};
    var week_start = {{ $salon->week_starting_on }};
    var confirm_booking = '{{ trans('salon.confirm_booking') }}';
    var booking_multiple_drag = '{{ trans('salon.booking_multiple_drag') }}';
    var client_name = '{{ $salon->client_settings->name_format }}';
    var trans_back = '{{ trans('salon.back') }}';
    var trans_submit = '{{ trans('salon.submit') }}';

    @foreach($bookings as $booking)
        var booking = {
            id: '{{ $booking["id"] }}',
            type: '{{ $booking["type"] }}',
            type_id: '{{ $booking["type_id"] }}',
            client_id: '{{ $booking["client_id"] }}',
            price: '{{ $booking["price"] }}',
            duration: '{{ $booking["duration"] }}',
            staff_id: '{{ $booking["staff_id"] }}',
            staff_first_name: '{{ $booking["staff_first_name"] }}',
            staff_last_name: '{{ $booking["staff_last_name"] }}',
            title: '{{ $booking["title"] }}',
            start: '{{ $booking["start"] }}',
            end: '{{ $booking["end"] }}',
            status: '{{ $booking["status"] }}',
            status_trans: '{{ $booking["status_trans"] }}',
            color: '{{ $booking["color"] }}',
            cust_id: '{{ $booking["client_id"] }}',
            cust_first_name: '{{ $booking["customer_first_name"] }}',
            cust_last_name: '{{ $booking["customer_last_name"] }}',
            cust_phone: @if($calendar_settings->phone_number === 1) '{{ $booking["customer_phone"] }}' @else '' @endif,
            cust_email: @if($calendar_settings->email_address === 1) '{{ $booking["customer_email"] }}' @else '' @endif,
            cust_address: @if($calendar_settings->address === 1 && $booking["customer_address"] != null) '{{ $booking["customer_address"] }}' @else '' @endif,
            cust_label: '{{ $booking["customer_label"] }}',
            cust_label_color: '{{ $booking["customer_label_color"] }}',
            custom_field_1: @if('{{ $booking["custom_field_1"] }}' != null) '{{ $booking["custom_field_1"] }}' @else null @endif,
            custom_field_2: @if('{{ $booking["custom_field_2"] }}' != null) '{{ $booking["custom_field_2"] }}' @else null @endif,
            custom_field_3: @if('{{ $booking["custom_field_3"] }}' != null) '{{ $booking["custom_field_3"] }}' @else null @endif,
            custom_field_4: @if('{{ $booking["custom_field_4"] }}' != null) '{{ $booking["custom_field_4"] }}' @else null @endif,
            customer_note: '{{ $booking["customer_note"] }}',
            created_at: '{{ $booking["created_at"] }}',
            updated_at: '{{ $booking["updated_at"] }}',
            created_by: '{{ $booking["created_by"] }}'
        };
        events.push(booking);
    @endforeach

    @foreach($hidden_days as $day)
        var day = {{ $day }};
        hidden_days.push(day);
    @endforeach
    var options = {};
    $(document).ready(function() {
        var prompt = false;
        options = {
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            allDaySlot: false,
            defaultView: '{{ $calendar_options->default_tab }}',
            locale: '{{ $locale }}',
            slotDuration: '{{ $calendar_options->appointment_interval }}',
            editable: {{ $calendar_options->drag_and_drop }},
            events: events,
            hiddenDays: hidden_days,
            eventRender: function(event, element) {
                var start_time = moment.utc(event.start._i);
                var end_time = moment.utc(event.end._i);
                element.html('<p>' + start_time.format("HH:mm") + ' - ' + end_time.format("HH:mm") + ' ' + event.title + ' (' + event.staff_first_name + ' ' + event.staff_last_name + ')</p>');
            },
            eventLimit: {{ $calendar_options->appointment_number }},
            minTime: '{{ $location_hours["min"] }}',
            maxTime: '{{ $location_hours["max"] }}',
            eventDrop: function(event,delta,revertFunc) {
                var selected_start = moment.utc(event.start._i);
                var selected_to = moment.utc(event.end._i);
                var selected_staff = event.staff_id;

                if(event.type != 'multiple') {
                    $.each(events, function(index,value) {
                        var booking_date = moment(value.start).format('YYYY-MM-DD');
                        var booking_start = moment(value.start).format('HH:mm');
                        var booking_end = moment(value.end).format('HH:mm');
                        var booking_staff = value.staff_id;
                        if(event.id != value.id && booking_date == selected_start.format('YYYY-MM-DD') && booking_staff == selected_staff && selected_to.format('HH:mm') > booking_start && selected_start.format('HH:mm') < booking_end) {
                            prompt = true;
                        }
                    });

                    if(prompt) {
                        var title = booking_exists;
                    } else {
                        var title = reschedule_booking;
                    }
                    swal({
                        title: title,
                        type: "warning",
                        showCancelButton: true,
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: "#52B3D9",
                        confirmButtonText: 'Yes',
                        closeOnConfirm: true,
                    }, function (isConfirm) {
                        if (!isConfirm) {
                            revertFunc();
                            prompt = false;
                        } else {
                            var event_id = event.id;
                            $.ajax({
                                type: 'post',
                                url: ajax_url + 'ajax/reschedule',
                                dataType: 'json',
                                beforeSend: function(request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                                },
                                data: {'id':event_id,'start':selected_start.format('YYYY-MM-DD HH:mm'),'end':selected_to.format('YYYY-MM-DD HH:mm'),'date':selected_start.format('YYYY-MM-DD')},
                                success: function(data) {
                                    if(data.status === 1) {
                                        toastr.success(booking_rescheduled);
                                        prompt = false;
                                    } else {
                                        toastr.error(reschedule_error);
                                        prompt = false;
                                    }
                                }
                            });
                        }
                    });
                } else {
                    revertFunc();
                    toastr.error(booking_multiple_drag);
                }
            },
            eventMouseover: function(data, event, view) {
                if(client_name === 'first_last') {
                    var name_formated = data.cust_first_name + ' ' + data.cust_last_name;
                } else {
                    var name_formated = data.cust_last_name + ' ' + data.cust_first_name;
                }
                var tooltip = '<div class="booking-info-wrap">'+
                                '<h2 class="text-center">' + name_formated + '<small  style="color: ' + data.cust_label_color + '"> ' + data.cust_label + '</small></h2><hr>'+
                                '@if($calendar_settings->phone_number === 1)<h4><i class="fa fa-phone"></i> ' + data.cust_phone + '</h4>@endif @if($calendar_settings->email_address === 1)<h4><i class="fa fa-envelope"></i> ' + data.cust_email + '</h4>@endif'+
                                '@if($calendar_settings->address === 1)<hr><h5>' + data.cust_address + ' ' + data.cust_city + '</h5>@endif'+
                                '@foreach($custom_fields as $field) <h5><strong>{{ $field->field_title }}:</strong> ' + data.{{ $field->field_name }} + '</h5>@endforeach</div>';

                $('body').append(tooltip);

                $(this).mouseover(function (e) {
                    $(this).css('z-index', 10000);
                    $('.booking-info-wrap').fadeIn('500');
                    $('.booking-info-wrap').fadeTo('10', 1.9);
                }).mousemove(function (e) {
                    $('.booking-info-wrap').css('top', e.pageY + 10);
                    $('.booking-info-wrap').css('left', e.pageX + 20);
                });
            },
            eventMouseout: function(data) {
                $('.booking-info-wrap').remove();
            },
            eventClick: function(event) {
                var start_time = moment.utc(event.start._i);
                var end_time = moment.utc(event.end._i);
                $('.booking-info-wrap').remove();

                if(client_name === 'first_last') {
                    var name_formated = event.cust_first_name + ' ' + event.cust_last_name;
                } else {
                    var name_formated = event.cust_last_name + ' ' + event.cust_first_name;
                }

                //get calendar links
                $.ajax({
                   type: 'get',
                   url: ajax_url + 'ajax/calendar/' + event.id + '/links',
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

                $('#showAppointmentActions').modal('show');
                $('.hidden-booking-id').html(event.id);
                $('#customerNoteArea').html(event.customer_note);
                $('.customer-link').attr('href', ajax_url + 'client/profile/' + event.cust_id);
                $('.customer-name').html('<strong>' + name_formated + '</strong><small  style="color: ' + event.cust_label_color + '"> ' + event.cust_label + '</small>');
                $('.client-note').html('<strong>' + event.customer_note + '</strong>');
                $('.service-data').html('<strong>' + event.title + ' - ' + event.staff_first_name + ' ' + event.staff_last_name + '</strong> (' + event.duration + ' min)');
                $('.service-price').html('<strong>' + estimated_price + '</strong> ' + event.price);
                $('.service-date').html(start_time.format("HH:mm") + ' - ' + end_time.format("HH:mm"));
                $('.service-creation-info').html('<strong>' + booking_created_at + '</strong>' + event.created_at + ' <strong>' + booking_updated_at + '</strong>' + event.updated_at + ' ' + booking_created_by + ' ' + event.created_by);
                $('.status-schedule').html(event.status_trans);
            }
        };

        $('#calendar').fullCalendar(options);

        $('.appointment-status button').on('click', function() {
            var action = $(this).data('status');
            var id = $('.hidden-booking-id').text();
            getFullBooking(id,action);
        });

        $('#createInvoice').on('click', function() {
            var id = $('.hidden-booking-id').text();
            var action = 'create_invoice';
            getFullBooking(id,action);
        });

        //reset values on modal close
        $('#addNewAppointment').on('hidden.bs.modal', function () {
            $('#addNewBooking').val('');
            $('#serviceSelection').html('<option value="default" selected disabled>' + select_service_trans + '</option>');
            $('#serviceDuration').val('');
            $('.booking-info').html('');
            $('.service-price').text('');
            $('#selectedDate').val('');
            $('#locationId').val('');
            $('#bookingLocation').val('');
            $('#bookingService').val('');
            $('#bookingDate').val('');
            $('#bookingFrom').val('');
            $('#bookingTo').val('');

        });
    });

</script>

@endsection