
jQuery(document).ready(function(){
  jQuery('body').on('click',".share a.share_assessment",function(){
      jQuery(".social_share_links").toggle('slow');
      console.log("here toogle");
  });

           jQuery('body').on('click','#calendar .use-ajax',function(){
             var nid = jQuery(this).attr('href').replace('#','');
         
                jQuery.ajax({
                  url : 'http://5ppsystem.com/calendar-modal-show/'+nid,
                  dataType: 'json',
                  cache: false,
                  success: function(data){

                    if(data){
                        jQuery("#calpopup-plx").html(data.modal); 
                       jQuery('#myModal').modal('show');
                    } 
                  },
                  error :function (data){

                  }
                });
             
          });

          //for scheduled
           jQuery('body').on('click','#calendar-scheduled .use-ajax',function(){
             var nid = jQuery(this).attr('href').replace('#','');
         
                jQuery.ajax({
                  url : 'http://5ppsystem.com/scheduled-calendar-modal-show/'+nid,
                  dataType: 'json',
                  cache: false,
                  success: function(data){

                    if(data){
                        jQuery("#calpopup-plx").html(data.modal); 
                       jQuery('#myModal').modal('show');
                    } 
                  },
                  error :function (data){

                  }
                });
             
          });

    jQuery('body').on('click', '#calendar a[href^="#"]', function (e) {
      e.preventDefault();
    });
     jQuery('body').on('click', '#calendar-scheduled a[href^="#"]', function (e) {
      e.preventDefault();
    });


});

  document.addEventListener('DOMContentLoaded', function() {
     
    // var month_v = jQuery.urlParam('MonthView'); // name
    // var event_data = jQuery.urlParam('EventData'); // name
    // var month_arr = [];
    // var month_arr = month_v.split('/');
    // var M = month_arr[0];
    // var Y = month_arr[1];
    //current date start
    var fulldate = new Date();
    var d = fulldate.getDate();
    d = ("0" + d).slice(-2);
    var m =  fulldate.getMonth();
    m = ("0" + m).slice(-2);
    var y = fulldate.getFullYear();
    var def_date = y+'-'+m+'-'+d; 
    //current date end
    var default_date = def_date;
    var calendarEl = document.getElementById('calendar');
  
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid' ],
      defaultDate: default_date,
      //editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: {url: 'http://5ppsystem.com/event-data'},
      eventRender: function (event,isMirror,isMirror,isStart,isEnd,view) {
        jQuery('.event-orange').addClass('yourClass'); 
      }
    });

    calendar.render();

    jQuery('.event-orange').addClass('yourClass'); 
    jQuery('.event-orange').attr('data-dialog-type','modal');
          jQuery('.event-orange').attr('data-dialog-options','{&quot;width&quot;:800, &quot;dialogClass&quot;: &quot;assessments-popup-md&quot;}');
    jQuery('.use-ajax').click(function(){
      console.log('here');
    });
          
  });


// jQuery.urlParam = function(name){
//   var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
//   return results[1] || 0;
// }



  document.addEventListener('DOMContentLoaded', function() {
     
    //current date start
    var fulldate = new Date();
    var d = fulldate.getDate();
    d = ("0" + d).slice(-2);
    var m =  fulldate.getMonth();
    m = ("0" + m).slice(-2);
    var y = fulldate.getFullYear();
    var def_date = y+'-'+m+'-'+d; 
    //current date end
    var default_date = def_date;
    var calendarEl = document.getElementById('calendar-scheduled');
  
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid' ],
      defaultDate: default_date,
      //editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: {url: 'http://5ppsystem.com/scheduled-event-data'},
      eventRender: function (event,isMirror,isMirror,isStart,isEnd,view) {
        jQuery('.event-orange').addClass('yourClass'); 
      }
    });

    calendar.render();

    jQuery('.event-orange').addClass('yourClass'); 
    jQuery('.event-orange').attr('data-dialog-type','modal');
          jQuery('.event-orange').attr('data-dialog-options','{&quot;width&quot;:800, &quot;dialogClass&quot;: &quot;assessments-popup-md&quot;}');
    jQuery('.use-ajax').click(function(){
      console.log('here');
    });
          
  });
