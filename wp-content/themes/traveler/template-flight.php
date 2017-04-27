<?php
/*
Template Name: Flight
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
$dt='';	
if(isset($_POST)){
$dt=base64_encode(serialize($_POST));
}
/*
set_time_limit(0);
global $wpdb;
$flightsresult=array();
if(isset($_POST['mode']) && ($_POST['mode']=='roundtrip' || $_POST['mode']=='oneway' || $_POST['mode']=='stopover')){
$from_city='';
$from_country='';
$to_city='';
$to_country='';
$searchdata=array();

$startdt='';
$enddt='';

$adult=isset($_POST['rtrip_adult'])?$_POST['rtrip_adult']:1;
$child=isset($_POST['rtrip_child'])?$_POST['rtrip_child']:0;
$infant=isset($_POST['rtrip_infant'])?$_POST['rtrip_infant']:0;
$cabinclass=isset($_POST['cabinclass'])?$_POST['cabinclass']:'Economy';
$date_flexi=isset($_POST['date_flexi'])?$_POST['date_flexi']:0;


if(isset($_POST['rtrip_from_city'])){
$from_city=$_POST['rtrip_from_city'];	
$fromcityarr=explode(",",$from_city);
$from_city=$fromcityarr[0];
if(count($fromcityarr)>1)$from_country=end($fromcityarr);
}
$searchdata['from_airport']=$from_city;

if(isset($_POST['rtrip_to_city'])){
$to_city=$_POST['rtrip_to_city'];	
$tocityarr=explode(",",$to_city);
$to_city=$tocityarr[0];
if(count($tocityarr)>1)$to_country=end($tocityarr);
}
$searchdata['to_airport']=$to_city;

if(isset($_POST['rtrip_depart_date'])){
	$startdt=$_POST['rtrip_depart_date'];
	$startdtarr=explode(",",$startdt);
	$startdt=$startdtarr[0];
	$startdt=date("Y-m-d",strtotime($startdt));
}
if(isset($_POST['rtrip_return_date'])){
    $enddt=$_POST['rtrip_return_date'];
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

$searchdata['date_flexi']=$date_flexi;

$searchdata['mode']=$_POST['mode'];


//echo "<pre>";
//print_r($searchdata);

	
$TARGETBRANCH = 'P105402';
$CREDENTIALS = 'Universal API/uAPI-405719738:ZkbN2zdSdNrcWWq5WXSbHtDTa';
$Provider = '1G'; // Any provider you want to use like 1G/1P/1V/ACH

	$message = '
	<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
	   <soapenv:Header/>
	   <soapenv:Body>
		  <air:AvailabilitySearchReq TraceId="trace" AuthorizedBy="user" TargetBranch="'.$TARGETBRANCH.'" xmlns:air="http://www.travelport.com/schema/air_v29_0" xmlns:com="http://www.travelport.com/schema/common_v29_0">
			 <com:BillingPointOfSaleInfo OriginApplication="UAPI"/>
			 <air:SearchAirLeg>
				<air:SearchOrigin>
				   <com:Airport Code="'.$searchdata['from_airport'].'"/>
				</air:SearchOrigin>
				<air:SearchDestination>
				   <com:Airport Code="'.$searchdata['to_airport'].'"/>
				</air:SearchDestination>
				<air:SearchDepTime PreferredTime="'.$searchdata['start_date'].'">
				</air:SearchDepTime>            
			 </air:SearchAirLeg>
			 <air:AirSearchModifiers>
				<air:PreferredProviders>
				   <com:Provider Code="'.$Provider.'"/>
				</air:PreferredProviders>
			 </air:AirSearchModifiers>
		  </air:AvailabilitySearchReq>
	   </soapenv:Body>
	</soapenv:Envelope>
	';
$message = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">   <soapenv:Header/>
	   <soapenv:Body>
		  <air:AvailabilitySearchReq AuthorizedBy="user" TargetBranch="'.$TARGETBRANCH.'" TraceId="trace" xmlns:air="http://www.travelport.com/schema/air_v26_0">
			 <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>
			 <air:SearchAirLeg>
				<air:SearchOrigin>
				   <com:Airport Code="'.$searchdata['from_airport'].'" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>
				</air:SearchOrigin>
				<air:SearchDestination>
				   <com:Airport Code="'.$searchdata['to_airport'].'" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>
				</air:SearchDestination>
				<air:SearchDepTime PreferredTime="'.$searchdata['start_date'].'"/>
				<air:AirLegModifiers>
				   <air:PreferredCabins>
					  <com:CabinClass Type="'.$searchdata['cabinclass'].'" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>
				   </air:PreferredCabins>
				</air:AirLegModifiers>
			 </air:SearchAirLeg>
			 <air:SearchAirLeg>
				<air:SearchOrigin>
				   <com:Airport Code="'.$searchdata['to_airport'].'" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>
				</air:SearchOrigin>
				<air:SearchDestination>
				   <com:Airport Code="'.$searchdata['from_airport'].'" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>
				</air:SearchDestination>
				<air:SearchDepTime PreferredTime="'.$searchdata['end_date'].'"/>
				<air:AirLegModifiers>
				   <air:PreferredCabins>
					  <com:CabinClass Type="'.$searchdata['cabinclass'].'" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>
				   </air:PreferredCabins>
				</air:AirLegModifiers>
			 </air:SearchAirLeg>
			 <air:AirSearchModifiers>
				<air:PreferredProviders>
				   <com:Provider Code="'.$Provider.'" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>
				</air:PreferredProviders>
			 </air:AirSearchModifiers>
		  </air:AvailabilitySearchReq>
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
	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
	$xml = simplexml_load_string($xml);
	$json = json_encode($xml);
	$responseArray = json_decode($json,true);
	
	//echo "<pre>";
	//print_r($responseArray);
	//die();
	
	$results=$responseArray['SOAPBody']['airAvailabilitySearchRsp']['airAirItinerarySolution'];
	//echo "<pre>";
	//print_r($results);
	$mainindex=0;
	$outboundconnections=array();
	$outbound=$results[0];
	$outbound=$outbound['airConnection'];
	if(isset($outbound['@attributes']))$outboundconnections[]=$outbound['@attributes']['SegmentIndex'];
	else if(isset($outbound) && is_array($outbound)){
		foreach($outbound as $b){
		   if(isset($b['@attributes']))$outboundconnections[]=$b['@attributes']['SegmentIndex'];
		}
	}
	
	$returnboundconnections=array();
	$retuenbound=$results[1];
	$retuenbound=$retuenbound['airConnection'];
	if(isset($retuenbound['@attributes']))$returnboundconnections[$mainindex++]=$retuenbound['@attributes']['SegmentIndex'];
	else if(isset($retuenbound) && is_array($retuenbound)){
		foreach($retuenbound as $r){
		   if(isset($r['@attributes']))$returnboundconnections[$mainindex++]=$r['@attributes']['SegmentIndex'];
		}
	}
	//print_r($outboundconnections);
//	echo "<pre>";
//	print_r($returnboundconnections);
//	die();
	
	$outboundflights=array();
	$returnboundflights=array();
	$results=$responseArray['SOAPBody']['airAvailabilitySearchRsp']['airAirSegmentList']['airAirSegment'];
	if(empty($results))$results=array();
	$mainindex=0;
	foreach($results as $result){
	 $data=array(
		'Key'=>$result['@attributes']['Key'],
		'Group'=>$result['@attributes']['Group'],
		'Carrier'=>$result['@attributes']['Carrier'],
		'FlightNumber'=>$result['@attributes']['FlightNumber'],
		'Origin'=>$result['@attributes']['Origin'],
		'Destination' =>$result['@attributes']['Destination'],
		'DepartureTime' =>$result['@attributes']['DepartureTime'],
		'ArrivalTime' =>$result['@attributes']['ArrivalTime'],
		'FlightTime' =>$result['@attributes']['FlightTime'],
		'TravelTime' =>$result['@attributes']['TravelTime'],
		'ETicketability' =>$result['@attributes']['ETicketability'],
		'Equipment' =>$result['@attributes']['Equipment'],
		'ChangeOfPlane' =>$result['@attributes']['ChangeOfPlane'],
		//'ParticipantLevel' =>$result['@attributes']['ParticipantLevel'],
		//'LinkAvailability' =>$result['@attributes']['LinkAvailability'],
		'PolledAvailabilityOption' =>$result['@attributes']['PolledAvailabilityOption'],
		'OptionalServicesIndicator' =>$result['@attributes']['OptionalServicesIndicator'],
		'AvailabilitySource' =>$result['@attributes']['AvailabilitySource'],
		'AvailabilityDisplayType' =>$result['@attributes']['AvailabilityDisplayType']
	  );
	 if(isset($result['@attributes']['ParticipantLevel'])){
		 $data['ParticipantLevel'] = $result['@attributes']['ParticipantLevel'];
	 }else{
		$data['ParticipantLevel']='Secure Sell'; 
	 }
	 if(isset($result['@attributes']['LinkAvailability'])){
		 $data['LinkAvailability'] = $result['@attributes']['LinkAvailability'];
	 }else{
		$data['LinkAvailability'] ='true'; 
	 }
	 if(isset($result['airCodeshareInfo'])){
		 $data['airCodeshareInfo'] = $result['airCodeshareInfo'];
	 }
	 
	 if(isset($result['airAirAvailInfo']['airBookingCodeInfo'])){
		 $airBookingCodeInfo=$result['airAirAvailInfo']['airBookingCodeInfo'];
		  $bookinginfo=array();
		 foreach($airBookingCodeInfo as $bookingcode){
			$opt=array(
				 'CabinClass' => $bookingcode['@attributes']['CabinClass'],
				 'BookingCounts' => $bookingcode['@attributes']['BookingCounts']
			);
			 $data['BookingCodeInfo'][]= $opt;
		 }
	 }else if(isset($result['airAirAvailInfo'][0]['@attributes']['CabinClass'])){
		 $opt=array(
				 'CabinClass' => $result['airAirAvailInfo'][0]['@attributes']['CabinClass'],
				 'BookingCounts' => $result['airAirAvailInfo'][0]['@attributes']['BookingCounts']
			);
		  $data['BookingCodeInfo'][]= $opt;
	 }
	 
	  if(isset($result['airFlightDetailsRef']['@attributes'])){
		  $data['airFlightDetailsRef']=$result['airFlightDetailsRef']['@attributes']['Key'];
	  }
	if($result['@attributes']['Group']==0)$outboundflights[]=$data;	
	else if($result['@attributes']['Group']==1)$returnboundflights[]=$data;	
	}
	//echo "<pre>";
	//print_r($outboundflights);
	
	// making the routes  .. first value in outboundconnections array indicates start of stop over flights , i assume previous segments flights are direct(non stop)
	$outboundnonstop=array();
	foreach($outboundflights as $k=>$bflight){
		if($k==$outboundconnections[0])break;
		else $outboundnonstop[]=$bflight;
	}
	
	$newrouts=array();
	$data=array();
	foreach($outboundflights as $k=>$bflight){
		if($k<$outboundconnections[0])continue;
		else{// process
		     if($bflight['Origin']==$searchdata['from_airport'] && $bflight['Destination']==$searchdata['to_airport'])$outboundnonstop[]=$bflight; // it self a complete route
			 else{
				 // capture to construct new route
				 $data[]=$bflight;
				 if($bflight['Destination']==$searchdata['to_airport']){ // route reached at destination, hence close 
					$newrouts[]=$data;
					$data=array();
				 }
			 }
		}
	}
	$outboundrouts['nonstop']=$outboundnonstop;
	if(count($newrouts))
	$outboundrouts['stopbreak']=$newrouts;
	
	//echo "<pre>";
	//print_r($outboundrouts);
	//die();
	
	$outboundairprice=array();
	$outboundnonstopprice=array();
	$outboundairprice=array();
	foreach($outboundrouts['nonstop'] as $flight){
	$soap ='
	<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
	  <soapenv:Header/>
	  <soapenv:Body>
		<air:AirPriceReq xmlns:air="http://www.travelport.com/schema/air_v29_0" AuthorizedBy="user" TargetBranch="'.$TARGETBRANCH.'" TraceId="trace">
		  <com:BillingPointOfSaleInfo xmlns:com="http://www.travelport.com/schema/common_v29_0" OriginApplication="UAPI"/>
		  <air:AirItinerary>';
			$soap .='<air:AirSegment ArrivalTime="'.$flight['ArrivalTime'].'" AvailabilityDisplayType="'.$flight['AvailabilityDisplayType'].'" AvailabilitySource="'.$flight['AvailabilitySource'].'" Carrier="'.$flight['Carrier'].'" ChangeOfPlane="'.$flight['ChangeOfPlane'].'" DepartureTime="'.$flight['DepartureTime'].'" Destination="'.$flight['Destination'].'" ETicketability="'.$flight['ETicketability'].'" Equipment="'.$flight['Equipment'].'" FlightNumber="'.$flight['FlightNumber'].'" FlightTime="'.$flight['FlightTime'].'" Group="'.$flight['Group'].'" Key="'.$flight['Key'].'" LinkAvailability="'.$flight['LinkAvailability'].'" OptionalServicesIndicator="true" Origin="'.$flight['Origin'].'" ParticipantLevel="'.$flight['ParticipantLevel'].'" PolledAvailabilityOption="'.$flight['PolledAvailabilityOption'].'" ProviderCode="1G" TravelTime="'.$flight['TravelTime'].'"/>';
			$soap .='
		   </air:AirItinerary>
		  <air:AirPricingModifiers PlatingCarrier="QF"/>';
		  foreach($searchdata['passengers'] as $k=>$psgcode){
			 // $soap .='<com:SearchPassenger xmlns:com="http://www.travelport.com/schema/common_v29_0" BookingTravelerRef="'.base64_encode($k).'" Code="'.$psgcode.'" />'; 
		  }
		   $soap .='<com:SearchPassenger xmlns:com="http://www.travelport.com/schema/common_v29_0" BookingTravelerRef="gr8AVWGCR064r57Jt0+8bA==" Code="ADT" Age="45" DOB="1970-07-24" />'; 
		  //$soap .='<air:AirPricingCommand CabinClass="'.$searchdata['CabinClass'].'"/>';  
		  foreach($flight['BookingCodeInfo'] as $binfo){
          $soap .='<air:AirPricingCommand CabinClass="'.$binfo['CabinClass'].'"/>';  
          }
		 $soap .='
		</air:AirPriceReq>
	  </soapenv:Body>
	</soapenv:Envelope>
	';
	
$soap ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header/>
   <soapenv:Body>
      <air:AirPriceReq AuthorizedBy="user" TargetBranch="'.$TARGETBRANCH.'" TraceId="trace" xmlns:air="http://www.travelport.com/schema/air_v26_0">
         <com:BillingPointOfSaleInfo OriginApplication="UAPI" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>
         <air:AirItinerary>
            <air:AirSegment Key="'.$flight['Key'].'" Group="'.$flight['Group'].'" Carrier="'.$flight['Carrier'].'" FlightNumber="'.$flight['FlightNumber'].'" Origin="'.$flight['Origin'].'" Destination="'.$flight['Destination'].'" DepartureTime="'.$flight['DepartureTime'].'" ArrivalTime="'.$flight['ArrivalTime'].'" FlightTime="'.$flight['FlightTime'].'" TravelTime="'.$flight['TravelTime'].'" ETicketability="'.$flight['ETicketability'].'" Equipment="'.$flight['Equipment'].'" ChangeOfPlane="'.$flight['ChangeOfPlane'].'" ParticipantLevel="'.$flight['ParticipantLevel'].'" LinkAvailability="'.$flight['LinkAvailability'].'" PolledAvailabilityOption="'.$flight['PolledAvailabilityOption'].'" OptionalServicesIndicator="'.$flight['OptionalServicesIndicator'].'" AvailabilitySource="'.$flight['AvailabilitySource'].'" ProviderCode="'.$Provider.'">
               <air:FlightDetails Key="'.$flight['airFlightDetailsRef'].'" Origin="'.$flight['Origin'].'" Destination="'.$flight['Destination'].'" DepartureTime="'.$flight['DepartureTime'].'" ArrivalTime="'.$flight['ArrivalTime'].'" FlightTime="'.$flight['FlightTime'].'" TravelTime="'.$flight['TravelTime'].'" Equipment="'.$flight['Equipment'].'"/>
            </air:AirSegment>
         </air:AirItinerary>';
		 foreach($searchdata['passengers'] as $k=>$psgcode){
         $soap .='<com:SearchPassenger BookingTravelerRef="'.base64_encode($k+1).'" Code="'.$psgcode.'" xmlns:com="http://www.travelport.com/schema/common_v26_0"/>';
		 }
	   foreach($flight['BookingCodeInfo'] as $binfo){
	     $soap .='<air:AirPricingCommand CabinClass="'.$binfo['CabinClass'].'"/>';  
	   }
 $soap .='</air:AirPriceReq>
   </soapenv:Body>
</soapenv:Envelope>';
	
	$auth = base64_encode("$CREDENTIALS"); 
	$curl = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
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
	
	//echo "<pre>";
	//print_r($responseArray);
	//die();
	
	$data=array();
	if(isset($responseArray['SOAPBody']['SOAPFault'])){
	$data['faultstring']=$responseArray['SOAPBody']['SOAPFault']['faultstring'];	
	}else{
	$results=$responseArray['SOAPBody']['airAirPriceRsp']['airAirItinerary']['airAirSegment']['@attributes'];
	$data1=array(
		'Key' => $results['Key'],
		'Group' => $results['Group'],
		'Carrier' => $results['Carrier'],
		'FlightNumber' => $results['FlightNumber'],
		'ProviderCode' => $results['ProviderCode'],
		'Origin' => $results['Origin'],
		'Destination' => $results['Destination'],
		'DepartureTime' => $results['DepartureTime'],
		'ArrivalTime' => $results['ArrivalTime'],
		'FlightTime' => $results['FlightTime'],
		'TravelTime' => $results['TravelTime'],
		'Distance' => $results['Distance'],
		'ClassOfService' => $results['ClassOfService'],
		'Equipment' => $results['Equipment'],
		'ChangeOfPlane' => $results['ChangeOfPlane'],
		'OptionalServicesIndicator' => $results['OptionalServicesIndicator'],
		'AvailabilitySource' => $results['AvailabilitySource'],
		'ParticipantLevel' => $results['ParticipantLevel'],
		'LinkAvailability' => $results['LinkAvailability'],
		'PolledAvailabilityOption' =>$results['PolledAvailabilityOption'],
		'AvailabilityDisplayType' => $results['AvailabilityDisplayType']
	);
	$results=$responseArray['SOAPBody']['airAirPriceRsp']['airAirPriceResult']['airAirPricingSolution']['@attributes'];
	$data2=array(
		'TotalPrice' => $results['TotalPrice'],
		'BasePrice' => $results['BasePrice'],
		'ApproximateTotalPrice' => $results['ApproximateTotalPrice'],
		'ApproximateBasePrice' => $results['ApproximateBasePrice'],
		'EquivalentBasePrice' =>  $results['EquivalentBasePrice'],
		'Taxes' => $results['Taxes'],
		'ApproximateTaxes' => $results['ApproximateTaxes']
	);
	$results=$responseArray['SOAPBody']['airAirPriceRsp']['airAirPriceResult']['airAirPricingSolution']['airAirPricingInfo'];
	$data3=array();
	if(empty($results))$results=array();
	foreach($results as $apinfo){
	$temp=array();
		$info=$apinfo['airBookingInfo']['@attributes'];
		$data=array(
		   'BookingCode' => $info['BookingCode'],
		   'CabinClass' => $info['CabinClass']
		);
		$temp['BookingInfo'][]=$data;
		$passengertype=$apinfo['airPassengerType'];
		if(!empty($passengertype) && is_array($passengertype)){
		   if(isset($passengertype['@attributes'])){
			$data=array(
			   'Code' => $passengertype['@attributes']['Code'],
			   'Age' => $passengertype['@attributes']['Age']
			);
			$temp['PassengerType'][]=$data;
		}else{
			foreach($passengertype as $ptype){
				if(isset($ptype['@attributes']['Code'])){
				$data=array(
				   'Code' => $ptype['@attributes']['Code'],
				   'Age' => $ptype['@attributes']['Age']
				);
				$temp['PassengerType'][]=$data;
				}// if
			}
		}// else
		}// !empty
	$data3['AirPricingInfo'][]=$temp;	
	}
	
	
	$data=$data1+$data2+ $data3; 
	}
	$outboundnonstopprice[]=$data;
	}
	
	$outboundairprice['nonstop']=$outboundnonstopprice;
	
	//echo "<pre>";
	//print_r($outboundairprice);
	//die();
	foreach($outboundairprice['nonstop'] as $f){
		if(isset($f['faultstring']))continue;
		$data=array(
		    'Key' => $f['Key'],
			'Group' => $f['Group'],
			'Carrier' => $f['Carrier'],
			'FlightNumber' => $f['FlightNumber'],
			'ProviderCode' => $f['ProviderCode'],
			'Origin' => $f['Origin'],
			'Destination' => $f['Destination'],
			'DepartureTime' => $f['DepartureTime'],
			'ArrivalTime' => $f['ArrivalTime'],
			'FlightTime' => $f['FlightTime'],
			'TravelTime' => $f['TravelTime'],
			'Distance' => $f['Distance'],
			'ClassOfService' => $f['ClassOfService'],
			'Equipment' => $f['Equipment'],
			'ChangeOfPlane' => $f['ChangeOfPlane'],
			'OptionalServicesIndicator' => $f['OptionalServicesIndicator'],
			'AvailabilitySource' => $f['AvailabilitySource'],
			'ParticipantLevel' => $f['ParticipantLevel'],
			'LinkAvailability' => $f['LinkAvailability'],
			'PolledAvailabilityOption' => $f['PolledAvailabilityOption'],
			'AvailabilityDisplayType' => $f['AvailabilityDisplayType'],
			'TotalPrice' => $f['TotalPrice'],
			'BasePrice' => $f['BasePrice'],
			'ApproximateTotalPrice' => $f['ApproximateTotalPrice'],
			'ApproximateBasePrice' => $f['ApproximateBasePrice'],
			'EquivalentBasePrice' => $f['EquivalentBasePrice'],
			'Taxes' => $f['Taxes'],
			'ApproximateTaxes' => $f['ApproximateTaxes']
		);
		$flightsresult[]=$data;
	}
}*/

