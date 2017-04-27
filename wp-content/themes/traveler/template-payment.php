<?php
/*
Template Name: Payment
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 */
session_start();
date_default_timezone_set('Asia/Colombo');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pay_amount=0;
if(isset($_POST['pay_amount']) && $_POST['pay_amount']>0){ // come from template booking
$pay_amount=$_POST['pay_amount']; // already in cents
//$pay_amount=$pay_amount * 100; // amount should be in cents
//$pay_amount=1051; // test
}

$post_id=isset($_SESSION['post_id'])?$_SESSION['post_id']:0;
if($post_id=='' || $post_id<1){
	header("Location: http://www.clickmybooking.com");
	exit();
}


$post_id=0;
if(isset($_POST['post_id']) && $_POST['post_id']>0){ // come from template booking / create reservation in document root
$post_id=$_POST['post_id'];
}

$booking_type='';
if(isset($_POST['booking_type']) && $_POST['booking_type']!=''){ // come from template booking / create reservation in document root
$booking_type=$_POST['booking_type'];
}

$booking_reason_payment='';
if(isset($_POST['booking_reason_payment']) && $_POST['booking_reason_payment']!=''){ // come from template booking free
$booking_reason_payment=$_POST['booking_reason_payment'];
}

if(!$post_id){
	die("stop1");
	header("Location: http://www.clickmybooking.com");
	exit();
}

if(!$pay_amount){
	die("stop2");
	header("Location: http://www.clickmybooking.com");
	exit();
}

$path = get_home_path();
$path = $path.'/paycorp-client-php';
include $path.'/au.com.gateway.client/GatewayClient.php';
include $path.'/au.com.gateway.client.config/ClientConfig.php';
include $path.'/au.com.gateway.client.component/RequestHeader.php';
include $path.'/au.com.gateway.client.component/CreditCard.php';
include $path.'/au.com.gateway.client.component/TransactionAmount.php';
include $path.'/au.com.gateway.client.component/Redirect.php';
include $path.'/au.com.gateway.client.facade/BaseFacade.php';
include $path.'/au.com.gateway.client.facade/Payment.php';
include $path.'/au.com.gateway.client.payment/PaymentInitRequest.php';
include $path.'/au.com.gateway.client.payment/PaymentInitResponse.php';
include $path.'/au.com.gateway.client.root/PaycorpRequest.php';
include $path.'/au.com.gateway.client.utils/IJsonHelper.php';
include $path.'/au.com.gateway.client.helpers/PaymentInitJsonHelper.php';
include $path.'/au.com.gateway.client.utils/HmacUtils.php';
include $path.'/au.com.gateway.client.utils/CommonUtils.php';
include $path.'/au.com.gateway.client.utils/RestClient.php';
include $path.'/au.com.gateway.client.enums/TransactionType.php';
include $path.'/au.com.gateway.client.enums/Version.php';
include $path.'/au.com.gateway.client.enums/Operation.php';
include $path.'/au.com.gateway.client.facade/Vault.php';
include $path.'/au.com.gateway.client.facade/Report.php';
include $path.'/au.com.gateway.client.facade/AmexWallet.php';
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
STEP3: Build PaymentInitRequest object
------------------------------------------------------------------------------*/
$initRequest = new PaymentInitRequest();
$initRequest->setClientId(14000327);
$initRequest->setTransactionType(TransactionType::$PURCHASE);
//$initRequest->setTransactionType(TransactionType::$COMPLETION);
$initRequest->setClientRef("merchant_reference");
$initRequest->setComment("merchant_additional_data");
$initRequest->setTokenize(TRUE);
//$initRequest->setExtraData(array("ADD-KEY-1" => "ADD-VALUE-1", "ADD-KEY-2" => "ADD-VALUE-2"));
$initRequest->setExtraData(array("post_id" => $post_id,"booking_type"=>$booking_type,"booking_reason_payment"=>$booking_reason_payment));
// sets transaction-amounts details (all amounts are in cents)
$transactionAmount = new TransactionAmount();
$transactionAmount->setTotalAmount(0);
$transactionAmount->setServiceFeeAmount(0);
$transactionAmount->setPaymentAmount($pay_amount);
//$transactionAmount->setCurrency("AUD");
$transactionAmount->setCurrency("LKR");
$initRequest->setTransactionAmount($transactionAmount);
// sets redirect settings
$redirect = new Redirect();
$redirect->setReturnUrl("http://www.clickmybooking.com/paycorp-client-php/au.com.gateway.IT/pcw_payment-complete_UT.php");
$redirect->setReturnMethod("GET");
$initRequest->setRedirect($redirect);

/*------------------------------------------------------------------------------
STEP4: Process PaymentInitRequest object
------------------------------------------------------------------------------*/
$initResponse = $Client->payment()->init($initRequest);

/*------------------------------------------------------------------------------
STEP5: Extract PaymentInitResponse object
------------------------------------------------------------------------------*/
//echo '<br><br>PCW Payment-Init Respopnse: --------------------------------------';
//echo '<br>Req Id : ' . $initResponse->getReqid();
//echo '<br>Payment Page Url : ' . $initResponse->getPaymentPageUrl();
//echo '<br>Expire At : ' . $initResponse->getExpireAt();
//echo '<br>------------------------------------------------------------------<br>';

get_header();
?>
    <div class="container">
     <br>
        <div class="row mb20">
			 <iframe class="col-sm-12" style="border:none;" height="375px"   src="<?php echo $initResponse->getPaymentPageUrl(); ?>"></iframe>
        </div>
        <p><strong>We use the highest secure payment gateway to process your transaction.</strong></p>
      <div class="payment_mode-final">
	  <ul>
		<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/secure/meera-ky.jpg"></a></li>
		<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/secure/sampath-bnk.jpg"></a></li>
		<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/secure/paycorp.png"></a></li>
        <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/secure/amex-c.jpg"></a></li>
		<li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/secure/visa-v.jpg"></a></li>
        <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/secure/mster-c.jpg"></a></li>
        <?php /*?><li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/secure/dinner-club.jpg"></a></li><?php */?>
        <?php /*?><li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/secure/Secure.jpg"></a></li><?php */?>
	  </ul>
	</div>
    </div>
<style>
.payment_mode-final{ width: 100%; max-width: 1140px; margin:0 auto; }
.payment_mode-final li{width:16%; float: left; margin:0; padding:0; text-align: center; list-style: none; border: 1px solid #cccccc;}
.payment_mode-final li:nth-child(1){border-left: none;}
.payment_mode-final li:last-child{ border-right: none;}
</style>
<?php
get_footer();