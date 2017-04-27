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
//set_time_limit(600);
//ini_set("memory_limit","256M");
date_default_timezone_set('Asia/Colombo');
global $current_user;
global $post;
global $wpdb;
$item_id=isset($_REQUEST['i'])?(int)$_REQUEST['i']:0;

$airlines=array();
if(isset($_SESSION['airlines'])){
  $airlines=$_SESSION['airlines'];	
}else{
$SQL="SELECT `iata`,`airline` FROM `airlines` WHERE `iata`!=''";
$results = $wpdb->get_results($SQL);
foreach($results as $result){
	$airlines[$result->iata]=$result->airline;
}
$_SESSION['airlines']=$airlines;
}

$airportscity=array();
$airportsname=array();
if(isset($_SESSION['airportscity'])){
  $airportscity=$_SESSION['airportscity'];	
  $airportsname=$_SESSION['airportsname'];	
}else{
$SQL="SELECT `iata_code`,`city`,`airport_name` FROM `airports`";
$results = $wpdb->get_results($SQL);
foreach($results as $result){
  $airportscity[$result->iata_code]=$result->city;
  $airportsname[$result->iata_code]=$result->airport_name;
}
$_SESSION['airportscity']=$airportscity;
$_SESSION['airportsname']=$airportsname;
}

$ptypefull=array('ADT'=>'Adult','CHD'=>'Child','CNN'=>'Child','INF'=>'Infant');


get_currentuserinfo();
$user_id = $current_user->ID;

$SQL="SELECT * FROM `wp_posts` WHERE `post_author`='".$user_id."' AND `ID`='".$item_id."'";
$results = $wpdb->get_results($SQL);
if(!count($results))die("INVALID reference, no data found");

$booking_reason=get_post_meta($item_id,'booking_reason',true);
$airdata=get_post_meta($item_id,'AIRDATA',true);
$booking_entries=get_post_meta($item_id,'booking_entries',true);

$transaction_id = get_post_meta($item_id,'TRANSACTION_ID',true);
$transaction_response_code = get_post_meta($item_id,'TRANSACTION_RESPONSE_CODE',true);

$ticket_number = get_post_meta($item_id,'ticket_number',true);

$searchdata = get_post_meta($item_id,'searchdata',true);
$searchdata=base64_decode($searchdata);
$searchdata=unserialize($searchdata);	

$TRIPID = get_post_meta($item_id,'TRIPID',true);
$PNR = get_post_meta($item_id,'PNR',true);
$from = get_post_meta($item_id,'from',true);
$to = get_post_meta($item_id,'to',true);
$cost = get_post_meta($item_id,'cost',true);
$disamt = get_post_meta($item_id,'disamt',true);
$traveltype = get_post_meta($item_id,'traveltype',true);
$departdate = get_post_meta($item_id,'departdate',true);
$returndate = get_post_meta($item_id,'returndate',true);
$bookingdate= get_post_meta($item_id,'bookingdate',true);
if($bookingdate!=''){
	//$bookingdate=date("d M Y",strtotime($bookingdate));
	$bookingdate=date("jS M, Y",strtotime($bookingdate));
}
if($traveltype=='oneway'){
	$returndate='';
}else{
$returndate=date('m/d/y',strtotime($returndate));
}
$priceresult=array();
if($airdata!=''){
$airdata=base64_decode($airdata);
$airdata=unserialize($airdata);	
$data=$airdata;
$AirSegment=$data['AirSegment'];
$baggageallow=array();
$grandtotalprice=0;
$grandbaseprice=0;
$grandtaxes=0;
	
if(isset($data['AirPricingSolution'])){
	$APRICESOL=$data['AirPricingSolution'];
	$grandtotalprice=$APRICESOL['TotalPrice'];
	$grandbaseprice=$APRICESOL['BasePrice'];
	$grandtaxes=$APRICESOL['Taxes'];
	
	$currency=substr($grandtotalprice,0,3);
    $grandtotalprice=substr($grandtotalprice,3);
    $grandtotalprice=$currency."".number_format($grandtotalprice);
	
	$grandbaseprice=substr($grandbaseprice,3);
    $grandbaseprice=$currency."".number_format($grandbaseprice);
	
	$grandtaxes=substr($grandtaxes,3);
    $grandtaxes=$currency."".number_format($grandtaxes);
}

if(isset($data['AirPricingSolution']['AirPricingInfo'][0])){
	$priceresult=$data['AirPricingSolution']['AirPricingInfo'][0];
	$BaggageRestriction=$data['AirPricingSolution']['AirPricingInfo'][0]['BaggageRestriction'];
	$baggageallow=processBaggageAllow($data['AirPricingSolution']['AirPricingInfo']);
}

$booking_entries=base64_decode($booking_entries);
$booking_entries=unserialize($booking_entries);	

$nopas=$booking_entries;
$noofpassenger=0;
foreach($nopas['title'] as $p){
	$noofpassenger++;
}

}// airdata
/* echo "booking_reason: ".$booking_reason;
echo "<pre>";
print_r($airdata);
die();  */
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
?>
<div id="printdiv">
<?php if($booking_reason=='Embassy Visa Purpose'){?>
<?php
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
?>
<?php
/* $path = get_home_path();
include($path."mpdf60/mpdf.php");
$mpdf=new mPDF('c');  */
$pdfhtml='<img src="http://www.clickmybooking.com/images/newsletter-logo.png" alt="Logo">';
$pdfhtml.='<br>';
$pdfhtml.='<br>';
$pdfhtml.=''.$bookingdate.',<br><br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_name']).',<br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_company']).',<br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_address']).'<br><br><br>'; 


