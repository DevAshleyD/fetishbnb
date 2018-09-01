/*
 * Author: DK
 * Description: Courses create.js
 **/

// global batches_id variable
var batches_id      = '';
var batches_ori     = [];
var booked_seats    = 0;
var available_seats = 0;

/*getCourses for fetching courses*/
function getCourses(course_category) {
    var data    = {
        csrf_token      : csrf_token,
        category_id     : course_category, 
    }; 
                    
    ajaxPost('get_courses', '#courses_loader', data, function(response) {
        $('#courses').html('');
        $.each(response, (index, item) => {
            $('#courses').append($('<option>', {
                    value: index,
                    text : item 
                }
            ));
        });
        $("#courses").selectpicker("refresh");
        $('#courses_loader').empty();
    });
}

/*getNetFees for calculation and showing net fees of any batch*/
function getNetFees() {
    var batches_id    = $('#batches_id').val();
    var count_members = $("input[name='fullname[]']").length;
    
    if(batches_id == 0 || batches_id == null)
        return false;

    var data    = {
        csrf_token      : csrf_token,
        batches_id      : batches_id, 
        count_members   : count_members,
    }; 
                    
    ajaxPost('get_net_fees', '#net_fees_loader', data, function(response) {
        $('#fees').val(response.fees);
        $('#net_fees').val(response.net_fees);
        $('#fees_label .text-info').html(response.single_fees+' X '+response.count_members);
        $('#net_fees_label .text-info').html(response.title+': '+response.rate+response.rate_type);
        $('#net_fees_loader').empty();
    });
}

/*getBookedSeats for fetching seats availability on particular date*/
function getBookedSeats(batches_id, booking_date) {
    var data    = {
        csrf_token      : csrf_token,
        batches_id      : batches_id, 
        booking_date    : booking_date,
    }; 
                    
    ajaxPost('get_booked_seats', '', data, function(response) {
        booked_seats = response.booked_seats;
    });   
}

/*selectEvent for selecting any event from calendar*/
function selectEvent(calEvent, data) {
    $.each(data, (index, item) => {
        if(calEvent.id == item.id) {
            // reset & enable members on selection of another event
            $('.members .row').not('.row:first').remove();
            $(".members-toggle").find("input,button,textarea,select").prop('disabled', false);

            batches_id = calEvent.id;
            $('#batches_id').val(batches_id);
            $('#batch_label').show();
            $('#batch_loader').html(`<div class="preloader pl-size-xs">
                              <div class="spinner-layer pl-`+admin_theme+`">
                                  <div class="circle-clipper left">
                                      <div class="circle"></div>
                                  </div>
                                  <div class="circle-clipper right">
                                      <div class="circle"></div>
                                  </div>
                              </div>
                          </div>`);
            $('#batch_label h4').html(item.title.textCapitalize());

            // in case of recurring batch
            if(item.recurring == 1)
                start_date  = new Date(calEvent.start.toDate());
            else
                start_date  = new Date(item.start_date);

            start_d         = start_date.getDate();
            start_m         = start_date.getMonth()+1; // only increase month by one in case of eventClick
            start_y         = start_date.getFullYear();

            // in case of recurring batch
            if(item.recurring == 1)
                end_date    = new Date(calEvent.start.toDate());
            else
                end_date    = new Date(item.end_date);

            end_d           = end_date.getDate();
            end_m           = end_date.getMonth()+1; // only increase month by one in case of eventClick
            end_y           = end_date.getFullYear();

            start_time      = item.start_time.split(':');
            start_time      = start_time[0]+':'+start_time[1];

            end_time        = item.end_time.split(':');
            end_time        = end_time[0]+':'+end_time[1];

            if(item.recurring == '1')
                duration        = '<tr>'+'<td>{{b_bookings_booking_date}}      </td>'+'<td>'+$.format.date(start_date, "d MMM, yy")+'</td>';
            else
                duration        = '<tr>'+'<td>{{b_bookings_duration}}      </td>'+'<td>'+$.format.date(start_date, "d MMM, yy")+' - '+$.format.date(end_date, "d MMM, yy")+'</td>';

            if(item.recurring == '1')
                total_days  = '<tr>'+'<td>{{c_l_batch_days}}     </td>'+'<td>'+JSON.parse(item.weekdays).length+'</td>';
            else
                total_days  = '';

            timing          = '<tr>'+'<td>{{b_bookings_timing}}        </td>'+'<td>'+time24To12(start_time)+' - '+time24To12(end_time)+'</td>';
            recurring       = '<tr>'+'<td>{{c_l_repetitive_batch}}     </td>'+'<td>'+(item.recurring == '1' ? 'Yes' : 'No')+'</td>';
            
            booking_date    = $.format.date(start_date, "yyyy-MM-dd");
            
            getBookedSeats(batches_id ,booking_date);

            setTimeout(function() {
                available_seats = item.capacity - booked_seats;
                if((available_seats) == 0) {
                    availability= '<tr class="text-danger">'+'<td><strong>{{b_bookings_availability}}  </strong></td>'+'<td>'+(available_seats)+'</td>';
                    booked      = '<tr class="text-danger">'+'<td><strong>{{b_bookings_booked}}        </strong></td>'+'<td>'+(booked_seats)+'</td>';
                } else {
                    availability= '<tr class="text-success">'+'<td><strong>{{b_bookings_availability}}  </strong></td>'+'<td>'+(available_seats)+'</td>';
                    booked      = '<tr class="text-success">'+'<td><strong>{{b_bookings_booked}}        </strong></td>'+'<td>'+(booked_seats)+'</td>';
                }
                    
                $('#batch_label table').html(duration+timing+recurring+total_days+availability+booked);
                $('#batch_loader').empty();

                // if available seat is 0 then reset & disable members
                if(available_seats == 0) {
                    $('.members .row').not('.row:first').remove();
                    $(".members-toggle").find("input,button,textarea,select").prop('disabled', true);
                }
            }, 2000);
                                 
            $('#booking_date').val(booking_date);
            $('#start_time').val(item.start_time);

            getNetFees();
        }
    });
}

