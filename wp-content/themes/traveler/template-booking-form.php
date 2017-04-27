<?php
/*
Template Name: Booking Form
*/
/*
Abdul Manashi
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Page.php
 *
 * Created by ShineTheme
 *
 */
//echo "<pre>";
//print_r($_POST);
//die();

//echo "<pre>";
//print_r($_POST);
set_time_limit(0);

$TARGETBRANCH = 'P7040105';
$CREDENTIALS = 'Universal API/uAPI6986631472-c8b85ad8:P+t2rJ7&M6';
$PCC='3OS2';
$Provider = '1G'; // Any provider you want to use like 1G/1P/1V/ACH

$data=array();
if(isset($_SESSION['APIRESPONSE'])){
$data=$_SESSION['APIRESPONSE'];	
}

$AirPricingSolution=$data['AirPricingSolution'];
$airsegments=$data['AirSegment'];
//echo "<pre>";
//print_r($AirPricingSolution);
//die();

if(isset($_POST['mode']) && $_POST['mode']=='frmpost'){
// https://support.travelport.com/webhelp/uapi/uAPI.htm#SampleWeb/Alternative%20Sample%20Pages/Samples_Air-Galileo-Overview.htm%3FTocPath%3DGetting%2520Started|Samples|XML%2520Samples%2520|Air|Air%2520Workflows|Galileo%2520%281G%29%2520Workflows|_____0
// https://support.travelport.com/webhelp/uapi/Content/SampleWeb/SampleFiles/104-03_1G_AirBook_Rq.xml

// https://github.com/smituk?tab=repositories
//https://github.com/smituk/onlinereservation

$APRICESOL=$AirPricingSolution;
$p=$_POST;

//echo "<pre>";
//print_r($airsegments);
//
//
//echo "<pre>";
//print_r($APRICESOL);
////die();
//
//
//echo "<pre>";
//print_r($p);

$error=array();
if(!count($error)){ // pcount
$soap='
<!--Release 15.1-->
<!--Version Dated as of 11/May/2015 15:11:13-->
<!--Air Booking For Galileo(1G) with LFS Request-->
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header />
   <soapenv:Body>
      <univ:AirCreateReservationReq xmlns:univ="http://www.travelport.com/schema/universal_v31_0" AuthorizedBy="user" RetainReservation="Both" TargetBranch="'.$TARGETBRANCH.'" TraceId="'.md5("ACRR".date("ymdHis")).'">
         <com:BillingPointOfSaleInfo xmlns:com="http://www.travelport.com/schema/common_v31_0" OriginApplication="UAPI" />';
foreach($p['ptype'] as $k=>$v){		 
 $soap.='
         <com:BookingTraveler xmlns:com="http://www.travelport.com/schema/common_v31_0" DOB="'.$p['dob_year'][$k].'-'.$p['dob_month'][$k].'-'.$p['dob_day'][$k].'" Gender="'.$p['gender'][$k].'" Key="'.base64_encode("P$k".date("YmdH")).'" TravelerType="'.$p['ptype'][$k].'">
            <com:BookingTravelerName First="'.$p['first_name'][$k].'" Last="'.$p['last_name'][$k].'" Prefix="'.$p['title'][$k].'" />
            <!--<com:DeliveryInfo>
               <com:ShippingAddress>
                  <com:AddressName>Smiths</com:AddressName>
                  <com:Street>Rossmarkt 6</com:Street>
                  <com:City>Frankfurt</com:City>
                  <com:State>Hesse</com:State>
                  <com:PostalCode>60311</com:PostalCode>
                  <com:Country>DE</com:Country>
               </com:ShippingAddress>
            </com:DeliveryInfo>-->
            <!--<com:PhoneNumber AreaCode="49" CountryCode="069" Location="FRA" Number="261111111" />-->
			';
if($k==0){
 $soap.='
			<com:PhoneNumber Number="'.$p['phnum'].'" />
            <com:Email EmailID="'.$p['email'].'" Type="Home" />';
}
 $soap.='			
            <!--<com:SSR Carrier="LH" FreeText="P/DE/F1234567/DE/12May57/M/01Jun14/Heinrich/Frederick" SegmentRef="FxUPDmXkROqwcyM2KUHfDg==" Status="HK" Type="DOCS" />-->
            <!--<com:Address>
               <com:AddressName>Smiths</com:AddressName>
               <com:Street>Rossmarkt 6</com:Street>
               <com:City>Frankfurt</com:City>
               <com:State>Hesse</com:State>
               <com:PostalCode>60311</com:PostalCode>
               <com:Country>DE</com:Country>
            </com:Address>-->
         </com:BookingTraveler>';
}
$soap.='
         <com:FormOfPayment xmlns:com="http://www.travelport.com/schema/common_v31_0" Key="'.base64_encode("F1".date("YmdHis")).'" Type="Cash" />
         <air:AirPricingSolution xmlns:air="http://www.travelport.com/schema/air_v31_0" ApproximateBasePrice="'.$APRICESOL['ApproximateBasePrice'].'" ApproximateTotalPrice="'.$APRICESOL['ApproximateTotalPrice'].'" BasePrice="'.$APRICESOL['BasePrice'].'" EquivalentBasePrice="'.$APRICESOL['EquivalentBasePrice'].'" Key="'.base64_encode("APS".date("YmdHis")).'" QuoteDate="'.$APRICESOL['QuoteDate'].'" Taxes="'.$APRICESOL['Taxes'].'" TotalPrice="'.$APRICESOL['TotalPrice'].'">';
foreach($airsegments as $k=>$ASG){		 
 $soap.='
           <air:AirSegment ArrivalTime="'.$ASG['ArrivalTime'].'" AvailabilityDisplayType="'.$ASG['AvailabilityDisplayType'].'" AvailabilitySource="'.$ASG['AvailabilitySource'][0].'" Carrier="'.$ASG['Carrier'].'" ChangeOfPlane="'.$ASG['ChangeOfPlane'].'" ClassOfService="'.$ASG['ClassOfService'].'" DepartureTime="'.$ASG['DepartureTime'].'" Destination="'.$ASG['Destination'].'" Distance="'.$ASG['Distance'].'" Equipment="'.$ASG['Equipment'].'" FlightNumber="'.$ASG['FlightNumber'].'" FlightTime="'.$ASG['FlightTime'].'" Group="'.$ASG['Group'].'" Key="'.$ASG['Key'].'" LinkAvailability="'.$ASG['LinkAvailability'].'" OptionalServicesIndicator="'.$ASG['OptionalServicesIndicator'].'" Origin="'.$ASG['Origin'].'" ParticipantLevel="'.$ASG['ParticipantLevel'].'" PolledAvailabilityOption="'.$ASG['PolledAvailabilityOption'].'" ProviderCode="'.$Provider.'" TravelTime="'.$ASG['TravelTime'].'">
               <air:FlightDetails DepartureTime="'.$ASG['DepartureTime'].'" Destination="'.$ASG['Destination'].'" Equipment="'.$ASG['Equipment'].'" FlightTime="'.$ASG['FlightTime'].'" Key="'.base64_encode("FD".$k).'" Origin="'.$ASG['Origin'].'" TravelTime="'.$ASG['TravelTime'].'">
                  <!--<air:Meals>SnackOrBrunch</air:Meals>
                  <air:InFlightServices>Non-smoking</air:InFlightServices>-->
               </air:FlightDetails>
            </air:AirSegment>';
}
$passengernum=0;
foreach($APRICESOL['AirPricingInfo'] as $k=>$api){		
$soap.='            
            <air:AirPricingInfo ApproximateBasePrice="'.$api['ApproximateBasePrice'].'" ApproximateTotalPrice="'.$api['ApproximateTotalPrice'].'" BasePrice="'.$api['BasePrice'].'" ETicketability="'.$api['ETicketability'].'" EquivalentBasePrice="'.$api['EquivalentBasePrice'].'" IncludesVAT="'.$api['IncludesVAT'].'" Key="'.$api['Key'].'" LatestTicketingTime="'.$api['LatestTicketingTime'].'" PlatingCarrier="'.$api['PlatingCarrier'].'" PricingMethod="'.$api['PricingMethod'].'" ProviderCode="'.$Provider.'" Taxes="'.$api['Taxes'].'" TotalPrice="'.$api['TotalPrice'].'">';
foreach($api['FareInfo'] as $finfo){
$soap.='
               <air:FareInfo Amount="'.$finfo['Amount'].'" DepartureDate="'.$finfo['DepartureDate'].'" Destination="'.$finfo['Destination'].'" EffectiveDate="'.$finfo['EffectiveDate'].'" FareBasis="'.$finfo['FareBasis'].'" Key="'.$finfo['Key'].'" NotValidAfter="'.$finfo['NotValidAfter'].'" NotValidBefore="'.$finfo['NotValidBefore'].'" Origin="'.$finfo['Origin'].'" PassengerTypeCode="'.$finfo['PassengerTypeCode'].'">
                  <common_v31_0:Endorsement xmlns:common_v31_0="http://www.travelport.com/schema/common_v31_0" Value="NONREF/FL/CHG RESTRICTED" />
                  <common_v31_0:Endorsement xmlns:common_v31_0="http://www.travelport.com/schema/common_v31_0" Value="CHECK FARE NOTE" />
                  <air:FareRuleKey FareInfoRef="'.$finfo['Key'].'" ProviderCode="'.$Provider.'">'.$finfo['FareRuleKey'].'</air:FareRuleKey>
               </air:FareInfo>';
}
foreach($api['BookingInfo'] as $binfo){
$soap.='
               <air:BookingInfo BookingCode="'.$binfo['BookingCode'].'" CabinClass="'.$binfo['CabinClass'].'" FareInfoRef="'.$binfo['FareInfoRef'].'" SegmentRef="'.$binfo['SegmentRef'].'" />';
}
foreach($api['TaxInfo'] as $tinfo){
$soap.='
               <air:TaxInfo Amount="'.$tinfo['Amount'].'" Category="'.$tinfo['Category'].'" Key="'.$tinfo['Key'].'" />';
}
$soap.='
               <air:FareCalc>'.$api['FareCalc'].'</air:FareCalc>';
foreach($api['PassengerType'] as $k2=>$ptype){
$soap.='
               <air:PassengerType BookingTravelerRef="'.base64_encode("P".$passengernum++.date("YmdH")).'" Code="'.$ptype['Code'].'" />';
}
$soap.='
               <air:ChangePenalty>
                  <air:Amount>'.$api['ChangePenalty']['Amount'].'</air:Amount>
               </air:ChangePenalty>
               <!--<air:BaggageAllowances>
                  <air:BaggageAllowanceInfo Carrier="LH" Destination="BCN" Origin="MUC" TravelerType="ADT">
                     <air:URLInfo>
                        <air:URL>MYTRIPANDMORE.COM/BAGGAGEDETAILSLH.BAGG</air:URL>
                     </air:URLInfo>
                     <air:TextInfo>
                        <air:Text>1P</air:Text>
                        <air:Text>BAGGAGE DISCOUNTS MAY APPLY BASED ON FREQUENT FLYER STATUS/ ONLINE CHECKIN/FORM OF PAYMENT/MILITARY/ETC.</air:Text>
                     </air:TextInfo>
                     <air:BagDetails ApplicableBags="1stChecked" ApproximateBasePrice="CAD0.00" ApproximateTotalPrice="CAD0.00" BasePrice="EUR0.00" TotalPrice="EUR0.00">
                        <air:BaggageRestriction>
                           <air:TextInfo>
                              <air:Text>UPTO50LB/23KG AND UPTO62LI/158LCM</air:Text>
                           </air:TextInfo>
                        </air:BaggageRestriction>
                     </air:BagDetails>
                     <air:BagDetails ApplicableBags="2ndChecked" ApproximateBasePrice="CAD110.39" ApproximateTotalPrice="CAD110.39" BasePrice="EUR75.00" TotalPrice="EUR75.00">
                        <air:BaggageRestriction>
                           <air:TextInfo>
                              <air:Text>UPTO50LB/23KG AND UPTO62LI/158LCM</air:Text>
                           </air:TextInfo>
                        </air:BaggageRestriction>
                     </air:BagDetails>
                  </air:BaggageAllowanceInfo>
                  <air:BaggageAllowanceInfo Carrier="LH" Destination="MUC" Origin="BCN" TravelerType="ADT">
                     <air:URLInfo>
                        <air:URL>MYTRIPANDMORE.COM/BAGGAGEDETAILSLH.BAGG</air:URL>
                     </air:URLInfo>
                     <air:TextInfo>
                        <air:Text>1P</air:Text>
                        <air:Text>BAGGAGE DISCOUNTS MAY APPLY BASED ON FREQUENT FLYER STATUS/ ONLINE CHECKIN/FORM OF PAYMENT/MILITARY/ETC.</air:Text>
                     </air:TextInfo>
                     <air:BagDetails ApplicableBags="1stChecked" ApproximateBasePrice="CAD0.00" ApproximateTotalPrice="CAD0.00" BasePrice="EUR0.00" TotalPrice="EUR0.00">
                        <air:BaggageRestriction>
                           <air:TextInfo>
                              <air:Text>UPTO50LB/23KG AND UPTO62LI/158LCM</air:Text>
                           </air:TextInfo>
                        </air:BaggageRestriction>
                     </air:BagDetails>
                     <air:BagDetails ApplicableBags="2ndChecked" ApproximateBasePrice="CAD110.39" ApproximateTotalPrice="CAD110.39" BasePrice="EUR75.00" TotalPrice="EUR75.00">
                        <air:BaggageRestriction>
                           <air:TextInfo>
                              <air:Text>UPTO50LB/23KG AND UPTO62LI/158LCM</air:Text>
                           </air:TextInfo>
                        </air:BaggageRestriction>
                     </air:BagDetails>
                  </air:BaggageAllowanceInfo>
                  <air:CarryOnAllowanceInfo Carrier="LH" Destination="BCN" Origin="MUC">
                     <air:TextInfo>
                        <air:Text>1P</air:Text>
                     </air:TextInfo>
                     <air:CarryOnDetails ApplicableCarryOnBags="1" ApproximateBasePrice="CAD0.00" ApproximateTotalPrice="CAD0.00" BasePrice="EUR0.00" TotalPrice="EUR0.00">
                        <air:BaggageRestriction>
                           <air:TextInfo>
                              <air:Text>UPTO18LB/8KG AND UPTO46LI/118LCM</air:Text>
                           </air:TextInfo>
                        </air:BaggageRestriction>
                     </air:CarryOnDetails>
                     <air:CarryOnDetails ApplicableCarryOnBags="2" ApproximateBasePrice="CAD0.00" ApproximateTotalPrice="CAD0.00" BasePrice="EUR0.00" TotalPrice="EUR0.00">
                        <air:BaggageRestriction>
                           <air:TextInfo>
                              <air:Text>CARRY ON PERSONAL ITEMS</air:Text>
                           </air:TextInfo>
                        </air:BaggageRestriction>
                     </air:CarryOnDetails>
                  </air:CarryOnAllowanceInfo>
                  <air:CarryOnAllowanceInfo Carrier="LH" Destination="MUC" Origin="BCN">
                     <air:TextInfo>
                        <air:Text>1P</air:Text>
                     </air:TextInfo>
                     <air:CarryOnDetails ApplicableCarryOnBags="1" ApproximateBasePrice="CAD0.00" ApproximateTotalPrice="CAD0.00" BasePrice="EUR0.00" TotalPrice="EUR0.00">
                        <air:BaggageRestriction>
                           <air:TextInfo>
                              <air:Text>UPTO18LB/8KG AND UPTO46LI/118LCM</air:Text>
                           </air:TextInfo>
                        </air:BaggageRestriction>
                     </air:CarryOnDetails>
                     <air:CarryOnDetails ApplicableCarryOnBags="2" ApproximateBasePrice="CAD0.00" ApproximateTotalPrice="CAD0.00" BasePrice="EUR0.00" TotalPrice="EUR0.00">
                        <air:BaggageRestriction>
                           <air:TextInfo>
                              <air:Text>CARRY ON PERSONAL ITEMS</air:Text>
                           </air:TextInfo>
                        </air:BaggageRestriction>
                     </air:CarryOnDetails>
                  </air:CarryOnAllowanceInfo>
               </air:BaggageAllowances>-->
            </air:AirPricingInfo>';
}
$soap.=' 
         </air:AirPricingSolution>
         <!--<com:ActionStatus xmlns:com="http://www.travelport.com/schema/common_v31_0" ProviderCode="'.$Provider.'" TicketDate="'.date("Y-m-d").'T'.date("H:i:s",strtotime("+7 hours")).'" Type="TAW" />-->
		 <com:ActionStatus xmlns:com="http://www.travelport.com/schema/common_v31_0" ProviderCode="'.$Provider.'" Type="ACTIVE" />
      </univ:AirCreateReservationReq>
   </soapenv:Body>
</soapenv:Envelope>
';
//die($soap);
if(isset($_SESSION['responseArray']) && 1==2){
//if(isset($_SESSION['responseArray'])){ // test mode
	$responseArray=$_SESSION['responseArray'];
}else{
    $auth = base64_encode("$CREDENTIALS"); 
	//$curl = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
	## Rajib Added - 23/10/15
	$curl = curl_init ("https://twsprofiler.travelport.com/Service/Default.ashx/B2BGateway/connect/uAPI/AirService");

	$header = array(
	"Content-Type: text/xml;charset=UTF-8", 
	"Accept: gzip,deflate", 
	"Cache-Control: no-cache", 
	"Pragma: no-cache", 
	"SOAPAction: \"\"",
	"Authorization: Basic $auth", 
	"Content-length: ".strlen($soap),
	); 
	//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
	//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
	curl_setopt($curl, CURLOPT_POST, true ); 
	curl_setopt($curl, CURLOPT_POSTFIELDS, $soap); 
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // this will prevent the curl_exec to return result and will let us to capture output
	$resp = curl_exec($curl);
	curl_close ($curl);
	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
	$xml = simplexml_load_string($xml);
	$json = json_encode($xml);
	$responseArray = json_decode($json,true);	
	
	$_SESSION['responseArray']=$responseArray;
	
}
	
	if(isset($responseArray['SOAPBody']['SOAPFault']['faultstring'])){
	   die($responseArray['SOAPBody']['SOAPFault']['faultstring']);
    }
	
echo "1. Response For Booking:<pre>";
print_r($responseArray);
//die();

	$AirReservationLocatorCode='';
	$UniversalRecordLocatorCode='';
	$AirPricingInfoRef='';
	if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['@attributes']['LocatorCode']))
	$AirReservationLocatorCode=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['@attributes']['LocatorCode'];
	
	$UniversalRecordLocatorCode=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['@attributes']['LocatorCode'];
	
	if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['airAirPricingInfo']['@attributes']['Key']))
    $AirPricingInfoRef=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['airAirPricingInfo']['@attributes']['Key'];
	
	echo "<br>2. ReservationLocatorCode: ".$AirReservationLocatorCode."<br>";
	