$pdfhtml.='Dear Sir/ Madam,<br><br>';
$pdfhtml.='<center><u><strong>Letter Of Confirmation</strong></u></center>';
$pdfhtml.='<br><br>';
$pdfhtml.='This is to keep infrom that bellow under mentioned passanger/s is/are flying to the destination given below on on the date of '.date("d F, Y",strtotime($reachdate)).'';
//if($traveltype=='roundtrip'){
//$pdfhtml.=' and returned on '.date("d F, Y",strtotime($backdate)).'';	
//}
$pdfhtml.='<br><br>';
$pdfhtml.='Relevent details are given bellow,<br>';
$pdfhtml.='<table cellpadding="0" cellspacing="0" border="0" bordercolor="#ccc" >
            <tr>
                <td style="width:50%;"><strong><u>Name</u></strong></td>
                <td style=""><strong><u>Passport Number</u></strong></td>
            </tr>';
foreach($booking_entries['title'] as $k=>$v){
$pdfhtml.='<tr>
                <td>'.$booking_entries['title'][$k].' '.$booking_entries['first_name'][$k].' '.$booking_entries['last_name'][$k].'</u></td>
                <td>'.$booking_entries['passport_no'][$k].'</td>
            </tr>';
}
$pdfhtml.='</table>';
$pdfhtml.='<br>';
$pdfhtml.='PNR : <strong>'.$PNR.'</strong>';
$pdfhtml.='<br>';
$pdfhtml.='Flight information:';
$pdfhtml.='<br>';
		
if(count($priceresult)){
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
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$thisCarrier.' '.$FlightNumber.' <br><img src="http://clickmybooking.com/airimages/'.$thisCarrier.'.GIF'.'" /><br>'.$thisairlinename.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">Aircraft '.$aircraft.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$airportscity[$segmentdtls['Origin']].' ('.$segmentdtls['Origin'].') '.date("l, d M",strtotime($segmentdtls['DepartureTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['DepartureTime'])).'<br />'.$airportsname[$segmentdtls['Origin']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$airportscity[$segmentdtls['Destination']].' ('.$segmentdtls['Destination'].') '.date("l, d M",strtotime($segmentdtls['ArrivalTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['ArrivalTime'])).' <br />'.$airportsname[$segmentdtls['Destination']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$duration.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$BI['CabinClass'].' '.$BI['BookingCode'].'</td>
		</tr>';
}
$pdfhtml.='</table>';
}else{
$pdfhtml.='We are sorry, we do not find any Itenary for your search. Please check your input and search again';
}
$pdfhtml.='<br>';
$pdfhtml.='Please be noted that this is computer generated print and only valid for 3days.';
$pdfhtml.='<br><br><br>';
$pdfhtml.='OFFICE: No 07, D S Senanyake Street, Kandy<br>
Email: booking@clickmybooking.com<br>
Contact Number: 081-2227771';
//$mpdf->WriteHTML($pdfhtml);
//$mpdf->Output('Letter Of Confirmation.pdf','D');  
?>
<div class="letter-area" style="width:100%;">
<div class="container-fluid">
    <div class="com-sm-12">
	    <?php echo $pdfhtml;?>
		<?php /* ?><br/>Manager<br />T.SHENGABRAJ </p><?php */ ?>
    </div>
