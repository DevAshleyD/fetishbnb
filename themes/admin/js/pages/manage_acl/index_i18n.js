/*
 * Author: DK
 * Description: Pages form.js
 **/

$(function () {
    
    "use strict";

    $('#groups').on('change', function() {
        var group_select = $(this).val();

        setTimeout(function() {
            window.location = site_url+uri_seg_1+'/'+uri_seg_2+'/index/'+group_select;
        }, 500);
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

                    $.each(JSON.parse(response.error_fields), (index, item) => {
                        $("input[name*='"+item+"'], select[name*='"+item+"'], textarea[name*='"+item+"']").closest('.form-line').addClass('error');
                        $('label.'+item).addClass('text-danger');
                    });

                    post_flag = 1;
                    $('#submit_loader').remove();
                } else {
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                }
            });
        }
        return false;           
    });
});