/*selectedEvent for selected event in case of edit*/
function selectedEvent(selEvent) {
    batches_id = selEvent.batches_id;
    $('#batches_id').val(batches_id);
    $('#batch_label').show();
    //$('#batch_loader').html('<i class="fa fa-circle-o-notch fa-spin loader"></i>');
    $('#batch_label h4').html(selEvent.batch_title.textCapitalize());
    $(".members-toggle").find("input,button,textarea,select").prop('disabled', true);
    $("#customers_label, #category_label, #courses_label").find("input,button,textarea,select").prop('disabled', true);
    $(".members-toggle").find("input,button,textarea,select").prop('disabled', true);
    $(".members-toggle").find("input,button,textarea,select").prop('disabled', true);

    // in case of recurring batch
    if(selEvent.batch_recurring == 1)
        start_date  = new Date(selEvent.booking_date);
    else
        start_date  = new Date(selEvent.batch_start_date);

    start_d         = start_date.getDate();
    start_m         = start_date.getMonth()+1; // only increase month by one in case of eventClick
    start_y         = start_date.getFullYear();

    // in case of recurring event
    if(selEvent.event_recurring == 1)
        end_date    = new Date(selEvent.booking_date);
    else
        end_date    = new Date(selEvent.batch_end_date);

    end_d           = end_date.getDate();
    end_m           = end_date.getMonth()+1; // only increase month by one in case of eventClick
    end_y           = end_date.getFullYear();

    start_time      = selEvent.batch_start_time.split(':');
    start_time      = start_time[0]+':'+start_time[1];

    end_time        = selEvent.batch_end_time.split(':');
    end_time        = end_time[0]+':'+end_time[1];

    if(selEvent.batch_recurring == '1')
        duration        = '<tr>'+'<td>{{b_bookings_booking_date}}      </td>'+'<td>'+$.format.date(start_date, "d MMM, yy")+'</td>';
    else
        duration        = '<tr>'+'<td>{{b_bookings_duration}}      </td>'+'<td>'+$.format.date(start_date, "d MMM, yy")+' - '+$.format.date(end_date, "d MMM, yy")+'</td>';

    if(selEvent.batch_recurring == '1')
        total_days  = '<tr>'+'<td>{{c_l_batch_days}}     </td>'+'<td>'+selEvent.batch_weekdays+'</td>';
    else
        total_days  = '';

    timing          = '<tr>'+'<td>{{b_bookings_timing}}        </td>'+'<td>'+time24To12(start_time)+' - '+time24To12(end_time)+'</td>';
    recurring       = '<tr>'+'<td>{{c_l_repetitive_batch}}     </td>'+'<td>'+(selEvent.batch_recurring == '1' ? 'Yes' : 'No')+'</td>';
    
    booking_date    = $.format.date(start_date, "yyyy-MM-dd");
    
    $('#batch_label table').html(duration+timing+recurring+total_days);
    $('#booking_date').val(booking_date);
    $('#start_time').val(selEvent.batch_start_time);

    getNetFees();
}

