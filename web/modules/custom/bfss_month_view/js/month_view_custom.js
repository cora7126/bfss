
  document.addEventListener('DOMContentLoaded', function() {
    var month_v = jQuery.urlParam('MonthView'); // name
    var event_data = jQuery.urlParam('EventData'); // name
    var month_arr = [];
    var month_arr = month_v.split('/');
    var M = month_arr[0];
    var Y = month_arr[1];
    var default_date = Y+'-'+M+'-01';
    var calendarEl = document.getElementById('calendar');
    console.log(event_data);
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'dayGrid' ],
      defaultDate: default_date,
      //editable: true,
      eventLimit: true, // allow "more" link when too many events

      events: {url: 'http://5ppsystem.com/event-data'}
    });

    calendar.render();
  });

jQuery.urlParam = function(name){
  var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
  return results[1] || 0;
}