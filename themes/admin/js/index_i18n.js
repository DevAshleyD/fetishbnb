/*
 * Author: DK
 * Description: Common index.js
 **/

var table;
var data;

$(function () {

  	"use strict";

  	data = {
  		'csrf_token' : csrf_token
  	}
                
    //datatables
    table = $('#table').DataTable({ 
        "dom"           : 'lBfrtip',
        "responsive"    : true,
        "buttons"       : ['csv', 'excel', 'pdf'],
        "processing"    : true, //Feature control the processing indicator.
        "serverSide"    : true, //Feature control DataTables' server-side processing mode.
        "order"         : [], //Initial no order.
        "ajax"          : {
            "url"   : site_url+uri_seg_1+'/'+uri_seg_2+'/ajax_list',
            "type"  : "POST",
            "data"  : data,
        },                      // Load data for the table's content from an Ajax source
        "columnDefs": [{ 
            "targets": [ 0, -1 ], //first & last column
            "orderable": false, //set not orderable
        }],
    });
 
    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

    // Set datatable button style to materialistic
    $('.dt-buttons').addClass('btn-group');
    $('.dt-buttons a').removeClass('dt-button');
    $('.dt-buttons a').addClass('btn bg-'+admin_theme+' waves-effect');
 });

// Status Update
function statusUpdate(status, id) {
    if(status.checked)  status = 1;
    else                status = 0;

    var data    = {
        csrf_token      : csrf_token,
        status          : status,
        id              : id,
    };
    ajaxPost('status_update', '#courses_loader', data, function(response) {
        if(response.flag == 1)
            show_success(response.msg);
        else
            show_danger(response.msg);

        if(response.flag == 1) {
            setTimeout(function () {
                table.ajax.reload( null, false ); // user paging is not reset on reload
            }, 1000);    
        }
    });
}

// Featured Update
function featuredUpdate(featured, id) {
    if(featured.checked)  featured = 1;
    else                featured = 0;

    var data    = {
        csrf_token      : csrf_token,
        featured          : featured,
        id              : id,
    };
    ajaxPost('featured_update', '#courses_loader', data, function(response) {
        if(response.flag == 1)
            show_success(response.msg);
        else
            show_danger(response.msg);

        if(response.flag == 1) {
            setTimeout(function () {
                table.ajax.reload( null, false ); // user paging is not reset on reload
            }, 1000);    
        }
    });
}