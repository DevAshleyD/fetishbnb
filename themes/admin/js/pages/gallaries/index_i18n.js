/*
 * Author: DK
 * Description: Pages form.js
 **/

// Multiple images preview in browser
var imagesPreview = function(input, placeToInsertImagePreview) {

    if (input.files) {
        var filesAmount = input.files.length;

        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function(event) {
                $($.parseHTML('<img class="col-sm-2 img-responsive thumbnail">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }

};

var remove_image = function(image_id) {
    text = "{{common_image}}";
    swal({
        title               : "{{alert_delete_question_1}} ?",
        type                : "error",
        showCancelButton    : true,
        confirmButtonColor  : "#DD6B55",
        confirmButtonText   : "{{action_delete}}",
        cancelButtonText    : "{{action_cancel}}",
        closeOnConfirm      : false,
        closeOnCancel       : false,
        showLoaderOnConfirm : true,
        html                : true,
        allowEscapeKey      : true,
        timer               : 5000,
    }, function (isConfirm) {
        if (isConfirm) {
             var data    = {
                csrf_token  : csrf_token,
                id          : image_id, 
            }; 
                
            ajaxPost('delete', '.remove-image', data, function(response) {
                if(response.flag == 1)
                    swal(text, "{{alert_delete_success_1}}", "success");
                else
                    swal("{{alert_delete_fail_1}}", response.msg, "warning");

                if(response.flag == 1) {
                    setTimeout(function () {
                        window.location.href = site_url+uri_seg_1+'/'+uri_seg_2;
                    }, 1000);    
                }
            });
        } else {
            swal("{{alert_delete_cancel}}", "{{alert_data_safe}}", "error");
        }
    });
}

$(function () {

    "use strict";

    // Initialize Lightbox
    $('#aniimated-thumbnials').lightGallery({
        thumbnail: false,
        selector: 'a'
    });
    
    $('#images').on('change', function() {
        $('div.gallery').html('');
        imagesPreview(this, 'div.gallery');
    });


    var post_flag = 1;
    // Ajax form submit with validation errors
    $('form#form-create').on('submit', function(e) {
        e.preventDefault();
        
        if(post_flag == 1) {
            post_flag = 0;
            
            $('.form-line').removeClass('error');    
            $('label').removeClass('text-danger');   
            
            var formData = new FormData($(this)[0]);
            ajaxPostMultiPart('save', '#submit_loader', formData, function(response) {
                if(response.flag == 0) {   
                    $('#validation-error').show();
                    $('#validation-error p').html(response.msg);

                    $.each(JSON.parse(response.error_fields), (index, item) => {
                        $("input[name*='"+item+"'], select[name*='"+item+"'], textarea[name*='"+item+"']").closest('.form-line').addClass('error');
                    });

                    post_flag = 1;
                    $('#submit_loader').remove();
                } else {
                    setTimeout(function() {
                        window.location.href = site_url+uri_seg_1+'/'+uri_seg_2;
                    }, 1000);
                }
            });
        }
        return false;           
    });

});