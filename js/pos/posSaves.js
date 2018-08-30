function submitBillingInfo() {
    var site_location = $('#siteLocation').val();
    var input_id = $('#inputId').val();
    var billing_address = $('#billingAddress').val();
    var billing_city = $('#billingCity').val();
    var billing_zip = $('#billingZip').val();
    var billing_country = $('#billingCountry').val();
    var oib = $('#billingOib').val();
    var iban = $('#billingIban').val();
    var swift = $('#billingSwift').val();
    var location_label = $('#locationLabel').val();
    var pdv_sustav = $('#pdv1').is(':checked') ? 1 : 0;
    var slijednost = $('#oznakaSlijednosti').val();

    $.ajax({
        type: 'post',
        url: billing_info_route,
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {site_location:site_location,input_id:input_id,billing_address:billing_address,billing_city:billing_city,
               billing_zip:billing_zip,billing_country:billing_country,billing_oib:oib,billing_iban:iban,billing_swift:swift,
               location_label:location_label,pdv_sustav:pdv_sustav,slijednost:slijednost},
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

function addChargingDevice() {
    $('#chargingDeviceLabel').val('');
    $('#deviceId').val('');
    $('#chargingDeviceModal').modal('show');
}

function editChargingLabel(id) {
    var el = $('.charging-devices-tb').find('tr[data-id="'+id+'"]');
    var device_label = el.find('.device-label').html();
    $('#chargingDeviceLabel').val(device_label);
    $('#deviceId').val(id);
    $('#chargingDeviceModal').modal('show');
}

function submitChargingDevice() {
    var label = $('#chargingDeviceLabel').val();
    var id = $('#deviceId').val();

    $.ajax({
        type: 'post',
        url: add_device_route,
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {id:id,label:label},
        success: function(data) {
            unsaved = false;
            if(data.status === 1) {
                toastr.success(data.message);
                if(id === null) {
                    $('.charging-devices-tb').append('<tr><td class="text-center">'+data.device.device_label+'</td><td class="text-center">'+data.device.location_label+'</td>' +
                        '<td class="text-center"><a href="#" onclick="editChargingDevice('+data.device.id+')"><i class="fa fa-pencil"></i></a></td>' +
                        '<td class="text-center"><a href="#" onclick="deleteChargingDevice('+data.device.id+')"><i class="fa fa-trash"></i></a></td></tr>');
                } else {
                    $('tr[data-id="'+id+'"]').find('.device-label').html(label);
                }

                $('#chargingDeviceModal').modal('hide');
            } else {
                toastr.error(data.message);
            }
        }
    });
}

function deleteChargingLabel(id) {
    swal({
        title: 'Jeste li sigurni?',
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
                url: delete_device_route,
                dataType: 'json',
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: {id:id},
                success: function(data) {
                    if(data.status === 1) {
                        $('tr[data-id="' + id + '"]').css('display', 'none');
                        toastr.success(data.message);
                    }
                }
            });
        }
    });
}

function viewInvoice(id) {
    $('.appended-value').remove();
    $.ajax({
        type: 'get',
        url: ajax_url + 'pos/invoice/' + id,
        success: function(data) {
            if(data.status === 1) {
                var base_price = 0;
                var vat_price = 0;

                $('.company-address').html('<strong>Inspinia, Inc.</strong><br>' +
                    '106 Jorg Avenu, 600/10<br>' +
                    'Chicago, VT 32456<br>\n' +
                    '<abbr title="Phone">P:</abbr> (123) 601-4590');
                $('.client-address').html('<strong>'+ data.invoice.client.name +'</strong><br>' + data.invoice.client.address + '<br>' + data.invoice.client.city + '<br><abbr title="Phone">P:</abbr>' + data.invoice.client.phone);
                $('.invoice-date').html(data.invoice.invoice_date);

                $.each(data.invoice.services, function(index, value) {
                    $('.invoice-table tbody').append('<tr class="appended-value"><td><div><strong>' + value.name + '</strong></div></td>' +
                        '<td>1</td>' +
                        '<td>' + value.price_no_vat + ' ' + data.invoice.currency.toUpperCase() + '</td>' +
                        '<td>' + value.vat + '</td>' +
                        '<td>' + value.total_price.toUpperCase() + '</td></tr>');
                    base_price += Number(value.price_no_vat);
                });
                vat_price = base_price * Number('0.'+25);
                $('.base-price').html(base_price + ' ' + data.invoice.currency.toUpperCase());
                $('.tax').html(vat_price + ' ' + data.invoice.currency.toUpperCase());
                $('.total-price').html(data.invoice.amount_charged);
            } else {
                toastr.error(data.message);
            }
        }
    });
    $('#invoiceModal').modal('show');
}