/*
 * Author: DK
 * Description: Custom functions custom.js
 **/


(function () {
    // remove has-error on focus on input 
    $(document).on('focus', "input, select, textarea", function() {
      $('.form-line').removeClass('error');
      $('label').removeClass('text-danger');
    });
}());

function showNotification(colorName, text, title, placementFrom, placementAlign, animateEnter, animateExit) {
    var allowDismiss = true;

    $.notify({
      title: title,
      message: text
    },
    {
      type: colorName,
      allow_dismiss: allowDismiss,
      newest_on_top: true,
      timer: 1000,
      placement: {
        from: 'top',
        align: 'right'
      },
      animate: {
        enter: 'animated bounceInRight',
        exit: 'animated bounceOutRight'
      },
      template: '<div data-notify="container" class="bootstrap-notify-container alert alert-dismissible {0} ' + (allowDismiss ? "p-r-35" : "") + '" role="alert">' +
      '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
      '<span data-notify="icon"></span> ' +
      '<span data-notify="title">{1}</span> ' +
      '<span data-notify="message">{2}</span>' +
      '<div class="progress" data-notify="progressbar">' +
      '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
      '</div>' +
      '<a href="{3}" target="{4}" data-notify="url"></a>' +
      '</div>'
    });
}

/*Show warning popup*/
function show_warning(message) {
  title = '<i class="material-icons">warning</i> ';
  showNotification('alert-warning', message, title);
}

/*Show danger popup*/
function show_danger(message) {
  title = '<i class="material-icons">error</i> ';
  showNotification('alert-danger', message, title);
}

/*Show info popup*/
function show_info(message) {
  title = '<i class="material-icons">add_alert</i> ';
  showNotification('alert-info', message, title);
}

/*Show success popup*/
function show_success(message) {
  title = '<i class="material-icons">done_all</i> ';
  showNotification('alert-success', message, title);
}

/* String capitalize */
String.prototype.textCapitalize = function() {
  return this.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

/* ajaxPost function 
 * url 		: method name
 * data 	: data to post
 * loader 	: loader id
 * callback : data to return
*/
function ajaxPost(url, loader, data, callback, access) {
  $.ajax({ 
      type        : 'POST', 
      url         : site_url+uri_seg_1+'/'+uri_seg_2+'/'+url, 
      data        : data, 
      dataType    : 'JSON',
      cache       : false,
      beforeSend  : function() {
        if(loader != '')
          $(loader).html(`<div class="preloader pl-size-xs">
                              <div class="spinner-layer pl-`+admin_theme+`">
                                  <div class="circle-clipper left">
                                      <div class="circle"></div>
                                  </div>
                                  <div class="circle-clipper right">
                                      <div class="circle"></div>
                                  </div>
                              </div>
                          </div>`);
      },
      success     : function (response) { 
        if(/<p>/i.test(response)) {
          show_danger(response);
        } else if(!response) {
          show_warning(response);
        } else {
          callback(response);
        }
        
        if(loader != '')
         $(loader+' i').remove();
      },
      error       : function(response, textStatus, errorThrown){
        if(/<p>/i.test(response.responseText)) {
          show_info(response.responseText);
        }

        if(typeof access !== 'undefined')
          swal("{{alert_delete_cancel}}", "{{alert_data_safe}}", "error");
        
        if(loader != '')
          $(loader+' i').remove();
      },
  });
  
}

/* ajaxPostMultiPart function 
 * url    : method name
 * data   : data to post
 * loader   : loader id
 * callback : data to return
*/
function ajaxPostMultiPart(url, loader, data, callback) {
  $.ajax({ 
      type        : 'POST', 
      url         : site_url+uri_seg_1+'/'+uri_seg_2+'/'+url, 
      data        : data, 
      dataType    : 'JSON',
      cache       : false,
      contentType : false,
      processData : false,
      beforeSend  : function() {
        if(loader != '')
          $(loader).html(`<div class="preloader pl-size-xs">
                              <div class="spinner-layer pl-`+admin_theme+`">
                                  <div class="circle-clipper left">
                                      <div class="circle"></div>
                                  </div>
                                  <div class="circle-clipper right">
                                      <div class="circle"></div>
                                  </div>
                              </div>
                          </div>`);
      },
      success     : function (response) { 
        if(/<p>/i.test(response)) {
          show_danger(response);
        } else if(!response) {
          show_warning(response);
        } else {
          callback(response);
        }
        
        if(loader != '')
         $(loader+' i').remove();
      },
      error       : function(response, textStatus, errorThrown){
        if(/<p>/i.test(response.responseText)) {
          show_info(response.responseText);
        }
        
        if(loader != '')
          $(loader+' i').remove();
      },
  });
  
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

// Ajax Delete With Sweetalert
function ajaxDelete(id, text, cont) {
    if(text == '')
      text = "{{alert_this}} "+cont;
    else
      text = cont+" : "+text.textCapitalize();

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
                            
            ajaxPost('delete', '#courses_loader', data, function(response) {
                if(response.flag == 1)
                    swal(text, "{{alert_delete_success_1}}", "success");
                else
                    swal("{{alert_delete_fail_1}}", cont+" : "+response.msg, "warning");

                if(response.flag == 1) {
                    setTimeout(function () {
                        if(uri_seg_3)
                          window.location.href = site_url+uri_seg_1+'/'+uri_seg_2;
                        else
                          table.ajax.reload( null, false ); // user paging is not reset on reload
                    }, 1000);    
                }
            }, 1);
        } else {
            swal("{{alert_delete_cancel}}", "{{alert_data_safe}}", "error");
        }
    });
}

// read notification
function read_notification(noti_type, noti_url) {
  var data    = {
      csrf_token      : csrf_token,
      n_type          : noti_type, 
  }; 
                  
  $.ajax({ 
      type        : 'POST', 
      url         : site_url+'notifications/delete_notification', 
      data        : data, 
      dataType    : 'JSON',
      cache       : false,
      success     : function (response) {
        window.location.href = noti_url;
      },
      error       : function(response, textStatus, errorThrown){
        if(/<p>/i.test(response.responseText)) {
          show_warning(response.responseText);
        }
      },
  });
}