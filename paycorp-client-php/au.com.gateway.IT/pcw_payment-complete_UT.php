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

$post_id=$extradata[0]['post_id'];

$post_id=$_SESSION['post_id'];

if($post_id=='' || $post_id<1){
	die('<div align="center" style="padding: 10% 0;">Your session has expired, please start with a new search! UT</div>');
}


$booking_type=$extradata[0]['booking_type'];

$booking_reason_payment=$extradata[0]['booking_reason_payment']; // frre booking template


$txnReference=$completeResponse->getTxnReference();
$responseCode=$completeResponse->getResponseCode();
$responseText=$completeResponse->getResponseText();

require_once('../../PHPMailer/PHPMailerAutoload.php'); 
require_once("../../mailsettings.php");

require_once('../../mpdf60/mpdf.php'); 
$mpdf=new mPDF('c'); 


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
require_once("../../tconnect.php");
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if($booking_type=='air'){
// insert into wp_postmeta
$responsindb=$responseCode.' '.$responseText;
$SQL="INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES";
$SQL.="($post_id, 'TRANSACTION_ID', '$txnReference'),";
$SQL.="($post_id, 'TRANSACTION_RESPONSE_CODE', '$responsindb')";
$result = $mysqli->query($SQL);
//$mysqli->close();
}
$paymentsuccess=0;
if($responseCode=='00'){ // If the returned responseCode is 00, the order is successful! Otherwise, there's an issue. 
$paymentsuccess=1;
if($booking_type=='holiday'){
$SQL = " UPDATE `booking` SET payment_dt = '".date('Y-m-d')."' , payment_trn_id = '".$txnReference."' WHERE  `booking_id` = '".$_SESSION['Mytripid']."' ";	  // Mytripid
$result = $mysqli->query($SQL);
$mysqli->close();
}
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
unset($mail);


// test
//$paymentsuccess=1;

$AirSegment=array();
$priceresult=array();
if($paymentsuccess && $booking_type=='air'){
$booking_entries="";
$SQL="SELECT  `meta_value` FROM `wp_postmeta` WHERE `post_id`='".$post_id."' AND `meta_key`='booking_entries'";
if($result = $mysqli->query($SQL)){
$row = $result->fetch_assoc();
$booking_entries=$row['meta_value'];
$booking_entries=base64_decode($booking_entries);
$booking_entries=unserialize($booking_entries);	

	
}else{
	die('There was an error running the query [' . $mysqli->error . ']');
}

// sendmail to client
$mailto=$booking_entries['eml'];
$mailtoname=$booking_entries['first_name'][0].' '.$booking_entries['last_name'][0];


$airdata="";
$SQL="SELECT  `meta_value` FROM `wp_postmeta` WHERE `post_id`='".$post_id."' AND `meta_key`='AIRDATA'";
if($result = $mysqli->query($SQL)){
$row = $result->fetch_assoc();
$airdata=$row['meta_value'];
}else{
	die('There was an error running the query2 [' . $mysqli->error . ']');
}
if($airdata!=''){
$airdata=base64_decode($airdata);
$airdata=unserialize($airdata);	
}
$PNR="";
$SQL="SELECT  `meta_value` FROM `wp_postmeta` WHERE `post_id`='".$post_id."' AND `meta_key`='PNR'";
if($result = $mysqli->query($SQL)){
$row = $result->fetch_assoc();
$PNR=$row['meta_value'];
}else{
	die('There was an error running the query4 [' . $mysqli->error . ']');
}

$traveltype="";
$SQL="SELECT  `meta_value` FROM `wp_postmeta` WHERE `post_id`='".$post_id."' AND `meta_key`='traveltype'";
if($result = $mysqli->query($SQL)){
$row = $result->fetch_assoc();
$traveltype=$row['meta_value'];
}
$booking_reason="";
$SQL="SELECT  `meta_value` FROM `wp_postmeta` WHERE `post_id`='".$post_id."' AND `meta_key`='booking_reason'";
if($result = $mysqli->query($SQL)){
$row = $result->fetch_assoc();
$booking_reason=$row['meta_value'];
}

$post_author=0;
$SQL="SELECT `post_author` FROM `wp_posts` WHERE `ID`='".$post_id."'";
if($result = $mysqli->query($SQL)){
$row = $result->fetch_assoc();
$post_author=$row['post_author'];
}

$user_email='';
/* $SQL="SELECT `user_email` FROM ` `wp_users` ` WHERE `ID`='".$post_author."'";
if($result = $mysqli->query($SQL)){
$row = $result->fetch_assoc();
$user_email=$row['user_email'];
} */
$mysqli->close();
if($booking_reason=='Embassy Visa Purpose'){
	if(isset($airdata['AirSegment'])){
		$AirSegment=$airdata['AirSegment'];
		if(isset($airdata['AirPricingSolution']['AirPricingInfo'][0])){
			$priceresult=$airdata['AirPricingSolution']['AirPricingInfo'][0];
		}
	}
} // embassy
}// payment success


if(count($priceresult)){
$FareInfoRef=$priceresult['FareInfo']['0']['Key'];
$FareRuleKey=$priceresult['FareInfo']['0']['FareRuleKey'];
$BaggageRestriction=$priceresult['BaggageRestriction'];

$EquivalentBasePrice=$priceresult['EquivalentBasePrice'];
$currency=substr($EquivalentBasePrice,0,3);
$EquivalentBasePrice=substr($EquivalentBasePrice,3);
$EquivalentBasePrice=$currency."".number_format($EquivalentBasePrice);

$Taxes=$priceresult['Taxes'];
$currency=substr($Taxes,0,3);
$Taxes=substr($Taxes,3);
$Taxes=$currency."".number_format($Taxes);

$TotalPrice=$priceresult['TotalPrice'];
$currency=substr($TotalPrice,0,3);
$TotalPrice=substr($TotalPrice,3);
$TotalPrice=$currency."".number_format($TotalPrice);

// get destination reach data
$reachdata=array();
$temasdata=$AirSegment;
foreach($temasdata as $as){
	if($as['Group']==0){
		$tempdata=array(
		    'Key' => $as['Key'],
			'Group' => $as['Group'],
			'Carrier' => $as['Carrier'],
			'FlightNumber' => $as['FlightNumber'],
			'ProviderCode' => $as['ProviderCode'],
			'Origin' => $as['Origin'],
			'Destination' => $as['Destination'],
			'DepartureTime' => $as['DepartureTime'],
			'ArrivalTime' => $as['ArrivalTime']
		);
	  $reachdata=$tempdata;
	}
}
$reachdate="";
if(isset($reachdata['ArrivalTime'])){
	$reachdate=$reachdata['ArrivalTime'];
}
// get back date
$backdate="";
$backdata=array();
$temasdata=$AirSegment;
foreach($temasdata as $as){
	if($as['Group']==1){
		$tempdata=array(
		    'Key' => $as['Key'],
			'Group' => $as['Group'],
			'Carrier' => $as['Carrier'],
			'FlightNumber' => $as['FlightNumber'],
			'ProviderCode' => $as['ProviderCode'],
			'Origin' => $as['Origin'],
			'Destination' => $as['Destination'],
			'DepartureTime' => $as['DepartureTime'],
			'ArrivalTime' => $as['ArrivalTime']
		);
	  $backdata=$tempdata;
	  break;
	}
}
if(isset($backdata['DepartureTime'])){
	$backdate=$backdata['DepartureTime'];
}


$BI=$priceresult['BookingInfo'][0];
$segmentref=$BI['SegmentRef'];
$skey=getSegmentKey($segmentref,$AirSegment);
$segmentdtls=$AirSegment[$skey];

// only departure leg
//if($segmentdtls['Group']==0){
$thisCarrier=$segmentdtls['Carrier'];
$thisairlinename=$airlines[$thisCarrier];
$FlightNumber=$segmentdtls['FlightNumber'];
$aircraft=$segmentdtls['Equipment'];
//$aircraft="Airbus A330-300"
$airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';

$depttimes[$n]=$segmentdtls['DepartureTime'];
$arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
$t1=$arrivaltimes[$n];	
$t2=$depttimes[$n];	
$duration=getTimeDiff($t1,$t2);

// last leg
$destination=$searchdata['to_city'];

$pdfhtml=''.$bookingdate.',<br><br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_name']).',<br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_company']).',<br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_address']).'<br><br><br>'; 


