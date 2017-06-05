<?php
/*
Template Name: Transport Result Next
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Template Name : Home
 *
 * Created by ShineTheme
 * Rajib Ganguly -- 18-11-2015
 */
if ( is_user_logged_in() ){
	if(isset($_SESSION['redirectoccurin'])&& $_SESSION['redirectoccurin']!=''){
		$ru=$_SESSION['redirectoccurin'];
		wp_redirect( home_url("/".$ru) ); exit;
	}
}else {
       
}

get_header();

/*echo '<pre>';
print_r($_POST);*/


while(have_posts()){
    the_post();
	the_content();

require_once(ABSPATH."travelportsettings.php");
global $wpdb; 


require_once('PHPMailer/PHPMailerAutoload.php'); 
require_once("mailsettings.php");
$mail = new PHPMailer;
$mail->isSMTP(); 
$mail->Host = $mailsetters['host']; 
$mail->SMTPAuth = $mailsetters['smtpauth'];    
$mail->SMTPDebug  =$mailsetters['smtpdebug'];
$mail->Port =$mailsetters['port'];   
$mail->Username = $mailsetters['username'];                
$mail->Password = $mailsetters['password'];                         
$mail->SMTPSecure = $mailsetters['smtpsecure'];                            
$mail->From = $mailsetters['from'];
$mail->FromName = $mailsetters['fromname'];

## Getting Posted values
foreach($_POST as $k=>$v)
{
	$$k = $v;
}

## Get Rate per Kilometer
$car = $wpdb->get_results(' SELECT * FROM vehicle WHERE id = '.$vehicle.' ');
//print_r($rate); die;
## Days for
$To = new DateTime($to);
$From = new DateTime($from);
$interval = $To->diff($From);
$days = $interval->d ;

$mytripid=tripIdGenerator();

// $city = $mysqli->real_escape_string($city);
	$SQL  =  'INSERT INTO booking_transport SET name = "'.$fname.' '.$lname.'" , dob = "'.$dob.'" , persons = '.$person.' , '.
	         ' from_dt = "'.$from.'" , to_dt = "'.$to.'" , email = "'.$email.'" , price = '.$car[0]->rate_per_kilometer.' , '.
			 ' booking_dt = "'.date('Y-m-d').'" , mobile = "'.$mobile.'" , booking_id = "'.$mytripid.'"  , '.
			 ' vehicle_id = '.$vehicle.' , start_desti = "'.$start.'" , end_desti =  "'.$end.'"  ';  
			 

$result = $wpdb->query($SQL);

if(isset($result))
{
// sendmail to client
$mailto= $email;
$mailsubject='';
$mailhtml='';
$mailtoname= $fname.' '.$lname;

$mail->addAddress($mailto, $mailtoname); 
$mail->addReplyTo($mailsetters['from']);
$mail->isHTML(true);  


$mailsubject='Clickmybooking - Transport Booking';
$mailhtml='Dear '.$mailtoname.',<br><br>';
$mailhtml.='Thanking for your inquiry with us dated '.date("jS M, Y").',';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Following is the Itenary for your search:';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='
<tr><td colspan="7"></td></tr>
<tr><td colspan="7"></td></tr>
<tr><td colspan="7" width="100%">
        <table  width="100%" cellpadding="0" cellspacing="0" border="1" bordercolor="#ccc" >
            <tr>
                <th width="70%">Price Details</th>
                <th>Price</th>
            </tr>
            <tr>
                <td width="70%">PNR</td>
                <td>'.$mytripid.'</td>
            </tr>
            <tr>
                <td width="70%">Rate</td>
                <td>'.$car[0]->rate_per_kilometer.'</td>
            </tr>
        </table>
</td></tr>
</table>';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='We are pleased to provide further information on request and trust that you would contact on us to fulfill your order, which will receive our prompt and keen attention.';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Thanking you,';
$mailhtml.='<br>';
$mailhtml.='<a href="http://www.clickmybooking.com">www.clickmybooking.com</a>';
	


$mail->Subject = $mailsubject;
$mail->Body    = $mailhtml;
$mail->send();
?> 
<div class="container">
	<div class="results">
    	<div class="row_div">
        	<div class="row">
            	<div class="col-sm-4">
                	<div class="col_img">
                    	<a href="#"><img src="../wp-content/plugins/transportbooking/uploads/vehicles/<?php echo $car[0]->photo;?>" /></a>
                    </div>
              	</div>
                <div class="col-sm-8">
                	<div class="res_title"><a href="#"><h4><?php echo $car[0]->vehicle;?></h4></a></div>
                	<div class="row">
                    	<div class="col-sm-12">
                            <div class="restaurant_info">
                            <p class="min-description"><span class="glyphicon glyphicon-map-marker">Thanking you for Booking Transport with us</span></p>
                            <div class="features">
                            </div>
                            </div>
                    	</div>                      
                </div>
            </div>
			<div class="col-sm-12">
				<div class="hotel-descr">
					<h2>Booking Information</h2>
                    <br /><br />
                    No of Day(s): <?php echo $days;?><br />
                    Person(s) : <?php echo $person;?><br />
                    Journey From Date : <?php echo $from;?><br />
                    Journey To Date :  <?php echo $to;?><br />
                    <strong>Trip ID :<strong> <?php echo $mytripid;?>  <br />
                    
				</div>
			</div>
        </div>
    </div>
</div>
</div>
<?php
}
?>

<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/flight-search.js"></script>
<script type="application/javascript">
//jQuery('.date-pick').datepicker({ autoclose: true});
</script>
<!--<script type="text/javascript" src="<?php echo site_url(); ?>/wp-content/plugins/hotelbooking/autocomplete/jquery.js"></script>-->
<script type='text/javascript' src='<?php echo site_url(); ?>/wp-content/plugins/hotelbooking/autocomplete/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>/wp-content/plugins/hotelbooking/autocomplete/jquery.autocomplete.css" />

<script type="application/javascript">


jQuery(document).ready(function() {
	jQuery("#city").autocomplete("<?php echo site_url(); ?>/wp-content/plugins/hotelbooking/ajaxpages/get_city.php", {
		width: 260,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});

// Rajib Added - Validation
jQuery('#sub').click( function () {
	
	if(jQuery('#city').val() == '')
	{
		alert('Please Enter City');
		jQuery('#city').focus();
		return false;
	}else if(jQuery('#checkin').val() == ''){
		alert('Please Enter Check In Date');
		jQuery('#checkin').focus();
		return false;
	}else if(jQuery('#checkout').val() == ''){
		alert('Please Enter Check Out Date');
		jQuery('#checkout').focus();
		return false;
	}
  return true;
});

});
</script>
<!-- SUbir Added - 03/12/15-->
<script>
$(function(){
	$('.carousel').carousel({
	  interval: 2000
	})	
});
</script>
<?php
}
get_footer();

function tripIdGenerator($length = 6){
$ret='C';
$pw='';
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$len = strlen($chars);
for ($i=0;$i<20;$i++)
        $pw .= substr($chars, rand(0, $len-1), 1);
// the finished password
$pw = str_shuffle($pw);
$pw=md5($pw);
$len = strlen($pw);
for ($i=0;$i<$length;$i++)
 $ret .= substr($pw, rand(0, $len-1), 1);
return strtoupper($ret);	
}

?>