//variables for loyalty program (discounts)
var total_price_calc = 0;
var base_price = 0;
var loyalty_type = '';
var loyalty_status = 0;
var max_amount = 0;
var points_awarded = 0;
var free_service = '';

var price_list = [];
var price = 0;

var discount_list = [];
var selected_discount = '';
var points_used = 0;
var discount_val = 0;
//variables for booking process
var booking_location = '';
var booking_date = '';
var booking_from = '';
var booking_to = '';
var account = '';
var staff = '';
var service = '';
var waiting_list = '';
var discount_code = '';

var selected_staff = [];

$(document).ready(function() {
    if(typeof location_count != 'undefined' && location_count >= 1) {
        if(section === null) {
            clientSelectLocation(null);
        } else {
            clientSelectLocation(first_location);
        }

    }

    var booking_process_height = $('.booking-process').height();
    $('.client-loader').css('height', booking_process_height);
    $('.admin-booking-loader').css('height', booking_process_height);

    $('#basePriceStatus').html(total_price_calc + ' ' + salon_currency.toUpperCase());

    $(document).on('click', '.service-checkbox', function() {
        calculateTotalPrice();
        $('#totalPriceCalculated').val(total_price_calc);
        if(loyalty_type === 1 && loyalty_status === 1 && total_price_calc <= max_amount) {
            if(base_price <= max_amount) {
                loyalty_status = 1;
                total_price_calc = 0;
            } else {
                loyalty_status = 0;
                total_price_calc = base_price;
            }
            $('#basePriceStatus').html('<del>' + total_price_calc + '</del> ' + 0 + ' ' + salon_currency.toUpperCase());
         } else if (loyalty_type === '3' && $('#loyaltyStatus').val() === '1') {
            var discount = $('#loyaltyDiscount').val() / 100;
            var discounted = total_price_calc * discount;
            var discounted_price = total_price_calc - discounted;

            $('#basePriceStatus').html('<del>' + total_price_calc + '</del> ' + discounted_price + ' ' + salon_currency.toUpperCase());
            $('#totalPriceCalculated').val(discounted_price);
        } else {
            $('#basePriceStatus').html( total_price_calc + ' ' + salon_currency.toUpperCase());
        }
    });

    $(document).on('change', '#freeServices', function() {
        var free_service_id = $(this).val();
        var service_el = $('#serviceList').find('input[data-id="'+free_service_id+'"]');
        //check if another service is already selected
        $.each(price_list, function(index, value) {
            value.price = value.old_price;
            if(value.id == free_service_id) {
                service_el.prop('checked', 'true');
                 value.price = 0;
                free_service = free_service_id;
            }
        });

        calculateTotalPrice();
        resetPrices();
        service_el.parent().parent().find('.service-price').find('h2').text(0 + ' ' + salon_currency.toUpperCase());
        $('#basePriceStatus').html(total_price_calc + ' ' + salon_currency.toUpperCase());
    });

    $(document).on('change', '#discountList', function() {
        selected_discount = $(this).val();
        $.each(discount_list, function(index, value) {
            if(value.id == selected_discount) {
                discount_val = value.discount / 100;
                points_used = value.points;
                calculateTotalPrice();
                $('#basePriceStatus').html('<del>' + base_price + '</del> ' + total_price_calc + ' ' + salon_currency.toUpperCase());
            }
        });
    });

    $('#addNewClient').on('click', function() {
        var locationId = $('#locationId').val();
        $('.location-id').val(locationId);
        $('#addNewClientModal').modal('show'); 
    });
    
    $('#selectClient').on('change', function() {
        var client = $('#selectClient').val();
        $.ajax({
           type: 'get',
           url: ajax_url + 'booking/get-client/' + client,
           success: function(data) {
               if(data.status === 1) {
                   $('#clientId').val(data.client.id);
               }
           }
        }); 
    });
    
    $('.staff-container').on('click', '#selectRandomStaff', function() {
        
        var selected_services = [];
        var selected_staff_arr = [];
        var selected_staff = [];

        var location_id = $('#locationId').val();
        $.each($('#serviceList input:checked'), function(index,val) {
            selected_services.push(val.name);
        });

        //select random staff from dropdown
        $('#selectSingleStaff option').each(function(index, value) {
           if(value.value != 0) {
               selected_staff_arr.push(value.value);
           }
        });

        selected_staff.push({'staff':selected_staff_arr[Math.floor(Math.random() * selected_staff_arr.length)]});

        $.ajax({
            type: 'post',
            url: ajax_url + 'ajax/staff/schedule',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
            },
            dataType: 'json',
            data: {'location':location_id, 'staff':selected_staff, 'services':selected_services},
            success: function(data) {
                if(data.status != 0) {
                    var date = new Date();
                    date.setDate(date.getDate());
    
                    $('#datepicker').datepicker({
                        keyboardNavigation: false,
                        forceParse: false,
                        startDate: date,
                        weekStart: week_start,
                        daysOfWeekDisabled: data.disabled_dates,
                    }).on('changeDate', function() {
                        $('#selectedDate').val(
                            $('#datepicker').datepicker('getFormattedDate')
                        );
                        getStaffSchedule();
                    });
                    
                    $('.datepicker').removeClass('datepicker-inline');
                    $('#anyStaff').val(1);
                }
            }
        });
    });
    
    $("#dateCalendar").on('changeDate',function() {
        getStaffSchedule();
    });
    
    $('.step-1').on('click', function() {
        $('.booking-step-service').css('display', 'block');
        $('.booking-step-staff').css('display', 'none');
        $('.booking-step-time').css('display', 'none');
        $('.client-submit-info').css('display', 'none');
    });
    
    $('.step-2').on('click', function() {
        $('.booking-step-service').css('display', 'none');
        $('.booking-step-staff').css('display', 'block');
        $('.booking-step-time').css('display', 'none');
        $('.client-submit-info').css('display', 'none');
    });
    
    $('.step-3').on('click', function() {
        $('.booking-step-service').css('display', 'none');
        $('.booking-step-staff').css('display', 'none');
        $('.booking-step-time').css('display', 'block');
        $('.client-submit-info').css('display', 'none');
    });
   
    $('.booking-confirm').on('click', '#useDiscountCode', function() {
        var client = $('#selectClient option:selected').val();
        var code = $('input[name="discount_code"]').val();
        var services = [];
        
        $.each($('#serviceList input:checked'), function() {
            services.push($(this).data('id'));
        });

        $.ajax({
            type: 'post',
            url: ajax_url + 'ajax/booking/redeem-code',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
            },
            dataType: 'json',
            data: {'client':client,'code':code,'services':services},
            success: function(data) {
                if(data.status === 1) {
                    total_price_calc = data.price.price;
                    discount_code = code;
                    $('.total-price-section').css('text-decoration', 'line-through');
                    $('.new-price').html('<br><strong>' + data.price.price + ' ' + salon_currency.toUpperCase() + '</strong>');
                    $('#totalPrice').val(data.price.price);
                    toastr.success(data.message);
                } else {
                    $('#codeError').html(data.price);
                }
            }
        });
    });
});