$ticketing='
<soapenv:Envelope>
<soapenv:Header/>
<soapenv:Body>
  <air:AirTicketingReq AuthorizedBy="user" BulkTicket="false" ReturnInfoOnFail="true" TargetBranch="'.$TARGETBRANCH.'" TraceId="'.md5("T".date("YmdHis")).'">
    <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
    <air:AirReservationLocatorCode>'.$AirReservationLocatorCode.'</air:AirReservationLocatorCode>
    <air:AirPricingInfoRef Key="'.$AirPricingInfoRef.'"/>
  </air:AirTicketingReq>
</soapenv:Body>
</soapenv:Envelope>';
	
$auth = base64_encode("$CREDENTIALS"); 
$soap_do = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
$header = array(
"Content-Type: text/xml;charset=UTF-8", 
"Accept: gzip,deflate", 
"Cache-Control: no-cache", 
"Pragma: no-cache", 
"SOAPAction: \"\"",
"Authorization: Basic $auth", 
"Content-length: ".strlen($ticketing),
); 
//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($soap_do, CURLOPT_POST, true ); 
curl_setopt($soap_do, CURLOPT_POSTFIELDS, $ticketing); 
curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); // this will prevent the curl_exec to return result and will let us to capture output
$resp = curl_exec($soap_do);
curl_close ($soap_do);
$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
$xml = simplexml_load_string($xml);
$json = json_encode($xml);
$responseArray = json_decode($json,true);

