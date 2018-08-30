var schedule = {};
var repeat_weeks = 0;
var counter = 1;

$(document).ready(function() {

    $('#staffPhoto').on('change', function() {
        readIMG(this);
    });
    
    $("#selectedDate").on('changeDate', function() {
        $('.starting-date-wrap').removeClass('active');
        $('.step-2').addClass('active').removeClass('disabled');
        $('.shift-select-wrap').addClass('active');
        
        $('#selectedDate_input').val(
            $('#selectedDate').datepicker('getFormattedDate')
        );
    });
    
    $('#repeatWeeks').on('change', function() {
        
        var repeat_weeks = $('#repeatWeeks').val();

        $('.shift-select-wrap').removeClass('active');
        $('.step-3').addClass('active').removeClass('disabled');
        $('.add-schedule-wrap').addClass('active');
        $('.submit-schedule').attr('data-repeats', repeat_weeks);
        
        if(repeat_weeks == 1) {
            $('.submit-schedule').html(button_finish);
        } else {
            $('.submit-schedule').html(button_next);
        }
        
        if(repeat_weeks != 1) {
            $('.shift-indicator').html(trans_shift + ' A');
        }
        
    });
    
    $('.submit-schedule').on('click', function() {
        var repeats = $(this).data('repeats');
        var current_repeats = repeats - repeat_weeks;
        
        counter++;

        if(repeats == 2 && counter === 2) {
            $('.submit-schedule').html(button_finish);
        } else if (repeats == 3 && counter === 3) {
            $('.submit-schedule').html(button_finish);
        } else if (repeats == 4 && counter === 4) {
            $('.submit-schedule').html(button_finish);
        }
        
        submitSchedule(schedule, current_repeats, counter);
        
    });
    
    $('.step-1').on('click', function() {
        $('.starting-date-wrap').css('display', 'block');
        $('.shift-select-wrap').css('display', 'none');
        $('.add-schedule-wrap').css('display', 'none');
    });
    
    $('.step-2').on('click', function() {
        $('.starting-date-wrap').css('display', 'none');
        $('.shift-select-wrap').css('display', 'block');
        $('.add-schedule-wrap').css('display', 'none');
    });
    
    $('.step-3').on('click', function() {
        $('.starting-date-wrap').css('display', 'none');
        $('.shift-select-wrap').css('display', 'none');
        $('.add-schedule-wrap').css('display', 'block');
    });

    $('.lunch-start-class').on('change', function() {
        var weekday = $(this).data('weekday');
        var time = $(this).val() + ':00';
        var end = moment(time, 'HH:mm:ss').add(30, 'minutes').format('HH:mm');
        $('#lunch_end_' + weekday).val(end);
    });
});

function submitSchedule(schedule, repeats, counter) {

    var date = $('#selectedDate_input').val();
    date = moment(date).format('YYYY-MM-DD');

    var date_arr = [];

    $('.day-class').each(function() {

        var element = $(this);
        var working_el = element.find('.working-class').val();
        if(element.find('.working-class').is(':checked')) {
            var working_day = 1;
        } else {
            var working_day = 0;
        }
        var working_start = element.find('.work-start-class').val();
        var working_end = element.find('.work-end-class').val();
        var lunch_start = element.find('.lunch-start-class').val();
        var lunch_end = element.find('.lunch-end-class').val();

        date_arr.push({'working': working_day,'work_start':working_start,'work_end':working_end,'lunch_start':lunch_start,'lunch_end':lunch_end});

    });

    schedule[date] = {
        date_arr
    };

    var next_date = moment(date).add(7, 'days');
    var next_date_format = next_date.format('YYYY-MM-DD');
    $('#selectedDate_input').val(next_date_format);

    clearHours();
    repeat_weeks++;
    $('.submit-schedule').attr('data-repeats', repeats);

    if(repeats == 1) {
        $('#update-hours').addClass('muted');
        $('.client-loader').removeClass('hidden');

        var ajax_weeks = $('#repeatWeeks').val();
        var ajax_repeat = $('#repeatFor').val();
        var ajax_uid = $('#userId').val();

        $.ajax({
            type: 'post',
            url: ajax_url + '/profile/set-staff-hours',
            dataType: 'json',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            data: {'uid':ajax_uid, 'repeatFor':ajax_repeat, 'repeatWeeks':ajax_weeks,'schedule':schedule},
            success: function(data) {
                $('#update-hours').removeClass('muted');
                $('.client-loader').addClass('hidden');
                if(data.status === 1) {
                    window.location.reload();
                } else {
                    toastr.error(data.message);
                }
            }
        });
    } else {
        if (counter === 2) {
            $('.shift-indicator').html(trans_shift + ' B');
            swal(trans_shift + ' B');
        } else if (counter === 3) {
            $('.shift-indicator').html(trans_shift + ' C');
            swal(trans_shift + ' C');
        } else if (counter === 4) {
            $('.shift-indicator').html(trans_shift + ' D');
            swal(trans_shift + ' D');
        }

        counter++;
    }
    
}

function updateStaffServices(id) {
    var services = [];
    
    $('.service-selection').each(function(index, value) {
        var service = $(this);
        services.push({'service': service[0].name, 'value': service[0].checked});    
    });
    
    $.ajax({
        type: 'post',
        url: ajax_url + '/services/update',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'uid':id, 'services':services},
        success: function(data) {
            toastr.success(data.message);
        }
    });
}

function updateUserProfile() {
    var form = new FormData();
    form.append('user_id', $('#userId').val());
    form.append('first_name', $('#firstName').val());
    form.append('last_name', $('#lastName').val());
    form.append('birthday', $('#birthday').val());
    form.append('phone', $('#phone').val());
    form.append('address', $('#address').val());
    form.append('city', $('#city').val());
    form.append('staff_photo', $('#staffPhoto')[0].files[0]);

    $.ajax({
        type: 'post',
        url: update_profile_route,
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

function deleteSchedule(id) {
    swal({
        title: trans_warning,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonColor: "#52B3D9",
        confirmButtonText: 'Yes',
        closeOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'get',
                url: ajax_url + 'ajax/delete-schedule/' + id,
                success: function (data) {
                    if (data.status === 1) {
                        $('#staffScheduleTable').css('display', 'none')
                    } else if (data.status === 0 && data.bookings === 1) {
                        swal({
                            title: trans_warning,
                            text: delete_warning,
                            type: "warning",
                            showCancelButton: true,
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: "#52B3D9",
                            confirmButtonText: 'Yes',
                            closeOnConfirm: true,
                        }, function (isConfirm) {
                            if (isConfirm) {
                                $.ajax({
                                    method: 'post',
                                    url: ajax_url + 'schedule/delete/confirm',
                                    beforeSend: function (request) {
                                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                                    },
                                    data: {'id': id},
                                    success: function (data) {
                                        if (data.status === 1) {
                                            $('#staffScheduleTable').css('display', 'none')
                                        }
                                    }
                                });
                            }
                        });
                    } else {
                        toastr.error_message(':(');
                    }
                }
            })
        }
    });
}

function updateStaffSecurity() {

    var user_id = $('#employeeId').val();
    var email = $('#email').val();
    var password = $('#password_new').val();
    var password_confirmation = $('#new_password_confirm').val();

    $.ajax({
        type: 'post',
        url: update_security_route,
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {'user_id':user_id,'email':email,'password':password,'password_confirmation':password_confirmation},
        success: function(data) {
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
            $('.staff-img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}