
jQuery(document).ready(function(){
  jQuery('body').on('click',".share a.share_assessment",function(){
      jQuery(".social_share_links").toggle('slow');
      console.log("here toogle");
  });
   jQuery('body').on('click','.use-ajax',function(){
     var nid = jQuery(this).attr('href').replace('#','');
    // console.log(nid);
       //var nid = 8;
        jQuery.ajax({
          url : 'http://5ppsystem.com/calendar-modal-show/'+nid,
          dataType: 'json',
          cache: false,
          success: function(data){
            //  console.log(data);
            // console.log('SDFSDF');

            console.log(data.modal);
            //  console.log(data.nid);
            if(data){
                jQuery("#calpopup-plx").html(data.modal); 
               jQuery('#myModal').modal('show');
            } 
          },
          error :function (data){
          //  console.log(data);
          }
        });


      //empty div and add id this for modal render #calpopup-plx
      // jQuery("#calpopup-plx").html('<!-- Modal --><div id="myModal" class="modal fade" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Modal Header</h4></div><div class="modal-body"><p>Some text in the modal.</p></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>');
       
    });

    jQuery('body').on('click', '#calendar a[href^="#"]', function (e) {
      e.preventDefault();
    });

  // jQuery('td.fc-event-container a').each(function(){
  //   jQuery('a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end').addClass('col use-ajax assessment_inner');
  //   jQuery('a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end').attr('data-dialog-type','modal');
  //   jQuery('a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end').attr('data-dialog-options','{&quot;width&quot;:800, &quot;dialogClass&quot;: &quot;assessments-popup-md&quot;}');
  // });
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
 jQuery('.use-ajax').click(function(){
      console.log('here12');
    });

// jQuery.urlParam = function(name){
//   var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
//   return results[1] || 0;
// }