var batches         = [];

// for recurring batches
function pad(num) {
  return String("0" + num).slice(-2);
}
function formatDate(d) {
  return d.getFullYear() + "-" + pad(d.getMonth() + 1) + "-" + pad(d.getDate())
}
var batches_e         = [];
//emulate server
var getBatches = function( start, end ){
    return batches_e;
}

/*fillCalendar for rendering calendar and filling data*/
function fillCalendar(data) {
    
    var backgroundColor = '';
    
    $.each(data, (index, item) => {
        backgroundColor = getRandomColor();

        start_time  = item.start_time.split(':');
        start_hor   = start_time[0];
        start_min   = start_time[1];

        end_time    = item.end_time.split(':');
        end_hor     = end_time[0];
        end_min     = end_time[1];

        start_date  = new Date(item.start_date);
        start_d     = start_date.getDate();
        start_m     = start_date.getMonth()+1;
        start_y     = start_date.getFullYear();
        
        end_date    = new Date(item.end_date);
        end_d       = end_date.getDate();
        end_m       = end_date.getMonth()+1;
        end_y       = end_date.getFullYear();

         /*In case of recurring batch*/
        if(item.recurring == 1) { 

            var aDay = 24 * 60 * 60 * 1000,
            range    = { // your range
                start   : start_date, // remember month is 0 based
                end     : end_date
            },
            ranges = [];

            for (var i = range.start.getTime(), end = range.end.getTime(); i <= end;) {
                var first   = new Date(i);
                var last    = new Date(first.getFullYear(), first.getMonth() + 1, 0); // last day of the month
                if(item.recurring_type == 'every_week') {
                    // in case of repetition every week
                    ranges.push({
                        start: moment(formatDate(first),'YYYY-MM-DD'),
                        end: moment(formatDate(last),'YYYY-MM-DD').endOf('month'),
                    })
                } else if(item.recurring_type == 'first_week') {
                    // 1 to 7
                    var cus_date_start = new Date(first.getFullYear(), first.getMonth(), 1);
                    var cus_date_end = new Date(first.getFullYear(), first.getMonth(), 7);
                    if( cus_date_start.getDate() >= first.getDate() && cus_date_end.getDate() <= last.getDate() ) {
                        ranges.push({
                            start: moment(formatDate(cus_date_start),'YYYY-MM-DD'),
                            end: moment(formatDate(cus_date_end),'YYYY-MM-DD'),
                        })
                    }
                } else if(item.recurring_type == 'second_week') {
                    // 8 to 14
                    var cus_date_start = new Date(first.getFullYear(), first.getMonth(), 8);
                    var cus_date_end   = new Date(first.getFullYear(), first.getMonth(), 14);
                    if( cus_date_start.getDate() >= first.getDate() && cus_date_end.getDate() <= last.getDate() ) {
                        ranges.push({
                            start: moment(formatDate(cus_date_start),'YYYY-MM-DD'),
                            end: moment(formatDate(cus_date_end),'YYYY-MM-DD'),
                        })
                    }
                } else if(item.recurring_type == 'third_week') {
                    // 15 to 21
                    var cus_date_start = new Date(first.getFullYear(), first.getMonth(), 15);
                    var cus_date_end   = new Date(first.getFullYear(), first.getMonth(), 21);
                    if( cus_date_start.getDate() >= first.getDate() && cus_date_end.getDate() <= last.getDate() ) {
                        ranges.push({
                            start: moment(formatDate(cus_date_start),'YYYY-MM-DD'),
                            end: moment(formatDate(cus_date_end),'YYYY-MM-DD'),
                        })
                    }
                } else {
                    // 22 to end
                    var cus_date_start = new Date(first.getFullYear(), first.getMonth(), 22);
                    
                    ranges.push({
                        start: moment(formatDate(cus_date_start),'YYYY-MM-DD'),
                        end: moment(formatDate(last),'YYYY-MM-DD').endOf('month'),
                    })
                }

                i = last.getTime() + aDay;
            }
            batches_e.push({
                id              : item.id,
                title           : item.title.textCapitalize(),
                start           : item.start_time,
                end             : item.end_time,
                dow             : JSON.parse(item.weekdays),
                ranges          : ranges,
                backgroundColor : backgroundColor,
                borderColor     : backgroundColor
            });        
        } else {
            start_date  = new Date(item.start_date);
            start_d     = start_date.getDate();
            start_m     = start_date.getMonth(); // dont increase month by 1 in case of data from server
            start_y     = start_date.getFullYear();
            
            end_date    = new Date(item.end_date);
            end_d       = end_date.getDate();
            end_m       = end_date.getMonth(); // dont increase month by 1 in case of data from server
            end_y       = end_date.getFullYear();

            batches_e.push({
                id              : item.id,
                title           : item.title.textCapitalize(),
                start           : new Date(start_y, start_m, start_d, start_hor, start_min),
                end             : new Date(start_y, start_m, start_d, end_hor, end_min),
                backgroundColor : backgroundColor,
                borderColor     : backgroundColor
            });        
        }

        // batches.push({
        //     id              : item.id,
        //     title           : item.title.textCapitalize(),
        //     start           : new Date(start_y, start_m, start_d, start_hor, start_min),
        //     end             : new Date(start_y, start_m, start_d, end_hor, end_min),
        //     backgroundColor : backgroundColor,
        //     borderColor     : backgroundColor
        // });
        
    });

    var cal_default_date_set = '';
    if(batches_e.length === 0) {
        // if there's no batches
        cal_default_date_set = new Date();
    } else {
        // set the very first batch date
        // skip recurring event date
        $.each(batches_e, (index, item) => {
            if(typeof(item.dow) === "undefined") {
                cal_default_date_set = item.start;
                return false;
            } 
        });

        if(!cal_default_date_set) {
            cal_default_date_set = new Date();
        }
    }

    $('#calendar').fullCalendar({
        themeSystem: 'bootstrap3',
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,listMonth'
        },
        defaultDate: cal_default_date_set,
        navLinks: true, // can click day/week names to navigate views
        eventLimit: true, // allow "more" link when too many events
        selectable: true,
        selectHelper: true,
        height: 600,
        editable: false,
        fixedWeekCount: false,
        titleRangeSeparator: ' \u2014 ',
        viewRender: function(currentView) {
            // disable older and blank months where events are concerned
            var minDate = moment(),
            maxDate     = moment(batches_ori[(batches_ori.length-1)].end_date);
            // Past
            if (minDate >= currentView.start && minDate <= currentView.end) {
                $(".fc-prev-button").prop('disabled', true); 
                $(".fc-prev-button").addClass('fc-state-disabled'); 
            }
            else {
                $(".fc-prev-button").removeClass('fc-state-disabled'); 
                $(".fc-prev-button").prop('disabled', false); 
            }
        },
        eventClick: function(calEvent) {
            var todayDate = moment(),
            eventDate     = calEvent.start;
            
            if (eventDate >= todayDate) {
                selectEvent(calEvent, data);    

                // scroll up after selection
                $('html, body').animate({
                    scrollTop: $('#courses_label').offset().top - 20
                }, 'slow');

            } else {  // disable older and blank months where events are concerned
                swal({
                    title: "{{b_bookings_booking_older_date}}",
                    timer: 2000,
                    showConfirmButton: true
                });
            }
        },
        eventRender: function(event, element, view){
            if(typeof event.ranges !== 'undefined') {
                return (event.ranges.filter(function(range){
                    return (event.start.isBefore(range.end) &&
                            event.end.isAfter(range.start));
                }).length)>0;    
            }
        },
        events: function( start, end, timezone, callback ){
            var events = getBatches(start,end); //this should be a JSON request
            callback(events);
        },
        events: batches_e,
    });
}

