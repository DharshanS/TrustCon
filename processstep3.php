<?php

session_start();

if(!isset($_SESSION['booking_entries'])){
echo 'Something gone wrong';
}

## Rajib Added - - 26/10/2015
function age($birthday){
 list($year,$month,$day) = explode("-",$birthday);
 $year_diff  = date("Y") - $year;
 $month_diff = date("m") - $month;
 $day_diff   = date("d") - $day;
 if ($day_diff < 0 && $month_diff==0){$year_diff--;}
 if ($day_diff < 0 && $month_diff < 0){$year_diff--;}
 return $year_diff;
}
## Rajib Added

$_SESSION['booking_entries']['ptype']=$_POST['ptype'];
$_SESSION['booking_entries']['title']=$_POST['title'];
$_SESSION['booking_entries']['first_name']=$_POST['first_name'];
$_SESSION['booking_entries']['last_name']=$_POST['last_name'];
$_SESSION['booking_entries']['dob']=$_POST['dob'];

## Calculation & storing ages - Rajib - 26/10/2015
$_SESSION['booking_entries']['age'][0] = age($_SESSION['booking_entries']['dob'][0]);
$_SESSION['booking_entries']['age'][1] = age($_SESSION['booking_entries']['dob'][1]);
$_SESSION['booking_entries']['age'][2] = age($_SESSION['booking_entries']['dob'][2]);
## Calculation & storing ages - Rajib - 26/10/2015

## Rajib Added - 15/10/2015
$_SESSION['booking_entries']['country']=$_POST['country'];
## Rajib Added - 15/10/2015
$_SESSION['booking_entries']['phone']=$_POST['phone'];
$_SESSION['booking_entries']['passport_no']=$_POST['passport_no'];
$_SESSION['booking_entries']['passport_exp_date']=$_POST['passport_exp_date'];
$_SESSION['booking_entries']['passport_country']=$_POST['passport_country'];
$_SESSION['booking_entries']['flyer_club']=$_POST['flyer_club'];
$_SESSION['booking_entries']['flyer_number']=$_POST['flyer_number'];

## Rajib Added - 18/12/2015
$_SESSION['booking_entries']['sex']=$_POST['sex'];
$_SESSION['booking_entries']['AddressName']=$_POST['AddressName'];
$_SESSION['booking_entries']['Street']=$_POST['Street'];
$_SESSION['booking_entries']['State']=$_POST['State'];
$_SESSION['booking_entries']['PostalCode']=$_POST['PostalCode'];
$_SESSION['booking_entries']['City']=$_POST['City'];
$_SESSION['booking_entries']['Country']=$_POST['Country'];
$_SESSION['booking_entries']['birth_country']=$_POST['birth_country'];
## Rajib Added - 18/12/2015

$_SESSION['booking_entries']['letterhead']=$_POST['letterhead'];
$html='';
foreach($_SESSION['booking_entries']['first_name'] as $k=>$f){
$html.='
<div class="col-sm-12">
<div class="gaping traveller">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td><img src="images/men.png" class="mail" /><span>'.$_SESSION['booking_entries']['first_name'][$k].' '.$_SESSION['booking_entries']['last_name'][$k].'</span></td>
	  <td><img src="images/baby.png" class="mail" /><span>'.$_SESSION['booking_entries']['dob'][$k].'</span></td>';
	  if(isset($_SESSION['booking_entries']['phone'][$k]))
$html.='<td><img src="images/mobile.png" class="mail" /><span>'.$_SESSION['booking_entries']['country'][$k].'-'.$_SESSION['booking_entries']['phone'][$k].'</span></td>';
       else $html.='<td><img src="images/mobile.png" class="mail" /><span></span></td>';
$html.='</tr>
  </table>
</div>
</div>
';
}
if($_SESSION['booking_entries']['booking_reason']=='Fare Quotation'){
$html.='	
<div class="letter-heading-area quotehead" id="letterhead">
  <label>Whome to address:</label>
  <div>
	   <div style="float:left; margin-right: 1%; width: 32%;">
		   Name: <textarea name="letterhead_name">'.$_SESSION['booking_entries']['letterhead_name'].'</textarea>
	   </div>
	   <div style="float:left; margin-right: 1%; width: 33%;">
	      Company: <textarea name="letterhead_company">'.$_SESSION['booking_entries']['letterhead_company'].'</textarea>
	   </div>
	   <div style="float:left; width: 33%;">
	      Address: <textarea name="letterhead_address">'.$_SESSION['booking_entries']['letterhead_address'].'</textarea>
	   </div>
   </div>
</div>';	
}
if($_SESSION['booking_entries']['booking_reason']=='Embassy Visa Purpose'){
$html.='	
<div class="letter-heading-area quotehead" id="letterhead">
  <label>Whome to address:</label>
  <div>
	   <div style="float:left; margin-right: 1%; width: 32%;">
		   To: <textarea name="letterhead_name">The Visa Officer</textarea>
	   </div>
	   <div style="float:left; margin-right: 1%; width: 33%;">
	      Embassy Name: <textarea name="letterhead_company">'.$_SESSION['booking_entries']['letterhead_company'].'</textarea>
	   </div>
	   <div style="float:left; width: 33%;">
	      Embassy Address: <textarea name="letterhead_address">'.$_SESSION['booking_entries']['letterhead_address'].'</textarea>
	   </div>
   </div>
   <a href="'.'http://www.sltda.lk/embassies_in_sri_lanka'.'" target="_blank">Embassies list</a>
</div>';	
}
echo $html;
?>
