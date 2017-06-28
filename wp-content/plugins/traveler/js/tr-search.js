jQuery(document).ready(function(){


jQuery("#cl").on("click", function() {
    alert("clik !!!");
});

	jQuery(".shw").on("click", function() {

            var ele=jQuery(this).attr("key");
		jQuery(ele).slideToggle('slow');

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


/*************************************/
    jQuery(".moretime_btn").click(function () {
        var ele = '#bestRo_' + jQuery(this).attr("id");
         alert(ele);
        alert('flag -- > '+jQuery(this).siblings(".collapse").attr("aria-hidden"));
  
       
        if (jQuery(this).siblings(".collapse").attr("aria-hidden")=='true')
        {
     
            jQuery(ele).css("visibility","visible");
            jQuery(this).siblings(".collapse").attr("aria-hidden","false");
        } 
        else
        {
         
            jQuery(ele).css("visibility","hidden");
             jQuery(this).siblings(".collapse").attr("aria-hidden","true");
        }


    });
   /*************************************/ 
     jQuery("input[name='selectFly']").click(function () {
         
       
         var radioValue = JSON.parse(atob(jQuery("input[name='selectFly']:checked").val()));
          
           jQuery.each(radioValue, function (index, element) {
              
              var date_act=new Date(element["@attributes"]['DepartureTime']);
         
          
              
               console.log(getTime(date_act));
            
           });
     });
//      jQuery('.typeahead').typeahead(
// 	{
// 		hint: true,
// 		highlight: true,
// 		minLength: 3,
// 		limit: 8
// 	},
// 	{
// 	source: function(q, cb) {
// 		return jQuery.ajax({
// 			dataType: 'json',
// 			type: 'get',
// 			url: 'http://www.clickmybooking.com/getcity_airport.php?q=' + q ,
// 			cache: false,
// 			success: function(data) {
// 				var result = [];
// 				jQuery.each(data, function(index, val) {
// 					result.push({
// 						value: val
// 					});
// 				});
// 				cb(result);
// 			}
// 		});
// 	}
// });


});
    
    function getTime(da)
    {
         var t;
           var time;
           var actualTime=da.getHours();
            
        if (actualTime >= 12 && actualTime < 24)
            {
                t = 'pm';
                time = da.getHours() % 12;
            } else
            {
                t = 'am';
                time = da.getHours();
                if (24 ===da.getHours())
                {
                    time = 12;
                }
            }
         return time+':'+da.getMinutes()+' '+t; 
    }

function chng_way(ele){ 

	if(ele.value=='oneway')jQuery('#return_date_div').addClass('in-active');

	if(ele.value=='roundtrip')jQuery('#return_date_div').removeClass('in-active');

}




