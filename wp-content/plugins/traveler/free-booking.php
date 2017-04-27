<?php

/*
Template Name: Dharshan
*/
/*
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
include PLUG_DIR.'/models/OnewayFlight.php';
global $wpdb; 

# SET END POINT
//$endpoint='https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService'; // Production end point
//$endpoint='https://twsprofiler.travelport.com/Service/Default.ashx/AirService'; // Profiler end point

$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService'; // LIVE end point


$bookingdata='';
$bookingdataoriginal='';
if(isset($_POST['bookingdata'])){
$bookingdata=$_POST['bookingdata'];	
$bookingdataoriginal=$bookingdata;
$bookingdata=base64_decode($bookingdata);
$bookingdata=unserialize($bookingdata);	
}else if(isset($_SESSION['bookingdata'])){
$bookingdata=$_SESSION['bookingdata'];	
$bookingdataoriginal=$bookingdata;
$bookingdata=base64_decode($bookingdata);
$bookingdata=unserialize($bookingdata);		
}

$searchdata='';
$searchdataoriginal='';
if(isset($_POST['searchdata'])){
$searchdata=$_POST['searchdata'];	
$searchdataoriginal=$searchdata;
$searchdata=base64_decode($searchdata);
$searchdata=unserialize($searchdata);	
}else if(isset($_SESSION['searchdata'])){
$searchdata=$_SESSION['searchdata'];	
$searchdataoriginal=$searchdata;
$searchdata=base64_decode($searchdata);
$searchdata=unserialize($searchdata);	

}
$useremail='';
$userid=0;
if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	//echo 'Personal Message For ' . $current_user->user_firstname . '!';
	if(isset($_SESSION['redirectoccurin'])){
		$_SESSION['redirectoccurin']='';
		unset($_SESSION['redirectoccurin']);
	}
	$useremail=$current_user->user_email;
	$userid=$current_user->ID;
}else {
        /* $_SESSION['bookingdata']=$bookingdataoriginal;
        $_SESSION['searchdata']=$searchdataoriginal;
		$_SESSION['redirectoccurin']='free-booking';
		wp_redirect( home_url("/login") ); exit; */
}


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

if(isset($_SESSION['booking_response']) && 1==2){
//if(isset($_SESSION['booking_response'])){
	$responseArray=$_SESSION['booking_response'];
}else{

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

$soap = '';
$soap = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header/>
   <soapenv:Body>
      <air:AirPriceReq TargetBranch="'.$TARGETBRANCH.'"  xmlns:air="http://www.travelport.com/schema/air_v32_0" xmlns:com="http://www.travelport.com/schema/common_v32_0" CheckOBFees="true" AuthorizedBy="User" TraceId="trace" >
  <BillingPointOfSaleInfo OriginApplication="UAPI" xmlns="http://www.travelport.com/schema/common_v32_0"/>
  <air:AirItinerary>';

$cnt = 0;


error_log("free booking --->".print_r($bookingdata->flightDetails,true) );



foreach($bookingdata->flightDetails as $b){
if($b['LinkAvailability']=='' || $b['ParticipantLevel']=='' || $b['PolledAvailabilityOption']==''){ 
  
   $soap .= '<air:AirSegment  Key="'.$b['Key'].'" CabinClass="'.$bookingdata['CabinClass'][$k].'" Group="'.$b['Group'].'" Carrier="'.$b['Carrier'].'" FlightNumber="'.$b['FlightNumber'].'" Origin="'.$b['Origin'].'" Destination="'.$b['Destination'].'" DepartureTime="'.$b['DepartureTime'].'" ArrivalTime="'.$b['ArrivalTime'].'" FlightTime="'.$b['FlightTime'].'" Distance="'.$b['Distance'].'"  ETicketability="'.$b['ETicketability'].'" Equipment="'.$b['Equipment'].'" ChangeOfPlane="'.$b['ChangeOfPlane'].'"  OptionalServicesIndicator="'.$b['OptionalServicesIndicator'].'" AvailabilitySource="'.$b['AvailabilitySource'].'"  AvailabilityDisplayType="'.$b['AvailabilityDisplayType'].'" ProviderCode="'.$Provider.'" >
   <air:FlightDetails Key="'.$b['airFlightDetailsRef'].'" Origin="'.$b['Origin'].'" Destination="'.$b['Destination'].'" DepartureTime="'.$b['DepartureTime'].'" ArrivalTime="'.$b['ArrivalTime'].'" FlightTime="'.$b['FlightTime'].'" Equipment="'.$b['Equipment'].'"/>
';

  }else{
	  
   $soap .= '<air:AirSegment  Key="'.$b['Key'].'" CabinClass="'.$b[0]['CabinClass']
           .'" Group="'.$b['Group'].'" Carrier="'.$b['Carrier'].'" FlightNumber="'.$b['FlightNumber'].'" Origin="'.$b['Origin'].'" Destination="'.$b['Destination'].'" DepartureTime="'.$b['DepartureTime'].'" ArrivalTime="'.$b['ArrivalTime'].'" FlightTime="'.$b['FlightTime'].'" Distance="'.$b['Distance'].'"  ETicketability="'.$b['ETicketability'].'" Equipment="'.$b['Equipment'].'" ChangeOfPlane="'.$b['ChangeOfPlane'].'" ParticipantLevel="'.$b['ParticipantLevel'].'" LinkAvailability="'.$b['LinkAvailability'].'" PolledAvailabilityOption="'.$b['PolledAvailabilityOption'].'" OptionalServicesIndicator="'.$b['OptionalServicesIndicator'].'" AvailabilitySource="'.$b['AvailabilitySource'].'"  AvailabilityDisplayType="'.$b['AvailabilityDisplayType'].'" 
		   ProviderCode="'.$Provider.'" >
   <air:FlightDetails Key="'.$b['airFlightDetailsRef'].'" Origin="'.$b['Origin'].'" Destination="'.$b['Destination'].'" DepartureTime="'.$b['DepartureTime'].'" ArrivalTime="'.$b['ArrivalTime'].'" FlightTime="'.$b['FlightTime'].'" Equipment="'.$b['Equipment'].'"/>
';
}


 if(count($bookingdata['segmentdtls']) == 2 || count($bookingdata['segmentdtls']) == 1 ){    //  For  Direct  Flight  ( oneway & roundtrip )
				 $soap .= '</air:AirSegment>';
				 $cnt++;
  }elseif(count($bookingdata['segmentdtls']) == 3 && ($cnt == 2 || $cnt == 0)){    //  For 1 Stop, Direct  OR  Direct, 1 Stop
				 $soap .= '</air:AirSegment>';
				 $cnt++;
  }elseif(is_array($bookingdata['Connection'][$cnt][0])){
	  foreach($bookingdata['Connection'][$cnt][0] as $c)
	  {
			if(isset($c) && $cnt % 2 == 0)
			{
				$soap .= '<air:Connection />';
				$soap .= '</air:AirSegment>';
				$cnt++;
			}else{
				 $soap .= '</air:AirSegment>';
				 $cnt++;
			}
	  } 
  }elseif(is_array($bookingdata['Connection'][$cnt])){
	  foreach($bookingdata['Connection'][$cnt] as $c)
	  {
			if(isset($c) && $cnt % 2 == 0)
			{
				$soap .= '<air:Connection />';
				$soap .= '</air:AirSegment>';
				$cnt++;
			}else{
				 $soap .= '</air:AirSegment>';
				 $cnt++;
			}
	  } 
  }else{
				 $soap .= '</air:AirSegment>';
				 $cnt++;
  }
} // end foreach AirSegment
$soap .= '</air:AirItinerary>
  		   <air:AirPricingModifiers FaresIndicator="PublicAndPrivateFares" />';

 foreach($bookingdata['passengers'][0] as $k=>$psgcode){

	 if($psgcode == 'ADT')
	 {
		 $Key = 1;
		 $soap .= '<com:SearchPassenger  BookingTravelerRef= "'.md5($k+1).'" Code="'.$psgcode.'" Key="'.$Key.'"  />';
	 }else if($psgcode == 'CNN'){
		 $Key = 2;
		 $Age = 8;
		 $soap .= '<com:SearchPassenger  BookingTravelerRef= "'.md5($k+1).'" Code="'.$psgcode.'" Key="'.$Key.'"  Age="'.$Age.'" />';
	 }else if($psgcode == 'INF'){
		 $Key = 3;
		 $Age = 1;
		 $soap .= '<com:SearchPassenger BookingTravelerRef= "'.md5($k+1).'" PricePTCOnly="true" Code="'.$psgcode.'" Key="'.$Key.'"  Age="'.$Age.'" />';
	 }
	 

 }

 //$soap .='<air:AirPricingCommand  />';
  $soap .='<air:AirPricingCommand>';
  foreach($bookingdata['segmentdtls'] as $k=>$b){
	$soap .='<air:AirSegmentPricingModifiers AirSegmentRef="'.$b['Key'].'">
      <air:PermittedBookingCodes>
        <air:BookingCode Code="'.$bookingdata['BookingCode'][$k] .'" />
      </air:PermittedBookingCodes>
    </air:AirSegmentPricingModifiers>'; 
  }
  $soap .='</air:AirPricingCommand>';
  
 $soap .= '</air:AirPriceReq>
   </soapenv:Body>
</soapenv:Envelope>';

 error_log($soap);
 
    $auth = base64_encode("$CREDENTIALS"); 
	$curl = curl_init ($endpoint);
	
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
	
	$_SESSION['booking_response']=$responseArray;
}

