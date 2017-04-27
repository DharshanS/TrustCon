<?php

function processResponse($responseArray)
{
if(isset($responseArray['SOAPBody']['SOAPFault']['faultstring']))
    {  

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
                <?php ?>
                <?php
			# Not to go further
			//die;
		}
		## XXXX - End - Error Trapping
}
else
{
 reservationSuceess($responseArray);   
}
}


function reservationSuceess($responseArray)
{

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
}
?>

