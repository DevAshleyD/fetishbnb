/*
 * Author: DK
 * Description: Menus index.js
 **/

function areyousure() {
    return confirm(are_you_sure+'?');
}

$(document).ready(function(){
  $('ol.nestable').nestedSortable({
      forcePlaceholderSize  : true,
      handle                : 'div',
      helper                : 'clone',
      items                 : 'li',
      opacity               : .6,
      placeholder           : 'placeholder',
      revert                : 250,
      tabSize               : 25,
      tolerance             : 'pointer',
      toleranceElement      : '> div',
      maxLevels             : 5,
      isTree                : true,
      expandOnHover         : 700,
      startCollapsed        : false,
      change                : function(){}
  });
});

$(document).on('click','.save-menu',function(){
     var saveObj = new Array();
     var ol = $('ol.nestable');
     var ms = toArray(); 

     $('#submit_loader').html(`<div class="preloader pl-size-xs">
                              <div class="spinner-layer pl-`+admin_theme+`">
                                  <div class="circle-clipper left">
                                      <div class="circle"></div>
                                  </div>
                                  <div class="circle-clipper right">
                                      <div class="circle"></div>
                                  </div>
                              </div>
                          </div>`);

     $.each(ms, function(i, obj) {
        ind = (i-1);
        if(obj.item_id!=null) {
            parent_id    = (obj.parent_id==null)?0:obj.parent_id;
            saveObj[ind] = {
              id        : obj.item_id,
              parent_id : parent_id,
              type      : ol.find('li').eq(ind).find('input[name="type"]').val(),
              label     : ol.find('li').eq(ind).find('input[name="label"]').val(),
              value     : ol.find('li').eq(ind).find('input[name="value"]').val()
            }; 
        }
     });
       
    var menu_id       = $('#menu-id').val();
    var menu_title    = $('#menu-title').val();
    
    $.ajax({
        url   : site_url+uri_seg_1+'/'+uri_seg_2+'/ajax_save',
        type  : 'POST',
        data  : {
          id         : menu_id,
          title      : menu_title,
          content    : saveObj,
          csrf_token : csrf_token,
        },
        success:function(result){
            $('#submit_loader').remove();
            result = JSON.parse(result);
            if(result.status=='error'){
                show_error(result.result);    
            } else{
                show_success(result.result);
            }
        }
    });
})

$(document).on('click','.addtomenu',function(){
     var box      = $(this).closest('.card');
     var box_body = box.find('.body');
     $.each(box_body.find('input[type="checkbox"]'), function( key, value ) {
          if($(this).prop('checked') == true) {
              TMS++;
              html = get_html($(this).attr('data-type'),$(this).attr('data-title'), $(this).val(), TMS);
              $('ol.nestable').append(html);
              $(this).prop('checked', false);
          }
     });
});

function get_html(type,label,value,id)
{
    html = '<li id="menuItem_'+id+'">'+
                 '<div class="panel panel-default">'+
                    '<div class="panel-heading" role="tab" id="headingOne_'+id+'">'+
                          '<h4 class="panel-title">'+
                            '<a role="button" data-toggle="collapse" href="#collapseOne_'+id+'" aria-expanded="true" aria-controls="collapseOne_'+id+'">'+label+'</a>'+
                          '</h4>'+
                    '</div>'+
                    '<div id="collapseOne_'+id+'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_'+id+'">'+
                      '<div class="panel-body">'+
                        '<input type="hidden" name="type" value="'+type+'"/>'+
                        '<input type="hidden" name="value" value="'+value+'"/>'+
                        '<div class="form-group">'+
                            '<div class="form-line">'+
                                '<input type="text" class="form-control" name="label" value="'+label+'" placeholder="Label"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<a class="btn btn-default pull-right waves-effect remfrmenu"><i class="fa fa-minus"></i></a>'+
                        '</div>'+
                      '</div>'+
                    '</div>'+
                '</div>'+
        '</li>';
    
    return html;}
    
$(document).on('click','.remfrmenu',function(){
    $(this).closest('li').remove();
});

$(document).on('click','.addlinktomenu',function(){
     TMS++;
     var box = $(this).closest('.card').find('.body');
     
     label = box.find('.link-text').val();
     url = box.find('.url').val();
     type = box.find('.info').attr('data-type');
     
     html =  get_html_for_link(type,label,url,TMS);
     $('ol.nestable').append(html);
     
     box.find('.url').val('#');
     box.find('.link-text').val('');
});


function get_html_for_link(type,label,value,id)
{
    html = '<li id="menuItem_'+id+'">'+
                 '<div class="panel panel-default">'+
                    '<div class="panel-heading" role="tab" id="headingOne_'+id+'">'+
                        '<h4 class="panel-title">'+
                          '<a role="button" data-toggle="collapse" href="#collapseOne_'+id+'" aria-expanded="true" aria-controls="collapseOne_'+id+'">'+label+'</a>'+
                        '</h4>'+
                    '</div>'+
                    '<div id="collapseOne_'+id+'" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne_'+id+'">'+
                    '<div class="panel-body">'+
                        '<input type="hidden" name="type" value="'+type+'"/>'+
                        
                        '<label>URL</label>'+
                        '<div class="form-group">'+
                            '<div class="form-line">'+
                                '<input type="text" class="form-control" name="value" value="'+value+'" placeholder="Link"/>'+
                            '</div>'+
                        '</div>'+
                        '<label>Menu Label</label>'+
                        '<div class="form-group">'+
                            '<div class="form-line">'+
                                '<input type="text" class="form-control" name="label" value="'+label+'" placeholder="Label"/>'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group">'+
                            '<a class="btn btn-default pull-right waves-effect remfrmenu"><i class="fa fa-minus"></i></a>'+
                        '</div>'+
                    '</div>'+
                  '</div>'+
                '</div>'+
        '</li>';
    
    return html;
}

function toArray(){
    return $('ol.nestable').nestedSortable('toArray', {startDepthCount: 0});
}

function check_array()
{
    array = toArray();
    $.each(array, function( index, value ) {
      console.log(array[index]);
    });
}

$(document).on('click','.edit-menu',function(){
    var id = $('select[name="menu_edit"] option:selected').val();
    urlgo = site_url+uri_seg_1+'/'+uri_seg_2+'/index/'+id;
    window.location.href=urlgo;
});