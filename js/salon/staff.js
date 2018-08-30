function updateStaffSettings() {
    var email_rosters = $('#emailRosters1').is(':checked') ? 1 : 0;
    var weekday = $('#weekday').val();
    var time = $('#emailTimeSelect').val();

    $.ajax({
        type: 'post',
        url: settings_route,
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {email_rosters:email_rosters,weekday:weekday,time:time},
        success: function(data) {
            unsaved = false;
            if(data.status === 1) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }
    })
}