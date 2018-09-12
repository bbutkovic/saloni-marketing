$(document).ready(function() {
    $('.new-group').on('click', function() {
        var category = $(this).data('id');
        $('#groupCatId').val(category);
        $('#newGroupModal').modal('show');
    });
    
    $('.new-subcategory').on('click', function() {
        var group = $(this).data('id');
        $('#subGroupId').val(group);
        $('#newSubCategory').modal('show');
    });
    
    $('.new-service-btn').on('click', function() {
        $('#newService').modal('show');
        $.ajax({
            type: 'get',
            url: ajax_url + 'ajax/unique-codes',
            success: function(data) {
                $('.input-service-code').val(data.code);
                $('.input-service-barcode').val(data.barcode);
            }
        });
    });
    
    $('.new-category-btn').on('click', function() {
        $('#newCategoryModal').modal('show');
    });
    
    $('#awardPoints').on('click', function() {
        if($('#awardPoints').is(':checked')) {
            $('.points-awarded').removeClass('hidden');
        } else {
            $('.points-awarded').addClass('hidden');
        }
    });
    
    $('.d-table').DataTable({
        pageLength: 20,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            {extend: 'excel', exportOptions: { columns: [1, 2, 3] }, title: 'Staff'},
            {extend: 'pdf', exportOptions: { columns: [1, 2, 3] }, title: 'Staff'},
            {extend: 'print',
            customize: function (win){
                $(win.document.body).addClass('white-bg');
                $(win.document.body).css('font-size', '10px');
                $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ]
    });
        
    $("#spectrumCatColor").spectrum({
        color: "#6262b8",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: cat_colors,
        change: function(color) {
            $("#spectrumCatColor").val(color.toHexString());
        }
    });

    $("#spectrumGroupColor").spectrum({
        color: "#6262b8",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: group_colors,
        change: function(color) {
            $("#spectrumCatColor").val(color.toHexString());
        }
    });

    $("#spectrumSubGroupColor").spectrum({
        color: "#6262b8",
        preferredFormat: "hex",
        showInput: true,
        showPalette: true,
        palette: subgroup_colors,
        change: function(color) {
            $("#spectrumCatColor").val(color.toHexString());
        }
    });
    
    $('.select-category').on('change', function() {
        var selected_category = $(this).val();

        $('.group-block').removeClass('active');
        $('.subgroup-block').removeClass('active');
        
        $('.select-group').html('<option class="ajax-group" value="" default>' + select_group_s + '</option>');
        $('.select-subgroup').html('<option value="" default>' + select_subgroup_s + '</option>');
        $.ajax({
            type: 'get',
            url: ajax_url + 'ajax/get-group/' + selected_category,
            success: function(data) {
                $('.group-block').addClass('active');
                $.each(data, function(index,val) {
                    $('.select-group').append('<option class="ajax-group" value="' + val.id + '">' + val.name + '</option>')
                });
            }
        });
    });
    
    $('.select-group').on('change', function() {
        var selected_group = $(this).val();
        $('.subgroup-block').removeClass('active');
        $('.select-subgroup').html('<option class="ajax-group" value="" default>' + select_subgroup_s + '</option>');
        $.ajax({
            type: 'get',
            url: ajax_url + 'ajax/get-subgroup/' + selected_group,
            success: function(data) {
                $('.subgroup-block').addClass('active');
                $.each(data, function(index,val) {
                    $('.select-subgroup').append('<option class="ajax-group" value="' + val.id + '">' + val.name + '</option>')
                });
            }
        });
    });
    
    $('#editStaff').on('hidden.bs.modal', function () {
        $('.dual-listbox').bootstrapDualListbox({
            destroy: true,
            refresh: true
        });
    });
   
});

function categoryEdit(id) {
    $('#categoryId').val(id);
    $('#editCategoryModal').modal('show');
    
    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/category/' + id,
        success: function(data) {
            if(data.status === 1) {
                $('#categoryEditName').val(data.category.name);
                $('#categoryEditDesc').val(data.category.description);
                $('#spectrumEditCatColor').val(data.category.cat_color);
                if(data.category.active === 1) {
                    $('#editActiveCategory').prop('checked', true);
                } else {
                    $('#editActiveCategory').prop('checked', false);
                }
                
                $("#spectrumEditCatColor").spectrum({
                    color: data.category.cat_color,
                    preferredFormat: "hex",
                    showInput: true,
                    showPalette: true,
                    palette: cat_colors,
                    change: function(color) {
                        $("#spectrumEditCatColor").val(color.toHexString());
                    }
                }); 
            }
        }
    });
}

