<?php
session_start();
set_time_limit(0);
date_default_timezone_set('Asia/Colombo');

if(!isset($_SESSION['booking_entries'])){
echo 'Something gone wrong';
}
require_once("travelportsettings.php");
require_once("tconnect.php");
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

include("mpdf60/mpdf.php");
$mpdf=new mPDF('c'); 

require_once('PHPMailer/PHPMailerAutoload.php'); 
require_once("mailsettings.php");
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

/*$TARGETBRANCH = 'P7040105';
$CREDENTIALS = 'Universal API/uAPI6986631472-c8b85ad8:P+t2rJ7&M6';
$PCC='3OS2';
$Provider = '1G'; // Any provider you want to use like 1G/1P/1V/ACH*/

/*--------LIVE CREDENTIAL ------*/
$TARGETBRANCH = 'P2721018'; 
$CREDENTIALS = 'Universal API/uAPI4655248065-02590718:nA?9{c3YC8';
$PCC='3OS2';
$Provider = '1G'; 
/*--------------*/

//$endpoint='https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService'; // Production end point
//$endpoint='https://twsprofiler.travelport.com/Service/Default.ashx/AirService'; // Profiler end point

$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService'; // LIVE end point

$GENERATELOG=1; // 1 OR 0

## XXXX Added - For SMS Gateway Template integration
function smsTemplate( $mobile , $username , $pnr , $tag )
{
	
	switch ($tag)
	{
		case "confirm":
			$smstext = '';
			$smstext = "Dear $username,";
			$smstext .= "<br>Congratulation for Your Air Booking! <br>";
			$smstext .= "Your PNR number is $pnr<br<br>";
			$smstext .= "Thanks<br><br>";
			$smstext .= "www.clickmybooking.com";
			break;
		case "embassy":
			$smstext = '';
			$smstext = "Dear $username,";
			$smstext .= "<br>Congratulation for Your Air Booking! <br>";
			$smstext .= "Your PNR number is $pnr<br<br>";
			$smstext .= "Thanks<br><br>";
			$smstext .= "www.clickmybooking.com";
			break;
		case "enquery":
			$smstext = '';
			$smstext = "Dear $username,";
			$smstext .= "<br>Congratulation for Your Enquery! <br>";
			$smstext .= "Please check your mailbox";
			$smstext .= "Thanks<br><br>";
			$smstext .= "www.clickmybooking.com";
			break;	
	}
	## Sending SMS - Start
	?>
    <script>
    location.href = "https://cpsolutions.dialog.lk/index.php/cbs/sms/send?destination=<?php echo $mobile;?>&q=xxxxxxxxxxxxxxx&message=<?php echo $smstext;?>";
    </script>
    <?php
	## Sending SMS - End
}
## XXXX Added - For SMS Gateway Template integration

// parsed booking response data
$bookingdata='';
if(isset($_POST['p'])){
$bookingdata=$_POST['p'];	
$bookingdata=base64_decode($bookingdata);
$bookingdata=unserialize($bookingdata);	
}
$data=$bookingdata;

## XXXX add
/*echo '<pre>';
print_r($data);
die('data'); */
## XXXX add

// actually searched data
$searchdata='';
$searchdataoriginal='';
if(isset($_POST['s'])){
$searchdata=$_POST['s'];
$searchdataoriginal=$searchdata;	
$searchdata=base64_decode($searchdata);
$searchdata=unserialize($searchdata);	
}

$userid=0;
if(isset($_POST['r'])){
$u=$_POST['r'];	
$u=base64_decode($u);
$u=unserialize($u);	
$userid=$u[0];
}

$booking_type='';
if(isset($_POST['b'])){
$booking_type=$_POST['b'];	
}

$airlines=array();
if(isset($_SESSION['airlines'])){
  $airlines=$_SESSION['airlines'];	
}else{/*
$SQL="SELECT `iata`,`airline` FROM `airlines` WHERE `iata`!=''";
$results = $wpdb->get_results($SQL);
foreach($results as $result){
	$airlines[$result->iata]=$result->airline;
}
$_SESSION['airlines']=$airlines;
*/}

$airportscity=array();
$airportsname=array();
if(isset($_SESSION['airportscity'])){
  $airportscity=$_SESSION['airportscity'];	
  $airportsname=$_SESSION['airportsname'];	
}else{/*
$SQL="SELECT `iata_code`,`city`,`airport_name` FROM `airports`";
$results = $wpdb->get_results($SQL);
foreach($results as $result){
  $airportscity[$result->iata_code]=$result->city;
  $airportsname[$result->iata_code]=$result->airport_name;
}
$_SESSION['airportscity']=$airportscity;
$_SESSION['airportsname']=$airportsname;
*/}

// ticketing
$noofpassenger=0;
$error=array();
$S=$_SESSION['booking_entries'];
foreach($S['first_name'] as $k=>$f){
	if($f=='')$error[]='First Name Missing';
	if($S['last_name'][$k]=='')$error[]='Last Name Missing';
	if($S['dob'][$k]=='')$error[]='DOB Missing';
	if($S['title'][$k]=='')$error[]='Title Missing';
	$noofpassenger++;
}
if(count($error)){
	echo "ERROR: "; 
	echo implode(",",$error);
	echo "<pre>";
	print_r($S);
	die();
}

$AirReservationLocatorCode='';
$UniversalRecordLocatorCode='';
$AirPricingInfoRef='';
$bookingdate='';
$tickettype='';
$ticketdate='';	
$ticketdata='';

$AirPricingSolution=$data['AirPricingSolution'];
$AirSegment=$airsegments=$data['AirSegment'];
# XXXX added - Connection
$AirConnection=$data['airConnection'];
# XXXX added - Connection

$DepartureTime='';

$APRICESOL=$AirPricingSolution;

$grandtotalpricewithcurrency=$APRICESOL['ApproximateTotalPrice'];
$grandbasepricewithcurrency=$APRICESOL['ApproximateBasePrice'];
$grandtaxeswithcurrency=$APRICESOL['ApproximateTaxes'];

$currency=substr($grandtotalpricewithcurrency,0,3);
$grandtotalprice=substr($grandtotalpricewithcurrency,3);
$grandtotalpricewithcurrency=$currency."".number_format($grandtotalprice);

$grandbaseprice=substr($grandbasepricewithcurrency,3);
$grandbasepricewithcurrency=$currency."".number_format($grandbaseprice);

$grandtaxes=substr($grandtaxeswithcurrency,3);
$grandtaxeswithcurrency=$currency."".number_format($grandtaxes);