//echo "<pre>";
//print_r($responseArray);
//die();
	
	$data=array();
	if(isset($responseArray['SOAPBody']['SOAPFault'])){
	$data['faultstring']=$responseArray['SOAPBody']['SOAPFault']['faultstring'];
	echo "<pre>";
	print_r($responseArray);
	    die($data['faultstring']);	
	}else{
	// AirSegment
	$AirSegment=$responseArray['SOAPBody']['airAirPriceRsp']['airAirItinerary']['airAirSegment'];
    $airsegments=array();
	if(isset($AirSegment['@attributes']['Key'])){
		$data1=array(
			'Key' => $AirSegment['@attributes']['Key'],
			'Group' => $AirSegment['@attributes']['Group'],
			'Carrier' => $AirSegment['@attributes']['Carrier'],
			'FlightNumber' => $AirSegment['@attributes']['FlightNumber'],
			'ProviderCode' => $AirSegment['@attributes']['ProviderCode'],
			'Origin' => $AirSegment['@attributes']['Origin'],
			'Destination' => $AirSegment['@attributes']['Destination'],
			'DepartureTime' => $AirSegment['@attributes']['DepartureTime'],
			'ArrivalTime' => $AirSegment['@attributes']['ArrivalTime'],
			'FlightTime' => $AirSegment['@attributes']['FlightTime'],
			'TravelTime' => $AirSegment['@attributes']['TravelTime'],
			'Distance' => $AirSegment['@attributes']['Distance'],
			'ClassOfService' => $AirSegment['@attributes']['ClassOfService'],
			'Equipment' => $AirSegment['@attributes']['Equipment'],
			'ChangeOfPlane' => $AirSegment['@attributes']['ChangeOfPlane'],
			'OptionalServicesIndicator' => $AirSegment['@attributes']['OptionalServicesIndicator'],
			'AvailabilitySource' => $AirSegment['@attributes']['AvailabilitySource'],
			'ParticipantLevel' => $AirSegment['@attributes']['ParticipantLevel'],
			'LinkAvailability' => $AirSegment['@attributes']['LinkAvailability'],
			'PolledAvailabilityOption' =>$AirSegment['@attributes']['PolledAvailabilityOption'],
			'AvailabilityDisplayType' => $AirSegment['@attributes']['AvailabilityDisplayType']
		);
		 if(isset($AirSegment['@attributes']['airCodeshareInfo'])){
		     $data1['airCodeshareInfo'] = $AirSegment['@attributes']['airCodeshareInfo'];
		 }else{
			  $data1['airCodeshareInfo'] = '';
		 }
		 
		 if(isset($AirSegment['@attributes']['airAirAvailInfo'][0]['@attributes']['BookingCounts'])){
			  $data1['BookingCounts']=$AirSegment['@attributes']['airAirAvailInfo'][0]['@attributes']['BookingCounts'];
		 }
		 if(isset($AirSegment['@attributes']['airFlightDetailsRef']['@attributes'])){
			  $data1['airFlightDetailsRef']=$AirSegment['@attributes']['airFlightDetailsRef']['@attributes']['Key'];
		 }
		$airsegments[]=$data1;
	}else if(isset($AirSegment[0]['@attributes']['Key'])){
		foreach($AirSegment as $aseg){
		   $data1=array(
				'Key' => $aseg['@attributes']['Key'],
				'Group' => $aseg['@attributes']['Group'],
				'Carrier' => $aseg['@attributes']['Carrier'],
				'FlightNumber' => $aseg['@attributes']['FlightNumber'],
				'ProviderCode' => $aseg['@attributes']['ProviderCode'],
				'Origin' => $aseg['@attributes']['Origin'],
				'Destination' => $aseg['@attributes']['Destination'],
				'DepartureTime' => $aseg['@attributes']['DepartureTime'],
				'ArrivalTime' => $aseg['@attributes']['ArrivalTime'],
				'FlightTime' => $aseg['@attributes']['FlightTime'],
				'TravelTime' => $aseg['@attributes']['TravelTime'],
				'Distance' => $aseg['@attributes']['Distance'],
				'ClassOfService' => $aseg['@attributes']['ClassOfService'],
				'Equipment' => $aseg['@attributes']['Equipment'],
				'ChangeOfPlane' => $aseg['@attributes']['ChangeOfPlane'],
				'OptionalServicesIndicator' => $aseg['@attributes']['OptionalServicesIndicator'],
				'AvailabilitySource' => $aseg['@attributes']['AvailabilitySource'],
				'ParticipantLevel' => $aseg['@attributes']['ParticipantLevel'],
				'LinkAvailability' => $aseg['@attributes']['LinkAvailability'],
				'PolledAvailabilityOption' =>$aseg['@attributes']['PolledAvailabilityOption'],
				'AvailabilityDisplayType' => $aseg['@attributes']['AvailabilityDisplayType']
			);
			 if(isset($aseg['@attributes']['airCodeshareInfo'])){
				 $data1['airCodeshareInfo'] = $aseg['@attributes']['airCodeshareInfo'];
			 }else{
				  $data1['airCodeshareInfo'] = '';
			 }
			 
			 if(isset($aseg['@attributes']['airAirAvailInfo'][0]['@attributes']['BookingCounts'])){
				  $data1['BookingCounts']=$aseg['@attributes']['airAirAvailInfo'][0]['@attributes']['BookingCounts'];
			 }
			 if(isset($aseg['@attributes']['airFlightDetailsRef']['@attributes'])){
				  $data1['airFlightDetailsRef']=$aseg['@attributes']['airFlightDetailsRef']['@attributes']['Key'];
			 }
			$airsegments[]=$data1;
		}
	}
	$data['AirSegment']=$airsegments;
	
	$AirPricingSolution=$responseArray['SOAPBody']['airAirPriceRsp']['airAirPriceResult']['airAirPricingSolution'];
	//echo "<pre>";
//	print_r($AirPricingSolution);
//	die();
	$data2=array(
		'TotalPrice' => $AirPricingSolution['@attributes']['TotalPrice'],
		'BasePrice' => $AirPricingSolution['@attributes']['BasePrice'],
		'ApproximateTotalPrice' => $AirPricingSolution['@attributes']['ApproximateTotalPrice'],
		'ApproximateBasePrice' => $AirPricingSolution['@attributes']['ApproximateBasePrice'],
		'EquivalentBasePrice' =>  $AirPricingSolution['@attributes']['EquivalentBasePrice'],
		'Taxes' => $AirPricingSolution['@attributes']['Taxes'],
		'ApproximateTaxes' => $AirPricingSolution['@attributes']['ApproximateTaxes'],
		'QuoteDate' => $AirPricingSolution['@attributes']['QuoteDate']
	);
	$data['AirPricingSolution']=$data2;
	
	$AirPricingInfo=array();
	if(isset($AirPricingSolution['airAirPricingInfo'])){
	$AirPricingInfo=$AirPricingSolution['airAirPricingInfo'];
	}
	$airpricinginfo=array();
	if(isset($AirPricingInfo['@attributes']['Key'])){
		$data3=array(
		    'Key'=> $AirPricingInfo['@attributes']['Key'],
			'TotalPrice'=> $AirPricingInfo['@attributes']['TotalPrice'],
			'BasePrice'=> $AirPricingInfo['@attributes']['BasePrice'],
			'ApproximateTotalPrice'=> $AirPricingInfo['@attributes']['ApproximateTotalPrice'],
			'ApproximateBasePrice'=> $AirPricingInfo['@attributes']['ApproximateBasePrice'],
			'EquivalentBasePrice'=>$AirPricingInfo['@attributes']['EquivalentBasePrice'],
			'ApproximateTaxes'=> $AirPricingInfo['@attributes']['ApproximateTaxes'],
			'Taxes'=> $AirPricingInfo['@attributes']['Taxes'],
			'LatestTicketingTime'=> $AirPricingInfo['@attributes']['LatestTicketingTime'],
			'PricingMethod'=> $AirPricingInfo['@attributes']['PricingMethod'],
			'Refundable'=> $AirPricingInfo['@attributes']['Refundable'],
			'ETicketability'=> $AirPricingInfo['@attributes']['ETicketability'],
			'PlatingCarrier'=> $AirPricingInfo['@attributes']['PlatingCarrier'],
			'ProviderCode'=> $AirPricingInfo['@attributes']['ProviderCode']
		);
		if(isset($AirPricingInfo['@attributes']['IncludesVAT'])){
			$data3['IncludesVAT']=$AirPricingInfo['@attributes']['IncludesVAT'];
		}
		$FareInfo=$AirPricingInfo['airFareInfo'];
		$fairinfo=processFairInfo($FareInfo);
		$data3['FareInfo']=$fairinfo;
		
		$BookingInfo=$AirPricingInfo['airBookingInfo'];
		$bookinginfo=processBookingInfo($BookingInfo);
		$data3['BookingInfo']=$bookinginfo;
		
		$TaxInfo=$AirPricingInfo['airTaxInfo'];
		$taxinfo=processTaxInfo($TaxInfo);
		$data3['TaxInfo']=$taxinfo;
		
		$data3['FareCalc']=$AirPricingInfo['airFareCalc'];
		
		if(isset($AirPricingInfo['airChangePenalty'])){
			$data3['ChangePenalty']=array('Amount'=>$AirPricingInfo['airChangePenalty']['airAmount']);
		}
		
		$PassengerType=$AirPricingInfo['airPassengerType'];
		$ptypedata=processPassengerType($PassengerType);
		$data3['PassengerType']=$ptypedata;
		
		$BaggageAllowances=$AirPricingInfo['airBaggageAllowances'];
		$baggageRestriction=processBaggageRestriction($BaggageAllowances);
		$data3['BaggageRestriction']=$baggageRestriction;
		
		$airpricinginfo[]=$data3;
		
	}else{
	foreach($AirPricingInfo as $apinfo){
	$data3=array();
	    $data3=array(
		    'Key'=> $apinfo['@attributes']['Key'],
			'TotalPrice'=> $apinfo['@attributes']['TotalPrice'],
			'BasePrice'=> $apinfo['@attributes']['BasePrice'],
			'ApproximateTotalPrice'=> $apinfo['@attributes']['ApproximateTotalPrice'],
			'ApproximateBasePrice'=> $apinfo['@attributes']['ApproximateBasePrice'],
			'EquivalentBasePrice'=>$apinfo['@attributes']['EquivalentBasePrice'],
			'ApproximateTaxes'=> $apinfo['@attributes']['ApproximateTaxes'],
			'Taxes'=> $apinfo['@attributes']['Taxes'],
			'LatestTicketingTime'=> $apinfo['@attributes']['LatestTicketingTime'],
			'PricingMethod'=> $apinfo['@attributes']['PricingMethod'],
			'Refundable'=> $apinfo['@attributes']['Refundable'],
			'ETicketability'=> $apinfo['@attributes']['ETicketability'],
			'PlatingCarrier'=> $apinfo['@attributes']['PlatingCarrier'],
			'ProviderCode'=> $apinfo['@attributes']['ProviderCode']
		);
		if(isset($apinfo['@attributes']['IncludesVAT'])){
			$data3['IncludesVAT']=$apinfo['@attributes']['IncludesVAT'];
		}
		
		$FareInfo=$apinfo['airFareInfo'];
		$fairinfo=processFairInfo($FareInfo);
		
		$data3['FareInfo']=$fairinfo;
		
		$BookingInfo=$apinfo['airBookingInfo'];
		$bookinginfo=processBookingInfo($BookingInfo);
		$data3['BookingInfo']=$bookinginfo;
		
		$TaxInfo=$apinfo['airTaxInfo'];
		$taxinfo=processTaxInfo($TaxInfo);
		$data3['TaxInfo']=$taxinfo;
		
		$data3['FareCalc']=$apinfo['airFareCalc'];
		
		if(isset($apinfo['airChangePenalty'])){
			$data3['ChangePenalty']=array('Amount'=>$apinfo['airChangePenalty']['airAmount']);
		}
		
		$PassengerType=$apinfo['airPassengerType'];
		$ptypedata=processPassengerType($PassengerType);
		$data3['PassengerType']=$ptypedata;
		
		$BaggageAllowances=$apinfo['airBaggageAllowances'];
		$baggageRestriction=processBaggageRestriction($BaggageAllowances);
		$data3['BaggageRestriction']=$baggageRestriction;
		
		$airpricinginfo[]=$data3;
    }
	}//else
    $data['AirPricingSolution']['AirPricingInfo']=$airpricinginfo;
	
	$FareNote=array();
	if(isset($FareNote)){
	  $FareNote=$AirPricingSolution['airFareNote'];
	}
	$data['AirPricingSolution']['FareNote']=$FareNote;
	
    //echo "<pre>";
   // print_r($data);
	
	}

