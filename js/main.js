var unsaved = false;

$('document').ready(function() {
    $("form :input").change(function() {
        unsaved = true;
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        var active_tab = $(e.target).attr('href');
        var previous_tab = $(e.relatedTarget).attr('href');
        
        //element name
        var active_el = active_tab.replace('#', '');
        var prev_el = previous_tab.replace('#', '');
        
        if(unsaved) {
            swal({
                title: swal_unsaved,
                text: swal_unsaved_desc,
                type: "warning",
                confirmButtonColor: "#52B3D9",
                confirmButtonText: swal_confirm,
                closeOnConfirm: true,
            }, function (isConfirm) {
                $(e.target).parent().removeClass('active');
                $('#'+active_el).removeClass('active');
                $(e.relatedTarget).parent().addClass('active');
                $('#'+prev_el).addClass('active');
                unsaved = false;
            });
        }
    });
    
    $('[data-toggle="tooltip"]').tooltip();
    
    $('.image-file').on('change', function() {
        var image_path = $(this).val();
        var image_name = image_path.substring(image_path.lastIndexOf("\\") + 1, image_path.length)
        $('.image-change').text(image_name);
    });
    
    $('.edit-schedule').on('click', function(e) {

        var start_date = $(this).data('date');

        $('#startDate').val(start_date);
        $('span.date').text(start_date);
        $('#endDate').val(moment(start_date).format('YYYY-MM-DD'));
        
        $('#endDate').datepicker({
            format: 'yyyy-mm-dd',
            keyboardNavigation: false,
            forceParse: false,
            startDate: moment(start_date).format('YYYY-MM-DD'),
            autoclose: true
        });
    });
    
    $("#selectedDate").on('change', function() {
        
        var date = $("#selectedDate").val();
        $("#selectDateInput").val(date);

        if(time_format == 'time-ampm') {
            date = moment(date).format('MM.DD.YYYY');
        } else {
            date = moment(date).format('DD.MM.YYYY');
        }

        $('.week-start-indicator').html('<strong>' + week_start_indicator + '</strong> ' + date);
    });
    
    $("#repeatWeeks").on('change', function() {
        
        var selected = $("#repeatWeeks").val();

        $("input[name=type]").val(selected);
       
        if(selected === '1') {
            $('.description-small').remove();
            $('#scheduleDesc').append('<small class="text-muted description-small">'+week_sch+'</small>');
        } else if(selected === '2') {
            $('.description-small').remove();
            $('#scheduleDesc').append('<small class="text-muted description-small">'+two_weeks+'</small>');
        } else if(selected === '3') {
            $('.description-small').remove();
            $('#scheduleDesc').append('<small class="text-muted description-small">'+three_weeks+'</small>');
        } else if(selected === '4') {
            $('.description-small').remove();
            $('#scheduleDesc').append('<small class="text-muted description-small">'+four_weeks+'</small>');
        }
       
    });
    
    $("#repeatFor").on('change', function() {
        var selected = $('#repeatFor').val();
        $("#repeatForInput").val(selected);
    });
    

    
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
            nameBlock : '<div id="name_block'+this.elementCount+'" class="col-lg-12 text-center"><div class="form-group input-group"><label for="field_name">' + select_field + '</label><input id="field_name'+this.elementCount+'" class="form-control select-option" required="" name="field_name['+this.elementCount+']" type="text" value=""><span class="input-group-btn"> <button type="button" id="deletebtn'+this.elementCount+'" class="btn btn-danger delete-block" data-set="'+this.elementCount+'" ><i class="fa fa-trash"></i></button> </span></div></div>',
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

$(document).on("click", '#spawnBtn', function() {
    spawner.elementCount++;
    spawner.setElements();
    spawner.spawn();
});

$("#resetFormBtn").on("click",function() {
   spawner.removeElements();
   spawner.elementCount = 0;
});

function clearHours() {
    $('.time-select').val("");
}

function deleteLocation() {
    swal({
        title: prompt,
        text: delete_desc,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#52B3D9",
        confirmButtonText: delete_location,
        closeOnConfirm: false
    }, function (isConfirm) {
        if (isConfirm) {
            window.location.href = ajax_url + 'location/delete';
        }
    });
}

function copyDataFromSalon(id) {
    $.ajax({
        type: 'get',
        url: ajax_url + 'ajax/get-salon-data/',
        success: function(data) {
            if(data.status === 1) {
                $('#location_name').val(data.data.business_name);
                $('#location_phone').val(data.data.business_phone);
                $('#location_mobile_phone').val(data.data.mobile_phone);
                $('#location_email').val(data.data.email_address);
                $('#location_address').val(data.data.address);
                $('#location_city').val(data.data.city);
                $('#location_zip').val(data.data.zip_code);
                $('#location_country').val(data.data.country);
                $('#billing_oib').val(data.billing.oib);
                $('#billing_iban').val(data.billing.iban);
                $('#billing_swift').val(data.billing.swift);
            }
        }
    });
}

function getStaffBooking() {
    var staff = $('#staffPicker').val();
    window.location.href = ajax_url + 'appointments/' + staff;
}

function adminSwitchLocation() {
    var location = $('#switchLocation').val();
    
    $.get({
        type: 'post',
        url: ajax_url + 'location/change',
        beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'location':location},
        success: function() {
            window.location.reload();
        }
    });
}

function addNewField(type) {
   $('#newFieldModal').modal('show');
   $('#fieldLocation').val(field_location);
}