$pdfhtml.='Dear Sir/ Madam,<br><br>';
$pdfhtml.='<u><strong>Letter Of Confirmation</strong></u><br><br>';
$pdfhtml.='This is to keep infrom Bellow under mentation passanger is flying '.$destination.'  on '.date("d F, Y",strtotime($reachdate)).'';
if($traveltype=='roundtrip'){
$pdfhtml.=' and returned on '.date("d F, Y",strtotime($backdate)).'';	
}
$pdfhtml.='<br><br>';
$pdfhtml.='Booking and passnger details given bellow<br>';
foreach($booking_entries['title'] as $k=>$v){
$pdfhtml.=''.$booking_entries['title'][$k].' '.$booking_entries['first_name'][$k].' '.$booking_entries['last_name'][$k].'&nbsp;&nbsp;&nbsp;&nbsp;PPT No.: '.$booking_entries['passport_no'][$k].'<br>';
}

$pdfhtml.='PNR : <strong>'.$PNR.'</strong><br>';
$pdfhtml.='<br>';
$pdfhtml.='<table cellpadding="10" cellspacing="0" border="0" bordercolor="#ccc" >
            <tr>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Flight</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Aircraft</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Departure</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Arrival</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Duration</th>
                <th style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Class Of Service</th>
            </tr>';
