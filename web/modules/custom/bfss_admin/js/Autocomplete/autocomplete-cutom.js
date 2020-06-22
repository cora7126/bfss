jQuery(function() {
	// var states = [
	// 	'Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
	// 	'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
	// 	'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
	// 	'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
	// 	'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
	// 	'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
	// 	'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
	// 	'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
	// 	'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
	// ];

	jQuery(document).on('change', '.cover_area_state', function(){
		var state = jQuery(this).val();
		//console.log(state);
		var curr_el = jQuery(this).parent().parent().parent().attr('id');
		var city_el  = jQuery('#'+curr_el).find('.cover_area_city').attr('id');
		jQuery.ajax({
			url : 'http://5ppsystem.com/get-cities?state='+state,
			dataType: 'json',
			cache: false,
			success: function(data){
				jQuery(".cover_area_city").autocomplete({
					 source:data
					});
			},
			error :function (data){

			}
		});

	});

});