echo "<br><br>3. Response For Ticketing:<pre>";	
print_r($resp);
//die();

$retrieve='
<soapenv:Envelope>
 <soapenv:Body>
	<univ:UniversalRecordRetrieveReq AuthorizedBy="user" TargetBranch="'.$TARGETBRANCH.'" TraceId="'.md5("R".date("YmdHis")).'">
		<com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
		<univ:UniversalRecordLocatorCode>'.$UniversalRecordLocatorCode.'</univ:UniversalRecordLocatorCode>
	</univ:UniversalRecordRetrieveReq>
 </soapenv:Body>
</soapenv:Envelope>
';

$auth = base64_encode("$CREDENTIALS"); 
$soap_do = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
$header = array(
"Content-Type: text/xml;charset=UTF-8", 
"Accept: gzip,deflate", 
"Cache-Control: no-cache", 
"Pragma: no-cache", 
"SOAPAction: \"\"",
"Authorization: Basic $auth", 
"Content-length: ".strlen($retrieve),
); 
//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($soap_do, CURLOPT_POST, true ); 
curl_setopt($soap_do, CURLOPT_POSTFIELDS, $retrieve); 
curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); // this will prevent the curl_exec to return result and will let us to capture output
$resp = curl_exec($soap_do);
curl_close ($soap_do);
//$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
//$xml = simplexml_load_string($xml);
//$json = json_encode($xml);
//$responseArray = json_decode($json,true);

