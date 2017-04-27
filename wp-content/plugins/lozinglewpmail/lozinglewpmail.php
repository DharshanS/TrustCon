<?php
/**
 * @package lozinglewpmail
 */
/*
Plugin Name: Lozinglewpmail
Plugin URI: http://lozingle.com/
Description: Set Custmise Settings for the Email in your WP environment.
Version: 1.8.0
Author: Abdul Manashi
License: GPLv2 or later
Text Domain: Lozinglewpmail
Help: http://www.smashingmagazine.com/2011/10/create-perfect-emails-wordpress-website/
*/



/*add_filter ("wp_mail_content_type", "lozinglewpmail_content_type");
function lozinglewpmail_content_type() {
	return "text/html";
}
	
add_filter ("wp_mail_from", "lozinglewpmail_from");
function lozinglewpmail_from() {
	return "booking@clickmybooking.com";
}
	
add_filter ("wp_mail_from_name", "lozinglewpmail_from_name");
function lozinglewpmail_name() {
	return "Clickmybooking";
}*/

add_action( 'phpmailer_init', 'configure_smtp' );
function configure_smtp(PHPMailer $phpmailer) {
	$phpmailer->isSMTP(); //switch to smtp
	$phpmailer->Host = 'sg2plcpnl0030.prod.sin2.secureserver.net';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 465;
    $phpmailer->Username = 'booking@clickmybooking.com';
	$phpmailer->Password = 'Sanjaya1987';
    $phpmailer->SMTPSecure = 'ssl';
	$phpmailer->From = 'booking@clickmybooking.com';
	$phpmailer->FromName = 'Clickmybooking';
	$phpmailer->isHTML(true);
}
if ( !function_exists( 'wp_new_user_notification' ) ) {
function wp_new_user_notification($user_id, $plaintext_pass) {
	$user = new WP_User($user_id);
	$user_login = stripslashes($user->user_login);
	$user_email = stripslashes($user->user_email);
	$email_subject = "Welcome to Clickmybooking ".$user_login."!";
	
	ob_start();
	include("email_header.php");
	?>
	<p>Welcome to you, <?php echo $user_login ?>. Thank you for joining clickmybooking.com!</p>
	<p>
		Your password is <strong style="color:orange"><?php echo $plaintext_pass ?></strong> <br>
		Please keep it secret and keep it safe!
	</p>
	<p>
		We hope you enjoy your stay at clickmybooking.com. If you have any problems, questions, opinions, praise, 
		comments, suggestions, please feel free to contact us at any time
	</p>
	<?php
	include("email_footer.php");
	$message = ob_get_contents();
	ob_end_clean();
	wp_mail($user_email, $email_subject, $message);
}
}

add_filter ("retrieve_password_title", "lozinglewpmail_retrieve_password_title");

function lozinglewpmail_retrieve_password_title() {
	return "Clickmybooking Password Reset";
}

	
add_filter ("retrieve_password_message", "lozinglewpmail_retrieve_password_message", 10,2);
function lozinglewpmail_retrieve_password_message($content, $key) {
	$user_data = '';
    // If no value is posted, return false
    if( ! isset( $_POST['user_login'] )  ){
            return '';
    }
    // Fetch user information from user_login
    if ( strpos( $_POST['user_login'], '@' ) ) {

        $user_data = get_user_by( 'email', trim( $_POST['user_login'] ) );
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }
    if( ! $user_data  ){
        return '';
    }
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
	
	ob_start();
	//$email_subject = imp_retrieve_password_title();
	include("email_header.php");
	?>
    <p>
     Dear User,
    </p>
    <p>
		It likes you (hopefully) want to reset your password for your clickmybooking.com account.
	</p>
     <p>
		To reset your password, visit the following link, otherwise just ignore this email and nothing will happen.
        <br>
       <a href="<?php echo network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');?>"><?php echo network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');?></a>
 
	</p>
   
    <?php
	include("email_footer.php");
	
	$message = ob_get_contents();
	ob_end_clean();
	return $message;
}