//echo "<pre>";
//print_r($flightsresult);
//die();						
get_header();

    $sidebar_id=apply_filters('st_blog_sidebar_id','blog');
?>
    <div class="container">
        <h1 class="page-title"><?php //the_title()?></h1>
        <div class="row mb20">
            <?php $sidebar_pos=apply_filters('st_blog_sidebar','right');
            if($sidebar_pos=="left"){
                get_sidebar('blog');
            }
            ?>
            <div class="<?php echo apply_filters('st_blog_sidebar','right')=='no'?'col-sm-12':'col-sm-9'; ?>">
                <?php while(have_posts()){
                    the_post();
                    ?>
                    <div <?php post_class()?>>
                        <div class="entry-content">
                            <?php
                            //the_content();
                            ?>
                            <div class="row">
                            <div class="col-sm-3"  id="filter-area">
                                <form>
                                    <div class="left-area-book-heading">
                                        <h1>Modify Search <span></span></h1>
                                    </div>
                                    <?php /*?><div class="left-area-book">
                                        <h2>Price</h2>
                                        <div class="prz-range">
                                            <label for="amount">Price range:</label>
                                            <input type="text" id="amount" readonly style="border:0; color:#1FACE0; font-weight:bold;">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div id="slider-range"></div>
                                    </div><?php */?>
                                    <div class="left-area-book">
                                        <h2>Origin</h2>
                                        <div><input type="text" name="Origin-Place" id="id-org-pls" class="cls-org-pls" value="Kolkata" /></div>
                                    </div>
                                    <div class="left-area-book">
                                        <h2>Destination</h2>
                                        <div><input type="text" name="Destination-Place" id="id-org-pls" class="cls-org-pls" value="Delhi" /></div>
                                    </div>
                                    <div class="left-area-book">
                                        <h2>Stops</h2>
                                        <div><input type="checkbox" name="Stops" id="id-stps" class="cls-stps" /> 0</div>
                                        <div><input type="checkbox" name="Stops" id="id-stps" class="cls-stps" /> 1</div>
                                    </div>
                                    <div class="left-area-book">
                                        <h2>Airlines</h2>
                                        <div><input type="checkbox" name="Airlines" id="id-airlines" class="cls-airlines" /> Air India</div>
                                        <div><input type="checkbox" name="Airlines" id="id-airlines" class="cls-airlines" /> IndiGo</div>
                                        <div><input type="checkbox" name="Airlines" id="id-airlines" class="cls-airlines" /> Jet Airways</div>
                                        <div><input type="checkbox" name="Airlines" id="id-airlines" class="cls-airlines" /> SpiceJet</div>
                                    </div>
                                    <?php /*?><div class="left-area-book">
                                        <h2>Departure Date & Time</h2>
                                        <div><input type="text" name="Departure-Date" id="datepicker" class="cls-date" placeholder="mm/dd/yyyy" /></div>
                                        <div><input type="radio" name="Departure-Time" id="id-time" class="cls-time" /> Any Time</div>
                                        <div><input type="radio" name="Departure-Time" id="id-time" class="cls-time" /> Morning (04:00 AM - 11:00 AM)</div>
                                        <div><input type="radio" name="Departure-Time" id="id-time" class="cls-time" /> Afternoon (11:00 AM - 04:00 PM)</div>
                                        <div><input type="radio" name="Departure-Time" id="id-time" class="cls-time" /> Evening (04:00 PM - 09:00 PM)</div>
                                        <div><input type="radio" name="Departure-Time" id="id-time" class="cls-time" /> Night (09:00 PM - 04:00 AM)</div>
                                    </div><?php */?>
                                </form>
                            </div>
                            <div class="col-sm-9">
                                <div class="left-area-book-heading">
                                    <h1>Review Flight</h1>
                                </div>
                                <div class="flt-list-price" id="lowestprice_list">
                                    <div class="flt-area bkg">
                                        <div class="flt-area-fst">All Airlines</div>
                                        <div class="flt-area-lst">Lowest Fare</div>
                                    </div>
                                    <div class="flt-area">
                                        <div class="flt-area-fst"><img src="<?php echo  get_template_directory_uri();?>/images/9W.jpg" /> - 9w</div>
                                        <div class="flt-area-lst">INR 4308</div>
                                    </div>
                                    <div class="flt-area">
                                        <div class="flt-area-fst"><img src="<?php echo  get_template_directory_uri();?>/images/9W.jpg" /> - 9w</div>
                                        <div class="flt-area-lst">INR 4308</div>
                                    </div>
                                    <div class="flt-area">
                                        <div class="flt-area-fst"><img src="<?php echo  get_template_directory_uri();?>/images/9W.jpg" /> - 9w</div>
                                        <div class="flt-area-lst">INR 4308</div>
                                    </div>
                                    <div class="flt-area">
                                        <div class="flt-area-fst"><img src="<?php echo  get_template_directory_uri();?>/images/9W.jpg" /> - 9w</div>
                                        <div class="flt-area-lst">INR 4308</div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="flt-dtl" id="flt-dtl"><br /><br /><br /><br /><br />
                                <center><img src="<?php echo  get_template_directory_uri();?>/images/ajax-loader.gif" width="66" height="66"></center>
                                    <?php /*?><table>
                                        <tr class="hdng">
                                            <td class="arvl">Airlines</td>
                                            <td class="dpt">Departure</td>
                                            <td class="arvl">Arrival</td>
                                            <td class="drsn">Stop</td>
                                            <td class="rfdbl">Price</td>
                                            <td class="prz"></td>
                                        </tr>
                                       <?php foreach($flightsresult as $f){?> 
                                        <tr class="dtl-lst">
                                            <td class="arvl"><img src="<?php echo  get_template_directory_uri();?>/images/9W.jpg" /><br /><?php echo $f['Carrier'];?><br /><?php echo $f['FlightNumber'];?></td>
                                            <td class="dpt"><?php echo $f['DepartureTime'];?> <br /><?php echo $f['Origin'];?></td>
                                            <td class="arvl"><?php echo $f['ArrivalTime'];?> <br /><?php echo $f['Destination'];?></td>
                                            <td class="drsn">0 Stop</td>
                                            <td class="rfdbl"><?php echo $f['TotalPrice'];?></td>
                                            <td class="prz"><button>Book</button></td>
                                        </tr>
                                        <?php } ?>
                                    </table><?php */?>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="entry-meta">
                            <?php
                            wp_link_pages( );
                           // edit_post_link(st_get_language('edit_this_page'), '<p>', '</p>');
                            ?>
                        </div>
                    </div>
                <?php
                }?>
            </div>
            <?php
            if($sidebar_pos=="right"){
                get_sidebar('blog');
            }
            ?>
        </div>
    </div>
