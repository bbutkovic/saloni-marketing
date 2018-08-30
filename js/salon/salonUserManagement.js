$("document").ready(function() {
   $(".permission-checkbox").on("click",function() {
       var action;
       if($(this).is(":checked"))
          action = "attach";
       else
           action = "detach";
      
       var roleID = $(this).data("role");
       var permName = $(this).data("perm");
       
       
        $.ajax({
         method: 'POST',
         url: change_perm_route,
         dataType: 'json',
         beforeSend: function(request) {
            return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
         },
         data: {roleID:roleID,permName:permName,action:action},
         success: function(data) {
            console.log(data);
         }
      });
        
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
});