//reset prices on selected free service (loyalty program type 2)
function resetPrices() {
    $('#serviceList input[type="checkbox"]').each(function() {
        var id = $(this).data('id');
        var parent = $(this).parent().parent().find('.service-price').find('h2');
        $.each(price_list, function(i, v) {
            if(v.id == id) {
                parent.html(v.price + ' ' + salon_currency.toUpperCase());
            }
        });
    });
}

function calculateTotalPrice() {
    total_price_calc = 0;
    base_price = 0;
    $('#serviceList input[type="checkbox"]').each(function() {
        var service_id = $(this).data('id');
        if($(this).is(':checked')) {
            $.each(price_list, function(index, value) {
                if(value.id == service_id) {
                    total_price_calc += value.price;
                    base_price += value.price;
                }
            });
        }
    });
    if(discount_val != 0) {
        var to_discount = total_price_calc * discount_val;
        total_price_calc = total_price_calc - to_discount;
    }
}

function clientSelectLocation(id) {
    if(typeof id != 'undefined' && id != null) {
        var location = id;
    } else {
        var location = $('#clientSelectLocation').val();
    }

    $('#selectCategory').html('<option value="default" selected disabled>' + select_category + '</option>');

    $('#locationId').val(location);
    $.ajax({
       type: 'get',
       url: ajax_url + 'ajax/client-booking/categories/' + location,
       success: function(data) {
           if(data.status === 1) {
               if(data.client_loyalty != null && data.client_loyalty.status != 0 && data.client_loyalty.type != 0) {
                    $('#clientLoyaltyPoints').text(data.client_loyalty.points);
                    $('.loyalty-promo-text').text(data.client_loyalty.message);

                    loyalty_type = data.client_loyalty.type;
                    loyalty_status = data.client_loyalty.status;

                    if(data.client_loyalty.type == 1) {
                        max_amount = data.client_loyalty.max_amount;
                        loyalty_status = data.client_loyalty.status;
                    } else if(data.client_loyalty.type == 2) {
                        $('#freeServices').removeClass('hidden').css('display', 'block');
                        $.each(data.client_loyalty.free_groups.group, function(index, value) {
                            $('#freeServices').append('<optgroup id="'+index+'Group" label="'+index+'"></optgroup>');
                            $.each(value, function(i,v) {
                               $('#'+index+'Group').append('<option value="'+v.id+'">'+v.name+'</option>');
                            });
                        });
                    } else if (data.client_loyalty.type == 3) {
                        $.each(data.client_loyalty.all_discounts, function(index, value) {
                            $('.loyalty-free-groups').append('<li>' + value.discount + '%' + ' (' + value.points + ' ' + points_trans + ')</li>');
                        });
                        if(typeof data.client_loyalty.available_discounts != 'undefined' && data.client_loyalty.available_discounts.length) {
                            discount_list = data.client_loyalty.available_discounts;
                            $('#discountList').removeClass('hidden').css('display', 'block');
                            $.each(data.client_loyalty.available_discounts, function(x,y) {
                                $('#discountList').append('<option value="'+y.id+'">'+y.discount+'%</option>');
                            });
                        }
                    }
                    $('#clientPointsNeeded').text(data.client_loyalty.points_needed);
               } else {
                   $('.client-loyalty-container').removeClass('col-md-6').css('display', 'none');
                   $('.client-services-container').removeClass('col-md-6');
               }

               $('.client-select-category').removeClass('hidden');
               $.each(data.category_list, function(index, value) {
                   $('.client-select-category').append('<option value="' + value.id + '">' + value.name + '</option>');
               });
               
               $('#clientBookingCheck').val(1);
           }
       }
    });
}