echo "<br><br><br>4. Response of Retrieve with UniversalRecordLocatorCode<pre>";	
print_r($resp);

die();

}
	//echo "<pre>";
	//print_r($p);
	//die();
}

$bookingdata='';
if(isset($_POST['bookingdata'])){
$bookingdata=$_POST['bookingdata'];	
$bookingdata=base64_decode($bookingdata);
$bookingdata=unserialize($bookingdata);	
}
//echo "<pre>";
//print_r($bookingdata);
//die();


$airlines=array();
if(isset($_SESSION['airlines'])){
  $airlines=$_SESSION['airlines'];	
}
$airportscity=array();
$airportsname=array();
if(isset($_SESSION['airportscity'])){
  $airportscity=$_SESSION['airportscity'];	
  $airportsname=$_SESSION['airportsname'];	
}

$countries=array();
if(isset($_SESSION['countries'])){
  $countries=$_SESSION['countries'];	
}

$passengers=array();
if(isset($bookingdata['passengers'][0])){
$passengers=$bookingdata['passengers'][0];
}
$ptypefull=array('ADT'=>'Adult','CHD'=>'Child','INF'=>'Infant');
//echo "<pre>";
//print_r($passengers);
//die();

$AirPricingSolution=$data['AirPricingSolution'];
$airsegments=$data['AirSegment'];

