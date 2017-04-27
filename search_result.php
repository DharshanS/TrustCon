<?php


set_time_limit(0);
date_default_timezone_set('Asia/Colombo');
# SET END POINT
//$endpoint='https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService'; // Production end point
//$endpoint='https://twsprofiler.travelport.com/Service/Default.ashx/AirService'; // Profiler end point

$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService'; // LIVE end point

$GENERATELOG=1; // 1 OR 0


require_once("library/cache.php");
require_once("travelportsettings.php");
require_once("tconnect.php");

$logpath="C:\\xampp\\htdocs\\travel\\my-errors.log";
$perpageList = 10;
$jsondata=array();
$carriers=array();
$prices=array();
$dt=array();

if(isset($_POST['dt'])){
	$dt=unserialize (base64_decode($_POST['dt']));
}
if(is_array($dt)){
	foreach($dt as $k=>$v){
		$_POST[$k]=$v;
	}
}
//require_once("wp-config.php");
//$alink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$alink = mysqli_connect('localhost', 'root','', 'v1');

//$alink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$countries=array();
 
if(isset($_SESSION['countries'])){
  $countries=$_SESSION['countries'];	

}else{

error_log("inside else", 3,$logpath );

$SQL="SELECT * FROM `countries` WHERE `code`!='' ORDER BY `name`";

$rs = mysqli_query($alink, $SQL) or die($SQL);

while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
	error_log(print_r($row,true), 3,$logpath );
 $countries[]=$row;
}
$_SESSION['countries']=$countries;

}
$airlines=array();
if(isset($_SESSION['airlines'])){
  $airlines=$_SESSION['airlines'];	
}else{
$SQL="SELECT `iata`,`airline` FROM `airlines` WHERE `iata`!='' ORDER BY `airline`";
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

$flightsresult=array();

$cache=new Cache;

/*if(isset($_SESSION['baseresponse']) && is_array($_SESSION['baseresponse']) && 1==2){
//if(isset($_SESSION['baseresponse']) && is_array($_SESSION['baseresponse'])){ // test mode
	$responseArray=$_SESSION['baseresponse'];
	$searchdata=$_SESSION['searchdata'];
}else */

if(isset($_POST['mode']) && ($_POST['mode']=='roundtrip' || $_POST['mode']=='oneway')){
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

$iata=isset($_POST['iata'])?$_POST['iata']:'';


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
   //$startdtarr=explode(",",$startdt);
	//$startdt=$startdtarr[0];
	//$startdt=date("Y-m-d",strtotime($startdt));
}

if(isset($_POST['return_date'])){
    $enddt=$_POST['return_date'];
	//$enddtarr=explode(",",$enddt);
	//$enddt=$enddtarr[0];
	//$enddt=date("Y-m-d",strtotime($enddt));
}

$searchdata['start_date']=$startdt;
$searchdata['end_date']=$enddt;

$passengers=array();
for($i=0;$i<$adult;$i++){
	$passengers[]='ADT';
}

for($i=0;$i<$child;$i++){
	$passengers[]='CNN';
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

$searchdata['iata']=$iata;

$_SESSION['searchdata']=$searchdata;

// generate cache key
$cachekey=getCacheKey($searchdata);
// set cache expire time
$cache->expire=getExpiretime($searchdata['start_date']);


$responsedata=$cache->get($cachekey);
if(isset($responsedata) && $responsedata!=''){
  $responseArray = $responsedata;	// cached data
}else{ // new search
	
if($searchdata['mode']=='oneway'){
  echo("oneway");
$message = '';
$message = '
	<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
	   <soapenv:Header/>
	   <soapenv:Body>
		<LowFareSearchReq xmlns="http://www.travelport.com/schema/air_v32_0" AuthorizedBy="User" TraceId="trace" TargetBranch="'.$TARGETBRANCH.'" >
			  <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v32_0" OriginApplication="UAPI" />
			  <SearchAirLeg>
				<SearchOrigin>
				  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['from_airport'].'"  />
				</SearchOrigin>
				<SearchDestination>
				  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['to_airport'].'"  />
				</SearchDestination>
				<SearchDepTime PreferredTime="'.$searchdata['start_date'].'" />
				<AirLegModifiers>
				  <PreferredCabins>
					<CabinClass Type="'.$searchdata['cabinclass'].'"  xmlns="http://www.travelport.com/schema/common_v32_0"/>
				  </PreferredCabins>
				</AirLegModifiers>
			  </SearchAirLeg>
			  <AirSearchModifiers >
				<PreferredProviders>
				  <Provider xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$Provider.'" />
				</PreferredProviders>
			  </AirSearchModifiers>';
				foreach($searchdata['passengers'] as $k=>$psgcode){
				 
				if($psgcode == 'ADT')	 
				{
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'"/>';
				}else if($psgcode == 'CNN'){
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" Age="8" />';
				}else if($psgcode == 'INF'){
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" PricePTCOnly="true" Age="1" />';
				} // endif
				
				} // endfor
	$message .='<AirPricingModifiers FaresIndicator="PublicAndPrivateFares" ETicketability="Yes" />
	</LowFareSearchReq>
	   </soapenv:Body>
	</soapenv:Envelope>
	';
//error_log($message, 3, "C:\\xampp\\htdocs\\travel\\my-errors.log");
}else if($searchdata['mode']=='roundtrip'){

$message = '';
$message = '
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Header/>
	<soapenv:Body>
		<LowFareSearchReq xmlns="http://www.travelport.com/schema/air_v32_0" AuthorizedBy="User" TraceId="trace" TargetBranch="'.$TARGETBRANCH.'" >
		  <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v32_0" OriginApplication="UAPI" />
		  <SearchAirLeg>
			<SearchOrigin>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['from_airport'].'"  />
			</SearchOrigin>
			<SearchDestination>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['to_airport'].'"  />
			</SearchDestination>
			<SearchDepTime PreferredTime="'.$searchdata['start_date'].'" />
			<AirLegModifiers>
			  <PreferredCabins>
				<CabinClass Type="'.$searchdata['cabinclass'].'"  xmlns="http://www.travelport.com/schema/common_v32_0"/>
			  </PreferredCabins>
			</AirLegModifiers>
		  </SearchAirLeg>
		  <SearchAirLeg>
			<SearchOrigin>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['to_airport'].'"  />
			</SearchOrigin>
			<SearchDestination>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['from_airport'].'"  />
			</SearchDestination>
			<SearchDepTime PreferredTime="'.$searchdata['end_date'].'" />
			<AirLegModifiers>
			<PreferredCabins>
				<CabinClass Type="'.$searchdata['cabinclass'].'"  xmlns="http://www.travelport.com/schema/common_v32_0"/>
			  </PreferredCabins>
			</AirLegModifiers>
		  </SearchAirLeg>
		  <AirSearchModifiers>
			<PreferredProviders>
			  <Provider xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$Provider.'" />
			</PreferredProviders>
		  </AirSearchModifiers>';
				foreach($searchdata['passengers'] as $k=>$psgcode){
				 
				if($psgcode == 'ADT')	 
				{
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'"/>';
				}else if($psgcode == 'CNN'){
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" Age="8" />';
				}else if($psgcode == 'INF'){
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" PricePTCOnly="true" Age="1" />';
				} // endif
				
				} // endfor
$message .= '<AirPricingModifiers FaresIndicator="PublicAndPrivateFares" ETicketability="Yes" />
             </LowFareSearchReq>
	 </soapenv:Body>
	</soapenv:Envelope>';	

}
	$gzdata = gzencode($message);
	$auth = base64_encode("$CREDENTIALS");
	
	
    
	$soap_do = curl_init ($endpoint); // defined at top
	
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
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
	curl_setopt($soap_do, CURLOPT_POST, true ); 
	curl_setopt($soap_do, CURLOPT_POSTFIELDS, $gzdata); 
	curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
	curl_setopt($soap_do, CURLOPT_ENCODING, 'gzip');
	curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); 
	$resp = curl_exec($soap_do);
	curl_close ($soap_do);
	

	
	
	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
	$xml = simplexml_load_string($xml);
	$json = json_encode($xml);
	$responseArray = json_decode($json,true);

	//$_SESSION['baseresponse']=$responseArray;
    
	// generate cache
	$cache->set($cachekey, $responseArray);

	if($GENERATELOG){ // generate log file
		$file = 'LowFareSearchReq.xml';
		$filedata = $message;
		file_put_contents($file, $filedata);
		
		$file1 = 'LowFareSearchRsp.xml';
		$filedata1 = $resp;
		file_put_contents($file1, $filedata1);
	}
}// new search

}else if(isset($_POST['mode']) && ($_POST['mode']=='multicity')){

$searchdata=array();

$searchdata['mode']=$_POST['mode'];



$from_city=array();

$from_city_full=array();

$from_country=array();

$to_city=array();

$to_city_full=array();

$to_country=array();

$depart_date=array();



foreach($_POST['from_city'] as $city){

 $fromcityarr=explode(",",$city);

 $city=$fromcityarr[0];

 $from_city[]=$city;

 if(isset($fromcityarr[1])){

	 $from_city_full[]=$fromcityarr[1];

 }else{

	 $from_city_full[]=''; 

 }

}



foreach($_POST['to_city'] as $city){

 $fromcityarr=explode(",",$city);

 $city=$fromcityarr[0];

 $to_city[]=$city;

 if(isset($fromcityarr[1])){

	 $to_city_full[]=$fromcityarr[1];

 }else{

	 $to_city_full[]=''; 

 }

}



foreach($_POST['depart_date'] as $startdt){

 $startdtarr=explode(",",$startdt);

 $startdt=$startdtarr[0];

 $depart_date[]=date("Y-m-d",strtotime($startdt));

}



$adult=isset($_POST['adult'])?$_POST['adult']:1;

$child=isset($_POST['child'])?$_POST['child']:0;

$infant=isset($_POST['infant'])?$_POST['infant']:0;

$cabinclass=isset($_POST['cabinclass'])?$_POST['cabinclass']:'Economy';



$searchdata['from_airport']=$from_city;

$searchdata['from_city']=$from_city_full;



$searchdata['to_airport']=$to_city;

$searchdata['to_city']=$to_city_full;

$searchdata['start_date']=$depart_date;

$passengers=array();

for($i=0;$i<$adult;$i++){

	$passengers[]='ADT';

}

for($i=0;$i<$child;$i++){

	$passengers[]='CNN';

}

for($i=0;$i<$infant;$i++){

	$passengers[]='INF';

}

$searchdata['passengers']=$passengers;
$searchdata['cabinclass']=$cabinclass;
$searchdata['adult']=$adult;
$searchdata['child']=$child;
$searchdata['infant']=$infant;

$searchdata['iata']=$iata;

$_SESSION['searchdata']=$searchdata;

// generate cache key
$cachekey=getCacheKey($searchdata);
// set cache expire time
$cache->expire=getExpiretime($searchdata['start_date']);

$responsedata=$cache->get($cachekey);
if(isset($responsedata) && $responsedata!=''){
  $responseArray = $responsedata;	// cached data
}else{ // new search

$soap = '';
$soap = '

<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">

  <soapenv:Header/>

	<soapenv:Body>

	<LowFareSearchReq  TargetBranch="'.$TARGETBRANCH.'" xmlns="http://www.travelport.com/schema/air_v32_0" SolutionResult="true" AuthorizedBy="User" TraceId="trace" >

	  <BillingPointOfSaleInfo OriginApplication="UAPI" xmlns="http://www.travelport.com/schema/common_v32_0" />';

foreach($searchdata['from_airport'] as $k=>$airport){

$soap .='<SearchAirLeg>

		<SearchOrigin>

		  <CityOrAirport Code="'.$airport.'" xmlns="http://www.travelport.com/schema/common_v32_0" ></CityOrAirport>

		</SearchOrigin>

		<SearchDestination>

		  <CityOrAirport Code="'.$searchdata['to_airport'][$k].'" xmlns="http://www.travelport.com/schema/common_v32_0"></CityOrAirport>

		</SearchDestination>

		<SearchDepTime PreferredTime="'.$searchdata['start_date'][$k].'T00:00:00">

		</SearchDepTime>

		<AirLegModifiers>

		</AirLegModifiers>

	  </SearchAirLeg>';

}

$soap .='

	  <AirSearchModifiers >

		<PreferredProviders>

		  <Provider Code="'.$Provider.'" xmlns="http://www.travelport.com/schema/common_v32_0" />

		</PreferredProviders>

	  </AirSearchModifiers>';

				foreach($searchdata['passengers'] as $k=>$psgcode){
				 
				if($psgcode == 'ADT')	 
				{
				$soap .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'"/>';
				}else if($psgcode == 'CNN'){
				$soap .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" Age="8" />';
				}else if($psgcode == 'INF'){
				$soap .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" PricePTCOnly="true" Age="1" />';
				} // endif
				
				} // endfor

	$soap .='<AirPricingModifiers FaresIndicator="PublicAndPrivateFares" ETicketability="Yes" />
	        </LowFareSearchReq>

</soapenv:Body>

</soapenv:Envelope>';	

	$gzdata = gzencode($soap);
    $auth = base64_encode("$CREDENTIALS"); 
	
	
	$soap_do = curl_init ($endpoint);  // defined at top
	
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
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
	curl_setopt($soap_do, CURLOPT_POST, true ); 
	curl_setopt($soap_do, CURLOPT_POSTFIELDS, $gzdata); 
	curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
	curl_setopt($soap_do, CURLOPT_ENCODING, 'gzip');
	curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); // this will prevent the curl_exec to return result and will let us to capture output
	$resp = curl_exec($soap_do);
	
	curl_close ($soap_do);
	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
	$xml = simplexml_load_string($xml);
	

if($GENERATELOG){ // generate log file
	$file = 'LowFareSearchReq.xml';
	$filedata = $soap;
	file_put_contents($file, $filedata);
	
	$file1 = 'LowFareSearchRsp.xml';
	$filedata1 = $resp;
	file_put_contents($file1, $filedata1);
}
	
	$json = json_encode($xml);
	$responseArray = json_decode($json,true);

	//$_SESSION['baseresponse']=$responseArray;
	
	
	// generate cache
	$cache->set($cachekey, $responseArray);
	
} // new search

}// multicity schema