/*callback function for n-level dynamic dropdown*/
function finishAjax(id, response) {
    $('.loader').remove();
    $('#'+id).append(unescape(response));
}

var flag = 0;
$(function () {
  	"use strict";

    // default hidden fields/elements
    $('#batch_label').hide();
    $('#batch_calendar').hide();

    /*for n-level dynamic dropdown*/    
    $(document).on('change', 'select.parent', function() {
        $(this).closest('div.parent').nextAll('select.parent').remove();
        $(this).nextAll('select.parent').remove();
        $(this).nextAll('label').remove();
        $(this).nextAll('p').remove();
        
        // reset fields on category change
        $('#batch_label').hide();
        $('#batch_calendar').hide();
        $('#courses').html('');
        $('#fees').val('');
        $('#net_fees').val('');
        $('#net_fees_label .text-info').html('');
        batches_ori = [];
 
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
            if(response) {
                setTimeout("finishAjax('show_sub_categories', '"+escape(response)+"')", 500);
                flag = 0;
            }
            else {
                flag = 1;
                $('.loader').remove();
                // get courses after all category loaded
                setTimeout(function() {
                    var sel_val = $('#show_sub_categories select').last();
                    getCourses(sel_val.val());
                }, 1000);
            }
        });
 
        return false;
    });
    
    // for fillCalendar on selection of any course
    $(document).on('change', "#courses", function() {
        var courses = $(this).val();
        if(courses != 0 && courses != null) {
            var data    = {
                csrf_token      : csrf_token,
                course_id        : courses, 
            }; 
                
            ajaxPost('get_batches', '#courses_loader', data, function(response) {
                batches_ori = response;
                
                if(selectedBatch == null || selectedBatch == '' ) { // show calendar only in case of create booking
                    $('#batch_calendar').show();
                    fillCalendar(response);
                    $('#courses_loader').empty();
                } 
                    
            });
        }
    }).trigger({ 
        type:   'change',
        target: $('#courses')[0]
    });

    // for selected event in case of edit
    if(selectedBatch != null && selectedBatch != '' ) {
        selectedEvent(JSON.parse(selectedBatch));
    }

    // for additional members dynamic rows
    var wrapper         = $(".members"); //Fields wrapper
    var add_button      = $(".add_member_button"); //Add button ID
    var x               = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        // add members according to availability of seats
        var count_members = $("input[name='fullname[]']").length;
        if(count_members < available_seats) {
            e.preventDefault();
            x++; //text box increment
            var clone = $(".members>div:lt(1)").clone();
            clone.find('input').val('');
            clone.find('select').val('');
            clone.find('textarea').val('');
            $(wrapper).append(clone); //add input box
            // $('.datepicker').each(function(){
            //     $(this).datepicker({autoclose: true});
            // });

            getNetFees();    
        } else {
            if(count_members == available_seats) 
                swal({
                    title: "{{b_bookings_booking_full}}",
                    timer: 2000,
                    showConfirmButton: true
                });
            else if($('#batches_id').val() != '')
                swal({
                    title: "{{b_bookings_booking_last}}",
                    timer: 2000,
                    showConfirmButton: true
                });
            else
                swal({
                    title: "{{b_bookings_select_batch}}",
                    timer: 2000,
                    showConfirmButton: true
                });
        }
    });
    
    $(wrapper).on("click",".remove_member", function(e){ //user click on remove text
        e.preventDefault(); 
        if(x > 1) {
            $(this).parent('div').remove(); 
            var count_members = $("input[name='fullname[]']").length;
            getNetFees();
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
                        
                        if((item[item.length -2]+item[item.length -1]) == '[]')
                            $('label.'+item.slice(0,-2)).addClass('text-danger');
                        else
                            $('label.'+item).addClass('text-danger');

                        // if batch is not selected
                        if(item == 'batches_id' || item == 'booking_date' || item == 'start_time')
                            $('#batch_label').closest('label').addClass('text-danger');    
                    });

                    post_flag = 1;
                    $('#submit_loader').empty();
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