//echo "<pre>";
//print_r($data['AirPricingSolution']);
//die();
$_SESSION['PARSEDDATA']=$data;
$totalprice=$data['AirPricingSolution']['TotalPrice'];
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
//echo "<pre>";
//print_r($data);
//die();

$ptypefull=array('ADT'=>'Adult','CNN'=>'Child','INF'=>'Infant');

// Discount should be calculate only from the Fare.( Not from total fare (fare+Tax))
// Dec 23 2016
$farePrice=0;

$pdatadtl=array();
foreach($data['AirPricingSolution']['AirPricingInfo'] as $aps){
	foreach($aps['PassengerType'] as $p){
		if(!isset($pdatadtl[$p['Code']])){
			$currency=substr($aps['ApproximateBasePrice'],0,3);
            $amnt=substr($aps['ApproximateBasePrice'],3);
			$currencytax=substr($aps['ApproximateTaxes'],0,3);
            $tax=substr($aps['ApproximateTaxes'],3);
			$pdatadtl[$p['Code']]=array('head'=>1,'currency'=>$currency,'amount'=>number_format($amnt),'tax'=>number_format($tax),'totalamount'=>number_format($amnt),'fprice'=>$amnt,'totaltax'=>number_format($tax),'ptype'=>$ptypefull[$p['Code']]);
		}else if(isset($pdatadtl[$p['Code']])){
			$h=$pdatadtl[$p['Code']]['head'] + 1;
			$pdatadtl[$p['Code']]['head']=$h;
			$currency=substr($aps['ApproximateBasePrice'],0,3);
            $amnt=substr($aps['ApproximateBasePrice'],3);
			$currencytax=substr($aps['ApproximateTaxes'],0,3);
            $tax=substr($aps['ApproximateTaxes'],3);
			$pdatadtl[$p['Code']]['currency']=$currency;
			$pdatadtl[$p['Code']]['amount']=number_format($amnt);
			$pdatadtl[$p['Code']]['tax']=number_format($tax);
			$pdatadtl[$p['Code']]['totalamount']=number_format($amnt * $h);
			$pdatadtl[$p['Code']]['fprice']=$amnt * $h;
			$pdatadtl[$p['Code']]['totaltax']=number_format($tax * $h);
			$pdatadtl[$p['Code']]['ptype']=$ptypefull[$p['Code']]."s";
		}
	}
}

//echo $farePrice;
//echo "<pre>";
//print_r($pdatadtl);
//die();
$fpricearr=$pdatadtl;
foreach($fpricearr as $fp){
	$farePrice= $farePrice + $fp['fprice'];
}

$priceresult=array();
$AirSegment=$data['AirSegment'];
if(isset($data['AirPricingSolution']['AirPricingInfo'][0])){
	$priceresult=$data['AirPricingSolution']['AirPricingInfo'][0];
}

$fromcity='';
$tocity='';
$fromairport='';

if(isset($searchdata['from_city'])){
$fromcity=$searchdata['from_city'];	
}
if(isset($searchdata['from_city'][0]) && is_array($searchdata['from_city'])){
$fromcity=$searchdata['from_city'][0];		
}

if(isset($searchdata['to_city'])){
$tocity=$searchdata['to_city'];	
}
if(isset($searchdata['to_city'][0]) && is_array($searchdata['to_city'])){
$tocity=end($searchdata['to_city']);		
}

if(isset($searchdata['from_airport'])){
$fromairport=$searchdata['from_airport'];	
}

if(isset($searchdata['from_airport'][0]) && is_array($searchdata['from_airport'])){
$fromairport=$searchdata['from_airport'][0];		
}

//echo "<pre>";
//print_r($bookingdata);
//die();

