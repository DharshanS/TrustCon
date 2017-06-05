<?php
/*
Template Name: Flight Result Lowprice
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
 
set_time_limit(0);
$jsondata=array();
$carriers=array();
$prices=array();
require_once("wp-config.php");
$dt=unserialize (base64_decode($_POST['dt']));
$alink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$airlines=array();
if(isset($_SESSION['airlines'])){
  $airlines=$_SESSION['airlines'];	
}else{
$SQL="SELECT `iata`,`airline` FROM `airlines` WHERE `iata`!=''";
$rs = mysqli_query($alink, $SQL);
while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
 $airlines[$row['iata']]=$row['airline'];
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
$rs = mysqli_query($alink, $SQL);
while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
  $airportscity[$row['iata_code']]=$row['city'];
  $airportsname[$row['iata_code']]=$row['airport_name'];
}
$_SESSION['airportscity']=$airportscity;
$_SESSION['airportsname']=$airportsname;
}

/*echo "<pre>";
print_r($_POST);
die();*/


$flightsresult=array();
if(isset($_SESSION['baseresponse']) && is_array($_SESSION['baseresponse']) && 1==2){
//if(isset($_SESSION['baseresponse']) && is_array($_SESSION['baseresponse'])){ // test mode
	$responseArray=$_SESSION['baseresponse'];
	$searchdata=$_SESSION['searchdata'];
}else{
$from_city='';
$from_city_full='';
$from_country='';
$to_city='';
$to_city_full='';
$to_country='';
$searchdata=array();

$startdt='';
$enddt='';

$adult=isset($_POST['adult'])?$_POST['adult']:1;
$child=isset($_POST['child'])?$_POST['child']:0;
$infant=isset($_POST['infant'])?$_POST['infant']:0;
$cabinclass=isset($_POST['cabinclass'])?$_POST['cabinclass']:'Economy';
$date_flexi=isset($_POST['date_flexi'])?$_POST['date_flexi']:0;


if(isset($_POST['from_city'])){
$from_city=$_POST['from_city'];	
$fromcityarr=explode(",",$from_city);
$from_city=$fromcityarr[0];
if(isset($fromcityarr[1])){
	 $from_city_full=$fromcityarr[1];
 }else{
	 $from_city_full=''; 
 }
if(count($fromcityarr)>1)$from_country=end($fromcityarr);
}
$searchdata['from_airport']=$from_city;
$searchdata['from_city']=$from_city_full;

if(isset($_POST['to_city'])){
$to_city=$_POST['to_city'];	
$tocityarr=explode(",",$to_city);
$to_city=$tocityarr[0];
if(isset($tocityarr[1])){
	 $to_city_full=$tocityarr[1];
 }else{
	 $to_city_full=''; 
 }
if(count($tocityarr)>1)$to_country=end($tocityarr);
}
$searchdata['to_airport']=$to_city;
$searchdata['to_city']=$to_city_full;

if(isset($_POST['depart_date'])){
	$startdt=$_POST['depart_date'];
	$startdtarr=explode(",",$startdt);
	$startdt=$startdtarr[0];
	$startdt=date("Y-m-d",strtotime($startdt));
}
if(isset($_POST['return_date'])){
    $enddt=$_POST['return_date'];
	$enddtarr=explode(",",$enddt);
	$enddt=$enddtarr[0];
	$enddt=date("Y-m-d",strtotime($enddt));
}
$searchdata['start_date']=$startdt;
$searchdata['end_date']=$enddt;

$passengers=array();
for($i=0;$i<$adult;$i++){
	$passengers[]='ADT';
}
for($i=0;$i<$child;$i++){
	$passengers[]='CHD';
}
for($i=0;$i<$infant;$i++){
	$passengers[]='INF';
}
$searchdata['passengers']=$passengers;

$searchdata['cabinclass']=$cabinclass;

$searchdata['adult']=$adult;
$searchdata['child']=$child;
$searchdata['infant']=$infant;


$searchdata['date_flexi']=$date_flexi;

$searchdata['mode']=$_POST['mode'];

$_SESSION['searchdata']=$searchdata;

$TARGETBRANCH = 'P7040105';
$CREDENTIALS = 'Universal API/uAPI6986631472-c8b85ad8:P+t2rJ7&M6';
$PCC='3OS2';
$Provider = '1G'; // Any provider you want to use like 1G/1P/1V/ACH

if($searchdata['mode']=='oneway'){
$message = '
	<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
	   <soapenv:Header/>
	   <soapenv:Body>
			 <LowFareSearchReq xmlns="http://www.travelport.com/schema/air_v32_0" TraceId="'.md5("L1").'" TargetBranch="'.$TARGETBRANCH.'" ReturnUpsellFare="true">
			  <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v32_0" OriginApplication="uAPI" />
			  <SearchAirLeg>
				<SearchOrigin>
				  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['from_airport'].'" PreferCity="true" />
				</SearchOrigin>
				<SearchDestination>
				  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['to_airport'].'" PreferCity="true" />
				</SearchDestination>
				<SearchDepTime PreferredTime="'.$searchdata['start_date'].'" />
				<!--<SearchDepTime PreferredTime="'.$searchdata['start_date'].'">
				  <SearchExtraDays DaysBefore="3" DaysAfter="3" xmlns="http://www.travelport.com/schema/common_v32_0" />
				</SearchDepTime>-->
				<AirLegModifiers>
				  <PreferredCabins>
					<CabinClass Type="'.$searchdata['cabinclass'].'" xmlns="http://www.travelport.com/schema/common_v32_0" />
				  </PreferredCabins>
				</AirLegModifiers>
			  </SearchAirLeg>
			  <AirSearchModifiers>
				<PreferredProviders>
				  <Provider xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$Provider.'" />
				</PreferredProviders>
			  </AirSearchModifiers>';
		 foreach($searchdata['passengers'] as $k=>$psgcode){
         $message .='<SearchPassenger xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'"/>';
		 }
	$message .='
            <!--<AirPricingModifiers FaresIndicator="PublicAndPrivateFares" CurrencyType="INR" />-->	
         </LowFareSearchReq>
	   </soapenv:Body>
	</soapenv:Envelope>
	';
}else if($searchdata['mode']=='roundtrip'){
$message='
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Header/>
	<soapenv:Body>
		<LowFareSearchReq xmlns="http://www.travelport.com/schema/air_v32_0" TraceId="'.md5("L2".date("YmdHis")).'" TargetBranch="'.$TARGETBRANCH.'" ReturnUpsellFare="true">
		  <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v32_0" OriginApplication="uAPI" />
		  <SearchAirLeg>
			<SearchOrigin>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['from_airport'].'" PreferCity="true" />
			</SearchOrigin>
			<SearchDestination>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['to_airport'].'" PreferCity="true" />
			</SearchDestination>
			<SearchDepTime PreferredTime="'.$searchdata['start_date'].'" />
			<AirLegModifiers>
			  <PreferredCabins>
				<CabinClass Type="'.$searchdata['cabinclass'].'" xmlns="http://www.travelport.com/schema/common_v32_0" />
			  </PreferredCabins>
			</AirLegModifiers>
		  </SearchAirLeg>
		  <SearchAirLeg>
			<SearchOrigin>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['to_airport'].'" PreferCity="true" />
			</SearchOrigin>
			<SearchDestination>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['from_airport'].'" PreferCity="true" />
			</SearchDestination>
			<SearchDepTime PreferredTime="'.$searchdata['end_date'].'" />
			<AirLegModifiers>
			  <PreferredCabins>
				<CabinClass Type="'.$searchdata['cabinclass'].'" xmlns="http://www.travelport.com/schema/common_v32_0" />
			  </PreferredCabins>
			</AirLegModifiers>
		  </SearchAirLeg>
		  <AirSearchModifiers>
			<PreferredProviders>
			  <Provider xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$Provider.'" />
			</PreferredProviders>
		  </AirSearchModifiers>';
foreach($searchdata['passengers'] as $k=>$psgcode){
 $message .='<SearchPassenger xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'"/>';
}
$message .='
<!--<AirPricingModifiers FaresIndicator="PublicAndPrivateFares" CurrencyType="INR" />-->
</LowFareSearchReq>
	 </soapenv:Body>
	</soapenv:Envelope>
';		
}
	
	$auth = base64_encode("$CREDENTIALS"); 
	$soap_do = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
	$header = array(
	"Content-Type: text/xml;charset=UTF-8", 
	"Accept: gzip,deflate", 
	"Cache-Control: no-cache", 
	"Pragma: no-cache", 
	"SOAPAction: \"\"",
	"Authorization: Basic $auth", 
	"Content-length: ".strlen($message),
	); 
	//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
	//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
	curl_setopt($soap_do, CURLOPT_POST, true ); 
	curl_setopt($soap_do, CURLOPT_POSTFIELDS, $message); 
	curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
	curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); // this will prevent the curl_exec to return result and will let us to capture output
	$resp = curl_exec($soap_do);
	curl_close ($soap_do);
	//die($resp);
	
