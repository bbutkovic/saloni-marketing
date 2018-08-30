$('document').ready(function() {
   
   $('#add-new-user').on('click', function() {
      var email = $('#email_address').val();
      var password = $('#password').val();
      var password_confirmation = $('#password_confirm').val();
      var first_name = $('#first_name').val();
      var last_name = $('#last_name').val();
      var user_role = $('#select-role').val();
      var role_name = $('#select-role :selected').text();
      var lang = 1;
      
      $.ajax({
         method: 'POST',
         url: ajax_url + 'ajax/addNewUser',
         dataType: 'json',
         beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
         },
         data: {'user_email':email, 'password':password, 'password_confirmation':password_confirmation, 'first_name':first_name, 'last_name':last_name, 'user_role':user_role, 'language':lang},
         success: function(data) {
             if(data.status === 1) {
                 toastr.success("User created");
                 $('.user-table').append("<tr class='user'><td>"+ email +"</td><td>"+ first_name +"</td><td>"+ last_name +"</td><td>"+ role_name +"</td><td><i class='fa fa-trash delete-user'></i></td></tr>")
             }
         }
      });
   });
   
   $("#booking_radio input").on('click', function() {
      var value = $(this).val();
      var uid = $('#uid').val();
      $.ajax({
         method: 'get',
         url: ajax_url + 'ajax/booking/' + uid + '/' + value,
         success: function(data) {
            if(data.status === 1) {
               toastr.success("User booking status changed");
            }
         }
      });
   });
   
   $(document).on('click','.open-edit-modal', function() {
      var value = $(this).data('id');
      var title = $(this).data('name');
      $('#fieldId').val(value);
      $('#fieldTitle').val(title);
      
      //clear modal
      $('.ajax-group').each(function() {
         $(this).remove();
      });
      
      $.ajax({
          type: 'get',
          url: ajax_url + '/get-field-info/' + value,
          success: function(data) {
             
              if(data.status === 1) {
                  var selected = '';
                  if(data.field_type === '1') {
                     var selected_text = 'selected';
                     var selected_multiple = '';
                  } else if (data.field_type === '2') {
                     var selected_multiple = 'selected';
                     var selected_text = '';
                  }
                  $('.edit-field-wrap').append('<div class="form-group ajax-group">'+
                                               '<label for="changeFieldSelectType">' + change_field_type + '</label>'+
                                               '<select name="field_input_type" class="form-control" id="changeFieldSelectType">'+
                                               '<option value="0">' + change_field_type + '</option>'+
                                               '<option value="1"' + selected_text + '>' + field_text_trans + '</option>'+
                                               '<option value="2"' + selected_multiple + '>' + field_multiple_select_trans + '</option></select></div><hr class="ajax-group">');
                                               
                  
                  if(data.field_type === '2') {
                      $('.select-options-wrap').css('visibility', 'visible');
                      $.each(data.select_options, function(index, value) {
                         $('.select-options-wrap').append('<div class="form-group ajax-group input-group field-option-'+value.id+'"><label for="field_name">' + select_field + '</label>'+
                         '<input id="field_name'+value.id+'" class="form-control existing-field" value="' + value.option_name + '" name="' + value.id + '" type="text">'+
                         '<span class="input-group-btn"><button type="button" class="btn btn-danger delete-block" onclick="deleteSelectOptions(' + value.id + ')"><i class="fa fa-trash"></i></button></span></div></div>');
                      });
                      $('.select-options-wrap').append('<div id="fieldsHolder" class="text-center ajax-group"></div>');
                      
                  } else if (data.field_type === '1') {
                     $('.select-options-wrap').css('visibility', 'hidden');
                  }
              }
          }
      });
      
      $('#editCustomField').modal('show');
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
              nameBlock : '<div id="name_block'+this.elementCount+'"><div class="form-group input-group"><label for="field_name">' + select_field + '</label><input id="field_name'+this.elementCount+'" class="form-control" required="" name="field_name['+this.elementCount+']" type="text" value=""><span class="input-group-btn"> <button type="button" id="deletebtn'+this.elementCount+'" class="btn btn-danger delete-block" data-set="'+this.elementCount+'" ><i class="fa fa-trash"></i></button> </span></div></div>',
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
   
   $(document).on('change', '#changeFieldSelectType', function() {
      
      $('#editFieldType').val($(this).val());

      if($(this).val() === '1') {
          $('.select-options-wrap').css('visibility', 'hidden');
      } else if ($(this).val() === '2') {
          $('.select-options-wrap').css('visibility', 'visible');
          $('.select-options-wrap').append('<div id="fieldsHolder" class="text-center ajax-group"></div>');
      }
      
   });
   
});

function deleteSelectOptions(id) {
            
   $('.field-option-'+id).remove();
   
}

function deleteService(id) {
   $.ajax({
      method: 'get',
      url: ajax_url + 'ajax/deleteService/' + id,
      success: function(data) {
         if(data.status === 1) {
            $('tr[data-id="service-' + id + '"]').css('display', 'none');
            toastr.success("Service deleted");
         }
         
      }
   });
}

function deleteUser(id) {
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
            url: ajax_url + 'ajax/deleteUser/' + id,
            success: function(data) {
               if(data.status === 1) {
                  $('tr[data-id="user-' + id + '"]').css('display', 'none');
                  toastr.success(user_deleted);
               }
            }
         });
      }
   });
}

