<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once PLUG_DIR .'/utility/FlightUtility.php';
include PLUG_DIR.'/service/LowFareAirPriceResponse.php';
include PLUG_DIR.'/view/RDisplay.php';


$bookingdataoriginal = '';
$bookingdata = '';
$TARGETBRANCH = 'P2721018'; 
$CREDENTIALS = 'Universal API/uAPI4655248065-02590718:nA?9{c3YC8';
$PCC='3OS2';
$Provider = '1G'; 
//get the encodeed booking data which set from search request
$bookingdataEncode = filter_input(INPUT_POST, 'bookingdata');
//error_log('bookingdataEncode--->'.print_r($bookingdataEncode, true));

if (isset($bookingdataEncode)) {
   // $bookingdataoriginal = $bookingdataEncode;
   // $bookingdataDecode = base64_decode($bookingdataEncode);
   $bookingdata = unserialize(base64_decode($bookingdataEncode));
    
    //error_log('bookingdata !!! ---> '.print_r($bookingdata,true));

   // $_SESSION['searchdata']=$bookingdata->searchData;
}
//echo 'FREE BOOKING CALLED';




$freebookingRequest = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">'.
   '<soapenv:Header/>'.
   '<soapenv:Body>'.
      '<air:AirPriceReq TargetBranch="P2721018"  xmlns:air="http://www.travelport.com/schema/air_v32_0" '. 
        'xmlns:com="http://www.travelport.com/schema/common_v32_0" CheckOBFees="true" AuthorizedBy="User" TraceId="trace" >'.
  '<BillingPointOfSaleInfo OriginApplication="UAPI" xmlns="http://www.travelport.com/schema/common_v32_0"/>'.
        
  '<air:AirItinerary>';
      $bookingSegment=$freebookingRequest;  
      $airPriceSegment='';
       //error_log('before loop --- >'.print_r($bookingdata, true));
       
    
     
       if(isset($bookingdata))
       {
        foreach($bookingdata as $item)
            {
            error_log(print_r($item,true));
            $index=$item['@attributes'];
            if(isset($item[0]['airBookingInfo']['@attributes']))
            {
            $bookingInfo=$item[0]['airBookingInfo']['@attributes'];
            }elseif(isset($item[0]['@attributes']))
            {
             $bookingInfo=$item[0]['@attributes'];   
            }
           // error_log('bookingdata --- >'.print_r($index, true));
        $bookingSegment.='<air:AirSegment '.
                'Key="'.$index['Key'].'" '.
                'CabinClass="'.$bookingInfo['CabinClass'].'"  ' .'Group="'.$index['Group'].'"  '.
                'Carrier="'.$index['Carrier'].'"  '.'FlightNumber="'.$index['FlightNumber'].'" '.
                'Origin="'.$index['Origin'].'"  '.  'Destination="'.$index['Destination'].'"  '.
                'DepartureTime="'.$index['DepartureTime'].'"  '. 
                'ArrivalTime="'.$index['ArrivalTime'].'"  '. 
                'FlightTime="'.$index['FlightTime'].'"  '.  'Distance="'.$index['Distance'].'"  '  .'ETicketability="'.$index['ETicketability'].'"  '.
                'Equipment="'.$index['Equipment'].'"  ' .'ChangeOfPlane="'.$index['ChangeOfPlane'].'"  '.'ParticipantLevel="'.$index['ParticipantLevel'].'" '.
                'LinkAvailability="'.$index['LinkAvailability'].'"  '. 'PolledAvailabilityOption="'.$index['PolledAvailabilityOption'].'"  '.
                'OptionalServicesIndicator="'.$index['OptionalServicesIndicator'].'"  '. 'AvailabilitySource="'.$index['AvailabilitySource'].'"  '.
                'AvailabilityDisplayType="'.$index['AvailabilityDisplayType'].'"  '. 'ProviderCode="'.PROVIDER.'"'.'>'.
                        '<air:FlightDetails Key="'.$item['airFlightDetailsRef']['@attributes']['Key'].'" '.
                        'Origin="'.$index['Origin'].'" '. 'Destination="'.$index['Destination'].'"  '.
                        'DepartureTime="'.$index['DepartureTime'].'"  '.
                        'ArrivalTime="'.$index['ArrivalTime'].'"  '.
                        'FlightTime="'.$index['FlightTime'].'"  '.
                        'Equipment="'.$index['Equipment'].'"  '.'/>'.
      '</air:AirSegment>';
        $airPriceSegment.='<air:AirSegmentPricingModifiers AirSegmentRef="'.preg_replace('/\s+/', '',$bookingInfo['SegmentRef']).'">'.
                            '<air:PermittedBookingCodes>'.
                               '<air:BookingCode Code="'.$bookingInfo['BookingCode'].'"  '.'/>'.
                           ' </air:PermittedBookingCodes>'.
                    ' </air:AirSegmentPricingModifiers>';
}               
        //Recursive air segment 
$bookingSegment.='</air:AirItinerary>'.
        //Itenerary End 
   		   '<air:AirPricingModifiers FaresIndicator="PublicAndPrivateFares" />';
$passangersData='';
$count=1;


       foreach($_SESSION['searchdata']['passengers'] as $passangers)
       {
           
           $key;
            if (strcmp($passangers, 'ADT') == 0) {
                $key = 1;
            } elseif (strcmp($passangers, 'CNN') == 0) {
                $key = 2;
            } elseif (strcmp($passangers, 'INF') == 0) {
                $key = 3;
            }
    $passangersData .='<com:SearchPassenger  BookingTravelerRef="' . md5($count) .'"'. ' Code="' . $passangers .'" '. 'Key="' . $key .'"'. '/>';
    //Age="'.$Age.'" should be add
    $count++;
        }
        $bookingSegment.=$passangersData;       
$bookingSegment.='<air:AirPricingCommand>'.$airPriceSegment. '</air:AirPricingCommand>';
$bookingSegment.='</air:AirPriceReq>'.   
   '</soapenv:Body>'.
'</soapenv:Envelope>';

$freebookingRequest=$bookingSegment;
       }

       
       error_log('REQUEST-->'.$freebookingRequest);

$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService';
// If pricing get failed then we remove the cache so that in next search user get new response
	//$cache->delete($cachekey);
//$freebookingRequest = file_get_contents(PLUG_DIR .'/xml/PriceRequest.xml', FILE_USE_INCLUDE_PATH);
     
// include PLUG_DIR.'/view/FreeBookingView.php';  
 //freeBookingInit($response);

$flightUtility=new FlightUtility();

$response=$flightUtility->sendPost($endpoint,$freebookingRequest,"PriceResponse.xml");



$bookreponse=process_price_response($response);

echo init_priceDisplay($bookreponse);

//error_log('Response---->'.print_r($response,true));

