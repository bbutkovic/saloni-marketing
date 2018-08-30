$(document).ready(function() {

    var clients_settings_form = $('#updateClientSettings');
    var new_client_form = $('#addNewClientForm');

    new_client_form.on('submit', function(ev) {
        ev.preventDefault();
        var location = $('#locationId').val();
        var first_name = $('#firstName').val();
        var last_name = $('#lastName').val();
        var email = $('#email').val();
        var phone = $('#phone').val();
        var address = $('#address').val();
        var gender = $('#gender').val();

        if($('#createAccount').is(':checked')) {
            var account = 1;
        } else {
            var account = 0;
        }

        if($('#accountPassword').length) {
            var password = $('#accountPassword').val();
        } else {
            var password = '';
        }

        if($('#accountPasswordConfirm').length) {
            var password_confirm = $('#accountPasswordConfirm').val();
        } else {
            var password_confirm = '';
        }

        if($('#custom_field_1').length) {
            var custom_1 = $('#custom_field_1').val();
        } else {
            var custom_1 = '';
        }

        if($('#custom_field_2').length) {
            var custom_2 = $('#custom_field_2').val();
        } else {
            var custom_2 = '';
        }

        if($('#custom_field_3').length) {
            var custom_3 = $('#custom_field_3').val();
        } else {
            var custom_3 = '';
        }

        if($('#custom_field_4').length) {
            var custom_4 = $('#custom_field_4').val();
        } else {
            var custom_4 = '';
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url + 'booking/new-client',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            data: {'location':location,'first_name':first_name,'last_name':last_name,'email':email,'phone':phone,'address':address,
                   'gender':gender,'account':account,'password':password,'password_confirm':password_confirm,'custom_field_1':custom_1,
                   'custom_field_2':custom_2,'custom_field_3':custom_3,'custom_field_4':custom_4},
            success: function(data) {
                unsaved = false;
                if(data.status === 1) {
                    window.location.reload();
                } else {
                    toastr.error(data.message);
                }
            }
        });
    });

    clients_settings_form.on('submit', function(ev) {
        ev.preventDefault();

        var phone = $('.phone-checkbox').is(':checked') ? 1 : 0;
        var address = $('.address-checkbox').is(':checked') ? 1 : 0;
        var gender = $('.gender-checkbox').is(':checked') ? 1 : 0;
        var sms = $('.sms-checkbox').is(':checked') ? 1 : 0;
        var email = $('.email-checkbox').is(':checked') ? 1 : 0;
        var viber = $('.viber-checkbox').is(':checked') ? 1 : 0;
        var facebook = $('.facebook-checkbox').is(':checked') ? 1 : 0;
        var name_format = $('#clientNameFormat').val();


        $.ajax({
            type: 'post',
            url: update_settings_route,
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            dataType: 'json',
            data: {
                phone: phone,
                address: address,
                gender: gender,
                sms: sms,
                email: email,
                viber: viber,
                facebook: facebook,
                name_format: name_format
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
    
    $('#createAccount').on('click', function() {
        $('.append-group').each(function() {
           $(this).remove(); 
        });
        
        $('.client-account-create').append('<div class="col-lg-12 form-group m-l append-group"><label for="accountPassword">' + password_trans + '</label><input type="password" id="accountPassword" class="form-control" name="password"></div><div class="col-lg-12 form-group m-l append-group"><label for="accountPasswordConfirm">' + password_confirm_trans + '</label><input type="password" id="accountPasswordConfirm" class="form-control" name="password" required></div>') 
    });
    
    $('#skipAccountCreation').on('click', function() {
        $('.append-group').each(function() {
           $(this).remove(); 
        });
    });
   
    $('.saveLabel').on('click', function() {
        
        var id = $('#labelId').val();

        if(id) {
            var label = $('#updateLabelname').val();
            var color = $('#spectrumUpdateLabel').val();
        } else {
            var label = $('#labelName').val();
            var color = $('#spectrumLabel').val();
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url + '/clients/label/save',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
            },
            data: {'id':id,'label':label,'color':color},
            success: function(data) {
                unsaved = false;
                $('#clientLabel').modal('hide');
                $('#updateLabel').modal('hide');
                if(data.status === 1) {
                    if(id) {
                        toastr.success(data.message);
                        var label = $('#label-' + data.label.id);
                        label.find('.label-name').html(data.label.name);
                        label.find('.color-shape').css('background-color', data.label.color);
                    } else {
                        toastr.success(data.message);
                        $('.client-labels table tbody').append('<tr><td>' + data.label.name + '</td><td><span class="color-shape" style="background-color: ' + data.label.color + '"></span></td><td class="user-options"><a href="#" class="label-edit" data-id="' + data.label.id + '" data-name="' + data.label.name + '" data-color="' + data.label.color + '" onclick="updateLabel(' + data.label.id + ')"><i class="fa fa-pencil table-profile"></i></a><a href="#" data-id="' + data.label.id + '" onclick="deleteLabel(' + data.label.id + ')"><i class="fa fa-trash table-delete"></i></a></td></tr>');
                    }
                }
            }
        });
    });
   
    $('.saveReferral').on('click', function() {
        
        var id = $('#referralId').val();

        if(id) {
            var referral = $('#updateReferralName').val();
        } else {
            var referral = $('#referralName').val();
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url + '/clients/referral/save',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
            },
            data: {'id':id,'referral':referral},
            success: function(data) {
                unsaved = false;
                $('#clientReferral').modal('hide');
                $('#updateClientReferral').modal('hide');
                if(data.status === 1) {
                    if(id) {
                        toastr.success(data.message);
                        $('#referral-' + data.referral.id).find('.referral-name').html(data.referral.name);
                    } else {
                        toastr.success(data.message);
                        $('.client-referral table tbody').append('<tr><td>' + data.referral.name + '</td><td class="user-options"><a href="#" class="referral-edit" data-id="' + data.referral.id + '" data-name="' + data.referral.name + '" onclick="updateReferral(' + data.referral.id + ')"><i class="fa fa-pencil table-profile"></i></a><a href="#" data-id="' + data.referral.id + '" onclick="deleteReferral(' + data.referral.id + ')"><i class="fa fa-trash table-delete"></i></a></td></tr>');
                    }
                } else {
                    toastr.error(data.message);
                }
            }
        });
   });

    if(typeof admin_booking === 'undefined') {
        $("#spectrumLabel").spectrum({
            color: "#4206A9",
            preferredFormat: "hex",
            showInput: true,
            showPalette: true,
            palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
            change: function(color) {
                $("#spectrumLabel").val(color.toHexString());
            }
        });
    }
});

