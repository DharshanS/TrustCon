jQuery(document).ready(function(){


	jQuery(".srh-button").click(function(){

		jQuery(".edit-area").slideToggle('slow');

	});

	jQuery(".shw").on("click", function() {

		jQuery(this).parent().parent().parent().prev().children(".edit-visl").slideToggle('slow');

		if ( jQuery.trim(jQuery(this).text().toString()) == ("Show Flight Details").toString() ) {

			jQuery(this).html('<span><i class="fa fa-minus"></i> Hide Flight Details</span>');

		} else {

			jQuery(this).html('<span><i class="fa fa-plus"></i> Show Flight Details</span>');

		}
                

	});
        
        jQuery(".more-fly").click(function(){

		//jQuery(".more-flights").slideToggle('slow');
              //  alert('more-fly'+ jQuery(this).parent().parent().parent().children('.more-flights'));
              jQuery(this).parent().parent().parent().children('.more-flights').slideToggle('slow');
                if ( jQuery.trim(jQuery(this).text().toString()) == ("more timing option").toString() ) {

			jQuery(this).text('Hide more timing option');

		} else {

			jQuery(this).html('<span><i class="fa fa-plus"></i> more timing option</span>');

		}
	});
       // jQuery('.more-flights').slideToggle('slow');

});

function chng_way(ele){ 

	if(ele.value=='oneway')jQuery('#return_date_div').addClass('in-active');

	if(ele.value=='roundtrip')jQuery('#return_date_div').removeClass('in-active');

}

jQuery('.date-pick').datepicker({ autoclose: true,todayHighlight: true});


jQuery('.typeahead').typeahead( 
	{ 
		hint: true, 
		highlight: true, 
		minLength: 3, 
		limit: 8 
	}, 
	{ 
	source: function(q, cb) { 
		return jQuery.ajax({ 
			dataType: 'json', 
			type: 'get', 
			url: 'http://www.clickmybooking.com/getcity_airport.php?q=' + q , 
			cache: false, 
			success: function(data) { 
				var result = []; 
				jQuery.each(data, function(index, val) { 
					result.push({ 
						value: val 
					}); 
				}); 
				cb(result); 
			} 
		}); 
	} 
});