/*
$resp='
	
<SOAP:Envelope xmlns:SOAP="http://schemas.xmlsoap.org/soap/envelope/">
   <SOAP:Body>
      <air:LowFareSearchRsp xmlns:air="http://www.travelport.com/schema/air_v32_0" TraceId="c3ca52ad20afcec4355fcb9b23f6cbe2" TransactionId="271D0F9B0A07643B073510C52D28DF12" ResponseTime="44110" DistanceUnits="MI" CurrencyType="LKR">
         <air:FlightDetailsList>
            <air:FlightDetails Key="sF6V09EGThu+tUb/MCr1ig==" Origin="DEL" Destination="CCU" DepartureTime="2015-08-15T14:15:00.000+05:30" ArrivalTime="2015-08-15T16:20:00.000+05:30" FlightTime="125" TravelTime="125" Equipment="788" OriginTerminal="3" />
            <air:FlightDetails Key="sOM6/N8VQnCmSfbkxOgf1A==" Origin="DEL" Destination="CCU" DepartureTime="2015-08-15T17:00:00.000+05:30" ArrivalTime="2015-08-15T19:05:00.000+05:30" FlightTime="125" TravelTime="125" Equipment="321" OriginTerminal="3" DestinationTerminal="2" />
            <air:FlightDetails Key="iEpdY6FAQICJ0zWI8Oeh1g==" Origin="DEL" Destination="CCU" DepartureTime="2015-08-15T20:15:00.000+05:30" ArrivalTime="2015-08-15T22:20:00.000+05:30" FlightTime="125" TravelTime="125" Equipment="321" OriginTerminal="3" />
            <air:FlightDetails Key="daVbfT+WTSa5IB6AvePDmg==" Origin="CCU" Destination="DEL" DepartureTime="2015-08-22T20:15:00.000+05:30" ArrivalTime="2015-08-22T22:30:00.000+05:30" FlightTime="135" TravelTime="135" Equipment="321" DestinationTerminal="3" />
            <air:FlightDetails Key="fM27K8MVT5yEejqqUw1glQ==" Origin="CCU" Destination="DEL" DepartureTime="2015-08-22T17:30:00.000+05:30" ArrivalTime="2015-08-22T19:50:00.000+05:30" FlightTime="140" TravelTime="140" Equipment="788" OriginTerminal="2" DestinationTerminal="3" />
            <air:FlightDetails Key="9HmvlwAjSFC5HypzchQo7g==" Origin="CCU" Destination="DEL" DepartureTime="2015-08-22T06:45:00.000+05:30" ArrivalTime="2015-08-22T09:10:00.000+05:30" FlightTime="145" TravelTime="145" Equipment="321" OriginTerminal="2" DestinationTerminal="3" />
            <air:FlightDetails Key="ak3JEi3yR3Cg1qQtxpPaSQ==" Origin="CCU" Destination="DEL" DepartureTime="2015-08-22T10:00:00.000+05:30" ArrivalTime="2015-08-22T12:10:00.000+05:30" FlightTime="130" TravelTime="130" Equipment="788" DestinationTerminal="3" />
            <air:FlightDetails Key="LWRxwFGuSWaKMHN/dwIdRw==" Origin="DEL" Destination="CCU" DepartureTime="2015-08-15T07:00:00.000+05:30" ArrivalTime="2015-08-15T09:05:00.000+05:30" FlightTime="125" TravelTime="125" Equipment="788" OriginTerminal="3" DestinationTerminal="2" />
            <air:FlightDetails Key="+g86BGDETGi4f0pNl+pWyA==" Origin="CCU" Destination="BOM" DepartureTime="2015-08-22T06:00:00.000+05:30" ArrivalTime="2015-08-22T08:50:00.000+05:30" FlightTime="170" TravelTime="1065" Equipment="319" OriginTerminal="2" DestinationTerminal="1A" />
            <air:FlightDetails Key="mWJyRGIRQAumT2GLRxOREQ==" Origin="BOM" Destination="DEL" DepartureTime="2015-08-22T21:30:00.000+05:30" ArrivalTime="2015-08-22T23:45:00.000+05:30" FlightTime="135" TravelTime="1065" Equipment="77W" OriginTerminal="2" DestinationTerminal="3" />
            <air:FlightDetails Key="WffetBFIQr+uKuVLDvKyGw==" Origin="CCU" Destination="BOM" DepartureTime="2015-08-22T19:40:00.000+05:30" ArrivalTime="2015-08-22T22:35:00.000+05:30" FlightTime="175" TravelTime="1650" Equipment="319" OriginTerminal="2" DestinationTerminal="1A" />
            <air:FlightDetails Key="bjfE540+QB+0G7bYnYZmFA==" Origin="BOM" Destination="DEL" DepartureTime="2015-08-23T21:00:00.000+05:30" ArrivalTime="2015-08-23T23:10:00.000+05:30" FlightTime="130" TravelTime="1650" Equipment="321" OriginTerminal="1A" DestinationTerminal="3" />
            <air:FlightDetails Key="YiMlGjdmSeWaE46yGDgo4g==" Origin="BOM" Destination="DEL" DepartureTime="2015-08-23T21:30:00.000+05:30" ArrivalTime="2015-08-23T23:45:00.000+05:30" FlightTime="135" TravelTime="1685" Equipment="77W" OriginTerminal="2" DestinationTerminal="3" />
            <air:FlightDetails Key="5AF6hWRLRXmseEZzb28xMA==" Origin="DEL" Destination="BOM" DepartureTime="2015-08-15T23:00:00.000+05:30" ArrivalTime="2015-08-16T01:10:00.000+05:30" FlightTime="130" TravelTime="590" Equipment="321" OriginTerminal="3" DestinationTerminal="2" />
            <air:FlightDetails Key="9ViRVBU4TMqO1MXt+x4/kg==" Origin="BOM" Destination="CCU" DepartureTime="2015-08-16T06:10:00.000+05:30" ArrivalTime="2015-08-16T08:50:00.000+05:30" FlightTime="160" TravelTime="590" Equipment="320" OriginTerminal="1A" DestinationTerminal="2" />
            <air:FlightDetails Key="mcH902NPRBSUBP4B9DVSzQ==" Origin="DEL" Destination="BOM" DepartureTime="2015-08-15T16:45:00.000+05:30" ArrivalTime="2015-08-15T19:05:00.000+05:30" FlightTime="140" TravelTime="965" Equipment="77W" OriginTerminal="3" DestinationTerminal="2" />
            <air:FlightDetails Key="svn8w8BgQl+teluoWk6xkg==" Origin="DEL" Destination="GAU" DepartureTime="2015-08-15T10:50:00.000+05:30" ArrivalTime="2015-08-15T13:10:00.000+05:30" FlightTime="140" TravelTime="1560" Equipment="321" OriginTerminal="3" />
            <air:FlightDetails Key="WuOjY1HeQsqVL8slTFsxEA==" Origin="GAU" Destination="CCU" DepartureTime="2015-08-16T11:40:00.000+05:30" ArrivalTime="2015-08-16T12:50:00.000+05:30" FlightTime="70" TravelTime="1560" Equipment="319" DestinationTerminal="2" />
            <air:FlightDetails Key="ri+sA/5aRe2dG377GEM0Fg==" Origin="CCU" Destination="GAU" DepartureTime="2015-08-22T09:50:00.000+05:30" ArrivalTime="2015-08-22T10:55:00.000+05:30" FlightTime="65" TravelTime="585" Equipment="319" OriginTerminal="2" />
            <air:FlightDetails Key="7hdcFJzsQZCd1dg1rbO72A==" Origin="GAU" Destination="DEL" DepartureTime="2015-08-22T17:05:00.000+05:30" ArrivalTime="2015-08-22T19:35:00.000+05:30" FlightTime="150" TravelTime="585" Equipment="321" DestinationTerminal="3" />
            <air:FlightDetails Key="pGKRBzwiR9mO30RUj3ZcyQ==" Origin="BOM" Destination="DEL" DepartureTime="2015-08-22T13:00:00.000+05:30" ArrivalTime="2015-08-22T15:05:00.000+05:30" FlightTime="125" TravelTime="545" Equipment="320" OriginTerminal="1A" DestinationTerminal="3" />
            <air:FlightDetails Key="kKeTsVHPTH2LRK0gh+PolA==" Origin="BOM" Destination="DEL" DepartureTime="2015-08-23T13:00:00.000+05:30" ArrivalTime="2015-08-23T15:05:00.000+05:30" FlightTime="125" TravelTime="1165" Equipment="320" OriginTerminal="1A" DestinationTerminal="3" />
            <air:FlightDetails Key="29h9cHvFTJusj5PIgvs+AQ==" Origin="DEL" Destination="BOM" DepartureTime="2015-08-15T13:00:00.000+05:30" ArrivalTime="2015-08-15T15:10:00.000+05:30" FlightTime="130" TravelTime="490" Equipment="321" OriginTerminal="3" DestinationTerminal="1A" />
            <air:FlightDetails Key="me694bc2QVmALz6XUuer9g==" Origin="BOM" Destination="CCU" DepartureTime="2015-08-15T18:30:00.000+05:30" ArrivalTime="2015-08-15T21:10:00.000+05:30" FlightTime="160" TravelTime="490" Equipment="319" OriginTerminal="1A" DestinationTerminal="2" />
            <air:FlightDetails Key="jLxIyPhsT3WElwL8YFSzHA==" Origin="GAU" Destination="CCU" DepartureTime="2015-08-16T10:25:00.000+05:30" ArrivalTime="2015-08-16T11:55:00.000+05:30" FlightTime="90" TravelTime="1505" Equipment="ATR" DestinationTerminal="2" />
            <air:FlightDetails Key="dShyPEwkQIyrFvaOYzFR8A==" Origin="CCU" Destination="HYD" DepartureTime="2015-08-22T13:25:00.000+05:30" ArrivalTime="2015-08-22T15:35:00.000+05:30" FlightTime="130" TravelTime="585" Equipment="319" OriginTerminal="2" />
            <air:FlightDetails Key="82nUnd4KTQqDwpgZxW5UFA==" Origin="HYD" Destination="DEL" DepartureTime="2015-08-22T20:55:00.000+05:30" ArrivalTime="2015-08-22T23:10:00.000+05:30" FlightTime="135" TravelTime="585" Equipment="77W" DestinationTerminal="3" />
            <air:FlightDetails Key="bCEIHp+0TI+mgRssXLJ07w==" Origin="BOM" Destination="HYD" Equipment="319" />
            <air:FlightDetails Key="8fMdmHN4RhSRd770wHkpTQ==" Origin="HYD" Destination="CCU" Equipment="319" />
            <air:FlightDetails Key="6qeJHeqwSBSekohbwLhx9g==" Origin="BOM" Destination="GOI" DepartureTime="2015-08-23T05:15:00.000+05:30" ArrivalTime="2015-08-23T06:30:00.000+05:30" FlightTime="75" TravelTime="1235" Equipment="321" OriginTerminal="2" />
            <air:FlightDetails Key="P8Ez8MTQQaCcuGalnwBPZA==" Origin="GOI" Destination="DEL" DepartureTime="2015-08-23T13:55:00.000+05:30" ArrivalTime="2015-08-23T16:15:00.000+05:30" FlightTime="140" TravelTime="1235" Equipment="319" DestinationTerminal="3" />
            <air:FlightDetails Key="g8Mg69guRqm7Wkwoyd8n7w==" Origin="BOM" Destination="GOI" DepartureTime="2015-08-22T13:00:00.000+05:30" ArrivalTime="2015-08-22T14:10:00.000+05:30" FlightTime="70" TravelTime="2055" Equipment="321" OriginTerminal="1A" />
            <air:FlightDetails Key="A5qNiHLfRLOQwzY3tnDA8w==" Origin="DEL" Destination="NAG" DepartureTime="2015-08-15T05:45:00.000+05:30" ArrivalTime="2015-08-15T07:15:00.000+05:30" FlightTime="90" TravelTime="1625" Equipment="319" OriginTerminal="3" />
            <air:FlightDetails Key="ZBYuVvJfRtmlf4ES5PF5CA==" Origin="NAG" Destination="BOM" DepartureTime="2015-08-15T20:45:00.000+05:30" ArrivalTime="2015-08-15T22:15:00.000+05:30" FlightTime="90" TravelTime="1625" Equipment="319" DestinationTerminal="1A" />
         </air:FlightDetailsList>
         <air:AirSegmentList>
            <air:AirSegment Key="zsah84E2TPewCZ9z3xsZGA==" Group="0" Carrier="AI" FlightNumber="20" Origin="DEL" Destination="CCU" DepartureTime="2015-08-15T14:15:00.000+05:30" ArrivalTime="2015-08-15T16:20:00.000+05:30" FlightTime="125" Distance="816" ETicketability="Yes" Equipment="788" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="sF6V09EGThu+tUb/MCr1ig==" />
            </air:AirSegment>
            <air:AirSegment Key="lHT5ul6HRNKsiPd30C0NmA==" Group="0" Carrier="AI" FlightNumber="764" Origin="DEL" Destination="CCU" DepartureTime="2015-08-15T17:00:00.000+05:30" ArrivalTime="2015-08-15T19:05:00.000+05:30" FlightTime="125" Distance="816" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z2|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="sOM6/N8VQnCmSfbkxOgf1A==" />
            </air:AirSegment>
            <air:AirSegment Key="IQ5pFPt9TdmiJ3NFgwCh4A==" Group="0" Carrier="AI" FlightNumber="22" Origin="DEL" Destination="CCU" DepartureTime="2015-08-15T20:15:00.000+05:30" ArrivalTime="2015-08-15T22:20:00.000+05:30" FlightTime="125" Distance="816" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|E9|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="iEpdY6FAQICJ0zWI8Oeh1g==" />
            </air:AirSegment>
            <air:AirSegment Key="ORptOTDPT2GX3sBgBTVq2w==" Group="1" Carrier="AI" FlightNumber="23" Origin="CCU" Destination="DEL" DepartureTime="2015-08-22T20:15:00.000+05:30" ArrivalTime="2015-08-22T22:30:00.000+05:30" FlightTime="135" Distance="816" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z2|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="daVbfT+WTSa5IB6AvePDmg==" />
            </air:AirSegment>
            <air:AirSegment Key="zO0aH/RoS9eViVvbWNeyRg==" Group="1" Carrier="AI" FlightNumber="701" Origin="CCU" Destination="DEL" DepartureTime="2015-08-22T17:30:00.000+05:30" ArrivalTime="2015-08-22T19:50:00.000+05:30" FlightTime="140" Distance="816" ETicketability="Yes" Equipment="788" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="fM27K8MVT5yEejqqUw1glQ==" />
            </air:AirSegment>
            <air:AirSegment Key="uA8H+g5WQiSwfBR2xDdA1Q==" Group="1" Carrier="AI" FlightNumber="763" Origin="CCU" Destination="DEL" DepartureTime="2015-08-22T06:45:00.000+05:30" ArrivalTime="2015-08-22T09:10:00.000+05:30" FlightTime="145" Distance="816" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S8|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="9HmvlwAjSFC5HypzchQo7g==" />
            </air:AirSegment>
            <air:AirSegment Key="2rASq/24QDKOmppw3lvbog==" Group="1" Carrier="AI" FlightNumber="21" Origin="CCU" Destination="DEL" DepartureTime="2015-08-22T10:00:00.000+05:30" ArrivalTime="2015-08-22T12:10:00.000+05:30" FlightTime="130" Distance="816" ETicketability="Yes" Equipment="788" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S2|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="ak3JEi3yR3Cg1qQtxpPaSQ==" />
            </air:AirSegment>
            <air:AirSegment Key="IdUvqgwDSRa7oHCASHJHBQ==" Group="0" Carrier="AI" FlightNumber="401" Origin="DEL" Destination="CCU" DepartureTime="2015-08-15T07:00:00.000+05:30" ArrivalTime="2015-08-15T09:05:00.000+05:30" FlightTime="125" Distance="816" ETicketability="Yes" Equipment="788" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|SC|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="LWRxwFGuSWaKMHN/dwIdRw==" />
            </air:AirSegment>
            <air:AirSegment Key="i4UHbVSWS++bvXv3AuCFRQ==" Group="1" Carrier="AI" FlightNumber="676" Origin="CCU" Destination="BOM" DepartureTime="2015-08-22T06:00:00.000+05:30" ArrivalTime="2015-08-22T08:50:00.000+05:30" FlightTime="170" Distance="1035" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="+g86BGDETGi4f0pNl+pWyA==" />
            </air:AirSegment>
            <air:AirSegment Key="7aKSfeP/RPyzazTUUrVICQ==" Group="1" Carrier="AI" FlightNumber="101" Origin="BOM" Destination="DEL" DepartureTime="2015-08-22T21:30:00.000+05:30" ArrivalTime="2015-08-22T23:45:00.000+05:30" FlightTime="135" Distance="708" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="F1|AL|C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|E9|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="mWJyRGIRQAumT2GLRxOREQ==" />
            </air:AirSegment>
            <air:AirSegment Key="l/iIrjYNRK+wYfVZ/DtMuQ==" Group="1" Carrier="AI" FlightNumber="773" Origin="CCU" Destination="BOM" DepartureTime="2015-08-22T19:40:00.000+05:30" ArrivalTime="2015-08-22T22:35:00.000+05:30" FlightTime="175" Distance="1035" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z2|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="WffetBFIQr+uKuVLDvKyGw==" />
            </air:AirSegment>
            <air:AirSegment Key="lP6vjP0mRjCMn8RG3AHrQg==" Group="1" Carrier="AI" FlightNumber="605" Origin="BOM" Destination="DEL" DepartureTime="2015-08-23T21:00:00.000+05:30" ArrivalTime="2015-08-23T23:10:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="bjfE540+QB+0G7bYnYZmFA==" />
            </air:AirSegment>
            <air:AirSegment Key="vSAiXZMhTnyj057GHJPQrw==" Group="1" Carrier="AI" FlightNumber="101" Origin="BOM" Destination="DEL" DepartureTime="2015-08-23T21:30:00.000+05:30" ArrivalTime="2015-08-23T23:45:00.000+05:30" FlightTime="135" Distance="708" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="F1|AL|C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|E9|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="YiMlGjdmSeWaE46yGDgo4g==" />
            </air:AirSegment>
            <air:AirSegment Key="UzOxqrRLQHiCE5G6b35RjQ==" Group="0" Carrier="AI" FlightNumber="315" Origin="DEL" Destination="BOM" DepartureTime="2015-08-15T23:00:00.000+05:30" ArrivalTime="2015-08-16T01:10:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|E9|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="5AF6hWRLRXmseEZzb28xMA==" />
            </air:AirSegment>
            <air:AirSegment Key="9MZFai/WSSK9rcFd35Lj8w==" Group="0" Carrier="AI" FlightNumber="675" Origin="BOM" Destination="CCU" DepartureTime="2015-08-16T06:10:00.000+05:30" ArrivalTime="2015-08-16T08:50:00.000+05:30" FlightTime="160" Distance="1035" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="9ViRVBU4TMqO1MXt+x4/kg==" />
            </air:AirSegment>
            <air:AirSegment Key="zLI1YLC5Re6vsx7MfsoILQ==" Group="0" Carrier="AI" FlightNumber="102" Origin="DEL" Destination="BOM" DepartureTime="2015-08-15T16:45:00.000+05:30" ArrivalTime="2015-08-15T19:05:00.000+05:30" FlightTime="140" Distance="708" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="F2|A1|C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|E9|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="mcH902NPRBSUBP4B9DVSzQ==" />
            </air:AirSegment>
            <air:AirSegment Key="eUe8d5+uR9qggpmIIAtFwg==" Group="0" Carrier="AI" FlightNumber="889" Origin="DEL" Destination="GAU" DepartureTime="2015-08-15T10:50:00.000+05:30" ArrivalTime="2015-08-15T13:10:00.000+05:30" FlightTime="140" Distance="904" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="svn8w8BgQl+teluoWk6xkg==" />
            </air:AirSegment>
            <air:AirSegment Key="Oa8M7JGcTeyqERlcant8BQ==" Group="0" Carrier="AI" FlightNumber="730" Origin="GAU" Destination="CCU" DepartureTime="2015-08-16T11:40:00.000+05:30" ArrivalTime="2015-08-16T12:50:00.000+05:30" FlightTime="70" Distance="311" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z3|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="WuOjY1HeQsqVL8slTFsxEA==" />
            </air:AirSegment>
            <air:AirSegment Key="zfRrybyAQKCgnT49l/csmA==" Group="1" Carrier="AI" FlightNumber="729" Origin="CCU" Destination="GAU" DepartureTime="2015-08-22T09:50:00.000+05:30" ArrivalTime="2015-08-22T10:55:00.000+05:30" FlightTime="65" Distance="311" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z2|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S4|E9|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="ri+sA/5aRe2dG377GEM0Fg==" />
            </air:AirSegment>
            <air:AirSegment Key="+q/OtGJmROWwTud1azvScA==" Group="1" Carrier="AI" FlightNumber="890" Origin="GAU" Destination="DEL" DepartureTime="2015-08-22T17:05:00.000+05:30" ArrivalTime="2015-08-22T19:35:00.000+05:30" FlightTime="150" Distance="904" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J3|Z1|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="7hdcFJzsQZCd1dg1rbO72A==" />
            </air:AirSegment>
            <air:AirSegment Key="wGDt8RX9TYy+0xEKj5oGqA==" Group="1" Carrier="AI" FlightNumber="677" Origin="BOM" Destination="DEL" DepartureTime="2015-08-22T13:00:00.000+05:30" ArrivalTime="2015-08-22T15:05:00.000+05:30" FlightTime="125" Distance="708" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="pGKRBzwiR9mO30RUj3ZcyQ==" />
            </air:AirSegment>
            <air:AirSegment Key="LHoe87bfRTOZdkXgY2QFJA==" Group="1" Carrier="AI" FlightNumber="677" Origin="BOM" Destination="DEL" DepartureTime="2015-08-23T13:00:00.000+05:30" ArrivalTime="2015-08-23T15:05:00.000+05:30" FlightTime="125" Distance="708" ETicketability="Yes" Equipment="320" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z3|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="kKeTsVHPTH2LRK0gh+PolA==" />
            </air:AirSegment>
            <air:AirSegment Key="9fyjzRXwSyW7JrxYOQ0O5w==" Group="0" Carrier="AI" FlightNumber="863" Origin="DEL" Destination="BOM" DepartureTime="2015-08-15T13:00:00.000+05:30" ArrivalTime="2015-08-15T15:10:00.000+05:30" FlightTime="130" Distance="708" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z2|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="29h9cHvFTJusj5PIgvs+AQ==" />
            </air:AirSegment>
            <air:AirSegment Key="8uFOBnGaQmiSxkEq1BeGHw==" Group="0" Carrier="AI" FlightNumber="774" Origin="BOM" Destination="CCU" DepartureTime="2015-08-15T18:30:00.000+05:30" ArrivalTime="2015-08-15T21:10:00.000+05:30" FlightTime="160" Distance="1035" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z2|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="me694bc2QVmALz6XUuer9g==" />
            </air:AirSegment>
            <air:AirSegment Key="brYsEIYORi2VzJLhucpx7Q==" Group="0" Carrier="AI" FlightNumber="9740" Origin="GAU" Destination="CCU" DepartureTime="2015-08-16T10:25:00.000+05:30" ArrivalTime="2015-08-16T11:55:00.000+05:30" FlightTime="90" Distance="311" ETicketability="Yes" Equipment="ATR" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:CodeshareInfo OperatingCarrier="9I" OperatingFlightNumber="740">ALLIANCE AIR</air:CodeshareInfo>
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U6|T4|S2|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="jLxIyPhsT3WElwL8YFSzHA==" />
            </air:AirSegment>
            <air:AirSegment Key="Y/bbbkLMQIGGAh7vpxLgZQ==" Group="1" Carrier="AI" FlightNumber="618" Origin="CCU" Destination="HYD" DepartureTime="2015-08-22T13:25:00.000+05:30" ArrivalTime="2015-08-22T15:35:00.000+05:30" FlightTime="130" Distance="745" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z3|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="dShyPEwkQIyrFvaOYzFR8A==" />
            </air:AirSegment>
            <air:AirSegment Key="cdp54BU2QCOvFA8+beoWmg==" Group="1" Carrier="AI" FlightNumber="127" Origin="HYD" Destination="DEL" DepartureTime="2015-08-22T20:55:00.000+05:30" ArrivalTime="2015-08-22T23:10:00.000+05:30" FlightTime="135" Distance="781" ETicketability="Yes" Equipment="77W" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="F3|A2|C4|D4|J4|Z2|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|ER|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="82nUnd4KTQqDwpgZxW5UFA==" />
            </air:AirSegment>
            <air:AirSegment Key="PRTIxTZbRJi+2LRBHD8u3A==" Group="0" Carrier="AI" FlightNumber="617" Origin="BOM" Destination="CCU" DepartureTime="2015-08-16T08:35:00.000+05:30" ArrivalTime="2015-08-16T12:40:00.000+05:30" FlightTime="245" Distance="1035" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" NumberOfStops="1" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C3|D2|J2|Z2|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|E9|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="bCEIHp+0TI+mgRssXLJ07w==" />
               <air:FlightDetailsRef Key="8fMdmHN4RhSRd770wHkpTQ==" />
            </air:AirSegment>
            <air:AirSegment Key="vxK3zAAsQxObn1Y8grrzbg==" Group="1" Carrier="AI" FlightNumber="984" Origin="BOM" Destination="GOI" DepartureTime="2015-08-23T05:15:00.000+05:30" ArrivalTime="2015-08-23T06:30:00.000+05:30" FlightTime="75" Distance="257" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|ZC|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|E9|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="6qeJHeqwSBSekohbwLhx9g==" />
            </air:AirSegment>
            <air:AirSegment Key="EB0F6gVyTwirp4gpFNBtYw==" Group="1" Carrier="AI" FlightNumber="155" Origin="GOI" Destination="DEL" DepartureTime="2015-08-23T13:55:00.000+05:30" ArrivalTime="2015-08-23T16:15:00.000+05:30" FlightTime="140" Distance="928" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|E9|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="P8Ez8MTQQaCcuGalnwBPZA==" />
            </air:AirSegment>
            <air:AirSegment Key="G+Eyr0TNSz2/FcMIep324g==" Group="1" Carrier="AI" FlightNumber="865" Origin="BOM" Destination="GOI" DepartureTime="2015-08-22T13:00:00.000+05:30" ArrivalTime="2015-08-22T14:10:00.000+05:30" FlightTime="70" Distance="257" ETicketability="Yes" Equipment="321" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Polled avail used" OptionalServicesIndicator="false" AvailabilitySource="S" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z4|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="g8Mg69guRqm7Wkwoyd8n7w==" />
            </air:AirSegment>
            <air:AirSegment Key="FaEAN8+uSeWscFk/l/CIOw==" Group="0" Carrier="AI" FlightNumber="469" Origin="DEL" Destination="NAG" DepartureTime="2015-08-15T05:45:00.000+05:30" ArrivalTime="2015-08-15T07:15:00.000+05:30" FlightTime="90" Distance="531" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z3|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="A5qNiHLfRLOQwzY3tnDA8w==" />
            </air:AirSegment>
            <air:AirSegment Key="Vj2K7tszRruimjSTBsCWhA==" Group="0" Carrier="AI" FlightNumber="630" Origin="NAG" Destination="BOM" DepartureTime="2015-08-15T20:45:00.000+05:30" ArrivalTime="2015-08-15T22:15:00.000+05:30" FlightTime="90" Distance="425" ETicketability="Yes" Equipment="319" ChangeOfPlane="false" ParticipantLevel="Secure Sell" LinkAvailability="true" PolledAvailabilityOption="Cached status used. Polled avail exists" OptionalServicesIndicator="false" AvailabilitySource="P" AvailabilityDisplayType="Fare Shop/Optimal Shop">
               <air:AirAvailInfo ProviderCode="1G">
                  <air:BookingCodeInfo BookingCounts="C4|D4|J4|Z2|Y9|B9|M9|H9|K9|Q9|V9|W9|G9|L9|U9|T9|S9|EC|NC" />
               </air:AirAvailInfo>
               <air:FlightDetailsRef Key="ZBYuVvJfRtmlf4ES5PF5CA==" />
            </air:AirSegment>
         </air:AirSegmentList>
         <air:FareInfoList>
            <air:FareInfo Key="vhZKTijwQMiKUHQirsWQbw==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="DEL" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR3700" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" ProviderCode="1G">gws-eJxNTssKhDAM/BiZ+8QHtreWWtmCFHHXgxf//zNMrcIOZEIyk4dzrqUMNNK5PzQ4G5+Q8gZkUCOEHTKSEC0OUNqIb1rxTA/azbdSsxQPgwSr0zPnvioFOG6e4vIuLNegPhSKH03ZTz+f2JPGWmtkU2GEfnQBcsMmag==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="AxOxhtXIQL2sIu/EK9/ySw==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR3600" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku1OYaG+tStEqTdHEtgMX/v8zSCmHWYqjxM4jxugoD3oZ4x8GHEOqqLoBClos5QWZSTgrdlBcwae+0add6+ql9CzNwyw52PTKdbqUXQ035/zrCwXtGsyHRuVpSdPyTZUT6UMIXjYTZthHJ3NiJnQ=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="pngxXU5LS0SaP6Gos2GfVw==" FareBasis="UIP" PassengerTypeCode="CNN" Origin="DEL" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR3600" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu+yRLbllZAkLFFMKPfSy//+MOc0OE1jGlvzIOSslMMot/2HBZ3l2dNsBAz1KOSD3QIgXJyhacfQNv+ngXbuUmXV42NjUp5s2mcoAzotfdZ0LFeMa3IdB9e3JihlVhYwppSi7Cw/4R19zaSZQ</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="+j5GT6uvQcu3nX85snsEew==" FareBasis="UIP" PassengerTypeCode="CNN" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR3500" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="+j5GT6uvQcu3nX85snsEew==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu+zSLrllpAkLDDMKPfSy//+MOe0KFVjGlvxIKSllYpAx3TDgOzwbmq2AgR5LeUPmiVAvdlC0YGsfnNPau3YoZ9buYWVVn65a5VB2c/w55+1a2K/BfehUXp4sm1FVyBBjDLK68IB/9AN0CCZa</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="C1iHtM7YS+WaRxm13dTv5Q==" FareBasis="SIP" PassengerTypeCode="INF" Origin="DEL" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu9y0LLmlZAkzDDM6duhl//8Zc5oeJrCMLfmRc54pK6OE/IcJ32lTqO2AgR6lfCAkIV4coMwVb33hml69a6cycugeFinJpxvbMpQOHCff63MsDOjX4D50qg9PptY25ULGlFKU3YUb/KMfb1omZA==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="kV6FtaSTQfSH2HBROWEyew==" FareBasis="SIP" PassengerTypeCode="INF" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2100" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="kV6FtaSTQfSH2HBROWEyew==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5x0LLmlZAkzDDM6duhl//8Zc5odJrCMLflRSgmUC5PE8ocFn2VTqO2AgR639oCQRPDiACU0vPSJOR1G105l5jg8rFKzT3f29VQOc/y41vdcGDGuwX0Y1O6eTK1vypVMOeckuwtX+Edfb/kmbg==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="Eq8ubHSZQBC/f3jX95rsrw==" FareBasis="TIP" PassengerTypeCode="ADT" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR4900" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="Eq8ubHSZQBC/f3jX95rsrw==" ProviderCode="1G">gws-eJxNTssKhDAM/BiZexJd2t5aamULSxDRgxf//zNMrYcdyIRkJo8YoxB/yPMY/zDgGlJF1Q1QkMVcfhBxI8SKE8RSsNcVfVpaVx+lZ24eypyDTS+0TI9yquHlnI++kNGuwXxoVL6WNM17qjQR+RCC580EB/voBnfvJoE=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="tOMMiEiPT7ylCyq+mJpbXw==" FareBasis="SIP" PassengerTypeCode="CNN" Origin="DEL" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR3700" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="tOMMiEiPT7ylCyq+mJpbXw==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5y1NLmleCkLFFM6duhl//8Zc5oeJrCMLfmRcw6UiVEe+Q8DvsNSUe0ADPRQ/UBmEuLFCUooeNcd9/TkXbuUnkPzUEWTT69cx6404Lz4Wba+MKBdg/vQqLw8mZotlSMZU0pRDhdm+Ec/c9omcg==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="x52yed0kQOSSOEesfOQg6Q==" FareBasis="TIP" PassengerTypeCode="INF" Origin="DEL" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="x52yed0kQOSSOEesfOQg6Q==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu9y0LLmlZAkzDDPKduhl//8Zc5oeJrCMLfmRc54pK6OE/IcJ32lTqO2AgR6lfCAkIV4coMwVb33hml69a6cycugeFinJpxvbMpQOHCff63MsDOjX4D50qg9PptY25ULGlFKU3YUb/KMfb80mZQ==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="pfdNSh9mSsKEFhZpN7ewgQ==" FareBasis="TIP" PassengerTypeCode="INF" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2100" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="pfdNSh9mSsKEFhZpN7ewgQ==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5x0LLmlZAkzDDPKduhl//8Zc5odJrCMLflRSgmUC5PE8ocFn2VTqO2AgR639oCQRPDiACU0vPSJOR1G105l5jg8rFKzT3f29VQOc/y41vdcGDGuwX0Y1O6eTK1vypVMOeckuwtX+EdfcGwmbw==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="QYxqcLJwTUWhBfh/GDV1wA==" FareBasis="TIP" PassengerTypeCode="ADT" Origin="DEL" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR4900" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="QYxqcLJwTUWhBfh/GDV1wA==" ProviderCode="1G">gws-eJxNTssKhDAM/BiZ+6QqtreWWrEgZRH34MX//wxT68IOZEIyk4f33lBGWun9HzpcXcjIZQcKqBHjF8ZMPUSLExSTcOQP3ulRu+VRWpbqYZTodHrhMjSlAufDc9p+C+s1qA+V0qqphPkImQNpnXNWdhUm6Ec3d1Amdw==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="o+OGTNSuSZivoveynD8EWg==" FareBasis="UIP" PassengerTypeCode="ADT" Origin="DEL" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR3600" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="o+OGTNSuSZivoveynD8EWg==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu+yRLbllZAkLFFMKPfSy//+MOc0OE1jGlvzIOSslMMot/2HBZ3l2dNsBAz1KOSD3QIgXJyhacfQNv+ngXbuUmXV42NjUp5s2mcoAzotfdZ0LFeMa3IdB9e3JihlVhYwppSi7Cw/4R19zaSZQ</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="tOP82U9XR++psl0W+SFEaA==" FareBasis="LIP" PassengerTypeCode="CNN" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR4100" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="tOP82U9XR++psl0W+SFEaA==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu+xuNLmlZAkLFFMKO/Sy//+MOm0HE1jGlvxIKSnlySBj+sOA7zA3NNsAAz1eZYHEB6Fe7KBowdJWXNPau3YqV9buYWVVn65a5VR2c9yc8+e3sF+D+9CpvD1ZNqOqkCHGGGRzYYJ/dABxZSZV</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="jIy9qlRrQlmEbzhpPX/EXQ==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="CCU" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR1300" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" ProviderCode="1G">gws-eJxNTrsOgzAM/Bh0ux2IiLeEFNQMpA/agYX//4zayVJLvrPO50eM0RF7CjzGvxhwDamg1DdQQZrLY4cXD6f1CWK34ihP9GFnam2dzmweypxFhzfapt6xwNkw52/bx7BbUBsM1rtSTbdPKjQRBREJL9Vn6Ds/Dj0l9g==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="vQFs/R/7ReGEjkZyQK2o+g==" FareBasis="SIPF2" PassengerTypeCode="ADT" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR1900" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJdycbor21bK2oBAUBB3bh/z9jaXvBUpwodh4hBKWc6GQOf5jwm2JBqS+gghZbusGTmK3eQdGEd3lmxRhXtX7t2sjSXFxl9TaemZehNGDvfHnc+0ZBuwazoVG6Wqpx+8TChXTee6dfE86wjw57USaO</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="prtyo3U+R526p/kww43VLQ==" FareBasis="SIP" PassengerTypeCode="CNN" Origin="CCU" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR1300" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="prtyo3U+R526p/kww43VLQ==" ProviderCode="1G">gws-eJxNTrsOwzAI/JjodkxjxWxOUaJ6CH2pQ5b+/2cU4gxF4g4dx6PWypQylXSpfzHgO8wNzV6AgTyv9w1ZMtjrHZR4wbs90Ic5VDs6nTk8pEnFh1dax96JwH6g6ufcF7fgNgQsNydTs7nRSFREpDxdn+Dv/AAPSyX+</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="S+0l6QMMQdqJdn+DPbCzrg==" FareBasis="SIPF2" PassengerTypeCode="CNN" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR1900" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJdzcbWnvr6FqtEgQEF3bh/z+DtL1gKU4UO48Yo9Bd6N0c/zDhO20VVV+AghZ7viGQmK0+QScZ7/osgjEuYn3t2sjSXEwuBRsvLMtQGnB2vj7ufaOgXYPZ0CgfljSpbpUL6UMIXj4mrLCPfnxnJpY=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="BlnHqkzSTMW1mzO3sNwF1A==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="DEL" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu9y0LLmlZAkzDDM6duhl//8Zc5oeJrCMLfmRc54pK6OE/IcJ32lTqO2AgR6lfCAkIV4coMwVb33hml69a6cycugeFinJpxvbMpQOHCff63MsDOjX4D50qg9PptY25ULGlFKU3YUb/KMfb1omZA==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="Y2cXXLrWT1CwKt0O7/q2bA==" FareBasis="SIPF2" PassengerTypeCode="ADT" Origin="DEL" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2000" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku9OBaG7toNUqsW7adhiX/f9nkAKHWYoTxc4jhOAoA71cwh86/LpYUOoLqKDF9XGHkhCrV1Bcwrs8s8M5Pli/7tqRpbk4yaQ2npn7Q2nAuvOclnNjuwazoVG6Wapx/sTCnvSq6t3XhBH20QZ+AiaO</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="ybESxWwkQRq6UysD2B5jSw==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="BOM" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-16" Amount="LKR1300" NegotiatedFare="false" NotValidBefore="2015-08-16" NotValidAfter="2015-08-16">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" ProviderCode="1G">gws-eJxNTjkOwzAMe0zAnXLj1Nrsugnqoe49ZOn/n1HZ6RABIgWKOmKMjuIZ5BB3MeA7pIJSn0AFLXP+wKuHs3oFxc14lTv+w5OptXc2luZhlqw2vHAZt04LrB1Pt2vfJ2i3YDY0mC9GNZ3fqXAkg6qGh+lH2Ds/Dn4l+Q==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="xmj1Up2mQGGV39WduCRM+A==" FareBasis="SIPF2" PassengerTypeCode="CNN" Origin="DEL" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2000" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJdydsor11lFZUgoDgwi78/2eQdhywFCeKnUdKSSkLgxzSHyZ8prWh2RMw0ON0vyGSEK83ULTg1R5V8RtfvG9D27N2F7Pk6OOVdd6VDmyDz+U6Nir6NbgNncrFk2WztXEmQ4wx6NuFI/yjL38YJpY=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="xs62wc3GTS2PBq6CV1dfdQ==" FareBasis="SIP" PassengerTypeCode="CNN" Origin="BOM" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-16" Amount="LKR1300" NegotiatedFare="false" NotValidBefore="2015-08-16" NotValidAfter="2015-08-16">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" ProviderCode="1G">gws-eJxNTjkOwzAMe0zAnXbj1tqcCAnqoeqFDln6/2dETj1UgEiBoo5SSmRIzOFU/mLAd5gqqr0AAz1VP0iSEL3ewBAXvOsDffjsqh2dH8fmoQYVH165jkdnM4+O8/3W97VbcBsaLFcnU7OpciSziOSn6xf4OzsSTCYM</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="WcgbQFtdQ3OJ1jlKUDMufw==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2100" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="WcgbQFtdQ3OJ1jlKUDMufw==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5x0LLmlZAkzDDM6duhl//8Zc9oOJrCMLflRSgmUC5PE8ocJn2lRqK2AgR639oCQRPRiAyU0vPSJYzoE79quHDkOD6vU7NOdfd6VzRwn1/r+LRzX4D4MandPptYX5UymnHOS1YUr/KMvcHcmbw==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="uFkt5qFMQcaq+PCJlp+6/Q==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="DEL" Destination="GAU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR3700" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="uFkt5qFMQcaq+PCJlp+6/Q==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku8N4tLdWUEYlVKGyHbjs/z9jaRnSLMVRYufhnGspPY083B8afBofEVMGEqjx9G/IYAjR4gSlDTjijt90r91UlStL8XCSyer0wqW7lAKcleew3QvLNagPhcKqKfn55SM70lhrjWQVRuhHX3ioJno=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="ngWUS3fARwKBhs/HN4oOzA==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="GAU" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-16" Amount="LKR1300" NegotiatedFare="false" NotValidBefore="2015-08-16" NotValidAfter="2015-08-16">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="ngWUS3fARwKBhs/HN4oOzA==" ProviderCode="1G">gws-eJxNTjkOhDAMfAyafhxYNukShStNtOIoaPj/M3AIxY7kseUZH957Q/nQSuv/0OBqQkLKK5BBjRgP9CSM1icoZsSWfniHe+3mR6lZiodRotPhiVNXlQKcD8+h7hOUW1AbCo2LphyGPSR2pHXOWVlV+EL/uQEreSYQ</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="LrR2GdepRSGFqkjF5OHZ0g==" FareBasis="SIP" PassengerTypeCode="CNN" Origin="DEL" Destination="GAU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR3700" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="LrR2GdepRSGFqkjF5OHZ0g==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku1Nga2+turJVmqKJiQMX/v8zSOkOsxRHiZ1HjNFRZnoZ4x8GHEOqqLoCClo80wa5eUKs2EFxBd/6wW96tq5eSs+ueZglB5teuExdacB+8aO8+0KHdg3mQ6PysqRZNVVOpA8heFlNuMM+OgF5vyaC</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="esNqPUdtSVmDR4mCT8JC2w==" FareBasis="SIP" PassengerTypeCode="CNN" Origin="GAU" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-16" Amount="LKR1300" NegotiatedFare="false" NotValidBefore="2015-08-16" NotValidAfter="2015-08-16">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="esNqPUdtSVmDR4mCT8JC2w==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku9MBa2+tIth6iSYQBy77/89YCkzCUpwodh4550AZGOWRb+jw7UpFtQUw0EN1w0gieL2DEias9YNrePSuHcqZQ/NQRZMPz5z7Q9nNcfGr/Pe1W3AbGk1vT6ZmpbInY0opyuLCE/7PDy9aJiM=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="xoNttsjURa+MJVSded4XFA==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="DEL" Destination="GAU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="xoNttsjURa+MJVSded4XFA==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu9y0LLkldMlmGGZ07NDL/v8z5jQ7TGAZW/Ij5zxTVkYJ+Q8TPlNRqO2AgR638oaQhHhxgDJXvPSJ3/TqXTuVkUP3cJMt+XRjW4bSgePka32MhQH9GtyHTvXuydRaUS5kTClF2V24wD/6AnBqJmY=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="xvc5Ly/RQFiu/CoIvygwHQ==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="xvc5Ly/RQFiu/CoIvygwHQ==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5x0LLmlZAkzDDM6duhl//8Zc9oOJrCMLflRSgmUC5PE8ocJn2lRqK2AgR639oCQRPRiAyU0vPSJYzoE79quHDkOD6vU7NOdfd6VzRwn1/r+LRzX4D4MandPptYX5UymnHOS1YUr/KMvcHcmbw==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="5PrUfMDZTeW2TZBVaXznWw==" FareBasis="TIP" PassengerTypeCode="ADT" Origin="DEL" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="5PrUfMDZTeW2TZBVaXznWw==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu9y0LLmlZAkzDDPKduhl//8Zc5oeJrCMLfmRc54pK6OE/IcJ32lTqO2AgR6lfCAkIV4coMwVb33hml69a6cycugeFinJpxvbMpQOHCff63MsDOjX4D50qg9PptY25ULGlFKU3YUb/KMfb80mZQ==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="pUoWB+i/QnqbWR7nGlk/ww==" FareBasis="TIP" PassengerTypeCode="ADT" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR4800" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="pUoWB+i/QnqbWR7nGlk/ww==" ProviderCode="1G">gws-eJxNTssKhDAM/BiZe5IqtreWWtnCEhZxD178/88wtR4cyIRkJo8YoxBP5NnFFwacQ6qougEKsljKFyKzg7PiALEU7PWHPi1iXb2Vnrl5KHMONr3SOt7KoYaHc/73hYx2DeZDo/KxpGnZU6WRyIcQPG8mzLCPLnhtJoI=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="s3VqGN90SKatIqEfyut3lA==" FareBasis="TIP" PassengerTypeCode="ADT" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2100" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="s3VqGN90SKatIqEfyut3lA==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5x0LLmlZAkzDDPKduhl//8Zc9oOJrCMLflRSgmUC5PE8ocJn2lRqK2AgR639oCQRPRiAyU0vPSJYzoE79quHDkOD6vU7NOdfd6VzRwn1/r+LRzX4D4MandPptYX5UymnHOS1YUr/KMvcOomcA==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="rEvQ2lgATQCPw/WfbjJF8A==" FareBasis="TIP" PassengerTypeCode="ADT" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="rEvQ2lgATQCPw/WfbjJF8A==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5x0LLmlZAkzDDPKduhl//8ZcxoGE1jGlvwopQTKhUli+cOCz7Ip1HbAQI9be0BIInpxgBIaXvrEnA7Bu3YqM8fhYZWafbqzr1MZwHFyre/fwnEN7sOgdvdkan1TrmTKOSfZXbjCP/oCbhQmZQ==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="Dpok1lCdSDa1ECnqU3T5Ng==" FareBasis="TIP" PassengerTypeCode="ADT" Origin="CCU" Destination="GAU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="Dpok1lCdSDa1ECnqU3T5Ng==" ProviderCode="1G">gws-eJxNTrsOwyAM/Bh0+5kSFTYQTVIWFEXJkKX//xm1Q4ee5LPsOz9yzp4yMcoj/8Hh40pD6zvQQY21nBCS8FpcoPgZR9swpr11+62MLOZhlZp0euEShmLAdXOtv4UCuwb1wWh+a+rldZTGQMaUUpRdhSf0oy9wRCZk</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="ySo1yud0SLekSm3rPYr13w==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="GAU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR3500" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="ySo1yud0SLekSm3rPYr13w==" ProviderCode="1G">gws-eJxNTssOwyAM+5jKdwfaDm6glrZIE5rYduhl//8ZC+VSS3GU2HmEEAxlohMbbhjwG2JGLhUooMaanpDZEVaLExST8M4v9GljtFsupWdpHi6yeJ3euI1dacB58R6/faGgXYP60CgdmkpcPzFzJJ333klV4QH96A92TSZ6</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="B+erd35uS0+ULWJZkEbRFg==" FareBasis="UIP" PassengerTypeCode="CNN" Origin="CCU" Destination="GAU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2000" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="B+erd35uS0+ULWJZkEbRFg==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu+xSltxSQrPlYkahh172/58xu6FQgWVjyY9SilIWJpnLAxN+09rRbQcM9HivBzIJ9foERTcc/YsxrNG1SxlZw8PGpj7ctMlQAjgvrvXeF7fgNgRtH09WzagqZMo5J9ldeMH/+QMq3yXx</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="8ujIOkpaQ5+fZ0iBbz521A==" FareBasis="SIP" PassengerTypeCode="CNN" Origin="GAU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR3500" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="8ujIOkpaQ5+fZ0iBbz521A==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJdycb0N5alQ4qoQgNcdiF//8M0pUDluIosfNIKSnlxCBz+sOEz5Qbmm2AgR7X+oCcAzF7sYOiFa/2xJhW9a4dysjaPSxSok+vXJdD2c3x41t+j4WKfg3uQ6d692TFLDcuZIgxBtlcuMA/+gJ6OiaN</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="93+8H4sfRl68heBbxd4xJA==" FareBasis="TIP" PassengerTypeCode="INF" Origin="CCU" Destination="GAU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="93+8H4sfRl68heBbxd4xJA==" ProviderCode="1G">gws-eJxNTjkOwzAMe0zAnbJT1N5sGHHrRSiCdsjS/z+jUtwhBERBInWUUgLlxiSxXLDgu9SBoTugoMWjfiAkEaw4QAkb3uOFOR28q6cyc3QPm7Rs0519nYoDx8mt/RdG+DWYD07b05IO7XVwJVPOOcluwh320Q9xwCZs</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="UtpUeW3+RA+ziatJ0hO7Tg==" FareBasis="SIP" PassengerTypeCode="INF" Origin="GAU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="UtpUeW3+RA+ziatJ0hO7Tg==" ProviderCode="1G">gws-eJxNTssKAjEM/Jhl7pN2xfbWsrYakCArHvbi/3+G6a6CA5mQzORRSgmUE5PE8ocJ76kq1FbAQI9Lu0NIInqxgRIanvrAMR2Cd21XjhyHh4ss2ac7+7wrmzm+fK2v38JxDe7DoHbzZGq9Kmcy5ZyTrC6c4R99AHDlJnE=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="jJDobaIbRAyrNKJyNB5zMA==" FareBasis="T7SS" PassengerTypeCode="ADT" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR3400" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="jJDobaIbRAyrNKJyNB5zMA==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku9OCaG8tULRKW6cBFy78/2eQ0ssixVYc5xFCMJSBTmz4iw5XFzNy2YACai7pDRmchdXiBMUkHOO+o40bo3J5Wo2lmjjL7HV85dq3Tg2cD07fT9soqOegPlRIL6USlyNm9qTz3ruf6iP0oxt95CaS</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="KQlf9JiuQN29uPgwf72otg==" FareBasis="UIP" PassengerTypeCode="CNN" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2800" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="KQlf9JiuQN29uPgwf72otg==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJ98QFrb0VWCsqjYAmcdiF//8M0pUDluIosfPIOVP0LFFD/sOEz3RpaLYBBvFYygoNPCF4sUOUBe/2wpgmvWuHMjK7R6pU+nRl1UPZzfHj6/MxFhL9GtyHTuXuyW5mQqpITClF3VyY4R99AXO8Jlo=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="XlcHRKCmT4WcTRNMTz8TEw==" FareBasis="SIP" PassengerTypeCode="INF" Origin="CCU" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="XlcHRKCmT4WcTRNMTz8TEw==" ProviderCode="1G">gws-eJxNTjkOwzAMe0zAnbZT1N6cGjGqIUoPdMjS/z+jUrxUgEiIoo5aa2S4MIdU/2LCd1oEoi9AQcvbviGQRLTiAENc8ZYHxnR0Vc/O4OQettCKTXf2eXQ8cJzY2mcsTPBrMB8c1ruRivZFOJO5lJKfpl9hD/0AS38mOg==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="Km3uGloLRsme1sEXD6d6ZQ==" FareBasis="SIP" PassengerTypeCode="INF" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="Km3uGloLRsme1sEXD6d6ZQ==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJd6cdor11bK2IBAGN0y78/2eQbiBhKY4SO49SSqCcmCSWPwx4D5NCbQUM9FjqDUIS0YsNlFDx0ieO6RC8a7ty5Ng9nGXOPt3Yxl3ZzPHly+P+W9ivwX3oVK+eTK1NypFMOeckqwtn+EcfcR0mcg==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="iRwoCw8zR2ypgtnkcRN5Lg==" FareBasis="TIP" PassengerTypeCode="ADT" Origin="DEL" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR3700" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="iRwoCw8zR2ypgtnkcRN5Lg==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku8Nr7a0dtFol6CbEhcv+/zOW0iFhKY4SOw/nXEsZaKRzNzT4Nj4h5Q3IoMbzvUJG00G0OEBpA/b0wX960G4+lZqleDjJZHU6MvZVKcBx8hyWa2G5BvWhUHhpyn7efWJPGmutkU2FB/SjH3uxJoI=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="pLVKEj1URNKbzhiWQi8mIQ==" FareBasis="UIP" PassengerTypeCode="CNN" Origin="DEL" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2900" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="pLVKEj1URNKbzhiWQi8mIQ==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJ98Rjor11QCsqsQxN4rDL/v8zSFcOWIqjxM4jpUTRSYKO6Q8DjmGuqLYBBvG4rQt05AXqxQ5RZnzqG7/pybt2Kj2zeaRIoU8XFu1KA/aTH/nVFxLtGtyHRvnpye5mQqpIiDEG3Vy4wj/6AnOSJk8=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="C0GIBs7kRs2uVyBmv8gc3w==" FareBasis="SIP" PassengerTypeCode="INF" Origin="DEL" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="C0GIBs7kRs2uVyBmv8gc3w==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJd2fdRHvr2FoRCQIap134/88gXTlgKY4SO4+c80iZGSXkPwz4DItCbQcM9Lg+HxCSEC8OUMaCt77wm569a6fSc2gerrImn66sU1cacJy8lXtfGNCuwX1oVG6eTK0uyomMKaUouwsX+EdfcPMmZw==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="sCzETgarRWm/MHlOVQCT2w==" FareBasis="SIP" PassengerTypeCode="INF" Origin="BOM" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-16" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-16" NotValidAfter="2015-08-16">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="sCzETgarRWm/MHlOVQCT2w==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJd6cd0N46qlXkQHiJwy78/2eQrhyIFFtxnEcpJVAOTBLLX0z4TItC7QkY6FnrG0ISwYsNlLDipXf8po+u2t4ZHLuHVWr26cY2j04PbDueb9exMKJfg/vQYb04mVpblDOZcs7p4foJ/tAXS8AmPQ==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="KKnimHMLS/WvfyDBA2y+ig==" FareBasis="TIP" PassengerTypeCode="INF" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="KKnimHMLS/WvfyDBA2y+ig==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5x0LLmlZAkzDDPKduhl//8Zc9oOJrCMLflRSgmUC5PE8ocJn2lRqK2AgR639oCQRPRiAyU0vPSJYzoE79quHDkOD6vU7NOdfd6VzRwn1/r+LRzX4D4MandPptYX5UymnHOS1YUr/KMvcOomcA==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="2xS6epqRQmSxxFYlnhHBxA==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR3100" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="2xS6epqRQmSxxFYlnhHBxA==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku1OKaG8tULRKrEPAhcv+/zOW0sssxVFi5xFCMJSBTvrwhw7fLmbkcgAF1FjSBrGW6LW4QTEJZ97Rpo3RbnmUlqV6OMvsdXrlaptSgfvh6fNuCwX1GtSHSumlqcTlipmWdN57J4cKI/SjH3JbJm8=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="r1wT2DJBQDmDWyfrnI4VvA==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="DEL" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR3100" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="r1wT2DJBQDmDWyfrnI4VvA==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku8OKaG8tUEQlKBPjwmX//xlLYUhYiqPEzsN7X1MaWnn5Byp8q5CQ8gZkUKNbF4gxhGhxgFJHfNIb/+lGu/lUrizFw156p9MjR3MpBThOHuJ8LyzXoD4UipOmHIY9JBrSOuesbCq00I9+dQcmbw==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="fvrlZ5WRRSaW+Jxq7lRPzQ==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="GAU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR3600" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="fvrlZ5WRRSaW+Jxq7lRPzQ==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJd6fboL21WjuohCI04LAL//8ZpCuHWYqjxM4jxugoM72M8YQB3yFVVN0ABS1yeUAunhit2EFxBa/6RJ92zrp6KD1L83CRJdj0ynU6lF0Nf76lT18oaNdgPjQqd0ua8jtVTqQPIXjZTLjCPvoBeSMmhQ==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="nJmr9wnnTmu45h6yczKtZg==" FareBasis="LIP" PassengerTypeCode="ADT" Origin="GAU" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-16" Amount="LKR5300" NegotiatedFare="false" NotValidBefore="2015-08-16" NotValidAfter="2015-08-16">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="nJmr9wnnTmu45h6yczKtZg==" ProviderCode="1G">gws-eJxNTssOgzAM+xjku5PxaG+tGIVKUzUhOHDZ/38GKXCYpThK7DxCCErp6OQV/tDg18SMXFaggBbjuEPbjlArDlB0wid/8Uz31i2XcmepHiYmtemkSW6lAsfFc3wWCuo1mA+VpsVSie+NqkI6772T1YQB9tEJcB4mRw==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="y2pJgSbATaqx5cuvo9DL8w==" FareBasis="LIP" PassengerTypeCode="CNN" Origin="GAU" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-16" Amount="LKR2700" NegotiatedFare="false" NotValidBefore="2015-08-16" NotValidAfter="2015-08-16">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="y2pJgSbATaqx5cuvo9DL8w==" ProviderCode="1G">gws-eJxNTssKhDAM/BiZezKL2t4qxWphCYvgwcv+/2dsqiI7kAnJTB4pJYr2EvSV/tDh200V1TbAIB4571CyB704IMoZ7/rBPT14107lymweKVLo04VFL6UBx8nL9Cxs1+A+NJpXT5bNhFSREGMMurkwwj/6AXByJk0=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="w+dcGRoGSTG2xsStu/ruDg==" FareBasis="UIP" PassengerTypeCode="CNN" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR3400" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="w+dcGRoGSTG2xsStu/ruDg==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu+zSNbllZAkLFDMKPfSy//+MOc0OFVjGlvxIKSllYZA53TDhOz0bmu2AgR6vskEeCzF7cYKiBUf7YEyretcuZWTtHlZW9emqVS7lNMefcz7GQkW/BvehU3l7smxGVSFDjDHI7sIK/+gHdIYmWw==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="DXybxkZHRgms0iJ8pCEP0Q==" FareBasis="SIP" PassengerTypeCode="INF" Origin="DEL" Destination="GAU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="DXybxkZHRgms0iJ8pCEP0Q==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu9y0LLkldMlmGGZ07NDL/v8z5jQ7TGAZW/Ij5zxTVkYJ+Q8TPlNRqO2AgR638oaQhHhxgDJXvPSJ3/TqXTuVkUP3cJMt+XRjW4bSgePka32MhQH9GtyHTvXuydRaUS5kTClF2V24wD/6AnBqJmY=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="HAIHqbRRSP6d7dQb/xGMAA==" FareBasis="LIP" PassengerTypeCode="INF" Origin="GAU" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-16" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-16" NotValidAfter="2015-08-16">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="HAIHqbRRSP6d7dQb/xGMAA==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu+2wNbmllHoLFDMKO/Sy//+MKV0OE1jGlvyotZroTbKm+ocJn2lpaHEAAWGs6xsqIjAWJ0Rtw95eGNN3duNSfjl1j7i4cdrN9VLOIAY/lrEwoV8Dfei0PZmihYsZ9VxKyXpQmMGPvm16JkY=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="kNnfa8bWTw6OfH/KwW7uvQ==" FareBasis="SIP" PassengerTypeCode="INF" Origin="CCU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="kNnfa8bWTw6OfH/KwW7uvQ==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5x0LLmlZAkzDDM6duhl//8ZcxoGE1jGlvwopQTKhUli+cOCz7Ip1HbAQI9be0BIInpxgBIaXvrEnA7Bu3YqM8fhYZWafbqzr1MZwHFyre/fwnEN7sOgdvdkan1TrmTKOSfZXbjCP/oCbaEmZA==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="UThLkJTnS1WNmyOBahDppQ==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="CCU" Destination="HYD" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR6600" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="UThLkJTnS1WNmyOBahDppQ==" ProviderCode="1G">gws-eJxNTrsOhDAM+xjk3Skg2q1VAdGlOnF3Qxf+/zNI6IKlOErsPGKMjjLTyxhfGHANqaDUE6igxtFWjELCadFAcRu+5YM+7axbH6VnMQ+z5KDTO/epKwa0h3P+94UCuwb1wWg7NNW0/lLhRPoQgpdThQX60Q12MCZx</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="/JV5AE26TIOCY5L/1EpeTw==" FareBasis="SIPFS" PassengerTypeCode="ADT" Origin="HYD" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR100" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="/JV5AE26TIOCY5L/1EpeTw==" ProviderCode="1G">gws-eJxNTksOhSAMPIyZfYv6hB1EIJIYYtQNG+9/DFvZvEk6bTrTj/feEM9kefR/GPAMoaDUE6ggiZh28IxRygZik3CVI1/o08ZIv35az6wuWnl1Mp0pT11RoH28tagLGXoL4oJS2iTVEO9QaCKyzjn7O0RYIP+8QE0mTA==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="VIJ+MbrmQKy/Ajs+hqEGRA==" FareBasis="UIP" PassengerTypeCode="CNN" Origin="CCU" Destination="HYD" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR5400" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="VIJ+MbrmQKy/Ajs+hqEGRA==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu+wSltwy0obmYkahh1z2/58xpw+YwDK25EfOWSmBUeb8hwnf6d3QbAcM9Nj6Ag2BUC86KLriaB9c0zq6dipX1uFhZVWfrlrlVLo5bi7leBaOa3AfBq2bJytmVBUyppSi7C684B/9AHx2Jmo=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="lemWb4FWQ06Nd6r/RQ9XDw==" FareBasis="SIPFS" PassengerTypeCode="CNN" Origin="HYD" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR100" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="lemWb4FWQ06Nd6r/RQ9XDw==" ProviderCode="1G">gws-eJxNTrsKwzAM/Jhwu6Qkrb05ODYxFBGayUv//zMqxUsOdBK60yOlJMQrBZ7TAxN+09bQ9AsoyGIvH/CK2coOYim42lkvjGkR6+utjSzuosw52nSlugzFgX7z0XdfKPBbMBecymFJs+rWaCEKMcbwOk14w/75A0FiJlQ=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="YtI0xD5PSsyIcJXthkhvUA==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="CCU" Destination="HYD" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="YtI0xD5PSsyIcJXthkhvUA==" ProviderCode="1G">gws-eJxNTjkOwzAMe0zAnbJT1N4cuDHiRShSdPDS/z+jUryEgChIpI5SSqA8mCSWGxb8lq2j6wkoaHGMF4QkghUDlLDj09+Y08G7eikzR/ewSs023djWqTgwLq71OxdG+DWYD077YUm7tq1zJVPOOclpwhP20R91jyZz</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="VwRioCWsQXS07kHkGKWzuQ==" FareBasis="SIPF2" PassengerTypeCode="ADT" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2000" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJdzctWntr2VqtEhQEHNiF//8M0o4DluJEsfOIMQrNid7Y+IcJnylV1PYAGqix5gsCCaf1DhrJeNZ7ERzjItpvQzuy7S4uZgk6XljcUPam+PH5dh0bLfo1qA2d8qappfWVKh3pQwhe3irM0I++f6Mmng==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="akRkAnOeRu25/0vJU0NWww==" FareBasis="SIPF2" PassengerTypeCode="ADT" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR1800" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="akRkAnOeRu25/0vJU0NWww==" ProviderCode="1G">gws-eJxNTssOAiEM/JjN3KcVI9zAXYgkikY9uBf//zMscHGSTpvO9BFjVMqRXg7xDwu+S6qo7Qk00GLLVwQSzuodFM141UdRzHFV67ehzSzdxVXWYOOFxU2lA/vg8/02Ngr6NZgNnfLFUkvbO1U60ocQvH5MOME++gF70CaP</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="UcA8Kxa/TeWnuk1jpDYPUw==" FareBasis="SIPF2" PassengerTypeCode="CNN" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR1800" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="UcA8Kxa/TeWnuk1jpDYPUw==" ProviderCode="1G">gws-eJxNTssOAiEM/JjN3IeKWbixIkSStRq9uBf//zMscHGSTpvO9JFSErozgzulPyz4LltD0xegoMW17IgkvNUH6KTg3Z5VMMdFrK9Dm1m6i9nlaOOV1U+lA8fgy+M+Ngr6NZgNncrNkmbVrdGTIcYY5GPCCvvoB3zmJpc=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="KvRCnZxLTWmw9cghjTaQRw==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="GAU" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="KvRCnZxLTWmw9cghjTaQRw==" ProviderCode="1G">gws-eJxNTssKwzAM+5iiu5xkLLkldMkWGGZ07NDL/v8z5jQ7VGAZW/Ij5+woF0bx+YQF36V0dN0ABS1u9QkhiWDFDoqrePcX5rRz1tVDmdkPD1dZk003tnAouxr+fC+fudBjXIP5MKg+LGnXVjoDGVNKUTYTrrCPfnFjJnI=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="XZdxsFuhS5Oh8HrR2rJFBg==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR3000" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="XZdxsFuhS5Oh8HrR2rJFBg==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJd6d0Wntr2TpRaQQ0uOzC/38G6coBS3GU2HmklBxlZJBL+sOAz5Arqu6AghZL2SDeE96KAxRX8KpP9GnnrKun0rM0D2eZo02vXP2pHGr48fVx7wsF7RrMh0blZknz8s6VngwxxiC7CRPsoy91ryZ7</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="T+nMGihkS9SkzN2CKNjgyg==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="BOM" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-22" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-22" NotValidAfter="2015-08-22">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="T+nMGihkS9SkzN2CKNjgyg==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJd6ctWnvrGK1WCQIap134/88gXTlgKY4SO4+cs6NcGMXnP0z4TEtD0x1Q0OJW7hCSCFYcoLiCd3thTDtnXT2VkX33cJU12XRlDadyqOHH1+djLPTo12A+dCqbJW1al8ZAxpRSlN2EGfbRF3GbJnM=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="xQ2ntkwzTkWQyzcyzfHSrg==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="DEL" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="xQ2ntkwzTkWQyzcyzfHSrg==" ProviderCode="1G">gws-eJxNTssOwjAM+5jJd2fdRHvr2FoRCQIap134/88gXTlgKY4SO4+c80iZGSXkPwz4DItCbQcM9Lg+HxCSEC8OUMaCt77wm569a6fSc2gerrImn66sU1cacJy8lXtfGNCuwX1oVG6eTK0uyomMKaUouwsX+EdfcPMmZw==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="NkXLAAoMSCiwt0/F9OLs+g==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="BOM" Destination="GOI" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-23" Amount="LKR400" NegotiatedFare="false" NotValidBefore="2015-08-23" NotValidAfter="2015-08-23">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="NkXLAAoMSCiwt0/F9OLs+g==" ProviderCode="1G">gws-eJxNTrsSwzAI+5icduGkV3uzmyYtQ52+liz9/88I2Eu5A3FIAnLOgXJilDH/xYDfUBRa30AFLW+bwnQYrd9BCQs++kQ3B5/WxnQU13CWOZl55Tp1xgN7q5ft0fYJ/BZMBi/L3aCW67coJzKmlOLL5mfYOwcHdyXi</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="Kz5K/9bgR8OXU1KhYtZIFQ==" FareBasis="SIPF1" PassengerTypeCode="ADT" Origin="GOI" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-23" Amount="LKR2000" NegotiatedFare="false" NotValidBefore="2015-08-23" NotValidAfter="2015-08-23">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="Kz5K/9bgR8OXU1KhYtZIFQ==" ProviderCode="1G">gws-eJxNTkkOwzAIfEw0d3Ac1dxsJXaLVNH1kkv//4xip4ciMYOGYck5B+KFEs/5LyZ8pqJQewIG8tzqFZIY0esdxKHipffGOMbD7LqN3sHcXbTyKj7eqMXR2c3jh+ebjo2Mfg1uQ4d6cbKyvYtSJEoikh6un+APfQFePiZ0</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="MBNJEK+gT4yGB/3z3msoXQ==" FareBasis="SIP" PassengerTypeCode="CNN" Origin="BOM" Destination="GOI" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-23" Amount="LKR400" NegotiatedFare="false" NotValidBefore="2015-08-23" NotValidAfter="2015-08-23">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="MBNJEK+gT4yGB/3z3msoXQ==" ProviderCode="1G">gws-eJxNTkEOhDAIfIyZO0U3trdqoyuHxd315MX/P0NoL5LAEGYGyDkzhRfF0OdHdLi6SSD6BxRk+d4FpkNv/QkKvOCQL5qZfaqVaciuoRJKMvNK69AYD5y1zvun7mP4LZgMXpbNQIvqJDQQxZRS/Nl8hL1zAwiFJeo=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="/TGUxSY2RJGxW/oqK6w5XQ==" FareBasis="SIPF1" PassengerTypeCode="CNN" Origin="GOI" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-23" Amount="LKR2000" NegotiatedFare="false" NotValidBefore="2015-08-23" NotValidAfter="2015-08-23">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="/TGUxSY2RJGxW/oqK6w5XQ==" ProviderCode="1G">gws-eJxNTkkOwzAIfEw0dyCuYt8cuXaLFNHtlEv//4xi+1IkZtAwLDlnIb5Q5DX/xYLvsivU3oCBPK/1QIqM4PUJYqn46LMx5risrtvoTZbuosIl+XijFmanB86Bt4eOjYJ+DW5Dh3p3smK2KwWimFKKL9c3+EM/XIwmcQ==</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="dZg/eAcdRFWE8e3cInUwug==" FareBasis="SIPF1" PassengerTypeCode="ADT" Origin="GOI" Destination="DEL" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-23" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-23" NotValidAfter="2015-08-23">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="dZg/eAcdRFWE8e3cInUwug==" ProviderCode="1G">gws-eJxNTkkOwyAMfEw0dxuoim+gBFpLEel2yaX/f0YNXGrJM9Z4vKSUHPGFIvv0Fwu+S1ZoewENZLmVHRIZweoTxK7grY/KmOPOm95Gb7LvLlp5FRuvVMPs9MA58Hbo2OjRr8Fs6FDuRi1vn6wUiKKIxKfpV9hDP1yCJm0=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="w2awbyeEQBmKvB3pdjO46w==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="DEL" Destination="NAG" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR1100" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="w2awbyeEQBmKvB3pdjO46w==" ProviderCode="1G">gws-eJxNTkEKwzAMe0zRXe4SFt8S2nQLjDC6XXrZ/58xu+2hAlsGSbZzziMlMsktXzDgN5SG1legg1a9PBA0QmzeQBkrPu2NMxzdsSsHi3s4yaQWXriEQ3Fg2/tcX+c+vwWzwVt9GvUyf0tjIJOqJllNuMP++QMusiYZ</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="D1yPPEAbSi6wzFvfnNoEeQ==" FareBasis="SIP" PassengerTypeCode="ADT" Origin="NAG" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2600" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="D1yPPEAbSi6wzFvfnNoEeQ==" ProviderCode="1G">gws-eJxNTrsSwjAM+5iedjm0R7IllBYyYLjC0oX//wyUtgO6s3y25EfOOdAGRjvlP3T4dqWi+gI4qLg8H7BAIqhYQQsT3vWFY3pQ1zdlz9Y8HG1Mmp4595uyunCwl9u+0NCuQT40mu5KXq6fUtmTMaUUbZFwhj76AXR1JnA=</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="wcLH+lJKQDO9NsJYa5ZY+w==" FareBasis="SIP" PassengerTypeCode="CNN" Origin="DEL" Destination="NAG" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR1100" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="wcLH+lJKQDO9NsJYa5ZY+w==" ProviderCode="1G">gws-eJxNTkEOwzAIe0zlO2SJGm6JsnSLVKGpO/Wy/z9jkO4wS2Ak20ApJRAnynwrf1jwWerA0ANQkJXWB6IksM0niEPHe7zwCyd3TOXi4B5q3MTCG23xUhw4Z7/3fe4L8FswG7z1p5E21TooEmURyXyYsML++QIvyCYh</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="Jj8fx1bVRGyDXoyfRn2ZiA==" FareBasis="SIP" PassengerTypeCode="CNN" Origin="NAG" Destination="BOM" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2600" NegotiatedFare="false" NotValidBefore="2015-08-15" NotValidAfter="2015-08-15">
               <air:BaggageAllowance>
                  <air:MaxWeight Value="15" Unit="Kilograms" />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="Jj8fx1bVRGyDXoyfRn2ZiA==" ProviderCode="1G">gws-eJxNTjkOwzAMe0zAnVIT1N7sGjk8VC3SKUv+/4zISQuUgChIpI6UklIGBrmlP3TYu1xRbQUM9Hi8nhAloV5soOiIT33jOz14107lyto8LFKiT0+c+ktpwHay5fm3sF2D+9BoXDxZMcuVPRlijEFWF+7wjw5ytiZt</air:FareRuleKey>
            </air:FareInfo>
            <air:FareInfo Key="mncuvSAeRdO49K6yTNPRHQ==" FareBasis="T2PP" PassengerTypeCode="ADT" Origin="NAG" Destination="CCU" EffectiveDate="2015-08-13T12:51:00.000+00:00" DepartureDate="2015-08-15" Amount="LKR2200" NegotiatedFare="false" NotValidBefore="2015-08-16" NotValidAfter="2015-08-16">
               <air:BaggageAllowance>
                  <air:NumberOfPieces>0</air:NumberOfPieces>
                  <air:MaxWeight />
               </air:BaggageAllowance>
               <air:FareRuleKey FareInfoRef="mncuvSAeRdO49K6yTNPRHQ==" ProviderCode="1G">gws-eJxNTrsOhDAM+xjk3UlVHWNRRbguETrdDSz3/59BShmwFEeJnUcpRSmZs6TywIT/tDQ0/wAORtT6g5CERnGAoiu+uu+4x3O0/ZJGTt1Eo2mMmxqH0oHjYl+2sTGhn0P40Gl9R/LmRtWQZ2oWqSG8EC+dkY0mSA==</air:FareRuleKey>
            </air:FareInfo>
         </air:FareInfoList>
         <air:RouteList>
            <air:Route Key="RqwxQoZ2SyOdSiKIghrUDg==">
               <air:Leg Key="ttAZE/Q+TjilRz7PQ68gag==" Group="0" Origin="DEL" Destination="CCU" />
               <air:Leg Key="jOPDcdG2SHie1amqP0a1cg==" Group="1" Origin="CCU" Destination="DEL" />
            </air:Route>
         </air:RouteList>
         <air:AirPricePointList>
            <air:AirPricePoint Key="ZBOK21T7Rg6K4MkRqluP2Q==" TotalPrice="LKR213505" BasePrice="INR28800" ApproximateTotalPrice="LKR213505" ApproximateBasePrice="LKR61900" EquivalentBasePrice="LKR61900" Taxes="LKR151605" ApproximateTaxes="LKR151605" CompleteItinerary="true">
               <air:AirPricingInfo Key="29Hd7dpAREeQSRDzYKqA0Q==" TotalPrice="LKR26226" BasePrice="INR3400" ApproximateTotalPrice="LKR26226" ApproximateBasePrice="LKR7300" EquivalentBasePrice="LKR7300" Taxes="LKR18926" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="vhZKTijwQMiKUHQirsWQbw==" />
                  <air:FareInfoRef Key="AxOxhtXIQL2sIu/EK9/ySw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1118" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR12656" />
                  <air:FareCalc>DEL AI CCU 1700SIP AI DEL 1700SIP INR3400END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="0xZyX7RpQ7iWXvPVG2YX2Q==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="2uNW+ADtQsCcgOOeQVHPHA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="u+1wn6crTEOkFcf7Og2Aaw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="QiyCyDKIRniVVAQi0H+asg==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="/V2tI/EwSCip2HiCwIn1dA==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="3M5hcFDfRU+uxwmgNoyv/A==" TotalPrice="LKR26015" BasePrice="INR3300" ApproximateTotalPrice="LKR26015" ApproximateBasePrice="LKR7100" EquivalentBasePrice="LKR7100" Taxes="LKR18915" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="+j5GT6uvQcu3nX85snsEew==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1107" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR12656" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI DEL 1650UIPCH INR3300END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="zGn6O/KBQxKSWW7t6PKlkg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="sPJNKGiFSYG+uBwXSRMDjA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="56rjNCKEQhCaT8R3+LYqrw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="1vcWpZl7RnCZ0eAPCRim0w==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="V3n6pNn4ReuZ4urXKxleJQ==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="0gs9tQ+uRsupmRMlZ/vQkA==" TotalPrice="LKR4541" BasePrice="INR2000" ApproximateTotalPrice="LKR4541" ApproximateBasePrice="LKR4300" EquivalentBasePrice="LKR4300" Taxes="LKR241" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="C1iHtM7YS+WaRxm13dTv5Q==" />
                  <air:FareInfoRef Key="kV6FtaSTQfSH2HBROWEyew==" />
                  <air:TaxInfo Category="JN" Amount="LKR241" />
                  <air:FareCalc>DEL AI CCU 1000SIPIN AI DEL 1000SIPIN INR2000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="1rubXi8rSmO0+OtbhWtRYA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="ev3BStjVSyKFxvDPpYyBqA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="03OG2aJyQhuqXsbC00ahEw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Aod0Mm4qRkupbwZ7mvBsYA==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="kV6FtaSTQfSH2HBROWEyew==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="NicvM9ykRDKy3IOHl7OKnQ==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="kV6FtaSTQfSH2HBROWEyew==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="rBcJSxPeT369YPeW/UWfxQ==" TotalPrice="LKR219417" BasePrice="INR31292" ApproximateTotalPrice="LKR219417" ApproximateBasePrice="LKR67500" EquivalentBasePrice="LKR67500" Taxes="LKR151917" ApproximateTaxes="LKR151917" CompleteItinerary="true">
               <air:AirPricingInfo Key="KRT+PqsmTw28mduLx9jcYQ==" TotalPrice="LKR27599" BasePrice="INR3973" ApproximateTotalPrice="LKR27599" ApproximateBasePrice="LKR8600" EquivalentBasePrice="LKR8600" Taxes="LKR18999" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="vhZKTijwQMiKUHQirsWQbw==" />
                  <air:FareInfoRef Key="Eq8ubHSZQBC/f3jX95rsrw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1191" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR12656" />
                  <air:FareCalc>DEL AI CCU 1700SIP AI DEL 2273TIP INR3973END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="QKa8NCSdR+SwvVcX8j8iLg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="vIdOCAmmS/ycMN/WuRCqsQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="G+hkgmFaS5aR+ijasX2UPw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="GZ0O/x9pQdmSWrx/i00INA==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Eq8ubHSZQBC/f3jX95rsrw==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="tstSXHfHSteTtPODLjW32Q==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Eq8ubHSZQBC/f3jX95rsrw==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="VEwP57kTQGWQPWsVDfSDHQ==" TotalPrice="LKR26120" BasePrice="INR3350" ApproximateTotalPrice="LKR26120" ApproximateBasePrice="LKR7200" EquivalentBasePrice="LKR7200" Taxes="LKR18920" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="tOMMiEiPT7ylCyq+mJpbXw==" />
                  <air:FareInfoRef Key="+j5GT6uvQcu3nX85snsEew==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1112" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR12656" />
                  <air:FareCalc>DEL AI CCU 1700SIP AI DEL 1650UIPCH INR3350END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="35+xO6eXSmOJOLZznOVPgw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="tOMMiEiPT7ylCyq+mJpbXw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="7+GMUI9hQtSyGoR9O3Jpag==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="tOMMiEiPT7ylCyq+mJpbXw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="RxmMTyoIQcGyuoBPX1H5rw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="tOMMiEiPT7ylCyq+mJpbXw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Ry0eXMs5R7aGU0MsMXgMKQ==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="z280vtVmT7Cr/B/XBm7SOQ==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="WWmJ3orRTyixSO6bKHOMOw==" TotalPrice="LKR4541" BasePrice="INR2000" ApproximateTotalPrice="LKR4541" ApproximateBasePrice="LKR4300" EquivalentBasePrice="LKR4300" Taxes="LKR241" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="x52yed0kQOSSOEesfOQg6Q==" />
                  <air:FareInfoRef Key="pfdNSh9mSsKEFhZpN7ewgQ==" />
                  <air:TaxInfo Category="JN" Amount="LKR241" />
                  <air:FareCalc>DEL AI CCU 1000TIPIN AI DEL 1000TIPIN INR2000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="5YPZsvc9Rm2MJJxWoF1eHQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="x52yed0kQOSSOEesfOQg6Q==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="EwuRNkG1SJGfhTHvwEP4pQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="x52yed0kQOSSOEesfOQg6Q==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="Is16oi+NRXuKJa9GZB982A==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="x52yed0kQOSSOEesfOQg6Q==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="A8GaMuczRAKISerA+3+7tQ==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pfdNSh9mSsKEFhZpN7ewgQ==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="WUdHUR2xRBmGMx3U+EYfeA==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pfdNSh9mSsKEFhZpN7ewgQ==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="yEA89EXJQeWwTVQiN/ejfA==" TotalPrice="LKR224485" BasePrice="INR33584" ApproximateTotalPrice="LKR224485" ApproximateBasePrice="LKR72300" EquivalentBasePrice="LKR72300" Taxes="LKR152185" ApproximateTaxes="LKR152185" CompleteItinerary="true">
               <air:AirPricingInfo Key="P4AWIDhNT8imA+Zq+XL6Lg==" TotalPrice="LKR28866" BasePrice="INR4546" ApproximateTotalPrice="LKR28866" ApproximateBasePrice="LKR9800" EquivalentBasePrice="LKR9800" Taxes="LKR19066" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="QYxqcLJwTUWhBfh/GDV1wA==" />
                  <air:FareInfoRef Key="Eq8ubHSZQBC/f3jX95rsrw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1258" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR12656" />
                  <air:FareCalc>DEL AI CCU 2273TIP AI DEL 2273TIP INR4546END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="8H5RlIt9QrSGDXiB7M4XRA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="QYxqcLJwTUWhBfh/GDV1wA==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="UxRjFTljS2q+YRngQFEWoQ==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Eq8ubHSZQBC/f3jX95rsrw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="PQgyqNEcRn2D76p7kPAjHQ==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Eq8ubHSZQBC/f3jX95rsrw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="1m1xlOuMR4qIcvgysFkWJQ==" TotalPrice="LKR26120" BasePrice="INR3350" ApproximateTotalPrice="LKR26120" ApproximateBasePrice="LKR7200" EquivalentBasePrice="LKR7200" Taxes="LKR18920" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="o+OGTNSuSZivoveynD8EWg==" />
                  <air:FareInfoRef Key="AxOxhtXIQL2sIu/EK9/ySw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1112" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR12656" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI DEL 1700SIP INR3350END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="pNoa6zIbTTKY+B8HnkK6RQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="o+OGTNSuSZivoveynD8EWg==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Mzivb/GsQEKToa5/WZcjZA==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="azrhFgtHS8yEEFFPbpqV1Q==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="bAVV9WsIRC2uH1U09Ppy+Q==" TotalPrice="LKR4541" BasePrice="INR2000" ApproximateTotalPrice="LKR4541" ApproximateBasePrice="LKR4300" EquivalentBasePrice="LKR4300" Taxes="LKR241" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="x52yed0kQOSSOEesfOQg6Q==" />
                  <air:FareInfoRef Key="pfdNSh9mSsKEFhZpN7ewgQ==" />
                  <air:TaxInfo Category="JN" Amount="LKR241" />
                  <air:FareCalc>DEL AI CCU 1000TIPIN AI DEL 1000TIPIN INR2000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="qG6R3ItMSwqhzvbP5IRYKw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="x52yed0kQOSSOEesfOQg6Q==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="FACwGTGSTJ+a6JPm5elHxA==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pfdNSh9mSsKEFhZpN7ewgQ==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="CAQ25PhJTFug0jHTRaXDdQ==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pfdNSh9mSsKEFhZpN7ewgQ==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="5eHyGWpeSU6LRwoQkuJfAw==" TotalPrice="LKR226597" BasePrice="INR34544" ApproximateTotalPrice="LKR226597" ApproximateBasePrice="LKR74300" EquivalentBasePrice="LKR74300" Taxes="LKR152297" ApproximateTaxes="LKR152297" CompleteItinerary="true">
               <air:AirPricingInfo Key="Z220cDY6R0qpEImjnI0vpw==" TotalPrice="LKR28866" BasePrice="INR4546" ApproximateTotalPrice="LKR28866" ApproximateBasePrice="LKR9800" EquivalentBasePrice="LKR9800" Taxes="LKR19066" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="QYxqcLJwTUWhBfh/GDV1wA==" />
                  <air:FareInfoRef Key="Eq8ubHSZQBC/f3jX95rsrw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1258" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR12656" />
                  <air:FareCalc>DEL AI CCU 2273TIP AI DEL 2273TIP INR4546END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="/QjkXA4xRFODzKFh1phHfg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="QYxqcLJwTUWhBfh/GDV1wA==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="WHa8CJWsQjeU+wH94wEFAg==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Eq8ubHSZQBC/f3jX95rsrw==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="OLXNtVwrQiS+5MYTDtvv8Q==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Eq8ubHSZQBC/f3jX95rsrw==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="pEH3PLPWRfe7kk7cWJiplA==" TotalPrice="LKR26648" BasePrice="INR3590" ApproximateTotalPrice="LKR26648" ApproximateBasePrice="LKR7700" EquivalentBasePrice="LKR7700" Taxes="LKR18948" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="tOP82U9XR++psl0W+SFEaA==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1140" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR12656" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI DEL 1940LIPCH INR3590END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="Beg6JD6CS+OQ97pxJRjB9g==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="or67wk0vRRetqgX94tMK+g==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="tOP82U9XR++psl0W+SFEaA==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="GUDg0BONRDeHyrPAQgTRYg==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="tOP82U9XR++psl0W+SFEaA==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="FngBjor/QIO6Vd3ZsHrgzw==" TotalPrice="LKR4541" BasePrice="INR2000" ApproximateTotalPrice="LKR4541" ApproximateBasePrice="LKR4300" EquivalentBasePrice="LKR4300" Taxes="LKR241" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="x52yed0kQOSSOEesfOQg6Q==" />
                  <air:FareInfoRef Key="pfdNSh9mSsKEFhZpN7ewgQ==" />
                  <air:TaxInfo Category="JN" Amount="LKR241" />
                  <air:FareCalc>DEL AI CCU 1000TIPIN AI DEL 1000TIPIN INR2000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="ZDHD0t2ASMW3zxadApgchw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="x52yed0kQOSSOEesfOQg6Q==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="s43jT9mGRgKT/+UtucOfqA==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pfdNSh9mSsKEFhZpN7ewgQ==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="oXHfxoP6Q9WAyK1g1UJ8sg==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pfdNSh9mSsKEFhZpN7ewgQ==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="hRZSJdLWQNqwUgwbpe4svQ==" TotalPrice="LKR256103" BasePrice="INR27855" ApproximateTotalPrice="LKR256103" ApproximateBasePrice="LKR60200" EquivalentBasePrice="LKR60200" Taxes="LKR195903" ApproximateTaxes="LKR195903" CompleteItinerary="true">
               <air:AirPricingInfo Key="gPYZXukWS42aQLi4Dfhbpw==" TotalPrice="LKR31353" BasePrice="INR3195" ApproximateTotalPrice="LKR31353" ApproximateBasePrice="LKR6900" EquivalentBasePrice="LKR6900" Taxes="LKR24453" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="vhZKTijwQMiKUHQirsWQbw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="vQFs/R/7ReGEjkZyQK2o+g==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1390" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI CCU 1700SIP AI BOM 595SIP AI DEL 900SIPF2 INR3195END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="bh9hQRyoTxqMR9uS/wVbXg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="HY9ysTkxTOC5n3EPxDOJcw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="4Nh+mf9ZR9yrKr4T1Tj1Og==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="oI3GllBxSMCQYVZ23QOZtA==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="Ha420maBSx2f7N5sJfuZLQ==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="IqQMDA95TNC1B6VT92u8lA==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="hxs5EUpRRHqiH8lYzYiknQ==" TotalPrice="LKR31247" BasePrice="INR3145" ApproximateTotalPrice="LKR31247" ApproximateBasePrice="LKR6800" EquivalentBasePrice="LKR6800" Taxes="LKR24447" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="S+0l6QMMQdqJdn+DPbCzrg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1384" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI BOM 595SIP AI DEL 900SIPF2 INR3145END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="hvRJWwVHSISpTN/jOeNw9A==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="+rkH+OpIT/ebtyeeel3Ayg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="9HQMvHyoSAGNpzJAd/5CRw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="BzdGpMLhROaLsMi9PARdvQ==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="/MmaO1h2T3S/G8aXBm4V8w==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="545Lll+nRbCa9BytgctHcg==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="31QPGnGvQpqBc0Cv79tvHg==" TotalPrice="LKR5703" BasePrice="INR2495" ApproximateTotalPrice="LKR5703" ApproximateBasePrice="LKR5400" EquivalentBasePrice="LKR5400" Taxes="LKR303" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="BlnHqkzSTMW1mzO3sNwF1A==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="vQFs/R/7ReGEjkZyQK2o+g==" />
                  <air:TaxInfo Category="JN" Amount="LKR303" />
                  <air:FareCalc>DEL AI CCU 1000SIPIN AI BOM 595SIP AI DEL 900SIPF2 INR2495END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="ReimvcuiSjSURYo6ch8FAQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="f77BItqQRGmTCfUv+Spzmg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="HXdhyYnQQQ+7l8Ewx13f5w==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="NDaY8H8oR6aiVUwnFcBj7Q==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="KDr33SBBS9aeDRYSnB2vkQ==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="UYdY2Er5Q/W7ENo+vwtfbA==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="y4r9zdr/RiKiBOM5Dahedw==" TotalPrice="LKR256103" BasePrice="INR27855" ApproximateTotalPrice="LKR256103" ApproximateBasePrice="LKR60200" EquivalentBasePrice="LKR60200" Taxes="LKR195903" ApproximateTaxes="LKR195903" CompleteItinerary="true">
               <air:AirPricingInfo Key="W2Cl5rFeSKefwd3/xzJ32g==" TotalPrice="LKR31353" BasePrice="INR3195" ApproximateTotalPrice="LKR31353" ApproximateBasePrice="LKR6900" EquivalentBasePrice="LKR6900" Taxes="LKR24453" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="AxOxhtXIQL2sIu/EK9/ySw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1390" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI DEL 1700SIP INR3195END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="O1ddIFB1QaiiHqUiGKXhoQ==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="DjN3XM5jT+2lUC/lSXtnTQ==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="/WQay+r7QIyIFrsf+yg/2g==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="T+msmGJGSjK0OlyLn9zUsg==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="pPAPAvH7QKmh31sSPt21FA==" TotalPrice="LKR31247" BasePrice="INR3145" ApproximateTotalPrice="LKR31247" ApproximateBasePrice="LKR6800" EquivalentBasePrice="LKR6800" Taxes="LKR24447" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xmj1Up2mQGGV39WduCRM+A==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="+j5GT6uvQcu3nX85snsEew==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1384" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI DEL 1650UIPCH INR3145END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="TCxRm0k9Seu33fFGlBJECQ==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="dROFhemiSV+y6WIB3wwnBg==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="6zYYsMSOTni2rw7iTmbXeQ==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="Hv0bGXwhSsmUhQ7DSoQ52A==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="MPzDq/YZSwKKbRo7615DfQ==" TotalPrice="LKR5703" BasePrice="INR2495" ApproximateTotalPrice="LKR5703" ApproximateBasePrice="LKR5400" EquivalentBasePrice="LKR5400" Taxes="LKR303" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="WcgbQFtdQ3OJ1jlKUDMufw==" />
                  <air:TaxInfo Category="JN" Amount="LKR303" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI DEL 1000SIPIN INR2495END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="0Z0wMuKWRVyOjmSfnEsl2Q==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="KFVF6nb7Q52zkF7Ik44xCQ==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="jW08iDhLQhGg/F5mIWhkiA==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="WcgbQFtdQ3OJ1jlKUDMufw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="lEcjoqmEQ8ulfMaatdU2VQ==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="WcgbQFtdQ3OJ1jlKUDMufw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="dnt79qK2RX2EcO3Nvlk2tw==" TotalPrice="LKR260216" BasePrice="INR34240" ApproximateTotalPrice="LKR260216" ApproximateBasePrice="LKR74000" EquivalentBasePrice="LKR74000" Taxes="LKR186216" ApproximateTaxes="LKR186216" CompleteItinerary="true">
               <air:AirPricingInfo Key="otMqaau9Rw+zVRBEKxi3Hw==" TotalPrice="LKR31877" BasePrice="INR3980" ApproximateTotalPrice="LKR31877" ApproximateBasePrice="LKR8600" EquivalentBasePrice="LKR8600" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="uFkt5qFMQcaq+PCJlp+6/Q==" />
                  <air:FareInfoRef Key="ngWUS3fARwKBhs/HN4oOzA==" />
                  <air:FareInfoRef Key="AxOxhtXIQL2sIu/EK9/ySw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 600SIP AI DEL 1700SIP INR3980END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="SUFkTamiSBChUoUKRWQKZQ==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="uFkt5qFMQcaq+PCJlp+6/Q==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ngWUS3fARwKBhs/HN4oOzA==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="bQf41pnLR+KZqj3+RZ5O+g==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="2SrbhastR6OWShrGkmWDxw==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="zWwM+OE/SNiYTXQJiSii1A==" TotalPrice="LKR31777" BasePrice="INR3930" ApproximateTotalPrice="LKR31777" ApproximateBasePrice="LKR8500" EquivalentBasePrice="LKR8500" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="LrR2GdepRSGFqkjF5OHZ0g==" />
                  <air:FareInfoRef Key="esNqPUdtSVmDR4mCT8JC2w==" />
                  <air:FareInfoRef Key="+j5GT6uvQcu3nX85snsEew==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 600SIP AI DEL 1650UIPCH INR3930END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="kslE5fpmQUOHA0OKjUAGpA==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="LrR2GdepRSGFqkjF5OHZ0g==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="esNqPUdtSVmDR4mCT8JC2w==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="LnODEWGGQx2OGjcbjB5WsQ==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="qy5URGYBRIeBM15Uz4jGtQ==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="MH4WwpA+QFKtPc4be0lobw==" TotalPrice="LKR5600" BasePrice="INR2600" ApproximateTotalPrice="LKR5600" ApproximateBasePrice="LKR5600" EquivalentBasePrice="LKR5600" Taxes="LKR0" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xoNttsjURa+MJVSded4XFA==" />
                  <air:FareInfoRef Key="ngWUS3fARwKBhs/HN4oOzA==" />
                  <air:FareInfoRef Key="xvc5Ly/RQFiu/CoIvygwHQ==" />
                  <air:FareCalc>DEL AI GAU 1000SIPIN AI CCU 600SIP AI DEL 1000SIPIN INR2600END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="vEBZElrjTpuKSqz3gOhx0w==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xoNttsjURa+MJVSded4XFA==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ngWUS3fARwKBhs/HN4oOzA==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="iAF//XluQQmvT+LBBfTHhA==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xvc5Ly/RQFiu/CoIvygwHQ==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="IF4Z+aIgTZSJe7putfRTjA==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xvc5Ly/RQFiu/CoIvygwHQ==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="gwCFq99wTkeWgAVxvZgg1A==" TotalPrice="LKR261171" BasePrice="INR30147" ApproximateTotalPrice="LKR261171" ApproximateBasePrice="LKR65000" EquivalentBasePrice="LKR65000" Taxes="LKR196171" ApproximateTaxes="LKR196171" CompleteItinerary="true">
               <air:AirPricingInfo Key="4DyeXhjETg2WFQHTRGd7kA==" TotalPrice="LKR32620" BasePrice="INR3768" ApproximateTotalPrice="LKR32620" ApproximateBasePrice="LKR8100" EquivalentBasePrice="LKR8100" Taxes="LKR24520" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="QYxqcLJwTUWhBfh/GDV1wA==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="vQFs/R/7ReGEjkZyQK2o+g==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1457" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI CCU 2273TIP AI BOM 595SIP AI DEL 900SIPF2 INR3768END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="NTRwkNuMQce5/tnZRj22vQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="QYxqcLJwTUWhBfh/GDV1wA==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="CktkgOieRliKjc0GM0ufGQ==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="0/XIomhfREmdLpPHTgbzCw==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="grZ5US4ZSHiIRvjxPJGtYQ==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="nHvV8RNNTFmoxwWGnCf0XQ==" TotalPrice="LKR31247" BasePrice="INR3145" ApproximateTotalPrice="LKR31247" ApproximateBasePrice="LKR6800" EquivalentBasePrice="LKR6800" Taxes="LKR24447" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="S+0l6QMMQdqJdn+DPbCzrg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1384" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI BOM 595SIP AI DEL 900SIPF2 INR3145END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="/oCNqGZwTpSvxYNn2EgfSA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="MDVvh3DPQS6CIpnlH4hQXA==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="cZOc89pVRaOIyYrdXowXYg==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="Hp0u7uvCRHKFUQ5JzWOE2g==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="WIMCDLG3RoSLU4wr7157LQ==" TotalPrice="LKR5703" BasePrice="INR2495" ApproximateTotalPrice="LKR5703" ApproximateBasePrice="LKR5400" EquivalentBasePrice="LKR5400" Taxes="LKR303" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="5PrUfMDZTeW2TZBVaXznWw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="vQFs/R/7ReGEjkZyQK2o+g==" />
                  <air:TaxInfo Category="JN" Amount="LKR303" />
                  <air:FareCalc>DEL AI CCU 1000TIPIN AI BOM 595SIP AI DEL 900SIPF2 INR2495END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="eX6Y5xBzRYmB2OC5xvT/Lw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="5PrUfMDZTeW2TZBVaXznWw==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="jxdtb3K1QSWqyyVUE07HlA==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="Dh66heI+SR2eSH4+sZautw==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="4s2TuDNaTzapAsy+U3KoEg==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="EsymHrsjSZWILRL5fzMw4A==" TotalPrice="LKR261171" BasePrice="INR30147" ApproximateTotalPrice="LKR261171" ApproximateBasePrice="LKR65000" EquivalentBasePrice="LKR65000" Taxes="LKR196171" ApproximateTaxes="LKR196171" CompleteItinerary="true">
               <air:AirPricingInfo Key="EwBJLxpTRyePTbONrWkH2A==" TotalPrice="LKR32620" BasePrice="INR3768" ApproximateTotalPrice="LKR32620" ApproximateBasePrice="LKR8100" EquivalentBasePrice="LKR8100" Taxes="LKR24520" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="pUoWB+i/QnqbWR7nGlk/ww==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1457" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI DEL 2273TIP INR3768END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="7KTB2EFxQN2j1aV87suBvg==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="HXWyRQufTLiyvqiR7sx4Bg==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="g1oOap9wSBu2kwvmvdQ+Bg==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pUoWB+i/QnqbWR7nGlk/ww==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="f6FnhcryRx+V2HQX2VSD2g==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pUoWB+i/QnqbWR7nGlk/ww==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="94acwXA4TOS6NWTc4+/lNA==" TotalPrice="LKR31247" BasePrice="INR3145" ApproximateTotalPrice="LKR31247" ApproximateBasePrice="LKR6800" EquivalentBasePrice="LKR6800" Taxes="LKR24447" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xmj1Up2mQGGV39WduCRM+A==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="+j5GT6uvQcu3nX85snsEew==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1384" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI DEL 1650UIPCH INR3145END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="AYDCzewCSbaVyg7WvyvH+Q==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="pMqnlzTeTty6Q3IYatAgGg==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="ydOTd1GtQSm5V0a29VRZ3Q==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="iT4zuLRQSymCG+QW75FYfw==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="dsan1BwATcaIxSe/J9s3/w==" TotalPrice="LKR5703" BasePrice="INR2495" ApproximateTotalPrice="LKR5703" ApproximateBasePrice="LKR5400" EquivalentBasePrice="LKR5400" Taxes="LKR303" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="s3VqGN90SKatIqEfyut3lA==" />
                  <air:TaxInfo Category="JN" Amount="LKR303" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI DEL 1000TIPIN INR2495END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="y9TOOLHgTrGy7EOznIaxMA==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="cOxOkRJVTK6yeaCl7lh0Bg==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="0hymlMiXQ0+g7uWjudxw5w==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="s3VqGN90SKatIqEfyut3lA==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="YJFaoyxoQZytaOS2H8PO4A==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="s3VqGN90SKatIqEfyut3lA==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="zuMFWmGNSvKjKwFLwSuSaw==" TotalPrice="LKR265016" BasePrice="INR36532" ApproximateTotalPrice="LKR265016" ApproximateBasePrice="LKR78800" EquivalentBasePrice="LKR78800" Taxes="LKR186216" ApproximateTaxes="LKR186216" CompleteItinerary="true">
               <air:AirPricingInfo Key="KjVWQO1kRpe/U68naXvzJA==" TotalPrice="LKR33077" BasePrice="INR4553" ApproximateTotalPrice="LKR33077" ApproximateBasePrice="LKR9800" EquivalentBasePrice="LKR9800" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="uFkt5qFMQcaq+PCJlp+6/Q==" />
                  <air:FareInfoRef Key="ngWUS3fARwKBhs/HN4oOzA==" />
                  <air:FareInfoRef Key="pUoWB+i/QnqbWR7nGlk/ww==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 600SIP AI DEL 2273TIP INR4553END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="nFWA7YvmTxGJWu9RDz1DSg==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="uFkt5qFMQcaq+PCJlp+6/Q==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ngWUS3fARwKBhs/HN4oOzA==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Cf0IWnZzRumLhlsVv6AX+A==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pUoWB+i/QnqbWR7nGlk/ww==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="E7cLMleLSQebe47La970rw==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pUoWB+i/QnqbWR7nGlk/ww==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="qiD4n0IAQQadv2dN3qbzrQ==" TotalPrice="LKR31777" BasePrice="INR3930" ApproximateTotalPrice="LKR31777" ApproximateBasePrice="LKR8500" EquivalentBasePrice="LKR8500" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="LrR2GdepRSGFqkjF5OHZ0g==" />
                  <air:FareInfoRef Key="esNqPUdtSVmDR4mCT8JC2w==" />
                  <air:FareInfoRef Key="+j5GT6uvQcu3nX85snsEew==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 600SIP AI DEL 1650UIPCH INR3930END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="pf78aRf5QqKhwh5lBmMpYA==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="LrR2GdepRSGFqkjF5OHZ0g==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="esNqPUdtSVmDR4mCT8JC2w==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Y9IZLmGcTZ6hSHhesJzBnQ==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="ozAKBg+ASReTF32Pr/Kddw==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="ix1OaJQKQy6XfC4e8v/+yw==" TotalPrice="LKR5600" BasePrice="INR2600" ApproximateTotalPrice="LKR5600" ApproximateBasePrice="LKR5600" EquivalentBasePrice="LKR5600" Taxes="LKR0" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xoNttsjURa+MJVSded4XFA==" />
                  <air:FareInfoRef Key="ngWUS3fARwKBhs/HN4oOzA==" />
                  <air:FareInfoRef Key="rEvQ2lgATQCPw/WfbjJF8A==" />
                  <air:FareCalc>DEL AI GAU 1000SIPIN AI CCU 600SIP AI DEL 1000TIPIN INR2600END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="6yhpo6aER7i54G8wcUjTVg==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xoNttsjURa+MJVSded4XFA==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ngWUS3fARwKBhs/HN4oOzA==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="L+uDFvJ3RN+MKGiBfmT3yQ==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="rEvQ2lgATQCPw/WfbjJF8A==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="FSIruFoSRc2MGhx7DrPivQ==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="rEvQ2lgATQCPw/WfbjJF8A==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="p/IbPXoXQ8u/dZrSKZZqqg==" TotalPrice="LKR266716" BasePrice="INR37440" ApproximateTotalPrice="LKR266716" ApproximateBasePrice="LKR80500" EquivalentBasePrice="LKR80500" Taxes="LKR186216" ApproximateTaxes="LKR186216" CompleteItinerary="true">
               <air:AirPricingInfo Key="ChBB+ZxjQzO1tAkWqubn8w==" TotalPrice="LKR32677" BasePrice="INR4380" ApproximateTotalPrice="LKR32677" ApproximateBasePrice="LKR9400" EquivalentBasePrice="LKR9400" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="vhZKTijwQMiKUHQirsWQbw==" />
                  <air:FareInfoRef Key="Dpok1lCdSDa1ECnqU3T5Ng==" />
                  <air:FareInfoRef Key="ySo1yud0SLekSm3rPYr13w==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI CCU 1700SIP AI GAU 1000TIP AI DEL 1680SIP INR4380END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="svoiwQCtTaK+2WhEyt7b5A==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="OmLlZWO+TkWL1vVlPECs7w==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="f3iSK1ptTw6Tcg86XvSwiw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="FBcrrL+IS/u8BZVSAsHL9Q==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Dpok1lCdSDa1ECnqU3T5Ng==" SegmentRef="zfRrybyAQKCgnT49l/csmA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ySo1yud0SLekSm3rPYr13w==" SegmentRef="+q/OtGJmROWwTud1azvScA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="mJWWr549Qw2yMAG0hYHgbg==" TotalPrice="LKR32377" BasePrice="INR4230" ApproximateTotalPrice="LKR32377" ApproximateBasePrice="LKR9100" EquivalentBasePrice="LKR9100" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="B+erd35uS0+ULWJZkEbRFg==" />
                  <air:FareInfoRef Key="8ujIOkpaQ5+fZ0iBbz521A==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI GAU 900UIPCH AI DEL 1680SIP INR4230END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="70rHgmtGRsifLxyh32JwcA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="advs1412RnW/dgjmEfzFIQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="XuUxFeVZTtKMBnyizYtuBQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="G6SmuSY1QVqmV6umbkVwRw==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="B+erd35uS0+ULWJZkEbRFg==" SegmentRef="zfRrybyAQKCgnT49l/csmA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="8ujIOkpaQ5+fZ0iBbz521A==" SegmentRef="+q/OtGJmROWwTud1azvScA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="66lg+ki3Q4W/csVU7Kvdzw==" TotalPrice="LKR6500" BasePrice="INR3000" ApproximateTotalPrice="LKR6500" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR0" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="C1iHtM7YS+WaRxm13dTv5Q==" />
                  <air:FareInfoRef Key="93+8H4sfRl68heBbxd4xJA==" />
                  <air:FareInfoRef Key="UtpUeW3+RA+ziatJ0hO7Tg==" />
                  <air:FareCalc>DEL AI CCU 1000SIPIN AI GAU 1000TIPIN AI DEL 1000SIPIN INR3000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="fHhhqEFKQju364mez5Zp/Q==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="5ci9cDT5QeCRhrfAMKZURA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="6+tLOdJcTIKm9HohthPY+g==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="nxUoTmTGQ6OpaCNa8fdkuQ==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="93+8H4sfRl68heBbxd4xJA==" SegmentRef="zfRrybyAQKCgnT49l/csmA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="UtpUeW3+RA+ziatJ0hO7Tg==" SegmentRef="+q/OtGJmROWwTud1azvScA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="1jAjysP2Se6h1WtolI2FOg==" TotalPrice="LKR267404" BasePrice="INR32788" ApproximateTotalPrice="LKR267404" ApproximateBasePrice="LKR70900" EquivalentBasePrice="LKR70900" Taxes="LKR196504" ApproximateTaxes="LKR196504" CompleteItinerary="true">
               <air:AirPricingInfo Key="Su15x3DJREaWkfVaFz84tQ==" TotalPrice="LKR32937" BasePrice="INR3878" ApproximateTotalPrice="LKR32937" ApproximateBasePrice="LKR8400" EquivalentBasePrice="LKR8400" Taxes="LKR24537" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="vhZKTijwQMiKUHQirsWQbw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="jJDobaIbRAyrNKJyNB5zMA==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1474" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI CCU 1700SIP AI BOM 595SIP AI DEL 1583T7SS INR3878END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="dPC+hV2ZRoW+NACAc3BYNA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="5QmoJbfwTzKovEUGQJaW+g==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="anVtKU5/QE+yuIS9JPbKHQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="NuAUaG1WTLmkTVWi3275sg==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="jJDobaIbRAyrNKJyNB5zMA==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="sXM9l/U/QFKNq2+SyLRNDg==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="jJDobaIbRAyrNKJyNB5zMA==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="EVZpQQUsS++VZHPdk9kzDA==" TotalPrice="LKR32198" BasePrice="INR3569" ApproximateTotalPrice="LKR32198" ApproximateBasePrice="LKR7700" EquivalentBasePrice="LKR7700" Taxes="LKR24498" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="KQlf9JiuQN29uPgwf72otg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1435" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI BOM 595SIP AI DEL 1324UIPCH INR3569END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="eDQvUPW1SDWclRhmzr8QYQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="s2QXj2yfQ/2DwnW/LNW5+g==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="+dtzCZ94SzW1mOi3XMKRAg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="cMYtHpBjTvKBx1gIVwHHfQ==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="+vB9OSEXScmUZfJ287nPTg==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="dy+GyFGRQDSxT+HhKjRp4w==" TotalPrice="LKR6864" BasePrice="INR3000" ApproximateTotalPrice="LKR6864" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR364" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="C1iHtM7YS+WaRxm13dTv5Q==" />
                  <air:FareInfoRef Key="XlcHRKCmT4WcTRNMTz8TEw==" />
                  <air:FareInfoRef Key="Km3uGloLRsme1sEXD6d6ZQ==" />
                  <air:TaxInfo Category="JN" Amount="LKR364" />
                  <air:FareCalc>DEL AI CCU 1000SIPIN AI BOM 1000SIPIN AI DEL 1000SIPIN INR3000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="fTygekevQmqxw9MVEmmnPQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="2SyWL5RZQjSsng0zZTEiwg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="fHNRP0c1TTyg04bvzG1rhg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C1iHtM7YS+WaRxm13dTv5Q==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Cm0i/qP2T6aNJsGSNcPwTg==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XlcHRKCmT4WcTRNMTz8TEw==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Km3uGloLRsme1sEXD6d6ZQ==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="qunMnfrXT7GcdBduzBBmyw==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XlcHRKCmT4WcTRNMTz8TEw==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Km3uGloLRsme1sEXD6d6ZQ==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="GK759+g4RgGYeTnV70xbhQ==" TotalPrice="LKR268248" BasePrice="INR33188" ApproximateTotalPrice="LKR268248" ApproximateBasePrice="LKR71700" EquivalentBasePrice="LKR71700" Taxes="LKR196548" ApproximateTaxes="LKR196548" CompleteItinerary="true">
               <air:AirPricingInfo Key="2HF+LywFQvSQNT+PYbvK/w==" TotalPrice="LKR33148" BasePrice="INR3978" ApproximateTotalPrice="LKR33148" ApproximateBasePrice="LKR8600" EquivalentBasePrice="LKR8600" Taxes="LKR24548" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="iRwoCw8zR2ypgtnkcRN5Lg==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="AxOxhtXIQL2sIu/EK9/ySw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1485" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI BOM 1683TIP AI CCU 595SIP AI DEL 1700SIP INR3978END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="zVdxibSaT3qUYDZqxq/65w==" TravelTime="P0DT8H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="iRwoCw8zR2ypgtnkcRN5Lg==" SegmentRef="9fyjzRXwSyW7JrxYOQ0O5w==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="8uFOBnGaQmiSxkEq1BeGHw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="ok89P3k+TZ2jx+MfXecX+w==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="L3CKIqMzSc6qLMhV4ug5DA==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="wZKucgxnQ+u5lga2xey64g==" TotalPrice="LKR32198" BasePrice="INR3569" ApproximateTotalPrice="LKR32198" ApproximateBasePrice="LKR7700" EquivalentBasePrice="LKR7700" Taxes="LKR24498" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pLVKEj1URNKbzhiWQi8mIQ==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="+j5GT6uvQcu3nX85snsEew==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1435" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI BOM 1324UIPCH AI CCU 595SIP AI DEL 1650UIPCH INR3569END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="sAWoYv4qQBmseDURXjfNZw==" TravelTime="P0DT8H10M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pLVKEj1URNKbzhiWQi8mIQ==" SegmentRef="9fyjzRXwSyW7JrxYOQ0O5w==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="8uFOBnGaQmiSxkEq1BeGHw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="BdvXp3HeQGOItUrvXdMU9A==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="/Nn/4tr2QNG4/IthHUL8IQ==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="SQm0/uUwRGid1TObB/y+Zg==" TotalPrice="LKR6864" BasePrice="INR3000" ApproximateTotalPrice="LKR6864" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR364" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="C0GIBs7kRs2uVyBmv8gc3w==" />
                  <air:FareInfoRef Key="sCzETgarRWm/MHlOVQCT2w==" />
                  <air:FareInfoRef Key="KKnimHMLS/WvfyDBA2y+ig==" />
                  <air:TaxInfo Category="JN" Amount="LKR364" />
                  <air:FareCalc>DEL AI BOM 1000SIPIN AI CCU 1000SIPIN AI DEL 1000TIPIN INR3000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="ZHuDNtXTRpy47t9wtorpEQ==" TravelTime="P0DT8H10M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C0GIBs7kRs2uVyBmv8gc3w==" SegmentRef="9fyjzRXwSyW7JrxYOQ0O5w==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="sCzETgarRWm/MHlOVQCT2w==" SegmentRef="8uFOBnGaQmiSxkEq1BeGHw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="zAF1sW3CSEKq1MjKVTpL6A==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="KKnimHMLS/WvfyDBA2y+ig==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="zY28SqNBSYCNMw/rJp3Fhw==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="KKnimHMLS/WvfyDBA2y+ig==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="bJrXOBdlT8mV+x99MDDlKw==" TotalPrice="LKR271204" BasePrice="INR34508" ApproximateTotalPrice="LKR271204" ApproximateBasePrice="LKR74500" EquivalentBasePrice="LKR74500" Taxes="LKR196704" ApproximateTaxes="LKR196704" CompleteItinerary="true">
               <air:AirPricingInfo Key="cecrDDqNTcibuV4q8Kn8oQ==" TotalPrice="LKR33887" BasePrice="INR4308" ApproximateTotalPrice="LKR33887" ApproximateBasePrice="LKR9300" EquivalentBasePrice="LKR9300" Taxes="LKR24587" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="QYxqcLJwTUWhBfh/GDV1wA==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="2xS6epqRQmSxxFYlnhHBxA==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1524" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI CCU 2273TIP AI BOM 595SIP AI DEL 1440SIP INR4308END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="z/roXHxaR2+VHC+a8Mm9wg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="QYxqcLJwTUWhBfh/GDV1wA==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="eZhbVcUkRda1p75jIm2BXA==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="2xS6epqRQmSxxFYlnhHBxA==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="EoFqzTymTO22z5m7K5nsQA==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="2xS6epqRQmSxxFYlnhHBxA==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="BLzMnFUNQiCTSsyatk1soQ==" TotalPrice="LKR32198" BasePrice="INR3569" ApproximateTotalPrice="LKR32198" ApproximateBasePrice="LKR7700" EquivalentBasePrice="LKR7700" Taxes="LKR24498" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="KQlf9JiuQN29uPgwf72otg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1435" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI BOM 595SIP AI DEL 1324UIPCH INR3569END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="54wp1tg5R2S8Axn+jM8vOw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="5R+KC+PwRmi68pBJM6I7Dg==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="mZ5U87JFTLKOOubOcWV28w==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="iwv6y9z8TSWG7PQnJQHsZQ==" TotalPrice="LKR6864" BasePrice="INR3000" ApproximateTotalPrice="LKR6864" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR364" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="x52yed0kQOSSOEesfOQg6Q==" />
                  <air:FareInfoRef Key="XlcHRKCmT4WcTRNMTz8TEw==" />
                  <air:FareInfoRef Key="Km3uGloLRsme1sEXD6d6ZQ==" />
                  <air:TaxInfo Category="JN" Amount="LKR364" />
                  <air:FareCalc>DEL AI CCU 1000TIPIN AI BOM 1000SIPIN AI DEL 1000SIPIN INR3000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="qozbwDX9SbaahSpUFdPKng==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="x52yed0kQOSSOEesfOQg6Q==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Ib6AMavYREuf43C9+MCOAg==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XlcHRKCmT4WcTRNMTz8TEw==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Km3uGloLRsme1sEXD6d6ZQ==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="bI9V8j0LRq+2TGSamwR99Q==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XlcHRKCmT4WcTRNMTz8TEw==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Km3uGloLRsme1sEXD6d6ZQ==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="4jYcWw3hREW5eBVaMvu0jg==" TotalPrice="LKR271204" BasePrice="INR34508" ApproximateTotalPrice="LKR271204" ApproximateBasePrice="LKR74500" EquivalentBasePrice="LKR74500" Taxes="LKR196704" ApproximateTaxes="LKR196704" CompleteItinerary="true">
               <air:AirPricingInfo Key="ToXWZ1MNSF2KJxJXKfMuyA==" TotalPrice="LKR33887" BasePrice="INR4308" ApproximateTotalPrice="LKR33887" ApproximateBasePrice="LKR9300" EquivalentBasePrice="LKR9300" Taxes="LKR24587" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="r1wT2DJBQDmDWyfrnI4VvA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="Eq8ubHSZQBC/f3jX95rsrw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1524" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI BOM 1440SIP AI CCU 595SIP AI DEL 2273TIP INR4308END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="eBp8wJhNRMuWqLHU80DWYw==" TravelTime="P0DT8H10M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="r1wT2DJBQDmDWyfrnI4VvA==" SegmentRef="9fyjzRXwSyW7JrxYOQ0O5w==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="8uFOBnGaQmiSxkEq1BeGHw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="3uzAx6UMTc+4llHfPfUsXg==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Eq8ubHSZQBC/f3jX95rsrw==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="8/Jl0SM+T1im4R9oKVjfBw==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Eq8ubHSZQBC/f3jX95rsrw==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="j4cdfcAKTJCh4KTSmoUSJQ==" TotalPrice="LKR32198" BasePrice="INR3569" ApproximateTotalPrice="LKR32198" ApproximateBasePrice="LKR7700" EquivalentBasePrice="LKR7700" Taxes="LKR24498" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pLVKEj1URNKbzhiWQi8mIQ==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="+j5GT6uvQcu3nX85snsEew==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1435" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR17911" />
                  <air:FareCalc>DEL AI BOM 1324UIPCH AI CCU 595SIP AI DEL 1650UIPCH INR3569END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="36rNJg69Sg6Pc2N8JdkYQg==" TravelTime="P0DT8H10M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pLVKEj1URNKbzhiWQi8mIQ==" SegmentRef="9fyjzRXwSyW7JrxYOQ0O5w==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="8uFOBnGaQmiSxkEq1BeGHw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="PyMbXCl3QSCuLXziZvEryQ==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="xMyoMwazSPWD/psvn0LGcA==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="3vt1L63+Rjez6FEf2dYPew==" TotalPrice="LKR6864" BasePrice="INR3000" ApproximateTotalPrice="LKR6864" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR364" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="C0GIBs7kRs2uVyBmv8gc3w==" />
                  <air:FareInfoRef Key="sCzETgarRWm/MHlOVQCT2w==" />
                  <air:FareInfoRef Key="KKnimHMLS/WvfyDBA2y+ig==" />
                  <air:TaxInfo Category="JN" Amount="LKR364" />
                  <air:FareCalc>DEL AI BOM 1000SIPIN AI CCU 1000SIPIN AI DEL 1000TIPIN INR3000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="unW6gLAiQ8m8aLx2qpOuPw==" TravelTime="P0DT8H10M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="C0GIBs7kRs2uVyBmv8gc3w==" SegmentRef="9fyjzRXwSyW7JrxYOQ0O5w==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="sCzETgarRWm/MHlOVQCT2w==" SegmentRef="8uFOBnGaQmiSxkEq1BeGHw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="hsMQKG6UT+OaKA1rGuP9TA==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="KKnimHMLS/WvfyDBA2y+ig==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="3lQCRULVRp+AM1fMsFSzzw==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="KKnimHMLS/WvfyDBA2y+ig==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="HmyWkSPnQxmQnPAEH1OVaA==" TotalPrice="LKR271916" BasePrice="INR39732" ApproximateTotalPrice="LKR271916" ApproximateBasePrice="LKR85700" EquivalentBasePrice="LKR85700" Taxes="LKR186216" ApproximateTaxes="LKR186216" CompleteItinerary="true">
               <air:AirPricingInfo Key="NrRKKmYzT5maory+tfuKjQ==" TotalPrice="LKR33977" BasePrice="INR4953" ApproximateTotalPrice="LKR33977" ApproximateBasePrice="LKR10700" EquivalentBasePrice="LKR10700" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="QYxqcLJwTUWhBfh/GDV1wA==" />
                  <air:FareInfoRef Key="Dpok1lCdSDa1ECnqU3T5Ng==" />
                  <air:FareInfoRef Key="fvrlZ5WRRSaW+Jxq7lRPzQ==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI CCU 2273TIP AI GAU 1000TIP AI DEL 1680SIP INR4953END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="GpN2BI32RPS6JLxpLOpwzw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="QYxqcLJwTUWhBfh/GDV1wA==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="ccC5tVh1RraAzz/4/B4vuA==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Dpok1lCdSDa1ECnqU3T5Ng==" SegmentRef="zfRrybyAQKCgnT49l/csmA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="fvrlZ5WRRSaW+Jxq7lRPzQ==" SegmentRef="+q/OtGJmROWwTud1azvScA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="ra2oscfsRyKT9/trQluoNA==" TotalPrice="LKR32377" BasePrice="INR4230" ApproximateTotalPrice="LKR32377" ApproximateBasePrice="LKR9100" EquivalentBasePrice="LKR9100" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="B+erd35uS0+ULWJZkEbRFg==" />
                  <air:FareInfoRef Key="8ujIOkpaQ5+fZ0iBbz521A==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI GAU 900UIPCH AI DEL 1680SIP INR4230END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="uP4HO/MCQNCxO0ddAbBfBg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="WpeXYYpcTk2kqQ7EJTaKJw==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="B+erd35uS0+ULWJZkEbRFg==" SegmentRef="zfRrybyAQKCgnT49l/csmA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="8ujIOkpaQ5+fZ0iBbz521A==" SegmentRef="+q/OtGJmROWwTud1azvScA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="EC1g7i36Tq2Z3fXwVut2jA==" TotalPrice="LKR6500" BasePrice="INR3000" ApproximateTotalPrice="LKR6500" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR0" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="x52yed0kQOSSOEesfOQg6Q==" />
                  <air:FareInfoRef Key="93+8H4sfRl68heBbxd4xJA==" />
                  <air:FareInfoRef Key="UtpUeW3+RA+ziatJ0hO7Tg==" />
                  <air:FareCalc>DEL AI CCU 1000TIPIN AI GAU 1000TIPIN AI DEL 1000SIPIN INR3000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="dqh/8NvAQoyx+UF0YzV2mw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="x52yed0kQOSSOEesfOQg6Q==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="+JgSOW8TTGGmGxM1wGjGqg==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="93+8H4sfRl68heBbxd4xJA==" SegmentRef="zfRrybyAQKCgnT49l/csmA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="UtpUeW3+RA+ziatJ0hO7Tg==" SegmentRef="+q/OtGJmROWwTud1azvScA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="glecC71qRWaXtQpsaI7GTw==" TotalPrice="LKR282316" BasePrice="INR44540" ApproximateTotalPrice="LKR282316" ApproximateBasePrice="LKR96100" EquivalentBasePrice="LKR96100" Taxes="LKR186216" ApproximateTaxes="LKR186216" CompleteItinerary="true">
               <air:AirPricingInfo Key="tCLpMHaKSnS61QhcgQjsTQ==" TotalPrice="LKR35877" BasePrice="INR5830" ApproximateTotalPrice="LKR35877" ApproximateBasePrice="LKR12600" EquivalentBasePrice="LKR12600" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="uFkt5qFMQcaq+PCJlp+6/Q==" />
                  <air:FareInfoRef Key="nJmr9wnnTmu45h6yczKtZg==" />
                  <air:FareInfoRef Key="AxOxhtXIQL2sIu/EK9/ySw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 2450LIP AI DEL 1700SIP INR5830END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="RT7aSVUPQ+KxnT2O3LiCHA==" TravelTime="P1DT1H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="uFkt5qFMQcaq+PCJlp+6/Q==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="nJmr9wnnTmu45h6yczKtZg==" SegmentRef="brYsEIYORi2VzJLhucpx7Q==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="ul03ciAQQB69W+bGYrFTzg==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="cQS2jUhuQlyEpehFv2AkbA==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="VcMM4EWQRaC4Gmd+A7xKhg==" TotalPrice="LKR33077" BasePrice="INR4555" ApproximateTotalPrice="LKR33077" ApproximateBasePrice="LKR9800" EquivalentBasePrice="LKR9800" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="LrR2GdepRSGFqkjF5OHZ0g==" />
                  <air:FareInfoRef Key="y2pJgSbATaqx5cuvo9DL8w==" />
                  <air:FareInfoRef Key="w+dcGRoGSTG2xsStu/ruDg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 1225LIPCH AI DEL 1650UIPCH INR4555END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="r3juOdziSIeqv4q23bZHSw==" TravelTime="P1DT1H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="LrR2GdepRSGFqkjF5OHZ0g==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="y2pJgSbATaqx5cuvo9DL8w==" SegmentRef="brYsEIYORi2VzJLhucpx7Q==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="MOWo3KNbSta8jl18SbCnUQ==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="w+dcGRoGSTG2xsStu/ruDg==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="TFsiqVerSHm/myBDALGOfg==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="w+dcGRoGSTG2xsStu/ruDg==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="ifrCl2m7RfSRhY2sC/O71Q==" TotalPrice="LKR6500" BasePrice="INR3000" ApproximateTotalPrice="LKR6500" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR0" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="DXybxkZHRgms0iJ8pCEP0Q==" />
                  <air:FareInfoRef Key="HAIHqbRRSP6d7dQb/xGMAA==" />
                  <air:FareInfoRef Key="kNnfa8bWTw6OfH/KwW7uvQ==" />
                  <air:FareCalc>DEL AI GAU 1000SIPIN AI CCU 1000LIPIN AI DEL 1000SIPIN INR3000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="RTFtkb6rT320tPMwZaECGg==" TravelTime="P1DT1H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="DXybxkZHRgms0iJ8pCEP0Q==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="HAIHqbRRSP6d7dQb/xGMAA==" SegmentRef="brYsEIYORi2VzJLhucpx7Q==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="0LzJEXEcSCeIHgxH+BoOfw==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="kNnfa8bWTw6OfH/KwW7uvQ==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="/9pO8yksRXeHjy/tDaTihg==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="kNnfa8bWTw6OfH/KwW7uvQ==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="22n07Zy4RSKRftKatDopBQ==" TotalPrice="LKR287116" BasePrice="INR46832" ApproximateTotalPrice="LKR287116" ApproximateBasePrice="LKR100900" EquivalentBasePrice="LKR100900" Taxes="LKR186216" ApproximateTaxes="LKR186216" CompleteItinerary="true">
               <air:AirPricingInfo Key="JOesUBzoQWCoB3Fwfi3S3g==" TotalPrice="LKR37077" BasePrice="INR6403" ApproximateTotalPrice="LKR37077" ApproximateBasePrice="LKR13800" EquivalentBasePrice="LKR13800" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="uFkt5qFMQcaq+PCJlp+6/Q==" />
                  <air:FareInfoRef Key="nJmr9wnnTmu45h6yczKtZg==" />
                  <air:FareInfoRef Key="pUoWB+i/QnqbWR7nGlk/ww==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 2450LIP AI DEL 2273TIP INR6403END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="NwJLnPqIRuyHYbTiobqjkw==" TravelTime="P1DT1H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="uFkt5qFMQcaq+PCJlp+6/Q==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="nJmr9wnnTmu45h6yczKtZg==" SegmentRef="brYsEIYORi2VzJLhucpx7Q==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="anUwiZ0uTHOwSYW7O9EOyQ==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pUoWB+i/QnqbWR7nGlk/ww==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="WZzwHSzOQj2eMol4TUYF0Q==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="pUoWB+i/QnqbWR7nGlk/ww==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="UV3G76saQv2pUu5q5ji8Dg==" TotalPrice="LKR33077" BasePrice="INR4555" ApproximateTotalPrice="LKR33077" ApproximateBasePrice="LKR9800" EquivalentBasePrice="LKR9800" Taxes="LKR23277" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="LrR2GdepRSGFqkjF5OHZ0g==" />
                  <air:FareInfoRef Key="y2pJgSbATaqx5cuvo9DL8w==" />
                  <air:FareInfoRef Key="w+dcGRoGSTG2xsStu/ruDg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18125" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 1225LIPCH AI DEL 1650UIPCH INR4555END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="DX2ca12nSLGp+ZidNOxtJQ==" TravelTime="P1DT1H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="LrR2GdepRSGFqkjF5OHZ0g==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="y2pJgSbATaqx5cuvo9DL8w==" SegmentRef="brYsEIYORi2VzJLhucpx7Q==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="x4NkJ9GCRm2DgrKVmNVRVQ==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="w+dcGRoGSTG2xsStu/ruDg==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="i+q9li4MSGOKTJ5rTSo8OQ==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="w+dcGRoGSTG2xsStu/ruDg==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="I/yvjVCVSYKib7wNQTBxeQ==" TotalPrice="LKR6500" BasePrice="INR3000" ApproximateTotalPrice="LKR6500" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR0" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="DXybxkZHRgms0iJ8pCEP0Q==" />
                  <air:FareInfoRef Key="HAIHqbRRSP6d7dQb/xGMAA==" />
                  <air:FareInfoRef Key="KKnimHMLS/WvfyDBA2y+ig==" />
                  <air:FareCalc>DEL AI GAU 1000SIPIN AI CCU 1000LIPIN AI DEL 1000TIPIN INR3000END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="MgQIf6lPSnCSdDRfcHvrJA==" TravelTime="P1DT1H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="DXybxkZHRgms0iJ8pCEP0Q==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="L" CabinClass="Economy" FareInfoRef="HAIHqbRRSP6d7dQb/xGMAA==" SegmentRef="brYsEIYORi2VzJLhucpx7Q==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="DuCTDAMKQtGFZcmmYXy1Zg==" TravelTime="P0DT2H25M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="KKnimHMLS/WvfyDBA2y+ig==" SegmentRef="uA8H+g5WQiSwfBR2xDdA1Q==" />
                        </air:Option>
                        <air:Option Key="fCC+6nykRD26BLz8YARJ/g==" TravelTime="P0DT2H10M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="KKnimHMLS/WvfyDBA2y+ig==" SegmentRef="2rASq/24QDKOmppw3lvbog==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="VjLDqojCQDu9jyO8waPMDw==" TotalPrice="LKR291491" BasePrice="INR38135" ApproximateTotalPrice="LKR291491" ApproximateBasePrice="LKR82400" EquivalentBasePrice="LKR82400" Taxes="LKR209091" ApproximateTaxes="LKR209091" CompleteItinerary="true">
               <air:AirPricingInfo Key="sD8V4ypoQYWYp5lHMmwc9g==" TotalPrice="LKR36502" BasePrice="INR4815" ApproximateTotalPrice="LKR36502" ApproximateBasePrice="LKR10400" EquivalentBasePrice="LKR10400" Taxes="LKR26102" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="vhZKTijwQMiKUHQirsWQbw==" />
                  <air:FareInfoRef Key="UThLkJTnS1WNmyOBahDppQ==" />
                  <air:FareInfoRef Key="/JV5AE26TIOCY5L/1EpeTw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1646" />
                  <air:TaxInfo Category="WO" Amount="LKR1338" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18984" />
                  <air:FareCalc>DEL AI CCU 1700SIP AI HYD 3100SIP AI DEL 15SIPFS INR4815END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="+VmQYIoSRGmy+88vxn5koQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="L7TB+iSaTSeWzIexkPnLoA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="coDzn+GWQri+5cgIbonV0w==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="ik98KOZcSJejSjKq/JwrJQ==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="UThLkJTnS1WNmyOBahDppQ==" SegmentRef="Y/bbbkLMQIGGAh7vpxLgZQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="/JV5AE26TIOCY5L/1EpeTw==" SegmentRef="cdp54BU2QCOvFA8+beoWmg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="OwSieekeThSnQKGgj4cLcQ==" TotalPrice="LKR35129" BasePrice="INR4215" ApproximateTotalPrice="LKR35129" ApproximateBasePrice="LKR9100" EquivalentBasePrice="LKR9100" Taxes="LKR26029" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="VIJ+MbrmQKy/Ajs+hqEGRA==" />
                  <air:FareInfoRef Key="lemWb4FWQ06Nd6r/RQ9XDw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1573" />
                  <air:TaxInfo Category="WO" Amount="LKR1338" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18984" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI HYD 2550UIPCH AI DEL 15SIPFS INR4215END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="D+Jaujk2ReWqpogp/WMLAw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="DPDHUKjzTKm58zetQJ8T6g==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="bG3CJH6gQWuMJTDIlzWrzw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="J4cvNRABTAuuECA89m3TZQ==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="VIJ+MbrmQKy/Ajs+hqEGRA==" SegmentRef="Y/bbbkLMQIGGAh7vpxLgZQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="lemWb4FWQ06Nd6r/RQ9XDw==" SegmentRef="cdp54BU2QCOvFA8+beoWmg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="bUsaauHiRfGttG8lFLD6wg==" TotalPrice="LKR4967" BasePrice="INR2015" ApproximateTotalPrice="LKR4967" ApproximateBasePrice="LKR4400" EquivalentBasePrice="LKR4400" Taxes="LKR567" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="BlnHqkzSTMW1mzO3sNwF1A==" />
                  <air:FareInfoRef Key="YtI0xD5PSsyIcJXthkhvUA==" />
                  <air:FareInfoRef Key="/JV5AE26TIOCY5L/1EpeTw==" />
                  <air:TaxInfo Category="JN" Amount="LKR247" />
                  <air:TaxInfo Category="WO" Amount="LKR320" />
                  <air:FareCalc>DEL AI CCU 1000SIPIN AI HYD 1000SIPIN AI DEL 15SIPFS INR2015END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="cK2RNJ7LQamA343WBMix7w==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="vcDO+A/lSxuImLAyBKh0og==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="IzItEAKsQ2W92uUH2pTCIA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="4sd6ATlIRgCy2qO46bPu4g==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="YtI0xD5PSsyIcJXthkhvUA==" SegmentRef="Y/bbbkLMQIGGAh7vpxLgZQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="/JV5AE26TIOCY5L/1EpeTw==" SegmentRef="cdp54BU2QCOvFA8+beoWmg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="g0P4X81+Qt+L3XKh53uF1w==" TotalPrice="LKR296559" BasePrice="INR40427" ApproximateTotalPrice="LKR296559" ApproximateBasePrice="LKR87200" EquivalentBasePrice="LKR87200" Taxes="LKR209359" ApproximateTaxes="LKR209359" CompleteItinerary="true">
               <air:AirPricingInfo Key="0Ds/74jNQ1aF8jXQyHL60g==" TotalPrice="LKR37769" BasePrice="INR5388" ApproximateTotalPrice="LKR37769" ApproximateBasePrice="LKR11600" EquivalentBasePrice="LKR11600" Taxes="LKR26169" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="QYxqcLJwTUWhBfh/GDV1wA==" />
                  <air:FareInfoRef Key="UThLkJTnS1WNmyOBahDppQ==" />
                  <air:FareInfoRef Key="/JV5AE26TIOCY5L/1EpeTw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1713" />
                  <air:TaxInfo Category="WO" Amount="LKR1338" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18984" />
                  <air:FareCalc>DEL AI CCU 2273TIP AI HYD 3100SIP AI DEL 15SIPFS INR5388END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="YwRU/ZQmRBOCBa4OI+9TRg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="QYxqcLJwTUWhBfh/GDV1wA==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="rMyF1KH3RHO87Q9pHO5cfw==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="UThLkJTnS1WNmyOBahDppQ==" SegmentRef="Y/bbbkLMQIGGAh7vpxLgZQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="/JV5AE26TIOCY5L/1EpeTw==" SegmentRef="cdp54BU2QCOvFA8+beoWmg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="dorxDYW6TTm3RSjxENRcIg==" TotalPrice="LKR35129" BasePrice="INR4215" ApproximateTotalPrice="LKR35129" ApproximateBasePrice="LKR9100" EquivalentBasePrice="LKR9100" Taxes="LKR26029" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="VIJ+MbrmQKy/Ajs+hqEGRA==" />
                  <air:FareInfoRef Key="lemWb4FWQ06Nd6r/RQ9XDw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1573" />
                  <air:TaxInfo Category="WO" Amount="LKR1338" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR18984" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI HYD 2550UIPCH AI DEL 15SIPFS INR4215END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="PrGVeFtTSMOtnrMQ8dwFXA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="8Y9N6ZkVQaaSL89RN6JdnQ==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="VIJ+MbrmQKy/Ajs+hqEGRA==" SegmentRef="Y/bbbkLMQIGGAh7vpxLgZQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="lemWb4FWQ06Nd6r/RQ9XDw==" SegmentRef="cdp54BU2QCOvFA8+beoWmg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="oVhdBmwwShWD3Azcbbz8gw==" TotalPrice="LKR4967" BasePrice="INR2015" ApproximateTotalPrice="LKR4967" ApproximateBasePrice="LKR4400" EquivalentBasePrice="LKR4400" Taxes="LKR567" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="5PrUfMDZTeW2TZBVaXznWw==" />
                  <air:FareInfoRef Key="YtI0xD5PSsyIcJXthkhvUA==" />
                  <air:FareInfoRef Key="/JV5AE26TIOCY5L/1EpeTw==" />
                  <air:TaxInfo Category="JN" Amount="LKR247" />
                  <air:TaxInfo Category="WO" Amount="LKR320" />
                  <air:FareCalc>DEL AI CCU 1000TIPIN AI HYD 1000SIPIN AI DEL 15SIPFS INR2015END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="msiUg9zISq+ezDUiAbih4g==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="5PrUfMDZTeW2TZBVaXznWw==" SegmentRef="IdUvqgwDSRa7oHCASHJHBQ==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="JMVUGrUWQImJvwryzqUoKA==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="YtI0xD5PSsyIcJXthkhvUA==" SegmentRef="Y/bbbkLMQIGGAh7vpxLgZQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="/JV5AE26TIOCY5L/1EpeTw==" SegmentRef="cdp54BU2QCOvFA8+beoWmg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="CyQkaPEpSKirQSl+QIp3lQ==" TotalPrice="LKR298704" BasePrice="INR26910" ApproximateTotalPrice="LKR298704" ApproximateBasePrice="LKR58500" EquivalentBasePrice="LKR58500" Taxes="LKR240204" ApproximateTaxes="LKR240204" CompleteItinerary="true">
               <air:AirPricingInfo Key="KFuyD1LuQLC6HBDOtZuK7g==" TotalPrice="LKR36480" BasePrice="INR2990" ApproximateTotalPrice="LKR36480" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR29980" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="vQFs/R/7ReGEjkZyQK2o+g==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1662" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 900SIPF2 INR2990END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="BwIR0m/LQoCYGj6ZtfyStw==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="46ApTCSyS/yT64XCaLGaMA==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="UnQFQ3M7S7CI1lTn+ToOBg==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="krBMZYafT+K9DbGkzQS9qA==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="+whrfTEjRr+ewcpRay8GDQ==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="SB30SUIQT4+rCFOYk8VvRA==" TotalPrice="LKR36480" BasePrice="INR2990" ApproximateTotalPrice="LKR36480" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR29980" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xmj1Up2mQGGV39WduCRM+A==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="S+0l6QMMQdqJdn+DPbCzrg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1662" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 900SIPF2 INR2990END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="MdLe8amaTLyg4WVksAQGdA==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="BMwFCpa4SQe5htn4V9KLqA==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="i//5FITtTuC1lbDpaD0Wug==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="7kwRQO2YTe27ugDrKLH2jA==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="VW6PZ7LpRhKv91cmd9liAw==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="jHsX5g25RFmiukbTAqwShw==" TotalPrice="LKR6864" BasePrice="INR2990" ApproximateTotalPrice="LKR6864" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR364" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="VwRioCWsQXS07kHkGKWzuQ==" />
                  <air:TaxInfo Category="JN" Amount="LKR364" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 900SIPF2 INR2990END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="gsu2SDM8Q+CCdoWWAjJZqw==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="Igzcn9DPRqmVlxreqvLqeA==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="a0FDonuVSeC2wF389s9NAA==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="VlFK+o/9Q5en4PlhvOYjyA==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="kGJhiSOvTdCL8BA2bPwE6A==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="VqAldTlWRFen3GNaiC+cYg==" TotalPrice="LKR298704" BasePrice="INR26910" ApproximateTotalPrice="LKR298704" ApproximateBasePrice="LKR58500" EquivalentBasePrice="LKR58500" Taxes="LKR240204" ApproximateTaxes="LKR240204" CompleteItinerary="true">
               <air:AirPricingInfo Key="gzLKGv0NQc6wGSZBtuBzvA==" TotalPrice="LKR36480" BasePrice="INR2990" ApproximateTotalPrice="LKR36480" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR29980" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="vQFs/R/7ReGEjkZyQK2o+g==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1662" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 900SIPF2 INR2990END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="QxRHQ5yBT8Cjv+gMEkq7Uw==" TravelTime="P0DT13H40M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="2RAXEsLeRli0sHR610ZeCA==" TravelTime="P0DT19H55M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Dc9T7ttoR52l7LLyu8jeew==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="j089p2PeReO2oZu54QiSDw==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="wn5bE4dnRhOduecF+nIdnA==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="8FIziNZGR5KoOucko3MzKg==" TotalPrice="LKR36480" BasePrice="INR2990" ApproximateTotalPrice="LKR36480" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR29980" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xmj1Up2mQGGV39WduCRM+A==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="S+0l6QMMQdqJdn+DPbCzrg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1662" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 900SIPF2 INR2990END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="hlneaDF5QYuK4jta+JpCCw==" TravelTime="P0DT13H40M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="i1BZkbWFQ26ksOcfSPURLA==" TravelTime="P0DT19H55M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Ybps+1EBRqWuL6KiQlo1lg==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="13Av93uCRTCwb8UvUKbj2A==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="cwerMO3qSHOAnx14j5J/9Q==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="1z7akdPRRmmRUpHtdYdYxg==" TotalPrice="LKR6864" BasePrice="INR2990" ApproximateTotalPrice="LKR6864" ApproximateBasePrice="LKR6500" EquivalentBasePrice="LKR6500" Taxes="LKR364" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="VwRioCWsQXS07kHkGKWzuQ==" />
                  <air:TaxInfo Category="JN" Amount="LKR364" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 900SIPF2 INR2990END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="Cg9oboCYS16ZKxW5BmYA2w==" TravelTime="P0DT13H40M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="gSgGr3opSVSuNZP5P2yX5w==" TravelTime="P0DT19H55M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="Uhf4f3RzQzKBczRTEulrRA==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="E9logV91R8i0U0M9FZMmtA==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="c5Ab/KRkSNGYlHSSGb5TtA==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="s5PPDdLvRUCAeYEcZphnpg==" TotalPrice="LKR299756" BasePrice="INR33295" ApproximateTotalPrice="LKR299756" ApproximateBasePrice="LKR71500" EquivalentBasePrice="LKR71500" Taxes="LKR228256" ApproximateTaxes="LKR228256" CompleteItinerary="true">
               <air:AirPricingInfo Key="/CSfZp/MTpqom4NkuzMIUg==" TotalPrice="LKR36632" BasePrice="INR3775" ApproximateTotalPrice="LKR36632" ApproximateBasePrice="LKR8100" EquivalentBasePrice="LKR8100" Taxes="LKR28532" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="uFkt5qFMQcaq+PCJlp+6/Q==" />
                  <air:FareInfoRef Key="ngWUS3fARwKBhs/HN4oOzA==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="akRkAnOeRu25/0vJU0NWww==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23380" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 600SIP AI BOM 595SIP AI DEL 900SIPF2 INR3775END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="+wSklJSTTYib4ukoBwa4pw==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="uFkt5qFMQcaq+PCJlp+6/Q==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ngWUS3fARwKBhs/HN4oOzA==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="j0bcF7Z4ReCmDIWj1OIWyA==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="akRkAnOeRu25/0vJU0NWww==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="C0CPWrMsTC+nCclLwRUK4Q==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="akRkAnOeRu25/0vJU0NWww==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="KuItZJveTFCYoeV6pkcSYw==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="akRkAnOeRu25/0vJU0NWww==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="kwH2s3pUSeOnbWfQrHc5SA==" TotalPrice="LKR36632" BasePrice="INR3775" ApproximateTotalPrice="LKR36632" ApproximateBasePrice="LKR8100" EquivalentBasePrice="LKR8100" Taxes="LKR28532" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="LrR2GdepRSGFqkjF5OHZ0g==" />
                  <air:FareInfoRef Key="esNqPUdtSVmDR4mCT8JC2w==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="UcA8Kxa/TeWnuk1jpDYPUw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23380" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 600SIP AI BOM 595SIP AI DEL 900SIPF2 INR3775END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="iOCvCPRrRcqTFQv7F1CZBw==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="LrR2GdepRSGFqkjF5OHZ0g==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="esNqPUdtSVmDR4mCT8JC2w==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="3nB3Ol3iQ+iBtq95fN3V+A==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="UcA8Kxa/TeWnuk1jpDYPUw==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="YlyquYhsSyqyHW8vy6rGVw==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="UcA8Kxa/TeWnuk1jpDYPUw==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="7Y3RaOoDRbSCqdUwW3NAXg==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="UcA8Kxa/TeWnuk1jpDYPUw==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="ENvOWb/XTQKz8SYUddr+Gg==" TotalPrice="LKR6700" BasePrice="INR3095" ApproximateTotalPrice="LKR6700" ApproximateBasePrice="LKR6700" EquivalentBasePrice="LKR6700" Taxes="LKR0" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xoNttsjURa+MJVSded4XFA==" />
                  <air:FareInfoRef Key="ngWUS3fARwKBhs/HN4oOzA==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="VwRioCWsQXS07kHkGKWzuQ==" />
                  <air:FareCalc>DEL AI GAU 1000SIPIN AI CCU 600SIP AI BOM 595SIP AI DEL 900SIPF2 INR3095END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="k2lYAYY4TdmvIzM3ZUbXdg==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xoNttsjURa+MJVSded4XFA==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ngWUS3fARwKBhs/HN4oOzA==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="ARlI2l6yTuukA6DHkyXTnA==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="yaYxf26dRTuECb6WD3gjfA==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="Aetxwz6EQC+0p9DZofEVsA==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="BUEXqQ4GQeqk5ynPlhn6Ew==" TotalPrice="LKR306956" BasePrice="INR36495" ApproximateTotalPrice="LKR306956" ApproximateBasePrice="LKR78700" EquivalentBasePrice="LKR78700" Taxes="LKR228256" ApproximateTaxes="LKR228256" CompleteItinerary="true">
               <air:AirPricingInfo Key="xyLN6BfjS16Dteqc5Vrfow==" TotalPrice="LKR37532" BasePrice="INR4175" ApproximateTotalPrice="LKR37532" ApproximateBasePrice="LKR9000" EquivalentBasePrice="LKR9000" Taxes="LKR28532" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="Dpok1lCdSDa1ECnqU3T5Ng==" />
                  <air:FareInfoRef Key="ySo1yud0SLekSm3rPYr13w==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23380" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI GAU 1000TIP AI DEL 1680SIP INR4175END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="7dAVf6e/Rn6w5YkrP9IXuA==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="ARdPXNczQrK+QaYsift78g==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="x3rbLdNmQRCudVzKnlYysA==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Dpok1lCdSDa1ECnqU3T5Ng==" SegmentRef="zfRrybyAQKCgnT49l/csmA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ySo1yud0SLekSm3rPYr13w==" SegmentRef="+q/OtGJmROWwTud1azvScA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="dfTv5zdIQga1oPBOXkA9Lg==" TotalPrice="LKR37332" BasePrice="INR4075" ApproximateTotalPrice="LKR37332" ApproximateBasePrice="LKR8800" EquivalentBasePrice="LKR8800" Taxes="LKR28532" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xmj1Up2mQGGV39WduCRM+A==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="B+erd35uS0+ULWJZkEbRFg==" />
                  <air:FareInfoRef Key="8ujIOkpaQ5+fZ0iBbz521A==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23380" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI GAU 900UIPCH AI DEL 1680SIP INR4075END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="rqQ9+OAFSsmbVBFE+JBdxg==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="n+JOJRyWRSO/j8rSfmht1w==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="ZlJS2jV6TZa90wyWJ5WKNA==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="B+erd35uS0+ULWJZkEbRFg==" SegmentRef="zfRrybyAQKCgnT49l/csmA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="8ujIOkpaQ5+fZ0iBbz521A==" SegmentRef="+q/OtGJmROWwTud1azvScA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="4xj5G1b1Rg2b9SSdC0Uxfg==" TotalPrice="LKR7500" BasePrice="INR3495" ApproximateTotalPrice="LKR7500" ApproximateBasePrice="LKR7500" EquivalentBasePrice="LKR7500" Taxes="LKR0" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="Dpok1lCdSDa1ECnqU3T5Ng==" />
                  <air:FareInfoRef Key="KvRCnZxLTWmw9cghjTaQRw==" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI GAU 1000TIPIN AI DEL 1000SIPIN INR3495END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="uS2MdmiNTbqm4vDNgd4v1Q==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="w9evG6LFTs2MCRzSz/xiPQ==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="tGn+pIcCQhKgwelJyHq4gA==" TravelTime="P0DT9H45M0S">
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="Dpok1lCdSDa1ECnqU3T5Ng==" SegmentRef="zfRrybyAQKCgnT49l/csmA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="KvRCnZxLTWmw9cghjTaQRw==" SegmentRef="+q/OtGJmROWwTud1azvScA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="V/19FLfnTFeq1xrD8kxAow==" TotalPrice="LKR307360" BasePrice="INR30866" ApproximateTotalPrice="LKR307360" ApproximateBasePrice="LKR66700" EquivalentBasePrice="LKR66700" Taxes="LKR240660" ApproximateTaxes="LKR240660" CompleteItinerary="true">
               <air:AirPricingInfo Key="vr+LtCFxQ2u9xKgC45cB4g==" TotalPrice="LKR37641" BasePrice="INR3530" ApproximateTotalPrice="LKR37641" ApproximateBasePrice="LKR7600" EquivalentBasePrice="LKR7600" Taxes="LKR30041" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="XZdxsFuhS5Oh8HrR2rJFBg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1723" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 1440SIP INR3530END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="m3PbIaysT5+ZBoYf4sHKtg==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="j9nLmykyRZGnjmPG06vxUA==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="93EVBnp7QIi7k2AyQrnmxA==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XZdxsFuhS5Oh8HrR2rJFBg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="nKkOjpynRwi7bk0cAQxAUQ==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XZdxsFuhS5Oh8HrR2rJFBg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="OuRZQLQ+QE60UMV6l95W5A==" TotalPrice="LKR37430" BasePrice="INR3414" ApproximateTotalPrice="LKR37430" ApproximateBasePrice="LKR7400" EquivalentBasePrice="LKR7400" Taxes="LKR30030" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xmj1Up2mQGGV39WduCRM+A==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="KQlf9JiuQN29uPgwf72otg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1712" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 1324UIPCH INR3414END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="S0O5pfoTRIO5HtCPhONtxg==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="6mTgPJ1YTT2P+NJB4Iji3g==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="LRcXr3HWQKq63NR6TAxHjQ==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="SzDqsKaoQp2pqoKKAmj7CA==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="+q5KljnnSuufQ+qFd/ESOA==" TotalPrice="LKR7076" BasePrice="INR3090" ApproximateTotalPrice="LKR7076" ApproximateBasePrice="LKR6700" EquivalentBasePrice="LKR6700" Taxes="LKR376" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="T+nMGihkS9SkzN2CKNjgyg==" />
                  <air:TaxInfo Category="JN" Amount="LKR376" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 1000SIPIN INR3090END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="fno32ipEQPSF77n1kykiQg==" TravelTime="P0DT9H50M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="j1xTW0pcQOq3aPLWfv4HGA==" TravelTime="P0DT16H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="NR+T7JOWSKeeUMsjAALEng==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="T+nMGihkS9SkzN2CKNjgyg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="XLvz33AwSJq7ALrQwySSyw==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="T+nMGihkS9SkzN2CKNjgyg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="+rJPUFBORLCqfU5F+QXbGA==" TotalPrice="LKR307360" BasePrice="INR30866" ApproximateTotalPrice="LKR307360" ApproximateBasePrice="LKR66700" EquivalentBasePrice="LKR66700" Taxes="LKR240660" ApproximateTaxes="LKR240660" CompleteItinerary="true">
               <air:AirPricingInfo Key="UUWg0JOIS+ylL4+wBVaOVQ==" TotalPrice="LKR37641" BasePrice="INR3530" ApproximateTotalPrice="LKR37641" ApproximateBasePrice="LKR7600" EquivalentBasePrice="LKR7600" Taxes="LKR30041" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="r1wT2DJBQDmDWyfrnI4VvA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="vQFs/R/7ReGEjkZyQK2o+g==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1723" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 1440SIP AI CCU 595SIP AI BOM 595SIP AI DEL 900SIPF2 INR3530END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="Sy22nJwsSBOVU2YtZJt0kQ==" TravelTime="P0DT8H10M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="r1wT2DJBQDmDWyfrnI4VvA==" SegmentRef="9fyjzRXwSyW7JrxYOQ0O5w==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="8uFOBnGaQmiSxkEq1BeGHw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="nhrzqQaKSRS+mosrA5wyfw==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="ssN3vumbTae5zxE6ruUyZw==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="h1UA0Cg7Tr+NDYAKK/870Q==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vQFs/R/7ReGEjkZyQK2o+g==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="U4PQs6cvR1Of3vU1eRGsXQ==" TotalPrice="LKR37430" BasePrice="INR3414" ApproximateTotalPrice="LKR37430" ApproximateBasePrice="LKR7400" EquivalentBasePrice="LKR7400" Taxes="LKR30030" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pLVKEj1URNKbzhiWQi8mIQ==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="S+0l6QMMQdqJdn+DPbCzrg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1712" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 1324UIPCH AI CCU 595SIP AI BOM 595SIP AI DEL 900SIPF2 INR3414END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="6rP26jhMQmO5aJV2jFtPEQ==" TravelTime="P0DT8H10M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pLVKEj1URNKbzhiWQi8mIQ==" SegmentRef="9fyjzRXwSyW7JrxYOQ0O5w==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="8uFOBnGaQmiSxkEq1BeGHw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="FQ4+CWjqSmqJ7gPkwa+pbw==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="ZoCqFw6vQiGyks1Jz3KqgQ==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="gOxO5vFQSxKa7k6UWoRXCA==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="S+0l6QMMQdqJdn+DPbCzrg==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="6wlf1TPNTvG4/wNOqjeLQw==" TotalPrice="LKR7076" BasePrice="INR3090" ApproximateTotalPrice="LKR7076" ApproximateBasePrice="LKR6700" EquivalentBasePrice="LKR6700" Taxes="LKR376" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xQ2ntkwzTkWQyzcyzfHSrg==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="VwRioCWsQXS07kHkGKWzuQ==" />
                  <air:TaxInfo Category="JN" Amount="LKR376" />
                  <air:FareCalc>DEL AI BOM 1000SIPIN AI CCU 595SIP AI BOM 595SIP AI DEL 900SIPF2 INR3090END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="TsRnC2oPT9uKhyN6FgVDfQ==" TravelTime="P0DT8H10M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xQ2ntkwzTkWQyzcyzfHSrg==" SegmentRef="9fyjzRXwSyW7JrxYOQ0O5w==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="8uFOBnGaQmiSxkEq1BeGHw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="uopXCq6jRRqvKHjbyPUfoQ==" TravelTime="P0DT17H45M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="7aKSfeP/RPyzazTUUrVICQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="2u9iSHW0RWSnZTfapYxhew==" TravelTime="P1DT3H30M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="lP6vjP0mRjCMn8RG3AHrQg==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="5Y6NUfh+R9iOVYTJDfBIcQ==" TravelTime="P1DT4H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="VwRioCWsQXS07kHkGKWzuQ==" SegmentRef="vSAiXZMhTnyj057GHJPQrw==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="IsYqXMclS6Cm8yOexyxQeg==" TotalPrice="LKR307360" BasePrice="INR30866" ApproximateTotalPrice="LKR307360" ApproximateBasePrice="LKR66700" EquivalentBasePrice="LKR66700" Taxes="LKR240660" ApproximateTaxes="LKR240660" CompleteItinerary="true">
               <air:AirPricingInfo Key="EwwHD0auRfyEq3LzENL2jA==" TotalPrice="LKR37641" BasePrice="INR3530" ApproximateTotalPrice="LKR37641" ApproximateBasePrice="LKR7600" EquivalentBasePrice="LKR7600" Taxes="LKR30041" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="XZdxsFuhS5Oh8HrR2rJFBg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1723" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 1440SIP INR3530END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="Ph/06AFuR6SVzt0Ckqu8Xg==" TravelTime="P0DT13H40M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="D/YRg+AqTqKU+toFHmh5eg==" TravelTime="P0DT19H55M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="e6T5IW0UT+WxR56bW+uSbA==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XZdxsFuhS5Oh8HrR2rJFBg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="B0EMsxS8SnWZYzSpT0hk3g==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XZdxsFuhS5Oh8HrR2rJFBg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="73BE90NMTMWjq6XxD1/NlQ==" TotalPrice="LKR37430" BasePrice="INR3414" ApproximateTotalPrice="LKR37430" ApproximateBasePrice="LKR7400" EquivalentBasePrice="LKR7400" Taxes="LKR30030" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xmj1Up2mQGGV39WduCRM+A==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="KQlf9JiuQN29uPgwf72otg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1712" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23166" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 1324UIPCH INR3414END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="rnIJnYI9Ty2S4bZNoghhsw==" TravelTime="P0DT13H40M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="d0V5DpCLS1GXEygfWvX3hg==" TravelTime="P0DT19H55M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xmj1Up2mQGGV39WduCRM+A==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="fGXuuI1ARZWtFNCgOv+uIA==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="LRbxsWt3QgebFsYxargFHA==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="K/AUQMiUQqSa4X0YxRvtBQ==" TotalPrice="LKR7076" BasePrice="INR3090" ApproximateTotalPrice="LKR7076" ApproximateBasePrice="LKR6700" EquivalentBasePrice="LKR6700" Taxes="LKR376" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="Y2cXXLrWT1CwKt0O7/q2bA==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="T+nMGihkS9SkzN2CKNjgyg==" />
                  <air:TaxInfo Category="JN" Amount="LKR376" />
                  <air:FareCalc>DEL AI BOM 900SIPF2 AI CCU 595SIP AI BOM 595SIP AI DEL 1000SIPIN INR3090END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="Xj7nAsD3RR2o42Lh26h62w==" TravelTime="P0DT13H40M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="UzOxqrRLQHiCE5G6b35RjQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="3f5szXMfSty2c9Y6rVFLqQ==" TravelTime="P0DT19H55M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Y2cXXLrWT1CwKt0O7/q2bA==" SegmentRef="zLI1YLC5Re6vsx7MfsoILQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="PRTIxTZbRJi+2LRBHD8u3A==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="BQgesbrTTbWGlDHmNARXew==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="T+nMGihkS9SkzN2CKNjgyg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="EgtbplRpR3y3rkXOaS18eg==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="T+nMGihkS9SkzN2CKNjgyg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="GPzJsn+VTsKGitijS1s81g==" TotalPrice="LKR307362" BasePrice="INR29934" ApproximateTotalPrice="LKR307362" ApproximateBasePrice="LKR64700" EquivalentBasePrice="LKR64700" Taxes="LKR242662" ApproximateTaxes="LKR242662" CompleteItinerary="true">
               <air:AirPricingInfo Key="El0P+a3jT7Ww3hyW0sLhRA==" TotalPrice="LKR37696" BasePrice="INR3426" ApproximateTotalPrice="LKR37696" ApproximateBasePrice="LKR7400" EquivalentBasePrice="LKR7400" Taxes="LKR30296" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="vhZKTijwQMiKUHQirsWQbw==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="NkXLAAoMSCiwt0/F9OLs+g==" />
                  <air:FareInfoRef Key="Kz5K/9bgR8OXU1KhYtZIFQ==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1710" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23434" />
                  <air:FareCalc>DEL AI CCU 1700SIP AI BOM 595SIP AI GOI 150SIP AI DEL 981SIPF1 INR3426END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="4F4MZe8pTTKomfZwHFD6bw==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="6UBP84PlT4GLKPN7vXv6JA==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="jSy5nzHHRKyhOT6zabqVYQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="vhZKTijwQMiKUHQirsWQbw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="AAJxPbc/QY6OSNAj50prsA==" TravelTime="P0DT20H35M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="NkXLAAoMSCiwt0/F9OLs+g==" SegmentRef="vxK3zAAsQxObn1Y8grrzbg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Kz5K/9bgR8OXU1KhYtZIFQ==" SegmentRef="EB0F6gVyTwirp4gpFNBtYw==" />
                           <air:Connection SegmentIndex="0" />
                           <air:Connection SegmentIndex="1" />
                        </air:Option>
                        <air:Option Key="TlDlNFP1TDOgHjme2MA1iQ==" TravelTime="P1DT10H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="NkXLAAoMSCiwt0/F9OLs+g==" SegmentRef="G+Eyr0TNSz2/FcMIep324g==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Kz5K/9bgR8OXU1KhYtZIFQ==" SegmentRef="EB0F6gVyTwirp4gpFNBtYw==" />
                           <air:Connection SegmentIndex="0" />
                           <air:Connection SegmentIndex="1" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="09/tK3/oRMajhR5hWxV2Lg==" TotalPrice="LKR37591" BasePrice="INR3376" ApproximateTotalPrice="LKR37591" ApproximateBasePrice="LKR7300" EquivalentBasePrice="LKR7300" Taxes="LKR30291" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="pngxXU5LS0SaP6Gos2GfVw==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="MBNJEK+gT4yGB/3z3msoXQ==" />
                  <air:FareInfoRef Key="/TGUxSY2RJGxW/oqK6w5XQ==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1705" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23434" />
                  <air:FareCalc>DEL AI CCU 1650UIPCH AI BOM 595SIP AI GOI 150SIP AI DEL 981SIPF1 INR3376END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="UkDKOLCORYucA9Z/3PpkUQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="Kmfa8FZkQPOvXoQzov7RJg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="zS8ukArsRuWlRw0Vqw5Pwg==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="pngxXU5LS0SaP6Gos2GfVw==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="2N5r4dkIQf6Iz7J1a+1IJw==" TravelTime="P0DT20H35M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="MBNJEK+gT4yGB/3z3msoXQ==" SegmentRef="vxK3zAAsQxObn1Y8grrzbg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="/TGUxSY2RJGxW/oqK6w5XQ==" SegmentRef="EB0F6gVyTwirp4gpFNBtYw==" />
                           <air:Connection SegmentIndex="0" />
                           <air:Connection SegmentIndex="1" />
                        </air:Option>
                        <air:Option Key="Sm6K1M1TRfuiF+zr9a6iIA==" TravelTime="P1DT10H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="MBNJEK+gT4yGB/3z3msoXQ==" SegmentRef="G+Eyr0TNSz2/FcMIep324g==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="/TGUxSY2RJGxW/oqK6w5XQ==" SegmentRef="EB0F6gVyTwirp4gpFNBtYw==" />
                           <air:Connection SegmentIndex="0" />
                           <air:Connection SegmentIndex="1" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="bW8rNRwPTtyrcHayPTbPqw==" TotalPrice="LKR6214" BasePrice="INR2726" ApproximateTotalPrice="LKR6214" ApproximateBasePrice="LKR5900" EquivalentBasePrice="LKR5900" Taxes="LKR314" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="BlnHqkzSTMW1mzO3sNwF1A==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="NkXLAAoMSCiwt0/F9OLs+g==" />
                  <air:FareInfoRef Key="dZg/eAcdRFWE8e3cInUwug==" />
                  <air:TaxInfo Category="JN" Amount="LKR314" />
                  <air:FareCalc>DEL AI CCU 1000SIPIN AI BOM 595SIP AI GOI 150SIP AI DEL 981SIPF1 INR2726END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="Xgu1gTpGRNezN17CQLZPWQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" SegmentRef="zsah84E2TPewCZ9z3xsZGA==" />
                        </air:Option>
                        <air:Option Key="it7uoPPfS8CcTvlkeFlrtQ==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" SegmentRef="lHT5ul6HRNKsiPd30C0NmA==" />
                        </air:Option>
                        <air:Option Key="9pciYJq+Sxq83dvc7kgyww==" TravelTime="P0DT2H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="BlnHqkzSTMW1mzO3sNwF1A==" SegmentRef="IQ5pFPt9TdmiJ3NFgwCh4A==" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="LN/mWXbUSpKH/Pchz3yLDg==" TravelTime="P0DT20H35M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="NkXLAAoMSCiwt0/F9OLs+g==" SegmentRef="vxK3zAAsQxObn1Y8grrzbg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="dZg/eAcdRFWE8e3cInUwug==" SegmentRef="EB0F6gVyTwirp4gpFNBtYw==" />
                           <air:Connection SegmentIndex="0" />
                           <air:Connection SegmentIndex="1" />
                        </air:Option>
                        <air:Option Key="3msmlTcZQPuTO3j9kMHAJw==" TravelTime="P1DT10H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="NkXLAAoMSCiwt0/F9OLs+g==" SegmentRef="G+Eyr0TNSz2/FcMIep324g==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="dZg/eAcdRFWE8e3cInUwug==" SegmentRef="EB0F6gVyTwirp4gpFNBtYw==" />
                           <air:Connection SegmentIndex="0" />
                           <air:Connection SegmentIndex="1" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="rFK3cGO5TFW7KTUkl+ytAg==" TotalPrice="LKR308756" BasePrice="INR37251" ApproximateTotalPrice="LKR308756" ApproximateBasePrice="LKR80500" EquivalentBasePrice="LKR80500" Taxes="LKR228256" ApproximateTaxes="LKR228256" CompleteItinerary="true">
               <air:AirPricingInfo Key="KDwiawwURUS8o6EgDm/PyA==" TotalPrice="LKR37832" BasePrice="INR4315" ApproximateTotalPrice="LKR37832" ApproximateBasePrice="LKR9300" EquivalentBasePrice="LKR9300" Taxes="LKR28532" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="uFkt5qFMQcaq+PCJlp+6/Q==" />
                  <air:FareInfoRef Key="ngWUS3fARwKBhs/HN4oOzA==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="XZdxsFuhS5Oh8HrR2rJFBg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23380" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 600SIP AI BOM 595SIP AI DEL 1440SIP INR4315END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="2B13ySbxQme9wPRtXgvQnA==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="uFkt5qFMQcaq+PCJlp+6/Q==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ngWUS3fARwKBhs/HN4oOzA==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="1nk8mQKhSLqp6Llrse+yQA==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XZdxsFuhS5Oh8HrR2rJFBg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="2g6lCiH9R/qkONN5Cn9Lgg==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="XZdxsFuhS5Oh8HrR2rJFBg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="tndZw4AkTEO6UjFJMcbqow==" TotalPrice="LKR37632" BasePrice="INR4199" ApproximateTotalPrice="LKR37632" ApproximateBasePrice="LKR9100" EquivalentBasePrice="LKR9100" Taxes="LKR28532" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="LrR2GdepRSGFqkjF5OHZ0g==" />
                  <air:FareInfoRef Key="esNqPUdtSVmDR4mCT8JC2w==" />
                  <air:FareInfoRef Key="prtyo3U+R526p/kww43VLQ==" />
                  <air:FareInfoRef Key="KQlf9JiuQN29uPgwf72otg==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR23380" />
                  <air:FareCalc>DEL AI GAU 1680SIP AI CCU 600SIP AI BOM 595SIP AI DEL 1324UIPCH INR4199END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="MqvamIDTSG27cyYYPgSUrQ==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="LrR2GdepRSGFqkjF5OHZ0g==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="esNqPUdtSVmDR4mCT8JC2w==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="m+oyNU+XSPe5Z+fNoGb3EA==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="bpCHk03AQHGKcjtZmxCipQ==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="prtyo3U+R526p/kww43VLQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="KQlf9JiuQN29uPgwf72otg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="wOH4tcrVRByHMKGhMO7FEA==" TotalPrice="LKR6900" BasePrice="INR3195" ApproximateTotalPrice="LKR6900" ApproximateBasePrice="LKR6900" EquivalentBasePrice="LKR6900" Taxes="LKR0" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="xoNttsjURa+MJVSded4XFA==" />
                  <air:FareInfoRef Key="ngWUS3fARwKBhs/HN4oOzA==" />
                  <air:FareInfoRef Key="jIy9qlRrQlmEbzhpPX/EXQ==" />
                  <air:FareInfoRef Key="T+nMGihkS9SkzN2CKNjgyg==" />
                  <air:FareCalc>DEL AI GAU 1000SIPIN AI CCU 600SIP AI BOM 595SIP AI DEL 1000SIPIN INR3195END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="vpq0F0VlRcqS8OIAd0T24g==" TravelTime="P1DT2H0M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xoNttsjURa+MJVSded4XFA==" SegmentRef="eUe8d5+uR9qggpmIIAtFwg==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ngWUS3fARwKBhs/HN4oOzA==" SegmentRef="Oa8M7JGcTeyqERlcant8BQ==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="KEYvfZLPQ/OX7RbcgQ5ncg==" TravelTime="P0DT9H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="i4UHbVSWS++bvXv3AuCFRQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="T+nMGihkS9SkzN2CKNjgyg==" SegmentRef="wGDt8RX9TYy+0xEKj5oGqA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                        <air:Option Key="iaqRLVkxQbe2M5pT0s1QQg==" TravelTime="P0DT19H25M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="jIy9qlRrQlmEbzhpPX/EXQ==" SegmentRef="l/iIrjYNRK+wYfVZ/DtMuQ==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="T+nMGihkS9SkzN2CKNjgyg==" SegmentRef="LHoe87bfRTOZdkXgY2QFJA==" />
                           <air:Connection SegmentIndex="0" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
            <air:AirPricePoint Key="1o/zOAWbSROE/wzYm6Xi9g==" TotalPrice="LKR310071" BasePrice="INR34215" ApproximateTotalPrice="LKR310071" ApproximateBasePrice="LKR73800" EquivalentBasePrice="LKR73800" Taxes="LKR236271" ApproximateTaxes="LKR236271" CompleteItinerary="true">
               <air:AirPricingInfo Key="uHMQ3B7gR82iLSGKYwd8bQ==" TotalPrice="LKR38099" BasePrice="INR3990" ApproximateTotalPrice="LKR38099" ApproximateBasePrice="LKR8600" EquivalentBasePrice="LKR8600" Taxes="LKR29499" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="w2awbyeEQBmKvB3pdjO46w==" />
                  <air:FareInfoRef Key="D1yPPEAbSi6wzFvfnNoEeQ==" />
                  <air:FareInfoRef Key="ybESxWwkQRq6UysD2B5jSw==" />
                  <air:FareInfoRef Key="AxOxhtXIQL2sIu/EK9/ySw==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1610" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR22737" />
                  <air:FareCalc>DEL AI NAG 495SIP AI BOM 1200SIP AI CCU 595SIP AI DEL 1700SIP INR3990END</air:FareCalc>
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:PassengerType Code="ADT" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="ahmf8rv7TaqM76+QqThNEw==" TravelTime="P1DT3H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="w2awbyeEQBmKvB3pdjO46w==" SegmentRef="FaEAN8+uSeWscFk/l/CIOw==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="D1yPPEAbSi6wzFvfnNoEeQ==" SegmentRef="Vj2K7tszRruimjSTBsCWhA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="ybESxWwkQRq6UysD2B5jSw==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                           <air:Connection SegmentIndex="1" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="XrMH7lF7QYmuCNdL6IVsbA==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="nom3kiKQS8SJ+3yRxoJKEQ==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="AxOxhtXIQL2sIu/EK9/ySw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="mwvsEuOnQDKdX29mRMLAEQ==" TotalPrice="LKR37993" BasePrice="INR3940" ApproximateTotalPrice="LKR37993" ApproximateBasePrice="LKR8500" EquivalentBasePrice="LKR8500" Taxes="LKR29493" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="wcLH+lJKQDO9NsJYa5ZY+w==" />
                  <air:FareInfoRef Key="Jj8fx1bVRGyDXoyfRn2ZiA==" />
                  <air:FareInfoRef Key="xs62wc3GTS2PBq6CV1dfdQ==" />
                  <air:FareInfoRef Key="+j5GT6uvQcu3nX85snsEew==" />
                  <air:TaxInfo Category="IN" Amount="LKR3889" />
                  <air:TaxInfo Category="JN" Amount="LKR1604" />
                  <air:TaxInfo Category="WO" Amount="LKR1018" />
                  <air:TaxInfo Category="YM" Amount="LKR245" />
                  <air:TaxInfo Category="YQ" Amount="LKR22737" />
                  <air:FareCalc>DEL AI NAG 495SIP AI BOM 1200SIP AI CCU 595SIP AI DEL 1650UIPCH INR3940END</air:FareCalc>
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:PassengerType Code="CNN" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="H9wWajbXTbejo43ZcrY5xA==" TravelTime="P1DT3H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="wcLH+lJKQDO9NsJYa5ZY+w==" SegmentRef="FaEAN8+uSeWscFk/l/CIOw==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="Jj8fx1bVRGyDXoyfRn2ZiA==" SegmentRef="Vj2K7tszRruimjSTBsCWhA==" />
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="xs62wc3GTS2PBq6CV1dfdQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                           <air:Connection SegmentIndex="1" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="BWImQRSySh24iPW+20OqSA==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="5UYuwvQWTeyFbBllNXtI+A==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="U" CabinClass="Economy" FareInfoRef="+j5GT6uvQcu3nX85snsEew==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
               <air:AirPricingInfo Key="1BgRpilETsqZhZ8RTycOVA==" TotalPrice="LKR5703" BasePrice="INR2495" ApproximateTotalPrice="LKR5703" ApproximateBasePrice="LKR5400" EquivalentBasePrice="LKR5400" Taxes="LKR303" LatestTicketingTime="2015-08-13T23:59:00.000+00:00" PricingMethod="Guaranteed" Refundable="true" ETicketability="Yes" PlatingCarrier="AI" ProviderCode="1G">
                  <air:FareInfoRef Key="w2awbyeEQBmKvB3pdjO46w==" />
                  <air:FareInfoRef Key="mncuvSAeRdO49K6yTNPRHQ==" />
                  <air:FareInfoRef Key="WcgbQFtdQ3OJ1jlKUDMufw==" />
                  <air:TaxInfo Category="JN" Amount="LKR303" />
                  <air:FareCalc>DEL AI NAG 495SIP AI X/BOM AI CCU 1000T2PPIN AI DEL 1000SIPIN INR2495END</air:FareCalc>
                  <air:PassengerType Code="INF" />
                  <air:ChangePenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:ChangePenalty>
                  <air:CancelPenalty>
                     <air:Amount>LKR3400.0</air:Amount>
                  </air:CancelPenalty>
                  <air:FlightOptionsList>
                     <air:FlightOption LegRef="ttAZE/Q+TjilRz7PQ68gag==" Destination="CCU" Origin="DEL">
                        <air:Option Key="jAitfSS0TQWrp3CW5QIrGQ==" TravelTime="P1DT3H5M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="w2awbyeEQBmKvB3pdjO46w==" SegmentRef="FaEAN8+uSeWscFk/l/CIOw==" />
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mncuvSAeRdO49K6yTNPRHQ==" SegmentRef="Vj2K7tszRruimjSTBsCWhA==" />
                           <air:BookingInfo BookingCode="T" CabinClass="Economy" FareInfoRef="mncuvSAeRdO49K6yTNPRHQ==" SegmentRef="9MZFai/WSSK9rcFd35Lj8w==" />
                           <air:Connection SegmentIndex="0" />
                           <air:Connection SegmentIndex="1" />
                        </air:Option>
                     </air:FlightOption>
                     <air:FlightOption LegRef="jOPDcdG2SHie1amqP0a1cg==" Destination="DEL" Origin="CCU">
                        <air:Option Key="X7SkOeuVTCqCWLgaXJGAyQ==" TravelTime="P0DT2H15M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="WcgbQFtdQ3OJ1jlKUDMufw==" SegmentRef="ORptOTDPT2GX3sBgBTVq2w==" />
                        </air:Option>
                        <air:Option Key="IQ6t1JpmQq2wKrjNusaiyQ==" TravelTime="P0DT2H20M0S">
                           <air:BookingInfo BookingCode="S" CabinClass="Economy" FareInfoRef="WcgbQFtdQ3OJ1jlKUDMufw==" SegmentRef="zO0aH/RoS9eViVvbWNeyRg==" />
                        </air:Option>
                     </air:FlightOption>
                  </air:FlightOptionsList>
               </air:AirPricingInfo>
            </air:AirPricePoint>
         </air:AirPricePointList>
      </air:LowFareSearchRsp>
   </SOAP:Body>
</SOAP:Envelope>
	';*/
	
	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
	$xml = simplexml_load_string($xml);
	$json = json_encode($xml);
	$responseArray = json_decode($json,true);
	
	$_SESSION['baseresponse']=$responseArray;
}