// lets process
if(isset($responseArray['SOAPBody']['SOAPFault']['faultstring'])){
	die($responseArray['SOAPBody']['SOAPFault']['faultstring']);
}

if($searchdata['mode']=='oneway'){  //die('oneway');

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

$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airFareInfoList']['airFareInfo'];

if(empty($results))$results=array();

$fairinfolist=array();

foreach($results as $result){

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


$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirPricePointList']['airAirPricePoint'];



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

	//echo "<br>Error:<pre>"; print_r($result['airAirPricingInfo']); die();

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
			//foreach($airConnection as $k=>$v){

				//print_r($c);
				$connectiondata=array(
				
				//$connectiondata[]=$c['@attributes']['SegmentIndex'];
				'SegmentIndex' => $c['SegmentIndex']
				
				);
				//$connectiondata[]=$v;

			}

			$foptions['Connection']=$connectiondata;

			

		}else{

			$foptions['Connection']=$connectiondata=array(
							
							'SegmentIndex' => NULL
							
							);

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
				//foreach($airConnection as $k=>$v){

				$connectiondata=array(
				
				//$connectiondata[]=$c['@attributes']['SegmentIndex'];
				'SegmentIndex' => $c['SegmentIndex']
				
				);
					//$connectiondata[] = $v;

				}

				$foptions['Connection']=$connectiondata;

			}else{

				$foptions['Connection']=$connectiondata=array(
							
							'SegmentIndex' => NULL
							
							);

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

			//echo "Error:<pre>"; print_r($ap); die();

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
					//foreach($airConnection as $k=>$v){

						$connectiondata=array(
						
						//$connectiondata[]=$c['@attributes']['SegmentIndex'];
						'SegmentIndex' => $c['SegmentIndex']
						
						);
						//$connectiondata[] = $v;

					}

					$foptions['Connection']=$connectiondata;

				}else{

					$foptions['Connection']=$connectiondata=array(
							
							'SegmentIndex' => NULL
							
							);

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
						//foreach($airConnection as $k=>$v){

							$connectiondata=array(
							
							//$connectiondata[]=$c['@attributes']['SegmentIndex'];
							'SegmentIndex' => $c['SegmentIndex']
							
							);
							//$connectiondata[] = $v;

						}

						$foptions['Connection']=$connectiondata;

					}else{

						$foptions['Connection']=$connectiondata=array(
							
							'SegmentIndex' => NULL
							
							);

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


$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airFareInfoList']['airFareInfo'];


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



$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirPricePointList']['airAirPricePoint'];



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

			 /*echo "<pre>";

			 print_r($flightoption); die('rr');*/

			 $data2['FlightOption']=$flightoption;

			 // ---------------------------------

			 $airpricinginfo[]=$data2;

 }else if(isset($result['airAirPricingInfo'][0])){ // multi passenger type more than one AirPricingInfo generate

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

		 /*echo "<pre>";

		 print_r($flightoption); die('rr');*/

		 $data2['FlightOption']=$flightoption;

		 $airpricinginfo[]=$data2;

	 }

 