get_header();
if(count($priceresult)){
$FareInfoRef=$priceresult['FareInfo']['0']['Key'];
$FareRuleKey=$priceresult['FareInfo']['0']['FareRuleKey'];
$BaggageRestriction=$priceresult['BaggageRestriction'];

$EquivalentBasePrice=$priceresult['EquivalentBasePrice'];

// I suggest that when it is initiating from CMB to use BasePrice and when it is other than CMB to use ApproximateBasePrice  April 01 2016
if($fromairport=='CMB'){
	//$EquivalentBasePrice=$priceresult['BasePrice'];
	$EquivalentBasePrice=$data['AirPricingSolution']['BasePrice'];
}else{
	//$EquivalentBasePrice=$priceresult['ApproximateBasePrice'];	
	$EquivalentBasePrice=$data['AirPricingSolution']['ApproximateBasePrice'];
}

$currency=substr($EquivalentBasePrice,0,3);
$EquivalentBasePrice=substr($EquivalentBasePrice,3);
$EquivalentBasePrice=$currency."".number_format($EquivalentBasePrice);




//$Taxes=$priceresult['Taxes'];
$Taxes=$data['AirPricingSolution']['Taxes'];
$currency=substr($Taxes,0,3);
$Taxes=substr($Taxes,3);
$Taxes=$currency."".number_format($Taxes);

//$TotalPrice=$priceresult['TotalPrice'];
$TotalPrice=$data['AirPricingSolution']['TotalPrice'];
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
$result = $wpdb->get_results($res);
$discount = $result[0]->discount;
if($discount>0)
{
//$disAmt = (substr($tPrice,3) / 100) * $discount;
$disAmt = ($farePrice / 100) * $discount; // Dec 23, 2016	
}else{
	$disAmt=0;
}

//$netprice=substr($priceresult['TotalPrice'],3) - $disAmt;
$netprice=substr($data['AirPricingSolution']['TotalPrice'],3) - $disAmt;

// agent commission
$myRole =  get_user_role(); // Comming from functions.php
if(isset($myRole) && $myRole == 'agent')
{
	$res = 'SELECT * FROM wp_options WHERE option_name = "agent_commission" ';
	$result = $wpdb->get_results($res);
	$commission = $result[0]->option_value;
	$webfaireamt = (substr($tPrice,3) / 100) * $commission;
	$netprice=$netprice - $webfaireamt;
}
$TOTALPRICEVALUE=$netprice;
$embacyfee=0;
$SQL = 'SELECT * FROM wp_options WHERE option_name = "embacy_letter_fee" ';
$result = $wpdb->get_results($SQL);
$embacy_letter_fee = $result[0]->option_value;

// actually only embacy purpose is paid srvice, others are free :)
$embacyfee=$embacy_letter_fee;
//$netpricewithembacy=$netprice + $embacyfee;
$netpricewithembacy=$embacyfee;  // when embassy purpose ( just want to pay 500.00) no need to add ticket amount)
$TOTALPRICEVALUE=$netpricewithembacy;
$netprice=$currency."".number_format($netprice);

$embacyfee=$currency."".number_format($embacyfee);
$netpricewithembacy=$currency."".number_format($netpricewithembacy);

?>
<div class="sky-bg">
  <div class="pop-edit"></div>
  <div class="container">
  <div class="main_wrapp">
    <div class="booking_panel">
    
      <div class="heading">
        <h3>Booking</h3>
        <h6>Book with simple 5 steps</h6>
      </div>
      <div id="reserved" style="width:100%;">
      <div class="step" id="step1" style="width:100%;">
       <div class="main_content br">
       <div class="booking_wrapp">
        <div class="heading pay">
          <h3><span>1</span>Itinerary</h3>
          <h6>Review your flight details</h6>
          <div class="clearfix"></div>
        </div>
        <div class="drop_select">
        	<span>Reason for Booking : </span>
        	<select name="booking_reason" id="booking_reason">
            	<option value="">Select</option>
            	<option value="Fare Quotation">Fare Quotation</option>
                <option value="Embassy Visa Purpose">Embassy Visa Purpose</option>
                <option value="Pay Later">Pay Later</option>
             </select>
        </div>
        </div>
        <?php if($searchdata['mode']=='roundtrip'){?>
        <div class="row">
          <div class="col-sm-12"> <span class="hd"><strong><?php echo $fromcity;?> to <?php echo $tocity;?></strong></span>
            <div class="first_before">
<?php 
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

$depttimes[$n]=$segmentdtls['DepartureTime'];
$arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
?>          
             <?php 
				if($n){
				$t1=$arrivaltimes[$n-1];	
				$t2=$depttimes[$n];	
				$layover=getTimeDiff($t1,$t2);
			 ?> 
             <div class="intro"><span>Change of Planes | Connection Time : <?php echo $layover;?></span></div>
             <?php }?>
              <div class="first_before_row">
                <ul>
                  <li><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" class="icn" /><span><?php echo $thisairlinename;?> <br />
                    <?php echo $thisCarrier;?> <?php echo $FlightNumber;?> <br />
                    <strong>Aircraft <?php echo $aircraft;?></strong></span></li>
                  <li><span><strong class="big"><?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?></strong> <?php echo date("D, d M",strtotime($segmentdtls['DepartureTime']));?> <br />
                    <strong class="big"><?php echo $airportscity[$segmentdtls['Origin']];?> (<?php echo $segmentdtls['Origin'];?>)</strong></span></li>
                  <li>
                    <center>
                      <img src="<?php echo get_template_directory_uri(); ?>/images/booking_dets_oneway_right.png" /><br />
                      <span>Non-stop</span>
                    </center>
                  </li>
                  <li><span><strong class="big"><?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?></strong> <?php echo date("D, d M",strtotime($segmentdtls['ArrivalTime']));?> <br />
                    <strong class="big"><?php echo $airportscity[$segmentdtls['Destination']];?> (<?php echo $segmentdtls['Destination'];?>)</strong></span></li>
                  <li><span><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></span></li>
                </ul>
                <div class="clearfix"></div>
              </div>
<?php $n++; }} ?>
              <!--end row-->
            </div>
          </div>
        </div>
        <!--row 1 end-->
        <div class="row">
 <div class="col-sm-12"> <span class="hd"><strong><?php echo $tocity;?> to <?php echo $fromcity;?></strong></span>
            <div class="first_before">
 <?php 
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
				if($n){
				$t1=$arrivaltimes[$n-1];	
				$t2=$depttimes[$n];	
				$layover=getTimeDiff($t1,$t2);
			  ?> 
              <div class="intro"><span>Change of Planes | Connection Time : <?php echo $layover;?></span></div>
              <?php } ?>
              <div class="first_before_row">
                <ul>
                  <li><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" class="icn" /><span><?php echo $thisairlinename;?> <br />
                    <?php echo $thisCarrier;?> <?php echo $FlightNumber;?> <br />
                    <strong>Aircraft <?php echo $aircraft;?></strong></span></li>
                  <li><span><strong class="big"><?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?></strong> <?php echo date("D, d M",strtotime($segmentdtls['DepartureTime']));?> <br />
                    <strong class="big"><?php echo $airportscity[$segmentdtls['Origin']];?> (<?php echo $segmentdtls['Origin'];?>)</strong></span></li>
                  <li>
                    <center>
                      <img src="<?php echo get_template_directory_uri(); ?>/images/booking_dets_oneway_right.png" /><br />
                      <span>Non-stop</span>
                    </center>
                  </li>
                  <li><span><strong class="big"><?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?></strong> <?php echo date("D, d M",strtotime($segmentdtls['ArrivalTime']));?> <br />
                    <strong class="big"><?php echo $airportscity[$segmentdtls['Destination']];?> (<?php echo $segmentdtls['Destination'];?>)</strong></span></li>
                  <li><span><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></span></li>
                </ul>
                <div class="clearfix"></div>
              </div>
 <?php $n++;}} ?>
              <!--end row-->         
            </div>
          </div>
        </div>
<?php }else if($searchdata['mode']=='oneway'){?>
       <div class="row">
          <div class="col-sm-12"> <span class="hd"><strong><?php echo $fromcity;?> to <?php echo $tocity;?></strong></span>
            <div class="first_before">
<?php 
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

$depttimes[$n]=$segmentdtls['DepartureTime'];
$arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
?>          
             <?php 
				if($n){
				$t1=$arrivaltimes[$n-1];	
				$t2=$depttimes[$n];	
				$layover=getTimeDiff($t1,$t2);
			 ?> 
             <div class="intro"><span>Change of Planes | Connection Time : <?php echo $layover;?></span></div>
             <?php }?>
              <div class="first_before_row">
                <ul>
                  <li><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" class="icn" /><span><?php echo $thisairlinename;?> <br />
                    <?php echo $thisCarrier;?> <?php echo $FlightNumber;?> <br />
                    <strong>Aircraft <?php echo $aircraft;?></strong></span></li>
                  <li><span><strong class="big"><?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?></strong> <?php echo date("D, d M",strtotime($segmentdtls['DepartureTime']));?> <br />
                    <strong class="big"><?php echo $airportscity[$segmentdtls['Origin']];?> (<?php echo $segmentdtls['Origin'];?>)</strong></span></li>
                  <li>
                    <center>
                      <img src="<?php echo get_template_directory_uri(); ?>/images/booking_dets_oneway_right.png" /><br />
                      <span>Non-stop</span>
                    </center>
                  </li>
                  <li><span><strong class="big"><?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?></strong> <?php echo date("D, d M",strtotime($segmentdtls['ArrivalTime']));?> <br />
                    <strong class="big"><?php echo $airportscity[$segmentdtls['Destination']];?> (<?php echo $segmentdtls['Destination'];?>)</strong></span></li>
                  <li><span><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></span></li>
                </ul>
                <div class="clearfix"></div>
              </div>
<?php $n++; } ?>
              <!--end row-->
            </div>
          </div>
        </div>
        <!--row 1 end-->
<?php }else if($searchdata['mode']=='multicity'){ ?>
<div class="row">
          <div class="col-sm-12"> <span class="hd"><strong><?php echo $fromcity;?> to <?php echo $tocity;?></strong></span>
            <div class="first_before">
<?php 
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

$depttimes[$n]=$segmentdtls['DepartureTime'];
$arrivaltimes[$n]=$segmentdtls['ArrivalTime'];
?>          
             <?php 
				if($n){
				$t1=$arrivaltimes[$n-1];	
				$t2=$depttimes[$n];	
				$layover=getTimeDiff($t1,$t2);
			 ?> 
             <div class="intro"><span>Change of Planes | Connection Time : <?php echo $layover;?></span></div>
             <?php }?>
              <div class="first_before_row">
                <ul>
                  <li><img src="airimages/<?php echo $thisCarrier.'.GIF';?>" class="icn" /><span><?php echo $thisairlinename;?> <br />
                    <?php echo $thisCarrier;?> <?php echo $FlightNumber;?> <br />
                    <strong>Aircraft <?php echo $aircraft;?></strong></span></li>
                  <li><span><strong class="big"><?php echo date("h:i a",strtotime($segmentdtls['DepartureTime']));?></strong> <?php echo date("D, d M",strtotime($segmentdtls['DepartureTime']));?> <br />
                    <strong class="big"><?php echo $airportscity[$segmentdtls['Origin']];?> (<?php echo $segmentdtls['Origin'];?>)</strong></span></li>
                  <li>
                    <center>
                      <img src="<?php echo get_template_directory_uri(); ?>/images/booking_dets_oneway_right.png" /><br />
                      <span>Non-stop</span>
                    </center>
                  </li>
                  <li><span><strong class="big"><?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?></strong> <?php echo date("D, d M",strtotime($segmentdtls['ArrivalTime']));?> <br />
                    <strong class="big"><?php echo $airportscity[$segmentdtls['Destination']];?> (<?php echo $segmentdtls['Destination'];?>)</strong></span></li>
                  <li><span><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></span></li>
                </ul>
                <div class="clearfix"></div>
              </div>
<?php $n++; } ?>
              <!--end row-->
            </div>
          </div>
        </div>
        <!--row 1 end-->
