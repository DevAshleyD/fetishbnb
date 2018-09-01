// institute logo
function readURL(input, i_id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {$(i_id).attr('src', e.target.result);}
        reader.readAsDataURL(input.files[0]);
    }
};
$("#institute_logo").change(function(){readURL(this, '#i_institute_logo');});
$("#banner_image_1").change(function(){readURL(this, '#i_banner_image_1');});
$("#banner_image_2").change(function(){readURL(this, '#i_banner_image_2');});
$("#banner_image_3").change(function(){readURL(this, '#i_banner_image_3');});
$("#banner_image_4").change(function(){readURL(this, '#i_banner_image_4');});
$("#banner_image_5").change(function(){readURL(this, '#i_banner_image_5');});

$(function() {

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