function deleteCategory() {
    var id = $('#categoryId').val();
    
    swal({
        title: trans_delete_check,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonColor: "#52B3D9",
        confirmButtonText: 'Yes',
        closeOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'get',
                url: ajax_url + 'ajax/category/delete/' + id,
                success: function(data) {
                    if(data.status === 1) {
                        window.location.reload();
                    } else {
                        toastr.error(delete_failed);
                    }
                }
            });
        }
    });
    
}

function editGroup(id) {
    $('#groupId').val(id);
    $('#editGroupModal').modal('show');
    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/group/' + id,
        success: function(data) {
            if(data.status === 1) {
                $('#editGroupName').val(data.group.name);
                $('#editGroupDesc').val(data.group.description);
                if(data.group.active === 1) {
                    $('#editActiveGroup').prop('checked', true);
                } else {
                    $('#editActiveGroup').prop('checked', false);
                }
                
                $("#spectrumEditGroupColor").spectrum({
                    color: data.group.group_color,
                    preferredFormat: "hex",
                    showInput: true,
                    showPalette: true,
                    palette: group_colors,
                    change: function(color) {
                        $("#spectrumEditCatColor").val(color.toHexString());
                    }
                });
            }
        }
    });
}

function deleteGroup() {
    var id = $('#groupId').val();
    
    swal({
        title: trans_delete_check,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonColor: "#52B3D9",
        confirmButtonText: 'Yes',
        closeOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'get',
                url: ajax_url + 'ajax/group/delete/' + id,
                success: function(data) {
                    if(data.status === 1) {
                        window.location.reload();
                    } else {
                        toastr.error(delete_failed);
                    }
                }
            });
        }
    });
}

function editSubCategory(id) {
    $('#subCategoryId').val(id);
    $('#editSubCategory').modal('show');
    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/subcategory/' + id,
        success: function(data) {
            if(data.status === 1) {
                $('#subCategoryEditName').val(data.subcategory.name);
                $('#subCategoryEditDesc').val(data.subcategory.description);

                if(data.subcategory.active === 1) {
                    $('#editActiveSubCategory').prop('checked', true);
                } else {
                    $('#editActiveSubCategory').prop('checked', false);
                }
                
                $("#spectrumEditSubGroupColor").spectrum({
                    color: data.subcategory.subgroup_color,
                    preferredFormat: "hex",
                    showInput: true,
                    showPalette: true,
                    palette: subgroup_colors,
                    change: function(color) {
                        $("#spectrumEditCatColor").val(color.toHexString());
                    }
                });
            }
        }
    });
}

function deleteSubCategory() {
    var id = $('#subCategoryId').val();
    
    swal({
        title: trans_delete_check,
        type: "warning",
        showCancelButton: true,
        cancelButtonText: 'Cancel',
        confirmButtonColor: "#52B3D9",
        confirmButtonText: 'Yes',
        closeOnConfirm: true,
    }, function (isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: 'get',
                url: ajax_url + 'ajax/subcategory/delete/' + id,
                success: function(data) {
                    if(data.status === 1) {
                        window.location.reload();
                    } else {
                        toastr.error(delete_failed);
                    }
                }
            });
        }
    });
}

function editService(id) {
    
    $('#editService').modal('show');
    $('#serviceId').val(id);
    
    //reset checkboxes
    $('#editServiceAvailability').prop('checked', false);
    $('#editServiceStaff').prop('checked', false);
    $('#editAllowDiscounts').prop('checked', false);
    $('#editAwardPoints').prop('checked', false);
    $('.ajax-group').remove();
    
    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/service/' + id,
        success: function(data) {
            if(data.status === 1) {
                $('#editCategory').val(data.service.service.category);
                $.ajax({
                    type: 'get',
                    url: ajax_url + 'ajax/get-group/' + data.service.service.category,
                    success: function(data) {
                        $('.group-block').addClass('active');
                        $.each(data, function(index,val) {
                            $('#editGroup').append('<option class="ajax-group" value="' + val.id + '">' + val.name + '</option>')
                        });
                    },
                    async: false
                });
                $('#editGroup').val(data.service.service.group);
                
                $.ajax({
                    type: 'get',
                    url: ajax_url + 'ajax/get-subgroup/' + data.service.service.group,
                    success: function(data) {
                        $('.edit-subgroup-block').addClass('active');
                        $.each(data, function(index,val) {
                            $('#editSubGroup').append('<option class="ajax-group" value="' + val.id + '">' + val.name + '</option>')
                        });
                    },
                    async: false
                });
                $('#editSubGroup').val(data.service.service.subgroup);
                
                $('.group-block').addClass('active');
                $('#editServiceName').val(data.service.service_details.name);
                $('#editServiceDesc').val(data.service.service_details.description);
                $('#editServiceCode').val(data.service.service_details.code);
                $('#editServiceBarcode').val(data.service.service_details.barcode);
                $('#editServiceDuration').val(data.service.service_details.service_length);
                $('#serviceVat').val(data.service.service_details.vat);
                $('#editServicePrice').val(data.service.service_details.price);
                $('#editServiceTax').val(data.service.service_details.tax);
                
                if(data.service.service_details.available === 1) {
                    $('#editServiceAvailability').prop('checked', true);
                }
                
                if(data.service.service_details.all_staff === 1) {
                    $('#editServiceStaff').prop('checked', true);
                }
                
                if(data.service.service.allow_discounts) {
                    $('#editAllowDiscounts').prop('checked', true);
                }
                
                if(data.service.service.award_points) {
                    $('#editAwardPoints').prop('checked', true);
                }
                
                if(data.service.service.points_awarded != null) {
                    $('#editPointsAwarded').val(data.service.service.points_awarded);
                } else {
                    $('#editPointsAwarded').val(0);
                }
            }
        }
    });
}