</div>
</div>
<?php }else if($booking_reason=='Fare Quotation'){
$pdfhtml='<img src="http://www.clickmybooking.com/images/newsletter-logo.png" alt="Logo">';
$pdfhtml.='<br>';
$pdfhtml.='<br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_name']).',<br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_company']).',<br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_address']).'<br><br><br>'; 
$pdfhtml.='Dear,<br>';	
$pdfhtml.='Thanking for your inquiry with us dated '.$bookingdate.'';	
$pdfhtml.='<br>';
$pdfhtml.='<br>';
/* $pdfhtml.='<br>';
$pdfhtml.='PNR : <strong>'.$PNR.'</strong>';
$pdfhtml.='<br>';		 */
if($noofpassenger>1){
	$pdfhtml.='Following are the Itenary for your search:';
}else{
	$pdfhtml.='Following is the Itenary for your search:';
}
$pdfhtml.='<br>';	
if(count($priceresult)){
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
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$thisCarrier.' '.$FlightNumber.' <br><img src="http://clickmybooking.com/airimages/'.$thisCarrier.'.GIF'.'" /><br>'.$thisairlinename.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">Aircraft '.$aircraft.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$airportscity[$segmentdtls['Origin']].' ('.$segmentdtls['Origin'].') '.date("l, d M",strtotime($segmentdtls['DepartureTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['DepartureTime'])).'<br />'.$airportsname[$segmentdtls['Origin']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$airportscity[$segmentdtls['Destination']].' ('.$segmentdtls['Destination'].') '.date("l, d M",strtotime($segmentdtls['ArrivalTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['ArrivalTime'])).' <br />'.$airportsname[$segmentdtls['Destination']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$duration.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$BI['CabinClass'].' '.$BI['BookingCode'].'</td>
		</tr>';
}
$pdfhtml.='</table>';
}else{
$pdfhtml.='We are sorry, we do not find any Itenary for your search. Please check your input and search again';
}
$pdfhtml.='<br>'; 
$pdfhtml.='<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong><u>Price Details</u></strong></td>
                <td style=""><strong><u>Price</u></strong></td>
            </tr>';
$pdfhtml.=' <tr>
                <td>Base Fare</td>
                <td>'.$grandbaseprice.'</td>
            </tr>';
			
$pdfhtml.=' <tr>
                <td>Taxes</td>
                <td>'.$grandtaxes.'</td>
            </tr>';
$pdfhtml.=' <tr>
                <td>Webfare Discount</td>
                <td>'.$disamt.'</td>
            </tr>';			
$pdfhtml.=' <tr>
                <td>Total Price</td>
                <td>'.$cost.'</td>
            </tr>';
$pdfhtml.='</table>';
$pdfhtml.='<br><br>';
$pdfhtml.='We are pleased to provide further information on request and trust that you would contact on us to fulfill your
order, which will receive our prompt and keen attention.';   
$pdfhtml.='<br>';     
$pdfhtml.='Thanking You,<br>';
$pdfhtml.='<a href="http://www.clickmybooking.com">www.clickmybooking.com</a><br>';
$pdfhtml.='<strong>OFFICE:</strong> No 07, D S Senanyake Street, Kandy<br>
<strong>Email:</strong> booking@clickmybooking.com<br>
<strong>Contact Number:</strong> 081-2227771';
?>
<div class="letter-area" style="width:100%;">
<div class="container-fluid">
    <div class="com-sm-12">
        <?php echo $pdfhtml;?>
    </div>
</div>
</div>
<?php }else if($booking_reason=='Pay Later' || $booking_reason==''){
$pdfhtml.='<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><img src="http://www.clickmybooking.com/images/newsletter-logo.png" alt="Logo"></td>
                <td valign="top"><strong>Customer Care:</strong><br>
				<strong>Call :</strong> 0812 22 77 77<br>
				<strong>Email :</strong> support@clickmybooking.com</td>
            </tr>';
$pdfhtml.='</table>';
$pdfhtml.='Travel Itinerary (reservation copy)';
$pdfhtml.='<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong>MyTrip ID :</strong>'.$TRIPID.'<br>';
if($ticket_number!=''){
$pdfhtml.='<strong>Ticket number :</strong> '.$ticket_number.'';
}else{
$pdfhtml.='<strong>Airline PNR :</strong> '.$PNR.'';
}				
$pdfhtml.='     </td>
                <td valign="top"><strong>Booking Date :</strong> '.$bookingdate.'</td>
            </tr>';
$pdfhtml.='</table>';
$pdfhtml.='<br>';
$pdfhtml.='<strong>Itinerary Details:</strong>';
$pdfhtml.='<br>';
if(count($priceresult)){
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
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$thisCarrier.' '.$FlightNumber.' <br><img src="http://clickmybooking.com/airimages/'.$thisCarrier.'.GIF'.'" /><br>'.$thisairlinename.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">Aircraft '.$aircraft.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$airportscity[$segmentdtls['Origin']].' ('.$segmentdtls['Origin'].') '.date("l, d M",strtotime($segmentdtls['DepartureTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['DepartureTime'])).'<br />'.$airportsname[$segmentdtls['Origin']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$airportscity[$segmentdtls['Destination']].' ('.$segmentdtls['Destination'].') '.date("l, d M",strtotime($segmentdtls['ArrivalTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['ArrivalTime'])).' <br />'.$airportsname[$segmentdtls['Destination']].'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$duration.'</td>
			<td style="border-bottom: 1px dotted #c4c4c4;" valign="top">'.$BI['CabinClass'].' '.$BI['BookingCode'].'</td>
		</tr>';
}
$pdfhtml.='</table>';
}else{
$pdfhtml.='We are sorry, we do not find any Itenary for your search. Please check your input and search again';
}
$pdfhtml.='<em>*All times are local to airport</em>';
$pdfhtml.='<br><br>';
$pdfhtml.='<strong>Passenger Details:</strong>';
$pdfhtml.='<br>';
$pdfhtml.='<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
            <tr>
                <td style="width:50%;"><strong><u>Name</u></strong></td>
                <td style=""><strong><u>Baggage Allowed </u></strong></td>
            </tr>';
