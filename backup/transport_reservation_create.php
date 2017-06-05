<?php
session_start();
set_time_limit(0);
if(!isset($_SESSION['booking_entries'])){
echo 'Something gone wrong';
}
require_once("travelportsettings.php");
require_once("tconnect.php");
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

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



$from=$fromcity;
$to=$tocity;
$cost=$totalprice;
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

## Rajib Added - 16/10/2015
$_SESSION['post_id'] = $post_id;
## Rajib Added - 16/10/2015

$mytripid=tripIdGenerator();

## Rajib Added - 29/10/15
$_SESSION['Mytripid']=$mytripid;	

// insert into wp_postmeta
$SQL="INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES";
$SQL.="($post_id, 'TRIPID', '$mytripid'),";
$SQL.="($post_id, 'PNR', '$PNR'),";
$SQL.="($post_id, 'AIRDATA', '".base64_encode(serialize($data))."'),";
$SQL.="($post_id, 'from', '$from'),";
$SQL.="($post_id, 'to', '$to'),";
$SQL.="($post_id, 'cost', '$cost'),";
$SQL.="($post_id, 'departdate', '$departdate'),";
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
$SQL.="($post_id, 'ticketdata', '".base64_encode(serialize($ticketdata))."'),";
}else{
$SQL.="($post_id, 'ticketdata', ''),";
}
$SQL.="($post_id, 'booking_entries', '".base64_encode(serialize($S))."'),";
$SQL.="($post_id, 'searchdata', '".$searchdataoriginal."')";
//die($SQL);
$result = $mysqli->query($SQL);
$mysqli->close();

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
$TotalPrice=$currency."".number_format($TotalPrice);
}

// sendmail to client
$mailto=$S['eml'];
$mailsubject='';
$mailhtml='';
$mailtoname=$S['first_name'][0].' '.$S['last_name'][0];

$mail->addAddress($mailto, $mailtoname); 
$mail->addReplyTo($mailsetters['from']);
$mail->isHTML(true);  

if($S['booking_reason']=='Fare Quotation'){
$mailsubject='Clickmybooking - Regarding Quotation For Air Ticket';
$mailhtml='Dear '.$mailtoname.',<br><br>';
$mailhtml.='Thanking for your inquiry with us dated '.date("jS M, Y").',';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Following is the Itenary for your search:';
$mailhtml.='<br>';
$mailhtml.='<br>';
if(count($priceresult)){
$mailhtml.='<table cellpadding="0" cellspacing="0" border="1" bordercolor="#ccc" >
            <tr>
                <th>Flight</th>
                <th>Aircraft</th>
                <th>Departure</th>
                <th>Arrival</th>
                <th>Duration</th>
                <th>Class Of Service</th>
                <th>Status</th>
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
			<td>'.$thisCarrier.' '.$FlightNumber.' <img src="http://clickmybooking.com/airimages/'.$thisCarrier.'.GIF'.'" /> '.$thisairlinename.'</td>
			<td>Aircraft '.$aircraft.'</td>
			<td>'.$airportscity[$segmentdtls['Origin']].' ('.$segmentdtls['Origin'].') '.date("l, d M",strtotime($segmentdtls['DepartureTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['DepartureTime'])).'<br />'.$airportsname[$segmentdtls['Origin']].'</td>
			<td>'.$airportscity[$segmentdtls['Destination']].' ('.$segmentdtls['Destination'].') '.date("l, d M",strtotime($segmentdtls['ArrivalTime'])).'<br />'.date("Y h:i",strtotime($segmentdtls['ArrivalTime'])).' <br />'.$airportsname[$segmentdtls['Destination']].'</td>
			<td>'.$duration.'</td>
			<td>'.$BI['CabinClass'].' '.$BI['BookingCode'].'</td>
			<td></td>
		</tr>';
}
$mailhtml.='
<tr><td colspan="7"></td></tr>
<tr><td colspan="7"></td></tr>
<tr><td colspan="7" width="100%">
        <table  width="100%" cellpadding="0" cellspacing="0" border="1" bordercolor="#ccc" >
            <tr>
                <th width="70%">Price Details</th>
                <th>Price</th>
            </tr>
            <tr>
                <td width="70%">Base Fare</td>
                <td>'.$EquivalentBasePrice.'</td>
            </tr>
            <tr>
                <td width="70%">Taxes</td>
                <td>'.$Taxes.'</td>
            </tr>
            <tr>
                <td width="70%">Total Price</td>
                <td>'.$TotalPrice.'</td>
            </tr>
        </table>
