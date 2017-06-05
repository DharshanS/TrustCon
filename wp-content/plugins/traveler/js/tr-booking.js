jQuery(document).ready(function() 
{ 
    jQuery('.date-pickR').datepicker({autoclose: true, todayHighlight: true});
    var id = '#fare-pop';
    //transition effect
    jQuery(id).fadeIn(1000);
    jQuery(id).fadeTo("slow", 0.8);
    //Get the window height and width
    var winH = jQuery(window).height();
    var winW = jQuery(window).width();
    //transition effect
    jQuery(id).fadeIn(2000);

    jQuery("#paylater-btn").click(function ()
    {
        var paymode = jQuery(this).text();
        alert(paymode);
        makeReservationAjax("", paymode);

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

function collectReservationDetails()
{ 
    alert('collectReservationDetails');
        var k=0;
        var first_name=[];
        var titile_array=[];
        var last_name=[];
        var dob_arry=[];
        var age_array=[];
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
         var passType=jQuery(this).attr('typePass');
         var dob=new Date(jQuery(this).val());
         var currentDate=new Date;
         var diffdate=currentDate.getTime()-dob.getTime();
         var years = 24 * 60 * 60 * 1000*360; 
         var age=diffdate/years;
     
            
        if(passType==='ADT')
        {
           if(age>11)
           {}else{ jQuery('#doberror'+k).text('adult age should be more than 11 years');return;}
         
        }
        else if(passType==='CNN')
        {
           if(age<=11& age>2)
           {}else{ jQuery('#doberror'+k).text('child age should be between 2 to 11 years');return;} 
        }
        else if(passType==='INF')
        {
            if(age<=2)
           {}else{ jQuery('#doberror'+k).text('infant age should be bellow 2 years');
           return;}
        }
        age_array.push(age);
        dob_arry.push(jQuery(this).val());
	k=k+1;
});

/*************************************************/
jQuery('#countryerror').text('');	
countryCode=jQuery('#country').val();
if(countryCode ==='' || MobCountryValidate(countryCode)=== false)
{
	jQuery('#countryerror').text('Enter Valid Country Code!');	
	jQuery('#country').focus();
	return;
}

/*************************************************/
jQuery('#moberror').text('');	
mobNumber=jQuery('#mob').val();
if(mobNumber === '' || MobValidate(mobNumber) === false)
{
	jQuery('#moberror').text('Enter Valid Mobile No!');	
	jQuery('#mob').focus();
	return;
}

/*************************************************/
jQuery('#emailerror').text('');	
var eml=jQuery('#email_address').val();
var send_offers=jQuery('#send_offers').is(":checked");
if(send_offers===true)send_offers=1;
else send_offers=0;
if(isValidEmailAddress(eml)===false)
{
jQuery('#emailerror').text('Error, enter a valid email address!');	
return;
}
var bookRequest={
    email:eml,
    sendOffers:send_offers,
    f_name:first_name,
    l_name:last_name,
    titile:titile_array,
    dob:dob_arry,
    age:age_array,
    sex:sex_array,
    countryCode:countryCode,
    mobNumber:mobNumber,
    passExDate:passport_exp_date,
    passCountry:passport_country,
    birthCountry:birth_country
};
return bookRequest;
}

function makeReservationAjax(request,mode)
{
    alert('makeReservation called'+ request.email);
       jQuery.ajax({
 	type: 'post', 
        data:{action:'makeReservation',reservationRequest:request,paymode:mode},
	url: '/travel/wp-admin/admin-ajax.php',
	success: function(h){ }
            });
}

});