function deleteSliderImage(id) {
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
                url: ajax_url + 'website/slider/delete',
                beforeSend: function(request) {
                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: {'id':id},
                success: function(data) {
                    console.log(data);
                    if(data.status === 1) {
                        toastr.success(data.message);
                        $('#webImage'+id).remove();
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
}
