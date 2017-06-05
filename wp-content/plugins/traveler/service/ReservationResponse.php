<?php
include_once PLUG_DIR.'/models/PricingFly.php';
include_once PLUG_DIR.'/service/dumpReservation.php';
include_once PLUG_DIR.'/service/emailSender.php';
include_once PLUG_DIR.'/utility/FlightUtility.php'; 
function process_reservation_response($responseArray,$resReq)
{
if(isset($responseArray['SOAPBody']['SOAPFault']['faultstring']))
    {  

	    ## XXXX - Start - Error Trapping  ( Known Error Issues = Now this portion is not working
		//echo '<pre>';
		$errorCD = array();
		$errorCD['faultstring'] = $responseArray['SOAPBody']['SOAPFault']['faultstring'];
		//print_r($data['errorCD']); die;
		
		$errTrap = explode(" " , $data['errorCD']);
		//echo $errTrap[1]; 
		
		$Errordis = '';
		switch ($errTrap[1]) 
		{
			case ":7:519:" : $Errordis = "Seat Not Available!"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			case "0" 	   : $Errordis = "Error -1"; break;
			default  	   : $Errordis = "Unable to be determined"; 
		}
        
		if($Errordis != '')
		{
                        ?>
                <?php ?>
                <?php
			# Not to go further
			//die;
		}
		## XXXX - End - Error Trapping
		echo"Some thing went wrong Please search again";
		wp_die();
}
else
{
 reservationSuceess($responseArray,$resReq);   
}
}


function reservationSuceess($responseArray,$resReq)
{
$util=new FlightUtility();
$reservationRsp=array("booking_date"=>"","airResCode"=>"","uniRecLocCode"=>"","proResInfoLocCode"=>"","airResRes"=>"","ticket_type"=>"","ticket_date"=>"","ticket_num"=>"");
if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['@attributes']['LocatorCode'])){
$reservationRsp['airResCode']=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['@attributes']['LocatorCode'];
$reservationRsp['booking_date']=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['@attributes']['CreateDate'];

}

if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['@attributes']['LocatorCode'])){
$reservationRsp['uniRecLocCode']=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['@attributes']['LocatorCode'];

}

if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['universalProviderReservationInfo']['@attributes']['LocatorCode'])){
$reservationRsp['proResInfoLocCode']=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['universalProviderReservationInfo']['@attributes']['LocatorCode'];

}


if(isset($responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['airAirPricingInfo']['@attributes']['Key']))
$AirPricingInfoRef=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['airAirPricingInfo']['@attributes']['Key'];

$reservationRsp['ticket_type']=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['common_v32_0ActionStatus']['@attributes']['Type'];
$reservationRsp['ticket_date']=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['common_v32_0ActionStatus']['@attributes']['TicketDate'];	


$data=$responseArray['SOAPBody']['universalAirCreateReservationRsp']['universalUniversalRecord']['airAirReservation']['airAirPricingInfo'];

	$tripId="";
	if(isset($reservationRsp['uniRecLocCode']))
	{
		$tripId=$util->tripIdGenerator();
	}
	
	update_reservation($reservationRsp,$data,$tripId,$resReq);
//email_send($resReq,$tripId);
	
}


?>

