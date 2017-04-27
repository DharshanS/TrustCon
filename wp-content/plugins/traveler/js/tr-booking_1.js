jQuery(document).ready(function() {
    alert('booking !!!!');
    jQuery('.date-pickR').datepicker({ autoclose: true,todayHighlight: true});
		var id = '#fare-pop';		
		//transition effect		
		jQuery(id).fadeIn(1000);	
		jQuery(id).fadeTo("slow",0.8);		
		//Get the window height and width
		var winH = jQuery(window).height();
		var winW = jQuery(window).width();
		//transition effect
		jQuery(id).fadeIn(2000); 	
});

function editit(s){ 
	jQuery('.step').css({"z-index": "0"});
	jQuery('#'+s).css({"position": "relative","float":"left","width":"100%","z-index": "999","background": "#fff"});
	jQuery('.pop-edit').show();
}
function unblockall(){ 
	jQuery('.step').css({"position": "","z-index": "0","background": ""});
	jQuery('.pop-edit').hide();
	jQuery('#make_reserv_btn').prop( "disabled", false );
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
// XXXX Added
function MobCountryValidate() {
        var country = document.getElementById("country").value;
        var pattern = /^\d{3,4}$/;
        if (pattern.test(country)) 
		return true;
        else
        return false;
    }
function MobValidate() {
        var mobile = document.getElementById("mob").value;
        var pattern = /^\d{9,10}$/;
        if (pattern.test(mobile)) 
		return true;
        else
        return false;
    }

function validateDateFormat(dateVal){
 
      var dateVal = dateVal;
 
      if (dateVal == null) 
          return false;
 
      var validatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/;
 
          dateValues = dateVal.match(validatePattern);
 
          if (dateValues == null) 
              return false;
 
      var dtYear = dateValues[1];        
          dtMonth = dateValues[3];
          dtDay=  dateValues[5];
 
       if (dtMonth < 1 || dtMonth > 12) 
          return false;
       else if (dtDay < 1 || dtDay> 31) 
         return false;
       else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
         return false;
       else if (dtMonth == 2){ 
         var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
         if (dtDay> 29 || (dtDay ==29 && !isleap)) 
                return false;
      }
 
     return true;
}

function processstep1(){
    
jQuery("#step1_after").slideToggle('slow');
jQuery('#step1').hide('slow');
jQuery('#step1_after').show();jQuery('#step2_dtls').show('slow');
unblockall();
}


function processstep2(){ 

jQuery('#emailerror').text('');	
var eml=jQuery('#email_address').val();
var send_offers=jQuery('#send_offers').is(":checked");
if(send_offers===true)send_offers=1;
else send_offers=0;
if(isValidEmailAddress(eml)===false){
jQuery('#emailerror').text('Error, enter a valid email address!');	
return;
}


jQuery.ajax({
	type: 'post',
	data:{ action: 'StepOne',eml:eml,send_offers:send_offers},
	url: '/travel/wp-admin/admin-ajax.php',
	success: function(h)
        {
            alert(h);
	   jQuery('#entered_email').text(h);
	   jQuery('#step2').hide();
           jQuery('#step2_after').show();
           jQuery('#step3_details').show('slow');
           unblockall();
	 
          jQuery('.collapse').show();
	
			   
	},
        error: function(xhr, textStatus, error){
              console.log(xhr.statusText);
      console.log(textStatus);
      console.log(error);
  }
});
}

function processstep3()
{ 
var k=0;
var first_name=[];
var titile_array=[];
var last_name=[];
var dob_arry=[];
var sex_array=[];
var countryCode;
var mobNumber;

var passport_exp_date=[];
var passport_country=[];
var birth_country=[];

var k=0;
 jQuery("select[name='title[]']").each(function ()
    {
         titile_array.push(jQuery.trim(jQuery(this).val()));
        k = k + 1;
    });
    
var k=0;
 jQuery("select[name='sex[]']").each(function ()
    {
         sex_array.push(jQuery.trim(jQuery(this).val()));
        k = k + 1;
    });    
    
    
var k=0;    
    jQuery("input[name='first_name[]']").each(function ()
    {
        jQuery('#fnameerror' + k).text('');
        if (jQuery.trim(jQuery(this).val()).length < 2)
        {
            jQuery('#fnameerror' + k).text('Missing Minimum two chracters !');
            return;
        }
         first_name.push(jQuery.trim(jQuery(this).val()));
        k = k + 1;
    });


var k=0;
jQuery("input[name='last_name[]']").each(function() {
	jQuery('#lnameerror'+k).text('');
    if(jQuery.trim(jQuery(this).val()).length<2){
	   jQuery('#lnameerror'+k).text('Missing Minimum two chracters !');
      return;
	}
        last_name.push(jQuery.trim(jQuery(this).val()));
	k=k+1;
});


var k=0;
jQuery("input[name='dob[]']").each(function() {
	jQuery('#doberror'+k).text('');
    if(jQuery(this).val()===''){
	   jQuery('#doberror'+k).text('DOB Missing');
   return;
	}
        dob_arry.push(jQuery(this).val());
	k=k+1;
});

// passport validation


// Mobile Country Code Chk
jQuery('#countryerror').text('');	

countryCode=jQuery('#country').val();

if(countryCode ==='' || MobCountryValidate(countryCode)=== false)
{
	jQuery('#countryerror').text('Enter Valid Country Code!');	
	jQuery('#country').focus();
	return;
}

// Mobile Chk
jQuery('#moberror').text('');	

mobNumber=jQuery('#mob').val();

if(mobNumber === '' || MobValidate(mobNumber) === false)
{
	jQuery('#moberror').text('Enter Valid Mobile No!');	
	jQuery('#mob').focus();
	return;
}




var bookRequest={
    f_name:first_name,
    l_name:last_name,
    titile:titile_array,
    dob:dob_arry,
    sex:sex_array,
    countryCode:countryCode,
    mobNumber:mobNumber,
    passExDate:passport_exp_date,
    passCountry:passport_country,
    birthCountry:birth_country
};




jQuery.ajax({
 	type: 'post', 
        data:{action:'StepThree',bookRequest:bookRequest},
	url: '/travel/wp-admin/admin-ajax.php',
	success: function(h) { 
	   jQuery('#step3').hide();
           jQuery('#step3_after').show();
           jQuery('#step4_details').show('slow');
           unblockall();
           alert(h);
	   processstep3_after(h);
	}
});



}

function processstep3_after(h){ 
	jQuery('#step3_after_content').html(h);
        
}
function processstep4(){
if(jQuery('#terms').is(":checked")==false){
	alert('Please visit the terms and condition page, you require to agree with those.');
	return;
}

jQuery('#step4_mail_details').html('Please Wait while we process your booking with the airline, this may take a minute.');
jQuery('#make_reserv_btn').val('Please Wait');
jQuery('#make_reserv_btn').prop( "disabled", true );


jQuery.ajax({
	type: 'post', 
	data: jQuery('textarea[name=\'letterhead_name\'],textarea[name=\'letterhead_company\'],textarea[name=\'letterhead_address\']'),
	url: '<?php echo home_url("/processstep4.php");?>',
	success: function(h) { 
	   if(h=='done'){
		   reservationcreate();
	   }
	}
});
 //jQuery('#step4').hide();jQuery('#step4_after').show();jQuery('#step5_details').show('slow');unblockall();	
}
function reservationcreate(){ 
 jQuery.ajax({  
	type: 'post', 
//	data: {p:'<?php echo base64_encode(serialize($_SESSION['PARSEDDATA']));?>',s:'<?php echo $searchdataoriginal;?>',r:'<?php echo base64_encode(serialize(array($userid)));?>',b:'free'},
	url: '<?php echo home_url("/reservation_create.php");?>',
	success: function(h) { 
	   jQuery('#reserved').html(h);
	   if(booking_reason=='Fare Quotation'){
		jQuery('#step5_details').html('');unblockall();	   
	   }else{
	 
	   getTripId();
		   if(booking_reason=='Embassy Visa Purpose'){
			   jQuery('#paynowarea').show();
			   jQuery('.embacytotal').show();
			   jQuery('.nonembacytotal').hide();
		   }else{
			   jQuery('.embacytotal').hide();
			   jQuery('.nonembacytotal').show();
		   }
		jQuery('#step5_details').show('slow');
		unblockall();
	   }
	}
});
}
function getPNR(){
	jQuery.ajax({  
	type: 'post', 
	url: '<?php echo home_url("/getpnr.php");?>',
	success: function(h) { 
	   jQuery('#pnr').text(h);
	}
  });
}
function getTripId(){
	jQuery.ajax({  
	type: 'post', 
	url: '<?php echo home_url("/gettripid.php");?>',
	success: function(h) { 
	   jQuery('#pnr').text(h);
	}
  });
}
jQuery(document).ready(function(){
 jQuery('.date-pick').datepicker({ autoclose: true,todayHighlight: true});
});

jQuery("#booking_reason").change(function(){
	var v=jQuery(this).val();
	jQuery('#letterhead').hide();
    if(v=='Fare Quotation' || v=='Embassy Visa Purpose'){
		jQuery('#letterhead').show();
	}
	if(booking_reason=='Embassy Visa Purpose'){
       jQuery('.embacytotal').show();
	   jQuery('.nonembacytotal').hide();
	}else{
	   jQuery('.embacytotal').hide();
	   jQuery('.nonembacytotal').show();
	}
});