if($booking_type=='free' && $S['booking_reason']=='Fare Quotation'){ // no reservation
	
}else{ // make reservation
if(isset($_SESSION['ticketing']) && 1==2){
  $responseArray=$_SESSION['ticketing'];	
}else{

/*$TARGETBRANCH = 'P7040105';
$CREDENTIALS = 'Universal API/uAPI6986631472-c8b85ad8:P+t2rJ7&M6';
$PCC='3OS2';
$Provider = '1G'; // Any provider you want to use like 1G/1P/1V/ACH*/

//$APRICESOL=$AirPricingSolution;

# Revised on Jan 30, 2016
$soap = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:univ="http://www.travelport.com/schema/universal_v32_0" xmlns:com="http://www.travelport.com/schema/common_v32_0" xmlns:air="http://www.travelport.com/schema/air_v32_0">
	<soapenv:Header/>
	<soapenv:Body>
		<univ:AirCreateReservationReq xmlns:air="http://www.travelport.com/schema/air_v32_0" xmlns:common_v32_0="http://www.travelport.com/schema/common_v32_0" xmlns:univ="http://www.travelport.com/schema/universal_v32_0" xmlns:com="http://www.travelport.com/schema/common_v32_0" AuthorizedBy="user" RetainReservation="None" TargetBranch="'.$TARGETBRANCH.'" TraceId="1f01d4fc93d28f56c815663101c2e13c">
			<com:BillingPointOfSaleInfo xmlns:com="http://www.travelport.com/schema/common_v32_0" OriginApplication="UAPI"/>
			';
foreach($S['first_name'] as $k=>$f){
$p=$S;	
if($p['ptype'][$k] == 'ADT'){
   $ky = 1;
}else if($p['ptype'][$k] == 'CNN'){
   $ky = 2;
}else if($p['ptype'][$k] == 'INF'){
   $ky = 3;
}
$soap .= '      <com:BookingTraveler xmlns:com="http://www.travelport.com/schema/common_v32_0" Gender="'.$p['sex'][$k].'" Key="'.$ky.'" TravelerType="'.$p['ptype'][$k].'">
				<com:BookingTravelerName First="'.$p['first_name'][$k].'" Last="'.$p['last_name'][$k].'" Prefix="'.$p['title'][$k].'"/>
				';
if($p['ptype'][$k] == 'CNN'){				
$soap .= '      <com:NameRemark>
					<com:RemarkData>P-C'.$p['age'][$k].'</com:RemarkData>
				</com:NameRemark>
				';
}else if($p['ptype'][$k] == 'INF'){
## Date format conversion For INF
$inf_dt = DateTime::createFromFormat('Y-m-d', $p['dob'][$k]);
$inf_dob = $inf_dt->format('dMy');
$upTxt = strtoupper(substr($inf_dob , 2,3));
$fullTxt = substr($inf_dob,0,2).$upTxt.substr($inf_dob,5,2);
## Date format conversion For INF	
$soap .= '      <com:NameRemark>
					<com:RemarkData>'.$fullTxt.'</com:RemarkData>
				</com:NameRemark>
				';	
}
if($k==0){
$soap .= '     <com:Email EmailID="'.$p['eml'].'" Type="Home"/>
               ';	
}
if($p['passport_no'][$k] != '' && $p['passport_country'][$k] != '' && $p['passport_exp_date'][$k] != ''){
	## Date format conversion
	$exp_dt = DateTime::createFromFormat('Y-m-d', $p['passport_exp_date'][$k]);
	$exp_dt = $exp_dt->format('dMy');
	$birth_dt = DateTime::createFromFormat('Y-m-d', $p['dob'][$k]);
	$birth_dt = $birth_dt->format('dMy');
	## Date format conversion
	
## XXXX - Temporarily Commented - 02/02/2016
/*$soap .= '<com:SSR Carrier="'.$airsegments[$k]['Carrier'].'" FreeText="P/'.$p['passport_country'][$k].'/'.$p['passport_no'][$k].'/'.$p['birth_country'][$k].'/'.$birth_dt.'/'.$p['sex'][$k].'/'.$exp_dt.'/'.$p['first_name'][$k].'/'.$p['last_name'][$k].'" Key="'.md5($k+1).'" Status="HK" Type="DOCS" />';*/
	
} // End Passport Request

$soap .= '  </com:BookingTraveler>
			';
}

$soap .= '  <com:ContinuityCheckOverride Key="Blanks">Blanks</com:ContinuityCheckOverride>
			<com:AgencyContactInfo>
				<com:PhoneNumber Type="Agency" CountryCode="'.$p['country'][0].'" Number="'.$p['phone'][0].'" Location="DEL" AreaCode="11" Text="ITQ INDIA"/>
			</com:AgencyContactInfo>
			
			<com:FormOfPayment xmlns:com="http://www.travelport.com/schema/common_v32_0" Key="'.base64_encode("F1".date("YmdHis")).'" Type="Cash"/>
			<air:AirPricingSolution ApproximateBasePrice="'.$APRICESOL['ApproximateBasePrice'].'" ApproximateTaxes="'.$APRICESOL['ApproximateTaxes'].'" ApproximateTotalPrice="'.$APRICESOL['ApproximateTotalPrice'].'" BasePrice="'.$APRICESOL['BasePrice'].'" Key="'.base64_encode("APS".date("YmdHis")).'" QuoteDate="'.$APRICESOL['QuoteDate'].'" Taxes="'.$APRICESOL['Taxes'].'" TotalPrice="'.$APRICESOL['TotalPrice'].'">
			';

# XXXX Added - 11/02/2016
$cnt = 0;
# XXXX Added - 11/02/2016

foreach($airsegments as $k=>$ASG){	


if($ASG['LinkAvailability']!='' || $ASG['ParticipantLevel']!='' || $ASG['PolledAvailabilityOption']!=''){ //jan 25 2016

$soap .= '   <air:AirSegment ArrivalTime="'.$ASG['ArrivalTime'].'" AvailabilityDisplayType="'.$ASG['AvailabilityDisplayType'].'" AvailabilitySource="'.$ASG['AvailabilitySource'].'" Carrier="'.$ASG['Carrier'].'" ChangeOfPlane="'.$ASG['ChangeOfPlane'].'" ClassOfService="'.$ASG['ClassOfService'].'" DepartureTime="'.$ASG['DepartureTime'].'" Destination="'.$ASG['Destination'].'" Distance="'.$ASG['Distance'].'" Equipment="'.$ASG['Equipment'].'" FlightNumber="'.$ASG['FlightNumber'].'" FlightTime="'.$ASG['FlightTime'].'" Group="'.$ASG['Group'].'" Key="'.$ASG['Key'].'"  LinkAvailability="'.$ASG['LinkAvailability'].'" OptionalServicesIndicator="'.$ASG['OptionalServicesIndicator'].'" Origin="'.$ASG['Origin'].'" ParticipantLevel="'.$ASG['ParticipantLevel'].'" PolledAvailabilityOption="'.$ASG['PolledAvailabilityOption'].'" ProviderCode="'.$Provider.'"  TravelTime="'.$ASG['TravelTime'].'">
                <air:FlightDetails ArrivalTime="'.$ASG['ArrivalTime'].'" DepartureTime="'.$ASG['DepartureTime'].'" Destination="'.$ASG['Destination'].'" Distance="'.$ASG['Distance'].'" FlightTime="'.$ASG['FlightTime'].'" Key="'.base64_encode("FD".$k).'" Origin="'.$ASG['Origin'].'" TravelTime="'.$ASG['TravelTime'].'"/>';
				
}else{
	
 $soap .= '   <air:AirSegment ArrivalTime="'.$ASG['ArrivalTime'].'" AvailabilityDisplayType="'.$ASG['AvailabilityDisplayType'].'" AvailabilitySource="'.$ASG['AvailabilitySource'].'" Carrier="'.$ASG['Carrier'].'" ChangeOfPlane="'.$ASG['ChangeOfPlane'].'" ClassOfService="'.$ASG['ClassOfService'].'" DepartureTime="'.$ASG['DepartureTime'].'" Destination="'.$ASG['Destination'].'" Distance="'.$ASG['Distance'].'" Equipment="'.$ASG['Equipment'].'" FlightNumber="'.$ASG['FlightNumber'].'" FlightTime="'.$ASG['FlightTime'].'" Group="'.$ASG['Group'].'" Key="'.$ASG['Key'].'"   OptionalServicesIndicator="'.$ASG['OptionalServicesIndicator'].'" Origin="'.$ASG['Origin'].'"   ProviderCode="'.$Provider.'"  TravelTime="'.$ASG['TravelTime'].'">
                 <air:FlightDetails ArrivalTime="'.$ASG['ArrivalTime'].'" DepartureTime="'.$ASG['DepartureTime'].'" Destination="'.$ASG['Destination'].'" Distance="'.$ASG['Distance'].'" FlightTime="'.$ASG['FlightTime'].'" Key="'.base64_encode("FD".$k).'" Origin="'.$ASG['Origin'].'" TravelTime="'.$ASG['TravelTime'].'"/>';
			  
} // endif

if($AirConnection[$cnt] == 'true')
{
	$soap .= '<air:Connection />';
	$soap .= '</air:AirSegment>';
	$cnt++;
}else{
	 $soap .= '</air:AirSegment>';
	 $cnt++;
}
if($DepartureTime==''){
 $DepartureTime=$ASG['DepartureTime'];
}

}// airsegments  end foreach


$passengernum=0;
foreach($APRICESOL['AirPricingInfo'] as $k=>$api){	
$soap .= '   <air:AirPricingInfo ApproximateBasePrice="'.$api['ApproximateBasePrice'].'" ApproximateTaxes="'.$api['ApproximateTaxes'].'" ApproximateTotalPrice="'.$api['ApproximateTotalPrice'].'" BasePrice="'.$api['BasePrice'].'" ETicketability="'.$api['ETicketability'].'" IncludesVAT="'.$api['IncludesVAT'].'" Key="'.$api['Key'].'" LatestTicketingTime="'.$api['LatestTicketingTime'].'" PlatingCarrier="'.$api['PlatingCarrier'].'" PricingMethod="'.$api['PricingMethod'].'" ProviderCode="'.$Provider.'" Taxes="'.$api['Taxes'].'" TotalPrice="'.$api['TotalPrice'].'">
             ';
foreach($api['FareInfo'] as $finfo){
$soap .= '      <air:FareInfo Amount="'.$finfo['Amount'].'" DepartureDate="'.$finfo['DepartureDate'].'" '
        . 'Destination="'.$finfo['Destination'].'" EffectiveDate="'.$finfo['EffectiveDate'].'" '
        . 'FareBasis="'.$finfo['FareBasis'].'" Key="'.$finfo['Key'].'" NotValidAfter="'.$finfo['NotValidAfter'].'" NotValidBefore="'.$finfo['NotValidBefore'].'" Origin="'.$finfo['Origin'].'" PassengerTypeCode="'.$finfo['PassengerTypeCode'].'" >
                  <common_v32_0:Endorsement Value="NON-REF/NON-END"/>
				  <air:FareRuleKey FareInfoRef="'.$finfo['Key'].'" ProviderCode="'.$Provider.'">'.$finfo['FareRuleKey'].'</air:FareRuleKey>
                </air:FareInfo>';
}
foreach($api['BookingInfo'] as $binfo){
$soap .= '     <air:BookingInfo BookingCode="'.$binfo['BookingCode'].'" CabinClass="'.$binfo['CabinClass'].'" FareInfoRef="'.$binfo['FareInfoRef'].'" SegmentRef="'.$binfo['SegmentRef'].'"/>
               ';
}
foreach($api['TaxInfo'] as $tinfo){
$soap .= '     <air:TaxInfo Amount="'.$tinfo['Amount'].'" Category="'.$tinfo['Category'].'" Key="'.$tinfo['Key'].'"/>
               ';
}
$soap .= '     <air:FareCalc>'.$api['FareCalc'].'</air:FareCalc>
               ';
foreach($api['PassengerType'] as $k2=>$ptype){
	 if($ptype['Code'] == 'ADT'){
		 $Key = 1;
$soap .='      <air:PassengerType  Code="'.$ptype['Code'].'"  BookingTravelerRef="'.$Key.'"  />';
	 }else if($ptype['Code'] == 'CNN'){
		 $Key = 2;
		 $Age = 8;
$soap .='      <air:PassengerType Code="'.$ptype['Code'].'"  BookingTravelerRef="'.$Key.'"  Age="'.$Age.'" />';
	 }else if($ptype['Code'] == 'INF'){
		 $Key = 3;
		 $Age = 1;
		 $dobINF = $p['dob'][2]; // For Temporary Hardcode
$soap .='     <air:PassengerType Code="'.$ptype['Code'].'" DOB="'.$dobINF.'"  BookingTravelerRef="'.$Key.'" Age="'.$Age.'" PricePTCOnly="true"  />';
	 }


}// passenger type

$soap .= '</air:AirPricingInfo>';

} // airpricinginfo
			
/*$soap .= '  </air:AirPricingSolution>
            <com:ActionStatus xmlns:com="http://www.travelport.com/schema/common_v32_0" ProviderCode="'.$Provider.'" TicketDate="'.date("Y-m-d").'T'.date("H:i:s",strtotime("+1 hours")).'" Type="TAW" />
		</univ:AirCreateReservationReq>
	</soapenv:Body>
</soapenv:Envelope>';*/

$soap .= '  </air:AirPricingSolution>
            <com:ActionStatus xmlns:com="http://www.travelport.com/schema/common_v32_0" ProviderCode="'.$Provider.'" TicketDate="'.date("c",strtotime("+1 hours")).'" Type="TAW" />
		</univ:AirCreateReservationReq>
	</soapenv:Body>
</soapenv:Envelope>';


/*echo '<pre>';
echo $soap;*/ //die('gg');



$gzdata = gzencode($soap);
$auth = base64_encode("$CREDENTIALS"); 
$curl = curl_init ($endpoint); // defined at top

$header = array(
"Content-Type: text/xml;charset=UTF-8", 
"Content-Encoding: gzip", 
"Cache-Control: no-cache", 
"Pragma: no-cache", 
"SOAPAction: \"\"",
"Authorization: Basic $auth", 
"Content-length: ".strlen($gzdata),
); 
//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($curl, CURLOPT_POST, true ); 
curl_setopt($curl, CURLOPT_POSTFIELDS, $gzdata); 
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
$resp = curl_exec($curl);
curl_close ($curl);




$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
$xml = simplexml_load_string($xml);
$json = json_encode($xml);
$responseArray = json_decode($json,true);
$_SESSION['ticketing']=$responseArray;

}

$ticketdata=$responseArray;

if($GENERATELOG){ // generate log file
$file = 'AirCreateReservationReq.xml';
$filedata = $soap;
file_put_contents($file, $filedata);

$file1 = 'AirCreateReservationRsp.xml';
$filedata1 = $resp;
file_put_contents($file1, $filedata1);
}

# XXXX debug
/*echo '<pre>';
print_r($resp);
die('Resp');*/

/*echo '<pre>';
print_r($responseArray);
die('Reservation-Resp-Array');*/
# XXXX 

if(isset($responseArray['SOAPBody']['SOAPFault']['faultstring'])){  

	    ## XXXX - Start - Error Trapping  ( Known Error Issues = Now this portion is not working
		//echo '<pre>';
		$errorCD = array();
		$errorCD['faultstring'] = $responseArray['SOAPBody']['SOAPFault']['faultstring'];
		//print_r($data['errorCD']); die;
		
		$errTrap = explode(" " , $data['errorCD']);
		//echo $errTrap[1]; 
		
		$Errordis = '';
		switch ($errTrap[1]) 
		{
			case ":7:519:" : $Errordis = "Seat Not Available!"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			default  	   : $Errordis = "Unable to be determined"; 
		}
        
		if($Errordis != '')
		{
		?>
        <?php /*?><link rel='stylesheet' href='http://www.clickmybooking.com/wp-content/themes/traveler/css/jAlert-v3.css'>
        <script src='http://www.clickmybooking.com/wp-content/themes/traveler/js/jquery-1.11.3.min.js'></script>
        <script src='http://www.clickmybooking.com/wp-content/themes/traveler/js/jAlert-v3.js'></script>
        <script src='http://www.clickmybooking.com/wp-content/themes/traveler/js/jAlert-functions.js'></script>
        <script>
            $(function(){
                
                var errMsg = "Fault Code:- <?php echo $responseArray['SOAPBody']['SOAPFault']['faultcode'];?><br>Description:- <?php echo $responseArray['SOAPBody']['SOAPFault']['detail']['ErrorInfo']['Description'];?><br><br><strong><font style='color:red'>FaultString:- <?php echo $responseArray['SOAPBody']['SOAPFault']['faultstring'];?></font></strong>";
				errorAlert(errMsg+"<br><font style='color:red'>( Will Redirect in 20sec )</font>");
            });
        </script><?php */?>
        <?php
			# Not to go further
			//die;
		}
		## XXXX - End - Error Trapping
}

if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['@attributes']['LocatorCode'])){
$AirReservationLocatorCode=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['@attributes']['LocatorCode'];
$bookingdate=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['@attributes']['CreateDate'];
//echo 'AirReservationLocatorCode='.$AirReservationLocatorCode;  die;
}

