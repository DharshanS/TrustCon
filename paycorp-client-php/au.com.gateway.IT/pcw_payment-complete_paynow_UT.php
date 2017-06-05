<?php session_start(); ?>
<?php include '../au.com.gateway.client/GatewayClient.php'; ?>
<?php include '../au.com.gateway.client.config/ClientConfig.php'; ?>
<?php include '../au.com.gateway.client.component/RequestHeader.php'; ?>
<?php include '../au.com.gateway.client.component/CreditCard.php'; ?>
<?php include '../au.com.gateway.client.component/TransactionAmount.php'; ?>
<?php include '../au.com.gateway.client.component/Redirect.php'; ?>
<?php include '../au.com.gateway.client.facade/BaseFacade.php'; ?>
<?php include '../au.com.gateway.client.facade/Payment.php'; ?>
<?php include '../au.com.gateway.client.root/PaycorpRequest.php'; ?>
<?php include '../au.com.gateway.client.root/PaycorpResponse.php'; ?>
<?php include '../au.com.gateway.client.payment/PaymentCompleteRequest.php'; ?>
<?php include '../au.com.gateway.client.payment/PaymentCompleteResponse.php'; ?>
<?php include '../au.com.gateway.client.utils/IJsonHelper.php'; ?>
<?php include '../au.com.gateway.client.helpers/PaymentCompleteJsonHelper.php'; ?>
<?php include '../au.com.gateway.client.utils/HmacUtils.php'; ?>
<?php include '../au.com.gateway.client.utils/CommonUtils.php'; ?>
<?php include '../au.com.gateway.client.utils/RestClient.php'; ?>
<?php include '../au.com.gateway.client.enums/TransactionType.php'; ?>
<?php include '../au.com.gateway.client.enums/Version.php'; ?>
<?php include '../au.com.gateway.client.enums/Operation.php'; ?>
<?php include '../au.com.gateway.client.facade/Vault.php'; ?>
<?php include '../au.com.gateway.client.facade/Report.php'; ?>
<?php include '../au.com.gateway.client.facade/AmexWallet.php'; ?>


<?php
date_default_timezone_set('Asia/Colombo');

//error_reporting(E_ALL);
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set('display_errors', 1);
/*------------------------------------------------------------------------------
STEP1: Build ClientConfig object
------------------------------------------------------------------------------*/
$ClientConfig = new ClientConfig();
$ClientConfig->setServiceEndpoint("https://sampath.paycorp.com.au/rest/service/proxy");
$ClientConfig->setAuthToken("0cfdb13c-bbc1-496b-b37e-44bd58577159");
$ClientConfig->setHmacSecret("e7vSNpCTREiorlmd");
$ClientConfig->setValidateOnly(FALSE);
/*------------------------------------------------------------------------------
STEP2: Build Client object
------------------------------------------------------------------------------*/

$Client = new GatewayClient($ClientConfig);
/*------------------------------------------------------------------------------
STEP3: Build PaymentCompleteRequest object
------------------------------------------------------------------------------*/
$completeRequest = new PaymentCompleteRequest();
$completeRequest->setClientId(14000327);
$completeRequest->setReqid($_GET['reqid']);

/*------------------------------------------------------------------------------
STEP4: Process PaymentCompleteRequest object
------------------------------------------------------------------------------*/

error_log(" PayNow Request --->". print_r($completeRequest,true));
sendPost();

$completeResponse = $Client->payment()->complete($completeRequest);

/*------------------------------------------------------------------------------
STEP5: Process PaymentCompleteResponse object
------------------------------------------------------------------------------*/
//echo '<br><br>PCW Payment-Complete Respopnse: ----------------------------------';
//echo '<br>Txn Reference : ' . $completeResponse->getTxnReference();
//echo '<br>Response Code : ' . $completeResponse->getResponseCode();
//echo '<br>Response Text : ' . $completeResponse->getResponseText();
//echo '<br>Settlement Date : ' . $completeResponse->getSettlementDate();
//echo '<br>Auth Code : ' . $completeResponse->getAuthCode();
//echo '<br>Token : ' . $completeResponse->getToken();
//echo '<br>Token Response Text: ' . $completeResponse->getTokenResponseText();
//echo '<br>----------------------------------------------------------------------';
// Code below by Abdul Manashi @lozingle.com
$extradata=$completeResponse->getExtraData();
error_log("Extra Data --->".print_r($extradata,true));
$post_id=$extradata[0]['post_id'];

if($post_id=='' || $post_id<1){
	die('<div align="center" style="padding: 10% 0;">Your session has expired, please start with a new search!!!!!</div>');
}



$txnReference=$completeResponse->getTxnReference();
$responseCode=$completeResponse->getResponseCode();
$responseText=$completeResponse->getResponseText();

require_once('../../PHPMailer/PHPMailerAutoload.php'); 
require_once("../../mailsettings.php");
$adminmail=$mailsetters['adminmail'];
$mailsubject="PCW Payment-Complete Respopnse:";

$mailhtml='Txn Reference : ' . $completeResponse->getTxnReference().'';
$mailhtml.='<br>Response Code : ' . $completeResponse->getResponseCode().'';
$mailhtml.='<br>Response Text : ' . $completeResponse->getResponseText().'';
$mailhtml.='<br>Settlement Date : ' . $completeResponse->getSettlementDate().'';
$mailhtml.='<br>Auth Code : ' . $completeResponse->getAuthCode().'';
$mailhtml.='<br>Token : ' . $completeResponse->getToken().'';
$mailhtml.='<br>Token Response Text: ' . $completeResponse->getTokenResponseText().'';

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

require_once("../../travelportsettings.php");


$db = new DBconnet();
$mysqli=$db->getDbConnetion();
// insert into wp_postmeta
$responsindb=$responseCode.' '.$responseText;
$SQL="INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES";
$SQL.="($post_id, 'TRANSACTION_ID', '$txnReference'),";
$SQL.="($post_id, 'TRANSACTION_RESPONSE_CODE', '$responsindb')";
$result = $mysqli->query($SQL);

$SQL="UPDATE `wp_postmeta` SET `meta_value`='' WHERE `post_id`='".$post_id."' AND `meta_key`='booking_reason'";
$result2 = $mysqli->query($SQL);

$mysqli->close();

if($responseCode=='00'){ // If the returned responseCode is 00, the order is successful! Otherwise, there's an issue. 
echo '<div align="center" style="padding: 10% 0;">Transaction was processed successfully: '.$responseCode.' '.$responseText.'</div>';
}else if($responseCode=='91' || $responseCode=='92' || $responseCode=='A4' || $responseCode=='C5' || $responseCode=='T3' || $responseCode=='T4' || $responseCode=='U9' || $responseCode=='X1' || $responseCode=='X3' || $responseCode=='-1' || $responseCode=='C0' || $responseCode=='A6'){
echo '<div align="center" style="padding: 10% 0;">Banking Network Temporarily Unavailable -Please try again later.</div>';
}else{
 //echo '<div align="center" style="padding: 10% 0;">Payment Declined - Please try an alternative card.</div>';
 echo '<div align="center" style="padding: 10% 0;">'.$responsindb.'</div>';
}
$mail->addAddress($adminmail); 
$mail->addReplyTo($mailsetters['from']);
$mail->isHTML(true);
$mail->Subject = $mailsubject;
$mail->Body    = $mailhtml;
$mail->send();


function sendPost()
{

    echo "Iam Here";

}



?>


