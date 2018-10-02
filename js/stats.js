var start_date = '';
var end_date = '';

$(document).ready(function() {
    $('.start-date-picker').on('change', function() {
        start_date = $(this).val();
        changeStatsDate();
    });

    $('.end-date-picker').on('change', function() {
        end_date = $(this).val();
        changeStatsDate();
    });
});

function changeStatsDate() {
    start_date = $('.start-date-picker').val();
    end_date = $('.end-date-picker').val();
    var date1 = new Date(start_date);
    var date2 = new Date(end_date);
    if(date1 != '' && date2 != '' && date1.getTime() <= date2.getTime()) {
        $.ajax({
            type: 'post',
            url: ajax_url + 'ajax/change-stats-date',
            dataType: 'json',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            data: {'start':start_date,'end':end_date},
            success: function(data) {
                if(data.status === 1) {
                    window.location.reload();
                } else {
                    toastr.error(data.message);
                }
            }
        });
    } else {
        toastr.error(invalid_dates);
    }
}