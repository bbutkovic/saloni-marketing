$(document).ready(function() {

    $('.uploaded-images').on('change', '.select-global-image', function() {
        var el = $(this);
        var image_id = $(this).data('id');
        var status = $(this).is(':checked') ? 1 : 0;
        var changed = true;

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url + 'website/update-slider',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            data: {'image':image_id,'status':status},
            success: function(data) {
                unsaved = false;
                if(data.status === 1) {
                    toastr.success(data.message);
                } else {
                    el.prop('checked', false);
                    toastr.error(data.message);
                }
            }
        });
    });

    $('#activateBookingBtn').on('change', function() {
        var status = $(this).is(':checked') ? 1 : 0;
        if(status === 1) {
            $('#bookingButtonSection').removeClass('hidden');
        } else {
            $('#bookingButtonSection').addClass('hidden');
        }
    });

    $('#aboutImageUpload').on('change', function() {
        readIMG(this);
    });

    var submit_form = $('#contentForm');
    submit_form.on('submit', function(ev) {
        ev.preventDefault();
        tinyMCE.triggerSave();

        var company_introduction = $('#companyIntroduction').val();
        var website_service = $('#websiteServiceText').val();
        var website_products = $('#websiteProductsText').val();
        var website_booking = $('#websiteBookingText').val();
        var terms_and_conditions = $('#termsAndConditions').val();
        var display_pricing = $('#displayPricing').is(':checked') ? 1 : 0;
        var display_booking_btn = $('#activateBookingBtn').is(':checked') ? 1 : 0;
        if(display_booking_btn === 1) {
            var btn_text = $('#buttonText').val();
            var btn_bg = $('#spectrumBackground').val();
            var btn_text_color = $('#spectrumText').val();
            if(btn_text === '' || btn_bg === '' || btn_text_color === '') {
                toastr.error(button_info_required);
                return false;
            }
        }

        $.ajax({
            type: 'post',
            dataType: 'json',
            url: ajax_url + 'website/content/update',
            beforeSend: function(request) {
                return request.setRequestHeader('X-CSRF-Token', $("meta[name='_token']").attr('content'));
            },
            data: {'company_introduction':company_introduction,'display_booking_btn':display_booking_btn,'display_pricing':display_pricing,'website_service':website_service,'website_booking':website_booking,'website_products':website_products,'terms_and_conditions':terms_and_conditions,'button_text':btn_text,'button_bg':btn_bg,'button_text_color':btn_text_color},
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

    tinymce.init({
        selector: "#companyIntroduction",
        theme: "modern",
        paste_data_images: true,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons | fontsizeselect fontselect",
        image_advtab: true,
        font_formats: 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
        file_picker_callback: function(callback, value, meta) {
            if (meta.filetype == 'image') {
                $('#upload').trigger('click');
                $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                            alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
        },
        templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        }, {
            title: 'Test template 2',
            content: 'Test 2'
        }]
    });

    tinymce.init({
        selector: "#websiteServiceText",
        theme: "modern",
        paste_data_images: true,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons | fontsizeselect fontselect",
        image_advtab: true,
        font_formats: 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
        file_picker_callback: function(callback, value, meta) {
            if (meta.filetype == 'image') {
                $('#upload').trigger('click');
                $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                            alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
        },
        templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        }, {
            title: 'Test template 2',
            content: 'Test 2'
        }]
    });

    tinymce.init({
        selector: "#websiteBookingText",
        theme: "modern",
        paste_data_images: true,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons | fontsizeselect fontselect",
        image_advtab: true,
        font_formats: 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
        file_picker_callback: function(callback, value, meta) {
            if (meta.filetype == 'image') {
                $('#upload').trigger('click');
                $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                            alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
        },
        templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        }, {
            title: 'Test template 2',
            content: 'Test 2'
        }]
    });

    tinymce.init({
        selector: "#websiteProductsText",
        theme: "modern",
        paste_data_images: true,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons | fontsizeselect fontselect",
        image_advtab: true,
        font_formats: 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
        file_picker_callback: function(callback, value, meta) {
            if (meta.filetype == 'image') {
                $('#upload').trigger('click');
                $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                            alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
        },
        templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        }, {
            title: 'Test template 2',
            content: 'Test 2'
        }]
    });

    tinymce.init({
        selector: "#termsAndConditions",
        theme: "modern",
        paste_data_images: true,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste textcolor colorpicker textpattern"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons | fontsizeselect fontselect",
        image_advtab: true,
        font_formats: 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings,zapf dingbats',
        file_picker_callback: function(callback, value, meta) {
            if (meta.filetype == 'image') {
                $('#upload').trigger('click');
                $('#upload').on('change', function() {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        callback(e.target.result, {
                            alt: ''
                        });
                    };
                    reader.readAsDataURL(file);
                });
            }
        },
        templates: [{
            title: 'Test template 1',
            content: 'Test 1'
        }, {
            title: 'Test template 2',
            content: 'Test 2'
        }]
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
        };
        $('.about-image-placeholder').addClass('active');
        reader.readAsDataURL(input.files[0]);
    }
}

$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href");
    if(target == '#tab-3' && changed) {
        swal({
            title: changed_slider,
            type: "warning",
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonColor: "#52B3D9",
            confirmButtonText: reload_page,
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
});