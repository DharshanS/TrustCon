<?php
session_start();
if(!isset($_SESSION['hotel_entries'])){
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

$_SESSION['hotel_entries']['ptype']=$_POST['ptype'];
$_SESSION['hotel_entries']['title']=$_POST['title'];
$_SESSION['hotel_entries']['first_name']=$_POST['first_name'];
$_SESSION['hotel_entries']['last_name']=$_POST['last_name'];
$_SESSION['hotel_entries']['dob']=$_POST['dob'];

## Calculation & storing ages - Rajib - 26/10/2015
$_SESSION['hotel_entries']['age'][0] = age($_SESSION['hotel_entries']['dob'][0]);
$_SESSION['hotel_entries']['age'][1] = age($_SESSION['hotel_entries']['dob'][1]);
$_SESSION['hotel_entries']['age'][2] = age($_SESSION['hotel_entries']['dob'][2]);
## Calculation & storing ages - Rajib - 26/10/2015

## Rajib Added - 15/10/2015
$_SESSION['hotel_entries']['country']=$_POST['country'];
## Rajib Added - 15/10/2015
$_SESSION['hotel_entries']['phone']=$_POST['phone'];
$_SESSION['hotel_entries']['passport_no']=$_POST['passport_no'];
$_SESSION['hotel_entries']['passport_exp_date']=$_POST['passport_exp_date'];
$_SESSION['hotel_entries']['passport_country']=$_POST['passport_country'];
$_SESSION['hotel_entries']['flyer_club']=$_POST['flyer_club'];
$_SESSION['hotel_entries']['flyer_number']=$_POST['flyer_number'];

$_SESSION['hotel_entries']['letterhead']=$_POST['letterhead'];
$html='';
foreach($_SESSION['hotel_entries']['first_name'] as $k=>$f){
$html.='
<div class="col-sm-12">
<div class="gaping traveller">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td><img src="images/men.png" class="mail" /><span>'.$_SESSION['hotel_entries']['first_name'][$k].' '.$_SESSION['hotel_entries']['last_name'][$k].'</span></td>
	  <td><img src="images/baby.png" class="mail" /><span>'.$_SESSION['hotel_entries']['dob'][$k].'</span></td>';
	  if(isset($_SESSION['hotel_entries']['phone'][$k]))
$html.='<td><img src="images/mobile.png" class="mail" /><span>'.$_SESSION['hotel_entries']['country'][$k].'-'.$_SESSION['hotel_entries']['phone'][$k].'</span></td>';
       else $html.='<td><img src="images/mobile.png" class="mail" /><span></span></td>';
$html.='</tr>
  </table>
</div>
</div>
';
if($_SESSION['hotel_entries']['booking_reason']=='Fare Quotation' || $_SESSION['hotel_entries']['booking_reason']=='Embassy Purpose'){
$html.='	
<div class="letter-heading-area" id="letterhead">
  <label>Whome to address:</label>
  <textarea name="letterhead">'.$_SESSION['hotel_entries']['letterhead'].'</textarea>
</div>';	
}

}
echo $html;
?>
