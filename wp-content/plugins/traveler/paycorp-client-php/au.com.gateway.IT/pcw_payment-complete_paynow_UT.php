<?php


$path=PLUG_DIR.'paycorp-client-php';
include  $path.'/au.com.gateway.client/GatewayClient.php';
include $path.'/au.com.gateway.client.config/ClientConfig.php';
include $path.'/au.com.gateway.client.component/RequestHeader.php';
include $path.'/au.com.gateway.client.component/CreditCard.php';
include $path.'/au.com.gateway.client.component/TransactionAmount.php';
include $path.'/au.com.gateway.client.component/Redirect.php';
include $path.'/au.com.gateway.client.facade/BaseFacade.php';
include $path.'/au.com.gateway.client.facade/Payment.php';
include $path.'/au.com.gateway.client.root/PaycorpRequest.php';
include $path.'/au.com.gateway.client.root/PaycorpResponse.php';
include $path.'/au.com.gateway.client.payment/PaymentCompleteRequest.php';
include $path.'/au.com.gateway.client.payment/PaymentCompleteResponse.php';
include $path.'/au.com.gateway.client.utils/IJsonHelper.php';
include $path.'/au.com.gateway.client.helpers/PaymentCompleteJsonHelper.php';
include $path.'/au.com.gateway.client.utils/HmacUtils.php';
include $path.'/au.com.gateway.client.utils/CommonUtils.php';
include $path.'/au.com.gateway.client.utils/RestClient.php';
include $path.'/au.com.gateway.client.enums/TransactionType.php';
include $path.'/au.com.gateway.client.enums/Version.php';
include $path.'/au.com.gateway.client.enums/Operation.php';
include $path.'/au.com.gateway.client.facade/Vault.php';
include $path.'/au.com.gateway.client.facade/Report.php';
include $path.'/au.com.gateway.client.facade/AmexWallet.php';
include PLUG_DIR.'service/ReservationRequest.php';
include_once (PLUG_DIR . '/service/dumpReservation.php');

//print_r($wp);
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

//error_log(" PayNow Request --->". print_r($completeRequest,true));
$response=init_reservation();

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
//error_log("Extra Data --->".print_r($extradata,true));
$post_id=$extradata[0]['post_id'];

//if($post_id=='' || $post_id<1){
//	die('<div align="center" style="padding: 10% 0;">Your session has expired, please start with a new search!!!!!</div>');
//}





$txnReference=$completeResponse->getTxnReference();
$responseCode=$completeResponse->getResponseCode();
$responseText=$completeResponse->getResponseText();




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
// echo '<div align="center" style="padding: 10% 0;">$responseText</div>';
 echo '<div align="center" style="padding: 10% 0;">'.$responsindb.'</div>';
}
email_send_after_reservation_to_admin($completeResponse);



?>