function addNewClient() {
    $('#addNewClientSubmit').modal('show');
    
    $('#skipAccountCreation').prop('checked', true);
    
    $('.append-group').each(function() {
       $(this).remove(); 
    });
        
}

function addNewLabel() {
    $('#clientLabel').modal('show');
}

function addNewReferral() {
    $('#clientReferral').modal('show');
}

function updateLabel(id) {
    $('#updateLabel').modal('show');
    $('#labelId').val(id);
    var label_name = $('.label-edit[data-id='+id+']').data('name');
    var label_color = $('.label-edit[data-id='+id+']').data('color');
    
    $('#updateLabelname').val(label_name);
    $('#spectrumUpdateLabel').val(label_color);

    $("#spectrumUpdateLabel").spectrum({
        color: label_color,
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: [["red", "rgba(0, 255, 0, .5)", "rgb(0, 0, 255)"]],
        change: function(color) {
            $("#spectrumLabel").val(color.toHexString());
        }
    });
}

function updateReferral(id) {
    $('#updateClientReferral').modal('show');
    $('#referralId').val(id);
    var referral_name = $('.referral-edit[data-id='+id+']').data('name');

    $('#updateReferralName').val(referral_name);
}

function deleteLabel(id) {

    $.ajax({
        type: 'post',
        dataType: 'json',
        url: ajax_url + 'clients/label/delete',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'label':id},
        success: function(data) {
            if(data.status === 1) {
                $('.client-labels tr[data-label='+id+']').css('display', 'none');
                $('.client-label-select option').each(function() {
                    if($(this).val() == id) {
                        $(this).remove();
                    }
                });
            }
        }
    });
}

function deleteReferral(id) {
    $.ajax({
        type: 'post',
        dataType: 'json',
        url: ajax_url + 'clients/referral/delete',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'referral':id},
        success: function(data) {
            if(data.status === 1) {
                $('.client-referral tr[data-label='+id+']').css('display', 'none');
                $('.client-referral-select option').each(function() {
                    if($(this).val() == id) {
                        $(this).remove();
                    }
                });
            }
        }
    });
}

function updateClientLabel(uid) {
    var client_label = $('.client-label-' + uid).val();
    var client = uid;
    
    $.ajax({
        type: 'post',
        url: ajax_url + 'client/set/label',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'client':client,'label':client_label},
        success: function(data) {
            if(data.status === 1) {
                toastr.success(updated);
            } else {
                toastr.error(update_failed);
            }
        }
    });
}

function updateClientReferral(uid) {
    var referral = $('.client-referral-' + uid).val();
    var client = uid;
    
    $.ajax({
        type: 'post',
        url: ajax_url + 'client/set/referral',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'client':client,'referral':referral},
        success: function(data) {
            if(data.status === 1) {
                toastr.success(updated);
            } else {
                toastr.error(update_failed);
            }
        }
    });
}