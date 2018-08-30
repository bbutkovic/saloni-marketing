$(document).ready(function() {

    $('#aboutImageUpload').on('change', function() {
        readIMG(this);
    });

    var submit_form = $('#contentForm');
    submit_form.on('submit', function(ev) {
       ev.preventDefault();

        var company_introduction = $('#companyIntroduction').val();
        var website_service = $('#websiteServiceText').val();
        var website_booking = $('#websiteBookingText').val();
        var website_about = $('#websiteAboutText').val();
        var terms_and_conditions = $('#termsAndConditions').val();
        var btn_text = $('#buttonText').val();
        var btn_bg = $('#spectrumBackground').val();
        var btn_text_color = $('#spectrumText').val();

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url + 'website/content/update',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            data: {'company_introduction':company_introduction,'website_service':website_service,'website_booking':website_booking,'website_about':website_about,'terms_and_conditions':terms_and_conditions,'button_text':btn_text,'button_bg':btn_bg,'button_text_color':btn_text_color},
            success: function(data) {
                unsaved = false;
                if(data.status === 1) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            }

        });
    });

    var slider_promo_form = $('#sliderPromoForm');
    slider_promo_form.on('submit', function(ev) {
        ev.preventDefault(ev);

        var slider_content = [];
        $('.slider-content-container').each(function() {
            var slider = {
                sliderTitleName: $(this).find('.slider-title').attr('name'),
                sliderTitleValue: $(this).find('.slider-title').val(),
                sliderTextName: $(this).find('.slider-text').attr('name'),
                sliderTextValue: $(this).find('.slider-text').val(),
                checkboxName: $(this).find('.book-now-checkbox').attr('name'),
                checkboxValue: $(this).find('.book-now-checkbox').is(':checked') ? 1 : 0,
                sliderActive: $(this).find('.slider-active').is(':checked') ? 1 : 0
            };
            slider_content.push(slider);
        });

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: slider_promo_route,
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            data: {slider_content:slider_content},
            success: function(data) {
                unsaved = false;
                if(data.status === 1) {
                    toastr.success(data.message);
                } else {
                    toastr.error(data.message);
                }
            }
        });
    });

    $('#setUrl').on('click', function() {
        var url = $('.input-url').val();
       
        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url + '/website/set-url',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
            },
            data: {'url':url},
            success: function(data) {
                
                if(data.status === 1) {
                    toastr.success(data.message);
                    $('.website-options-heading').append('<a href="' + data.url + '">' + data.url + '</a>');
                    if($('.website-url').length) {
                        $('.website-url').remove();
                    }
                } else {
                    toastr.error(data.message);
                }
            }
            
        });
    });
    
    $('input[name="featured_image"]').on('change', function() {
        var fileUpload = document.getElementById('featuredImageUpload');
        console.log(fileUpload);
 
        //Check whether the file is valid Image.
        var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+(.jpg|.png|.gif)$");
        if (regex.test(fileUpload.value.toLowerCase())) {
     
            //Check whether HTML5 is supported.
            if (typeof (fileUpload.files) != "undefined") {
                //Initiate the FileReader object.
                var reader = new FileReader();
                //Read the contents of Image File.
                reader.readAsDataURL(fileUpload.files[0]);
                reader.onload = function (e) {
                    //Initiate the JavaScript Image object.
                    var image = new Image();
     
                    //Set the Base64 string return from FileReader as source.
                    image.src = e.target.result;
                           
                    //Validate the File Height and Width.
                    image.onload = function () {
                        var height = this.height;
                        var width = this.width;
                        if (height < 250 || width < 500) {
                            toastr.error(image_dim_not_valid);
                            $('.submit-post-btn').prop('disabled', true);
                        } else {
                        
                            $('.submit-post-btn').attr('disabled', false);
                        }
                    };
     
                }
            } else {
                alert("This browser does not support HTML5.");
                return false;
            }
        } else {
            alert("Please select a valid Image file.");
            return false;
        }
    });
    
});

function deleteBlogPost(id) {
    
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
                url: ajax_url + 'blog/post/delete',
                beforeSend: function(request) {
                   return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
                },
                data: {'id':id},
                success: function(data) {
                    if(data.status === 1) {
                        toastr.success(data.message);
                        $('#blogPost'+id).remove();
                    } else {
                        toastr.error(data.message);
                    }
                }
            });
        }
    });
    
}

function editBlogPost(id) {
    
    $('#editBlogPostModal').modal('show');
    $('#blogPostId').val(id);
    
    $.ajax({
        type: 'get',
        url: ajax_url + '/blog/' + id,
        success: function(data) {

            if(data.status === 1) {
                $('#editPostTitle').val(data.post.title);
                $('#editPostDesc').val(data.post.description);
                
                var content = data.post.content;
            
                var editor = tinymce.get('summernote-edit');
                editor.setContent(content);
            }
        }
    });
    
}

function updateSocialLinks(id) {
    var facebook = $('#facebookLink').val();
    var twitter = $('#twitterLink').val();
    var instagram = $('#instagramLink').val();
    var pinterest = $('#pinterestLink').val();
    
    $.ajax({
        method: 'post',
        url: ajax_url + 'website/social-links/save',
        beforeSend: function(request) {
           return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content')); 
        },
        data: {'id':id, 'facebook_link':facebook,'twitter_link':twitter,'instagram_link':instagram,'pinterest_link':pinterest},
        success: function(data) {
            if(data.status === 1) {
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        }
    });
}

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
                url: ajax_url + 'website/slider-image/delete',
                beforeSend: function(request) {
                   return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
                },
                data: {'id':id},
                success: function(data) {
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

function readIMG(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.about-image-placeholder').css('background-image', 'url("'+e.target.result+'")');
        }
        $('.about-image-placeholder').addClass('active');
        reader.readAsDataURL(input.files[0]);
    }
}