<?php /*?><link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="<?php echo  get_template_directory_uri();?>/js/jquery-ui.js"></script>
<script>
	jQuery(function() {
		jQuery( "#datepicker" ).datepicker();
				
		jQuery( "#slider-range" ).slider({
		  range: true,
		  min: 4308,
		  max: 27958,
		  values: [ 4308, 27958 ],
		  slide: function( event, ui ) {
			jQuery( "#amount" ).val( "Rs " + ui.values[ 0 ] + " - " + ui.values[ 1 ] );
		  }
		});
		jQuery( "#amount" ).val( "Rs " + jQuery( "#slider-range" ).slider( "values", 0 ) +
		  " - " + jQuery( "#slider-range" ).slider( "values", 1 ) );
	});
</script><?php */?>
<script>
function Loadresult(dt){ 
jQuery.ajax({
	type: 'post',
	dataType:'json',
	data:{dt:dt},
	//url: 'http://clickmybooking.com/search_result.php',
	url: 'http://localhost:8080/travel/search_result.php',
	chache: false,
	success: function(json) { //alert(json);
		jQuery('#filter-area').html(json['filter']);
		jQuery('#flt-dtl').html(json['flights']);
		jQuery('#lowestprice_list').html(json['lowestprice_list']);
	}
});	
}
Loadresult('<?php echo $dt;?>');
</script>
<?php
get_footer();