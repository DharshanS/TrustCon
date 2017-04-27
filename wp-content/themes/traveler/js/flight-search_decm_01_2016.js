var mcity=0;
var ncity=2;
function chng_way(ele){
	if(mcity==1){
		doSlide();
	}
	if(ele.value=='oneway')jQuery('#return_date').hide();
	if(ele.value=='roundtrip')jQuery('#return_date').show();
}
jQuery(document).ready(function(){ 
	jQuery(".mlt").click(function(){
		doSlide();
	});
    jQuery('.date-pick').datepicker({ autoclose: true,todayHighlight: true});
});
function doSlide(){
   jQuery(".multiple-system").slideToggle("3000",function(){
		if (jQuery('.multiple-system').is(':hidden')){
			mcity=0;
			// select the default
			jQuery('#roundtrip').prop('checked', true);
			jQuery('#return_date').show();
			jQuery( "#mlt-st" ).removeClass( "actv" );
		}else{
			mcity=1;
			jQuery( "#mlt-st" ).addClass( "actv" );
		}
  });
}

function rmv(ele){
 jQuery(ele).parent().parent().remove();	
 ncity=ncity-1;
}
jQuery("#addcity-btn").on("click", function() {
	if( ncity < 5 ){
	 var l=ncity-2+1;
	  jQuery.ajax({
			type: 'post',
			data:{l:l},
			url: 'http://www.clickmybooking.com/addcity.php',
			//url: 'http://localhost/clickmybooking/addcity.php',
			success: function(h) { 
			  jQuery('#pps').html(h);	
		      ncity = ncity +1;	
			}
		});
	}
});