if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['@attributes']['LocatorCode'])){
$UniversalRecordLocatorCode=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['@attributes']['LocatorCode'];
//echo 'UniversalRecordLocatorCode='.$UniversalRecordLocatorCode; die;
}

$ProviderReservationInfoLocatorCode='';
if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['universalProviderReservationInfo']['@attributes']['LocatorCode'])){
$ProviderReservationInfoLocatorCode=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['universalProviderReservationInfo']['@attributes']['LocatorCode'];
//echo 'ProviderReservationInfoLocatorCode='.$ProviderReservationInfoLocatorCode;  die;
}


if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['airAirPricingInfo']['@attributes']['Key']))
$AirPricingInfoRef=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['airAirPricingInfo']['@attributes']['Key'];
	
$_SESSION['UniversalRecordLocatorCode']=$UniversalRecordLocatorCode;	
$_SESSION['AirReservationLocatorCode']=$AirReservationLocatorCode;

$tickettype=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['common_v31_0ActionStatus']['@attributes']['Type'];
$ticketdate=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['common_v31_0ActionStatus']['@attributes']['TicketDate'];	

}// booking type


$ptypefull=array('ADT'=>'Adult','CNN'=>'Child','INF'=>'Infant');

// OLD  ( XXXX - Commented - 16/2/16 )
//$totalprice=$data['AirPricingSolution']['TotalPrice'];
# XXXX - revised - 16/2/16
if(isset($APRICESOL['AirPricingInfo'][0]['TotalPrice']))
{
	$totalprice=$APRICESOL['AirPricingInfo'][0]['TotalPrice'];
}elseif(isset($data['AirPricingSolution']['TotalPrice'])){
	$totalprice=$data['AirPricingSolution']['TotalPrice'];
}
# XXXX - revised - 16/2/16

$currency=substr($totalprice,0,3);
$totalprice=substr($totalprice,3);
$totalprice=$currency."".number_format($totalprice);
			
$taxes=$data['AirPricingSolution']['Taxes'];
$currency=substr($taxes,0,3);
$taxes=substr($taxes,3);
$taxes=$currency."".number_format($taxes);

$pdata=array();
foreach($data['AirPricingSolution']['AirPricingInfo'] as $aps){
	foreach($aps['PassengerType'] as $p){
		if(!isset($pdata[$p['Code']])){
			$currency=substr($aps['TotalPrice'],0,3);
            $amnt=substr($aps['TotalPrice'],3);
			$pdata[$p['Code']]=array('head'=>1,'amount'=>$currency."".number_format($amnt));
		}else if(isset($pdata[$p['Code']])){
			$h=$pdata[$p['Code']]['head'] + 1;
			$pdata[$p['Code']]['head']=$h;
			$currency=substr($aps['TotalPrice'],0,3);
            $amnt=substr($aps['TotalPrice'],3);
			$pdata[$p['Code']]['amount']=$currency."".number_format($amnt * $h);
		}
	}
	
}
$ptypedata=array();
if(isset($pdata['ADT']))$ptypedata['ADULT']=$pdata['ADT'];
if(isset($pdata['CNN']))$ptypedata['CHILD']=$pdata['CNN'];
if(isset($pdata['INF']))$ptypedata['INFANT']=$pdata['INF'];

