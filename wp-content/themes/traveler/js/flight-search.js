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

jQuery('#depart_date').change(function () {
    //alert(jQuery('#depart_date').val());
	//  var temp = jQuery(this).datepicker('getDate');
	//  var d = new Date(temp);
	//  d.setDate(d.getDate() + 1);
	jQuery('#return_date').datepicker('setStartDate', jQuery('#depart_date').val());
});
jQuery('#adutl_no_one').change(function () {
    var adlt=jQuery('#adutl_no_one').val();
	jQuery("#child_no_one option").remove();
    for(var i=0;i<=(adlt * 2);i++){
		jQuery('<option>').val(i).text(i).appendTo('#child_no_one');
	}
	jQuery("#infant_no_one option").remove();
    for(var i=0;i<=(adlt * 1);i++){
		jQuery('<option>').val(i).text(i).appendTo('#infant_no_one');
	}
	
});