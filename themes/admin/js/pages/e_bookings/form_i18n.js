/*
 * Author: DK
 * Description: Classes create.js
 **/

// global events_id variable
var events_id      = '';
var events_ori     = [];
var booked_seats    = 0;
var available_seats = 0;

/*getNetFees for calculation and showing net fees of any event*/
function getNetFees() {
    var events_id    = $('#events_id').val();
    var count_members = $("input[name='fullname[]']").length;
    
    if(events_id == 0 || events_id == null)
        return false;

    var data    = {
        csrf_token      : csrf_token,
        events_id      : events_id, 
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
function getBookedSeats(events_id, booking_date) {
    var data    = {
        csrf_token      : csrf_token,
        events_id      : events_id, 
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

            events_id = calEvent.id;
            $('#events_id').val(events_id);
            $('#event_label').show();
            $('#event_loader').html(`<div class="preloader pl-size-xs">
                              <div class="spinner-layer pl-`+admin_theme+`">
                                  <div class="circle-clipper left">
                                      <div class="circle"></div>
                                  </div>
                                  <div class="circle-clipper right">
                                      <div class="circle"></div>
                                  </div>
                              </div>
                          </div>`);
            $('#event_label h4').html(item.title.textCapitalize());

            // in case of recurring event
            if(item.recurring == 1)
                start_date  = new Date(calEvent.start.toDate());
            else
                start_date  = new Date(item.start_date);

            start_d         = start_date.getDate();
            start_m         = start_date.getMonth()+1; // only increase month by one in case of eventClick
            start_y         = start_date.getFullYear();

            // in case of recurring event
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
                duration        = '<tr>'+'<td>{{e_bookings_booking_date}}      </td>'+'<td>'+$.format.date(start_date, "d MMM, yy")+'</td>';
            else
                duration        = '<tr>'+'<td>{{e_bookings_duration}}      </td>'+'<td>'+$.format.date(start_date, "d MMM, yy")+' - '+$.format.date(end_date, "d MMM, yy")+'</td>';

            if(item.recurring == '1')
                total_days  = '<tr>'+'<td>{{e_l_event_days}}     </td>'+'<td>'+JSON.parse(item.weekdays).length+'</td>';
            else
                total_days  = '';
            
            timing          = '<tr>'+'<td>{{e_bookings_timing}}        </td>'+'<td>'+time24To12(start_time)+' - '+time24To12(end_time)+'</td>';
            recurring       = '<tr>'+'<td>{{e_l_repetitive_event}}     </td>'+'<td>'+(item.recurring == '1' ? 'Yes' : 'No')+'</td>';

            booking_date    = $.format.date(start_date, "yyyy-MM-dd");
            
            getBookedSeats(events_id ,booking_date);

            setTimeout(function() {
                available_seats = item.capacity - booked_seats;
                if((available_seats) == 0) {
                    availability= '<tr class="text-danger">'+'<td><strong>{{e_bookings_availability}}  </strong></td>'+'<td>'+(available_seats)+'</td>';
                    booked      = '<tr class="text-danger">'+'<td><strong>{{e_bookings_booked}}        </strong></td>'+'<td>'+(booked_seats)+'</td>';
                } else {
                    availability= '<tr class="text-success">'+'<td><strong>{{e_bookings_availability}}  </strong></td>'+'<td>'+(available_seats)+'</td>';
                    booked      = '<tr class="text-success">'+'<td><strong>{{e_bookings_booked}}        </strong></td>'+'<td>'+(booked_seats)+'</td>';
                }
                    
                $('#event_label table').html(duration+timing+recurring+total_days+availability+booked);
                $('#event_loader').empty();

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
function selectedEventFunc(selEvent) {
    events_id = selEvent.events_id;
    $('#events_id').val(events_id);
    $('#event_label').show();
    //$('#event_loader').html('<i class="fa fa-circle-o-notch fa-spin loader"></i>');
    $('#event_label h4').html(selEvent.event_title.textCapitalize());
    $(".members-toggle").find("input,button,textarea,select").prop('disabled', true);
    $("#customers_label, #event_types_label").find("input,button,textarea,select").prop('disabled', true);
    $(".members-toggle").find("input,button,textarea,select").prop('disabled', true);
    $(".members-toggle").find("input,button,textarea,select").prop('disabled', true);

    // in case of recurring event
    if(selEvent.event_recurring == 1)
        start_date  = new Date(selEvent.booking_date);
    else
        start_date  = new Date(selEvent.event_start_date);

    start_d         = start_date.getDate();
    start_m         = start_date.getMonth()+1; // only increase month by one in case of eventClick
    start_y         = start_date.getFullYear();

    // in case of recurring event
    if(selEvent.event_recurring == 1)
        end_date    = new Date(selEvent.booking_date);
    else
        end_date    = new Date(selEvent.event_end_date);

    end_d           = end_date.getDate();
    end_m           = end_date.getMonth()+1; // only increase month by one in case of eventClick
    end_y           = end_date.getFullYear();

    start_time      = selEvent.event_start_time.split(':');
    start_time      = start_time[0]+':'+start_time[1];

    end_time        = selEvent.event_end_time.split(':');
    end_time        = end_time[0]+':'+end_time[1];

    if(selEvent.event_recurring == '1')
        duration        = '<tr>'+'<td>{{e_bookings_booking_date}}      </td>'+'<td>'+$.format.date(start_date, "d MMM, yy")+'</td>';
    else
        duration        = '<tr>'+'<td>{{e_bookings_duration}}      </td>'+'<td>'+$.format.date(start_date, "d MMM, yy")+' - '+$.format.date(end_date, "d MMM, yy")+'</td>';

    if(selEvent.event_recurring == '1')
        total_days  = '<tr>'+'<td>{{e_l_event_days}}     </td>'+'<td>'+selEvent.event_weekdays+'</td>';
    else
        total_days  = '';
    
    timing          = '<tr>'+'<td>{{e_bookings_timing}}        </td>'+'<td>'+time24To12(start_time)+' - '+time24To12(end_time)+'</td>';
    recurring       = '<tr>'+'<td>{{e_l_repetitive_event}}     </td>'+'<td>'+(selEvent.event_recurring == '1' ? 'Yes' : 'No')+'</td>';

    booking_date    = $.format.date(start_date, "yyyy-MM-dd");
    
    $('#event_label table').html(duration+timing+recurring+total_days);
    $('#booking_date').val(booking_date);
    $('#start_time').val(selEvent.event_start_time);

    getNetFees();
}

// for recurring events
function pad(num) {
  return String("0" + num).slice(-2);
}
function formatDate(d) {
  return d.getFullYear() + "-" + pad(d.getMonth() + 1) + "-" + pad(d.getDate())
}
var events_e         = [];
//emulate server
var getEvents = function( start, end ){
    return events_e;
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

        /*In case of recurring event*/
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
            events_e.push({
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

            events_e.push({
                id              : item.id,
                title           : item.title.textCapitalize(),
                start           : new Date(start_y, start_m, start_d, start_hor, start_min),
                end             : new Date(start_y, start_m, start_d, end_hor, end_min),
                backgroundColor : backgroundColor,
                borderColor     : backgroundColor
            });        
        }
        
    });

    var cal_default_date_set = '';
    if(events_e.length === 0) {
        // if there's no batches
        cal_default_date_set = new Date();
    } else {
        // set the very first batch date
        // skip recurring event date
        $.each(events_e, (index, item) => {
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
            maxDate     = moment(events_ori[(events_ori.length-1)].end_date);
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
                    scrollTop: $('#event_types_label').offset().top - 20
                }, 'slow');
            }
            else {  // disable older and blank months where events are concerned
                swal({
                    title: "{{e_bookings_booking_older_date}}",
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
            var events = getEvents(start,end); //this should be a JSON request
            callback(events);
        },
        events: events_e,
    });
}

var flag = 0;
$(function () {
    "use strict";

    // default hidden fields/elements
    $('#event_label').hide();
    $('#event_calendar').hide();

    // for fillCalendar on selection of any class
    $(document).on('change', "#event_types", function() {
        var event_types = $(this).val();
        if(event_types != 0 && event_types != null) {
            var data    = {
                csrf_token      : csrf_token,
                event_type_id   : event_types, 
            }; 
                
            ajaxPost('get_events', '#event_types_loader', data, function(response) {
                events_ori = response;
                if(selectedEvent == null || selectedEvent == '' ) { // show calendar only in case of create booking 
                    $('#event_calendar').show();
                    fillCalendar(response);
                    $('#event_types_loader').empty();
                }   
            });
        }
    }).trigger({ 
        type:   'change',
        target: $('#event_types')[0]
    });

    // for selected event in case of edit
    if(selectedEvent != null && selectedEvent != '' ) {
        selectedEventFunc(JSON.parse(selectedEvent));
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
                    title: "{{e_bookings_booking_full}}",
                    timer: 2000,
                    showConfirmButton: true
                });
            else if($('#events_id').val() != '')
                swal({
                    title: "{{e_bookings_booking_last}}",
                    timer: 2000,
                    showConfirmButton: true
                });
            else
                swal({
                    title: "{{e_bookings_select_event}}",
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

                        // if event is not selected
                        if(item == 'events_id' || item == 'booking_date' || item == 'start_time')
                            $('#event_label').closest('label').addClass('text-danger');    
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