                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     






<?php 
include_once PLUG_DIR.'/service/LowFareAirPriceResponse.php';
include_once (PLUG_DIR . 'utility/FlightUtility.php');
include PLUG_DIR.'/view/BookDisplay.php';
$outbound=json_decode(base64_decode($_POST['outbound']),true);
$inbound=json_decode(base64_decode($_POST['inbound']),true);
error_log(print_r($outbound,true));

$freebookingRequest = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">'.
   '<soapenv:Header/>'.
   '<soapenv:Body>'.
      '<air:AirPriceReq TargetBranch="'.TARGET_BRANCH.'"  xmlns:air="http://www.travelport.com/schema/air_v32_0" '.
        'xmlns:com="http://www.travelport.com/schema/common_v32_0" CheckOBFees="true" AuthorizedBy="User" TraceId="trace" >'.
  '<BillingPointOfSaleInfo OriginApplication="UAPI" xmlns="http://www.travelport.com/schema/common_v32_0"/>'.
        
  '<air:AirItinerary>';
      $bookingSegment=$freebookingRequest;  
      $airPriceSegment='';
       //error_log('before loop --- >'.print_r($bookingdata, true));
       
  
        foreach($outbound as $jndex)
{
 
            if(isset($jndex['@attributes']))
            {
            $index=$jndex['@attributes'];
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
//                        '<air:FlightDetails Key="'.$index[1]['Key'].'" '.
//                        'Origin="'.$index[1]['Origin'].'" '. 'Destination="'.$index[1]['Destination'].'"  '.
//                        'DepartureTime="'.$index[1]['DepartureTime'].'"  '.
//                        'ArrivalTime="'.$index[1]['ArrivalTime'].'"  '.
//                        'FlightTime="'.$index[1]['FlightTime'].'"  '.
//                        'Equipment="'.$index[1]['Equipment'].'"  '.'/>'.
      '</air:AirSegment>';
                }
        $airPriceSegment.='<air:AirSegmentPricingModifiers AirSegmentRef="'.preg_replace('/\s+/', '',$index[0]['SegmentRef']).'">'.
                            '<air:PermittedBookingCodes>'.
                               '<air:BookingCode Code="'.$index[0]['BookingCode'].'"  '.'/>'.
                           ' </air:PermittedBookingCodes>'.
                    ' </air:AirSegmentPricingModifiers>';
              
        //Recursive air segment 
}
        foreach($inbound as $indexIn)
{
 
            if(isset($indexIn['@attributes']))
            {
            $index=$indexIn['@attributes'];
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
//                        '<air:FlightDetails Key="'.$index[1]['Key'].'" '.
//                        'Origin="'.$index[1]['Origin'].'" '. 'Destination="'.$index[1]['Destination'].'"  '.
//                        'DepartureTime="'.$index[1]['DepartureTime'].'"  '.
//                        'ArrivalTime="'.$index[1]['ArrivalTime'].'"  '.
//                        'FlightTime="'.$index[1]['FlightTime'].'"  '.
//                        'Equipment="'.$index[1]['Equipment'].'"  '.'/>'.
      '</air:AirSegment>';
                }
        $airPriceSegment.='<air:AirSegmentPricingModifiers AirSegmentRef="'.preg_replace('/\s+/', '',$index[0]['SegmentRef']).'">'.
                            '<air:PermittedBookingCodes>'.
                               '<air:BookingCode Code="'.$index[0]['BookingCode'].'"  '.'/>'.
                           ' </air:PermittedBookingCodes>'.
                    ' </air:AirSegmentPricingModifiers>';
              
        //Recursive air segment 
}
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
       


$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService';

$flightUtility=new FlightUtility();

$response=$flightUtility->sendPost($endpoint,$freebookingRequest,"");

$bookreponse=process_price_response($response);
echo book_Init($bookreponse);


