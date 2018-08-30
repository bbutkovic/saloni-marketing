$(document).ready(function() {

    tinymce.init({
        selector: "textarea",
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

    if($('.d-table').length) {
        $('.d-table').DataTable({
            pageLength: 20,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
            ]
        });
    }

    $('.template-for').on('change', function() {
        var type = $(this).val();
        $('.template-attachment').removeClass('hidden');

        if(type != 1) {
            $('.template-attachment').addClass('hidden');
        }

    });

    $('.template-type').on('change', function() {

        var type = $(this).val();

        $('.fields-wrap').empty();

        getContentFields(type);
    });

    $('input[name="include_voucher"]').on('change', function() {
        var check = $(this).val();
        var id = $(this).closest('form').data('id');
        if(typeof id === 'undefined') {
            id = 1;
        }
        $('.select-voucher'+id).remove();

        if(check == 1) {
            $.ajax({
                type: 'get',
                url: ajax_url + 'ajax/vouchers/get',
                success: function(data) {
                    if(data.status === 1) {

                        $('.vouchers-row'+id).append('<select class="form-control select-voucher' + id + ' m-l voucher-select" name="voucher"></select>');
                        $.each(data.vouchers, function(index,value) {
                            $('.select-voucher'+id).append('<option value="' + value.id + '">' + value.name + ' ' + value.discount + '%</option>');
                        });
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        } else {
            $('.select-voucher'+id).remove();
        }
    });

    $('.reminders-form input').on('change', function() {
        var id = $(this).closest('form').data('id');

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

function disableReminders(id) {

    if($('.checkbox-no-notifications'+id).is(':checked')) {
        $('.template-selection-wrap'+id).each(function() {
            $(this).addClass('hidden');
        });
        $('.checkbox-reminder'+id).each(function() {
            $(this).prop('checked', false);
        });
    }
}

function getContentFields(type) {
    var fields = [];
    if (type == '1') {
        fields.push('ClientFirstName','ClientLastName','AppointmentDate','AppointmentTime','Price',
            'StaffList','ServiceList','CurrentDate','BusinessName','BusinessPhone','BusinessAddress',
            'BusinessCity','BusinessPostCode','LoyaltyPoints');
    } else if (type == '2') {
        fields.push('ClientFirstName','ClientLastName','AppointmentDate','AppointmentTime','Price','StaffList','ServiceList',
            'CurrentDate','BusinessName','BusinessPhone','BusinessAddress','BusinessCity','BusinessPostCode','LoyaltyPoints');
    } else if (type == '3') {
        fields.push('ClientFirstName','ClientLastName','AppointmentDate','AppointmentTime','CurrentDate','BusinessName','BusinessPhone',
            'BusinessAddress','BusinessCity','BusinessPostCode','AvailableRescheduleDates');
    } else if (type == '4') {
        fields.push('ClientFirstName','ClientLastName','AppointmentDate','AppointmentTime','CurrentDate','BusinessName','BusinessPhone',
            'BusinessAddress','BusinessCity','BusinessPostCode');
    } else if (type == '5') {
        fields.push('ClientFirstName','ClientLastName','ClientBirthday','CurrentDate','BusinessName','BusinessPhone','BusinessAddress','BusinessCity','BusinessPostCode');
    } else if (type == '6') {
        fields.push('ClientFirstName','ClientLastName','BusinessName','BusinessPhone','BusinessAddress','BusinessCity','BusinessPostCode','LoyaltyPoints','LoyaltyStatus','LoyaltyProgram');
    } else if (type == '7') {
        fields.push('ClientFirstName','ClientLastName','CurrentDate','BusinessName','BusinessPhone','BusinessAddress','BusinessCity','BusinessPostCode');
    }

    $.each(fields, function(index,value) {
        $('.fields-wrap').append('<a href="#" class="template-field appended-field" id="templateField' + index + '" onclick="addFieldToTemplateContent(' + index + ')">' + value + '</a>');
    });
}

function addNewTemplate(id) {
    $('#addNewTemplateModal').modal('show');
}

function editMarketingTemplate(id) {
    $('#editTemplateModal').modal('show');
    $('#templateId').val(id);
    $.ajax({
        type: 'get',
        url: ajax_url + 'marketing/template/' + id,
        success: function(data) {
            if(data.status === 1) {

                $('#templateNameEdit').val(data.template.template_name);
                $('#templateSubjectEdit').val(data.template.subject);
                $('#templateForEdit').val(data.template.template_for);
                $('#templateTypeEdit').val(data.template.template_type);
                getContentFields(data.template.template_type);

                tinyMCE.activeEditor.setContent(data.template.content);

            } else {
                toastr.error(data.message);
            }
        }
    });
}

function updateTemplate() {
    var id = $('#templateId').val();
    var name = $('#templateNameEdit').val();
    var subject = $('#templateSubjectEdit').val();
    var temp_for = $('#templateForEdit').val();
    var temp_type = $('#templateTypeEdit').val();
    var content = tinyMCE.activeEditor.getContent();

    $.ajax({
        type: 'post',
        url: ajax_url + 'marketing/template/edit',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {'id':id,'name':name,'subject':subject,'temp_for':temp_for,'temp_type':temp_type,'content':content},
        success: function(data) {
            if(data.status === 1) {
                $('#editTemplateModal').modal('hide');
                $('#templateNameEdit').val('');
                $('#templateSubjectEdit').val('');
                $('#templateForEdit').val('');
                $('#templateTypeEdit').val('');
                tinyMCE.activeEditor.setContent('');

                $('#template'+id).find('.template-name-td').text(data.template[0].template_name);
                $('#template'+id).find('.template-for-td').text(data.template[0].template_for_str);
                $('#template'+id).find('.template-type-td').text(data.template[0].template_type_str);

                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }
    });
}

function deleteMarketingTemplate(id) {
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
                method: 'post',
                url: ajax_url + 'template/delete',
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: {'id':id},
                success: function(data) {
                    if(data.status === 1) {
                        toastr.success(data.message);
                        $('#template'+id).remove();
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
}

function addFieldToTemplateContent(field) {

    var field = $('#templateField' + field).text();

    if($('#tinymce').length) {
        tinyMCE.activeEditor.execCommand('mceInsertContent', false, '[' + field + ']');
    } else {
        var position = $('#basicTextarea').getCursorPosition();
        var content = $('#basicTextarea').val();
        var newContent = content.substr(0, position) + '[' + field + ']' + content.substr(position);
        $('#basicTextarea').val(newContent);
    }
}

function submitReminderSettings(id) {

    var email_template = $('.email-template-select'+id).val();
    var sms_template = $('.sms-template-select'+id).val();
    var viber_template = $('.viber-template-select'+id).val();
    var messenger_template = $('.messenger-template-select'+id).val();

    if($('.checkbox-no-notifications'+id).is(':checked')) {
        var no_notifications = 0;
    } else {
        var no_notifications = 1;
    }

    if($('.select-voucher'+id).length) {
        var voucher = $('.select-voucher'+id).val();
    } else {
        var voucher = null;
    }

    if($('#sendBefore'+id).length) {
        var send_before = $('#sendBefore'+id).val();
    } else {
        var send_before = null;
    }

    $.ajax({
        type: 'post',
        url: ajax_url + 'marketing/reminder/update',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {'type':id,'no_notifications':no_notifications,'email_temp':email_template,'sms_temp':sms_template,'viber_temp':viber_template,'messenger_temp':messenger_template,'voucher':voucher,'send_before':send_before},
        success: function(data) {
            if(data.status === 1) {
                toastr.success(data.message);
                unsaved = false;
            } else {
                toastr.error(data.message);
            }
        }
    });

}

(function ($, undefined) {
    $.fn.getCursorPosition = function () {
        var el = $(this).get(0);
        var pos = 0;
        if ('selectionStart' in el) {
            pos = el.selectionStart;
        } else if ('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
})(jQuery);