<?php }?>        
        <!--ROW END-->
        <div class="button_panel">
          <div class="row">
            <div class="col-sm-3">
            <form name="frmFL" id="frmFL<?php echo $z?>" method="post" action="farerule.php" target="_blank">
            <input type="hidden" name="k" value="<?php echo $FareRuleKey;?>" />
            <input type="hidden" name="ref" value="<?php echo $FareInfoRef;?>" />
            <ul>
                <li><img src="<?php echo get_template_directory_uri(); ?>/images/booking_op.png" /></li>
                <li><span>Fare Rules <a onclick="jQuery('#frmFL<?php echo $z;?>').submit();">click here</a></span></li>
             </ul>
            </form>
            </div>
            <div class="col-sm-3">
              <ul>
                <li><img src="<?php echo get_template_directory_uri(); ?>/images/booking_op.png" /></li>
                <li><span>Baggage Allowances :<br />
                  <?php echo $BaggageRestriction;?></span></li>
              </ul>
            </div>
            <div class="col-sm-3">
              <ul>
                <li><img src="<?php echo get_template_directory_uri(); ?>/images/booking_op.png" /></li>
              </ul>
            </div>
          </div>
        </div>
        <!--button panel end-->
        
        <div class="col-sm-6">
          <div class="details">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr class="nonembacytotal">
                <td align="left"><span><strong>Base Fare :</strong></span></td>
                <td align="right"><span><strong>  </strong></span></td>
              </tr>
			<?php foreach($pdatadtl as $pd){?>
			  <tr class="nonembacytotal">
                <td align="left"><span><?php echo $pd['head'];?> <?php echo $pd['ptype'];?>(<?php echo $pd['head'];?> x <?php echo $pd['amount'];?>) :</span></td>
                <td align="right"><span> <?php echo $pd['currency'];?> <?php echo $pd['totalamount'];?></span></td>
              </tr>			
			<?php } ?>
            <tr class="nonembacytotal">
                <td align="left"><span><strong>Taxes :</strong></span></td>
                <td align="right"><span><strong>  </strong></span></td>
              </tr>
			<?php foreach($pdatadtl as $pd){?>
			  <tr class="nonembacytotal">
                <td align="left"><span><?php echo $pd['head'];?> <?php echo $pd['ptype'];?>(<?php echo $pd['head'];?> x <?php echo $pd['tax'];?>) :</span></td>
                <td align="right"><span> <?php echo $pd['currency'];?> <?php echo $pd['totaltax'];?></span></td>
              </tr>		
			<?php } ?>
              <tr class="nonembacytotal">
                <td align="left"><span><strong>Total Airfare :</strong></span></td>
                <td align="right"><span><strong><?php echo $TotalPrice;?></strong></span></td>
              </tr>
              <tr class="nonembacytotal">
                <td align="left"><span>Webfare Discount :</span></td>
                <td align="right"><span><strong>
                <?php
                    //echo 'yes';
					$res = 'SELECT discount FROM airlines WHERE airline = "'.$thisairlinename.'" ';
                    $result = $wpdb->get_results($res);
					$discount = $result[0]->discount;
					//echo $tPrice;
					//echo $result[0]->option_value; 
                    if($discount>0)
					{
					//$disAmt = (substr($tPrice,3) / 100) * $discount;
					$disAmt = ($farePrice / 100) * $discount; // Dec 23, 2016
						echo $currency.number_format($disAmt);
					}else{
						echo 0;
					}
				?>
                 </strong></span></td>
              </tr>
			  <tr class="embacytotal" style="display:none;">
                <td align="left"><span>Letter Fee :</span></td>
                <td align="right"><span><?php echo $embacyfee;?></span></td>
              </tr>
			  <tr class="embacytotal" style="display:none;">
                <td align="left"><span><strong class="larg">Total price :</strong></span></td>
                <td align="right"><span><strong><strong><?php echo $netpricewithembacy;?></strong></strong></span></td>
              </tr>
			  <tr class="nonembacytotal">
                <td align="left"><span><strong class="larg">Total price :</strong></span></td>
                <td align="right"><span><strong><strong><?php echo $netprice;?></strong></strong></span></td>
              </tr>
             </table>
          </div>
        </div>
        <div align="right" class="but_section">
          <input name="" type="button" value="Continue Booking" onclick="processstep1();" class="booking_btn" />
        </div>
      </div>
      </div><!--step1 end-->
      <div class="step" id="step1_after"  style="display:none;">
        <div class="booking_wrapp">
        <div class="wrapp">
          <div class="edit" onclick="jQuery('#step1_after').hide();jQuery('#step1').show('slow');editit('step1');"><span>Edit</span></div>
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
$skey=getSegmentKey($segmentref,$AirSegment);
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
                          <img src="<?php echo get_template_directory_uri(); ?>/images/booking_dets_oneway_right.png" /><br />
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
                  <tr  class="nonembacytotal">
                    <td align="left"><span>Base Fare :</span></td>
                    <td align="right"><span> <?php echo $EquivalentBasePrice;?></span></td>
                  </tr>
                  <tr  class="nonembacytotal">

                    <td align="left"><span>Taxes :</span></td>
                    <td align="right"><span> <?php echo $Taxes;?></span></td>
                  </tr>
               <tr  class="nonembacytotal">
                <td align="left"><span>Webfare Discount :</span></td>
                <td align="right"><span><strong>
                <?php
                    //echo 'yes';
					$res = 'SELECT discount FROM airlines WHERE airline = "'.$thisairlinename.'" ';
                    $result = $wpdb->get_results($res);
					$discount = $result[0]->discount;
					//echo $tPrice;
					//echo $result[0]->option_value; 
                    if($discount>0)
					{
					//$disAmt = (substr($tPrice,3) / 100) * $discount;
					$disAmt = ($farePrice / 100) * $discount; // Dec 23, 2016
						echo $currency.number_format($disAmt);
					}else{
						echo 0;
					}
				?>
                 </strong></span></td>
              </tr>
			  <tr class="embacytotal" style="display:none;">
                <td align="left"><span>Letter Fee :</span></td>
                <td align="right"><span><?php echo $embacyfee;?></span></td>
              </tr>
			  <tr class="embacytotal" style="display:none;">
                <td align="left"><span><strong class="larg">Total price :</strong></span></td>
                <td align="right"><span><strong><strong><?php echo $netpricewithembacy;?></strong></strong></span></td>
              </tr>
			  <tr class="nonembacytotal">
                <td align="left"><span><strong class="larg">Total price :</strong></span></td>
                <td align="right"><span><strong><strong><?php echo $netprice;?></strong></strong></span></td>
              </tr>
                </table>
              </div>
            </div>
            <!--Right end--> 
          </div>
        </div>
        </div>
      </div>
      <div class="step" id="step2" style="width:100%;">
       <div class="booking_wrapp">
        <div class="heading pay">
          <h3><span>2</span>Email Address</h3>
           <h6>Where do you want the ticket</h6>
          <div class="clearfix"></div>
        </div>
        <div id="step2_dtls" style="display:none;">
            <div class="mail_details"> <span>* <strong>FREE Reservation:</strong> You can make No-commitment FREE Reservation online without making any payments now.</span> <br />
                <span>* <strong>Come back and Pay Later:</strong> We will send a link to your e-mail to return back to the booking page to continue to make payment.</span><br />
                <span>* <strong>Confirmed Reservation:</strong> Please continue to get a confirmed reservation to your e-mail & mobile, by arranging payment later you can get the e-ticket to travel.</span> <br />
                <span>* <strong>Before you travel:</strong> Please check you have valid passport & VISA to travel to the destination your flying to.</span> </div>
              <div class="clearfix"></div>
              <div class="col-sm-6">
                <div class="mail_box" id="step2_mail_box"> <span>Your e-mail address</span> <span id="emailerror" class="err" style="color:#F00;"></span>
                  <label>
                    <input name="email_address" id="email_address" value="<?php echo $useremail;?>" type="text" class="txt_box" placeholder="E-mail address" />
                  </label>
                  <label>
                    <input name="send_offers" id="send_offers" type="checkbox" value="1" />
                    Send me travel offers, deals and news by email</label>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="col-sm-12">
                <div align="right" class="btn_panel">
                  <input name="" type="button" value="Continue" onclick="processstep2();" class="booking_btn" />
                </div>
              </div>
        </div>
       </div>
      </div>
      <div class="step" id="step2_after" style="display:none;">
       <div class="booking_wrapp">
        <div class="main_content">
          <div class="wrapp">
            <div class="edit" onclick="jQuery('#step2_after').hide();jQuery('#step2').show('slow');editit('step2');"><span>Edit</span></div>
            <div class="no"><span>2</span></div>
            <div class="row">
              <div class="col-sm-12">
                <div class="gaping">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="7%"><img src="<?php echo get_template_directory_uri(); ?>/images/email_summary.png" class="mail" /></td>
                      <td width="93%"><span id="entered_email"></span></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
       </div>
      </div>
      <div class="step" id="step3" style="width:100%;">
       <div class="booking_wrapp">
        <div class="heading pay">
          <h3><span>3</span>Traveller</h3>
          <h6>Tell us who is travelling</h6>
          <div class="clearfix"></div>
        </div>
       </div>
       <div id="step3_details"  style="display:none;">
          <div class="traveller_details"> <span><strong>* Please provide the full name (including all names) as it is in your passport. please leave a space between each name. example on where to find your name in your passport <a href="#" target="_blank">click here</a> (will open in new tab)</strong></span> </div>
		 <?php $psn=$bookingdata['passengers'][0];?>
		 <?php foreach($bookingdata['passengers'][0] as $k=>$p){?>
          <div class="form_panel"><span><strong><?php echo $ptypefull[$p];?> <?php echo $k+1;?></strong></span>
            <div class="form_wrapp">
            <input type="hidden" name="ptype[]" value="<?php echo $p;?>" />
              <ul>
                <li>
                  <label><span>Title</span><br />
                    <select name="title[]" class="txt_box">
                      <?php
					  if($p == 'ADT')
					  {
					  ?>
                      <option value="MR">Mr</option>

                      <option value="MS">Ms</option>

                      <option value="MRS">Mrs</option>

                      <option value="DR">Dr</option>

                      <option value="REV">Rev</option>
                      <?php
					  }else{
					  ?>
                      <!-- XXXX Added -->
                      <option value="MSTR">Master</option>
                      <option value="MISS">Miss</option>
                      <!-- XXXX Added -->
                      <?php
					  }
					  ?>
                    </select>
                  </label>
                </li>
                <li>

                  <label><span>Other Name (Firstname)</span><span id="fnameerror<?php echo $k;?>" class="err" style="color:#F00;"></span><br />

                    <input name="first_name[]" type="text" class="txt_box" placeholder="Other Name" id="fname" />

                  </label>

                </li>

                <li>

                  <label><span>Surname (Lastname)</span><span id="lnameerror<?php echo $k;?>" class="err" style="color:#F00;"></span><br />

                    <input name="last_name[]" type="text" class="txt_box" placeholder="Surname" id="lname"/>

                  </label>

                </li>
				<li>
                  <label><span>Gender</span><span id="sexerror" class="err" style="color:#F00;"></span><br />

                        <select name="sex[]" class="txt_box" id="sex">
    
                           <option value="M">Male</option>
                           <option value="F">Female</option>
                       
                        </select>

                  </label>
                </li>
                <li>

                  <label><span>Date of Birth</span><span id="doberror<?php echo $k;?>" class="err" style="color:#F00;"></span><br />
                     <input name="dob[]" type="text" class="txt_box date-pickR" placeholder="Date of Birth" data-date-format="yyyy-mm-dd"  data-date-end-date="0d" id="dobid<?php echo $k;?>"/>
                  </label>

                </li>
                 <?php if($k==0){?>
                 <li>

                  <label><span>Country Code (ex 0094)</span> <span id="countryerror" class="err" style="color:#F00;"></span><br />

                    <input name="country[]" type="text" class="txt_box" placeholder="Country Code" id="country" />

                  </label>

                </li>
                <li>

                  <label><span>Mobile No (ex 774705553)</span> <span id="moberror" class="err" style="color:#F00;"></span><br />

                    <input name="phone[]" type="text" class="txt_box" placeholder="Mobile No" id="mob" maxlength="14" />

                  </label>

                </li>
               <?php }else{ ?>
               <li><label> &nbsp; </label></li>
               <?php } ?>
              </ul>
              <div class="clearfix"></div>
              <a role="button" data-toggle="collapse" href="#collapseExample<?php echo $k;?>" aria-expanded="false" aria-controls="collapseExample<?php echo $k;?>">Add optional details</a> 
              <!--toggle-->
              <div class="collapse" id="collapseExample<?php echo $k;?>">
                <div class="well">
                  <ul>
                    <li>
                      <label><span>Passport Number</span> <span id="passportnoerror<?php echo $k;?>" class="err" style="color:#F00;"></span><br />
                        <input name="passport_no[]" type="text" class="txt_box" placeholder="Passport No" />
                      </label>
                    </li>
                    <li>
                      <label><span>Expiration date</span> <span id="passportexpdateerror<?php echo $k;?>" class="err" style="color:#F00;"></span><br />
                         <input name="passport_exp_date[]" type="text" class="txt_box date-pickR" data-date-format="yyyy-mm-dd" data-date-start-date="0d" placeholder="Expiration date"  id="ppexpire<?php echo $k;?>"/>
                      </label>
                    </li>
                    <li>
                      <label><span>Issuing country</span> <span id="passportcountryerror<?php echo $k;?>" class="err" style="color:#F00;"></span><br />
                        <input name="passport_country[]" type="text" class="txt_box" placeholder="Issuing country" />
                      </label>
                    </li>
					
					<li>
                          <label><span>Birth Country</span> <span id="birthcountryerror<?php echo $k;?>" class="err" style="color:#F00;"></span><br />
                             <input name="birth_country[]" type="text" class="txt_box" placeholder="US" id="birth_country" />
                          </label>
                        </li>
				    <li>
					   <label><span> </span><br />
                             <a target="_blank" href="<?php echo get_template_directory_uri(); ?>/images/Passport-01.jpg">Sample passport</a>
						   </label>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
          <?php } ?>
          
          <div class="letter-heading-area" id="letterhead" style="display:none;">
          	<label>Whome to address:</label>
            Name: <textarea name="letterhead_name"></textarea>
			Company: <textarea name="letterhead_company"></textarea>
			Address: <textarea name="letterhead_address"></textarea>
          </div>
          
          <div align="right" class="but_section">
          <input name="" type="button" class="booking_btn" onclick="processstep3();" value="Continue" />
          </div>
       </div>
      </div>
      <div class="step" id="step3_after" style="display:none;">
        <div class="booking_wrapp">
            <div class="main_content">
              <div class="wrapp">
                <div class="edit" onclick="jQuery('#step3_after').hide();jQuery('#step3').show('slow');editit('step3');"><span>Edit</span></div>
                <div class="no"><span>3</span></div>
                <div class="row" id="step3_after_content">
                  <div class="col-sm-12">
                    <div class="gaping traveller">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="gaping traveller">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="gaping traveller">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="gaping traveller">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                  <div class="col-sm-12">
                    <div class="gaping traveller">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>
                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
      <div class="step" id="step4" style="width:100%;">
       <div class="booking_wrapp">
        <div class="heading pay">
          <h3><span>4</span>Reserve</h3>
          <h6>Make your reservation</h6>
          <div class="clearfix"></div>
        </div>
        <div id="step4_details" style="display:none;">
           <div class="mail_details" id="step4_mail_details"> <span>* <strong>FREE Reservation:</strong> You can make No-commitment FREE Reservation online without making any payments now.</span> <br />
                <span>* <strong>Come back and Pay Later:</strong> We will send a link to your e-mail to return back to the booking page to continue to make payment.</span><br />
                <span>* <strong>Confirmed Reservation:</strong> Please continue to get a confirmed reservation to your e-mail & mobile, by arranging payment later you can get the e-ticket to travel.</span> <br />
                <span>* <strong>Before you travel:</strong> Please check you have valid passport & VISA to travel to the destination your flying to.</span> 
                <br /><br />
                <label><input name="terms" id="terms" type="checkbox" checked="checked" value="1" /> <span>I understand and agree with the Rules and Restrictions of this fare, the Privacy Policy, the Visa Rules and the <a href="#">Terms and Conditions</a> of Clickmybooking.com</span></label>
                <div class="term-condition">
                	<h1>Terms & Conditions</h1>
                    <p>Welcome! Click my booking is designed for users who exclusively sought for assistance in gathering travel information, making reservation and to carry out transactions with travel suppliers/ agents. Please note that your are bound to terms and conditions of Click my booking website which is maintained by Meera Travels , a company incorporated and registered under the laws of Democratic Socialist Republic of Sri Lanka.<p>
                    <p>You are allowed to print your travel itinerary for travelling purpose, but in case of modification, duplication or usage for transacting with a third party; of the original document is considered as an offence and legal charges will be pressed immediately.</p>
                    <p>Moreover, you are not allowed to interfere with the activity of this website by any means, attempting to do so is also considered as a punishable offence. Upon agreeing to abide by the terms and conditions mentioned above and in other posts in this website, you are granted to access Click my booking and obtain our excellent quality services.</p>
                    
                    <h1>Privacy Policy</h1>