</td></tr>
</table>';
}else{
$mailhtml.='We are sorry, we do not find any Itenary for your search. Please check your input and search again';
}
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='We are pleased to provide further information on request and trust that you would contact on us to fulfill your order, which will receive our prompt and keen attention.';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Thanking you,';
$mailhtml.='<br>';
$mailhtml.='<a href="http://www.clickmybooking.com">www.clickmybooking.com</a>';
	
}else if($S['booking_reason']=='Embassy Purpose'){
$mailsubject='Clickmybooking - Regarding Booking Of Air Ticket';
$mailhtml='Dear '.$mailtoname.',<br><br>';
$mailhtml.='Thanking for your interest with us.';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Your Universal Record Locator Code given below, it will useful for any future communication with us:';
$mailhtml.='<br>';
//$mailhtml.=$UniversalRecordLocatorCode.'<br>';
## Rajib Added
if($AirReservationLocatorCode != '')
$mailhtml.=$AirReservationLocatorCode.'<br>';
else
$mailhtml.=$UniversalRecordLocatorCode.'<br>';
## Rajib Added
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='You can get a print out of confirmation letter from your personal dashboard.';
$mailhtml.='<br>';
$mailhtml.='We are pleased to provide further information on request and trust that you would contact on us to fulfill your order, which will receive our prompt and keen attention.';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Thanking you,';
$mailhtml.='<br>';
$mailhtml.='<a href="http://www.clickmybooking.com">www.clickmybooking.com</a>';
}else{
$mailsubject='Clickmybooking - Regarding Booking Of Air Ticket';
$mailhtml='Dear '.$mailtoname.',<br><br>';
$mailhtml.='Thanking for your interest with us.';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Your Universal Record Locator Code given below, it will useful for any future communication with us:';
$mailhtml.='<br>';
//$mailhtml.=$UniversalRecordLocatorCode.'<br>';
## Rajib Added
if($AirReservationLocatorCode != '')
$mailhtml.=$AirReservationLocatorCode.'<br>';
else
$mailhtml.=$UniversalRecordLocatorCode.'<br>';
## Rajib Added
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='You can get a print out of the ticket from your personal dashboard.';
$mailhtml.='<br>';
$mailhtml.='We are pleased to provide further information on request and trust that you would contact on us to fulfill your order, which will receive our prompt and keen attention.';
$mailhtml.='<br>';
$mailhtml.='<br>';
$mailhtml.='Thanking you,';
$mailhtml.='<br>';
$mailhtml.='<a href="http://www.clickmybooking.com">www.clickmybooking.com</a>';	
}


$mail->Subject = $mailsubject;
$mail->Body    = $mailhtml;
$mail->send();
//$meta_key
// meta_value
$html='';
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
$currency=substr($TotalPrice,0,3);
$TotalPrice=substr($TotalPrice,3);
$TotalPrice=$currency."".number_format($TotalPrice);
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
                    <td align="right"><span> <?php echo $EquivalentBasePrice;?></span></td>
                  </tr>
                  <tr>

                    <td align="left"><span>Taxes :</span></td>
                    <td align="right"><span> <?php echo $Taxes;?></span></td>
                  </tr>
                  <tr>
                    <td align="left"><span>Webfare Discount :</span></td>
                    <td align="right"><span>-1,904</span></td>
                  </tr>
                  <tr>
                    <td align="left"><span>Cash price :</span></td>
                    <td align="right"><span><strong>207,736</strong></span></td>
                  </tr>
                  <tr>
                    <td align="left"><span>VISA Catds 5% Discounted Price :*</span></td>
                    <td align="right"><span> 198,216</span></td>
                  </tr>
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
