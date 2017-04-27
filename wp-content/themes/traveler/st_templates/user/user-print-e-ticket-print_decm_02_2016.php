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

$ptypefull=array('ADT'=>'Adult','CHD'=>'Child','INF'=>'Infant');


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
$traveltype = get_post_meta($item_id,'traveltype',true);
$departdate = get_post_meta($item_id,'departdate',true);
$returndate = get_post_meta($item_id,'returndate',true);
$bookingdate= get_post_meta($item_id,'bookingdate',true);
if($bookingdate!=''){
	$bookingdate=date("d M Y",strtotime($bookingdate));
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
if(isset($data['AirPricingSolution']['AirPricingInfo'][0])){
	$priceresult=$data['AirPricingSolution']['AirPricingInfo'][0];
	$BaggageRestriction=$data['AirPricingSolution']['AirPricingInfo'][0]['BaggageRestriction'];
	$baggageallow=processBaggageAllow($data['AirPricingSolution']['AirPricingInfo']);
}

$booking_entries=base64_decode($booking_entries);
$booking_entries=unserialize($booking_entries);	

}// airdata
/*echo "booking_reason: ".$booking_reason;
echo "<pre>";
print_r($airdata);*/
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
/* echo "<pre>";
print_r($searchdata);
die(); */
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

$pdfhtml=''.$bookingdate.',<br><br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_name']).',<br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_company']).',<br>';
$pdfhtml.=''.nl2br($booking_entries['letterhead_address']).'<br><br><br>'; 


$pdfhtml.='Dear Sir/ Madam,<br><br>';
$pdfhtml.='<u><strong>Letter Of Confirmation</strong></u><br><br>';
$pdfhtml.='This is to confirm that the passenger holds a valid Sri Lankan passport and that the travel itinerary to travel from'.$airportscity[$segmentdtls['Origin']].' to '.$destination.' as per confirmed by the passenger is as follows:';
$pdfhtml.='<br><br>';
$pdfhtml.='
<table cellpadding="0" cellspacing="0" border="1" bordercolor="#ccc">
            <tr>
                <th colspan="4">Name </th>
                <th>PPT No</th>
            </tr>';
foreach($booking_entries['title'] as $k=>$v){
  $pdfhtml.='<tr>
                <td colspan="4">'.$booking_entries['title'][$k].' '.$booking_entries['first_name'][$k].' '.$booking_entries['last_name'][$k].'</td>
                <td>'.$booking_entries['passport_no'][$k].'</td>
            </tr>';
}
 $pdfhtml.='<tr>
                <th>FLIGHT NO</th>
                <th>DATE</th>
                <th>SECTOR</th>
                <th>DEPARTURE</th>
                <th>ARRIVAL</th>
            </tr>';
foreach($priceresult['BookingInfo'] as $BI){
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
$pdfhtml.='<tr>
                <td>'.$FlightNumber.'</td>
                <td>'.date("d M",strtotime($segmentdtls['DepartureTime'])).'</td>
                <td>'.$segmentdtls['Origin'].' - '.$segmentdtls['Destination'].'</td>
                <td>'.date("Hi",strtotime($segmentdtls['DepartureTime'])).'</td>
                <td>'.date("Hi",strtotime($segmentdtls['ArrivalTime'])).'</td>
            </tr>';
}
$pdfhtml.='<tr>
                <td colspan="5">Air Line ref – '.$thisCarrier.' – '.$FlightNumber.'</td>
            </tr>
        </table>
        <p>Thanking You,<br /> Yours Sincerely,</p>
        <p>&nbsp;</p>
        <p>.............................................
        <br/>Manager</p>';