<p>Click my booking will not hold any responsibility for any details you have sent to this website, since this website could be read and intercepted by frauds unless it is properly encrypted. We also advise you to encrypt confidential information such as credit card numbers, bank details etc.</p>
 
<p>All copy rights of the content of this website are reserved by Click my booking. Thus, copying or reproducing the content of this website is a punishable offence, except when it is necessary in instances such as to ensure payment, submit travel itineraries and for other avail paid services.</p>
 
                    <h1>Safety Measures</h1>
                    
                    <p>SSL, is the technology which Click my booking uses to ensure the safety of your confidentiality. Secure Sockets Layer or SSL technology encrypts all your information with 128 bit encryption before it is been sent to our servers and this information can be read ONLY by the relevant recipient. Click my booking holds your basic personal information like Name, Contact numbers, Email IDs etc. while the finance related information are held by the relevant banks.</p>
                                                              
                    <h1>Return and cancellation Policy</h1>
                    
                    <p>All the requests of online payments should be directed to the email: meera1958@yahoo.co.in Refunding will be proceeded based on the conditions provided by the Cancellation policy. The amount will be Refunded at the same time as the time of online purchase. Please note that the time duration taken to transfer funds differ according to the policy of the particular bank. This might take 15-30 business days or might even postpone until the next billing cycle.</p>
                    <p>The amount refunded should be converted to the local currency by the bank associated with your credit cards. Finally you will be notified about your statement, with an email bearing 'Click my booking' as the line of the subject.</p>
                    
                </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-12">
            <div align="right" class="btn_panel">
              <input name="" type="button" value="Make Reservation" id="make_reserv_btn" onclick="processstep4();" class="booking_btn" />
            </div>
          </div>
        </div>
       </div>
      </div>
      <div class="step" id="step4_after" style="display:none;">
        <div class="booking_wrapp">
           <div class="main_content">
              <div class="wrapp">
                <div class="edit" onclick="jQuery('#step4_after').hide();jQuery('#step4').show('slow');editit('step4');"><span>Edit</span></div>
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
      </div>
      <div class="step" id="step5" style="width:100%;">
       <div class="booking_wrapp">
        <div class="heading pay">
          <h3><span>5</span>Choose Payment Mode</h3>
          <h6>Convenience fee will be charged based on payment mode</h6>
          <div class="clearfix"></div>
        </div>
       </div>
       
       <div id="step5_details" style="display:none;">
           <div class="row">
                <div class="col-sm-6">
                  <div class="payment_lft">
                    <center>
                      <h4>Ref No (My Trip ID) : <strong id="pnr">XXXXXX</strong></h4>
                    </center>
                    <div class="payment_details">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr class="nonembacytotal">
                          <td align="left"><span>Base Fare</span></td>
                          <td align="right"><span> <?php echo $EquivalentBasePrice;?></span></td>
                        </tr>
                        <tr class="nonembacytotal">
                          <td align="left"><span>Taxs & Surcharge</span></td>
                          <td align="right"><span> <?php echo $Taxes;?></span></td>
                        </tr>
                        <tr>
                        <tr  class="nonembacytotal">
                          <td align="left"><span><strong>Total</strong></span></td>
                          <td align="right"><span><strong><?php echo $TotalPrice;?></strong></span></td>
                        </tr>
               <tr class="nonembacytotal">
                <td align="left"><span>Webfare Discount :</span></td>
                <td align="right"><span><strong>
                <?php
                    //echo 'yes';
					$res = 'SELECT discount FROM airlines WHERE airline = "'.$thisairlinename.'" ';
                    $result = $wpdb->get_results($res);
					$discount = $result[0]->discount;
					//echo $tPrice;
					//echo $result[0]->option_value; 
                    if($discount>0)
					{
					//$disAmt = (substr($tPrice,3) / 100) * $discount;
					$disAmt = ($farePrice / 100) * $discount; // Dec 23, 2016
						echo $currency.number_format($disAmt);
					}else{
						echo 0;
					}
				?>
                 </strong></span></td>
              </tr>
			  <tr class="embacytotal" style="display:none;">
                <td align="left"><span>Letter Fee :</span></td>
                <td align="right"><span><?php echo $embacyfee;?></span></td>
              </tr>
			  <tr class="embacytotal" style="display:none;">
                <td align="left"><span><strong class="larg">Total price :</strong></span></td>
                <td align="right"><span><strong><strong><?php echo $netpricewithembacy;?></strong></strong></span></td>
              </tr>
			  <tr class="nonembacytotal">
                <td align="left"><span><strong class="larg">Total price :</strong></span></td>
                <td align="right"><span><strong><strong><?php echo $netprice;?></strong></strong></span></td>
              </tr>
                        
                      </table>
                    </div>
                    <p><strong>We use the highest secure payment gateway to process your transaction.</strong></p>
                    <div class="payment_mode">
                      <ul>
                        <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/mster-c.jpg" /></a></li>
                        <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/visa-v.jpg" /></a></li>
                        <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/amex-c.jpg" /></a></li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!--left-->
                
                <div class="col-sm-6">
                  <div class="payment_options">
                    <div class="payment_options_wrapp">
					   <span id="paynowarea"  style="display:none;">
					   <form name="frmpay" action="<?php echo site_url(); ?>/payment" method="post">
						<input type="hidden" name="pay_amount" value="<?php echo $TOTALPRICEVALUE*100;?>" size="20" maxlength="10">
						<input type="hidden" name="post_id" value="2" />
						<input type="hidden" name="booking_type" value="air" />
						<input type="hidden" name="booking_reason_payment" id="booking_reason_payment" value="" />
						<div class="btn_panel" align="right">
                            <input class="booking_btn" type="submit" value="Continue" name="">
                        </div>

                      </form>	
                      </span>					  
						
                    </div> 
                  </div>
                </div>
                <!--right--> 
            </div>
       </div>
       
      </div>
      <div>&nbsp;</div>