$data['AirPricingInfo']=$airpricinginfo;

$airpricepointlist[]= $data;

}// foreach 

$flightsresult=$airpricepointlist;


}else if($searchdata['mode']=='multicity'){ //die('multi');

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

$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airFareInfoList']['airFareInfo'];

if(empty($results))$results=array();

$fairinfolist=array();

if(isset($results['@attributes']['Key'])){

	$result=$results;

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

	

}else if(isset($results[0]['@attributes']['Key'])){

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

}// foreach


$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airRouteList']['airRoute'];

if(empty($results))$results=array();

$routelist=array();

foreach($results as $k=>$result){

 // if($k=='airLeg'){

  if(isset($result[0]['@attributes']['Key'])){

  $data=array(

    'LegKey' => $result[0]['@attributes']['Key'],

	'Group' => $result[0]['@attributes']['Group'],

	'Origin' => $result[0]['@attributes']['Origin'],

	'Destination' => $result[0]['@attributes']['Destination']

  );

  $routelist[]=$data;

  }

}




//echo "<br>AirPricingSolution";

$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirPricingSolution'];
## Rajib Added
//$results=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirPricePointList']['airAirPricePoint'];


if(empty($results))$results=array();

$airpricingsolution=array();

foreach($results as $result){


	$data=array(

	    'Key' => $result['@attributes']['Key'],

		'TotalPrice' => $result['@attributes']['TotalPrice'],

		'BasePrice' => $result['@attributes']['BasePrice'],

		'ApproximateTotalPrice' => $result['@attributes']['ApproximateTotalPrice'],

		'ApproximateBasePrice' => $result['@attributes']['ApproximateBasePrice'],

		'Taxes' => $result['@attributes']['Taxes'],

		'ApproximateTaxes' => $result['@attributes']['ApproximateTaxes']

	);

	// Journey

	$airJourney=$result['airJourney'];

	$jounney=array();

	foreach($airJourney as $aj){

		$data2=array(

		   'TravelTime' => $aj['@attributes']['TravelTime']

		);

		if(isset($aj[0]['@attributes']['Key'])){

			$data2['airAirSegmentRef'][]=$aj[0]['@attributes']['Key'];

		}else if(isset($aj['airAirSegmentRef'][0]['@attributes']['Key'])){

			foreach($aj['airAirSegmentRef'] as $asr){

			   $data2['airAirSegmentRef'][]=$asr['@attributes']['Key'];

			}

		}

		$jounney[]=$data2;

	}

	$data['Journey']=$jounney;

	

	// Leg ref

	$airLegRef=$result['airLegRef'];

	$legref=array();

	foreach($airLegRef as $alr){

		$legref[]=$alr['@attributes']['Key'];

	}

	$data['LegRef']=$legref;

	

	//airAirPricingInfo

	$airAirPricingInfo=$result['airAirPricingInfo'];

	if(isset($airAirPricingInfo[0]['@attributes']['Key'])){ // multipassenger type

		$airAirPricingInfo=$airAirPricingInfo[0];

	}

	$pricinginfodata=array(

	    'Key' => $airAirPricingInfo['@attributes']['Key'],

		'TotalPrice' => $airAirPricingInfo['@attributes']['TotalPrice'],

		'BasePrice' => $airAirPricingInfo['@attributes']['BasePrice'],

		'ApproximateTotalPrice' => $airAirPricingInfo['@attributes']['ApproximateTotalPrice'],

		'ApproximateBasePrice' => $airAirPricingInfo['@attributes']['ApproximateBasePrice'],

		'Taxes' => $airAirPricingInfo['@attributes']['Taxes'],

		'ApproximateTaxes' => $airAirPricingInfo['@attributes']['ApproximateTaxes'],

		'LatestTicketingTime' => $airAirPricingInfo['@attributes']['LatestTicketingTime'],

		'PricingMethod' => $airAirPricingInfo['@attributes']['PricingMethod'],

		'ETicketability' => $airAirPricingInfo['@attributes']['ETicketability'],

		'ProviderCode' => $airAirPricingInfo['@attributes']['ProviderCode']

	);

	$airFareInfoRef=$airAirPricingInfo['airFareInfoRef'];

	$fairinforef=array();

	if(isset($airFareInfoRef['@attributes']['Key'])){

		$fairinforef[]=$airFareInfoRef['@attributes']['Key'];	

	}else if(isset($airFareInfoRef[0]['@attributes']['Key'])){

		foreach($airFareInfoRef as $afir){

		   $fairinforef[]=$afir['@attributes']['Key'];	

		}

	}

	$pricinginfodata['FareInfoRef']=$fairinforef;


	// airBookingInfo

	$airBookingInfo=$airAirPricingInfo['airBookingInfo'];


	$bookinginfo=array();

	if(isset($airBookingInfo['@attributes']['BookingCode'])){

		$bookinginfodata=array(

		    'BookingCode' => $airBookingInfo['@attributes']['BookingCode'],

			'CabinClass' => $airBookingInfo['@attributes']['CabinClass'],

			'FareInfoRef' => $airBookingInfo['@attributes']['FareInfoRef'],

			'SegmentRef' => $airBookingInfo['@attributes']['SegmentRef']

		);

		$bookinginfo[]=$bookinginfodata;

	}else if(isset($airBookingInfo[0]['@attributes']['BookingCode'])){

		foreach($airBookingInfo as $abi){

			$bookinginfodata=array(

				'BookingCode' => $abi['@attributes']['BookingCode'],

				'CabinClass' => $abi['@attributes']['CabinClass'],

				'FareInfoRef' => $abi['@attributes']['FareInfoRef'],

				'SegmentRef' => $abi['@attributes']['SegmentRef']

			);

			$bookinginfo[]=$bookinginfodata;

		}

	}

	$pricinginfodata['BookingInfo']=$bookinginfo;

/*echo "<pre>";
print_r($bookinginfo);
die('BookingInfo');*/	

	//airTaxInfo

	$airTaxInfo=$result['airTaxInfo'];

	$taxinfo=array();

	if(isset($airTaxInfo['@attributes']['Category'])){

		$taxinfodata=array(

		    'Category' => $airTaxInfo['@attributes']['Category'],

			'Amount' => $airTaxInfo['@attributes']['Amount']

		);

		$taxinfo[]=$taxinfodata;

	}else if(isset($airTaxInfo[0]['@attributes']['Category'])){

		foreach($airTaxInfo as $ati){

			$taxinfodata=array(

				'Category' => $ati['@attributes']['Category'],

				'Amount' => $ati['@attributes']['Amount']

			);

			$taxinfo[]=$taxinfodata;

		}

	}

	$pricinginfodata['TaxInfo']=$taxinfo;

	//airFareCalc

	$airFareCalc=$result['airFareCalc'];

	$pricinginfodata['FareCalc']=$airFareCalc;

	//airPassengerType

	$airPassengerType=$result['airPassengerType'];

	$passengertype=array();

	if(isset($airPassengerType['@attributes']['Code'])){

		$psgdata=array(

		    'Code' => $airPassengerType['@attributes']['Code']

		);

		$passengertype[]=$psgdata;

	}else if(isset($airPassengerType[0]['@attributes']['Code'])){

		foreach($airPassengerType as $apt){

			$psgdata=array(

				'Code' => $apt['@attributes']['Code']

			);

			$passengertype[]=$psgdata;

		}

	}

	$pricinginfodata['PassengerType']=$passengertype;

	//airChangePenalty

	if(isset($result['airChangePenalty'])){

	  $airChangePenalty=$result['airChangePenalty'];

	  $pricinginfodata['ChangePenalty']=$airChangePenalty['airAmount'];

	}

	 if(isset($result['airCancelPenalty'])){

	    $pricinginfodata['CancelPenalty']=$result['airCancelPenalty']['airAmount'];

	 }

	

	$data['AirPricingInfo']=$pricinginfodata;

	

	//airConnection

	$airConnection=$result['airConnection'];
		
	$connection=array();

	if(isset($airConnection['@attributes']['SegmentIndex'])){

		//foreach($airConnection as $k=>$v){
		$connectiondata=array(

		    'SegmentIndex' => $airConnection['@attributes']['SegmentIndex']
			//$connectiondata[]=$v

		);
		//}

		$connection[]=$connectiondata;

	}else if(isset($airConnection[0]['@attributes']['SegmentIndex'])){

		foreach($airConnection as $ac){
			//foreach($airConnection as $k=>$v){

			$connectiondata=array(

				'SegmentIndex' => $ac['@attributes']['SegmentIndex']
				//$connectiondata[]=$v

			);

			$connection[]=$connectiondata;

		}

	}

	

	$data['Connection']=$connection;

	

	$airpricingsolution[]=$data;

}// end airpricingsolution loop

$flightsresult=$airpricingsolution;

} // multicity


