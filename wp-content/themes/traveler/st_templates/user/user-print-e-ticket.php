<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User Print E-Ticket
 *
 * Created by H+Plus Designs - Sri Lanka 
 *
 */
?>
<?php 
// generate ticket from PNR where applicable
pnrprocess();
?>
<div class="st-create">
    <h2>Print E-Ticket</h2>
</div>
<?php
$class_user = new STUser_f();
//$html = $class_user->get_book_history();
$print=1;
$html = $class_user->get_book_history_loz($print);
if(!empty($html)){
?>
    <table class="table table-bordered table-striped table-booking-history">
        <thead>
        <tr>
            <?php /*?><th><?php st_the_language('user_type')?></th>
            <th><?php st_the_language('user_title')?></th>
            <th><?php st_the_language('user_location') ?></th>
            <th><?php st_the_language('user_order_date')?></th>
            <th><?php st_the_language('user_execution_date') ?></th>
            <th><?php st_the_language('user_cost') ?></th>
            <th><?php st_the_language('action') ?></th><?php */?>
            <th>TRIPID</th>
            <th>PNR</th>
            <th>Ticket No.</th>
            <th>From City</th>
            <th>To City</th>
            <th>Depart</th>
            <th>Arrive</th>
            <th>Cost</th>
            <th>Booking Date</th>
            <th>Reason</th>
            <th>Type/TranID/Payment</th>
            <?php /* ?><th>Tran ID</th>
            <th>Payment Status</th><?php */ ?>
            <th>Print</th>
        </tr>
        </thead>
        <tbody id="data_history_book">
        <?php
        echo balanceTags($html);
        ?>
        </tbody>
    </table>
    <span class="btn btn-primary btn_load_his_book" data-per="2" ><?php st_the_language('user_load_more') ?></span>
<?php }else{ ?>
    <table class="table table-bordered table-striped table-booking-history">
        <thead>
        <tr>
            <th>TRIPID</th>
            <th>PNR</th>
            <th>Ticket No.</th>
            <th>From City</th>
            <th>To City</th>
            <th>Depart</th>
            <th>Arrive</th>
            <th>Cost</th>
            <th>Booking Date</th>
            <th>Reason</th>
            <th>Type/TranID/Payment</th>
            <?php /* ?><th>Tran ID</th>
            <th>Payment Status</th><?php */ ?>
            <th>Print</th>
        </tr>
        </thead>
    </table>
    <h5><?php st_the_language('user_no_booking_history') ?></h5>
<?php } ?>
<?php
function pnrprocess(){
/*--------LIVE CREDENTIAL ------*/
$TARGETBRANCH = 'P2721018'; 
$CREDENTIALS = 'Universal API/uAPI4655248065-02590718:nA?9{c3YC8';
$PCC='3OS2';
$Provider = '1G'; 
/*--------------*/
global $wpdb; 
$userid=0;
$PNRS=array();
if (is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	//$useremail=$current_user->user_email;
	$userid=$current_user->ID;
}
$SQL="SELECT `post_id`,`PNR` FROM `pnr_history` WHERE `post_author`='".$userid."' AND `ticket_number`='' AND `PNR`!='' AND `departtime` > NOW()"; // departdate
$results = $wpdb->get_results($SQL);
foreach($results as $result){
	$PNRS[$result->post_id]=$result->PNR;
}
foreach($PNRS as $pid=>$PNR){
	
$soap_pnr ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:univ="http://www.travelport.com/schema/universal_v35_0" xmlns:com="http://www.travelport.com/schema/common_v35_0">
   <soapenv:Header/>
         <soapenv:Body>
      <univ:UniversalRecordRetrieveReq TargetBranch="'.$TARGETBRANCH.'" >
<com:BillingPointOfSaleInfo OriginApplication="UAPI" />
<univ:ProviderReservationInfo ProviderCode="1G" ProviderLocatorCode="'.$PNR.'" />
</univ:UniversalRecordRetrieveReq>
   </soapenv:Body>
</soapenv:Envelope>';

$gzdata = gzencode($soap_pnr);
$auth = base64_encode("$CREDENTIALS"); 
$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/UniversalRecordService'; 
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
	
$universalUniversalRecord=array();
if(isset($responseArrayPNR['SOAPBody']['universalUniversalRecordRetrieveRsp']['universalUniversalRecord'])){
  $universalUniversalRecord=$responseArrayPNR['SOAPBody']['universalUniversalRecordRetrieveRsp']['universalUniversalRecord'];
}
$LocatorCode='';
if(isset($universalUniversalRecord['airAirReservation']['@attributes']['LocatorCode'])){
  $LocatorCode=$universalUniversalRecord['airAirReservation']['@attributes']['LocatorCode'];
}
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


if(isset($responseArrayTicket['SOAPBody']['airAirRetrieveDocumentRsp']['airETR']['airTicket']['@attributes']['TicketNumber'])){
$TicketNumber=$responseArrayTicket['SOAPBody']['airAirRetrieveDocumentRsp']['airETR']['airTicket']['@attributes']['TicketNumber'];	
}
if($TicketNumber!=''){
	$SQL="UPDATE `wp_postmeta` SET `meta_value`='".$TicketNumber."' WHERE `meta_key`='ticket_number' AND `post_id`='".$pid."'";
	$results = $wpdb->get_results($SQL);
	unset($results);
	$SQL="UPDATE `pnr_history` SET `ticket_number`='".$TicketNumber."' WHERE `PNR`='".$PNR."'"; 
    $results = $wpdb->get_results($SQL);
	unset($results);
}// db update
}// $IssueElectronicTicket
}// foreach
}
?>






