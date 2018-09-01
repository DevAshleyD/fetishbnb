/*
 * Author: DK
 * Description: Users edit.js
 **/

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#c_image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
};

$("#image").change(function(){
    readURL(this);
});

$(function () {

    "use strict";

    $('#dob').datepicker({
      autoclose: true
    });

    // Ajax form submit with validation errors
    var post_flag = 1;
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

                    if(typeof response.error_fields !== 'undefined' && response.error_fields > 0)
                    {
                        $.each(JSON.parse(response.error_fields), (index, item) => {
                            $("input[name*='"+item+"'], select[name*='"+item+"'], textarea[name*='"+item+"']").closest('.form-line').addClass('error');
                            $('label.'+item).addClass('text-danger');
                        });    
                    }
                    
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