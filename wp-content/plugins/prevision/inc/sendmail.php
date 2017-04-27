<?php
session_start();

global $WP3S_Prevision;

$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];
require_once( $path_to_wp.'/wp-load.php' );
require_once( $path_to_wp.'/wp-includes/functions.php');

// change this email address to your own email id.
$option = get_option('wp3s_'); 
define("CONTACT_EMAIL", $WP3S_Prevision->_settings['wp3s_prevision_emailaddress']);
define("SUBJECT_EMAIL", __('Please contact me', 'wp3s_prevision'));

function ValidateEmail($email)
	{
	/*
	(Name) Letters, Numbers, Dots, Hyphens and Underscores
	(@ sign)
	(Domain) (with possible subdomain(s) ).
	Contains only letters, numbers, dots and hyphens (up to 255 characters)
	(. sign)
	(Extension) Letters only (up to 10 (can be increased in the future) characters)
	*/

	$regex = '/([a-z0-9_.-]+)'. # name

	'@'. # at

	'([a-z0-9.-]+){2,255}'. # domain & possibly subdomains

	'.'. # period

	'([a-z]+){2,10}/i'; # domain extension 

	if($email == '') { 
			return false;
		}
		else {
			$eregi = preg_replace($regex, '', $email);
	}

	return empty($eregi) ? true : false;
} // end function ValidateEmail



error_reporting (E_ALL ^ E_NOTICE);

$post = (!empty($_POST)) ? true : false;

if($post) {

	$name = stripslashes($_POST['name']);
	$email_from = trim($_POST['email']);
	$comments = stripslashes($_POST['message']);

	$error = '';

	// Check name
	if(!$name) {
		if (!$error) $error .= '<ul style="list-style:none;">';
		$error .= '<li>' . __('Please enter your name.','wp3s') . '</li>';
	}
	/*if(!$last_name) {
		if (!$error) $error .= '<ul style="list-style:none;">';
		$error .= '<li>' . __('Please enter your last name.','wp3s') . '</li>';
	}*/

	// Check email

	if(!$email_from) {
		if (!$error) $error .= '<ul style="list-style:none;">';
		$error .= '<li>' . __('Please enter your email address.','wp3s') . '</li>';
	}

	if($email_from && !ValidateEmail($email_from)) {
		if (!$error) $error .= '<ul style="list-style:none;">';	
		$error .= '<li>' . __('Please enter a valid e-mail address.','wp3s') . '</li>';
	}
	
	/*if(!$email_subject) {
		if (!$error) $error .= '<ul style="list-style:none;">';
		$error .= '<li>' . __('Please enter a subject.','wp3s') . '</li>';
	}*/

	// Check message (length)

	if(!$comments) {
		if (!$error) $error .= '<p><ul>';	
		$error .= "<li>" . __('Please enter your message.','wp3s') . "</li>";
	}


	if(!$error) {
		
		//$message2 = $email . '\n\n' . $fname . ' ' . $lname . '\n\n' . $message;
		
		$email_message .= "Name: ".$name."\n";
		//$email_message .= "Last Name: ".$last_name."\n";
		$email_message .= "Email: ".$email_from."\n";
		$email_message .= "Subject: " . SUBJECT_EMAIL . "\n";
		$email_message .= "Message: ".$comments."\n";
		$email_to = CONTACT_EMAIL;
		$email_subject = __('Please contact me','wp3s');
     
		// create email headers
		$headers = 'From: '.$email_from."\r\n".
		'Reply-To: '.$email_from."\r\n" .
		'X-Mailer: PHP/' . phpversion();
		$mail = mail($email_to, $email_subject, $email_message, $headers); 
		
		if($mail) {
			echo 'thanks';
		} else {
			echo 'senderror' . __('Email was not sent. Error!','wp3s');
		}

	}
	else
	{
		$error .= '</ul>';
		echo 'errors' . $error;
	}
//echo 'here';
}