//$mpdf->WriteHTML($pdfhtml);
//$mpdf->Output('Letter Of Confirmation.pdf','D');  
?>
<div class="letter-area">
<div class="container-fluid">
    <div class="com-sm-12">
	     <p>
        <span class="letter-date"><?php echo $bookingdate;?></span><br />
        </p>
		<p>
		<span class="address"><?php echo nl2br($booking_entries['letterhead_name']);?></span><br>
		<span class="address"><?php echo nl2br($booking_entries['letterhead_company']);?></span><br>
		<span class="address"><?php echo nl2br($booking_entries['letterhead_address']);?></span>
		</p>
	    <p>Dear Sir / Madam.</p>
        <h3>Letter of confirmation</h3>
        <p>This is to confirm that the passenger holds a valid Sri Lankan passport and that the travel itinerary to travel from <?php echo $airportscity[$segmentdtls['Origin']];?> to <?php echo $destination;?> through <?php echo $thisairlinename;?> as per confirmed by the passenger is as follows:</p>
        <table cellpadding="0" cellspacing="0" border="1" bordercolor="#ccc">
            <tr>
                <th colspan="4">Name </th>
                <th>PPT No</th>
            </tr>
			<?php foreach($booking_entries['title'] as $k=>$v){?>
            <tr>
                <td colspan="4"><?php echo $booking_entries['title'][$k];?> <?php echo $booking_entries['first_name'][$k];?> <?php echo $booking_entries['last_name'][$k];?></td>
                <td><?php echo $booking_entries['passport_no'][$k];?></td>
            </tr>
			<?php }?>
            <tr>
                <th>FLIGHT NO</th>
                <th>DATE</th>
                <th>SECTOR</th>
                <th>DEPARTURE</th>
                <th>ARRIVAL</th>
            </tr>
<?php
foreach($priceresult['BookingInfo'] as $BI){
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
?>   
            <tr>
                <td><?php echo $FlightNumber;?></td>
                <td><?php echo date("d M",strtotime($segmentdtls['DepartureTime']));?></td>
                <td><?php echo $segmentdtls['Origin'];?> - <?php echo $segmentdtls['Destination'];?></td>
                <td><?php echo date("Hi",strtotime($segmentdtls['DepartureTime']));?></td>
                <td><?php echo date("Hi",strtotime($segmentdtls['ArrivalTime']));?></td>
            </tr>
<?php //}//group ?>
<?php }//for each ?>
            <tr>
                <td colspan="5">Air Line ref – <?php echo $thisCarrier;?> – <?php echo $FlightNumber;?></td>
            </tr>
        </table>
        <p>Thanking You,<br /> Yours Sincerely,</p>
        <p>&nbsp;</p>
        <p>.............................................
        <br/>Manager</p>
		<?php /* ?><br/>Manager<br />T.SHENGABRAJ </p><?php */ ?>
    </div>
