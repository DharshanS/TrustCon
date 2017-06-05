<?php
$html = '
<h1><a name="top"></a>mPDF</h1>
<h2>Basic HTML Example</h2>
This file demonstrates most of the HTML elements.
<h3>Heading 3</h3>
<h4>Heading 4</h4>
<h5>Heading 5</h5>
<h6>Heading 6</h6>
<p>P: Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>';

include("mpdf60/mpdf.php");
$mpdf=new mPDF('c'); 

$mpdf->WriteHTML($html);
//$mpdf->Output(); exit;
//$mpdf->Output('test.pdf','D'); exit;
$s = $mpdf->Output('','S');  
//echo $s;
//echo nl2br(htmlspecialchars($s));  exit;

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

$mail->addAddress("smithhappy123@gmail.com","John Smith"); 
$mail->addReplyTo($mailsetters['from']);
$mail->isHTML(true);  
$mail->AddStringAttachment($s, "cmb.pdf");

$mail->Subject = "Mail Fronm CMB";
$mail->Body    = "hi ther PFA";
if($mail->send()){
	echo "Send";
}else{
	echo 'Mailer Error: ' . $mail->ErrorInfo;
}
exit();
?>