foreach($priceresult['BookingInfo'] as $BI){
$segmentref=$BI['SegmentRef'];
$skey=getSegmentKey($segmentref,$AirSegment);
$segmentdtls=$AirSegment[$skey];
$thisCarrier=$segmentdtls['Carrier'];
$thisairlinename=$airlines[$thisCarrier];
$FlightNumber=$segmentdtls['FlightNumber'];
$aircraft=$segmentdtls['Equipment'];
$airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';
$depttimes[$n]=$segmentdtls['DepartureTime'];
$arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
$t1=$arrivaltimes[$n];	
$t2=$depttimes[$n];	
$duration=getTimeDiff($t1,$t2);
$pdfhtml.='<tr>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$thisCarrier.' '.$FlightNumber.' <img src="http://clickmybooking.com/airimages/'.$thisCarrier.'.GIF'.'" /> '.$thisairlinename.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">Aircraft '.$aircraft.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$airportscity[$segmentdtls['Origin']].' ('.$segmentdtls['Origin'].') '.date("l, d M",strtotime($segmentdtls['DepartureTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['DepartureTime'])).'<br />'.$airportsname[$segmentdtls['Origin']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$airportscity[$segmentdtls['Destination']].' ('.$segmentdtls['Destination'].') '.date("l, d M",strtotime($segmentdtls['ArrivalTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['ArrivalTime'])).' <br />'.$airportsname[$segmentdtls['Destination']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$duration.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$BI['CabinClass'].' '.$BI['BookingCode'].'</td>
		</tr>';
}
$pdfhtml.='</table>';
$pdfhtml.='<p>Thanking You,<br /> Yours Sincerely,</p>
        <p>&nbsp;</p>
        <p>.............................................
        <br/>Manager</p>';
$pdfhtml.='<br>';
$pdfhtml.='<em>';
$pdfhtml.='You can get a print out of confirmation letter from your personal dashboard.';
$pdfhtml.='<br>';
$pdfhtml.='We are pleased to provide further information on request and trust that you would contact on us to fulfill your order, which will receive our prompt and keen attention.';
$pdfhtml.='<br>';
$pdfhtml.='</em>';
$pdfhtml.='<a href="http://www.clickmybooking.com">www.clickmybooking.com</a>';
$pdfhtml.='<br>';
$pdfhtml.='<br>';
$pdfhtml.='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:20px 0; border-top: 3px solid #4EDBFF ; color: #0000A6; ">
	<tr>
		<td align="center" valign="top">Head Office: 76/A, New Town, Digana, Rajawella, Kandy.<br>
		Tel: +94 812 203050, +94 812 227777 | Fax: +94 812 220103 | Mob: +94 777 776535<br>
		City Office: 07, D.S. Senanayake Veediya, Kandy - Sri Lanka.<br>
		08, Bus Stand, Commercial Building, Bandarawela - Sri Lanka. Tel: +94 57 2230555/ 131<br>
		E-mail: meera 1958@yahoo.co.in | www.meeratravels.lk</td>
	</tr>
</table>
';

$mailsubject='Clickmybooking - Regarding Booking Of Air Ticket';
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

// pdf attachment
$mpdf->WriteHTML($pdfhtml);
$s = $mpdf->Output('','S');  
$mail2->AddStringAttachment($s, $mailsubject.".pdf");
$mail2->addAddress($mailto, $mailtoname); 
$mail2->addReplyTo($mailsetters['from']);
$mail2->isHTML(true);
$mail2->Subject = $mailsubject;
$mail2->Body    = $pdfhtml;
$mail2->send();
unset($mail2);

}// count priceresult


if(isset($_SESSION['Mytripid'])){
 $_SESSION['Mytripid']=array();
 unset($_SESSION['Mytripid']);
}
if(isset($_SESSION['post_id'])){
 $_SESSION['post_id']=array();
 unset($_SESSION['post_id']);
}
// after this need redirection to  success page
function getTimeDiff($d1,$d2){
	$date1 = new DateTime($d1);
	$date2 = new DateTime($d2);	
	$diff = $date2->diff($date1);
	$h = $diff->h;
	$h = $h + ($diff->days*24);	
	$m=$diff->i;

	$ret='';
	if($h)$ret.=$h;
	if($h==1)$ret.= "hr ";
	else if($h>1)$ret.= "hrs ";
	if($m)$ret.=$m;
	if($m==1)$ret.= "min";
	else if($m>1)$ret.= "mins";
	return $ret;
}
function getSegmentKey($ref,$airsegments){
	foreach ($airsegments as $k => $v) {
       if ($v['Key'] === $ref) {
           return $k;
       }
   }
   return null;
}
?>
