<?php
/**
 * Created by PhpStorm.
 * User: mirdu
 * Date: 5/31/2017
 * Time: 10:40 AM
 */
require_once(get_home_path().'PHPMailer/PHPMailerAutoload.php');
require_once(PLUG_DIR."/core/MailSettings.php");
include_once PLUG_DIR.'/utility/FlightUtility.php';
$mailsetters = array();
$mailsetters['host'] = 'smtp.gmail.com';
$mailsetters['smtpauth'] = true;
$mailsetters['smtpdebug'] = 0;
$mailsetters['port'] = '465';
$mailsetters['username'] = 'cdharshans@gmail.com';
$mailsetters['password'] = 'saraspathy@123';
$mailsetters['smtpsecure'] = 'ssl';
$mailsetters['from'] = 'cdharshans@gmail.com';
$mailsetters['fromname'] = 'Clickmybooking';
$mailsetters['adminmail'] = 'raj@clickmybooking.com';

$GLOBALS['mailsetters'] = $mailsetters;
//define ('mail_set','mailsetters');
function get_emailer()
{

global $mailsetters;
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $mailsetters['host'];
    $mail->SMTPAuth = $mailsetters['smtpauth'];
    $mail->SMTPDebug = $mailsetters['smtpdebug'];
    $mail->Port = $mailsetters['port'];
    $mail->Username = $mailsetters['username'];
    $mail->Password = $mailsetters['password'];
    $mail->SMTPSecure = $mailsetters['smtpsecure'];
    $mail->From = $mailsetters['from'];
    $mail->FromName = $mailsetters['fromname'];
  
    return $mail;
}