<!-- end steps  --> 
      
      </div>
    </div>
  </div>
</div> 
</div> 

<div class="pop_wrapp" id="fare-pop">
	<div class="pop_up">
    	<center><h2>Reason For Booking</h2></center>
        <ul>
        	<li><a style="cursor:pointer;" onclick="setBookingReason(1);">Fare Quotation</a></li>
            <li><a style="cursor:pointer;" onclick="setBookingReason(2);">Embassy Visa Purpose</a></li>
            <li><a style="cursor:pointer;" onclick="setBookingReason(3);">Pay Later</a></li>
        </ul>
    </div>
</div>
 	
<?php
}
?>
<script type="text/javascript">
jQuery(document).ready(function() {
		var id = '#fare-pop';		
		//transition effect		
		jQuery(id).fadeIn(1000);	
		jQuery(id).fadeTo("slow",0.8);		
		//Get the window height and width
		var winH = jQuery(window).height();
		var winW = jQuery(window).width();
		//transition effect
		jQuery(id).fadeIn(2000); 	
});
function setBookingReason(v){
jQuery('#booking_reason option:eq('+v+')').prop('selected', true)
var breson=jQuery('#booking_reason').val();

if(breson=='Embassy Visa Purpose'){
   jQuery('.embacytotal').show();
   jQuery('.nonembacytotal').hide();
}else{
   jQuery('.embacytotal').hide();
   jQuery('.nonembacytotal').show();
}

var id = '#fare-pop';	
jQuery(id).fadeOut(500); 	
}
</script>
<script type="text/javascript">
var booking_reason='';
function editit(s){ 
	jQuery('.step').css({"z-index": "0"});
	jQuery('#'+s).css({"position": "relative","float":"left","width":"100%","z-index": "999","background": "#fff"});
	jQuery('.pop-edit').show();
}
function unblockall(){ 
	jQuery('.step').css({"position": "","z-index": "0","background": ""});
	jQuery('.pop-edit').hide();
	jQuery('#make_reserv_btn').prop( "disabled", false );
}
function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
// XXXX Added
function MobCountryValidate() {
        var country = document.getElementById("country").value;
        var pattern = /^\d{3,4}$/;
        if (pattern.test(country)) 
		return true;
        else
        return false;
    }
function MobValidate() {
        var mobile = document.getElementById("mob").value;
        var pattern = /^\d{9,10}$/;
        if (pattern.test(mobile)) 
		return true;
        else
        return false;
    }

function validateDateFormat(dateVal){
 
      var dateVal = dateVal;
 
      if (dateVal == null) 
          return false;
 
      var validatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/;
 
          dateValues = dateVal.match(validatePattern);
 
          if (dateValues == null) 
              return false;
 
      var dtYear = dateValues[1];        
          dtMonth = dateValues[3];
          dtDay=  dateValues[5];
 
       if (dtMonth < 1 || dtMonth > 12) 
          return false;
       else if (dtDay < 1 || dtDay> 31) 
         return false;
       else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
         return false;
       else if (dtMonth == 2){ 
         var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
         if (dtDay> 29 || (dtDay ==29 && !isleap)) 
                return false;
      }
 
     return true;
}
// XXXX Added	
function processstep1(){
booking_reason=jQuery('#booking_reason').val();
if(booking_reason==''){
alert("What is your booking reason, please select one.");	
return false;
}
jQuery.ajax({
	type: 'post',
	data:{booking_reason:booking_reason},
	url: '<?php echo home_url("/processstep1.php");?>',
	success: function(h) {
        jQuery('#step1').hide();jQuery('#step1_after').show();jQuery('#step2_dtls').show('slow');unblockall();	
	}
});
}
function processstep2(){ 
var breson=jQuery('#booking_reason').val();
jQuery('#emailerror').text('');	
var eml=jQuery('#email_address').val();
var send_offers=jQuery('#send_offers').is(":checked");
if(send_offers==true)send_offers=1;
else send_offers=0;
if(isValidEmailAddress(eml)==false){
jQuery('#emailerror').text('Error, enter a valid email address!');	
return;
}
jQuery.ajax({
	type: 'post',
	data:{eml:eml,send_offers:send_offers},
	url: '<?php echo home_url("/processstep2.php");?>',
	success: function(h) {
	   jQuery('#entered_email').text(h);
	   jQuery('#step2').hide();jQuery('#step2_after').show();jQuery('#step3_details').show('slow');unblockall();
	   jQuery('#booking_reason_payment').val(breson);
       if(breson=='Embassy Visa Purpose'){ // display the passport fields
            jQuery('.collapse').show();
	   }	   
			   
	}
});
}

