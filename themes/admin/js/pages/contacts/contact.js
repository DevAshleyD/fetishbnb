$(function () {

    "use strict";


    /**
     * Set message as read when opened
     */
    $(document).on('shown.bs.modal', ".modal", function(e) {
        var id  = $(this).data('id');
        var ref = $(this).data('read');
        
        if (!ref) {
            var data    = {
                csrf_token      : csrf_token,
            }; 
            ajaxPost('read/'+id, '#courses_loader', data, function(response) {
                
            });
        }
    });

    $(document).on('hidden.bs.modal', ".modal", function(e) {
        var ref = $(this).data('read');
        if(!ref) table.ajax.reload( null, false ); // user paging is not reset on reload
    });

});
