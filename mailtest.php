<?php
include_once('PHPMailer/PHPMailerAutoload.php'); 
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


$mail->addAddress('smithhappy123@gmail.com', 'John Smith'); 
$mail->addReplyTo('abdul@lozingle.com', 'Abdul Manashi');
$mail->isHTML(true);  
$mail->Subject = "Email Feature";
$mail->Body    = "Hi, it is abdul here";
$mail->send();