function processstep3(){ 
var err=0;
// Fname Chk
var k=0;
jQuery("input[name='first_name[]']").each(function() {
	jQuery('#fnameerror'+k).text('');
    if(jQuery.trim($(this).val()).length<2){
	   jQuery('#fnameerror'+k).text('Missing Minimum two chracters !');
       err=1;	   
	}
	k=k+1;
});

var k=0;
jQuery("input[name='last_name[]']").each(function() {
	jQuery('#lnameerror'+k).text('');
    if(jQuery.trim($(this).val()).length<2){
	   jQuery('#lnameerror'+k).text('Missing Minimum two chracters !');
       err=1;	   
	}
	k=k+1;
});

// DOB Chk
var k=0;
jQuery("input[name='dob[]']").each(function() {
	jQuery('#doberror'+k).text('');
    if($(this).val()==''){
	   jQuery('#doberror'+k).text('DOB Missing');
       err=1;	   
	}
	k=k+1;
});

// passport validation
var breson=jQuery('#booking_reason').val();
if(breson=='Embassy Visa Purpose'){
var k=0;
jQuery("input[name='passport_no[]']").each(function() {
	jQuery('#passportnoerror'+k).text('');
    if($(this).val()==''){
	   jQuery('#passportnoerror'+k).text('Passport Number Missing');
       err=1;	   
	}
	k=k+1;
});
var k=0;
jQuery("input[name='passport_exp_date[]']").each(function() {
	jQuery('#passportexpdateerror'+k).text('');
    if($(this).val()==''){
	   jQuery('#passportexpdateerror'+k).text('Exp Date Missing');
       err=1;	   
	}
	k=k+1;
});
var k=0;
jQuery("input[name='passport_country[]']").each(function() {
	jQuery('#passportcountryerror'+k).text('');
    if($(this).val()==''){
	   jQuery('#passportcountryerror'+k).text('Country Missing');
       err=1;	   
	}
	k=k+1;
});
var k=0;
jQuery("input[name='birth_country[]']").each(function() {
	jQuery('#birthcountryerror'+k).text('');
    if($(this).val()==''){
	   jQuery('#birthcountryerror'+k).text('Birth Country Missing');
       err=1;	   
	}
	k=k+1;
});
}// breason
// Mobile Country Code Chk
jQuery('#countryerror').text('');	

var country=jQuery('#country').val();

if(country == '' || MobCountryValidate(country) == false)
{
	jQuery('#countryerror').text('Enter Valid Country Code!');	
	jQuery('#country').focus();
	return;
}
// Mobile Chk
jQuery('#moberror').text('');	

var mob=jQuery('#mob').val();

if(mob == '' || MobValidate(mob) == false)
{
	jQuery('#moberror').text('Enter Valid Mobile No!');	
	jQuery('#mob').focus();
	return;
}

if(err==1){
	return false;
}
// XXXX added
jQuery.ajax({
	type: 'post', 
	/* data: jQuery('select[name=\'title[]\'], input[name=\'ptype[]\'], input[name=\'first_name[]\'], input[name=\'last_name[]\'], input[name=\'dob[]\'], input[name=\'phone[]\'],  input[name=\'passport_no[]\'], input[name=\'passport_exp_date[]\'], input[name=\'passport_country[]\'],  select[name=\'flyer_club[]\'],  input[name=\'flyer_number[]\'],  textarea[name=\'letterhead\']'), */
	data: jQuery('select[name=\'sex[]\'],select[name=\'title[]\'], input[name=\'ptype[]\'], input[name=\'first_name[]\'], input[name=\'last_name[]\'], input[name=\'dob[]\'], input[name=\'country[]\'] , input[name=\'phone[]\'],  input[name=\'passport_no[]\'], input[name=\'passport_exp_date[]\'], input[name=\'passport_country[]\'],  select[name=\'flyer_club[]\'],  input[name=\'flyer_number[]\'],  input[name=\'AddressName[]\'],  input[name=\'Street[]\'],  input[name=\'City[]\'],  input[name=\'State[]\'],  input[name=\'PostalCode[]\'],  input[name=\'Country[]\'], input[name=\'birth_country[]\']'),
	url: '<?php echo home_url("/processstep3.php");?>',
	success: function(h) { 
	   jQuery('#step3').hide();jQuery('#step3_after').show();jQuery('#step4_details').show('slow');unblockall();
	   processstep3_after(h);
	}
});
}

function processstep3_after(h){ 
	jQuery('#step3_after_content').html(h);
}
function processstep4(){
if(jQuery('#terms').is(":checked")==false){
	alert('Please visit the terms and condition page, you require to agree with those.');
	return;
}

jQuery('#step4_mail_details').html('Please Wait while we process your booking with the airline, this may take a minute.');
jQuery('#make_reserv_btn').val('Please Wait');
jQuery('#make_reserv_btn').prop( "disabled", true );

jQuery.ajax({
	type: 'post', 
	data: jQuery('textarea[name=\'letterhead_name\'],textarea[name=\'letterhead_company\'],textarea[name=\'letterhead_address\']'),
	url: '<?php echo home_url("/processstep4.php");?>',
	success: function(h) { 
	   if(h=='done'){
		   reservationcreate();
	   }
	}
});
 //jQuery('#step4').hide();jQuery('#step4_after').show();jQuery('#step5_details').show('slow');unblockall();	
}
function reservationcreate(){ 
 jQuery.ajax({  
	type: 'post', 
	data: {p:'<?php echo base64_encode(serialize($_SESSION['PARSEDDATA']));?>',s:'<?php echo $searchdataoriginal;?>',r:'<?php echo base64_encode(serialize(array($userid)));?>',b:'free'},
	url: '<?php echo home_url("/reservation_create.php");?>',
	success: function(h) { 
	   jQuery('#reserved').html(h);
	   if(booking_reason=='Fare Quotation'){
		jQuery('#step5_details').html('');unblockall();	   
	   }else{
	   <?php /*?>getPNR();<?php */?>
	   getTripId();
		   if(booking_reason=='Embassy Visa Purpose'){
			   jQuery('#paynowarea').show();
			   jQuery('.embacytotal').show();
			   jQuery('.nonembacytotal').hide();
		   }else{
			   jQuery('.embacytotal').hide();
			   jQuery('.nonembacytotal').show();
		   }
		jQuery('#step5_details').show('slow');
		unblockall();
	   }
	}
});
}
function getPNR(){
	jQuery.ajax({  
	type: 'post', 
	url: '<?php echo home_url("/getpnr.php");?>',
	success: function(h) { 
	   jQuery('#pnr').text(h);
	}
  });
}
function getTripId(){
	jQuery.ajax({  
	type: 'post', 
	url: '<?php echo home_url("/gettripid.php");?>',
	success: function(h) { 
	   jQuery('#pnr').text(h);
	}
  });
}
jQuery(document).ready(function(){
 jQuery('.date-pick').datepicker({ autoclose: true,todayHighlight: true});
});

jQuery("#booking_reason").change(function(){
	var v=jQuery(this).val();
	jQuery('#letterhead').hide();
    if(v=='Fare Quotation' || v=='Embassy Visa Purpose'){
		jQuery('#letterhead').show();
	}
	if(booking_reason=='Embassy Visa Purpose'){
       jQuery('.embacytotal').show();
	   jQuery('.nonembacytotal').hide();
	}else{
	   jQuery('.embacytotal').hide();
	   jQuery('.nonembacytotal').show();
	}
});
</script>
<?php
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
get_footer();
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css"
  
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
 <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
 
  <script>
  jQuery(function() {
	<?php foreach($psn as $k=>$v){?>
	   jQuery( "#dobid<?php echo $k;?>" ).datepicker({
		  changeMonth: true,
		  changeYear: true,
		  dateFormat: 'yy-mm-dd',
		  <?php if($v=='ADT'){?>
		  yearRange: "-80:+0"
		  <?php }else if($v=='CNN'){?>
		  yearRange: "-8:+0"
		  <?php }else if($v=='INF'){?>
		  yearRange: "-1:+0"
		  <?php } ?>
		});
		jQuery( "#ppexpire<?php echo $k;?>" ).datepicker({
		  changeMonth: true,
		  changeYear: true,
		  dateFormat: 'yy-mm-dd',
		});
	<?php } ?>
	
  });
  </script>
 <script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(".fancyboxpop").fancybox();
	});
</script>