function selectCategory() {
    var category = $('#selectCategory').val();
    var locationId = $('#locationId').val();
    $('.services-list-container').remove();
    $('#servicesSelectedButton').remove();
    $('.client-loyalty-container').removeClass('hidden');
    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/services/' + locationId + '/' + category,
        success: function(data) {

            if(data.status === 1) {
                $.each(data.services, function(index,val) {
                    var subgroup_services = [];
                    $('#totalPriceCalc').removeClass('hidden');
                    $('#serviceList').append('<div class="col-xs-12 services-list-container group-wrap-' + index + ' m-t m-b"><div class="group-heading"><h1>' + val[0].group_name + '</h1></div></div>');
                    $.each(val, function(i, v) {
                        var service_price = v.service.service_price;
                        var price_obj = {
                            'id': v.service.service_id,
                            'price': Number(v.service.service_price),
                            'old_price': Number(v.service.service_price)
                        };
                        price_list.push(price_obj);
                        if(v.service.subgroup_id === null) {
                            $('.group-wrap-' + index).append('<div class="service-wrap"><div class="col-xs-2 checkbox checkbox-primary"><input type="checkbox" id="checkbox' + v.service.service_id + '" class="service-checkbox" data-id="' + v.service.service_id + '" data-service="' + v.service.service_name + '" data-price="' + service_price + '" name="select-' + v.service.service_id + '"><label for="checkbox' + v.service.service_id + '"></label></div><div class="col-xs-5 service-name"><h2>' + v.service.service_name + '</h2></div><div class="col-xs-5 service-price"><h2>' + service_price + ' ' + salon_currency.toUpperCase() + '</h2></div></div><hr>');
                        } else {
                            subgroup_services.push({'subgroup_id':v.service.subgroup_id, 'subgroup_name':v.service.subgroup_name, 'service':v});
                        }
                    });
                    
                    function _toConsumableArray(arr) {
                        if (Array.isArray(arr)) {
                            for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) {
                                arr2[i] = arr[i];
                            }
                            return arr2;
                        } else {
                            return Array.from(arr);
                        }
                    }
                    var unique = [].concat(_toConsumableArray(new Set(subgroup_services.map(function (item) {
                      return item.subgroup_id;
                    }))));
                    
                    if(unique.length > 0) {
                        for(var i=0; i<unique.length; i++) {
                            $('#serviceList').append('<div class="col-xs-12 services-list-container subgroup-wrap-' + subgroup_services[i].subgroup_id + '"><div class="subgroup-heading"><h1>' + subgroup_services[i].subgroup_name + '</h1></div></div>');
                        }
                        $.each(subgroup_services, function(x, y) {
                            var service_price = y.service.service.service_price;
                            $('.subgroup-wrap-' + y.subgroup_id).append('<div class="service-wrap"><div class="col-xs-2 checkbox checkbox-primary"><input type="checkbox" class="service-checkbox" id="checkbox' + y.service.service.service_id + '" data-service="' + y.service.service.service_name + '" data-price="' + service_price + '" name="select-' + y.service.service.service_id + '"><label for="checkbox' + y.service.service.service_id + '"></label></div><div class="col-xs-5 service-name"><h2>' + y.service.service.service_name + '</h2></div><div class="col-xs-5 service-price"><h2>' + service_price + ' kn</h2></div></div><hr>');
                        });
                    }
                });
                $('#serviceList').append('<button type="button" id="servicesSelectedButton" class="btn btn-success" onclick="servicesSelected()">' + next + '</button>');
            } else {
                toastr.error('No data available');
            }
        }
    });
}

