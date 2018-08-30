$(document).ready(function() {

    var voucherForm = $('#voucherForm');
    voucherForm.on('submit', function(e) {
        e.preventDefault();

        var integer_test = /^[0-9]+$/;
        if (!integer_test.test($('#voucherDiscount').val()) || !integer_test.test($('#voucherAmount').val())) {
            toastr.error(discount_format_error);
        } else {
            $.ajax({
                type: 'post',
                url: ajax_url + 'loyalty/voucher/new',
                dataType: 'json',
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: {'name':$('#couponName').val(),'amount':$('#voucherAmount').val(), 'discount':$('#voucherDiscount').val(), 'expire_date':$('#expireDatePicker').val()},
                success: function(data) {
                    if(data.status === 1) {
                        window.location.reload();
                        $.each('#tab-pane', function() {
                           $(this).removeClass('active');
                        });
                        $.each('#tab-5-li', function() {
                            $(this).removeClass('active');
                        });
                        $('#tab-pane').addClass('active');
                        $('#tab-5-li').addClass('active');
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });

    function Spawner(){
        this.elementCount = 0;
        this.removeElements = function() {
            for(var i = 1; i <= this.elementCount; i++) {
                $("#name_block"+i).remove();
                $("#status_block"+i).remove();
            }
        }
        this.setElements = function() {
            this.htmlElements = {
                nameBlock : '<div id="name_block'+this.elementCount+'" class="col-lg-12 discount-container text-center"><div class="form-group col-lg-6"><label for="field_discount">' + discount_percentage + '</label><input id="field_discount'+this.elementCount+'" class="form-control field-discount" required="" name="field_discount['+this.elementCount+']" type="number" value=""></div><div class="form-group points-needed input-group col-lg-6"><label for="field_points">' + number_of_pts + '</label><input id="field_points'+this.elementCount+'" class="form-control field-points" required="" name="field_points['+this.elementCount+']" type="number" value=""><span class="input-group-btn"><button type="button" id="deletebtn'+this.elementCount+'" class="btn btn-danger delete-block" data-set="'+this.elementCount+'" ><i class="fa fa-trash"></i></button></span></div></div></div>',
            };
        }
        this.spawn = function()
        {
            var html="";
            for(var i = 0; i < Object.keys(this.htmlElements).length;i++)
                html += this.htmlElements[Object.keys(this.htmlElements)[i]];
            $("#fieldsHolder").append(html);
            $(".delete-block").on("click",function()
            {
                var elementPosition = $(this).data("set");
                $("#name_block"+elementPosition).remove();
                $("#status_block"+elementPosition).remove();
                $(this).remove();
            });
        }
    }
    
    var spawner = new Spawner();

    $("#spawnBtn").on("click",function() {
        spawner.elementCount++;
        spawner.setElements();
        spawner.spawn();
      
    });
   
    $("#resetFormBtn").on("click",function() {
       spawner.removeElements();
       spawner.elementCount = 0;
    });

    $('input[name="happy_hour_active"]').on('change', function() {
       var status = $(this).val();

       $.ajax({
           type: 'post',
           url: ajax_url + 'ajax/happy-hour/change-status',
           dataType: 'json',
           beforeSend: function(request) {
               return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
           },
           data: {'status':status},
           success: function(data) {
               if(data.status === 1) {
                   toastr.success(data.message);
               } else {
                   toastr.error(data.message);
               }
           }
       });
    });

    if($('#loyaltyTypeSelect').val()) {
        var type = $('#loyaltyTypeSelect').val();
        if (type === '0') {
            $('#noLoyaltyProgram').addClass('active');
        } else if (type === '1') {
            $('#freeBooking').addClass('active');
        } else if (type === '2') {
            $('#serviceFree').addClass('active');
        } else if (type === '3') {
            $('#bookingDiscount').addClass('active');
        }
    }

    $('#loyaltyTypeSelect').on('change', function() {
        var type = $(this).val();
        $('.booking-type-wrap').each(function () {
            $(this).removeClass('active');
        });

        if (type === '0') {
            $('#noLoyaltyProgram').addClass('active');
        } else if (type === '1') {
            $('#freeBooking').addClass('active');
        } else if (type === '2') {
            $('#serviceFree').addClass('active');
        } else if (type === '3') {
            $('#bookingDiscount').addClass('active');
        }
    });

    $('.groups-radio-container input').on('change', function() {
        var group = $(this).val();
        $('.appended-value').remove();

        $.ajax({
            type: 'get',
            url: ajax_url + 'ajax/get-service-groups/' + group,
            success: function(data) {
                if(data.status === 1) {
                    $('.groups-radio-container').append('<select id="serviceGroupSelect" name="group_select[]" class="appended-value form-control selectpicker" multiple data-actions-box="true"></select>');
                    $.each(data.group, function(index, value) {
                        switch(group) {
                            case '0':
                                $('#serviceGroupSelect').append('<option value="0-' + value.id + '">' + value.name + '</option>');
                                break;
                            case '1':
                                $('#serviceGroupSelect').append('<option value="1-' + value.id + '">' + value.name + '</option>');
                                break;
                            case '2':
                                $('#serviceGroupSelect').append('<option value="2-' + value.id + '">' + value.name + '</option>');
                                break;
                            case '3':
                                $('#serviceGroupSelect').append('<option value="3-' + value.id + '">' + value.name + '</option>');
                                break;
                        }
                    });
                    $('.selectpicker').selectpicker('render');
                } else {
                    toastr.message(data.message);
                }
            }
        });
    });

});

function addDiscounts() {
    var discounts = [];

    $('.discount-container').each(function(index, value) {
        var discount = $(this).find('.field-discount').val();
        var points = $(this).find('.field-points').val();
        var discount_obj = {
            'discount': discount,
            'points': points
        };
        discounts.push(discount_obj);
    });

    return discounts;
}

function updateLoyaltyProgram(id) {
    swal({
        title: delete_check,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: cancel,
        confirmButtonColor: "#52B3D9",
        confirmButtonText: 'Yes',
        closeOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            switch(id) {
                case 0:
                    $.ajax({
                        type: 'post',
                        url: ajax_url + 'loyalty/change-type',
                        dataType: 'json',
                        beforeSend: function(request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                        },
                        data: {type:'0'},
                        success: function(data) {
                            if(data.status === 1) {
                                toastr.success(data.message);
                            } else {
                                toastr.error(data.message);
                            }
                        }
                    });
                    break;
                case 1:
                    var arrival_points = $('#arrivalPoints1').val();
                    var required_arrivals = $('#requiredArrivals1').val();
                    var max_amount = $('#maxAmount').val();
                    var expire_date = $('#expireDate1').val();

                    $.ajax({
                        type: 'post',
                        url: ajax_url + 'loyalty/change-type',
                        dataType: 'json',
                        beforeSend: function(request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                        },
                        data: {type:'1',arrival_points:arrival_points,required_arrivals:required_arrivals,max_amount:max_amount,expire_date:expire_date},
                        success: function(data) {
                            if(data.status === 1) {
                                toastr.success(data.message);
                            } else {
                                toastr.error(data.message);
                            }
                        }
                    });
                    break;
                case 2:
                    var arrival_points = $('#arrivalPoints2').val();
                    var required_arrivals = $('#requiredArrivals2').val();
                    var service_group = $('#serviceGroupSelect').val();
                    var expire_date = $('#expireDate2').val();
                    $.ajax({
                        type: 'post',
                        url: ajax_url + 'loyalty/change-type',
                        dataType: 'json',
                        beforeSend: function(request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                        },
                        data: {type:'2',arrival_points:arrival_points,required_arrivals:required_arrivals,service_group:service_group,expire_date:expire_date},
                        success: function(data) {
                            if(data.status === 1) {
                                toastr.success(data.message);
                            } else {
                                toastr.error(data.message);
                            }
                        }
                    });
                    break;
                case 3:
                    var discounts = addDiscounts();
                    var social_points = $('#socialPoints').val();
                    var referral_points = $('#referralPoints').val();
                    var money_spent = $('#moneySpent').val();
                    var max_points = $('#maxPoints').val();
                    var expire_date = $('#expireDate3').val();
                    var share_title = $('#faceShareTitle').val();
                    var share_desc = $('#faceShareDesc').val();
                    var existing_discounts = [];

                    $('.discounts-group').each(function() {
                        var discount = $(this).find('.discount-exst-val').val();
                        var points = $(this).find('.points-exst-val').val();
                        var id = $(this).data('id');
                        var discount_obj = {
                            'id': id,
                            'discount': discount,
                            'points': points
                        };
                        existing_discounts.push(discount_obj);
                    });

                    $.ajax({
                        type: 'post',
                        url: ajax_url + 'loyalty/change-type',
                        dataType: 'json',
                        beforeSend: function(request) {
                            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                        },
                        data: {type:'3',discounts:discounts,social_points:social_points,referral_points:referral_points,share_title:share_title,share_desc:share_desc,
                               money_spent:money_spent,max_points:max_points,expire_date:expire_date,existing_discounts:existing_discounts},
                        success: function(data) {
                            if(data.status === 1) {
                                toastr.success(data.message);
                            } else {
                                toastr.error(data.message);
                            }
                        }
                    });
                    break;
            }

        }
    });
}

function deleteDiscount(id) {
    
    $.ajax({
        type: 'post',
        url: ajax_url + 'loyalty/discount/delete',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'discount_id':id},
        success: function(data) {
            if(data.status === 1) {
                $('#discountGroup'+id).remove();
            } else {
                toastr.error(data.message);
            }
        }
    });
    
}

function createNewVoucher() {
    $('#slowDayHour').modal('show');
}

function editVoucher(id) {
    $('#editVoucherModal').modal('show');

    var voucher_name = $('tr[data-voucher="' + id + '"]').find('.voucher-name').text();
    var voucher_discount = $('tr[data-voucher="' + id + '"]').find('.voucher-discount').text();
    var voucher_amount = $('tr[data-voucher="' + id + '"]').find('.voucher-amount').text();
    var voucher_code = $('tr[data-voucher="' + id + '"]').find('.voucher-code').text();
    var expire_date = $('tr[data-voucher="'+ id +'"]').find('.voucher-date').text();

    $('#editCouponName').val(voucher_name);
    $('#editVoucherAmount').val(voucher_amount);
    $('#editVoucherDiscount').val(voucher_discount);
    $('#editVoucherCode').val(voucher_code);
    $('#editExpireDate').val(expire_date);
    $('#voucherId').val(id);
}

function updateVoucher(id) {

    var id = $('#voucherId').val();
    var name = $('#editCouponName').val();
    var amount = $('#editVoucherAmount').val();
    var discount = $('#editVoucherDiscount').val();
    var code = $('#editVoucherCode').val();
    var date = $('#editExpireDate').val();

    $.ajax({
        type: 'post',
        url: ajax_url + 'loyalty/voucher/update',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {'id':id,'name':name,'amount':amount,'discount':discount,'code':code,'expire_date':date},
        success: function(data) {
            if(data.status === 1) {
                toastr.success(data.message);
                $('tr[data-voucher="' + id + '"]').find('.voucher-name').text(name);
                $('tr[data-voucher="' + id + '"]').find('.voucher-discount').text(discount);
                $('tr[data-voucher="' + id + '"]').find('.voucher-amount').text(amount);
                $('tr[data-voucher="' + id + '"]').find('.voucher-code').text(code);
                $('tr[data-voucher="' + id + '"]').find('.voucher-date').text(date);
                $('#editVoucherModal').modal('hide');
            } else {
                toastr.error(data.message);
            }
        }
    });
}

function deleteVoucher(id) {
    swal({
        title: delete_check,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: cancel,
        confirmButtonColor: "#52B3D9",
        confirmButtonText: accept_delete,
        closeOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'get',
                url: ajax_url + 'loyalty/voucher/delete/' + id,
                success: function(data) {
                    if(data.status === 1) {
                        $('tr[data-voucher="' + id + '"]').remove();
                        toastr.success(data.message);
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });

}