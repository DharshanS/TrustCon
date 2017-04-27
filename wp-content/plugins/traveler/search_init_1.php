<?php


error_log(" ------------------- > Flight search v1 ". get_home_path());
set_time_limit(0);
date_default_timezone_set('Asia/Colombo');
# SET END POINT
//$endpoint='https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService'; // Production end point
//$endpoint='https://twsprofiler.travelport.com/Service/Default.ashx/AirService'; // Profiler end point

$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService'; // LIVE end point

$GENERATELOG=0; // 1 OR 0

$xmlLogPath=get_home_path().'/logxml';
require_once(plugin_dir_path( __FILE__ ) . 'search_thread_v1.php');
require_once(plugin_dir_path( __FILE__ ) . 'service/RoundTripResponse.php');

require_once(get_home_path().'/library/cache.php');
require_once(get_home_path().'/travelportsettings.php');
require_once(get_home_path().'/tconnect.php');

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
$SQL="SELECT * FROM `countries` WHERE `code`!='' ORDER BY `name`";
$rs = mysqli_query($alink, $SQL) or die($SQL);
while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
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
$SQL="SELECT `iata_code`,`city`,`airport_name`,`country` FROM `airports`";
$rs = mysqli_query($alink, $SQL);
while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){
  $airportscity[$row['iata_code']]=$row['city'];
  $airportsname[$row['iata_code']]=$row['airport_name'];
  $airlines[$row['iata']]=$row['iata_code'];
  $countries['countries']=$row['country'];
}
$_SESSION['airportscity']=$airportscity;
$_SESSION['airportsname']=$airportsname;
$_SESSION['airlines']=$airlines;
$_SESSION['countries']=$countries;
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
	//$resp = curl_exec($soap_do);
	curl_close ($soap_do);
	

	 //$resp = file_get_contents(plugin_dir_path( __FILE__ ) .'/xml/LowFareSearchRsp.xml', FILE_USE_INCLUDE_PATH);
	
	//$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
	//$xml = simplexml_load_string($xml);
//        libxml_use_internal_errors(true);
//       error_log("Reading xml file ............");
//      // $xml = simplexml_load_file(plugin_dir_path( __FILE__ ) .'/LowFareSearchRsp.xml');
//     
//     
//	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
//	$xml = simplexml_load_string($xml);
//     
//}
//     
//	$json = json_encode($xml);
//	$responseArray = json_decode($json,true);

	//$_SESSION['baseresponse']=$responseArray;
    
	// generate cache
	//$cache->set($cachekey, $responseArray);

       //echo  initSearch($responseArray);
        echo  roundTrip();
}
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