$priceresult=array();
$baggageallow=array();
$airsegments=$data['AirSegment'];
if(isset($data['AirPricingSolution']['AirPricingInfo'][0])){
	$priceresult=$data['AirPricingSolution']['AirPricingInfo'][0];
	$BaggageRestriction=$data['AirPricingSolution']['AirPricingInfo'][0]['BaggageRestriction'];
	$baggageallow=processBaggageAllow($data['AirPricingSolution']['AirPricingInfo']);
}


// get destination reach data
$reachdata=array();
$temasdata=$airsegments;
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
$temasdata=$airsegments;
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




$fromcity='';
$tocity='';
if(isset($searchdata['from_city'])){
$fromcity=$searchdata['from_city'];	
}else if(isset($searchdata['from_city'][0])){
$fromcity=$searchdata['from_city'][0];		
}

if(isset($searchdata['to_city'])){
$tocity=$searchdata['to_city'];	
}else if(isset($searchdata['to_city'][0])){
$tocity=end($searchdata['to_city']);		
}

// Get PNR from PROVIDER LOCATORCODE

// test
//////$ProviderReservationInfoLocatorCode='BM67SM';

$soap_pnr ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:univ="http://www.travelport.com/schema/universal_v35_0" xmlns:com="http://www.travelport.com/schema/common_v35_0">
   <soapenv:Header/>
         <soapenv:Body>
      <univ:UniversalRecordRetrieveReq TargetBranch="'.$TARGETBRANCH.'" >
<com:BillingPointOfSaleInfo OriginApplication="UAPI" />
<univ:ProviderReservationInfo ProviderCode="1G" ProviderLocatorCode="'.$ProviderReservationInfoLocatorCode.'" />
</univ:UniversalRecordRetrieveReq>
   </soapenv:Body>
</soapenv:Envelope>';

$gzdata = gzencode($soap_pnr);
$auth = base64_encode("$CREDENTIALS"); 
$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/UniversalRecordService'; // PNR Record Retrieve request

$curlpnr = curl_init ($endpoint); 

