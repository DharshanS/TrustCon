<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User Inside Cancellation
 *
 * Created by H+Plus Designs - Sri Lanka 
 *
 */
?>

<?php
if(isset($_POST['mode']) && $_POST['mode']=='canform') {  // canform
$hasError = false;
if(trim($_POST['booking_referance']) === '') {
		$bookingreferanceError = 'Please enter your booking referance';
		$hasError = true;
} else {
	$booking_referance = trim($_POST['booking_referance']);
}
if(trim($_POST['surname']) === '') {
		$surnameError = 'Please enter your surname';
	    $hasError = true;
} else {
	$surname = trim($_POST['surname']);
}
if(trim($_POST['phone_no']) === '') {
		$phoneError = 'Please enter your phone number';
		$hasError = true;
} else {
	$phone = trim($_POST['phone_no']);
}

if(trim($_POST['email']) === '')  {
		$emailError = 'Please enter your email address';
		$hasError = true;
} else if (!preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,4}$/i", trim($_POST['email']))) {
$emailError = 'You entered an invalid email address';
$hasError = true;
} else {
$email = trim($_POST['email']);
}

if(trim($_POST['comments']) === '') {
	$commentError = 'Please enter cancellation message';
	$hasError = true;
} else {
	if(function_exists('stripslashes')) {
		$comments = stripslashes(trim($_POST['comments']));
	} else {
		$comments = trim($_POST['comments']);
}
}

if(!$hasError) {
/*$emailTo = get_option('tz_email');
if (!isset($emailTo) || ($emailTo == '') ){
	$emailTo = get_option('admin_email');
}*/
$path = get_home_path();
require_once($path.'PHPMailer/PHPMailerAutoload.php'); 
require_once($path."mailsettings.php");
$adminmail=$mailsetters['adminmail'];

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

$mail2 = new PHPMailer;
$mail2->isSMTP(); 
$mail2->Host = $mailsetters['host']; 
$mail2->SMTPAuth = $mailsetters['smtpauth'];    
$mail2->SMTPDebug  =$mailsetters['smtpdebug'];
$mail2->Port =$mailsetters['port'];   
$mail2->Username = $mailsetters['username'];                
$mail2->Password = $mailsetters['password'];                         
$mail2->SMTPSecure = $mailsetters['smtpsecure'];                            
$mail2->From = $mailsetters['from'];
$mail2->FromName = $mailsetters['fromname'];

$emailTo = $mailsetters['from'];


$subject = 'Flight Booking Cancel Request From '.$surname;
$body = "Dear Admin,\n\nA booking cancel request has been posted with the following information:\n\n";
$body.="Surname: ".$surname." \n\nEmail: ".$email." \n\nPhone: ".$phone."\n\nCancellation Message: ".$comments."";
$body.="\n\n Thanks";
$body=nl2br($body);
/*$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: '.$surname.' <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
wp_mail($emailTo, $subject, $body, $headers);
$emailSent = true;*/
//$mail->addAddress("smithhappy123@gmail.com"); 
$mail->addAddress($emailTo); 
$mail->addReplyTo($email);
$mail->isHTML(true);  
$mail->Subject = $subject;
$mail->Body    = $body;
$mail->send();

$mail2->addAddress($adminmail); 
$mail2->addReplyTo($mailsetters['from']);
$mail2->isHTML(true);
$mail2->Subject = $subject;
$mail2->Body    = $body;
$mail2->send();  

$emailSent = true;
}
}
?>

