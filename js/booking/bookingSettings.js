$(document).ready(function() {

    $(document).on('change', '.js-switch', function() {
        var state = $(this).is(':checked') ? 1 : 0;
        var id = $(this).parent().parent().data('id');

        $.ajax({
            type: 'post',
            url: ajax_url + 'ajax/custom-fields/status',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            data: {'state':state,'id':id},
            success: function(data) {
                if(data.status === 1) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            }
        });
    });

    var policies_form = $('#updatePolicies');
    var new_field_form = $('#addNewFieldForm');
    var edit_field_form = $('#editCustomField');

    policies_form.on('submit', function(ev) {
        ev.preventDefault();

        var staff_selection = $('#staffSelection1').is(':checked') ? 1 : 0;
        var show_prices = $('#showPrices1').is(':checked') ? 1 : 0;
        var multiple_staff = $('#multipleStaff1').is(':checked') ? 1 : 0;
        var first_name_only = $('#firstNameOnly1').is(':checked') ? 1 : 0;
        var cancel_limit = $('#cancelLimit').val();
        var booking_slot = $('#bookingSlot').val();

        $.ajax({
            type: 'post',
            url: update_policies_route,
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            dataType: 'json',
            data: {
                staff_selection: staff_selection,
                show_prices: show_prices,
                multiple_staff: multiple_staff,
                first_name_only: first_name_only,
                cancel_limit: cancel_limit,
                booking_slot: booking_slot,
            },
            success: function (data) {
                unsaved = false;
                if (data.status === 1) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            }
        })
    });

    new_field_form.on('submit', function(ev) {
        ev.preventDefault();

        var field_location = $('#fieldLocation').val();
        var main_field_name = $('#fieldName').val();
        var field_input_type = $('#fieldSelectType').val();
        var select_options = [];
        $('.select-option').each(function() {
            select_options.push($(this).val());
        });

        $.ajax({
            type: 'post',
            url: update_fields_route,
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            dataType: 'json',
            data: {
                field_location: field_location,
                main_field_name: main_field_name,
                field_input_type: field_input_type,
                select_options: select_options
            },
            success: function (data) {
                unsaved = false;
                if (data.status === 1) {
                    window.location.reload();
                } else {
                    toastr.error(data.message);
                }
            }
        })
    });

    edit_field_form.on('submit', function(ev) {
        ev.preventDefault();

        var field_id = $('#fieldId').val();
        var field_type = $('#editFieldType').val();
        var field_title = $('#fieldTitle').val();
        var new_options = [];
        var select_options = [];
        $('.existing-field').each(function() {
            select_options.push({'name':$(this).attr('name'),'value':$(this).val()});
        });
        $('.select-option').each(function() {
            new_options.push({'name':$(this).attr('name'),'value':$(this).val()});
        });
        $.ajax({
            type: 'post',
            url: edit_field_route,
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            dataType: 'json',
            data: {
                field_id: field_id,
                field_type: field_type,
                field_title: field_title,
                select_options: select_options,
                new_options:new_options
            },
            success: function (data) {
                unsaved = false;
                if (data.status === 1) {
                    $('#field-' + data.field.id).find('.title-field').html(data.field.field_title);
                    $('#editCustomField').modal('hide');
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            }
        })
    });

    $('#fieldSelectType').on('change', function() {
        if($(this).val() === '2') {
            $('.new-field-creation .row').append('<button type="button" id="spawnBtn" class="btn btn-success m-t m-b m-l"><i class="fa fa-plus"></i></button>'+
                '<div id="fieldsHolder" class="text-center">'+
                '<div class="col-lg-12">'+
                '<div class="form-group"><label for="field_name0">' + select_field + '</label><input id="field_name0" class="form-control select-option" required="" name="field_name[0]" type="text" value="">'+
                '</div></div></div>');
        }
    });

});

