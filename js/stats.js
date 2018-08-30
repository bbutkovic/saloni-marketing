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

    if(start_date != '' && end_date != '') {
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
    }
}