?>

<div class="sky-bg">

 <div class="container">

        <div class="row">

<?php if($searchdata['mode']=='oneway' || $searchdata['mode']=='roundtrip'){ ?>  

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

                    	<form action="<?php //echo site_url(); ?>/flight-result/" method="post">

                        	<div class="select-trip-option">

                            	<div class="r-trip"><input type="radio" name="mode" value="roundtrip"  onchange="chng_way(this);"  <?php echo $searchdata['mode']=='roundtrip'?'checked':'';?> /> Round Trip</div>

                                <div class="o-way"><input type="radio" name="mode" value="oneway"  onchange="chng_way(this);" <?php echo $searchdata['mode']=='oneway'?'checked':'';?> /> One Way</div>

                            </div>

                            <div class="edit-book-dtl">

                            	<div class="edit-lcl">

                                	<div>

                                    	<label>Depart From</label>

                                        <input type="text" name="from_city" class="typeahead cls-edit-depart"  id="id-edit-depart" value="<?php echo $searchdata['from_airport'];?>,<?php echo $searchdata['from_city'];?>" />

										<i onclick="jQuery('#id-edit-depart').val('');" class="fa fa-times-circle"></i>

                                    </div>

                                </div>

                                <div class="edit-lcl">

                                	<div>

                                    	<label>Destination</label>

                                        <input type="text" name="to_city" class="typeahead cls-edit-desti" id="id-edit-desti" value="<?php echo $searchdata['to_airport'];?>,<?php echo $searchdata['to_city'];?>" />

										<i onclick="jQuery('#id-edit-desti').val('');" class="fa fa-times-circle"></i>

                                    </div>

                                </div>

                                <div class="date-pick-area">

                                	<div>

                                    	<div class="date_picker">

                                        	<label>Depart Date</label>

                                            <input type="text" class="date-pick search_arrival" data-date-format="yyyy-mm-dd" name="depart_date" placeholder="Departing" value="<?php echo date("Y-m-d",strtotime($searchdata['start_date']));?>" />

                                        </div>

                                    </div>

                                </div>

                                <div class="date-pick-area">

                                	<div id="return_date_div" class="<?php echo $_POST['mode']=='oneway'?'in-active':''?>">

                                    	<div class="date_picker">

                                        	<label>Arrival Date</label>

                                            <input type="text" class="date-pick search_arrival" data-date-format="yyyy-mm-dd" name="return_date"  placeholder="Arrival" value="<?php echo date("Y-m-d",strtotime($searchdata['end_date']));?>" />

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
                                
                                 <div class="edit-psg">
                                	<div>
                                    	<div class="select-option">
                                            <label>Select Airline: </label>
                                            <select name="iata">
                                            <option value="" >All Airlines</option>
                                                <?php foreach($airlines as $k=>$v){?>
                        <option value="<?php echo $k;?>" <?php echo $_POST['iata']==$k?'selected="selected"':'';?> ><?php echo $v;?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div class="edit-psg-btn-area">

                                	<button>Find Flight <i class="fa fa-angle-double-right"></i></button>

                                </div>

                               <?php /*?> <div class="clearfix"></div>

								<p>Info for children <a href="#">under 15 years old travelling alone</a> <i class="fa fa-angle-double-right"></i></p><?php */?>

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

<?php } ?>

<?php if($searchdata['mode']=='oneway'){ ?>           

<?php 

$n=1;

foreach($flightsresult as $f){

# Rajib test
/*echo '<pre>';
print_r($f); die('one');*/
# Rajib test

## Rajib Added
if($n >= $perpageList) break;
## Rajib Added

## Rajib Added - Connection
$conn=$f['AirPricingInfo'][0]['FlightOption']['Option'][0]['Connection'];
## Rajib Added - Connection


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


## Rajib modify - for -tv Stop
if(!array_key_exists($k1, $airsegments) || $stopsover < 0){

 continue;

}


$startsegment=$airsegments[$k1];

$k2=getSegmentKey($endSegmentRef,$airsegments);

$endsegment=$airsegments[$k2];

$totaltime=getTimeDiff(date("Y-m-d H:i:s",strtotime($startsegment['DepartureTime'])),date("Y-m-d H:i:s",strtotime($endsegment['ArrivalTime'])));

$Carrier=$startsegment['Carrier'];

$airlinename=$airlines[$Carrier];



$BookingInfo=$fopt['BookingInfo'];

## Rajib added
$ConnectionInfo=$fopt['Connection'];
/*echo '<pre>';
print_r($ConnectionInfo);
die;*/
## Rajib added

// just flter by airline July 26, 2016	
if(isset($searchdata['iata']) && $searchdata['iata']!=''){
    if($searchdata['iata']!=$Carrier)continue;
}


$FareInfoRef=$fopt['BookingInfo']['0']['FareInfoRef'];

$fkey=getFairInfoKey($FareInfoRef,$fairinfolist);

$FairInfo=$fairinfolist[$fkey];

$FareRuleKey=$FairInfo['FareRuleKey'];

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

                                <div class="col-sm-3 col-xs-4 flt-single drsn"><?php //echo trim($airlinename);?><img src="../airimages/<?php echo $Carrier.'.GIF';?>" /> 
								<?php echo $airlinename;?></div>

                                <div class="col-sm-1 col-xs-4 flt-single"><a><?php echo $stopsover?($stopsover>1?$stopsover.' Stops':$stopsover.' Stop'):'Direct';?></a></div>

                                <div class="col-sm-2 col-xs-4 flt-single"><?php echo $totaltime;?></div>

                            </div>

                        </div>

                        <div class="edit-visl">

                        <?php 

						    $bookingdata=array();

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

							

							$bookingdata['segmentdtls'][]=$segmentdtls;

							## Rajib added
							$bookingdata['Connection'][]=$conn;
							## Rajib added

							$bookingdata['CabinClass'][]=$b['CabinClass'];

							$bookingdata['BookingCode'][]=$b['BookingCode'];

							$bookingdata['passengers'][]=$searchdata['passengers'];

							

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

                            <div class="vgn"><p><img src="../airimages/<?php echo $thisCarrier.'.GIF';?>" /> <?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $b['CabinClass'];?> <?php echo  $b['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p></div>

                            <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> <?php echo floor($segmentdtls['FlightTime']/60);?>hrs <?php echo ceil($segmentdtls['FlightTime']%60);?>mins</div>

                        <?php }?> 

                        </div>

                        <div class="clearfix"></div>

                    </div>

					<div class="col-sm-4 show-flight-prize">

                    	<div class="flt-prz">

                        	<label>Per Adult OneWay</label>

                            <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="<?php echo $FairAmount;?>" readonly />

                            <form name="frmAvail" action="../free-booking" method="post">

                            <input type="hidden" name="bookingdata" value="<?php echo base64_encode(serialize($bookingdata));?>" />

                            <input type="hidden" name="searchdata" value="<?php echo base64_encode(serialize($searchdata));?>" />

                            <input type="hidden" name="searchmode" value="oneway" />

                            <button type="submit">Make Free Booking</button>

                            </form>

                            <form name="frmAvail" action="../booking" method="post">

                            <!-- Rajib added -->
                            <input type="hidden" name="connectiondata" value="<?php echo base64_encode(serialize($ConnectionInfo));?>" />
                            <!-- Rajib added -->
                            <input type="hidden" name="bookingdata" value="<?php echo base64_encode(serialize($bookingdata));?>" />

                            <input type="hidden" name="searchdata" value="<?php echo base64_encode(serialize($searchdata));?>" />

                            <input type="hidden" name="searchmode" value="oneway" />

                            <button type="submit">Book Now</button>

                            </form>

                            <form name="frmFL" id="frmFL<?php echo $n?>" method="post" action="../farerule.php" target="_blank">

                            <input type="hidden" name="k" value="<?php echo $FareRuleKey;?>" />

                            <input type="hidden" name="ref" value="<?php echo $FareInfoRef;?>" />

                            <p><a class="shw2" onclick="jQuery('#frmFL<?php echo $n;?>').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>

                            </form>

                            <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>

                        </div>

                        <div class="clearfix"></div>

                    </div>

                    <div class="clearfix"></div>

                </div>

            </div>

<?php $n++; }}?>			

<?php }else if($searchdata['mode']=='roundtrip'){?>

<?php $z=1;?>

<?php foreach($flightsresult as $f){?>  

<?php
# Rajib test
/*echo '<pre>';
print_r($f); die;*/

## Rajib Added
if($z >= $perpageList) break;
## Rajib Added

## Rajib Added - Connection
$conn=$f['AirPricingInfo'][0]['FlightOption'][0]['Option'][0]['Connection'];
## Rajib Added - Connection

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

## Rajib modifiy - In/Out Stops - 25/01/2016
if($inStopover<0 || $outStopover<0)
continue;

// just flter by airline July 26, 2016	
if(isset($searchdata['iata']) && $searchdata['iata']!=''){
    if($searchdata['iata']!=$outCarrier)continue;
}


$BookingInfoIn=$fopt['BookingInfo'];

$bookingdata=array();

$fopt=$apf['FlightOption'][0]['Option'][$fm[0]]; // out

$FareInfoRef=$fopt['BookingInfo']['0']['FareInfoRef'];

$fkey=getFairInfoKey($FareInfoRef,$fairinfolist);

$FairInfo=$fairinfolist[$fkey];

$FareRuleKey=$FairInfo['FareRuleKey'];

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

                        <div class="col-sm-3 col-xs-4 flt drsn"><?php //echo $outAirlinename;?><img src="../airimages/<?php echo $outCarrier.'.GIF';?>" /> <?php echo $outAirlinename;?></div>

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

                        <div class="col-sm-3 col-xs-4 flt drsn"><?php //echo $inAirlinename;?><img src="../airimages/<?php echo $inCarrier.'.GIF';?>" /> <?php echo $inAirlinename;?>
                        </div>

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

					

					$bookingdata['segmentdtls'][]=$segmentdtls;
					
					## Rajib added
					$bookingdata['Connection'][]=$conn;
					## Rajib added

					$bookingdata['CabinClass'][]=$bout['CabinClass'];

					$bookingdata['BookingCode'][]=$bout['BookingCode'];

					$bookingdata['passengers'][]=$searchdata['passengers'];		

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

                    <div class="vgn"><p><img src="../airimages/<?php echo $thisCarrier.'.GIF';?>" /> <?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $bout['CabinClass'];?> <?php echo $bout['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p></div>

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

					

					$bookingdata['segmentdtls'][]=$segmentdtls;
					
					## Rajib added
					$bookingdata['Connection'][]=$conn;
					## Rajib added


					$bookingdata['CabinClass'][]=$bin['CabinClass'];

					$bookingdata['BookingCode'][]=$bin['BookingCode'];

					$bookingdata['passengers'][]=$searchdata['passengers'];

							

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

                    <div class="vgn"><p><img src="../airimages/<?php echo $thisCarrier.'.GIF';?>" /> <?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $bin['CabinClass'];?> <?php echo $bin['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p></div>

                    <?php }?>

                    <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> <?php echo floor($totalflighttime/60);?>hrs <?php echo ceil($totalflighttime%60);?>mins</div>

                    

                </div>

                <div class="clearfix"></div>

            </div>

            <div class="col-sm-4 show-flight-prize">

                <div class="flt-prz">

                    <label>Per Adult Return</label>

                    <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="<?php echo $FairAmount;?>" readonly />

                    <form name="frmAvail" action="../free-booking" method="post">

                    <input type="hidden" name="bookingdata" value="<?php echo base64_encode(serialize($bookingdata));?>" />

                    <input type="hidden" name="searchdata" value="<?php echo base64_encode(serialize($searchdata));?>" />

                    <input type="hidden" name="searchmode" value="roundtrip" />

                    <button type="submit">Make Free Booking</button>

                    </form>

                    <form name="frmAvail" action="../booking" method="post">
                    
                    <!-- Rajib added -->
                    <input type="hidden" name="connectiondata" value="<?php echo base64_encode(serialize($ConnectionInfo));?>" />
                    <!-- Rajib added -->

                    <input type="hidden" name="bookingdata" value="<?php echo base64_encode(serialize($bookingdata));?>" />

                    <input type="hidden" name="searchdata" value="<?php echo base64_encode(serialize($searchdata));?>" />

                    <input type="hidden" name="searchmode" value="roundtrip" />

                    <button type="submit">Book Now</button>

                    </form>

                    <form name="frmFL" id="frmFL<?php echo $z?>" method="post" action="../farerule.php" target="_blank">

                    <input type="hidden" name="k" value="<?php echo $FareRuleKey;?>" />

                    <input type="hidden" name="ref" value="<?php echo $FareInfoRef;?>" />

                    <p><a class="shw2" onclick="jQuery('#frmFL<?php echo $z;?>').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>

                    </form>

                    <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>

                </div>

                <div class="clearfix"></div>

            </div>

            <div class="clearfix"></div>

        </div>

    </div>

 <?php $z++;} }?>  

 <?php }else if($searchdata['mode']=='multicity'){ //die('multicity');?>        

 <?php 

/*echo '<pre>';
print_r($flightsresult);
die('Multicity_Result');*/

$n=1;

foreach($flightsresult as $f){

## Rajib Added - Connection
$conn=$f['Connection'];
## Rajib Added - Connection

## Rajib Added
if($n >= $perpageList) break;
## Rajib Added
	

if(!isset($f['AirPricingInfo']['BookingInfo']['0'])){

	continue;

}

$apf=$f['AirPricingInfo'];



$TotalPrice=$apf['TotalPrice'];

$currency=substr($TotalPrice,0,3);

$TotalPrice=substr($TotalPrice,3);

$FairAmount=$TotalPrice;// / (count($apf['PassengerType']));

$FairAmount=$currency." ".$FairAmount;



//foreach($options as $fopt){

//$TravelTime=$fopt['TravelTime'];

//$TravelTime=getTimeInFormat($TravelTime);

$TravelTime='';



$startSegmentRef=$apf['BookingInfo']['0']['SegmentRef'];

$endSegmentpos=count($apf['BookingInfo'])-1;

$stopsover=count($apf['BookingInfo'])-1;

$endSegmentRef=$apf['BookingInfo'][$endSegmentpos]['SegmentRef'];

$k1=getSegmentKey($startSegmentRef,$airsegments);


# Rajib added - Stopover - 25/01/2016
if(!array_key_exists($k1, $airsegments) || $stopsover<0){

 continue;

}



$startsegment=$airsegments[$k1];

$k2=getSegmentKey($endSegmentRef,$airsegments);

$endsegment=$airsegments[$k2];

$totaltime=getTimeDiff(date("Y-m-d H:i:s",strtotime($startsegment['DepartureTime'])),date("Y-m-d H:i:s",strtotime($endsegment['ArrivalTime'])));

$Carrier=$startsegment['Carrier'];

$airlinename=$airlines[$Carrier];



$origin=$startsegment['Origin'];

$destination=$endsegment['Destination'];

$BookingInfo=$apf['BookingInfo'];
## Rajib added
$ConnectionInfo=$apf['Connection'];
## Rajib added



$FareInfoRef=$apf['BookingInfo']['0']['FareInfoRef'];

$fkey=getFairInfoKey($FareInfoRef,$fairinfolist);

$FairInfo=$fairinfolist[$fkey];

$FareRuleKey=$FairInfo['FareRuleKey'];

?>

            <div class="col-sm-12">

            	<div class="show-flight">                    

                	<div class="col-sm-8">

                    	<div class="visl">

                        	<div class="row">

                            	<div class="col-sm-3 col-xs-6 flt-single arw">

                                	<p><?php //echo $n."<br />"; ?><?php echo $origin;?><br />

                                    <?php echo date("h:i a",strtotime($startsegment['DepartureTime']));?> <br/>

							        <?php echo date("D, d M Y",strtotime($startsegment['DepartureTime']));?></p>

                                </div>

                                <div class="col-sm-3 col-xs-6 flt-single">

                                	<p><?php echo $destination;?><br />

                                    <?php echo date("h:i a",strtotime($endsegment['ArrivalTime']));?> <br/>

							        <?php echo date("D, d M Y",strtotime($endsegment['ArrivalTime']));?></p>

                                </div>

                                <div class="col-sm-3 col-xs-4 flt-single drsn"><?php //echo trim($airlinename);?><img src="../airimages/<?php echo $Carrier.'.GIF';?>" /></div>

                                <div class="col-sm-1 col-xs-4 flt-single"><a><?php echo $stopsover?($stopsover>1?$stopsover.' Stops':$stopsover.' Stop'):'Direct';?></a></div>

                                <div class="col-sm-2 col-xs-4 flt-single"><?php echo $totaltime;?></div>

                            </div>

                        </div>

                        <div class="edit-visl">

                        <?php 

						    $bookingdata=array();

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

							

							$bookingdata['segmentdtls'][]=$segmentdtls;
							
							## Rajib Added
							$bookingdata['Connection'][]=$conn;
							## Rajib Added

							$bookingdata['CabinClass'][]=$b['CabinClass'];

							$bookingdata['BookingCode'][]=$b['BookingCode'];

							$bookingdata['passengers'][]=$searchdata['passengers'];

							

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

                            <div class="vgn"><p><img src="../airimages/<?php echo $thisCarrier.'.GIF';?>" /> <?php echo $thisairlinename;?> - <?php echo $thisCarrier;?><?php echo $FlightNumber;?> <?php echo $airCodeshareInfo;?> <a><?php echo $b['CabinClass'];?> <?php echo  $b['BookingCode'];?></a> - Aircraft <?php echo $aircraft;?></p></div>

                            <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> <?php echo floor($segmentdtls['FlightTime']/60);?>hrs <?php echo ceil($segmentdtls['FlightTime']%60);?>mins</div>

                        <?php }?> 

                        </div>

                        <div class="clearfix"></div>

                    </div>

					<div class="col-sm-4 show-flight-prize">

                    	<div class="flt-prz">

                        	<label>Per Adult Multicity</label>

                            <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="<?php echo $FairAmount;?>" readonly />

                            <form name="frmAvail" action="../free-booking" method="post">

                            <input type="hidden" name="bookingdata" value="<?php echo base64_encode(serialize($bookingdata));?>" />

                            <input type="hidden" name="searchdata" value="<?php echo base64_encode(serialize($searchdata));?>" />

                            <input type="hidden" name="searchmode" value="multicity" />

                            <button type="submit">Make Free Booking</button>

                            </form>

                            <form name="frmAvail" action="../booking" method="post">

                             <!-- Rajib added -->
                            <input type="hidden" name="connectiondata" value="<?php echo base64_encode(serialize($ConnectionInfo));?>" />
                            <!-- Rajib added -->
                           <input type="hidden" name="bookingdata" value="<?php echo base64_encode(serialize($bookingdata));?>" />

                            <input type="hidden" name="searchdata" value="<?php echo base64_encode(serialize($searchdata));?>" />

                            <input type="hidden" name="searchmode" value="multicity" />

                            <button type="submit">Book Now</button>

                            </form>

                            <form name="frmFL" id="frmFL<?php echo $$n?>" method="post" action="../farerule.php" target="_blank">

                            <input type="hidden" name="k" value="<?php echo $FareRuleKey;?>" />

                            <input type="hidden" name="ref" value="<?php echo $FareInfoRef;?>" />

                            <p><a class="shw2" onclick="jQuery('#frmFL<?php echo $$n;?>').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>

                            </form>

                            <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>

                        </div>

                        <div class="clearfix"></div>

                    </div>

                    <div class="clearfix"></div>

                </div>

            </div>

<?php $n++;}?>	           