</div>
</div>
<?php }else if($booking_reason=='Fare Quotation'){?>
<div class="letter-area">
<div class="container-fluid">
    <div class="com-sm-12">
        <p>
        <span class="letter-date"><?php echo $bookingdate;?></span><br />
        </p>
		<p>
		<span class="address"><?php echo nl2br($booking_entries['letterhead_name']);?></span><br>
		<span class="address"><?php echo nl2br($booking_entries['letterhead_company']);?></span><br>
		<span class="address"><?php echo nl2br($booking_entries['letterhead_address']);?></span>
		</p>
        <p>Dear,</p>
        <h3>REGARDING QUOTATION FOR AIR TICKET</h3>
        <p>Thanking for your inquiry letter dated <?php echo $bookingdate;?></p>
		<p>We are please to quote you the following:</p>
        <table cellpadding="0" cellspacing="0" border="1" bordercolor="#ccc" >
            <tr>
                <th>Flight</th>
                <th>Aircraft</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Duration</th>
                <th>Class Of Service</th>
                <th>Status</th>
            </tr>
<?php
foreach($priceresult['BookingInfo'] as $BI){
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
?>        
            <tr>
                <td><?php echo $thisCarrier;?> <?php echo $FlightNumber;?> <img src="../airimages/<?php echo $thisCarrier.'.GIF';?>" /> <?php echo $thisairlinename;?></td>
                <td>Aircraft <?php echo $aircraft;?></td>
                <td><?php echo $airportscity[$segmentdtls['Origin']];?> (<?php echo $segmentdtls['Origin'];?>) <?php echo date("l, d M",strtotime($segmentdtls['DepartureTime']));?><br /><?php echo date("Y h:i",strtotime($segmentdtls['DepartureTime']));?><br /><?php echo $airportsname[$segmentdtls['Origin']];?></td>
                <td><?php echo $airportscity[$segmentdtls['Destination']];?> (<?php echo $segmentdtls['Destination'];?>) <?php echo date("l, d M",strtotime($segmentdtls['ArrivalTime']));?><br /><?php echo date("Y h:i",strtotime($segmentdtls['ArrivalTime']));?> <br /><?php echo $airportsname[$segmentdtls['Destination']];?></td>
                <td><?php echo $duration;?></td>
                <td><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></td>
                <td></td>
            </tr>
<?php //}//group ?>
<?php }//for each ?>
        </table>
        <table cellpadding="0" cellspacing="0" border="1" bordercolor="#ccc" >
            <tr>
                <th width="70%">Price Details</th>
                <th>Price</th>
            </tr>
            <tr>
                <td width="70%">Base Fare</td>
                <td><?php echo $EquivalentBasePrice;?></td>
            </tr>
            <tr>
                <td width="70%">Taxes</td>
                <td><?php echo $Taxes;?></td>
            </tr>
            <tr>
                <td width="70%">Total Price</td>
                <td><?php echo $TotalPrice;?></td>
            </tr>
        </table>
        <p>We are pleased to provide further information on request and trust that you would contact on us to fulfill your order, which will receive our prompt and keen attention.</p>
        <p>Quotation is valid until <?php echo date("d M Y",strtotime($departdate));?>.</p>
        <p>Thanking You,</p>
        <p>&nbsp;</p>
        <p>.............................................
        <br/>Manager<br />T.SHENGABRAJ </p>
    </div>
</div>
</div>
<?php }else if($booking_reason=='Pay Later' || $booking_reason==''){?>
<div class="free-booking">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <img src="<?php echo st()->get_option('logo',get_template_directory_uri().'/img/logo-invert.png') ?>" alt="Click My Booking" class="click-logo" />
                <h2>Travel Itinerary <span>(reservation copy)</span></h2>
            </div>
            <div class="col-sm-6">
                <div class="text-right ppd">
                    <h2>Customer Care:</h2>
                    <p><strong>Call :</strong> 0812 22 77 77</p>
                    <p><strong>Email :</strong> support@clickmybooking.com</p>                        
                </div>
            </div>
            <div class="clearfix"></div>
            <hr />
            <div class="col-sm-6">
                <p><strong>MyTrip ID :</strong> <?php echo $TRIPID;?></p>
                <?php if($ticket_number!=''){// ticket_number?>
                <p><strong>Ticket number :</strong> <?php echo $ticket_number;?></p>
                <?php }else{?>
                <p><strong>Airline PNR :</strong> <?php echo $PNR;?></p>
                <?php } ?>
                <p>&nbsp;</p>
            </div>
            <div class="col-sm-6">
                <div class="text-right">
                    <p><strong>Booking Date :</strong> <?php echo $bookingdate;?></p>
                    <?php /*?><p><strong>Ticket Time Limit : 14 Sep 2015, 15:30</strong></p><?php */?>
                    <p>&nbsp;</p>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="free-booking-heading">Itinerary Details</div>
                <table class="free-booking-flight-dtl">
                    <tr>
                        <th>Flight</th>
                        <th>Aircraft</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Duration</th>
                        <th>Class Of Service</th>
                        <th>Status</th>
                    </tr>
<?php //if($traveltype=='roundtrip'){?>
<?php 
$n=0;
$depttimes=array();
$arrivaltimes=array();
//echo "<pre>";
//print_r($priceresult);
foreach($priceresult['BookingInfo'] as $BI){
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
?>          
                    <tr>
                        <td><?php echo $thisCarrier;?> <?php echo $FlightNumber;?> <img src="../airimages/<?php echo $thisCarrier.'.GIF';?>" /> <?php echo $thisairlinename;?></td>
                        <td>Aircraft <?php echo $aircraft;?></td>
                        <td><?php echo $airportscity[$segmentdtls['Origin']];?> (<?php echo $segmentdtls['Origin'];?>) <?php echo date("l, d M",strtotime($segmentdtls['DepartureTime']));?><br /><?php echo date("Y h:i",strtotime($segmentdtls['DepartureTime']));?><br /><?php echo $airportsname[$segmentdtls['Origin']];?></td>
                        <td><?php echo $airportscity[$segmentdtls['Destination']];?> (<?php echo $segmentdtls['Destination'];?>) <?php echo date("l, d M",strtotime($segmentdtls['ArrivalTime']));?><br /><?php echo date("Y h:i",strtotime($segmentdtls['ArrivalTime']));?> <br /><?php echo $airportsname[$segmentdtls['Destination']];?></td>
                        <td><?php echo $duration;?></td>
                        <td><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></td>
                        <td></td>
                    </tr>
<?php //}//group ?>
<?php }//for each ?>
<?php //}//roundtrip?>
                </table>
                <p>*All times are local to airport</p>
            </div>
            <div class="col-sm-12">
                <div class="free-booking-heading">Passenger Details</div>
                <table class="free-booking-psg-dtl">
                <?php foreach($booking_entries['ptype'] as $k=>$v){?>
                    <tr>
                        <th rowspan="2"><?php echo $ptypefull[$v];?></th>
                        <th>Name : </th>
                        <td><?php echo strtoupper($booking_entries['title'][$k]);?> <?php echo strtoupper($booking_entries['first_name'][$k]);?> <?php echo strtoupper($booking_entries['last_name'][$k]);?></td>
                    </tr>
                    <tr>
                        <th>Baggage Allowed : </th>
                        <td><?php if($v=='CHD')$v='CNN'; echo $baggageallow[$v];?></td>
                    </tr>
                  <?php }?>
                </table>
                <hr />
            </div>
            <div class="col-sm-12">
                <div class="free-booking-heading">Additional Information</div>
                <ul>
                    <li><i class="fa fa-angle-right"></i> This document can not be used as a travel document or an E-ticket under any circumstance.</li>
                    <li><i class="fa fa-angle-right"></i> The fare is subjected to change unless it is being ticketed.</li>
                    <li><i class="fa fa-angle-right"></i> You may have to submit details of your passport before issuing of tickets to certain destinations.</li>
                    <li><i class="fa fa-angle-right"></i> Our agents might contact you directly for verification purposes.</li>
                    <li><i class="fa fa-angle-right"></i> Authorities should be immediately if the name appearing in this document is different to that mentioned in the passport.</li>
                    <li><i class="fa fa-angle-right"></i> You are required to comprehend and settle the total flight fare before you could proceed to the issuing of the ticket.</li>
                    <li><i class="fa fa-angle-right"></i> Any alteration done to the special fares offered are subjected to a fee of penalty and cannot be refunded.</li>
                    <li><i class="fa fa-angle-right"></i> Please use the MyTrip ID when communicating with us.</li>
                    <li><i class="fa fa-angle-right"></i> Please use 'MYSKY ID' to contact us.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php } ?>
</div>
<?php if($booking_reason=='Embassy Visa Purpose'){?>
<div style="width:100%; text-align:right;">
<form name="frmpdf" method="post" action="/getpdf.php">
<input type="hidden" name="pdfdata" value="<?php echo base64_encode($pdfhtml);?>">
<input type="submit" name="btnPdf" value="Download Pdf" />
</form>
</div>
<?php }else{?>
<div style="width:100%; text-align:right;"><input type="button" name="btnPrint" id="btnPrint" value="Print This" />
</div>
<?php } ?>
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