function switchLocation() {
   var select_id = document.getElementById("switch-location");
   var selected_local = select_id.options[select_id.selectedIndex].value;
   window.location.href = ajax_url + 'location-info/' + selected_local;
}

function switchLang() {
  var select_id = document.getElementById("switch-lang");
  var selected_lang = select_id.options[select_id.selectedIndex].value;
  
  $.ajax({
     type: "GET",
     url: ajax_url + 'ajax/switch-lang/' + selected_lang,
     success: function() {
         window.location.reload();
     }
  });
}

function addStaffMember() {
   var first_name = $('#first_name').val();
   var last_name = $('#last_name').val();
   var email = $('#email_address').val();
   var pin = $('#pin').val();
   var password = $('#password').val();
   var location = $('#selected_location').val();
   var role = $('#user_role').val();

    $('.client-loader').removeClass('hidden');
    $('.modal-content').addClass('muted');
   
   $.ajax({
      type: 'post',
      url: ajax_url + 'ajax/addNewStaff',
      dataType: 'json',
      beforeSend: function(request) {
         return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
      },
      data: {'first_name':first_name,'last_name':last_name,'email':email,'password':password,'pin':pin,'location':location,'role':role},
      success: function(data) {
         $('.client-loader').addClass('hidden');
         $('.modal-content').removeClass('muted');
         if(data.status === 1) {
            window.location.reload();
         } else {
            if(data.error_message != null) {
               toastr.error(data.error_message);
            } else {
               toastr.error('Error');  
            }
         }
      }
   });
}

function visitProfile(id) {
   window.location.href = ajax_url + 'profile/' + id;
}

function updateUserRole(uid) {
   var role = $('.user-role-' + uid).val();
   $.ajax({
      type: 'get',
      url: ajax_url + 'ajax/update-role/' + uid + '/' + role,
      success: function(data) {
         if(data.status === 1) {
            toastr.success('User updated');
         }
      }
   });
}

function updateUserLocation(uid) {
   var location_id = $('.user-location-' + uid).val();
   $.ajax({
      type: 'get',
      url: ajax_url + 'ajax/update-location/' + uid + '/' + location_id,
      success: function(data) {
         if(data.status === 1) {
            toastr.success('User updated');
         }
      }
   });
}