<style type="text/css">
.cancel-area { width:90%; max-width:700px; margin:0 auto; background:#fff; border:1px solid #ccc; }
.cancel-area .cancel-heading { background:#005AA8; color:#fff; padding:10px 20px; width:100%; }
.cancel-area .cancel-heading h1 { font-size: 22px; font-weight: bold; margin:5px 0; }
.cancel-area .cancel-heading h1 span { display:block; font-size:14px; margin-top:5px; font-weight:normal; }
.cancel-area .cmb-cross { float: right; margin: 8px 0; width: auto; }
.cancel-area .cancel-body { background: #fff; padding: 20px; width: 100%; }
.cancel-area .cancel-body label { font-size: 12px; font-weight: normal; margin: 5px 0 12px; text-transform: uppercase; width: 100%; }
.cancel-area .cancel-body input[type="text"], .cancel-area .cancel-body input[type="email"] { background: #f5f5f5; border: 1px solid #eee; border-radius: 4px; box-shadow: 0 2px 1px rgba(0, 0, 0, 0.1) inset; margin-bottom: 10px; padding: 5px 5px 10px; width: 100%; font-weight:bold; }
.cancel-area .cancel-body textarea { background: #f5f5f5; border: 1px solid #eee; border-radius: 4px; box-shadow: 0 2px 1px rgba(0, 0, 0, 0.1) inset; margin-bottom: 10px; padding: 5px 5px 10px; width: 100%; max-width:100%; min-width:100%; height:150px; max-height:150px; min-height:150px; font-weight:bold; }
.cancel-area .cancel-body hr { float: left; margin: 0 0 20px; width: 100%; }
.cancel-area .cancel-body input[type="submit"] { background: #005aa8; border: 1px solid #005aa8; color: #fff; font-size: 15px; padding: 8px 0; text-transform: uppercase; width: 100%; }
</style>
<div class="st-create">
    <h2>Inside Cancellation</h2>
    
    <div class="cancel-area">
    	 <div class="cancel-body">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
              <?php if(isset($emailSent) && $emailSent == true) { ?>
                <div class="thanks">
                    <p style="color:#FF6633;">Thanks, your request sent successfully.</p>
                </div>
            <?php } else { ?>
                <?php if($hasError){ ?>
                    <?php /*?><span class="error">Sorry, an error occured.</span><?php */?>
             <?php } ?>
        	<form action="<?php the_permalink(); ?>?sc=inside-cancellation" method="post" name="cancel-form">
                <input type="hidden" name="mode" value="canform" />
            	<div class="row">
                	<div class="col-sm-6">
                    	<label>Your Booking Reference:</label>
                        <input type="text" name="booking_referance" placeholder="enter your booking reference" value="<?php isset($_POST['booking_referance'])?$_POST['booking_referance']:'';?>"/>
                        <span style="color:#F00;"><?php echo $bookingreferanceError;?></span>
                    </div>
                    <div class="col-sm-6">
                    	<label>Your Surname:</label>
                        <input type="text" name="surname" placeholder="enter your surname" value="<?php isset($_POST['surname'])?$_POST['surname']:'';?>"/>
                        <span style="color:#F00;"><?php echo $surnameError;?></span>
                    </div>
                    <div class="col-sm-12"><hr /></div>
                    <div class="col-sm-6">
                    	<label>Phone No:</label>
                        <input type="text" name="phone_no" placeholder="enter your phone no" value="<?php isset($_POST['phone_no'])?$_POST['phone_no']:'';?>"/>
                        <span style="color:#F00;"><?php echo $phoneError;?></span>
                    </div>
                    <div class="col-sm-6">
                    	<label>Email Id:</label>
                        <input type="email" name="email" placeholder="enter your email id" value="<?php isset($_POST['email'])?$_POST['email']:'';?>"/>
                        <span style="color:#F00;"><?php echo $emailError;?></span>
                    </div>
                    <div class="col-sm-12"><hr /></div>
                    <div class="col-sm-12">
                    	<label>Cancellation Message</label>
                        <textarea name="comments" placeholder="enter your cancellation message"><?php isset($_POST['comments'])?$_POST['comments']:'';?></textarea>
                        <span style="color:#F00;"><?php echo $commentError;?></span>
                    </div>
                    <div class="col-sm-12"><hr /></div>
                    <div class="col-sm-12">
                    	<input type="submit" name="submit" value="Submit" />
                    </div>
                </div>
            </form>
            <?php } ?>
           <?php endwhile; endif; ?>
        </div>
    </div>
    
</div>







