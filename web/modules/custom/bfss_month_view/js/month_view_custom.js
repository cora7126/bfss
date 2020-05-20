
jQuery(document).ready(function(){
  console.log("cal12345");
  jQuery('td.fc-event-container a').each(function(){
    jQuery('a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end').addClass('col use-ajax assessment_inner');
    jQuery('a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end').attr('data-dialog-type','modal');
    jQuery('a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end').attr('data-dialog-options','{&quot;width&quot;:800, &quot;dialogClass&quot;: &quot;assessments-popup-md&quot;}');
  });
});

  document.addEventListener('DOMContentLoaded', function() {
     
    var month_v = jQuery.urlParam('MonthView'); // name
    var event_data = jQuery.urlParam('EventData'); // name
    var month_arr = [];
    var month_arr = month_v.split('/');
    var M = month_arr[0];
    var Y = month_arr[1];
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
    console.log(event_data);
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid' ],
      defaultDate: default_date,
      //editable: true,
      eventLimit: true, // allow "more" link when too many events
      events: {url: 'http://5ppsystem.com/event-data'},
       // eventRender: function(event, element, view) {
       //   element.find('span.fc-title').addClass('yourClass'); 
       // },
    });

    calendar.render();
  });

jQuery.urlParam = function(name){
  var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
  return results[1] || 0;
}