function updateSchedule() {
   var working_status = $('#workingStatus').val();
   var work_start = $('select[name=work_start]').val();
   var work_end = $('select[name=work_end]').val();
   var lunch_start = $('select[name=lunch_start]').val();
   var lunch_end = $('select[name=lunch_end]').val();
   var selected_date = $('#startDate').val();
   var end_date = $('#endDate').val();
   var uid = $('input[name=uid]').val();

   $.ajax({
      type: 'post',
      url: ajax_url + 'ajax/update-schedule',
      beforeSend: function(request) {
         return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
      },
      data: {'working_status':working_status,'work_start':work_start,'work_end':work_end,'lunch_start':lunch_start,'lunch_end':lunch_end,'selected_date':selected_date,'end_date':end_date,'uid':uid},
      success: function(data) {
         if(data.status === 1) {
            
            $.each(data.days, function(index, value) {
               var date_el = $('tr.dates-row[data-date="' + value.date + '"]');
               date_el.find('.work-start-td').html(value.start);
               date_el.find('.work-end-td').html(value.end);
               date_el.find('.lunch-start-td').html(value.lunch_start);
               date_el.find('.lunch-end-td').html(value.lunch_end);
            });
            
            toastr.success(trans_success);
            $('#editSchedule').modal('hide');
            
         } else if (data.status != 1  && data.bookings != null) {
            /*var booking_list = '';
            
            $.each(data.bookings, function(index, val) {
               booking_list += '<li class="affected-booking"><a href="' + val.id + '">' + val.service + ' - ' + val.booking_date + ' (' + val.start + ' - ' + val.end + ')</a></li>';
            });
            
            var booking_list_text = '<h3 class="text-center">' + bookings_found_trans + '</h3><br>' + booking_list;*/
            
            var booking_list_text = '<h3 class="text-center">' + bookings_found_trans + '</h3>';
            
            swal({
               html: true,
               title: trans_warning,
               text: booking_list_text,
               type: "warning",
               confirmButtonColor: "#52B3D9",
               confirmButtonText: 'OK',
               closeOnConfirm: true,
            }, function (isConfirm) {
               if (isConfirm) {
                  $('#editSchedule').modal('hide');
                  toastr.warning(reschedule_canceled);
               }
            });
         }
      }
   })
}

function getLocationStaff() {
   var location_id = $('#switch-location').val();
   window.location.href = ajax_url + 'staff/rosters/' + location_id;
}

function copyLocationHours(id) {
   $.ajax({
      type: 'get',
      url: ajax_url + 'ajax/location-hours/' + id,
      success: function(data) {
         if(data.status === 1) {
            $.each(data.hours, function(index,val) {
               var day = getDays(index);
               if(val.status != null) {
                  if(day == val.dayname) {
                     $('#work_start_' + day).val(val.start_time);
                     $('#work_end_' + day).val(val.closing_time);
                  }
               } else {
                  if(day == val.dayname) {
                     $('#work_start_' + day).prop('disabled', true).val(salon_closed);
                     $('#work_end_' + day).prop('disabled', true).val(salon_closed);
                     $('#lunch_start_' + day).prop('disabled', true).val(salon_closed);
                     $('#lunch_end_' + day).prop('disabled', true).val(salon_closed);
                  }
               }
            });
         }
      }
   })
}

function getDays(index) {
   switch(index) {
      case 0:
         var day = 'Monday';
         break;
      case 1:
         var day = 'Tuesday';
         break;
      case 2:
         var day = 'Wednesday';
         break;
      case 3:
         var day = 'Thursday';
         break;
      case 4:
         var day = 'Friday';
         break;
      case 5:
         var day = 'Saturday';
         break;
      case 6:
         var day = 'Sunday';
         break;
      default:
         var day = '';
         break;
   }
   return day;
}

function deleteField(id) {
   $.ajax({
      type: 'get',
      url: ajax_url + 'ajax/delete-field/' + id,
      success: function(data) {
         if(data.status === 1) {
            $('#field-' + id).css('display', 'none');
            toastr.success(field_deleted);
         } else {
            toastr.success(delete_failed);
         }
      }
   });
}

function deleteField(id) {
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
            url: ajax_url + 'field/delete',
            beforeSend: function(request) {
               return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
            },
            data: {'id':id},
            success: function(data) {
               if(data.status === 1) {
                  toastr.success(field_deleted);
                  $('#field-'+id).remove();
               } else {
                  toastr.error(delete_failed);
               }
            }
         });
      }
   });
}

function deletePhoto(id) {
   swal({
      title: prompt,
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
            url: ajax_url + 'location/image/delete',
            beforeSend: function(request) {
               return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
            },
            data: {'id':id},
            success: function(data) {
               if(data.status === 1) {
                  toastr.success(data.message);
                  $('#photo'+id).remove();
               } else {
                  toastr.error(data.message);
               }
            }
         });
      }
   });
}

function deleteSalon(id) {
    swal({
        title: prompt,
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
                url: delete_salon_route,
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: {'id':id},
                success: function(data) {
                    if(data.status === 1) {
                        toastr.success(data.message);
                        $('.superadmin-salons-table').find('tr[data-id="'+id+'"]').css('display', 'none');
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
}

function deleteUserAccount(id) {
    swal({
        title: prompt,
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
                url: delete_user_account,
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: {'id':id},
                success: function(data) {
                    if(data.status === 1) {
                        window.location.reload();
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
}