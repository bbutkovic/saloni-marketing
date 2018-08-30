$(document).ready(function() {
    tinymce.init({
        selector: ".text-campaign",
        theme: "modern",
        paste_data_images: true,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "template paste textcolor colorpicker textpattern"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview | forecolor backcolor",
        image_advtab: true,
        file_picker_callback: function(callback, value, meta) {
            if (meta.filetype == 'image') {
                $('#upload').trigger('click');
                $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                            alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
        },
        templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        }, {
            title: 'Test template 2',
            content: 'Test 2'
        }]
    });

    $("#clientInactive").ionRangeSlider({
        type: "single",
        grid: true,
        min: 0,
        max: 60,
        from: $('#clientInactive').val(),
        prefix: trans_month
    });

    $("#clientAge").ionRangeSlider({
        type: "double",
        grid: true,
        min: 0,
        max: 90,
        from: $('#clientAge').val(),
        to: $('#youngerThan').val(),
        prefix: trans_age,
        max_postfix: "+"
    });

    $("#loyaltyPoints").ionRangeSlider({
        type: "single",
        grid: true,
        min: 0,
        max: 1000,
        from: $('#loyaltyPoints').val(),
    });

    $('.campaign-medium-type input').on('change', function() {
        var id = 1;
        if($('.checkbox-email-notifications'+id).is(':checked')) {
            $('.checkbox-no-notifications'+id).prop('checked', false);
            $('.email-template'+id).parent().removeClass('hidden');
        } else {
            $('.email-template'+id).parent().addClass('hidden');
        }

        if($('.checkbox-sms-notifications'+id).is(':checked')) {
            $('.checkbox-no-notifications'+id).prop('checked', false);
            $('.sms-template'+id).parent().removeClass('hidden');
        } else {
            $('.sms-template'+id).parent().addClass('hidden');
        }

        if($('.checkbox-viber-notifications'+id).is(':checked')) {
            $('.checkbox-no-notifications'+id).prop('checked', false);
            $('.viber-template'+id).parent().removeClass('hidden');
        } else {
            $('.viber-template'+id).parent().addClass('hidden');
        }

        if($('.checkbox-messenger-notifications'+id).is(':checked')) {
            $('.checkbox-no-notifications'+id).prop('checked', false);
            $('.messenger-template'+id).parent().removeClass('hidden');
        } else {
            $('.messenger-template'+id).parent().addClass('hidden');
        }

        if($('.checkbox-push-notifications'+id).is(':checked')) {
            $('.checkbox-no-notifications'+id).prop('checked', false);
            $('.push-template'+id).parent().removeClass('hidden');
        } else {
            $('.push-template'+id).parent().addClass('hidden');
        }
    });

});

function addNewCampaign() {
    $('#addNewCampaignModal').modal('show');
}

function deleteCampaign(id) {

    swal({
        title: swal_alert,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonColor: "#52B3D9",
        confirmButtonText: 'Yes',
        closeOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                method: 'get',
                url: ajax_url + 'marketing/campaign-delete/' + id,
                success: function(data) {
                    if(data.status === 1) {
                        $('tr[data-id="campaign' + id + '"]').css('display', 'none');
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });

}

function sendCampaign(id) {

    swal({
        title: swal_alert,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonColor: "#52B3D9",
        confirmButtonText: 'Yes',
        closeOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                method: 'get',
                url: ajax_url + 'marketing/campaign-send/' + id,
                success: function(data) {
                    if(data.status === 1) {
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });

}