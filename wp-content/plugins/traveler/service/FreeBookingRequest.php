<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once PLUG_DIR .'/utility/FlightUtility.php';
include_once PLUG_DIR .'/models/OnewayFlight.php';
include PLUG_DIR.'/service/FreeBookingResponse.php';
include PLUG_DIR.'/view/FreeBookingView.php';


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
    $bookingdataoriginal = $bookingdataEncode;
    $bookingdataDecode = base64_decode($bookingdataEncode);
    $bookingdata = unserialize($bookingdataDecode);
    
    //error_log('bookingdata ---> '.print_r($bookingdata,true));
    $_SESSION['searchData']=$bookingdata->searchData;
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
       
       if(isset($bookingdata->flightDetails['Key']))
       {
        $bookingdata->flightDetails=array($bookingdata->flightDetails) ; 
       }
     
       if(isset($bookingdata)&& !empty($bookingdata))
       {
        foreach($bookingdata->flightDetails as $index)
{
           // error_log('bookingdata --- >'.print_r($index, true));
        $bookingSegment.='<air:AirSegment '.
                'Key="'.$index['Key'].'" '.
                'CabinClass="'.$index[0]['CabinClass'].'"  ' .'Group="'.$index['Group'].'"  '.
                'Carrier="'.$index['Carrier'].'"  '.'FlightNumber="'.$index['FlightNumber'].'" '.
                'Origin="'.$index['Origin'].'"  '.  'Destination="'.$index['Destination'].'"  '.
                'DepartureTime="'.$index['DepartureTime'].'"  '. 
                'ArrivalTime="'.$index['ArrivalTime'].'"  '. 
                'FlightTime="'.$index['FlightTime'].'"  '.  'Distance="'.$index['Distance'].'"  '  .'ETicketability="'.$index['ETicketability'].'"  '.
                'Equipment="'.$index['Equipment'].'"  ' .'ChangeOfPlane="'.$index['ChangeOfPlane'].'"  '.'ParticipantLevel="'.$index['ParticipantLevel'].'" '.
                'LinkAvailability="'.$index['LinkAvailability'].'"  '. 'PolledAvailabilityOption="'.$index['PolledAvailabilityOption'].'"  '.
                'OptionalServicesIndicator="'.$index['OptionalServicesIndicator'].'"  '. 'AvailabilitySource="'.$index['AvailabilitySource'].'"  '.
                'AvailabilityDisplayType="'.$index['AvailabilityDisplayType'].'"  '. 'ProviderCode="'.PROVIDER.'"'.'>'.
                        '<air:FlightDetails Key="'.$index[1]['Key'].'" '.
                        'Origin="'.$index[1]['Origin'].'" '. 'Destination="'.$index[1]['Destination'].'"  '.
                        'DepartureTime="'.$index[1]['DepartureTime'].'"  '.
                        'ArrivalTime="'.$index[1]['ArrivalTime'].'"  '.
                        'FlightTime="'.$index[1]['FlightTime'].'"  '.
                        'Equipment="'.$index[1]['Equipment'].'"  '.'/>'.
      '</air:AirSegment>';
        $airPriceSegment.='<air:AirSegmentPricingModifiers AirSegmentRef="'.preg_replace('/\s+/', '',$index[0]['SegmentRef']).'">'.
                            '<air:PermittedBookingCodes>'.
                               '<air:BookingCode Code="'.$index[0]['BookingCode'].'"  '.'/>'.
                           ' </air:PermittedBookingCodes>'.
                    ' </air:AirSegmentPricingModifiers>';
}               
        //Recursive air segment 
$bookingSegment.='</air:AirItinerary>'.
        //Itenerary End 
   		   '<air:AirPricingModifiers FaresIndicator="PublicAndPrivateFares" />';
$passangersData='';
$count=1;


       foreach($bookingdata->searchData['passengers'] as $passangers)
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


$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService';
// If pricing get failed then we remove the cache so that in next search user get new response
	//$cache->delete($cachekey);
//$freebookingRequest = file_get_contents(PLUG_DIR .'/xml/PriceRequest.xml', FILE_USE_INCLUDE_PATH);
     
// include PLUG_DIR.'/view/FreeBookingView.php';  
 //freeBookingInit($response);

$flightUtility=new FlightUtility();

$response=$flightUtility->sendPost($endpoint,$freebookingRequest);



$bookreponse=processearchDatasResponse($response);

echo freeBookingInit($bookreponse);

//error_log('Response---->'.print_r($response,true));