//foreach($booking_entries['title'] as $k=>$v){
foreach($booking_entries['ptype'] as $k=>$v){
$pdfhtml.='<tr>
                <td><em>'.$ptypefull[$v].'</em><br>'.$booking_entries['title'][$k].' '.$booking_entries['first_name'][$k].' '.$booking_entries['last_name'][$k].'</u></td>';
if($v=='CHD')$v='CNN';
$pdfhtml.='     <td>'.$baggageallow[$v].'</td>
            </tr>';
}
$pdfhtml.='</table>';
$pdfhtml.='<br>';
$pdfhtml.='<strong>Additional Information:</strong>';
$pdfhtml.='<br>';
$pdfhtml.='&nbsp;&nbsp;&gt;This document can not be used as a travel document or an E-ticket under any circumstance.';
$pdfhtml.='<br>';
$pdfhtml.='&nbsp;&nbsp;&gt;The fare is subjected to change unless it is being ticketed.';
$pdfhtml.='<br>';
$pdfhtml.='&nbsp;&nbsp;&gt;You may have to submit details of your passport before issuing of tickets to certain destinations.';
$pdfhtml.='<br>';
$pdfhtml.='&nbsp;&nbsp;&gt;Our agents might contact you directly for verification purposes.';
$pdfhtml.='<br>';
$pdfhtml.='&nbsp;&nbsp;&gt;Authorities should be immediately if the name appearing in this document is different to that mentioned in the passport.';
$pdfhtml.='<br>';
$pdfhtml.='&nbsp;&nbsp;&gt;You are required to comprehend and settle the total flight fare before you could proceed to the issuing of the ticket.';
$pdfhtml.='<br>';
$pdfhtml.='&nbsp;&nbsp;&gt;Any alteration done to the special fares offered are subjected to a fee of penalty and cannot be refunded.';
$pdfhtml.='<br>';
$pdfhtml.='&nbsp;&nbsp;&gt;Please use the MyTrip ID when communicating with us.';
$pdfhtml.='<br>';
$pdfhtml.='&nbsp;&nbsp;&gt;Please use \'MYSKY ID\' to contact us.';
$pdfhtml.='<br><br>';
$pdfhtml.='<strong>OFFICE:</strong> No 07, D S Senanyake Street, Kandy<br>
<strong>Email:</strong> booking@clickmybooking.com<br>
<strong>Contact Number:</strong> 081-2227771';
?>
<div class="free-booking" style="width:100%">
    <div class="container-fluid">
        <div class="row">
            <?php echo $pdfhtml; ?>
        </div>
    </div>
</div>
<?php } ?>
</div>
<?php //if($booking_reason=='Embassy Visa Purpose'){?>
<div style="width:100%; text-align:right;">
<form name="frmpdf" method="post" action="/getpdf.php">
<input type="hidden" name="pdfdata" value="<?php echo base64_encode($pdfhtml);?>">
<input type="submit" name="btnPdf" value="Download Pdf" />
</form>
</div>
<?php /* //}else{?>
<div style="width:100%; text-align:right;"><input type="button" name="btnPrint" id="btnPrint" value="Print This" />
</div>
<?php //} */ ?>
<script type="text/javascript">
	jQuery("#btnPrint").live("click", function () {
		Popup(jQuery('#printdiv').html());
    });
function Popup(data) {
    var mywindow = window.open('', 'my div', 'height=400,width=800,scrollbars=1,resizable=1');
    mywindow.document.write('<html><head><title></title>');
    mywindow.document.write('<link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/bootstrap.css" type="text/css" />');  
	mywindow.document.write('<link rel="stylesheet" href="<?php echo get_template_directory_uri();?>/css/styles.css" type="text/css" />');  
    mywindow.document.write('<style type="text/css">.test { color:red; } </style></head><body>');
    mywindow.document.write(data);
    mywindow.document.write('</body></html>');
    mywindow.document.close();
    mywindow.print();                        
}

</script>
<?php }// count price result?>
<?php
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
?>


