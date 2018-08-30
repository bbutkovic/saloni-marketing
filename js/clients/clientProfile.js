$(document).ready(function() {
   var client_form = $('#clientProfileForm');

   client_form.on('submit', function(ev) {
       ev.preventDefault();
       var client_id = $('#clientId').val();
       var first_name = $('#firstName').val();
       var last_name = $('#lastName').val();
       var email = $('#email').val();
       var phone = $('#phone').val();
       var address = $('#address').val();
       var city = $('#city').val();
       var zip = $('#zip').val();
       var gender = $('#gender').val();
       var birthday = $('#birthday').val();
       var allow_sms_reminders = $('#allowSmsReminders').is(':checked') ? 1 : 0;
       var allow_sms_marketing = $('#allowSmsMarketing').is(':checked') ? 1 : 0;
       var allow_email_reminders = $('#allowEmailReminders').is(':checked') ? 1 : 0;
       var allow_email_marketing = $('#allowEmailMarketing').is(':checked') ? 1 : 0;
       var allow_viber_reminders = $('#allowViberReminders').is(':checked') ? 1 : 0;
       var allow_viber_marketing = $('#allowViberMarketing').is(':checked') ? 1 : 0;
       var allow_facebook_reminders = $('#allowFacebookReminders').is(':checked') ? 1 : 0;
       var allow_facebook_marketing = $('#allowFacebookMarketing').is(':checked') ? 1 : 0;
       var custom_fields = [];
       $('.custom-field-val').each(function(index, value) {
           var field = {
               fieldName: value.name,
               fieldValue: value.value
           };
           custom_fields.push(field);
       });

       $.ajax({
           type: 'post',
           url: update_profile_route,
           beforeSend: function (request) {
               return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
           },
           dataType: 'json',
           data: {
               client_id: client_id, first_name: first_name,address: address,
               last_name: last_name,city: city,
               email: email,zip: zip,
               phone: phone,gender: gender,
               birthday: birthday, custom_fields: custom_fields,
               allow_sms_reminders: allow_sms_reminders,allow_sms_marketing: allow_sms_marketing,
               allow_email_reminders: allow_email_reminders,allow_email_marketing: allow_email_marketing,
               allow_viber_reminders: allow_viber_reminders,allow_viber_marketing: allow_viber_marketing,
               allow_facebook_reminders: allow_facebook_reminders,allow_facebook_marketing: allow_facebook_marketing
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

});