$totalprice=$AirPricingSolution['TotalPrice'];
$currency=substr($totalprice,0,3);
$totalprice=substr($totalprice,3);
$totalprice=$currency."".number_format($totalprice);
			
$taxes=$AirPricingSolution['Taxes'];
$currency=substr($taxes,0,3);
$taxes=substr($taxes,3);
$taxes=$currency."".number_format($taxes);

$pdata=array();
foreach($AirPricingSolution['AirPricingInfo'] as $aps){
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
//echo "<pre>";
//print_r($ptypedata);
//die();

$priceresult=array();
$AirSegment=$data['AirSegment'];
if(isset($AirPricingSolution['AirPricingInfo'][0])){
	$priceresult=$AirPricingSolution['AirPricingInfo'][0];
}


$TARGETBRANCH = 'P7040105';
$CREDENTIALS = 'Universal API/uAPI6986631472-c8b85ad8:P+t2rJ7&M6';
$PCC='3OS2';
$Provider = '1G'; // Any provider you want to use like 1G/1P/1V/ACH

get_header();
if(count($passengers)){
?>
<div class="booking_panel form">
  <div class="container">
    	<div class="row">
          <form name="frmBooknow" id="frmBooknow" method="post" action="">
          <input type="hidden" name="mode" value="frmpost" />
        	<div class="col-sm-7">
            	<h3>Travellers</h3>
                <p>Please ensure that names entered match passport and/or photo identification</p>
                <?php foreach($passengers as $k=>$p){
				if($p=='ADT'){
					$t1=date('Y')-12;
					$t2=date('Y')-113;
				}else if($p=='CHD'){
					$t1=date('Y')-2;
					$t2=date('Y')-12;
				}else if($p=='INF'){
					$t1=date('Y');
					$t2=date('Y')-2;
				}
					
				?>
                <h5><?php echo $ptypefull[$p];?> <?php echo $k+1;?></h5>
                <input type="hidden" name="ptype[]" value="<?php echo $p;?>"  />
            	<div class="content_wrapp">
                <label><strong>*</strong> Title <br />
                <select name="title[]" class="txt_box">
                	<option value="" selected="selected">Select title...</option>
                    <option value="Mr">Mr</option>
                    <option value="Mrs">Mrs</option>
                    <option value="Miss">Miss</option>
                    <option value="Ms">Ms</option>
                </select>
                </label>
                
                <label><strong>*</strong> First Name <br /><input name="first_name[]" type="text" class="txt_box" /></label>
                <label><strong>*</strong> Last Name <br /><input name="last_name[]" type="text" class="txt_box" /></label>
                
                <label><strong>*</strong> Gender <br />                
                <select name="gender[]" class="txt_box">
                    <option value="" selected="selected">Select Gender...</option>
                    <option value="M">Male</option>
                    <option value="F">Female</option>
                    <option value="O">Other</option>
                </select>
                </label>
                
                <label><strong>*</strong> Date of Birth <br />                
                <select name="dob_day[]" class="txt_box">
                    <option value="" selected="selected">Day</option>
                    <?php for($i=1;$i<=31;$i++){?>
                    <option value="<?php  echo sprintf('%02d', $i);?>"><?php echo $i;?></option>
                    <?php } ?>
                </select>
                
                <select name="dob_month[]" class="txt_box">
                    <option value="" selected="selected">Month</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                                
                <select name="dob_year[]" class="txt_box">
                    <option value="" selected="selected">Year</option>
                    <?php for($i=$t1;$i>=$t2;$i--){?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php } ?>
                </select>
                </label>
                
                <?php if($k==0){?>
                <label><strong>*</strong> Email <br /><input name="email" type="text" class="txt_box" /></label>
                <label><strong>*</strong> Phone No. <br /><input name="phnum" type="text" class="txt_box" /></label>                
                <?php } ?>
                
                <label>Do you have your passport details? - optional </label>
                <input id="showPassport<?php echo $k;?>" onfocus="jQuery('#passdiv<?php echo $k;?>').show();jQuery('#passportgiven<?php echo $k;?>').val(1);jQuery(this).addClass('butfocus');" name="" type="button" class="but" value="Yes" /> <input id="hidePassport<?php echo $k;?>" onfocus="jQuery('#passdiv<?php echo $k;?>').hide();jQuery('#passportgiven<?php echo $k;?>').val(0);jQuery('#showPassport<?php echo $k;?>').removeClass('butfocus');" name="" type="button" class="but" value="No" />
                <input type="hidden" name="passportgiven[]" id="passportgiven<?php echo $k;?>" value="0" />
                <div class="toggle" id="passdiv<?php echo $k;?>" style="display:none;">
                <label><strong>*</strong> Country <br />
                <select name="passport_country[]" class="txt_box">
                    <option value="" selected="selected">Select Country...</option>
                    <?php foreach($countries as $c){?>
                     <option value="<?php echo $c['code'];?>"><?php echo $c['name'];?></option>
                     
                    <?php }?>
                </select>
                </label>
                
                <label><strong>*</strong> Passport number <br /><input name="passport_number[]" type="text" class="txt_box" /></label>
                
                <label><strong>*</strong> Expiry <br />                
                <select name="passport_exp_day[]" class="txt_box">
                    <option value="" selected="selected">Day</option>
                    <?php for($i=1;$i<=31;$i++){?>
                    <option value="<?php  echo sprintf('%02d', $i);?>"><?php echo $i;?></option>
                    <?php } ?>
                </select>
                
                <select name="passport_exp_month[]" class="txt_box">
                    <option value="" selected="selected">Month</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                
                <select name="passport_exp_year[]" class="txt_box">
                    <option value="" selected="selected">Year</option>
                   <?php for($i=date("Y");$i<=date("Y")+20;$i++){?>
                    <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php } ?>
                </select>
                </label>
                </div>
                
                </div>
                <?php } ?>
            </div><!--left-->
         </form>   
            <div class="max">
            <div class="col-sm-5">
            <h5>Your Booking</h5>            
            	<div class="col-sm-12"><!--left section--> 
<?php if($_POST['searchmode']=='roundtrip'){?>
<?php 
$hd=0;
$totalduration=0;
$n=0;
$depttimes=array();
$arrivaltimes=array();
foreach($priceresult['BookingInfo'] as $BI){
$segmentref=$BI['SegmentRef'];
$skey=getSegmentKey($segmentref,$AirSegment);
$segmentdtls=$airsegments[$skey];	
// only departure leg
if($segmentdtls['Group']==0){
$thisCarrier=$segmentdtls['Carrier'];
$thisairlinename=$airlines[$thisCarrier];
$FlightNumber=$segmentdtls['FlightNumber'];
$aircraft=$segmentdtls['Equipment'];
//$aircraft="Airbus A330-300"
$airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';

$totalduration=$totalduration + $segmentdtls['FlightTime'];
$depttimes[$n]=$segmentdtls['DepartureTime'];
$arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
?>                
                <?php 
				     // displaying header
				     if(!$hd){
					?>
                    <h6>Departing</h6>                
                        <div class="pad">
                     <?php $hd=1;}?> 
                    <?php 
					if($n){
					$t1=$arrivaltimes[$n-1];	
					$t2=$depttimes[$n];	
					$layover=getTimeDiff($t1,$t2);
					?> 
                    <div class="layover">Layover <?php echo $layover;?></div>
                    <?php } ?>
                    
                	<div class="col-sm-5">                    
                    <p class="right_arrow">
                    <strong>Sydney</strong><br />
                    SYD<br />
                    Sydney Airport (Kingsford Smith Airport)<br />
                    <span>11:15 AM</span><br />
                    Fri, 28 Aug 2015
                    </p>
                    </div>
                    <!--col 1 end-->
                    <div class="col-sm-4">
                    <p>
                    <strong>Singapore</strong><br />
                    SIN<br />
                    Singapore Changi Airport<br />
                    <span>17:40 PM</span><br />
                    Fri, 28 Aug 2015
                    </p>
                    </div>
                    <!--col 2 end-->
                    <div class="col-sm-3 eco">
                    <p>                    
                    <a href="#" class="link">Economy</a><br />
                    <section class="time">8hr  25m</section>
                    </p>
                    </div>
                    <!--col 3 end-->
                    <div class="mrgn">
                    <div class="bottom_row"><img src="<?php echo get_template_directory_uri(); ?>/images/sq.png" /><p>Singapore Airlines - <strong>SQ232</strong> - Airbus A380-800 </p>
                    </div>
                    </div>
                    
                    <div class="responsive">
                    <div class="bottom_row"><img src="<?php echo get_template_directory_uri(); ?>/images/sq.png" /><p>Singapore Airlines - <strong>SQ232</strong> - Airbus A380-800 
                    <a href="#">Economy</a></p>
                    </div>
                    <div class="responsive_time">
                    <section class="time"><span>Duration</span><br />8hr  25m</section>
                    </div>
                    </div>
                    <div class="clearfix"></div>
                    <!--Departing end-->                    
                     <?php $n++; }} ?>
                    <div class="total_duration"> <strong>Total Duration</strong> <?php echo floor($totalduration/60);?>hrs <?php echo ceil($totalduration%60);?>mins</div>
                    <div class="clearfix"></div>
 <?php 
$hd=0;
$totalduration=0;
$n=0;
$depttimes=array();
$arrivaltimes=array();
foreach($priceresult['BookingInfo'] as $BI){
$segmentref=$BI['SegmentRef'];
$skey=getSegmentKey($segmentref,$AirSegment);
$segmentdtls=$airsegments[$skey];
if($segmentdtls['Group']==1){	
$thisCarrier=$segmentdtls['Carrier'];
$thisairlinename=$airlines[$thisCarrier];
$FlightNumber=$segmentdtls['FlightNumber'];
$aircraft=$segmentdtls['Equipment'];
//$aircraft="Airbus A330-300"
$airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';
$totalduration=$totalduration + $segmentdtls['FlightTime'];
$depttimes[$n]=$segmentdtls['DepartureTime'];
$arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
?>  
				   <?php 
				   // displaying header
				   if(!$hd && $segmentdtls['Group']==1){
				   ?> 
                   <h6>Returning</h6>                
                   <?php $hd=1;}?> 
                   
                    <?php 
					if($n){
					$t1=$arrivaltimes[$n-1];	
					$t2=$depttimes[$n];	
					$layover=getTimeDiff($t1,$t2);
					?> 
                    <div class="layover">Layover <?php echo $layover;?></div>
                    <?php } ?>
               
                	<div class="col-sm-5">                    
                    <p class="right_arrow">
                    <strong><?php echo $airportscity[$segmentdtls['Origin']];?></strong><br />
                    <?php echo $segmentdtls['Origin'];?><br />
                    <?php echo $airportsname[$segmentdtls['Origin']];?><br />
                    <span><?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?></span><br />
                    <?php echo date("D, d M Y",strtotime($segmentdtls['DepartureTime']));?>
                    </p>
                    </div>
                    <!--col 1 end-->
                    <div class="col-sm-4">
                    <p>
                    <strong><?php echo $airportscity[$segmentdtls['Destination']];?></strong><br />
                    <?php echo $segmentdtls['Destination'];?><br />
                    <?php echo $airportsname[$segmentdtls['Destination']];?><br />
                    <span><?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?></span><br />
                    <?php echo date("D, d M Y",strtotime($segmentdtls['ArrivalTime']));?>
                    </p>
                    </div>
                    <!--col 2 end-->
                    <div class="col-sm-3 eco">
                    <p>                    
                    <a href="#" class="link"><?php echo $BI['CabinClass'];?></a><br />
                    <section class="time"><?php echo floor($segmentdtls['FlightTime']/60);?>hrs <?php echo ceil($segmentdtls['FlightTime']%60);?>mins</section>
                    </p>
                    </div>
                    <!--col 3 end-->
                    <div class="mrgn">
                    <div class="bottom_row"><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" /><p><?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p>
                    </div>
                    </div>
                    
                    <div class="responsive">
                    <div class="bottom_row"><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" /><p><?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?> 
                    <a href="#"><?php echo $BI['CabinClass'];?></a></p>
                    </div>
                    <div class="responsive_time">
                    <section class="time"><span>Duration</span><br />8hr  25m</section>
                    </div>
                    </div>
                    <div class="clearfix"></div>
                    <!--Departing end-->                    
                     <?php $n++;}} ?>
                    <div class="total_duration"> <strong>Total Duration</strong> <?php echo floor($totalduration/60);?>hrs <?php echo ceil($totalduration%60);?>mins</div>
                    <div class="clearfix"></div>  
                </div>
                </div>
<?php }else if($_POST['searchmode']=='oneway'){ ?>
                <h6>Departing</h6> 
                  <div class="pad">   
<?php 
$hd=0;
$totalduration=0;
$n=0;
$depttimes=array();
$arrivaltimes=array();
foreach($priceresult['BookingInfo'] as $BI){
$segmentref=$BI['SegmentRef'];
$skey=getSegmentKey($segmentref,$AirSegment);
$segmentdtls=$airsegments[$skey];	
// only departure leg
$thisCarrier=$segmentdtls['Carrier'];
$thisairlinename=$airlines[$thisCarrier];
$FlightNumber=$segmentdtls['FlightNumber'];
$aircraft=$segmentdtls['Equipment'];
//$aircraft="Airbus A330-300"
$airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';

$totalduration=$totalduration + $segmentdtls['FlightTime'];
$depttimes[$n]=$segmentdtls['DepartureTime'];
$arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
?>              
                    
                                
                    <?php 
					if($n){
					$t1=$arrivaltimes[$n-1];	
					$t2=$depttimes[$n];	
					$layover=getTimeDiff($t1,$t2);
					?> 
                    <div class="layover">Layover <?php echo $layover;?></div>
                    <?php } ?>
                    
                	<div class="col-sm-5">                    
                    <p class="right_arrow">
                    <strong><?php echo $airportscity[$segmentdtls['Origin']];?></strong><br />
                    <?php echo $segmentdtls['Origin'];?><br />
                    <?php echo $airportsname[$segmentdtls['Origin']];?><br />
                    <span><?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?></span><br />
                    <?php echo date("D, d M Y",strtotime($segmentdtls['DepartureTime']));?>
                    </p>
                    </div>
                    <!--col 1 end-->
                    <div class="col-sm-4">
                    <p>
                    <strong><?php echo $airportscity[$segmentdtls['Destination']];?></strong><br />
                    <?php echo $segmentdtls['Destination'];?><br />
                    <?php echo $airportsname[$segmentdtls['Destination']];?><br />
                    <span><?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?></span><br />
                    <?php echo date("D, d M Y",strtotime($segmentdtls['ArrivalTime']));?>
                    </p>
                    </div>
                    <!--col 2 end-->
                    <div class="col-sm-3 eco">
                    <p>                    
                    <a href="#" class="link"><?php echo $BI['CabinClass'];?></a><br />
                    <section class="time"><?php echo floor($segmentdtls['FlightTime']/60);?>hrs <?php echo ceil($segmentdtls['FlightTime']%60);?>mins</section>
                    </p>
                    </div>
                    <!--col 3 end-->
                    <div class="mrgn">
                    <div class="bottom_row"><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" /><p><?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p>
                    </div>
                    </div>
                    
                    <div class="responsive">
                    <div class="bottom_row"><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" /><p><?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?> 
                    <a href="#"><?php echo $BI['CabinClass'];?></a></p>
                    </div>
                    <div class="responsive_time">
                    <section class="time"><span>Duration</span><br />8hr  25m</section>
                    </div>
                    </div>
                    <div class="clearfix"></div>
                    <!--Departing end-->                    
                     <?php $n++; } ?>
                    <div class="total_duration"> <strong>Total Duration</strong> <?php echo floor($totalduration/60);?>hrs <?php echo ceil($totalduration%60);?>mins</div>
                    <div class="clearfix"></div>
                    </div>
                    </div>
<?php }else if($_POST['searchmode']=='multicity'){ ?>
                   
<?php 
$hd=0;
$totalduration=0;
$n=0;
$depttimes=array();
$arrivaltimes=array();
foreach($priceresult['BookingInfo'] as $BI){
$segmentref=$BI['SegmentRef'];
$skey=getSegmentKey($segmentref,$AirSegment);
$segmentdtls=$airsegments[$skey];	
// only departure leg
$thisCarrier=$segmentdtls['Carrier'];
$thisairlinename=$airlines[$thisCarrier];
$FlightNumber=$segmentdtls['FlightNumber'];
$aircraft=$segmentdtls['Equipment'];
//$aircraft="Airbus A330-300"
$airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';

//$totalduration=$totalduration + $segmentdtls['FlightTime'];
$totalduration=$segmentdtls['FlightTime'];
$depttimes[$n]=$segmentdtls['DepartureTime'];
$arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
?>              
                    
                                
                    <h6>Departing</h6> 
                      <div class="pad">
                    
                	<div class="col-sm-5">                    
                    <p class="right_arrow">
                    <strong><?php echo $airportscity[$segmentdtls['Origin']];?></strong><br />
                    <?php echo $segmentdtls['Origin'];?><br />
                    <?php echo $airportsname[$segmentdtls['Origin']];?><br />
                    <span><?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?></span><br />
                    <?php echo date("D, d M Y",strtotime($segmentdtls['DepartureTime']));?>
                    </p>
                    </div>
                    <!--col 1 end-->
                    <div class="col-sm-4">
                    <p>
                    <strong><?php echo $airportscity[$segmentdtls['Destination']];?></strong><br />
                    <?php echo $segmentdtls['Destination'];?><br />
                    <?php echo $airportsname[$segmentdtls['Destination']];?><br />
                    <span><?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?></span><br />
                    <?php echo date("D, d M Y",strtotime($segmentdtls['ArrivalTime']));?>
                    </p>
                    </div>
                    <!--col 2 end-->
                    <div class="col-sm-3 eco">
                    <p>                    
                    <a href="#" class="link"><?php echo $BI['CabinClass'];?></a><br />
                    <section class="time"><?php echo floor($segmentdtls['FlightTime']/60);?>hrs <?php echo ceil($segmentdtls['FlightTime']%60);?>mins</section>
                    </p>
                    </div>
                    <!--col 3 end-->
                    <div class="mrgn">
                    <div class="bottom_row"><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" /><p><?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p>
                    </div>
                    </div>
                    
                    <div class="responsive">
                    <div class="bottom_row"><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" /><p><?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?> 
                    <a href="#"><?php echo $BI['CabinClass'];?></a></p>
                    </div>
                    <div class="responsive_time">
                    <section class="time"><span>Duration</span><br />8hr  25m</section>
                    </div>
                    </div>
                    <div class="clearfix"></div>
                    <!--Departing end-->                    
                    <div class="total_duration"> <strong>Total Duration</strong> <?php echo floor($totalduration/60);?>hrs <?php echo ceil($totalduration%60);?>mins</div>
                    <div class="clearfix"></div>
                    </div>
                     <?php $n++; } ?>
                  </div>
<?php }?>
                <!--Left Section End-->               
                
                <div class="clearfix"></div>
                
          <div class="submit_panel">
       		<input name="" type="button" onclick="sbmt();" value="Book Online" class="submit" />
            <br  clear="all"/>
            <p>Secure methods of payments</p>
            <img src="<?php echo get_template_directory_uri(); ?>/images/cards.jpg" /></div>
				
            </div><!--right-->
        </div>  
        </div>      
    </div>
</div>
<script>
function sbmt(){
 jQuery('#frmBooknow').submit();
}
</script>
<?php
}// count passengers
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
get_footer();

/*
ACTIVE 	 Set the status to Active for ACH requests where an instant purchase occurs.

TAW  Ticket At Will. Sets the time at which the PNR will be placed on the ticketing queue to be ticketed. The PNR will NOT be ticketed automatically, just placed on the queue.
Note: Requires a corresponding TicketDate attribute.

TTL  Ticket Time Limit. Sets the time at which the PNR will be placed on the ticketing queue to be ticketed. The PNR will NOT be ticketed automatically, just placed on the queue.
Typically, the 'TTL' value is preferred so that the PNR will be canceled automatically if there is a problem or if it is never ticketed.
Note: Requires a corresponding TicketDate attribute.

TAU  Arrange Ticketing Date. Equivalent to TAX in Worldspan.

Unknown Default action status, If no action status is specified in a request.Equivalent to 'T/' in Galileo. 
*/