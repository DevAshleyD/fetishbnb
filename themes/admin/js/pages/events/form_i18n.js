/*
 * Author: DK
 * Description: Batches create.js
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

$(function () {
    "use strict";

    $('#images').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });

    if(tutors)
        $("#tutors").val(tutors).trigger("change");

    $('.help-recursive').on('click', function() {
        swal({
            title: "{{events_recurring_help}}",
            text: `{{events_recurring_help_a}}`,
            html: true
        });
    });
    
    
    $('#start_end_date').daterangepicker();

    $('#recurring').change(function() {
        if($(this).is(":checked")) {
            $('#weekdays_select').show();
            $('#recurring_type_select').show();
        } else {
            $('#weekdays_select').hide();
            $('#recurring_type_select').hide();
        }
    }).trigger('change');

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

    // Ajax form submit with validation errors
    var post_flag = 1;
    $('form#form-create').on('submit', function(e) {
        e.preventDefault();
        
        var start_time  = $('#start_time_1').val()+':'+$('#start_time_2').val()+' '+$('#start_time_3').val();
        var end_time    = $('#end_time_1').val()+':'+$('#end_time_2').val()+' '+$('#end_time_3').val();

        $('#start_time').val(start_time);
        $('#end_time').val(end_time);

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