function servicesSelected() {
    var selected_services = [];
    var location_id = $('#locationId').val();
    
    $('.staff-wrap').remove();
    $('.action-buttons-wrap').css('display', 'none');

    $('.booking-step.step-1').html('<i class="fa fa-check"></i>');
    $('.step-2').removeClass('disabled');
    
    $.each($('#serviceList input:checked'), function(index,val) {
        selected_services.push(val.name);
    });

    if(selected_services === undefined || selected_services.length != 0) {

        $('.client-loader').removeClass('hidden');
        $('.booking-process').addClass('muted');

        if (staff_selection === 0) {

            $.ajax({
                type: 'post',
                url: ajax_url + 'ajax/booking/submit-services',
                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                dataType: 'json',
                data: {'location': location_id, 'services': selected_services},
                success: function (data) {

                    $('.client-loader').addClass('hidden');
                    $('.booking-process').removeClass('muted');

                    if (data.status != 0) {
                        $('.step-2').addClass('active');
                        $('.booking-step-service').css('display', 'none');
                        $('.booking-step-staff').css('display', 'block');

                        if (multiple_staff === 0) {
                            $('.staff-container').append('<div class="staff-wrap single-staff"><div class="row">'+
                                '<button class="btn btn-primary" id="selectRandomStaff" type="button" data-toggle="tooltip" data-placement="top" data-container="body" title="" data-original-title="' + select_staff_randomly_desc + '">' + select_staff_randomly + '</button>' +
                                '<select class="form-control selectpicker select-alt" name="staffSelection" id="selectSingleStaff" required>'+
                                '<option value="0" selected disabled>' + select_staff + '</option></select></div></div>');
                            $.each(data, function (index, val) {
                                $.each(val, function (x, y) {
                                    $('#selectSingleStaff').append('<option data-thumbnail="' + ajax_url + y.avatar + '" value="' + y.user_id + '">' + y.first_name + ' ' + y.last_name + '</option>');
                                });
                            });
                        } else {
                            $('.staff-container').append('<div class="staff-wrap single-staff"></div>');
                            $.each(data, function (index, val) {
                                $.each(val, function (x, y) {
                                    $('.staff-container').append('<div class="staff-wrap"><div class="col-xs-6"><h2 class="service-name">' + y[0].service_name + '</h2></div><div class="col-xs-6"><select class="form-control selectpicker" name="service-' + y[0].service_id + '" id="service-' + y[0].service_id + '" required><option value="0" selected disabled>' + select_staff + '</option></select></div></div>');
                                    $.each(y, function (i, v) {
                                        $('#service-' + v.service_id).append('<option data-thumbnail="' + ajax_url + v.avatar + '" value="' + v.user_id + '">' + v.first_name + ' ' + v.last_name + '</option>');
                                    });
                                });

                            });
                        }
                        $('select.selectpicker').selectpicker();
                        $('.staff-container').append('<div class="row action-buttons-wrap"><button type="button" class="btn btn-danger" onclick="returnToPreviousTab(1)"><i class="fa fa-arrow-left"></i> ' + trans_back + '</button><button type="button" id="staffSelectedButton" class="btn btn-success" onclick="staffSelected()">' + next_date + '</button></div>');
                    }
                }
            });

        } else {

            var selected_services = [];

            $('.booking-step-service').css('display', 'none');
            $('.booking-step-time').css('display', 'block');
            $('.step-3').removeClass('disabled');
            $('.step-3').addClass('active');

            var selected_staff = 'all';
            var location_id = $('#locationId').val();

            $.each($('#serviceList input:checked'), function (index, val) {
                selected_services.push(val.name);
            });

            $.ajax({
                type: 'post',
                url: ajax_url + 'ajax/staff/schedule',
                beforeSend: function (request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                dataType: 'json',
                data: {'location': location_id, 'staff': selected_staff, 'services': selected_services},
                success: function (data) {
                    $('.client-loader').addClass('hidden');
                    $('.booking-process').removeClass('muted');

                    if (data.status != 0) {
                        var date = new Date();
                        date.setDate(date.getDate());
                        initDatePicker(date,data.disabled_dates);
                        $('.datepicker').removeClass('datepicker-inline');

                        $.each(data.random_staff, function (index, val) {
                            $('#randomStaff').append('<option value="' + val.user_id + '">' + val.user_id + '</option>');
                        });

                    }
                }
            });
        }
    } else {
        toastr.error(services_not_selected);
    }
}

function initDatePicker(date,disabled_dates) {
    $('#datepicker').datepicker({
        keyboardNavigation: false,
        forceParse: false,
        startDate: date,
        weekStart: week_start,
        datesDisabled: disabled_dates
    }).on('changeDate', function () {
        $('#selectedDate').val(
            $('#datepicker').datepicker('getFormattedDate')
        );
        getStaffSchedule();
    });
}

function staffSelected() {

    var selected_staff = [];
    var selected_services = [];
    var location_id = $('#locationId').val();
    
    $('#datepicker').datepicker('remove');
    
    $.each($('.staff-wrap select'), function(index, value) {
        var selected_value = $(this).val();
        if(selected_value != null) {
            selected_staff.push({'service':value.name,'staff':selected_value});
        } else {
            selected_staff = [];
            return 1;
        }
    });

    $.each($('#serviceList input:checked'), function(index,val) {
        selected_services.push(val.name);
    });

    if(selected_staff.length <= 0 || (multiple_staff != 0 && selected_staff.length != selected_services.length)) {
        toastr.error(staff_not_selected)
    } else {
        $('.client-loader').removeClass('hidden');
        $('.booking-process').addClass('muted');

        $.ajax({
            type: 'post',
            url: ajax_url + 'ajax/staff/schedule',
            beforeSend: function (request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            dataType: 'json',
            data: {'location': location_id, 'staff': selected_staff},
            success: function (data) {
                $('.client-loader').addClass('hidden');
                $('.booking-process').removeClass('muted');

                if (data.status != 0) {
                    var date = new Date();
                    date.setDate(date.getDate());
                    initDatePicker(date,data.disabled_dates);
                    $('.datepicker').removeClass('datepicker-inline');
                }
            }
        });
    }
}

function getStaffSchedule() {
    $('.client-loader').removeClass('hidden');
    $('.booking-process').addClass('muted');
    var service = [];
    var any_staff = selected_staff;
    $('.booking-step-staff').css('display', 'none');
    $('.booking-step-time').css('display', 'block');
    $('.booking-step.step-2').html('<i class="fa fa-check"></i>');
    $('.step-3').removeClass('disabled');
    $('.step-3').addClass('active');
    $('.available-time').remove();

    $.each($('#serviceList input:checked'), function(index,val) {
        service.push(val.name);
    });
    
    var staff = [];
    if(staff_selection === 0) {
        if(typeof any_staff != 'undefined' && any_staff.length > 0) {
            staff = 'all';
        } else {
            $.each($('.staff-wrap select'), function() {
                var selected_value = $(this).val();
                staff.push(selected_value);
            });
        }
    } else {
        $.each($('#randomStaff option'), function() {
            var selected_value = $(this).val();
            staff.push(selected_value);
        });
    }

    var location = $('#locationId').val();
    var selected_date = $('#selectedDate').val();
    
    if(staff_selection != 0) {
        staff = 'all';
    }

    $.ajax({
        type: 'post',
        url: ajax_url + 'ajax/schedule/get-schedule',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'location':location,'staff':staff,'service':service,'selected_date':selected_date},
        success: function(data) {
            $('.client-loader').addClass('hidden');
            $('.booking-process').removeClass('muted');
            $('#availableForBooking').addClass('active');

            $.each(data, function(index, value) {
                $('.table-schedule').append('<tr class="available-time" data-id="time-' + index + '" onclick="selectTime(' + index + ')">' +
                                            '<td class="time-from text-center" data-start="' + value.from + '" data-end="' + value.to + '">' + value.from + ' - ' + value.to + '</td></tr>');
            });
        }
    });
}

function calculatePoints() {
    var services = [];

    if(total_price_calc != 0 && loyalty_type == 3) {
        $.each($('#serviceList input:checked'), function() {
            services.push($(this).data('id'));
        });
        $.ajax({
            type: 'post',
            url: ajax_url + 'ajax/booking/calc-points',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            dataType: 'json',
            data: {'services':services},
            success: function(data) {
                if(data.status === 1) {
                    points_awarded = data.points;
                    $('#awardedPoints').val(data.points);
                    $('#awardedPointsClient').val(data.points);
                    $('.points-awarded').html(data.message);
                }
            },
            async: false
        });
    } else {
        $('.action-buttons-wrap').attr('style', 'margin-top: 0px !important;');
        $('#awardedPoints').val(0);
        $('#awardedPointsClient').val(0);
        $('.points-awarded').remove();
    }
}

function selectTime(id) {
    var anyStaff = $('#anyStaff').val();
    var el = $('tr[data-id=time-' + id + ']');
    var service = [];

    $.each($('.appended-value'), function() {
        $(this).remove();
    });
    
    $.each($('#serviceList input:checked'), function(index,val) {
        var service_el = $(this);
        $.each(price_list, function(x,y) {
            if(service_el.data('id') == y.id) {
                service.push(service_el.data('service') + ' ' + y.price + ' ' + salon_currency.toUpperCase());
            }
        });

        $('.booking-confirm').append('<input type="hidden" class="appended-value" name="service[]" value="' + val.name + '">');
    });
    
    var services = service.join('</br>');
    var staff = [];

    //get list of selected staff
    if(staff_selection === 0) {
        if(anyStaff) {
            $('.booking-confirm').append('<input type="hidden" class="appended-value" name="staff[]" value="undefined">')
        } else {
            $.each($('.staff-wrap select'), function() {
                var selected_value = $(this).val();
                $('.booking-confirm').append('<input type="hidden" class="appended-value" name="staff[]" value="' + selected_value + '">');
            });
        }
    } else {
        $.each($('#randomStaff option'), function() {
            var selected_value = $(this).val();
            $('.booking-confirm').append('<input type="hidden" class="appended-value" name="staff[]" value="' + selected_value + '">');
        });
    }

    booking_location = $('#locationId').val();
    service_list = service;
    staff_list = staff;
    booking_date = $('#selectedDate').val();
    booking_from = el.find('.time-from').data('start');
    booking_to = el.find('.time-from').data('end');
    $('.available-time').removeClass('selected-time');

    $('tr[data-id=time-' + id + ']').addClass('selected-time');
    $('#collapseTimesTable .collapse-link').trigger('click');

    if($('#clientBookingCheck').val() === '1') {
        $('.select-client-row').remove();
        $('.customer-info').remove();
    } else {
        $.ajax({
            type: 'get',
            url: ajax_url + 'ajax/clients/' + booking_location,
            success: function(data) {
                if(data.status === 1) {
                    if(data.message != 0) {
                        $('#selectClient').html('<option class="appended-value" value="">' + select_client + '</option>');
                        $.each(data.clients, function(index,value) {
                            if(client_name === 'first_last') {
                                var name_formated = value.first_name + ' ' + value.last_name;
                            } else {
                                var name_formated = value.last_name + ' ' + value.first_name;
                            }
                            $('#selectClient').append('<option class="appended-value" value="' + value.id + '">' + name_formated + '</option>');
                        });
                    } else {
                        $('#selectClient').append('<option class="appended-value" value="0" selected disabled>' + no_clients + '</option>');
                    }
                }
            },
            async: false
        });
        $('.selectpicker').selectpicker('refresh');

    }
    
    $('#selectedBookingTime').addClass('active');

    $('#totalPrice').val(total_price_calc);
    
    if(waiting_list_status === 1) {
        var waitinglist_check = '<div class="row text-center"><div class="form-group" data-toggle="tooltip" data-placement="top" title="" data-original-title="You will receive an email if earlier date (with the same time) becomes available."><label class="waiting-list-label" for="waitingList"><input type="checkbox" name="waiting_list" id="waitingList">Add me to waiting list</label></div></div>';
    } else {
        var waitinglist_check = '';
    }
    
    $('.booking-confirm').append(
        '<div class="appended-value row"><h2 class="text-center">' + selected_booking_for + booking_date + '</h2></div>'+
        '<div class="row appended-value text-center"><h3>' + booking_from + ' - ' + booking_to + '</h3></div>'+
        '<div class="row appended-value"><p>' + services + '</p></br>'+ waitinglist_check +
        '<hr><p class="total-price-section">' + price_calc + total_price_calc + ' ' + salon_currency.toUpperCase() + '</p><br><p class="new-price"></p></br>'+
        '<div class="form-group input-group discount-block col-sm-4"><label for="discount_code"></label>' +
        '<input type="text" name="discount_code" class="form-control" placeholder="' + discount_code_trans + '"><span class="input-group-btn">' +
        '<button id="useDiscountCode" type="button" class="btn btn-default"><i class="fa fa-check"></i></button></span></div></div>'+
        '<div class="row"><small class="text-danger" id="codeError"></small></div><hr><h4 class="text-center points-awarded"></h4><br>' +
        '<div class="row action-buttons-wrap appended-value"><button type="button" class="btn btn-danger" onclick="returnToPreviousTab(2)">' +
        '<i class="fa fa-arrow-left"></i> ' + trans_back + '</button>' +
        '<button type="button" class="btn btn-success submit-booking-btn m-l appended-value" onclick="bookingProceed()">' + confirm_booking + '</button></div>');

    calculatePoints();
}

function registerNewClient() {
    
    $('#clientLoginFields').empty();
    $('#clientAccountFields').empty();
    
    $('.booking-account-buttons').html('<button type="button" class="btn btn-danger" onclick="resetButtons()">' +
        '<i class="fa fa-arrow-left"></i> ' + trans_back + '</button><button class="btn btn-success"><i class="fa fa-user"></i> ' + trans_submit + '</button>');
    
    $('#clientAccount').val(1);

    if(!$('.booking-password').length) {
        $('#clientAccountFields').append('<hr><div class="form-group col-sm-6 booking-custom-field booking-password">' +
            '<label for="accountPassword">' + password_trans + '</label>' +
            '<input type="password" id="accountPassword" class="form-control" name="password" required></div>' +
            '<div class="form-group col-sm-6 booking-custom-field"><label for="accountPasswordConfirm">' + password_confirm_trans + '</label>' +
            '<input type="password" id="accountPasswordConfirm" class="form-control" name="password_confirm" required></div>');
    }
}

function loginClient() {
    
    $('#clientLoginFields').empty();
    $('#clientAccountFields').empty();
    $('#clientRequiredFields').empty();
    
    $('.booking-account-buttons').html('<button type="button" class="btn btn-danger" onclick="resetButtons()"><i class="fa fa-arrow-left"></i> ' + trans_back + '</button><button class="btn btn-success"><i class="fa fa-user"></i> ' + trans_submit + '</button>');
    
    $('#clientAccount').val(2);
    $('#clientLoginFields').append('<hr><div class="form-group col-sm-6 booking-custom-field login-email">' +
        '<label for="loginEmail">Email</label><input type="email" id="loginEmail" class="form-control" name="email"></div>' +
        '<div class="form-group col-sm-6 booking-custom-field"><label for="loginPassword">' + password_trans + '</label>' +
        '<input type="password" id="loginPassword" class="form-control" name="password" required></div><hr>');
}

function resetButtons() {
    $('#clientLoginFields').empty();
    $('#clientAccountFields').empty();
    $('#clientAccount').val(0);
    $('.booking-account-buttons').html('<button type="button" class="btn btn-primary" onclick="registerNewClient()">' + trans_register + '</button><button type="button" class="btn btn-primary" onclick="loginClient()"> ' + trans_login + '</button><button class="btn btn-primary">' + trans_submit_without_account + '</button>');
}

function bookingProceed() {
    if($('#clientBookingCheck').val() != '1') {
        if($('#selectClient').val() != '') {

            var client_id = $('#selectClient').val();
            var service = [];
            $.each($('#serviceList input:checked'), function(index,val) {
                service.push(val.name);
            });
            if(staff_selection === 0) {
                var staff = [];
                $.each($('.staff-wrap select'), function() {
                    var selected_value = $(this).val();
                    staff.push(selected_value);
                });
            } else {
                var staff = [];
                staff.push('undefined');
            }
            $.ajax({
                type: 'post',
                dataType: 'json',
                url: ajax_url + 'booking/new',
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: {'booking_location':booking_location,'booking_date':booking_date,'booking_from':booking_from,'client_id':client_id,
                    'total_price':total_price_calc,'base_price':base_price,'points_awarded':points_awarded,'service':service,'discount_code':discount_code,
                    'staff':staff,'free_service':free_service,'points_used':points_used,'selected_discount':selected_discount},
                success: function(data) {
                    if(data.status === 1) {
                        swal({
                            title: booking_success,
                            type: "success",
                            showCancelButton: true,
                            cancelButtonText: add_new_booking,
                            confirmButtonColor: "#52B3D9",
                            confirmButtonText: go_to_calendar,
                            closeOnConfirm: true,
                        }, function (isConfirm) {
                            if (!isConfirm) {
                                window.location.href = ajax_url + 'admin/booking';
                            } else {
                                window.location.href = ajax_url + 'appointments';
                            }
                        });
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        } else {
            toastr.error(client_not_selected);
        }
    } else {
        $('.booking-step-time').css('display', 'none');
        $('.booking-step.step-3').html('<i class="fa fa-check"></i>');
        $('.client-submit-info').css('display', 'block');
        $('.appended-value').each(function() {
            $(this).css('display', 'none');
        });
        if(!$('.booking-custom-field').length) {
            $('.client-loader').removeClass('hidden');
            $('.booking-process').addClass('muted');
            $.ajax({
                type: 'get',
                url: ajax_url + 'ajax/booking-fields/' + booking_location,
                success: function(data) {
                    $('.client-loader').addClass('hidden');
                    $('.booking-process').removeClass('muted');
                    if(data.status === 1) {
                        $.each(data.fields, function(index, value) {
                            if(value[0].field_type === 'text') {
                                $('#bookingRequiredFields').append('<div class="col-sm-6 form-group booking-custom-field">' +
                                    '<label for="' + value[0].field_name + '">' + value[0].field_title + '</label>' +
                                    '<input type="text" name="' + value[0].field_name + '" id="' + value[0].field_name + '" class="form-control" required></div>');
                            } else {
                                $('#bookingRequiredFields').append('<div class="col-sm-6 form-group booking-custom-field">' +
                                    '<label for="' + value[0].field_name + '">' + value[0].field_title + '</label>' +
                                    '<select name="' + value[0].field_name + '" id="' + value[0].field_name + '" class="form-control" required></select></div>');

                                $.each(value[0].select_options, function(i,v) {
                                    $('#'+value[0].field_name).append('<option value="' + v.option_name + '">' + v.option_name + '</option>');
                                });
                                
                            }
                        });
                    }
                }
            });
        }
    }
    
}

var clientForm = $('#clientInsertData');

clientForm.on('submit', function(ev) { 
    
    ev.preventDefault();

    var first_name = $('#firstName').length ? $('#firstName').val() : '';
    var last_name = $('#lastName').length ? $('#lastName').val() : '';
    var phone = $('#phone').length ? $('#phone').val() : '';
    var email = $('#email').length ? $('#email').val() : '';
    var gender = $('#gender').length ? $('#gender').val() : '';
    var address = $('#address').length ? $('#address').val() : '';
    var custom_1 = $('#custom_field_1').length ? $('#custom_field_1').val() : '';
    var custom_2 = $('#custom_field_2').length ? $('#custom_field_2').val() : '';
    var custom_3 = $('#custom_field_3').length ? $('#custom_field_3').val() : '';
    var custom_4 = $('#custom_field_4').length ? $('#custom_field_4').val() : '';
    var login_email = $('#loginEmail').length ? $('#loginEmail').val() : '';
    var login_password = $('#loginPassword').length ? $('#loginPassword').val() : '';
    var account_password = $('#accountPassword').length ? $('#accountPassword').val() : '';
    if($('#accountPasswordConfirm').length) {
        var account_password_confirm = $('#accountPasswordConfirm').val();
        if(account_password != account_password_confirm) {
            toastr.error(passwords_mismatch);
            return 0;
        }
    } else {
        var account_password_confirm = '';
    }

    var service = [];
    
    $.each($('#serviceList input:checked'), function(index,val) {
        service.push(val.name);
    });
    
    if(staff_selection === 0) {
        
        var staff = [];
        $.each($('.staff-wrap select'), function() {
            var selected_value = $(this).val();
            staff.push(selected_value);
        });
        
    } else {
        var staff = [];
        staff.push('undefined');
    }
    
    var account = $('#clientAccount').val();

    $.ajax({
        type: 'post',
        dataType: 'json',
        url: ajax_url + 'ajax/booking/submit',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'booking_location':booking_location,'loyalty_status':loyalty_status,'loyalty_type':loyalty_type,'booking_date':booking_date,'booking_from':booking_from,
               'total_price':total_price_calc,'base_price':base_price,'points_awarded':points_awarded,'first_name':first_name,'last_name':last_name,
               'email':email,'phone':phone,'address':address,'gender':gender,'account':account,'custom_field_1':custom_1,'custom_field_2':custom_2,
               'custom_field_3':custom_3,'custom_field_4':custom_4,'login_email':login_email,'login_password':login_password,'password':account_password,'discount_code':discount_code,
               'password_confirm':account_password_confirm,'service':service,'staff':staff,'free_service':free_service,'points_used':points_used,'selected_discount':selected_discount},
        success: function(data) {
            if(data.status === 1) {
                swal({
                    title: booking_success,
                    text: booking_success_action,
                    type: "success",
                    showCancelButton: false,
                    confirmButtonColor: "#52B3D9",
                    confirmButtonText: return_to_homepage,
                    closeOnConfirm: true,
                }, function (isConfirm) {
                    if (isConfirm) {
                        window.history.back();
                    }
                });
            } else {
                toastr.error(data.message);
            }
        }
    });
});

function submitNewClient() {
    
    var location = $('#locationId').val();
    
    var account = $('#createAccount').is(':checked') ? 1 : 0;
    var first_name = $('#firstName').length ? $('#firstName').val() : '';
    var last_name = $('#lastName').length ? $('#lastName').val() : '';
    var email = $('#email').length ? $('#email').val() : '';
    var phone = $('#phone').length ? $('#phone').val() : '';
    var address = $('#address').length ? $('#address').val() : '';
    var gender = $('#gender').length ? $('#gender').val() : '';
    var password = $('#accountPassword').length ? $('#accountPassword').val() : '';
    var password_confirm = $('#accountPasswordConfirm').length ? $('#accountPasswordConfirm').val() : '';
    var custom_1 = $('#custom_field_1').length ? $('#custom_field_1').val() : '';
    var custom_2 = $('#custom_field_2').length ? $('#custom_field_2').val() : '';
    var custom_3 = $('#custom_field_3').length ? $('#custom_field_3').val() : '';
    var custom_4 = $('#custom_field_4').length ? $('#custom_field_4').val() : '';

    $.ajax({
        type: 'post',
        dataType: 'json',
        url: ajax_url + '/booking/new-client',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'location':location,'first_name':first_name,'last_name':last_name,
               'email':email,'phone':phone,'address':address,'gender':gender,'account':account,
               'password':password,'password_confirm':password_confirm,'custom_field_1':custom_1,
               'custom_field_2':custom_2,'custom_field_3':custom_3,'custom_field_4':custom_4},
        success: function(data) {
            if(data.status === 1) {
                if(client_name === 'first_last') {
                    var name_formated = data.client.first_name + ' ' + data.client.last_name;
                } else {
                    var name_formated = data.client.last_name + ' ' + data.client.first_name;
                }
                $.each($('#selectClient option'), function() {
                    $(this).prop('selected', false);
                });
                $('#selectClient').append('<option value="' + data.client.id + '" selected>' + name_formated + '</option>');
                $('#clientId').val(data.client.id);
                $('#addNewClientModal').modal('hide');

                $('.selectpicker').selectpicker('refresh');
            }
        }
    });
}

////////////////////

function clientRegister() {
    $('.form-section').removeClass('hidden');
    $('.select-section').addClass('hidden');
    $('.form-section').html('<div class="row client-account-group"><div class="form-group">' +
        '<label for="accountFirstName">' + trans_first_name + '</label>' +
        '<input type="text" class="form-control" name="first_name" id="accountFirstName" required></div>' +
        '<div class="form-group"><label for="accountLastName">' + trans_last_name + '</label>' +
        '<input type="text" class="form-control" name="last_name" id="accountLastName" required></div>' +
        '<div class="form-group"><label for="accountEmail">Email</label>' +
        '<input type="email" class="form-control" name="email" id="accountEmail" required>' +
        '</div><div class="form-group"><label for="accountPassword">' + password_trans + '</label>' +
        '<input type="password" class="form-control" name="password" id="accountPassword" required></div>' +
        '<div class="form-group"><label for="accountPasswordConfirmation">' + password_confirm_trans + '</label>' +
        '<input type="password" class="form-control" name="password_confirmation" id="accountPasswordConfirmation" required></div>' +
        '</div><div class="row"><div class="i-checks"><label><input type="checkbox" value="" id="gdprConsent"><i></i>'+trans_accept_gdpr+'<a href="'+privacy_policy_route+'">'+privacy_policy+'</a></label></div></div>' +
        '<div class="row"><button type="button" class="go-back-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i>' + trans_back + '</button>' +
        '<button class="login-button"><i class="fa fa-user"></i>' + trans_register + '</button></div>');
}

function clientLogin() {
    $('.form-section').removeClass('hidden');
    $('.select-section').addClass('hidden');
    $('.form-section').html('<div class="client-account-group"><div class="form-group"><label for="loginEmail">Email</label><input type="email" class="form-control" name="email" id="loginEmail" required></div><div class="form-group"><label for="loginPassword">' + password_trans + '</label><input type="password" class="form-control" name="password" id="loginPassword" required></div></div><button type="button" class="go-back-btn" onclick="goBack()"><i class="fa fa-arrow-left"></i> ' + trans_back + '</button><button class="login-button"><i class="fa fa-user"></i> ' + trans_register + '</button>');
}

function continueAsGuest() {
    $('.form-section').addClass('hidden');
    $('.select-section').addClass('hidden');
    $('.bookingStepLocation').removeClass('hidden');
}

function goBack() {
    $('.select-section').removeClass('hidden');
    $('.go-back-btn').remove();
    $('.form-section').addClass('hidden');
}

var formSubmit = $('.form-section');

formSubmit.on('submit', function(ev) { 
    ev.preventDefault();
    
    if($('#accountFirstName').length) {
        var first_name = $('#accountFirstName').val();
    } else {
        var first_name = '';
    }
    
    if($('#accountLastName').length) {
        var last_name = $('#accountLastName').val();
    } else {
        var last_name = '';
    }
    
    if($('#accountEmail').length) {
        var email = $('#accountEmail').val();
    } else {
        var email = '';
    }
    
    if($('#accountPassword').length) {
        var account_password = $('#accountPassword').val();
    } else {
        var account_password = '';
    }
    
    if($('#accountPasswordConfirmation').length) {
        var account_password_confirmation = $('#accountPasswordConfirmation').val();
    } else {
        var account_password_confirmation = '';
    }
    
    if($('#loginEmail').length) {
        var login_email = $('#loginEmail').val();
    } else {
        var login_email = '';
    }
    
    if($('#loginPassword').length) {
        var login_password = $('#loginPassword').val();
    } else {
        var login_password = '';
    }

    var consent = $('#gdprConsent').is(':checked') ? 1 : 0;

    if(login_email != '' && login_password != '') {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url + 'auth/postLogin',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
            },
            data: {'type':'ajax','email':login_email,'password':login_password},
            success: function(data) {
                if(data.status === 1) {
                    toastr.success(data.message);
                    window.location.reload();
                } else {
                    toastr.error(data.message);
                }
            }
        });
    } else {
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url + 'auth/postRegister',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
            },
            data: {'type':'ajax','first_name':first_name,'last_name':last_name,'consent':consent,
                   'email':email,'password':account_password,'password_confirmation':account_password_confirmation},
            success: function(data) {
                if(data.status === 1) {
                    toastr.success(data.message);
                    window.location.reload();
                } else {
                    toastr.error(data.message);
                }
            }
        });
    }
    
});

function returnToPreviousTab(index) {
    if(index === 1) {
        $('.booking-step-service').css('display', 'block');
        $('.booking-step-staff').css('display', 'none');
        $('#datepicker').datepicker('remove');
    } else if (index === 2) {
        $('.booking-step-staff').css('display', 'block');
        $('.booking-step-time').css('display', 'none');
        $('.action-buttons-wrap').css('display', 'block');
    } else if (index === 3) {
        $('.booking-step-time').css('display', 'block');
        $('.client-submit-info').css('display', 'none');
        $('.booking-custom-field').remove();
    }
}