<?php } ?>

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

function chng_way(ele){ 

	if(ele.value=='oneway')jQuery('#return_date_div').addClass('in-active');

	if(ele.value=='roundtrip')jQuery('#return_date_div').removeClass('in-active');

}

jQuery('.date-pick').datepicker({ autoclose: true,todayHighlight: true});


jQuery('.typeahead').typeahead( 
	{ 
		hint: true, 
		highlight: true, 
		minLength: 3, 
		limit: 8 
	}, 
	{ 
	source: function(q, cb) { 
		return jQuery.ajax({ 
			dataType: 'json', 
			type: 'get', 
			url: 'http://www.clickmybooking.com/getcity_airport.php?q=' + q , 
			cache: false, 
			success: function(data) { 
				var result = []; 
				jQuery.each(data, function(index, val) { 
					result.push({ 
						value: val 
					}); 
				}); 
				cb(result); 
			} 
		}); 
	} 
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

function getCacheKey($searchdata){
	$cachekey='';	
	if($searchdata['mode']=='oneway'){
$cachekey="o".$searchdata['from_airport'].$searchdata['to_airport'].str_replace("-","",$searchdata['start_date']).$searchdata['cabinclass'].$searchdata['adult'].$searchdata['child'].$searchdata['infant'];
	}else if($searchdata['mode']=='roundtrip'){
$cachekey="r".$searchdata['from_airport'].$searchdata['to_airport'].str_replace("-","",$searchdata['start_date']).str_replace("-","",$searchdata['end_date']).$searchdata['cabinclass'].$searchdata['adult'].$searchdata['child'].$searchdata['infant'];
	}else if($searchdata['mode']=='multicity'){
		$from='';
		if(isset($searchdata['from_airport']) && is_array($searchdata['from_airport']))
		   $from=implode(":",$searchdata['from_airport']);
		$to='';
		if(isset($searchdata['to_airport']) && is_array($searchdata['to_airport']))
		   $to=implode(":",$searchdata['to_airport']); 
		$dates='';
		if(isset($searchdata['start_date']) && is_array($searchdata['start_date']))
		   $dates=implode(":",$searchdata['start_date']);   
		$dates=str_replace("-","",$dates);  
		$cachekey="m".$from.$to.$dates.$searchdata['cabinclass'].$searchdata['adult'].$searchdata['child'].$searchdata['infant'];
	}
	$cachekey=strtolower($cachekey);	
	return $cachekey;	
}

function getExpiretime($dt=''){
	$ex=0;
	if($dt=='')$dt=date("Y-m-d");
	$secdiff=strtotime($dt) - time();
	$hourdiff=floor($secdiff/(60*60));
	if($hourdiff>0 && $hourdiff<(4 *24)){ // 0 to 3 days
		$ex=(60 * 5); // 5 min	
	}else if($hourdiff>=(4 *24) && $hourdiff<(8 *24)){ // 4 to 7 days
		$ex=(60 * 15); // 15 min	
	}else if($hourdiff>=(8 *24) && $hourdiff<(16 *24)){ // 8 to 15 days
	   $ex=(60 * 30); // 30 min	
	}else if($hourdiff>=(16 *24)){ //>=16 days
	   $ex=(60 * 60); // 60 min	
	}	
	return $ex;
}
?>