// lets process
if(isset($responseArray['SOAPBody']['SOAPFault']['faultstring'])){
	die($responseArray['SOAPBody']['SOAPFault']['faultstring']);
}
//echo "<pre>";
//print_r($responseArray);
//
//die();
if($searchdata['mode']=='oneway'){
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airFlightDetailsList']['airFlightDetails'];
if(empty($results))$results=array();
$flightdetails=array();
foreach($results as $result){
    $data=array(
	    'Key'=> $result['@attributes']['Key'],
		'Origin'=> $result['@attributes']['Origin'],
		'Destination'=> $result['@attributes']['Destination'],
		'DepartureTime'=> $result['@attributes']['DepartureTime'],
		'ArrivalTime'=> $result['@attributes']['ArrivalTime'],
		'FlightTime'=> $result['@attributes']['FlightTime'],
		'TravelTime'=> $result['@attributes']['TravelTime'],
		'Equipment'=> $result['@attributes']['Equipment'],
		'OriginTerminal'=> $result['@attributes']['OriginTerminal'],
		'DestinationTerminal'=> $result['@attributes']['DestinationTerminal']
	);
	$flightdetails[]=$data;        
}
//echo "<pre>";
//print_r($flightdetails);

//echo "<br>AirSegment";
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirSegmentList']['airAirSegment'];
if(empty($results))$results=array();
$airsegments=array();
foreach($results as $result){
	 $data=array(
	    'Key'=>$result['@attributes']['Key'],
		'Group'=>$result['@attributes']['Group'],
		'Carrier'=>$result['@attributes']['Carrier'],
		'FlightNumber'=>$result['@attributes']['FlightNumber'],
		'Origin'=>$result['@attributes']['Origin'],
		'Destination'=>$result['@attributes']['Destination'],
		'DepartureTime'=>$result['@attributes']['DepartureTime'],
		'ArrivalTime'=>$result['@attributes']['ArrivalTime'],
		'FlightTime'=>$result['@attributes']['FlightTime'],
		'Distance'=>$result['@attributes']['Distance'],
		'ETicketability'=>$result['@attributes']['ETicketability'],
		'Equipment'=>$result['@attributes']['Equipment'],
		'ChangeOfPlane'=>$result['@attributes']['ChangeOfPlane'],
		'ParticipantLevel'=>$result['@attributes']['ParticipantLevel'],
		'LinkAvailability'=>$result['@attributes']['LinkAvailability'],
		'PolledAvailabilityOption'=>$result['@attributes']['PolledAvailabilityOption'],
		'OptionalServicesIndicator'=>$result['@attributes']['OptionalServicesIndicator'],
		'AvailabilitySource'=>$result['@attributes']['AvailabilitySource'],
		'AvailabilityDisplayType'=>$result['@attributes']['AvailabilityDisplayType']
	);
	if(isset($result['airCodeshareInfo'])){
		 $data['airCodeshareInfo'] = $result['airCodeshareInfo'];
	 }else{
		  $data['airCodeshareInfo'] = '';
	 }
	 
	 if(isset($result['airAirAvailInfo'][0]['@attributes']['BookingCounts'])){
		  $data['BookingCounts']=$result['airAirAvailInfo'][0]['@attributes']['BookingCounts'];
	 }
	 if(isset($result['airFlightDetailsRef']['@attributes'])){
		  $data['airFlightDetailsRef']=$result['airFlightDetailsRef']['@attributes']['Key'];
	  }
  $airsegments[]=$data; 
	
}

//echo "<pre>";
//print_r($airsegments);
//die();

//echo "<br>Fairinfo";
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airFareInfoList']['airFareInfo'];
//echo "<pre>";
//print_r($results);
//die();
if(empty($results))$results=array();
$fairinfolist=array();
foreach($results as $result){
// $result=$results[17];
	 $data=array(
	    'Key' => $result['@attributes']['Key'],
		'FareBasis' => $result['@attributes']['FareBasis'],
		'PassengerTypeCode' => $result['@attributes']['PassengerTypeCode'],
		'Origin' => $result['@attributes']['Origin'],
		'Destination' => $result['@attributes']['Destination'],
		'EffectiveDate' => $result['@attributes']['EffectiveDate'],
		'DepartureDate' => $result['@attributes']['DepartureDate'],
		'Amount' => $result['@attributes']['Amount'],
		'NegotiatedFare' => $result['@attributes']['NegotiatedFare'],
		'NotValidBefore' => isset($result['@attributes']['NegotiatedFare'])?$result['@attributes']['NegotiatedFare']:'',
		'NotValidAfter' => isset($result['@attributes']['NotValidAfter'])?$result['@attributes']['NotValidAfter']:'',
	 );
	 if(isset($result['airFareSurcharge'])){
		 $op=array(
		    'Key' => $result['airFareSurcharge']['@attributes']['Key'],
			'Type' => $result['airFareSurcharge']['@attributes']['Type'],
			'Amount' => $result['airFareSurcharge']['@attributes']['Amount']
		 );
		$data['FareSurcharge']=$op;
	 }
	 
	 if(isset($result['airBaggageAllowance']) && is_array($result['airBaggageAllowance'])){
		 $allownce=array();
			 if(isset($result['airBaggageAllowance']['airNumberOfPieces'])){
				 $allownce['NumberOfPieces']=$result['airBaggageAllowance']['airNumberOfPieces'];
			 }
			 if(isset($result['airBaggageAllowance']['airMaxWeight'])){
				 $allownce['MaxWeight']=array('Value' => $result['airBaggageAllowance']['airMaxWeight']['Value'],'Unit' => $result['airBaggageAllowance']['airMaxWeight']['Unit']);
			 }else if(isset($result['airBaggageAllowance'][0]['@attributes'])){
				 $m=$result['airBaggageAllowance'][0]['@attributes'];
				 $allownce['MaxWeight']=array('Value' => $m['Value'],'Unit' =>$m['Unit']);
				 
			 }
		 $data['BaggageAllowance']=$allownce;
	 }
	if(isset($result['airBrand'])){
		  $op=array(
		    'Key' => $result['airBrand']['@attributes']['Key'],
			'BrandFound' => isset($result['airBrand']['@attributes']['BrandFound'])?$result['airBrand']['@attributes']['BrandFound']:'',
			'BrandID' => isset($result['airBrand']['@attributes']['BrandID'])?$result['airBrand']['@attributes']['BrandID']:'',
			'UpSellBrandFound' => $result['airBrand']['@attributes']['UpSellBrandFound']
		 );
		$data['Brand']=$op;
	}
	if(isset($result['airFareRuleKey'])){
		 $data['FareRuleKey']=$result['airFareRuleKey'];
	}
	$fairinfolist[]=$data;
}

//echo "<pre>";
//print_r($fairinfolist);

//echo "<br>RouteList";
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airRouteList']['airRoute'];
if(empty($results))$results=array();
$routelist=array();
foreach($results as $k=>$result){
  if($k=='airLeg'){
  $data=array(
    'LegKey' => $result['@attributes']['Key'],
	'Group' => $result['@attributes']['Group'],
	'Origin' => $result['@attributes']['Origin'],
	'Destination' => $result['@attributes']['Destination']
  );
  $routelist[]=$data;
  }
}

//echo "<pre>";
//print_r($routelist);

//echo "<br>AirPricePointList";
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirPricePointList']['airAirPricePoint'];
//echo "<pre>";
//print_r($results);
//die();
if(empty($results))$results=array();
$airpricepointlist=array();
foreach($results as $k=>$result){
 $data=array(
    'Key' => $result['@attributes']['Key'],
	'TotalPrice' => $result['@attributes']['TotalPrice'],
	'BasePrice' => $result['@attributes']['BasePrice'],
	'ApproximateTotalPrice' => $result['@attributes']['ApproximateTotalPrice'],
	'ApproximateBasePrice' => $result['@attributes']['ApproximateBasePrice'],
	'EquivalentBasePrice' => $result['@attributes']['EquivalentBasePrice'],
	'Taxes' => $result['@attributes']['Taxes'],
	'ApproximateTaxes' => $result['@attributes']['ApproximateTaxes'],
	'CompleteItinerary' => $result['@attributes']['CompleteItinerary']
 );
 $pricinginfodata=array();
 if(isset($result['airAirPricingInfo']['@attributes'])){
 $data2=array(
    'Key' => $result['airAirPricingInfo']['@attributes']['Key'],
	'TotalPrice' => $result['airAirPricingInfo']['@attributes']['TotalPrice'],
	'BasePrice' => $result['airAirPricingInfo']['@attributes']['BasePrice'],
	'ApproximateTotalPrice' => $result['airAirPricingInfo']['@attributes']['ApproximateTotalPrice'],
	'ApproximateBasePrice' => $result['airAirPricingInfo']['@attributes']['ApproximateBasePrice'],
	'EquivalentBasePrice' => $result['airAirPricingInfo']['@attributes']['EquivalentBasePrice'],
	'Taxes' => $result['airAirPricingInfo']['@attributes']['Taxes'],
	'LatestTicketingTime' => $result['airAirPricingInfo']['@attributes']['LatestTicketingTime'],
	'PricingMethod' => $result['airAirPricingInfo']['@attributes']['PricingMethod'],
	'ETicketability' => $result['airAirPricingInfo']['@attributes']['ETicketability'],
	'PlatingCarrier' => $result['airAirPricingInfo']['@attributes']['PlatingCarrier'],
	'ProviderCode' => $result['airAirPricingInfo']['@attributes']['ProviderCode']
 );
 
 $data2['FareInfoRef']= $result['airAirPricingInfo']['airFareInfoRef']['@attributes']['Key'];
 $taxdata=array();
 if(isset($result['airAirPricingInfo']['airTaxInfo'])){
 $taxinfo= $result['airAirPricingInfo']['airTaxInfo'];
 foreach($taxinfo as $t){
	 $tdata=array(
	     'Category' => $t['@attributes']['Category'],
         'Amount' => $t['@attributes']['Amount']
	 );
	 $taxdata[]=$tdata;
 }
 }else{
	echo "<br>Error:<pre>"; print_r($result['airAirPricingInfo']); die();
 }
 $data2['TaxInfo']=$taxdata;
 $data2['FareCalc']=$result['airAirPricingInfo']['airFareCalc'];
 $ptypedata=array();
 if(isset($result['airAirPricingInfo']['airPassengerType']['@attributes'])){
 $ptypedata=array(
    'Code' => $result['airAirPricingInfo']['airPassengerType']['@attributes']['Code'],
    'Age' => $result['airAirPricingInfo']['airPassengerType']['@attributes']['Age']
 );
 }else{
	foreach($result['airAirPricingInfo']['airPassengerType'] as $psgtype){
		$psgtypedata[]=array(
			'Code' => $psgtype['@attributes']['Code'],
			'Age' => $psgtype['@attributes']['Age']
		 );
		 $ptypedata[]=$psgtypedata;
	}
 }
 $data2['PassengerType']=$ptypedata;
 $data2['ChangePenalty']=$result['airAirPricingInfo']['airChangePenalty']['airAmount'];
 $FlightOption=$result['airAirPricingInfo']['airFlightOptionsList']['airFlightOption'];
 //echo "<pre>";
// print_r($FlightOption);
// die();
 $foptdata=array(
    'LegRef' => $FlightOption['@attributes']['LegRef'],
	'Destination' => $FlightOption['@attributes']['Destination'],
	'Origin' => $FlightOption['@attributes']['Origin']
 );
    $foptionsdata=array();
    if(isset($FlightOption['airOption']['@attributes'])){
		$foptions=array(
             'Key' => $FlightOption['airOption']['@attributes']['Key'],
             'TravelTime' => $FlightOption['airOption']['@attributes']['TravelTime']
        );
		$airBookingInfo=$FlightOption['airOption']['airBookingInfo'];
		$bookingdata=array();
		foreach($airBookingInfo as $a){
			$bidata=array(
			    'BookingCode' => $a['@attributes']['BookingCode'],
				'CabinClass' => $a['@attributes']['CabinClass'],
				'FareInfoRef' => $a['@attributes']['FareInfoRef'],
				'SegmentRef' => $a['@attributes']['SegmentRef']
			);
			$bookingdata[]=$bidata;
		}
		$foptions['BookingInfo']=$bookingdata;
		if(isset($FlightOption['airOption']['airConnection'])){
			$airConnection=$FlightOption['airOption']['airConnection'];
			$connectiondata=array();
			foreach($airConnection as $c){
				$connectiondata[]=$c['@attributes']['SegmentIndex'];
			}
			$foptions['Connection']=$connectiondata;
		}else{
			$foptions['Connection']='';
		}
	  $foptionsdata[]=$foptions;	
	}else{// multiple options
		foreach($FlightOption['airOption'] as $o){
			$foptions=array(
				 'Key' => $o['@attributes']['Key'],
				 'TravelTime' => $o['@attributes']['TravelTime']
			);
			$bookingdata=array();
			if(isset($o['airBookingInfo'])){
			$airBookingInfo=$o['airBookingInfo'];
			foreach($airBookingInfo as $a){
				$bidata=array(
					'BookingCode' => $a['@attributes']['BookingCode'],
					'CabinClass' => $a['@attributes']['CabinClass'],
					'FareInfoRef' => $a['@attributes']['FareInfoRef'],
					'SegmentRef' => $a['@attributes']['SegmentRef']
				);
				$bookingdata[]=$bidata;
			}
			}else if(isset($o[0])){
			     $bidata=array(
					'BookingCode' => $o[0]['@attributes']['BookingCode'],
					'CabinClass' => $o[0]['@attributes']['CabinClass'],
					'FareInfoRef' => $o[0]['@attributes']['FareInfoRef'],
					'SegmentRef' => $o[0]['@attributes']['SegmentRef']
				);
				$bookingdata[]=$bidata;
			}// if
			$foptions['BookingInfo']=$bookingdata;
			if(isset($o['airConnection'])){
				$airConnection=$o['airConnection'];
				$connectiondata=array();
				foreach($airConnection as $c){
					$connectiondata[]=$c['@attributes']['SegmentIndex'];
				}
				$foptions['Connection']=$connectiondata;
			}else{
				$foptions['Connection']='';
			}
		   $foptionsdata[]=$foptions;			
		}// end $o
    }
 
 $foptdata['Option']=$foptionsdata;
 $data2['FlightOption']=$foptdata;
 $pricinginfodata[]=$data2;
 }else{// multi type passenger
	foreach($result['airAirPricingInfo'] as $ap){ 
	   $data2=array(
			'Key' => $ap['@attributes']['Key'],
			'TotalPrice' => $ap['@attributes']['TotalPrice'],
			'BasePrice' => $ap['@attributes']['BasePrice'],
			'ApproximateTotalPrice' => $ap['@attributes']['ApproximateTotalPrice'],
			'ApproximateBasePrice' => $ap['@attributes']['ApproximateBasePrice'],
			'EquivalentBasePrice' => $ap['@attributes']['EquivalentBasePrice'],
			'Taxes' => $ap['@attributes']['Taxes'],
			'LatestTicketingTime' => $ap['@attributes']['LatestTicketingTime'],
			'PricingMethod' => $ap['@attributes']['PricingMethod'],
			'ETicketability' => $ap['@attributes']['ETicketability'],
			'PlatingCarrier' => $ap['@attributes']['PlatingCarrier'],
			'ProviderCode' => $ap['@attributes']['ProviderCode']
		 );
		 
		 $data2['FareInfoRef']= $ap['airFareInfoRef']['@attributes']['Key'];
		 $taxdata=array();
		 if(isset($ap['airTaxInfo'])){
		 $taxinfo= $ap['airTaxInfo'];
		 foreach($taxinfo as $t){
			 $tdata=array(
				 'Category' => $t['@attributes']['Category'],
				 'Amount' => $t['@attributes']['Amount']
			 );
			 $taxdata[]=$tdata;
		 }
		 }else{
			echo "Error:<pre>"; print_r($ap); die();
		 }
		 $data2['TaxInfo']=$taxdata;
		 $data2['FareCalc']=$ap['airFareCalc'];
		  $ptypedata=array();
		 if(isset($ap['airPassengerType']['@attributes'])){
		 $ptypedata[]=array(
			'Code' => $ap['airPassengerType']['@attributes']['Code'],
			'Age' => $ap['airPassengerType']['@attributes']['Age']
		 );
		 }else{
			foreach($ap['airPassengerType'] as $psgtype){
				$psgtypedata=array(
					'Code' => $psgtype['@attributes']['Code'],
					'Age' => $psgtype['@attributes']['Age']
				 );
				 $ptypedata[]=$psgtypedata;
			}
		 }
		 $data2['PassengerType']=$ptypedata;
		 $data2['PassengerType']=$ptypedata;
		 $data2['ChangePenalty']=$ap['airChangePenalty']['airAmount'];
		 $FlightOption=$ap['airFlightOptionsList']['airFlightOption'];
		 $foptdata=array(
			'LegRef' => $FlightOption['@attributes']['LegRef'],
			'Destination' => $FlightOption['@attributes']['Destination'],
			'Origin' => $FlightOption['@attributes']['Origin']
		 );
			$foptionsdata=array();
			if(isset($FlightOption['airOption']['@attributes'])){
				$foptions=array(
					 'Key' => $FlightOption['airOption']['@attributes']['Key'],
					 'TravelTime' => $FlightOption['airOption']['@attributes']['TravelTime']
				);
				$airBookingInfo=$FlightOption['airOption']['airBookingInfo'];
				$bookingdata=array();
				foreach($airBookingInfo as $a){
					$bidata=array(
						'BookingCode' => $a['@attributes']['BookingCode'],
						'CabinClass' => $a['@attributes']['CabinClass'],
						'FareInfoRef' => $a['@attributes']['FareInfoRef'],
						'SegmentRef' => $a['@attributes']['SegmentRef']
					);
					$bookingdata[]=$bidata;
				}
				$foptions['BookingInfo']=$bookingdata;
				if(isset($FlightOption['airOption']['airConnection'])){
					$airConnection=$FlightOption['airOption']['airConnection'];
					$connectiondata=array();
					foreach($airConnection as $c){
						$connectiondata[]=$c['@attributes']['SegmentIndex'];
					}
					$foptions['Connection']=$connectiondata;
				}else{
					$foptions['Connection']='';
				}
			  $foptionsdata[]=$foptions;	
			}else{// multiple options
				foreach($FlightOption['airOption'] as $o){
					$foptions=array(
						 'Key' => $o['@attributes']['Key'],
						 'TravelTime' => $o['@attributes']['TravelTime']
					);
					$bookingdata=array();
					if(isset($o['airBookingInfo'])){
					$airBookingInfo=$o['airBookingInfo'];
					foreach($airBookingInfo as $a){
						$bidata=array(
							'BookingCode' => $a['@attributes']['BookingCode'],
							'CabinClass' => $a['@attributes']['CabinClass'],
							'FareInfoRef' => $a['@attributes']['FareInfoRef'],
							'SegmentRef' => $a['@attributes']['SegmentRef']
						);
						$bookingdata[]=$bidata;
					}
					}else if(isset($o[0])){
						 $bidata=array(
							'BookingCode' => $o[0]['@attributes']['BookingCode'],
							'CabinClass' => $o[0]['@attributes']['CabinClass'],
							'FareInfoRef' => $o[0]['@attributes']['FareInfoRef'],
							'SegmentRef' => $o[0]['@attributes']['SegmentRef']
						);
						$bookingdata[]=$bidata;
					}// if
					$foptions['BookingInfo']=$bookingdata;
					if(isset($o['airConnection'])){
						$airConnection=$o['airConnection'];
						$connectiondata=array();
						foreach($airConnection as $c){
							$connectiondata[]=$c['@attributes']['SegmentIndex'];
						}
						$foptions['Connection']=$connectiondata;
					}else{
						$foptions['Connection']='';
					}
				   $foptionsdata[]=$foptions;			
				}// end $o
			}
		 
		 $foptdata['Option']=$foptionsdata;
		 $data2['FlightOption']=$foptdata;
		 $pricinginfodata[]=$data2;
	}// $ap
 }
 
 
 
 $data['AirPricingInfo']=$pricinginfodata;
$airpricepointlist[]=$data;
}
$flightsresult=$airpricepointlist;

}else if($searchdata['mode']=='roundtrip'){ //die('stop');
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airFlightDetailsList']['airFlightDetails'];
if(empty($results))$results=array();
$flightdetails=array();
foreach($results as $result){
    $data=array(
	    'Key'=> $result['@attributes']['Key'],
		'Origin'=> $result['@attributes']['Origin'],
		'Destination'=> $result['@attributes']['Destination'],
		'DepartureTime'=> $result['@attributes']['DepartureTime'],
		'ArrivalTime'=> $result['@attributes']['ArrivalTime'],
		'FlightTime'=> $result['@attributes']['FlightTime'],
		'TravelTime'=> $result['@attributes']['TravelTime'],
		'Equipment'=> $result['@attributes']['Equipment'],
		'OriginTerminal'=> $result['@attributes']['OriginTerminal'],
		'DestinationTerminal'=> $result['@attributes']['DestinationTerminal']
	);
	$flightdetails[]=$data;        
}
//echo "<pre>";
//print_r($flightdetails);

//echo "<br>AirSegment";
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirSegmentList']['airAirSegment'];
if(empty($results))$results=array();
$airsegments=array();
foreach($results as $result){
	 $data=array(
	    'Key'=>$result['@attributes']['Key'],
		'Group'=>$result['@attributes']['Group'],
		'Carrier'=>$result['@attributes']['Carrier'],
		'FlightNumber'=>$result['@attributes']['FlightNumber'],
		'Origin'=>$result['@attributes']['Origin'],
		'Destination'=>$result['@attributes']['Destination'],
		'DepartureTime'=>$result['@attributes']['DepartureTime'],
		'ArrivalTime'=>$result['@attributes']['ArrivalTime'],
		'FlightTime'=>$result['@attributes']['FlightTime'],
		'Distance'=>$result['@attributes']['Distance'],
		'ETicketability'=>$result['@attributes']['ETicketability'],
		'Equipment'=>$result['@attributes']['Equipment'],
		'ChangeOfPlane'=>$result['@attributes']['ChangeOfPlane'],
		'ParticipantLevel'=>$result['@attributes']['ParticipantLevel'],
		'LinkAvailability'=>$result['@attributes']['LinkAvailability'],
		'PolledAvailabilityOption'=>$result['@attributes']['PolledAvailabilityOption'],
		'OptionalServicesIndicator'=>$result['@attributes']['OptionalServicesIndicator'],
		'AvailabilitySource'=>$result['@attributes']['AvailabilitySource'],
		'AvailabilityDisplayType'=>$result['@attributes']['AvailabilityDisplayType']
	);
	if(isset($result['airCodeshareInfo'])){
		 $data['airCodeshareInfo'] = $result['airCodeshareInfo'];
	 }else{
		  $data['airCodeshareInfo'] = '';
	 }
	 
	 if(isset($result['airAirAvailInfo'][0]['@attributes']['BookingCounts'])){
		  $data['BookingCounts']=$result['airAirAvailInfo'][0]['@attributes']['BookingCounts'];
	 }
	 if(isset($result['airFlightDetailsRef']['@attributes'])){
		  $data['airFlightDetailsRef']=$result['airFlightDetailsRef']['@attributes']['Key'];
	  }
  $airsegments[]=$data; 
	
}

//echo "<pre>";
//print_r($airsegments);
//die();

//echo "<br>Fairinfo";
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airFareInfoList']['airFareInfo'];
//echo "<pre>";
//print_r($results);
//die();
if(empty($results))$results=array();
$fairinfolist=array();
foreach($results as $result){
// $result=$results[17];
	 $data=array(
	    'Key' => $result['@attributes']['Key'],
		'FareBasis' => $result['@attributes']['FareBasis'],
		'PassengerTypeCode' => $result['@attributes']['PassengerTypeCode'],
		'Origin' => $result['@attributes']['Origin'],
		'Destination' => $result['@attributes']['Destination'],
		'EffectiveDate' => $result['@attributes']['EffectiveDate'],
		'DepartureDate' => $result['@attributes']['DepartureDate'],
		'Amount' => $result['@attributes']['Amount'],
		'NegotiatedFare' => $result['@attributes']['NegotiatedFare'],
		'NotValidBefore' => isset($result['@attributes']['NegotiatedFare'])?$result['@attributes']['NegotiatedFare']:'',
		'NotValidAfter' => isset($result['@attributes']['NotValidAfter'])?$result['@attributes']['NotValidAfter']:'',
	 );
	 if(isset($result['airFareSurcharge'])){
		 $op=array(
		    'Key' => $result['airFareSurcharge']['@attributes']['Key'],
			'Type' => $result['airFareSurcharge']['@attributes']['Type'],
			'Amount' => $result['airFareSurcharge']['@attributes']['Amount']
		 );
		$data['FareSurcharge']=$op;
	 }
	 
	 if(isset($result['airBaggageAllowance']) && is_array($result['airBaggageAllowance'])){
		 $allownce=array();
			 if(isset($result['airBaggageAllowance']['airNumberOfPieces'])){
				 $allownce['NumberOfPieces']=$result['airBaggageAllowance']['airNumberOfPieces'];
			 }
			 if(isset($result['airBaggageAllowance']['airMaxWeight'])){
				 $allownce['MaxWeight']=array('Value' => $result['airBaggageAllowance']['airMaxWeight']['Value'],'Unit' => $result['airBaggageAllowance']['airMaxWeight']['Unit']);
			 }else if(isset($result['airBaggageAllowance'][0]['@attributes'])){
				 $m=$result['airBaggageAllowance'][0]['@attributes'];
				 $allownce['MaxWeight']=array('Value' => $m['Value'],'Unit' =>$m['Unit']);
				 
			 }
		 $data['BaggageAllowance']=$allownce;
	 }
	if(isset($result['airBrand'])){
		  $op=array(
		    'Key' => $result['airBrand']['@attributes']['Key'],
			'BrandFound' => isset($result['airBrand']['@attributes']['BrandFound'])?$result['airBrand']['@attributes']['BrandFound']:'',
			'BrandID' => isset($result['airBrand']['@attributes']['BrandID'])?$result['airBrand']['@attributes']['BrandID']:'',
			'UpSellBrandFound' => $result['airBrand']['@attributes']['UpSellBrandFound']
		 );
		$data['Brand']=$op;
	}
	if(isset($result['airFareRuleKey'])){
		 $data['FareRuleKey']=$result['airFareRuleKey'];
	}
	$fairinfolist[]=$data;
}

//echo "<pre>";
//print_r($fairinfolist);

//echo "<br>RouteList";
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airRouteList']['airRoute'];
if(empty($results))$results=array();
if(isset($results['airLeg'])){
 $results=$results['airLeg'];	
}
$routelist=array();
if(isset($results['@attributes'])){
	  $data=array(
		'LegKey' => $results['@attributes']['Key'],
		'Group' => $results['@attributes']['Group'],
		'Origin' => $results['@attributes']['Origin'],
		'Destination' => $results['@attributes']['Destination']
	  );
	  $routelist[]=$data;
}else{
foreach($results as $k=>$result){
  $data=array(
    'LegKey' => $result['@attributes']['Key'],
	'Group' => $result['@attributes']['Group'],
	'Origin' => $result['@attributes']['Origin'],
	'Destination' => $result['@attributes']['Destination']
  );
  $routelist[]=$data;
}
}

//echo "<pre>";
//print_r($routelist);

//echo "airAirPricePoints";
$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirPricePointList']['airAirPricePoint'];
//echo "<pre>";
//print_r($results);
//die();
if(empty($results))$results=array();
$airpricepointlist=array();
foreach($results as $k=>$result){
 $data=array(
    'Key' => $result['@attributes']['Key'],
	'TotalPrice' => $result['@attributes']['TotalPrice'],
	'BasePrice' => $result['@attributes']['BasePrice'],
	'ApproximateTotalPrice' => $result['@attributes']['ApproximateTotalPrice'],
	'ApproximateBasePrice' => $result['@attributes']['ApproximateBasePrice'],
	'EquivalentBasePrice' => $result['@attributes']['EquivalentBasePrice'],
	'Taxes' => $result['@attributes']['Taxes'],
	'ApproximateTaxes' => $result['@attributes']['ApproximateTaxes'],
	'CompleteItinerary' => $result['@attributes']['CompleteItinerary']
 );
 $airpricinginfo=array();
 if(isset($result['airAirPricingInfo']['@attributes'])){ // single passenger type
 $api=$result['airAirPricingInfo'];
 $data2=array(
    'Key' => $api['@attributes']['Key'],
	'TotalPrice' => $api['@attributes']['TotalPrice'],
	'BasePrice' => $api['@attributes']['BasePrice'],
	'ApproximateTotalPrice' => $api['@attributes']['ApproximateTotalPrice'],
	'ApproximateBasePrice' => $api['@attributes']['ApproximateBasePrice'],
	'EquivalentBasePrice' => $api['@attributes']['EquivalentBasePrice'],
	'Taxes' => $api['@attributes']['Taxes'],
	'LatestTicketingTime' => $api['@attributes']['LatestTicketingTime'],
	'PricingMethod' => $api['@attributes']['PricingMethod'],
	'ETicketability' => $api['@attributes']['ETicketability'],
	'PlatingCarrier' => $api['@attributes']['PlatingCarrier'],
	'ProviderCode' => $api['@attributes']['ProviderCode']
 );
 $FareInfoRef=array();
 if(isset($api['airFareInfoRef']['@attributes']['Key'])){
	 $FareInfoRef[]=$api['airFareInfoRef']['@attributes']['Key'];
 }else if(isset($api['airFareInfoRef'][0])){
	 foreach($api['airFareInfoRef'] as $fir){
		 if(isset($fir['@attributes']['Key'])){
			$FareInfoRef[]=$fir['@attributes']['Key']; 
		 }
	 }
 }
 $data2['FareInfoRef']= $FareInfoRef;
 $airpricinginfo[]=$data2;
 }else if(isset($result['airAirPricingInfo'][0])){ // multi passenger type more than one AirPricingInfo generate
	// foreach($result['airAirPricingInfo'] as $api){
	   $api=$result['airAirPricingInfo'][0]; // we use only the first(adult) only
	    $data2=array(
			'Key' => $api['@attributes']['Key'],
			'TotalPrice' => $api['@attributes']['TotalPrice'],
			'BasePrice' => $api['@attributes']['BasePrice'],
			'ApproximateTotalPrice' => $api['@attributes']['ApproximateTotalPrice'],
			'ApproximateBasePrice' => $api['@attributes']['ApproximateBasePrice'],
			'EquivalentBasePrice' => $api['@attributes']['EquivalentBasePrice'],
			'Taxes' => $api['@attributes']['Taxes'],
			'LatestTicketingTime' => $api['@attributes']['LatestTicketingTime'],
			'PricingMethod' => $api['@attributes']['PricingMethod'],
			'ETicketability' => $api['@attributes']['ETicketability'],
			'PlatingCarrier' => $api['@attributes']['PlatingCarrier'],
			'ProviderCode' => $api['@attributes']['ProviderCode']
		 );
		 
		 $FareInfoRef=array();
		 if(isset($api['airFareInfoRef']['@attributes']['Key'])){
			 $FareInfoRef[]=$api['airFareInfoRef']['@attributes']['Key'];
		 }else if(isset($api['airFareInfoRef'][0])){
			 foreach($api['airFareInfoRef'] as $fir){
				 if(isset($fir['@attributes']['Key'])){
					$FareInfoRef[]=$fir['@attributes']['Key']; 
				 }
			 }
		 }
		 $data2['FareInfoRef']= $FareInfoRef;
		 
		 $taxdata=array();
		 if(isset($api['airTaxInfo'])){
		 $taxinfo= $api['airTaxInfo'];
		 foreach($taxinfo as $t){
			 $tdata=array(
				 'Category' => $t['@attributes']['Category'],
				 'Amount' => $t['@attributes']['Amount']
			 );
			 $taxdata[]=$tdata;
		 }
		 $data2['TaxInfo']=$taxdata;
		 $data2['FareCalc']=$api['airFareCalc'];
		 
		 $ptypedata=array();
		 if(isset($api['airPassengerType']['@attributes'])){
			 $psgtypedata=array(
				'Code' => $api['airPassengerType']['@attributes']['Code'],
				'Age' => $api['airPassengerType']['@attributes']['Age']
			 );
			 $ptypedata[]=$psgtypedata;
		 }else{
			foreach($api['airPassengerType'] as $psgtype){
				$psgtypedata=array(
					'Code' => $psgtype['@attributes']['Code'],
					'Age' => $psgtype['@attributes']['Age']
				 );
				 $ptypedata[]=$psgtypedata;
			}
		 }
		 $data2['PassengerType']=$ptypedata;
		 if(isset($api['airChangePenalty'])){
		 $data2['ChangePenalty']=$api['airChangePenalty']['airAmount'];
		 }
		 if(isset($api['airCancelPenalty'])){
		 $data2['CancelPenalty']=$api['airCancelPenalty']['airAmount'];
		 }
		 
		 $FlightOption=$api['airFlightOptionsList']['airFlightOption'];
		 
		 $flightoption=processFlightOptions($FlightOption);
		 //echo "<pre>";
         //print_r($flightoption);
		 $data2['FlightOption']=$flightoption;
		 $airpricinginfo[]=$data2;
	// }
 }
 
$data['AirPricingInfo']=$airpricinginfo;
$airpricepointlist[]= $data;
}
}
$flightsresult=$airpricepointlist;
}// round trip
//echo "<pre>";
//print_r($flightsresult);
//die();
// https://support.travelport.com/webhelp/uapi/Content/Air/Low_Fare_Shopping/low_fare_shopping_with_flexible_dates.htm
get_header();
?>
<div class="sky-bg">
 <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="flt-dtl-area">
                	<!--Search Result Area Starts-->
                    <div class="srh-result-area">
                    	<form>
                        	<div class="srh-lcl">
                            	<input type="text" name="srh-dpt-location" class="cls-srh-dpt-location" id="id-srh-dpt-location" value="<?php echo $searchdata['from_city'];?> (<?php echo $searchdata['from_airport'];?>)" readonly />
                            </div>
                            <div class="srh-lcl">
                            	<input type="text" name="srh-arvl-location" class="cls-srh-arvl-location" id="id-srh-arvl-location" value="<?php echo $searchdata['to_city'];?> (<?php echo $searchdata['to_airport'];?>)" readonly />
                            </div>
                            <div class="srh-date">
                            	<label>Depart</label>
                            	<input type="text" name="srh-dpt-date" class="cls-srh-dpt-date" id="id-srh-dpt-date" value="<?php echo date("d/m/Y",strtotime($searchdata['start_date']));?>" readonly />
                            </div>
                            <div class="srh-date">
                               <?php if($searchdata['mode']=='oneway'){?>
                            	<div class="in-active">
                                 <label>Return</label>
                                    <input type="text" name="srh-dpt-date" class="cls-srh-dpt-date" id="id-srh-dpt-date" value="<?php echo date("d/m/Y",strtotime($searchdata['end_date']));?>" readonly />
                                </div>
                               <?php }else{?>
                            	<div>
                                 <label>Return</label>
                                    <input type="text" name="srh-dpt-date" class="cls-srh-dpt-date" id="id-srh-dpt-date" value="<?php echo date("d/m/Y",strtotime($searchdata['end_date']));?>" readonly />
                                </div>
                               <?php }?>
                            </div>
                            <div class="srh-psg">
                            	<input type="text" name="srh-passengers" class="cls-srh-psg-dtl" id="id-srh-psg-dtl" value="<?php echo count($searchdata['adult'])?$searchdata['adult'].' Adult':'';?> <?php echo count($searchdata['infant'])?$searchdata['infant'].' Infant':'';?> <?php echo count($searchdata['child'])?$searchdata['child'].' Child':'';?>" readonly />
                                <input type="text" name="srh-travel-class" class="cls-srh-trvl-cls" id="id-srh-trvl-cls" value="<?php echo $searchdata['cabinclass'];?>" readonly />
                             </div>
                        	<div class="srh-btn">
                            	<div class="srh-button"><i class="fa fa-pencil"></i> Edit Search</div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                    <!--Search Result Area Ends-->
                	<!--Edit Search Drop Down Area Starts-->
                	<div class="edit-area">
                    	<form>
                        	<div class="select-trip-option">
                            	<div class="r-trip"><input type="radio" name="edit-way" <?php echo $searchdata['mode']=='roundtrip'?'checked':'';?> /> Round Trip</div>
                                <div class="o-way"><input type="radio" name="edit-way" <?php echo $searchdata['mode']=='oneway'?'checked':'';?> /> One Way</div>
                            </div>
                            <div class="edit-book-dtl">
                            	<div class="edit-lcl">
                                	<div>
                                    	<label>Depart From</label>
                                        <input type="text" name="edit-depart" class="cls-edit-depart" id="id-edit-depart" value="<?php echo $searchdata['from_city'];?> (<?php echo $searchdata['from_airport'];?>)" />
										<i onclick="alert(1)" class="fa fa-times-circle"></i>
                                    </div>
                                </div>
                                <div class="edit-lcl">
                                	<div>
                                    	<label>Destination</label>
                                        <input type="text" name="edit-desti" class="cls-edit-desti" id="id-edit-desti" value="<?php echo $searchdata['to_city'];?> (<?php echo $searchdata['to_airport'];?>)" />
										<i onclick="alert(2)" class="fa fa-times-circle"></i>
                                    </div>
                                </div>
                                <div class="date-pick">
                                	<div>
                                    	<div class="date_picker">
                                        	<label>Depart Date</label>
                                            <input type="text" class="search_arrival" placeholder="Departing" value="<?php echo date("d/m/Y",strtotime($searchdata['start_date']));?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="date-pick">
                                	<div class="in-active">
                                    	<div class="date_picker">
                                        	<label>Arrival Date</label>
                                            <input type="text" class="search_arrival" placeholder="Arrival" value="<?php echo date("d/m/Y",strtotime($searchdata['end_date']));?>" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="edit-book-dtl2">
                            	<div class="edit-psg">
                                	<div>
                                    	<div class="select-option">
                                            <label>Adults: </label>
                                            <select name="adult">
                                                <option value="1" <?php echo $searchdata['adult']==1?'selected="selected"':'';?> >1 Adult</option>
                                                <option value="2" <?php echo $searchdata['adult']==2?'selected="selected"':'';?> >2 Adults</option>
                                                <option value="3" <?php echo $searchdata['adult']==3?'selected="selected"':'';?> >3 Adults</option>
                                                <option value="4" <?php echo $searchdata['adult']==4?'selected="selected"':'';?> >4 Adults</option>
                                                <option value="5" <?php echo $searchdata['adult']==5?'selected="selected"':'';?> >5 Adults</option>
                                                <option value="6" <?php echo $searchdata['adult']==6?'selected="selected"':'';?> >6 Adults</option>
                                                <option value="7" <?php echo $searchdata['adult']==7?'selected="selected"':'';?> >7 Adults</option>
                                                <option value="8" <?php echo $searchdata['adult']==8?'selected="selected"':'';?> >8 Adults</option>
                                                <option value="9" <?php echo $searchdata['adult']==9?'selected="selected"':'';?> >9 Adults</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-psg">
                                	<div>
                                    	<div class="select-option">
                                            <label>Children:</label>
                                            <select name="child">
                                                <option value="0" <?php echo $searchdata['child']==0?'selected="selected"':'';?> >0 Children</option>
                                                <option value="1" <?php echo $searchdata['child']==1?'selected="selected"':'';?> >1 Child</option>
                                                <option value="2" <?php echo $searchdata['child']==2?'selected="selected"':'';?> >2 Children</option>
                                                <option value="3" <?php echo $searchdata['child']==3?'selected="selected"':'';?> >3 Children</option>
                                                <option value="4" <?php echo $searchdata['child']==4?'selected="selected"':'';?> >4 Children</option>
                                                <option value="5" <?php echo $searchdata['child']==5?'selected="selected"':'';?> >5 Children</option>
                                                <option value="6" <?php echo $searchdata['child']==6?'selected="selected"':'';?> >6 Children</option>
                                                <option value="7" <?php echo $searchdata['child']==7?'selected="selected"':'';?> >7 Children</option>
                                                <option value="8" <?php echo $searchdata['child']==8?'selected="selected"':'';?> >8 Children</option>
                                                <option value="9" <?php echo $searchdata['child']==9?'selected="selected"':'';?> >9 Children</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-psg">
                                	<div>
                                    	<div class="select-option">
                                            <label>Infants: </label>
                                            <select name="infant">
                                                <option value="0" <?php echo $searchdata['infant']==0?'selected="selected"':'';?> >0 Infants</option>
                                                <option value="1" <?php echo $searchdata['infant']==1?'selected="selected"':'';?> >1 Infant</option>
                                                <option value="2" <?php echo $searchdata['infant']==2?'selected="selected"':'';?> >2 Infants</option>
                                                <option value="3" <?php echo $searchdata['infant']==3?'selected="selected"':'';?> >3 Infants</option>
                                                <option value="4" <?php echo $searchdata['infant']==4?'selected="selected"':'';?> >4 Infants</option>
                                                <option value="5" <?php echo $searchdata['infant']==5?'selected="selected"':'';?> >5 Infants</option>
                                                <option value="6" <?php echo $searchdata['infant']==6?'selected="selected"':'';?> >6 Infants</option>
                                                <option value="7" <?php echo $searchdata['infant']==7?'selected="selected"':'';?> >7 Infants</option>
                                                <option value="8" <?php echo $searchdata['infant']==8?'selected="selected"':'';?> >8 Infants</option>
                                                <option value="9" <?php echo $searchdata['infant']==9?'selected="selected"':'';?> >9 Infants</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-psg">
                                	<div>
                                    	<div class="select-option">
                                            <label>Class:</label>
                                            <select name="cabinclass">
                                                <option value="Economy" <?php echo $searchdata['cabinclass']=='Economy'?'selected="selected"':'';?> >Economy</option>
                                                <option value="Business" <?php echo $searchdata['cabinclass']=='Business'?'selected="selected"':'';?> >Business</option>
                                                <option value="First" <?php echo $searchdata['cabinclass']=='First'?'selected="selected"':'';?> >First Class</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="edit-psg-btn-area">
                                	<button>Find Flight <i class="fa fa-angle-double-right"></i></button>
                                </div>
                                <div class="clearfix"></div>
								<p>Info for children <a href="#">under 15 years old travelling alone</a> <i class="fa fa-angle-double-right"></i></p>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                    <!--Edit Search Drop Down Area Ends-->
                    <div>
                    	<form></form>
                    </div>
                </div>
            </div>