function deleteServiceById(id) {
    swal({
        title: 'Are you sure?',
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
                url: ajax_url + 'ajax/deleteService/' + id,
                success: function(data) {
                    if(data.status === 1) {
                        window.location.reload();
                    }
                }
            });
        }
    });
}

function editStaff(id) {
    $('#editStaff').modal('show');
    $('#serviceNum').val(id);
    
    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/get-service-staff/' + id,
        success: function(data) {
            $('.dual-listbox').empty();
            $.each(data, function(index,val) {
                if(val.selected === 1) {
                    $('.dual-listbox').append('<option class="staff-options" value="' + val.id + '" selected>' + val.first_name + ' ' + val.last_name + '</option>');
                } else {
                    $('.dual-listbox').append('<option class="staff-options" value="' + val.id + '">' + val.first_name + ' ' + val.last_name + '</option>');
                }
            });
            $('.dual-listbox').bootstrapDualListbox('refresh', true);
        },
        async: false
    });
    
    $('.dual-listbox').bootstrapDualListbox({
        infoText: false,
        nonSelectedListLabel: 'Non-selected',
        selectedListLabel: 'Staff selected for this service',
    });
}

function updateServiceLoyalty() {

    var allow_discounts = [];
    var award_points = [];
    var award_points_check = [];

    $('.service-discounts').each(function() {
        var service_discount = $(this);
        var service_val = $(this).is(':checked') ? 1 : 0;

        allow_discounts.push({'service':service_discount[0].name,'allow_discount':service_val});
    });

    $('.service-points').each(function() {
        var service_points = $(this);

        award_points.push({'service':service_points[0].name,'service_points':service_points[0].value});
    });

    $('.service-points-check').each(function() {
        var points_check = $(this);
        var service_val = $(this).is(':checked') ? 1 : 0;

        award_points_check.push({'service':points_check[0].name,'award_points':service_val});
    });

    $.ajax({
        type: 'post',
        url: ajax_url + 'loyalty/update/services',
        dataType: 'json',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
        },
        data: {'discounts':allow_discounts,'points':award_points,'award_points':award_points_check},
        success: function(data) {
            if(data.status === 1) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }

    });
}

$(document).on('change', '#importFromLocation', function() {
    var location = $(this).val();

    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/location/' + location + '/services',
        success: function(data) {
            if(data.status === 1) {
                $.each(data.services, function(index, value) {
                    $.each(value, function(i, v) {
                        $('.services-list').append('<div class="col-xs-1 checkbox checkbox-primary appended-content">' +
                            '<input type="checkbox" id="checkbox' + v.id + '" class="service-checkbox"  name="' + v.id + '">' +
                            '<label for="checkbox' + v.id + '"></label></div><div class="col-xs-11 service-name-container service-name appended-content"><h2>' + v.name + ' - ' + v.category_name + ' ' + v.subgroup_name + '</h2></div></div>'
                        )
                    });
                });
            } else {
                toastr.error(data.message);
            }
        }
    });

    $('#importServicesModal').modal('show');
});

$(document).on('hidden.bs.modal', '#importServicesModal', function() {
    $('.appended-content').each(function() {
        $(this).remove();
    });
    $('#importFromLocation').val(0);
});

function submitServices() {
    var selected_services = [];
    $('.service-checkbox').each(function() {
        if($(this).is(':checked')) {
            var service_id = $(this).attr('name');
            selected_services.push(service_id);
        }
    });

    if(typeof selected_services != 'undefined' && selected_services.length != 0) {
        $.ajax({
            type: 'post',
            url: ajax_url + 'location/services/import',
            dataType: 'json',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            data: {'services':selected_services},
            success: function(data) {
                console.log(data);
                if(data.status === 1) {
                    window.location.reload();
                } else {
                    toastr.error(data.message);
                }
            }
        });
    }
}