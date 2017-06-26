<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once PLUG_DIR .'/utility/FlightUtility.php';
include PLUG_DIR.'/service/LowFareAirPriceResponse.php';
include PLUG_DIR.'/view/BookDisplay.php';
//get the encodeed booking data which set from search request
$bookingdataEncode = filter_input(INPUT_POST, 'outbound');
if (isset($bookingdataEncode)) {

   $bookingdata = unserialize(base64_decode($bookingdataEncode));
    

}

$booking_Request = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">'.
   '<soapenv:Header/>'.
   '<soapenv:Body>'.
      '<air:AirPriceReq TargetBranch="'.TARGET_BRANCH.'"  xmlns:air="http://www.travelport.com/schema/air_v32_0" '.
        'xmlns:com="http://www.travelport.com/schema/common_v32_0" CheckOBFees="true" AuthorizedBy="User" TraceId="trace" >'.
  '<BillingPointOfSaleInfo OriginApplication="UAPI" xmlns="http://www.travelport.com/schema/common_v32_0"/>'.
        
  '<air:AirItinerary>';
      $bookingSegment=$booking_Request;  
      $airPriceSegment='';
       if(isset($bookingdata))
       {
        foreach($bookingdata as $item)
            {

            $index=$item['@attributes'];
            if(isset($item[0]['airBookingInfo']['@attributes']))
            {
            $bookingInfo=$item[0]['airBookingInfo']['@attributes'];
            }elseif(isset($item[0]['@attributes']))
            {
             $bookingInfo=$item[0]['@attributes'];   
            }
        $_SESSION['air_line']=$index['Carrier'];
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

$booking_Request=$bookingSegment;
       }



$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService';
$flightUtility=new FlightUtility();

$response=$flightUtility->sendPost($endpoint,$booking_Request,"");//airAirPriceRsp.xml



$bookreponse=process_price_response($response);
echo book_Init($bookreponse);

//error_log('Response---->'.print_r($response,true));