$header = array(
"Content-Type: text/xml;charset=UTF-8", 
"Content-Encoding: gzip", 
"Cache-Control: no-cache", 
"Pragma: no-cache", 
"SOAPAction: \"\"",
"Authorization: Basic $auth", 
"Content-length: ".strlen($gzdata),
); 
//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
curl_setopt($curlpnr, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($curlpnr, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($curlpnr, CURLOPT_POST, true ); 
curl_setopt($curlpnr, CURLOPT_POSTFIELDS, $gzdata); 
curl_setopt($curlpnr, CURLOPT_HTTPHEADER, $header);
curl_setopt($curlpnr, CURLOPT_ENCODING, 'gzip');
curl_setopt($curlpnr, CURLOPT_RETURNTRANSFER, true); 
$resp_pnr = curl_exec($curlpnr);
curl_close ($curlpnr);


$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp_pnr);
$xml = simplexml_load_string($xml);
$json = json_encode($xml);
$responseArrayPNR = json_decode($json,true);


if($GENERATELOG){ // generate log file
$file_pnr = 'UniversalRecordRetrieveReq.xml';
$filedata_pnr = $soap_pnr;
file_put_contents($file_pnr, $filedata_pnr);

$file1_pnr = 'UniversalRecordRetrieveRsp.xml';
$filedata1_pnr = $resp_pnr;
file_put_contents($file1_pnr, $filedata1_pnr);
}

$SupplierLocatorCode='';
$universalUniversalRecord=array();
if(isset($responseArrayPNR['SOAPBody']['universalUniversalRecordRetrieveRsp']['universalUniversalRecord'])){
  $universalUniversalRecord=$responseArrayPNR['SOAPBody']['universalUniversalRecordRetrieveRsp']['universalUniversalRecord'];
}
if(isset($universalUniversalRecord['airAirReservation']['common_v35_0SupplierLocator']['@attributes']['SupplierLocatorCode'])){
  $SupplierLocatorCode=$universalUniversalRecord['airAirReservation']['common_v35_0SupplierLocator']['@attributes']['SupplierLocatorCode'];
}
$LocatorCode='';
if(isset($universalUniversalRecord['airAirReservation']['@attributes']['LocatorCode'])){
  $LocatorCode=$universalUniversalRecord['airAirReservation']['@attributes']['LocatorCode'];
}
//$PNR=$SupplierLocatorCode;
$PNR=$ProviderReservationInfoLocatorCode; // this is actual 
$TicketNumber='';

// retrive Ticket
// if we get DocumentSelect IssueElectronicTicket="true.  
//That means AirTicket(s) exists in this PNR. 
//In order to retrieve these Air Tickets we have to call AirDocumentRetriveReq

$IssueElectronicTicket='';
if(isset($universalUniversalRecord['airAirReservation']['airTicketingModifiers']['airDocumentSelect']['@attributes']['IssueElectronicTicket'])){
  $IssueElectronicTicket=$universalUniversalRecord['airAirReservation']['airTicketingModifiers']['airDocumentSelect']['@attributes']['IssueElectronicTicket'];
}
$ticketnumber='';
if($IssueElectronicTicket=='true'){
$soap_ticket ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:univ="http://www.travelport.com/schema/universal_v35_0" xmlns:com="http://www.travelport.com/schema/common_v35_0">
<soapenv:Header/>
<soapenv:Body>
 <AirRetrieveDocumentReq xmlns="http://www.travelport.com/schema/air_v35_0" xmlns:common="http://www.travelport.com/schema/common_v35_0" xmlns:rail="http://www.travelport.com/schema/rail_v35_0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  TargetBranch="'.$TARGETBRANCH.'">
    <common:BillingPointOfSaleInfo OriginApplication="UAPI" />
    <AirReservationLocatorCode>'.$LocatorCode.'</AirReservationLocatorCode>
 </AirRetrieveDocumentReq> 
</soapenv:Body>
</soapenv:Envelope>';
$gzdata = gzencode($soap_ticket);
$auth = base64_encode("$CREDENTIALS"); 
//$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirRetrieveDocumentService'; 
// AirRetrieveDocument Retrieve request

$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService'; 



$curlticket = curl_init ($endpoint); 

$header = array(
"Content-Type: text/xml;charset=UTF-8", 
"Content-Encoding: gzip", 
"Cache-Control: no-cache", 
"Pragma: no-cache", 
"SOAPAction: \"\"",
"Authorization: Basic $auth", 
"Content-length: ".strlen($gzdata),
); 
curl_setopt($curlticket, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($curlticket, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($curlticket, CURLOPT_POST, true ); 
curl_setopt($curlticket, CURLOPT_POSTFIELDS, $gzdata); 
curl_setopt($curlticket, CURLOPT_HTTPHEADER, $header);
curl_setopt($curlticket, CURLOPT_ENCODING, 'gzip');
curl_setopt($curlticket, CURLOPT_RETURNTRANSFER, true); 
$resp_ticket = curl_exec($curlticket);
curl_close ($curlticket);


$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp_ticket);
$xml = simplexml_load_string($xml);
$json = json_encode($xml);
$responseArrayTicket = json_decode($json,true);

if($GENERATELOG){ // generate log file
$file_ticket = 'AirRetrieveDocumentReq.xml';
$filedata_pnr = $soap_ticket;
file_put_contents($file_ticket, $filedata_pnr);

$file1_ticket = 'AirRetrieveDocumentRsp.xml';
$filedata1_ticket = $resp_ticket;
file_put_contents($file1_ticket, $filedata1_ticket);
}

if(isset($responseArrayTicket['SOAPBody']['airAirRetrieveDocumentRsp']['airETR']['airTicket']['@attributes']['TicketNumber'])){
$TicketNumber=$responseArrayTicket['SOAPBody']['airAirRetrieveDocumentRsp']['airETR']['airTicket']['@attributes']['TicketNumber'];	
}
	
}// issueticket true


// insert into wp post table

// discount
$disAmt=0;
foreach($priceresult['BookingInfo'] as $BI){
$segmentref=$BI['SegmentRef'];
$skey=getSegmentKey($segmentref,$AirSegment);
$segmentdtls=$airsegments[$skey];	
// only departure leg
if($segmentdtls['Group']==0){
$thisCarrier=$segmentdtls['Carrier'];
$thisairlinename=$airlines[$thisCarrier];
break;
}}
$SQL = 'SELECT discount FROM airlines WHERE airline = "'.$thisairlinename.'" ';
$result = $mysqli->query($SQL);
$row = $result->fetch_object();
$discount = $row->discount;
if($discount>0)
{
 $disAmt = ($grandbaseprice / 100) * $discount;
}
if($disAmt){
	$discountamount=$currency."".number_format($disAmt);
}else{
	$discountamount=0;
}
$netprice= $grandtotalprice - $disAmt;
$netpricewithcurrency=$currency."".number_format($netprice);


$from=$fromcity;
$to=$tocity;
$cost=$netpricewithcurrency;//$totalprice;
$departdate=$searchdata['start_date'];
$returndate=$searchdata['end_date'];
$traveltype=$searchdata['mode']; // oneway, return, multicity

$post_author=$userid;
$post_date=date("Y-m-d H:i:s");
$post_date_gmt=$post_date;
$post_content='';
$post_title='Air Booking';
$post_excerpt='';
$post_status='publish';
$comment_status='';
$ping_status='';
$post_password='';
$post_name='Air Booking';
$to_ping='';
$pinged='';
$post_modified=$post_date;
$post_modified_gmt=$post_date;
$post_content_filtered='None';
$post_parent=0;
$guid='';
$menu_order=0;
$post_type='st_order';
$post_mime_type='';
$comment_count=0;
// $city = $mysqli->real_escape_string($city);
$SQL="INSERT INTO `wp_posts` SET `post_author`='".$post_author."',`post_date`='".$post_date."',`post_date_gmt`='".$post_date_gmt."',`post_title`='".$post_title."'";
$SQL.=",`post_status`='".$post_status."',`post_name`='".$post_name."',`post_modified`='".$post_modified."',`post_modified_gmt`='".$post_modified_gmt."'";
$SQL.=",`post_type`='".$post_type."'";
$result = $mysqli->query($SQL);
$post_id=$mysqli->insert_id;
## XXXX Added - 16/10/2015
$_SESSION['post_id'] = $post_id;
## XXXX Added - 16/10/2015

$mytripid=tripIdGenerator();

## XXXX Added - 29/10/15
$_SESSION['Mytripid']=$mytripid;

$Sbooking_reason=$S['booking_reason'];

// insert into wp_postmeta
$SQL="INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES";
$SQL.="($post_id, 'TRIPID', '$mytripid'),";
$SQL.="($post_id, 'PNR', '$PNR'),";
$SQL.="($post_id, 'provider_code', '$ProviderReservationInfoLocatorCode'),";
$SQL.="($post_id, 'ticket_number', '$TicketNumber'),";
$SQL.="($post_id, 'AIRDATA', '".base64_encode(serialize($data))."'),";
$SQL.="($post_id, 'from', '$from'),";
$SQL.="($post_id, 'to', '$to'),";
$SQL.="($post_id, 'totalprice', '$grandtotalpricewithcurrency'),";
$SQL.="($post_id, 'baseprice', '$grandbasepricewithcurrency'),";
$SQL.="($post_id, 'taxes', '$grandtaxeswithcurrency'),";
$SQL.="($post_id, 'disamt', '$discountamount'),";
$SQL.="($post_id, 'cost', '$cost'),";
$SQL.="($post_id, 'departdate', '$departdate'),";
$SQL.="($post_id, 'departtime', '$DepartureTime'),";
$SQL.="($post_id, 'returndate', '$returndate'),";
$SQL.="($post_id, 'traveltype', '$traveltype'),";
$SQL.="($post_id, 'send_offers', '".$S['send_offers']."'),";
$SQL.="($post_id, 'contact_email', '".$S['eml']."'),";
$SQL.="($post_id, 'booking_reason', '".$S['booking_reason']."'),";
if($bookingdate!='')
$SQL.="($post_id, 'bookingdate', '".date("Y-m-d H:i:s",strtotime($bookingdate))."'),";
else 
$SQL.="($post_id, 'bookingdate', '".date("Y-m-d H:i:s")."'),";

$SQL.="($post_id, 'tickettype', '$tickettype'),";
if($ticketdate!='')
$SQL.="($post_id, 'ticketdate', '".date("Y-m-d H:i:s",strtotime($ticketdate))."'),";
else 
$SQL.="($post_id, 'ticketdate', ''),";
if($ticketdata!=''){
//$SQL.="($post_id, 'ticketdata', '".base64_encode(serialize($ticketdata))."'),";
$SQL.="($post_id, 'ticketdata', ''),";
}else{
$SQL.="($post_id, 'ticketdata', ''),";
}
$SQL.="($post_id, 'booking_entries', '".base64_encode(serialize($S))."'),";
$SQL.="($post_id, 'searchdata', '".$searchdataoriginal."')";
//die($SQL);
$result = $mysqli->query($SQL);


$ticket_number=$TicketNumber;
$DepartureTime=date("Y-m-d H:i:s",strtotime($DepartureTime));
// PNR history
$SQL="INSERT INTO `pnr_history` SET `post_author`='".$post_author."',`post_id`='".$post_id."',`PNR`='".$PNR."',`ticket_number`='".$ticket_number."',`departdate`='".$departdate."',`departtime`='".$DepartureTime."',  `returndate`='".$returndate."'";
$mysqli->query($SQL);

$FareInfoRef='';
$FareRuleKey='';
$BaggageRestriction='';
$EquivalentBasePrice='';
$currency='';
$Taxes='';
$TotalPrice='';
$n=0;
$depttimes=array();
$arrivaltimes=array();

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

// discount
foreach($priceresult['BookingInfo'] as $BI){
$segmentref=$BI['SegmentRef'];
$skey=getSegmentKey($segmentref,$AirSegment);
$segmentdtls=$airsegments[$skey];	
// only departure leg
if($segmentdtls['Group']==0){
$thisCarrier=$segmentdtls['Carrier'];
$thisairlinename=$airlines[$thisCarrier];
break;
}}
$SQL = 'SELECT discount FROM airlines WHERE airline = "'.$thisairlinename.'" ';
$result = $mysqli->query($SQL);
$row = $result->fetch_object();
$discount = $row->discount;
if($discount>0)
{
$disAmt = ($TotalPrice / 100) * $discount;
	
}else{
	$disAmt=0;
}
$TotalPrice= $TotalPrice - $disAmt;

$TotalPrice=$currency."".number_format($TotalPrice);
}

// sendmail to client
$mailto=$S['eml'];
$mailsubject='';
$mailhtml='';
$mailtoname=$S['first_name'][0].' '.$S['last_name'][0];
$title=$S['title'][0];
$passport_no=$S['passport_no'][0];

$letterhead_name=isset($S['letterhead_name'])?$S['letterhead_name']:'';
$letterhead_company=isset($S['letterhead_company'])?$S['letterhead_company']:'';
$letterhead_address=isset($S['letterhead_address'])?$S['letterhead_address']:'';


$mail->addAddress($mailto, $mailtoname); 
$mail->addReplyTo($mailsetters['from']);
$mail->isHTML(true);  

$pdfhtml="";

$mailhtml='<img src="http://www.clickmybooking.com/images/newsletter-logo.png" alt="Logo">';
$mailhtml.='<br>';
$mailhtml.='<br>';

if($S['booking_reason']=='Fare Quotation'){
$mailsubject='Clickmybooking - Regarding Quotation For Air Ticket';
$mailhtml.=''.$letterhead_name.',<br>';
$mailhtml.=''.$letterhead_company.',<br>';
$mailhtml.=''.$letterhead_address.'<br>';
$mailhtml.='<br>';
//$mailhtml.='Dear '.$mailtoname.',<br><br>';
$mailhtml.='Dear ,<br>';
$mailhtml.='<br>';
$mailhtml.='Thanking for your inquiry with us dated '.date("jS M, Y").',';
$mailhtml.='<br>';
$mailhtml.='<br>';
if($noofpassenger>1){
	$mailhtml.='Following are the Itenary for your search:';
}else{
	$mailhtml.='Following is the Itenary for your search:';
}
$mailhtml.='<br>';
$mailhtml.='<br>';
if(count($priceresult)){
$mailhtml.='<table cellpadding="10" cellspacing="0" border="0" bordercolor="#ccc" >
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
$skey=getSegmentKey($segmentref,$airsegments);
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
$mailhtml.='<tr>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$thisCarrier.' '.$FlightNumber.' <br><img src="http://clickmybooking.com/airimages/'.$thisCarrier.'.GIF'.'" /> <br>'.$thisairlinename.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">Aircraft '.$aircraft.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$airportscity[$segmentdtls['Origin']].' ('.$segmentdtls['Origin'].') '.date("l, d M",strtotime($segmentdtls['DepartureTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['DepartureTime'])).'<br />'.$airportsname[$segmentdtls['Origin']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$airportscity[$segmentdtls['Destination']].' ('.$segmentdtls['Destination'].') '.date("l, d M",strtotime($segmentdtls['ArrivalTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['ArrivalTime'])).' <br />'.$airportsname[$segmentdtls['Destination']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$duration.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$BI['CabinClass'].' '.$BI['BookingCode'].'</td>
		</tr>';
}
$mailhtml.='
<tr><td colspan="7" width="100%">
        <table  width="100%" cellpadding="10" cellspacing="0" border="0" bordercolor="#ccc" >
            <tr>
                <th width="70%" style="background: #2eade2; color: #ffffff;">Price Details</th>
                <th style="background: #2eade2; color: #ffffff;">Price</th>
            </tr>
            <tr>
                <td width="70%" style="border-bottom: 1px dotted #c4c4c4;">Base Fare</td>
                <td style="border-bottom: 1px dotted #c4c4c4;">'.$grandbasepricewithcurrency.'</td>
            </tr>
            <tr>
                <td width="70%">Taxes</td>
                <td>'.$grandtaxeswithcurrency.'</td>
            </tr>
			<tr>
                <td width="70%">Webfare Discount</td>
                <td>'.$discountamount.'</td>
            </tr>
            <tr>
                <td width="70%" style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">Total Price</td>
                <td style="border-bottom: 1px solid #2eade2;border-top: 1px solid #2eade2;">'.$netpricewithcurrency.'</td>
            </tr>
        </table>
</td></tr>
</table>';
}else{
$mailhtml.='We are sorry, we do not find any Itenary for your search. Please check your input and search again';
$pdfhtml.='We are sorry, we do not find any Itenary for your search. Please check your input and search again';
}
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='We are pleased to provide further information on request and trust that you would contact on us to fulfill your order, which will receive our prompt and keen attention.';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Thanking you,';
$mailhtml.='<br>';
$mailhtml.='<a href="http://www.clickmybooking.com">www.clickmybooking.com</a><br>';
$mailhtml.='<strong>OFFICE:</strong> No 07, D S Senanyake Street, Kandy<br>
<strong>Email:</strong> booking@clickmybooking.com<br>
<strong>Contact Number:</strong> 081-2227771';

}else if($S['booking_reason']=='Embassy Visa Purpose'){
$mailsubject='Clickmybooking - Regarding Booking Of Air Ticket';
$mailhtml.=''.date("jS M, Y").',<br><br>';
$mailhtml.=''.$letterhead_name.',<br>';
$mailhtml.=''.$letterhead_company.',<br>';
$mailhtml.=''.$letterhead_address.'<br><br><br>'; 
$mailhtml.='Dear Sir/ Madam,<br><br>';
$mailhtml.='<u><strong>Letter Of Confirmation</strong></u><br><br>';
//$mailhtml.='This is to keep infrom Bellow under mentation passanger is flying '.$tocity.' on '.date("d F, Y",strtotime($reachdate)).'';
//if($traveltype=='roundtrip'){
//$mailhtml.=' and returned on '.date("d F, Y",strtotime($backdate)).'';	
//}
$mailhtml.='This is to keep infrom that bellow under mentioned passanger/s is/are flying to the destination given below on on the date of '.date("d F, Y",strtotime($reachdate)).'';
$mailhtml.='<br><br>';
$mailhtml.='Relevent details are given bellow,<br>';
$mailhtml.='<table cellpadding="0" cellspacing="0" border="0" bordercolor="#ccc" >
            <tr>
                <td style="width:50%;"><strong><u>Name</u></strong></td>
                <td style=""><strong><u>Passport Number</u></strong></td>
            </tr>';
foreach($S['first_name'] as $k=>$v){
$mailhtml.='<tr>
                <td>'.$S['title'][$k].' '.$S['first_name'][$k].' '.$S['last_name'][$k].'</u></td>
                <td>'.$S['passport_no'][$k].'</td>
            </tr>';
}
$mailhtml.='</table>';
$mailhtml.='PNR : <strong>'.$ProviderReservationInfoLocatorCode.'</strong><br>';
$mailhtml.='<br>'; 
$mailhtml.='Flight information:';
$mailhtml.='<br><br>';

if(count($priceresult)){
$mailhtml.='<table cellpadding="10" cellspacing="0" border="0" bordercolor="#ccc" >
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
$skey=getSegmentKey($segmentref,$airsegments);
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
$mailhtml.='<tr>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$thisCarrier.' '.$FlightNumber.' <br><img src="http://clickmybooking.com/airimages/'.$thisCarrier.'.GIF'.'" /> <br>'.$thisairlinename.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">Aircraft '.$aircraft.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$airportscity[$segmentdtls['Origin']].' ('.$segmentdtls['Origin'].') '.date("l, d M",strtotime($segmentdtls['DepartureTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['DepartureTime'])).'<br />'.$airportsname[$segmentdtls['Origin']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$airportscity[$segmentdtls['Destination']].' ('.$segmentdtls['Destination'].') '.date("l, d M",strtotime($segmentdtls['ArrivalTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['ArrivalTime'])).' <br />'.$airportsname[$segmentdtls['Destination']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$duration.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$BI['CabinClass'].' '.$BI['BookingCode'].'</td>
		</tr>';
}
$mailhtml.='</table>';
}else{
$mailhtml.='We are sorry, we do not find any Itenary for your search. Please check your input and search again';
}
$mailhtml.='<br>';
$mailhtml.='Please be noted that this is computer generated print and only valid for 3days.';
$mailhtml.='<br><br><br>';
$mailhtml.='<a href="http://www.clickmybooking.com">www.clickmybooking.com</a><br>';
$mailhtml.='<strong>OFFICE:</strong> No 07, D S Senanyake Street, Kandy<br>
<strong>Email:</strong> booking@clickmybooking.com<br>
<strong>Contact Number:</strong> 081-2227771';

## XXXX Added for SMS integration
$myPhone = $S['country'][0].$S['phone'][0];
//smsTemplate($myPhone , $mailtoname , $PNR , 'embassy');
## XXXX Added for SMS integration
	
}else{
$mailsubject='Clickmybooking - Regarding Booking Of Air Ticket';
$mailhtml='<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><img src="http://www.clickmybooking.com/images/newsletter-logo.png" alt="Logo"></td>
                <td valign="top"><strong>Customer Care:</strong><br>
				<strong>Call :</strong> 0812 22 77 77<br>
				<strong>Email :</strong> support@clickmybooking.com</td>
            </tr>';
$mailhtml.='</table>';
$mailhtml.='Travel Itinerary (reservation copy)';
$mailhtml.='<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong>MyTrip ID :</strong>'.$mytripid.'<br>';
$mailhtml.='<strong>Airline PNR :</strong> '.$PNR.'';
$mailhtml.='     </td>
                <td valign="top"><strong>Booking Date :</strong> '.date("jS M, Y").'</td>
            </tr>';
$mailhtml.='</table>';
$mailhtml.='<br>';
$mailhtml.='<strong>Itinerary Details:</strong>';
$mailhtml.='<br><br>';
if(count($priceresult)){
$mailhtml.='<table cellpadding="10" cellspacing="0" border="0" bordercolor="#ccc" >
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
$skey=getSegmentKey($segmentref,$airsegments);
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
$mailhtml.='<tr>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$thisCarrier.' '.$FlightNumber.' <br /><img src="http://clickmybooking.com/airimages/'.$thisCarrier.'.GIF'.'" /><br />'.$thisairlinename.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">Aircraft '.$aircraft.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$airportscity[$segmentdtls['Origin']].' ('.$segmentdtls['Origin'].') '.date("l, d M",strtotime($segmentdtls['DepartureTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['DepartureTime'])).'<br />'.$airportsname[$segmentdtls['Origin']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$airportscity[$segmentdtls['Destination']].' ('.$segmentdtls['Destination'].') '.date("l, d M",strtotime($segmentdtls['ArrivalTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['ArrivalTime'])).' <br />'.$airportsname[$segmentdtls['Destination']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$duration.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;">'.$BI['CabinClass'].' '.$BI['BookingCode'].'</td>
		</tr>';
}
$mailhtml.='</table>';
}else{
$mailhtml.='We are sorry, we do not find any Itenary for your search. Please check your input and search again';
}
$mailhtml.='<em>*All times are local to airport</em>';
$mailhtml.='<br><br>';
$mailhtml.='<strong>Passenger Details:</strong>';
$mailhtml.='<br>';
$mailhtml.='<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong><u>Name</u></strong></td>
                <td style=""><strong><u>Baggage Allowed </u></strong></td>
            </tr>';
//foreach($booking_entries['title'] as $k=>$v){
foreach($S['ptype'] as $k=>$v){
$mailhtml.='<tr>
                <td><em>'.$ptypefull[$v].'</em><br>'.$S['title'][$k].' '.$S['first_name'][$k].' '.$S['last_name'][$k].'</u></td>';
if($v=='CHD')$v='CNN';
$mailhtml.='     <td>'.$baggageallow[$v].'</td>
            </tr>';
}
$mailhtml.='</table>';
$mailhtml.='<br>';
$mailhtml.='<strong>Additional Information:</strong>';
$mailhtml.='<br>';
$mailhtml.='&nbsp;&nbsp;&gt;This document can not be used as a travel document or an E-ticket under any circumstance.';
$mailhtml.='<br>';
$mailhtml.='&nbsp;&nbsp;&gt;The fare is subjected to change unless it is being ticketed.';
$mailhtml.='<br>';
$mailhtml.='&nbsp;&nbsp;&gt;You may have to submit details of your passport before issuing of tickets to certain destinations.';
$mailhtml.='<br>';
$mailhtml.='&nbsp;&nbsp;&gt;Our agents might contact you directly for verification purposes.';
$mailhtml.='<br>';
$mailhtml.='&nbsp;&nbsp;&gt;Authorities should be immediately if the name appearing in this document is different to that mentioned in the passport.';
$mailhtml.='<br>';
$mailhtml.='&nbsp;&nbsp;&gt;You are required to comprehend and settle the total flight fare before you could proceed to the issuing of the ticket.';
$mailhtml.='<br>';
$mailhtml.='&nbsp;&nbsp;&gt;Any alteration done to the special fares offered are subjected to a fee of penalty and cannot be refunded.';
$mailhtml.='<br>';
$mailhtml.='&nbsp;&nbsp;&gt;Please use the MyTrip ID when communicating with us.';
$mailhtml.='<br>';
$mailhtml.='&nbsp;&nbsp;&gt;Please use \'MYSKY ID\' to contact us.';
$mailhtml.='<br><br>';
$mailhtml.='<strong>OFFICE:</strong> No 07, D S Senanyake Street, Kandy<br>
<strong>Email:</strong> booking@clickmybooking.com<br>
<strong>Contact Number:</strong> 081-2227771';
## XXXX Added for SMS integration
$myPhone = $S['country'][0].$S['phone'][0];
//smsTemplate($myPhone , $mailtoname , $PNR , 'confirm');
## XXXX Added for SMS integration

	
}// else
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='<table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding:20px 0; border-top: 3px solid #4EDBFF ; color: #0000A6; ">
	<tr>
		<td align="center" valign="top">Head Office: 76/A, New Town, Digana, Rajawella, Kandy.<br>
		Tel: +94 812 203050, +94 812 227777 | Fax: +94 812 220103 | Mob: +94 777 776535<br>
		City Office: 07, D.S. Senanayake Veediya, Kandy - Sri Lanka.<br>
		08, Bus Stand, Commercial Building, Bandarawela - Sri Lanka. Tel: +94 57 2230555/ 131<br>
		E-mail: meera 1958@yahoo.co.in | www.meeratravels.lk</td>
	</tr>
</table>
';


// pdf attachment
$mpdf->WriteHTML($mailhtml);
$sout = $mpdf->Output('','S');  
$mail->AddStringAttachment($sout, $mailsubject.".pdf");

$mail->Subject = $mailsubject;
$mail->Body    = $mailhtml;
if($Sbooking_reason=='Embassy Visa Purpose'){
	
}else{
$mail->send();
}

$mail2->addAddress($adminmail); 
$mail2->addReplyTo($mailsetters['from']);
$mail2->isHTML(true);
$mail2->AddStringAttachment($s, $mailsubject.".pdf");
$mail2->Subject = $mailsubject;
$mail2->Body    = $mailhtml;
$mail2->send();  


//$meta_key
// meta_value
$html='';
/* $smsmessage='Test';
$dstphone='+919830748641';
$smsgateway='http://203.153.222.25:5000/sms/send_sms.php?username=meera&password=Meera2016&src=Meera&dst='.$dstphone.'&msg='.$smsmessage.'&dr=1';

$smsret=file_get_contents($smsgateway); */
?>
<?php	
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
$tPrice=$TotalPrice;
$currency=substr($TotalPrice,0,3);
$TotalPrice=substr($TotalPrice,3);
$TotalPrice=$currency."".number_format($TotalPrice);

// discount
foreach($priceresult['BookingInfo'] as $BI){
$segmentref=$BI['SegmentRef'];
$skey=getSegmentKey($segmentref,$AirSegment);
$segmentdtls=$airsegments[$skey];	
// only departure leg
if($segmentdtls['Group']==0){
$thisCarrier=$segmentdtls['Carrier'];
$thisairlinename=$airlines[$thisCarrier];
break;
}}
$res = 'SELECT discount FROM airlines WHERE airline = "'.$thisairlinename.'" ';
$result = $mysqli->query($res); //$wpdb->get_results($res);
$row = $result->fetch_object();
$discount = $row->discount;
if($discount>0)
{
//$disAmt = (substr($tPrice,3) / 100) * $discount;
$disAmt = ($grandbaseprice / 100) * $discount;
}else{
	$disAmt=0;
}
//$netprice=substr($tPrice,3) - $disAmt;
$netprice=$grandtotalprice - $disAmt;
// agent commission
/*$myRole =  get_user_role(); // Comming from functions.php
if(isset($myRole) && $myRole == 'agent')
{

	$res = 'SELECT * FROM wp_options WHERE option_name = "agent_commission" ';
	$result = $mysqli->query($res); //$wpdb->get_results($res);
	$commission = $result->option_value;
	$webfaireamt = (substr($tPrice,3) / 100) * $commission;
	
	$netprice=$netprice - $webfaireamt;
}*/

$netprice=$currency."".number_format($netprice);

// $result = $mysqli->query($SQL);
$mysqli->close();

?>
<div class="step" id="step1_after">
        <div class="booking_wrapp">
        <div class="wrapp">
          <div class="edit"><span></span></div>
          <div class="no"><span>1</span></div>
          <div class="row">
            <div class="col-sm-9">
              <div class="left_panel">
                <div class="row">
				<?php 
                $n=0;
                $depttimes=array();
                $arrivaltimes=array();
                foreach($priceresult['BookingInfo'] as $BI){
                $segmentref=$BI['SegmentRef'];
                $skey=getSegmentKey($segmentref,$airsegments);
                $segmentdtls=$airsegments[$skey];	
                $thisCarrier=$segmentdtls['Carrier'];
                $thisairlinename=$airlines[$thisCarrier];
                $FlightNumber=$segmentdtls['FlightNumber'];
                $aircraft=$segmentdtls['Equipment'];
                //$aircraft="Airbus A330-300"
                $airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
                if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';
                
                $depttimes[$n]=$segmentdtls['DepartureTime'];
                $arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
                ?>  
                  <div class="col-sm-12">
                    <ul>
                      <li><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" class="icn" /><span><?php echo $thisairlinename;?> <br />
                        <?php echo $thisCarrier;?> <?php echo $FlightNumber;?> <br />
                        Aircraft <?php echo $aircraft;?></span></li>
                      <li><span><?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?> <?php echo date("D, d M",strtotime($segmentdtls['DepartureTime']));?> <br />
                        <?php echo $airportscity[$segmentdtls['Origin']];?> (<?php echo $segmentdtls['Origin'];?>)</span></li>
                      <li>
                        <center>
                          <img src="images/booking_dets_oneway_right.png" /><br />
                          <span>Non-stop</span>
                        </center>
                      </li>
                      <li><span><?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?> <?php echo date("D, d M",strtotime($segmentdtls['ArrivalTime']));?> <br />
                        <?php echo $airportscity[$segmentdtls['Destination']];?> (<?php echo $segmentdtls['Destination'];?>)</span></li>
                      <li><span><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></span></li>
                    </ul>
                  </div>
<?php } ?>
                </div>
              </div>
            </div>
            <!--Left end-->

            <div class="col-sm-3">
              <div class="right_panel">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td align="left"><span>Base Fare :</span></td>
                    <td align="right"><span> <?php echo $grandbasepricewithcurrency;?></span></td>
                  </tr>
                  <tr>

                    <td align="left"><span>Taxes :</span></td>
                    <td align="right"><span> <?php echo $grandtaxeswithcurrency;?></span></td>
                  </tr>
                  <tr>
                    <td align="left"><span>Total Airfare :</span></td>
                    <td align="right"><span><?php echo $grandtotalpricewithcurrency;?></span></td>
                  </tr>
                  <tr>
                    <td align="left"><span>Webfare Discount :</span></td>
                    <td align="right"><span><?php echo $disAmt>0?$currency.number_format($disAmt):'0';?></span></td>
                  </tr>
                  <tr>
                    <td align="left"><span><strong>Total Price :</strong></span></td>
                    <td align="right"><span><strong><?php echo $netprice;?></strong></span></td>
                  </tr>
                  <?php /*?><tr>
                    <td align="left"><span>Cash price :</span></td>
                    <td align="right"><span><strong>207,736</strong></span></td>
                  </tr>
                  <tr>
                    <td align="left"><span>VISA Catds 5% Discounted Price :*</span></td>
                    <td align="right"><span> 198,216</span></td>
                  </tr><?php */?>
                </table>
              </div>
            </div>
            <!--Right end--> 
          </div>
        </div>
        </div>
      </div>
      <div class="step" id="step2_after">
       <div class="booking_wrapp">
        <div class="main_content">
          <div class="wrapp">
            <div class="edit"><span></span></div>
            <div class="no"><span>2</span></div>
            <div class="row">
              <div class="col-sm-12">
                <div class="gaping">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="7%"><img src="images/email_summary.png" class="mail" /></td>
                      <td width="93%"><span id="entered_email"><?php echo $_SESSION['booking_entries']['eml'];?></span></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
       </div>
      </div>
      <div class="step" id="step3_after">
        <div class="booking_wrapp">
            <div class="main_content">
              <div class="wrapp">
                <div class="edit"><span></span></div>
                <div class="no"><span>3</span></div>
                <div class="row" id="step3_after_content">
                <?php
				foreach($_SESSION['booking_entries']['first_name'] as $k=>$f){
				?>
                <div class="col-sm-12">
                    <div class="gaping traveller">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><img src="images/men.png" class="mail" /><span><?php echo $_SESSION['booking_entries']['first_name'][$k].' '.$_SESSION['booking_entries']['last_name'][$k];?></span></td>
                          <td><img src="images/baby.png" class="mail" /><span><?php echo $_SESSION['booking_entries']['dob'][$k];?></span></td>
                          <?php
						  if(isset($_SESSION['booking_entries']['phone'][$k])){
						  ?>
                          <td><img src="images/mobile.png" class="mail" /><span><?php echo $_SESSION['booking_entries']['country'][$k].'-'.$_SESSION['booking_entries']['phone'][$k];?></span></td> 
                          <?php }else{?>
                          <td><img src="images/mobile.png" class="mail" /><span></span></td>
                          <?php } ?>
                        </tr>
                      </table>
                    </div>
                  </div>
                <?php } ?>
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="step" id="step4_after">
        <div class="booking_wrapp">
           <div class="main_content">
              <div class="wrapp">
                <div class="edit"></div>
                <div class="no"><span>4</span></div>
                <div class="row">
                  <div class="col-sm-12">
                    <div class="gaping traveller">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="93%"><span class="bk">Your Booking is confirm.</span></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
<?php
}// count price list

function processFairInfo($FareInfo){
$fairinfo=array();
if(isset($FareInfo['@attributes'])){
	$data4=array(
		'Key'=> $FareInfo['@attributes']['Key'],
		'FareBasis'=> $FareInfo['@attributes']['FareBasis'],
		'PassengerTypeCode'=> $FareInfo['@attributes']['PassengerTypeCode'],
		'Origin'=> $FareInfo['@attributes']['Origin'],
		'Destination'=> $FareInfo['@attributes']['Destination'],
		'EffectiveDate'=> $FareInfo['@attributes']['EffectiveDate'],
		'DepartureDate'=> $FareInfo['@attributes']['DepartureDate'],
		'Amount'=> $FareInfo['@attributes']['Amount'],
		'NotValidBefore'=> $FareInfo['@attributes']['NotValidBefore'],
		'NotValidAfter'=> $FareInfo['@attributes']['NotValidAfter']
	);
	if(isset($FareInfo['airFareRuleKey'])){
		$data4['FareRuleKey']=$FareInfo['airFareRuleKey'];
	}
	$fairinfo[]=$data4;
}else if(isset($FareInfo[0]['@attributes'])){
	foreach($FareInfo as $FI){
		$data4=array(
			'Key'=> $FI['@attributes']['Key'],
			'FareBasis'=> $FI['@attributes']['FareBasis'],
			'PassengerTypeCode'=> $FI['@attributes']['PassengerTypeCode'],
			'Origin'=> $FI['@attributes']['Origin'],
			'Destination'=> $FI['@attributes']['Destination'],
			'EffectiveDate'=> $FI['@attributes']['EffectiveDate'],
			'DepartureDate'=> $FI['@attributes']['DepartureDate'],
			'Amount'=> $FI['@attributes']['Amount'],
			'NotValidBefore'=> $FI['@attributes']['NotValidBefore'],
			'NotValidAfter'=> $FI['@attributes']['NotValidAfter']
		);
		if(isset($FI['airFareRuleKey'])){
			$data4['FareRuleKey']=$FI['airFareRuleKey'];
		}
		$fairinfo[]=$data4;  
	}
}
return $fairinfo;	
}
function processBookingInfo($BookingInfo){
$bookinginfo=array();
if(isset($BookingInfo['@attributes'])){
	$data5=array(
		'BookingCode'=> $BookingInfo['@attributes']['BookingCode'],
		'CabinClass'=> $BookingInfo['@attributes']['CabinClass'],
		'FareInfoRef'=> $BookingInfo['@attributes']['FareInfoRef'],
		'SegmentRef'=> $BookingInfo['@attributes']['SegmentRef']
	);
	$bookinginfo[]=$data5;
}else if(isset($BookingInfo[0]['@attributes'])){
	 foreach($BookingInfo as $BI){
		$data5=array(
			'BookingCode'=> $BI['@attributes']['BookingCode'],
			'CabinClass'=> $BI['@attributes']['CabinClass'],
			'FareInfoRef'=> $BI['@attributes']['FareInfoRef'],
			'SegmentRef'=> $BI['@attributes']['SegmentRef']
		);
		$bookinginfo[]=$data5;
	 }
}
return $bookinginfo;	
}
function processTaxInfo($TaxInfo){
$taxinfo=array();
if(isset($TaxInfo['@attributes'])){
	$data6=array(
		'Category'=> $TaxInfo['@attributes']['Category'],
		'Amount'=> $TaxInfo['@attributes']['Amount'],
		'Key'=> $TaxInfo['@attributes']['Key']
	);
	$taxinfo[]=$data6;
}else if(isset($TaxInfo[0]['@attributes'])){
	 foreach($TaxInfo as $TI){
		$data6=array(
			'Category'=> $TI['@attributes']['Category'],
			'Amount'=> $TI['@attributes']['Amount'],
			'Key'=> $TI['@attributes']['Key']
		);
		$taxinfo[]=$data6;
	 }
}
return $taxinfo;	
}
function processPassengerType($PassengerType){
$ptypedata=array();
 if(isset($PassengerType['@attributes']['Code'])){
 $psgtypedata=array(
	'Code' => $PassengerType['@attributes']['Code']
 );
 if(isset($PassengerType['@attributes']['Age'])){
	$psgtypedata['Age'] =$PassengerType['@attributes']['Age'];
 }
 $ptypedata[]=$psgtypedata;
 }else if(isset($PassengerType[0]['@attributes'])){
	foreach($PassengerType as $psgtype){
		$psgtypedata=array(
			'Code' => $psgtype['@attributes']['Code']
		 );
		  if(isset($PassengerType['@attributes']['Age'])){
		     $psgtypedata['Age'] =$psgtype['@attributes']['Age'];
		  }
		 $ptypedata[]=$psgtypedata;
	}
 }	
return $ptypedata;
}
function processBaggageRestriction($BaggageAllowances){
$baggageRestriction='';
$airBaggageAllowanceInfo=$BaggageAllowances['airBaggageAllowanceInfo'];
$airBagDetails=array();
if(isset($airBaggageAllowanceInfo['airBagDetails'])){
$airBagDetails=$airBaggageAllowanceInfo['airBagDetails'];
}else if(isset($airBaggageAllowanceInfo[0]['airBagDetails'])){
$airBagDetails=$airBaggageAllowanceInfo[0]['airBagDetails'];	
}

$airBaggageRestriction=array();
if(isset($airBagDetails['airBaggageRestriction'])){
$airBaggageRestriction=$airBagDetails['airBaggageRestriction'];
}else if(isset($airBagDetails[0]['airBaggageRestriction'])){
$airBaggageRestriction=$airBagDetails[0]['airBaggageRestriction'];	
}

if(isset($airBaggageRestriction['airTextInfo']['airText']))
$baggageRestriction=$airBaggageRestriction['airTextInfo']['airText'];

return $baggageRestriction;	
}
function processBaggageAllow($priceinfo=array()){
  $allow=array();
  foreach($priceinfo as $PI){
	  $allow[$PI['PassengerType'][0]['Code']]=$PI['BaggageRestriction'];
  }
  return $allow;
}
function getSegmentKey($ref,$airsegments){
	foreach ($airsegments as $k => $v) {
       if ($v['Key'] === $ref) {
           return $k;
       }
   }
   return null;
}
function getFairInfoKey($ref,$fairinfolist){
	foreach ($fairinfolist as $k => $v) {
       if ($v['Key'] === $ref) {
           return $k;
       }
   }
   return null;
}
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

function tripIdGenerator($length = 6){
$ret='C';
$pw='';
$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
$len = strlen($chars);
for ($i=0;$i<20;$i++)
        $pw .= substr($chars, rand(0, $len-1), 1);
// the finished password
$pw = str_shuffle($pw);
$pw=md5($pw);
$len = strlen($pw);
for ($i=0;$i<$length;$i++)
 $ret .= substr($pw, rand(0, $len-1), 1);
return strtoupper($ret);	
}
?>
