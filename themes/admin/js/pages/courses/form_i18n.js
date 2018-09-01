/*
 * Author: DK
 * Description: Classes edit.js
 **/

// Multiple images preview in browser
var imagesPreview = function(input, placeToInsertImagePreview) {

    if (input.files) {
        var filesAmount = input.files.length;
        $('.gallery').html('');
        for (i = 0; i < filesAmount; i++) {
            var reader = new FileReader();

            reader.onload = function(event) {
                $($.parseHTML('<img class="col-sm-2 img-responsive thumbnail">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
            }

            reader.readAsDataURL(input.files[i]);
        }
    }

};


function finishAjax(id, response) {
    $('.loader').remove();
    $('#'+id).append(unescape(response));
}


$(function () {

    "use strict";

    // N level categories
    $(document).on('change', '.parent', function() { // different syntax for livequery
        $(this).closest('div.parent').nextAll('select.parent').remove();
        $(this).nextAll('select.parent').remove();
        $(this).nextAll('label').remove();
        $(this).nextAll('p').remove();
 
        $('#show_sub_categories').append(`<div class="loader preloader pl-size-xs">
                              <div class="spinner-layer pl-`+admin_theme+`">
                                  <div class="circle-clipper left">
                                      <div class="circle"></div>
                                  </div>
                                  <div class="circle-clipper right">
                                      <div class="circle"></div>
                                  </div>
                              </div>
                          </div>`);
 
        $.post(site_url+uri_seg_1+'/'+uri_seg_2+'/get_course_categories_levels', {
            csrf_token : csrf_token,
            category_id: $(this).val(),
        }, function(response){
            if(response)
                setTimeout("finishAjax('show_sub_categories', '"+escape(response)+"')", 400);
            else
                $('.loader').remove();
        });
 
        return false;
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
                        window.location.href = site_url+uri_seg_1+'/'+uri_seg_2;
                    }, 1000);
                }
            });
        }
        return false;           
    });

    $('#images').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });

    //TinyMCE
    tinymce.init({
        selector: "textarea.tinymce",
        theme: "modern",
        height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools'
        ],
        toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons',
        image_advtab: true,
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
    tinymce.suffix = ".min";
    tinyMCE.baseURL = base_url+'/themes/admin/plugins/tinymce';

 });