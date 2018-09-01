/*
 * Author: DK
 * Description: Custom_fields edit.js
 **/

$(function () {

    "use strict";
    
    $('#input_type').on('change', function() {
        var input_type = $(this).val();

        if(input_type == 'input') {
            $('#is_numeric').closest('.icheckbox_minimal-blue').removeClass('disabled');
            $('#is_numeric').attr("disabled", false);

            $('#show_editor').attr("checked", false);
            $('#show_editor').closest('.icheckbox_minimal-blue').removeClass('checked');
            $('#show_editor').closest('.icheckbox_minimal-blue').addClass('disabled');
            $('#show_editor').attr("disabled", true);

            $('.options-toggle').hide();
        }
        if(input_type == 'textarea') {
            $('#is_numeric').attr("checked", false);
            $('#is_numeric').closest('.icheckbox_minimal-blue').removeClass('checked');
            $('#is_numeric').closest('.icheckbox_minimal-blue').addClass('disabled');
            $('#is_numeric').attr("disabled", true);

            $('#show_editor').closest('.icheckbox_minimal-blue').removeClass('disabled');
            $('#show_editor').attr("disabled", false);

            $('.options-toggle').hide();
        }
        if(input_type == 'radio' || input_type == 'checkbox' || input_type == 'dropdown') {
            $('#is_numeric').attr("checked", false);
            $('#is_numeric').closest('.icheckbox_minimal-blue').removeClass('checked');
            $('#is_numeric').closest('.icheckbox_minimal-blue').addClass('disabled');
            $('#is_numeric').attr("disabled", true);

            $('#show_editor').attr("checked", false);
            $('#show_editor').closest('.icheckbox_minimal-blue').removeClass('checked');
            $('#show_editor').closest('.icheckbox_minimal-blue').addClass('disabled');
            $('#show_editor').attr("disabled", true);

            $('.options-toggle').show();
        }
        if(input_type == 'file' || input_type == 'email' ) {
            $('#is_numeric').attr("checked", false);
            $('#is_numeric').closest('.icheckbox_minimal-blue').removeClass('checked');
            $('#is_numeric').closest('.icheckbox_minimal-blue').addClass('disabled');
            $('#is_numeric').attr("disabled", true);

            $('#show_editor').attr("checked", false);
            $('#show_editor').closest('.icheckbox_minimal-blue').removeClass('checked');
            $('#show_editor').closest('.icheckbox_minimal-blue').addClass('disabled');
            $('#show_editor').attr("disabled", true);

            $('.options-toggle').hide();
        }
        
    }).trigger('change');
    
    var wrapper         = $(".options"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    var x               = options_count; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        x++; //text box increment
        var clone = $(".options>div:lt(1)").clone();
        clone.find('input').val('');
        $(wrapper).append(clone); //add input box
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); 
        if(x > 1) {
            $(this).parent('div').remove(); 
            x--;
        }
    })

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
                        window.location.href = site_url+uri_seg_1+'/'+uri_seg_2;
                    }, 1000);
                }
            });
        }
        return false;           
    });

});