<?php if($searchdata['mode']=='oneway'){ ?>           
<?php 
$n=1;
foreach($flightsresult as $f){
	
if(!isset($f['AirPricingInfo'][0]['FlightOption']['Option'][0]['BookingInfo']['0'])){
	continue;
}
$origin=$f['AirPricingInfo'][0]['FlightOption']['Origin'];	
$destination=$f['AirPricingInfo'][0]['FlightOption']['Destination']; 
$AirPricingInfo=$f['AirPricingInfo'];

//foreach($AirPricingInfo as $apf){
$apf=$AirPricingInfo[0];

$TotalPrice=$apf['TotalPrice'];
$currency=substr($TotalPrice,0,3);
$TotalPrice=substr($TotalPrice,3);
$FairAmount=$TotalPrice;// / (count($apf['PassengerType']));
$FairAmount=$currency." ".$FairAmount;
//$FareInfoRef=$apf['FareInfoRef'];
//$FareInfokey=getFairInfoKey($FareInfoRef,$fairinfolist);
//$fairinfodtl=$fairinfolist[$FareInfokey];
//$FairAmount=$fairinfodtl['Amount'];

$options=$apf['FlightOption']['Option'];

foreach($options as $fopt){
$TravelTime=$fopt['TravelTime'];
$TravelTime=getTimeInFormat($TravelTime);

//if(!isset($fopt['BookingInfo']['0'])){
//	continue;
//}

$startSegmentRef=$fopt['BookingInfo']['0']['SegmentRef'];
$endSegmentpos=count($fopt['BookingInfo'])-1;
$stopsover=count($fopt['BookingInfo'])-1;
$endSegmentRef=$fopt['BookingInfo'][$endSegmentpos]['SegmentRef'];
$k1=getSegmentKey($startSegmentRef,$airsegments);

if(!array_key_exists($k1, $airsegments)){
 continue;
}

$startsegment=$airsegments[$k1];
$k2=getSegmentKey($endSegmentRef,$airsegments);
$endsegment=$airsegments[$k2];
$totaltime=getTimeDiff(date("Y-m-d H:i:s",strtotime($startsegment['DepartureTime'])),date("Y-m-d H:i:s",strtotime($endsegment['ArrivalTime'])));
$Carrier=$startsegment['Carrier'];
$airlinename=$airlines[$Carrier];

$BookingInfo=$fopt['BookingInfo'];
?>
            <div class="col-sm-12">
            	<div class="show-flight">                    
                	<div class="col-sm-8">
                    	<div class="visl">
                        	<div class="row">
                            	<div class="col-sm-3 col-xs-6 flt-single arw">
                                	<p><?php //echo $n++."<br />"; ?><?php echo $origin;?><br />
                                    <?php echo date("h:i a",strtotime($startsegment['DepartureTime']));?> <br/>
							        <?php echo date("D, d M Y",strtotime($startsegment['DepartureTime']));?></p>
                                </div>
                                <div class="col-sm-3 col-xs-6 flt-single">
                                	<p><?php echo $destination;?><br />
                                    <?php echo date("h:i a",strtotime($endsegment['ArrivalTime']));?> <br/>
							        <?php echo date("D, d M Y",strtotime($endsegment['ArrivalTime']));?></p>
                                </div>
                                <div class="col-sm-3 col-xs-4 flt-single drsn"><?php echo trim($airlinename);?><?php /*?><img src="images/singaporeairlines.jpg" /><?php */?></div>
                                <div class="col-sm-1 col-xs-4 flt-single"><a><?php echo $stopsover?($stopsover>1?$stopsover.' Stops':$stopsover.' Stop'):'Direct';?></a></div>
                                <div class="col-sm-2 col-xs-4 flt-single"><?php echo $totaltime;?></div>
                            </div>
                        </div>
                        <div class="edit-visl">
                        <?php 
						    foreach($BookingInfo as $b){
							$segmentref=$b['SegmentRef'];
							$skey=getSegmentKey($segmentref,$airsegments);
							$segmentdtls=$airsegments[$skey];
							$thisCarrier=$segmentdtls['Carrier'];
                            $thisairlinename=$airlines[$thisCarrier];
							$FlightNumber=$segmentdtls['FlightNumber'];
							$aircraft=$segmentdtls['Equipment'];
							//$aircraft="Airbus A330-300"
							$airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
							if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';
							$totalflighttime=$totalflighttime + $segmentdtls['FlightTime'];
						?>
                         <div class="head"><i class="fa fa-plane"></i> Depart</div>
                            <div class="row flt-dtl">
                            	<div class="col-sm-4 col-xs-6 arw">
                                	<p><?php echo $airportscity[$segmentdtls['Origin']];?><br />
                                    <?php echo $segmentdtls['Origin'];?><br/>
                                    <?php echo $airportsname[$segmentdtls['Origin']];?><br/>
                                    <?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?> <br/>
                                    <?php echo date("D, d M Y",strtotime($segmentdtls['DepartureTime']));?></p>
                                </div>
                                <div class="col-sm-4 col-xs-6">
                                	<p><?php echo $airportscity[$segmentdtls['Destination']];?><br />
                                    <?php echo $segmentdtls['Destination'];?><br/>
                                    <?php echo $airportsname[$segmentdtls['Destination']];?><br/>
                                    <?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?> <br/>
                                    <?php echo date("D, d M Y",strtotime($segmentdtls['ArrivalTime']));?></p>
                                </div>
                                <div class="col-sm-4">
                                	<p><i class="fa fa-clock-o"></i> <?php echo floor($segmentdtls['FlightTime']/60);?>hrs <?php echo ceil($segmentdtls['FlightTime']%60);?>mins </p>
                                </div>
                            </div>
                            <div class="vgn"><p><img src="images/virgin-logo.png" /> <?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $b['CabinClass'];?> <?php echo $b['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p></div>
                            <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> <?php echo floor($segmentdtls['FlightTime']/60);?>hrs <?php echo ceil($segmentdtls['FlightTime']%60);?>mins</div>
                        <?php }?> 
                        </div>
                        <div class="clearfix"></div>
                    </div>
					<div class="col-sm-4 show-flight-prize">
                    	<div class="flt-prz">
                        	<label>Per Adult OneWay</label>
                            <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="<?php echo $FairAmount;?>" readonly />
                            <button>Make Free Booking</button>
                            <button>Book Now</button>
                            <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
<?php }}?>			
<?php }else if($searchdata['mode']=='roundtrip'){?>
<?php $z=1;?>
<?php foreach($flightsresult as $f){?>  
<?php
$outorigin=$f['AirPricingInfo'][0]['FlightOption'][0]['Origin'];
$outleg=$f['AirPricingInfo'][0]['FlightOption'][0]['LegRef'];	
$outdestination=$f['AirPricingInfo'][0]['FlightOption'][0]['Destination']; 

$inorigin=$f['AirPricingInfo'][0]['FlightOption'][1]['Origin'];	
$inleg=$f['AirPricingInfo'][0]['FlightOption'][1]['LegRef'];	
$indestination=$f['AirPricingInfo'][0]['FlightOption'][1]['Destination']; 

$AirPricingInfo=$f['AirPricingInfo'];
$apf=$AirPricingInfo[0];

$TotalPrice=$apf['TotalPrice'];
$currency=substr($TotalPrice,0,3);
$TotalPrice=substr($TotalPrice,3);
$FairAmount=$TotalPrice;
$FairAmount=$currency." ".$FairAmount;

$totaloutoption=count($apf['FlightOption'][0]['Option']);
$totalinoption=count($apf['FlightOption'][1]['Option']);
$outarr=array();
for($i=0; $i<$totaloutoption;$i++){
	$outarr[]=$i;
}
$inarr=array();
for($i=0; $i<$totalinoption;$i++){
	$inarr[]=$i;
}
$flightmap=array();
$n=0;
foreach($outarr as $k=>$out){
	//$combinearr[$k]=array();
	foreach($inarr as $in){
	    $flightmap[$n++]=array($out,$in);
	}
}
foreach($flightmap as $fm){
// out bound
//$fopt=$apf['FlightOption'][0]['Option'][0]; //0
$fopt=$apf['FlightOption'][0]['Option'][$fm[0]]; // out

if(!isset($fopt['BookingInfo']['0']['SegmentRef'])){
	continue;
}

$startSegmentRef=$fopt['BookingInfo']['0']['SegmentRef'];
$endSegmentpos=count($fopt['BookingInfo'])-1;
$stopsover=count($fopt['BookingInfo'])-1;
$endSegmentRef=$fopt['BookingInfo'][$endSegmentpos]['SegmentRef'];
$k1=getSegmentKey($startSegmentRef,$airsegments);
$startsegment=$airsegments[$k1];

$k2=getSegmentKey($endSegmentRef,$airsegments);
$endsegment=$airsegments[$k2];
$totaltime=getTimeDiff(date("Y-m-d H:i:s",strtotime($startsegment['DepartureTime'])),date("Y-m-d H:i:s",strtotime($endsegment['ArrivalTime'])));
$Carrier=$startsegment['Carrier'];
$airlinename=$airlines[$Carrier];

$outTotaltime=$totaltime;
$outCarrier=$Carrier;
$outAirlinename=$airlinename;
$outStopover=$stopsover;
$outStartsegment=$startsegment;
$outEndsegment=$endsegment;

$BookingInfoOut=$fopt['BookingInfo'];

// in bound
//$fopt=$apf['FlightOption'][1]['Option'][0]; //1
$fopt=$apf['FlightOption'][1]['Option'][$fm[1]]; // in

$startSegmentRef2=$fopt['BookingInfo']['0']['SegmentRef'];
$endSegmentpos2=count($fopt['BookingInfo'])-1;
$stopsover2=count($fopt['BookingInfo'])-1;
$endSegmentRef2=$fopt['BookingInfo'][$endSegmentpos2]['SegmentRef'];
$k12=getSegmentKey($startSegmentRef2,$airsegments);
$startsegment2=$airsegments[$k12];

$k22=getSegmentKey($endSegmentRef2,$airsegments);
$endsegment2=$airsegments[$k22];
$totaltime=getTimeDiff(date("Y-m-d H:i:s",strtotime($startsegment2['DepartureTime'])),date("Y-m-d H:i:s",strtotime($endsegment2['ArrivalTime'])));
$Carrier=$startsegment2['Carrier'];
$airlinename=$airlines[$Carrier];

$inTotaltime=$totaltime;
$inCarrier=$Carrier;
$inAirlinename=$airlinename;
$inStopover=$stopsover2;
$inStartsegment=$startsegment2;
$inEndsegment=$endsegment2;

$BookingInfoIn=$fopt['BookingInfo'];
?> 
      <div class="col-sm-12">
        <div class="show-flight">                    
            <div class="col-sm-8">
                <div class="visl">
                    <div class="row brd-btm "><!--Added CSS-->
                        <div class="col-sm-3 col-xs-6 flt arw">
                            <p><?php echo $outorigin?> <?php //echo $outleg;?><?php //echo $z++;?>  <?php //echo $startSegmentRef;?><br />
                            <?php echo date("h:i a",strtotime($outStartsegment['DepartureTime']));?> <?php //echo 'GRP'.$outStartsegment['Group'];?><br/>
							<?php echo date("D, d M Y",strtotime($outStartsegment['DepartureTime']));?></p>
                        </div>
                        <div class="col-sm-3 col-xs-6 flt">
                            <p><?php echo $outdestination?><br />
                            <?php echo date("h:i a",strtotime($outEndsegment['ArrivalTime']));?>  <?php //echo 'GRP'.$outEndsegment['Group'];?><br/>
							<?php echo date("D, d M Y",strtotime($outEndsegment['ArrivalTime']));?></p>
                        </div>
                        <div class="col-sm-3 col-xs-4 flt drsn"><?php echo $outAirlinename;?></div>
                        <div class="col-sm-1 col-xs-4 flt"><a><?php echo $outStopover?($outStopover>1?$outStopover.' Stops':$outStopover.' Stop'):'Direct';?></a></div>
                        <div class="col-sm-2 col-xs-4 flt"><?php echo $outTotaltime;?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3 col-xs-6 flt arw">
                            <p><?php echo $inorigin?> <?php //echo $inleg;?><?php //echo $k12;?><br />
                            <?php echo date("h:i a",strtotime($inStartsegment['DepartureTime']));?>  <?php //echo 'GRP'.$inStartsegment['Group'];?><br/>
							<?php echo date("D, d M Y",strtotime($inStartsegment['DepartureTime']));?></p>
                        </div>
                        <div class="col-sm-3 col-xs-6 flt">
                            <p><?php echo $indestination?><br />
                            <?php echo date("h:i a",strtotime($inEndsegment['ArrivalTime']));?>  <?php //echo 'GRP'.$inEndsegment['Group'];?><br/>
							<?php echo date("D, d M Y",strtotime($inEndsegment['ArrivalTime']));?></p>
                        </div>
                        <div class="col-sm-3 col-xs-4 flt drsn"><?php echo $inAirlinename;?></div>
                        <div class="col-sm-1 col-xs-4 flt"><a><?php echo $inStopover?($inStopover>1?$inStopover.' Stops':$inStopover.' Stop'):'Direct';?></a></div>
                        <div class="col-sm-2 col-xs-4 flt"><?php echo $inTotaltime;?></div>
                    </div>
                </div>
                <div class="edit-visl">
                    <div class="head"><i class="fa fa-plane"></i> Depart</div>
                    <?php 
					$totalflighttime=0;
					foreach($BookingInfoOut as $bout){
					$segmentref=$bout['SegmentRef'];
					$skey=getSegmentKey($segmentref,$airsegments);
					$segmentdtls=$airsegments[$skey];
					$thisCarrier=$segmentdtls['Carrier'];
					$thisairlinename=$airlines[$thisCarrier];
					$FlightNumber=$segmentdtls['FlightNumber'];
					$aircraft=$segmentdtls['Equipment'];
					//$aircraft="Airbus A330-300"
					$airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
					if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';	
					$totalflighttime=$totalflighttime + $segmentdtls['FlightTime'];				
					?>
                    <div class="row flt-dtl">
                        <div class="col-sm-4 col-xs-6 arw">
                           <p><?php echo $airportscity[$segmentdtls['Origin']];?><br />
							<?php echo $segmentdtls['Origin'];?><br/>
                            <?php echo $airportsname[$segmentdtls['Origin']];?><br/>
                            <?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?> <br/>
                            <?php echo date("D, d M Y",strtotime($segmentdtls['DepartureTime']));?></p>
                        </div>
                        <div class="col-sm-4 col-xs-6">
                            <p><?php echo $airportscity[$segmentdtls['Destination']];?><br />
							<?php echo $segmentdtls['Destination'];?><br/>
                            <?php echo $airportsname[$segmentdtls['Destination']];?><br/>
                            <?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?> <br/>
                            <?php echo date("D, d M Y",strtotime($segmentdtls['ArrivalTime']));?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><i class="fa fa-clock-o"></i> <?php echo floor($segmentdtls['FlightTime']/60);?>hrs <?php echo ceil($segmentdtls['FlightTime']%60);?>mins</p>
                        </div>
                    </div>
                    <div class="vgn"><p><img src="images/virgin-logo.png" /> <?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $bout['CabinClass'];?> <?php echo $bout['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p></div>
                    <?php }?>
                    <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> <?php echo floor($totalflighttime/60);?>hrs <?php echo ceil($totalflighttime%60);?>mins</div>
                    
                    <div class="head"><i class="fa fa-plane"></i> Return</div>
                    <?php 
					$totalflighttime=0;
					foreach($BookingInfoIn as $bin){
					$segmentref=$bin['SegmentRef'];
					$skey=getSegmentKey($segmentref,$airsegments);
					$segmentdtls=$airsegments[$skey];
					$thisCarrier=$segmentdtls['Carrier'];
					$thisairlinename=$airlines[$thisCarrier];
					$FlightNumber=$segmentdtls['FlightNumber'];
					$aircraft=$segmentdtls['Equipment'];
					//$aircraft="Airbus A330-300"
					$airCodeshareInfo=$segmentdtls['airCodeshareInfo'];
					if($airCodeshareInfo!='')$airCodeshareInfo=' - Operated by '.$airCodeshareInfo.' - ';	
					$totalflighttime=$totalflighttime + $segmentdtls['FlightTime'];			
					?>
                    <div class="row flt-dtl">
                        <div class="col-sm-4 col-xs-6 arw">
                            <p><?php echo $airportscity[$segmentdtls['Origin']];?><br />
							<?php echo $segmentdtls['Origin'];?><br/>
                            <?php echo $airportsname[$segmentdtls['Origin']];?><br/>
                            <?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?> <br/>
                            <?php echo date("D, d M Y",strtotime($segmentdtls['DepartureTime']));?></p>
                        </div>
                        <div class="col-sm-4 col-xs-6">
                           <p><?php echo $airportscity[$segmentdtls['Destination']];?><br />
							<?php echo $segmentdtls['Destination'];?><br/>
                            <?php echo $airportsname[$segmentdtls['Destination']];?><br/>
                            <?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?> <br/>
                            <?php echo date("D, d M Y",strtotime($segmentdtls['ArrivalTime']));?></p>
                        </div>
                        <div class="col-sm-4">
                            <p><i class="fa fa-clock-o"></i> <?php echo floor($segmentdtls['FlightTime']/60);?>hrs <?php echo ceil($segmentdtls['FlightTime']%60);?>mins</p>
                        </div>
                    </div>
                    <div class="vgn"><p><img src="images/virgin-logo.png" /> <?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $bin['CabinClass'];?> <?php echo $bin['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p></div>
                    <?php }?>
                    <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> <?php echo floor($totalflighttime/60);?>hrs <?php echo ceil($totalflighttime%60);?>mins</div>
                    
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="col-sm-4 show-flight-prize">
                <div class="flt-prz">
                    <label>Per Adult Return</label>
                    <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="<?php echo $FairAmount;?>" readonly />
                    <button>Make Free Booking</button>
                    <button>Book Now</button>
                    <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
 <?php } }?>  
 <?php }// roundtrip ?>        
            
        </div>
    </div>
</div>
<script type="application/javascript">
jQuery(document).ready(function(){
	jQuery(".srh-button").click(function(){
		jQuery(".edit-area").slideToggle('slow');
	});
	jQuery(".shw").on("click", function() {
		jQuery(this).parent().parent().parent().prev().children(".edit-visl").slideToggle('slow');
		if ( jQuery.trim(jQuery(this).text().toString()) == ("Show Flight Details").toString() ) {
			jQuery(this).html('<span><i class="fa fa-minus"></i> Hide Flight Details</span>');
		} else {
			jQuery(this).html('<span><i class="fa fa-plus"></i> Show Flight Details</span>');
		}
	});
});
</script>
<?php
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
function getTimeInFormat($s){
$ret=$s;
$a=explode("T",$s);	
$ret=$a[1];
$b=explode("H",$ret);	
$h=$b[0];
$c=explode("M",$b[1]);	
$m=$c[0];

$ret='';
if($h)$ret.=$h;
if($h==1)$ret.= "hr ";
else if($h>1)$ret.= "hrs ";
if($m)$ret.=$m;
if($m==1)$ret.= "min";
else if($m>1)$ret.= "mins";
return $ret;
}
function getDaydiff($d1,$d2){
$date1 = new DateTime($d1);
$date2 = new DateTime($d2);	
$interval = $date1->diff($date2);
$d=$interval->days;
return $d;
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

function processFlightOptions($FlightOption){
$flightoption=array();	
if(isset($FlightOption['@attributes'])){ // for oneway
$flightoption=$FlightOption;

}else{
foreach($FlightOption as $fo){
//$fo=$FlightOption[0];
$fodata=array(
	'LegRef' => $fo['@attributes']['LegRef'],
	'Destination' => $fo['@attributes']['Destination'],
	'Origin' => $fo['@attributes']['Origin']
 );
 // option
$optionarr=array();
$Option=$fo['airOption'];
if(isset($Option['@attributes']['Key'])){ // single
$optdata=array(
	'Key' => $Option['@attributes']['Key'],
	'TravelTime' => $Option['@attributes']['TravelTime']
 );	
 $bookinginfodata=array();
 $BookingInfo=$Option['airBookingInfo'];
 $bookinginfodata=processBookingInfo($BookingInfo);
 $optdata['BookingInfo']=$bookinginfodata;
// connection
$connectiondata=array();
$Connection=$Option['airConnection'];
$connectiondata=processConnection($Connection);
$optdata['Connection']=$connectiondata;

 $optionarr[]=$optdata;
}else if(isset($Option[0])){
foreach($Option as $opt){
//$opt=$Option[0];
$optdata=array(
	'Key' => $opt['@attributes']['Key'],
	'TravelTime' => $opt['@attributes']['TravelTime']
 );
$bookinginfodata=array();
$BookingInfo=$opt['airBookingInfo'];
$bookinginfodata=processBookingInfo($BookingInfo);
$optdata['BookingInfo']=$bookinginfodata;
// connection
$connectiondata=array();
$Connection=$opt['airConnection'];
$connectiondata=processConnection($Connection);
$optdata['Connection']=$connectiondata;

$optionarr[]=$optdata;
}// foreach $opt
}//$Option[0]
$fodata['Option']=$optionarr;
$flightoption[]=$fodata;
}// foreach $fo
}
/*if(isset($FlightOption['@attributes'])){ // for oneway
	$fo=$FlightOption;
	$fodata=array(
			'LegRef' => $fo['@attributes']['LegRef'],
			'Destination' => $fo['@attributes']['Destination'],
			'Origin' => $fo['@attributes']['Origin']
		 );
	// option	 
	$optionarr=array();
	$fodata['Option']=$optionarr; 
	$flightoption[]=$fodata;
 }else if(isset($FlightOption[0])){ // for roundtrip, 1 for grp 0 , 2 for grp1
	 foreach($FlightOption as $fo){
		 $fodata=array(
			'LegRef' => $fo['@attributes']['LegRef'],
			'Destination' => $fo['@attributes']['Destination'],
			'Origin' => $fo['@attributes']['Origin']
		 );
		 
		 // option
		 $Option=$fo['airOption'];
		 $optionarr=array();
		 if(isset($Option['@attributes'])){ // single option
			 $odata=array(
				'Key' => $Option['@attributes']['Key'],
				'TravelTime' => $Option['@attributes']['TravelTime']
			);
			// Booking
			$bookingdata=array();
			if(isset($o['airBookingInfo'])){
			$airBookingInfo=$o['airBookingInfo'];
			if(isset($airBookingInfo['@attributes'])){
				$bidata=array(
					'BookingCode' => $airBookingInfo['@attributes']['BookingCode'],
					'CabinClass' => $airBookingInfo['@attributes']['CabinClass'],
					'FareInfoRef' => $airBookingInfo['@attributes']['FareInfoRef'],
					'SegmentRef' => $airBookingInfo['@attributes']['SegmentRef']
				);
				$bookingdata[]=$bidata;
				
			}else if(isset($airBookingInfo[0])){
			foreach($airBookingInfo as $a){
				$bidata=array(
					'BookingCode' => $a['@attributes']['BookingCode'],
					'CabinClass' => $a['@attributes']['CabinClass'],
					'FareInfoRef' => $a['@attributes']['FareInfoRef'],
					'SegmentRef' => $a['@attributes']['SegmentRef']
				);
				$bookingdata[]=$bidata;
			}// foreach
			}// if
			}else if(isset($o[0])){
				 $bidata=array(
					'BookingCode' => $o[0]['@attributes']['BookingCode'],
					'CabinClass' => $o[0]['@attributes']['CabinClass'],
					'FareInfoRef' => $o[0]['@attributes']['FareInfoRef'],
					'SegmentRef' => $o[0]['@attributes']['SegmentRef']
				);
				$bookingdata[]=$bidata;
			}// if
			$odata['BookingInfo']=$bookingdata;
			
			// connection
				$connectiondata=array();
				if(isset($o['airConnection'])){
					$airConnection=$o['airConnection'];
					if(isset($airConnection['@attributes'])){
						$connectiondata[]=$airConnection['@attributes']['SegmentIndex'];
					//}else if(isset($airConnection[0])){
					}else{
					foreach($airConnection as $c){
						$connectiondata[]=$c['@attributes']['SegmentIndex'];
					}
					}//if
				}
				$odata['Connection']=$connectiondata;
			
			$optionarr[]=$odata;
		 }else if(isset($Option[0])){ // multiple options
			 foreach($Option as $o){
				 $odata=array(
					'Key' => $o['@attributes']['Key'],
					'TravelTime' => $o['@attributes']['TravelTime']
				);
				// Booking
				$bookingdata=array();
				if(isset($o['airBookingInfo'])){
				$airBookingInfo=$o['airBookingInfo'];
				if(isset($airBookingInfo['@attributes'])){
					$bidata=array(
						'BookingCode' => $airBookingInfo['@attributes']['BookingCode'],
						'CabinClass' => $airBookingInfo['@attributes']['CabinClass'],
						'FareInfoRef' => $airBookingInfo['@attributes']['FareInfoRef'],
						'SegmentRef' => $airBookingInfo['@attributes']['SegmentRef']
					);
					$bookingdata[]=$bidata;
					
				}else if(isset($airBookingInfo[0])){
				foreach($airBookingInfo as $a){
					$bidata=array(
						'BookingCode' => $a['@attributes']['BookingCode'],
						'CabinClass' => $a['@attributes']['CabinClass'],
						'FareInfoRef' => $a['@attributes']['FareInfoRef'],
						'SegmentRef' => $a['@attributes']['SegmentRef']
					);
					$bookingdata[]=$bidata;
				}// foreach
				}// if
				}else if(isset($o[0])){
					 $bidata=array(
						'BookingCode' => $o[0]['@attributes']['BookingCode'],
						'CabinClass' => $o[0]['@attributes']['CabinClass'],
						'FareInfoRef' => $o[0]['@attributes']['FareInfoRef'],
						'SegmentRef' => $o[0]['@attributes']['SegmentRef']
					);
					$bookingdata[]=$bidata;
				}// if
				$odata['BookingInfo']=$bookingdata;
				
				// connection
				$connectiondata=array();
				if(isset($o['airConnection'])){
					$airConnection=$o['airConnection'];
					if(isset($airConnection['@attributes'])){
						$connectiondata[]=$airConnection['@attributes']['SegmentIndex'];
					//}else if(isset($airConnection[0])){
					}else{
					foreach($airConnection as $c){
						$connectiondata[]=$c['@attributes']['SegmentIndex'];
					}
					}//if
				}
				$odata['Connection']=$connectiondata;
				
				
				$optionarr[]=$odata;
			 }
		 }
		 $fodata['Option']=$optionarr;
		 
		 $flightoption[]=$fodata;
	 }
 }//if*/






return $flightoption;	
}
function processBookingInfo($BookingInfo){
$bookinginfodata=array();
if(isset($BookingInfo['@attributes']['BookingCode'])){
$bookinginfodataraw=array(
    'BookingCode' => $BookingInfo['@attributes']['BookingCode'],
	'CabinClass' => $BookingInfo['@attributes']['CabinClass'],
	'FareInfoRef' => $BookingInfo['@attributes']['FareInfoRef'],
	'SegmentRef' => $BookingInfo['@attributes']['SegmentRef']
);	
$bookinginfodata[]=$bookinginfodataraw;
}else if(isset($BookingInfo[0])){
	foreach($BookingInfo as $binfo){
	    $bookinginfodataraw=array(
			'BookingCode' => $binfo['@attributes']['BookingCode'],
			'CabinClass' => $binfo['@attributes']['CabinClass'],
			'FareInfoRef' => $binfo['@attributes']['FareInfoRef'],
			'SegmentRef' => $binfo['@attributes']['SegmentRef']
		);	
		$bookinginfodata[]=$bookinginfodataraw;
	}
}
return $bookinginfodata;	
}
function processConnection($Connection){
$connectiondata=array();
if(isset($Connection['@attributes']['SegmentIndex'])){
$data=array(
    'SegmentIndex' => $Connection['@attributes']['SegmentIndex']
);	
$connectiondata[]=$data;
}else if(isset($Connection[0])){
	foreach($Connection as $c){
	    $data=array(
			'SegmentIndex' => $c['@attributes']['SegmentIndex']
		);	
		$connectiondata[]=$data;
	}
}

return $connectiondata;
}
get_footer();
