(function () {
    // remove has-error on focus on input 
    $(document).on('focus', "input, select, textarea", function() {
      $('.form-line').removeClass('error');
      $('label').removeClass('text-danger');
    });
}());

/* String capitalize */
String.prototype.textCapitalize = function() {
  return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

/*getRandomColor random color generator*/
function getRandomColor() {
    var items = ['#f44336','#f39c12','#0073b7','#00c0ef','#00a65a','#3c8dbc','#b71c1c','#d81b60','#ad1457',
                '#9c27b0','#4a148c','#d500f9','#651fff','#304ffe','#26c6da','#00695c','#2e7d32','#00c853',
                '#ff6f00','#e65100','#ffa726','#ff5722','#bf360c','#bf360c',];

    return items[Math.floor(Math.random()*items.length)];
}

/*time24To12 convert 24 hours to 12 hours*/
function time24To12 (time) {
  // Check correct time format and split into components
  time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

  if (time.length > 1) { // If time format correct
    time = time.slice (1);  // Remove full string match value
    time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
    time[0] = +time[0] % 12 || 12; // Adjust hours
  }
  return time.join (''); // return adjusted time or original string
}


/* ajaxPostCustom function 
 * url 		: method name
 * data 	: data to post
 * loader 	: loader id
 * callback : data to return
*/
function ajaxPostCustom(url, loader, data, callback) {
  $.ajax({ 
      type        : 'POST', 
      url         : site_url+uri_seg_1+'/'+url, 
      data        : data, 
      dataType    : 'JSON',
      cache       : false,
      beforeSend  : function() {
        if(loader != '')
          $(loader).html('<i class="fa fa-circle-o-notch fa-spin loader"></i>');
      },
      success     : function (response) { 
        if(/<p>/i.test(response)) {
          swal("{{alert_oops}}", response, "error");
        } else if(!response) {
          swal("{{alert_fatal}}", response, "warning");
        } else {
          callback(response);
        }
        
        if(loader != '')
         $(loader+' i').remove();
      },
      error       : function(response, textStatus, errorThrown){
        if(/<p>/i.test(response.responseText)) {
          swal("{{alert_fatal}}", response.responseText, "warning");
        }
        
        if(loader != '')
          $(loader+' i').remove();
      },
  });
  
}

/* ajaxPostMultiPartCustom function 
 * url    : method name
 * data   : data to post
 * loader   : loader id
 * callback : data to return
*/
function ajaxPostMultiPartCustom(url, loader, data, callback) {
  $.ajax({ 
      type        : 'POST', 
      url         : site_url+uri_seg_1+'/'+url, 
      data        : data, 
      dataType    : 'JSON',
      cache       : false,
      contentType : false,
      processData : false,
      beforeSend  : function() {
        if(loader != '')
          $(loader).html('<i class="fa fa-circle-o-notch fa-spin loader"></i>');
      },
      success     : function (response) { 
        if(/<p>/i.test(response)) {
          swal("{{alert_oops}}", response, "error");
        } else if(!response) {
          swal("{{alert_fatal}}", response, "warning");
        } else {
          callback(response);
        }
        
        if(loader != '')
         $(loader+' i').remove();
      },
      error       : function(response, textStatus, errorThrown){
        if(/<p>/i.test(response.responseText)) {
          swal("{{alert_fatal}}", response.responseText, "warning");
        }
        
        if(loader != '')
          $(loader+' i').remove();
      },
  });
  
}


// Ajax Delete With Sweetalert
function ajaxDelete(id, text) {
    text    = text.textCapitalize();

    swal({
        title               : "{{alert_delete_question_1}}",
        text                : text,
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
                csrf_token      : csrf_token,
                id              : id, 
            };
                            
            ajaxPostCustom('delete', '#blogs_loader', data, function(response) {
                if(response.flag == 1)
                    swal(text, "{{alert_delete_success_1}}", "success");
                else
                    swal("{{alert_delete_fail_1}}", response.msg, "warning");

                if(response.flag == 1) {
                  setTimeout(function () {
                      location.reload(true);
                  }, 500);    
                }
            });
        } else {
            swal("{{alert_delete_cancel}}", "{{alert_data_safe}}", "error");
        }
    });
}


// Booking Cancellation With Sweetalert
function bookingCancel(id, text, cont) {
    text = cont+" : "+text.textCapitalize();

    swal({
        title               : "{{alert_cancel_question_1}}",
        text                : text,
        type                : "error",
        showCancelButton    : true,
        confirmButtonColor  : "#DD6B55",
        confirmButtonText   : "{{action_cancel_booking}}",
        cancelButtonText    : "{{action_no}}",
        closeOnConfirm      : false,
        closeOnCancel       : false,
        showLoaderOnConfirm : true,
        html                : true,
        allowEscapeKey      : true,
        timer               : 10000,
    }, function (isConfirm) {
        if (isConfirm) {
            var data    = {
                csrf_token      : csrf_token,
                id              : id, 
            };
                            
            ajaxPostCustom('cancel_booking', '#blogs_loader', data, function(response) {
                if(response.flag == 1)
                    swal(text, "{{alert_cancel_success_1}}", "success");
                else
                    swal("{{alert_cancel_fail_1}}", response.msg, "warning");

                if(response.flag == 1) {
                  setTimeout(function () {
                      location.reload(true);
                  }, 3000);    
                }
            });
        } else {
            swal("{{alert_cancel_cancel}}", "{{alert_booking_safe}}", "error");
        }
    });
}