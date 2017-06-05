<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



include_once  PLUG_DIR.'/models/PricingFly.php';
include_once  PLUG_DIR.'/utility/FlightUtility.php';
include_once  PLUG_DIR.'/service/ReservationResponse.php';

function init_reservation()
{

    $reReq = $_POST['reservationRequest'];
    error_log('reservationRequest ---- >' . print_r($reReq, true));
    $paymode = $_POST['paymode'];
    error_log('reservationRequest ---- >' . print_r($_SESSION['pay_details'], true));
    $_SESSION['booking_reason'] = $paymode;
//$_SESSION['reservation_details']=$reReq;

    return;
    $pricingData = unserialize($_SESSION['price']);
    $searchData = $_SESSION['searchdata'];

    $util = new FlightUtility();
    $passMap = array("ADT" => "1", "CNN" => "2", "INF" => "3");
    $passangers = $searchData['passengers'];
    $basepare = $pricingData->priceDetails->baseFare;
    $pricinInfo = $pricingData->airDetails;
    $ticketingTime = "";

    $soapRequest = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:univ="http://www.travelport.com/schema/universal_v32_0" xmlns:com="http://www.travelport.com/schema/common_v32_0" xmlns:air="http://www.travelport.com/schema/air_v32_0">'
        . '<soapenv:Header/>'
        . '<soapenv:Body>'
        . '<univ:AirCreateReservationReq'
        . ' xmlns:air="http://www.travelport.com/schema/air_v32_0" 
                xmlns:common_v32_0="http://www.travelport.com/schema/common_v32_0"
                xmlns:univ="http://www.travelport.com/schema/universal_v32_0" 
                xmlns:com="http://www.travelport.com/schema/common_v32_0"
        AuthorizedBy="user" RetainReservation="None" TargetBranch="P2721018"
        TraceId="1f01d4fc93d28f56c815663101c2e13c">'
        //SaleInfo
        . '<com:BillingPointOfSaleInfo xmlns:com="http://www.travelport.com/schema/common_v32_0" OriginApplication="UAPI"/>';
    //Booking TravelerInfo
    $keyValue = 1;
    foreach ($reReq['f_name'] as $Key => $index) {
        error_log('Key --> ' . $Key);
        $soapRequest .= '<com:BookingTraveler xmlns:com="http://www.travelport.com/schema/common_v32_0"'
            . ' Gender="M" Key="' . ($Key + 1) . '" TravelerType="' . $passangers[$Key] . '">'
            . '<com:BookingTravelerName First="' . $index . '" Last="' . $reReq['l_name'][$Key] . '" Prefix="' . $reReq['titile'][$Key] . '"/>';
        if ($Key == 0) {
            $soapRequest .= '<com:Email EmailID="' . $reReq['email'] . '" Type="Home"/>';
        }
        if ($passangers[$Key] == 'CNN') {
            $soapRequest .= '<com:NameRemark>
					<com:RemarkData>P-C' . $reReq['age'][$Key] . '</com:RemarkData>
				</com:NameRemark>';
        } elseif ($passangers[$Key] == 'INF') {
            $inf_dt = DateTime::createFromFormat('Y-m-d', $reReq['dob'][$Key]);
            $inf_dob = $inf_dt->format('dMy');
            $upTxt = strtoupper(substr($inf_dob, 2, 3));
            $fullTxt = substr($inf_dob, 0, 2) . $upTxt . substr($inf_dob, 5, 2);
            $soapRequest .= '<com:NameRemark>
					<com:RemarkData>' . $fullTxt . '</com:RemarkData>
				</com:NameRemark>';
        }
        $soapRequest .= '</com:BookingTraveler>';

    }
    //$reReq['countrycode'] haed code please change later
    $soapRequest .= '<com:ContinuityCheckOverride Key="Blanks">Blanks</com:ContinuityCheckOverride>'
        . '<com:AgencyContactInfo>'
        . '<com:PhoneNumber Type="Agency" CountryCode="0094" Number="' . $reReq['mobNumber'] . '" Location="DEL" AreaCode="11" Text="ITQ INDIA"/>'
        . '</com:AgencyContactInfo>'
        . '<com:FormOfPayment xmlns:com="http://www.travelport.com/schema/common_v32_0" Key="' . base64_encode("F1" . date("YmdHis")) . '" Type="Cash"/>'
        . '<air:AirPricingSolution'
        . '  ApproximateBasePrice="' . $basepare['ApproximateBasePrice'] . '"'
        . ' ApproximateTaxes="' . $basepare['ApproximateTaxes'] . '"'
        . ' ApproximateTotalPrice="' . $basepare['ApproximateTotalPrice'] . '"'
        . ' BasePrice="' . $basepare['BasePrice'] . '"'
        . ' Key="' . base64_encode("APS" . date("YmdHis")) . '"'
        . ' QuoteDate="' . $basepare['QuoteDate'] . '" '
        . ' Taxes="' . $basepare['Taxes'] . '"'
        . ' TotalPrice="' . $basepare['TotalPrice'] . '">';

    $soapRequest .= air_item_process($pricinInfo);

    foreach ($pricingData->priceDetails->fareInfo as $Key => $index) {

        if (isset($index->pricingInfo)) {

            $tem = $index->pricingInfo;
            // error_log('fareInfo //\\\////->'.print_r($tem,true));
            $ticketingTime = $tem['LatestTicketingTime'];
            $soapRequest .= '<air:AirPricingInfo'
                . ' ApproximateBasePrice="' . $tem['ApproximateBasePrice'] . '"'
                . ' ApproximateTaxes="' . $tem['ApproximateTaxes'] . '"'
                . ' ApproximateTotalPrice="' . $tem['ApproximateTotalPrice'] . '" BasePrice="' . $tem['BasePrice'] . '" '
                . ' ETicketability="' . $tem['ETicketability'] . '"'
                . ' IncludesVAT="' . $tem['IncludesVAT'] . '" Key="' . $tem['Key'] . '"'
                . ' LatestTicketingTime="' . $tem['LatestTicketingTime'] . '" PlatingCarrier="' . $tem['PlatingCarrier'] . '"'
                . ' PricingMethod="' . $tem['PricingMethod'] . '" ProviderCode="' . $tem['ProviderCode'] . '" Taxes="' . $tem['Taxes'] . '"'
                . ' TotalPrice="' . $tem['TotalPrice'] . '">';
        }

        if (isset($index->fareInfo['@attributes'])) {
            $index->fareInfo = array($index->fareInfo);
        }

        foreach ($index->fareInfo as $jindex) {
            // error_log('fareInfo 2-->'.print_r($jindex,true));
            if (isset($jindex['@attributes'])) {
                $fareInfp = $jindex['@attributes'];

                $soapRequest .= '<air:FareInfo Amount="' . $fareInfp['Amount'] . '" DepartureDate="' . $fareInfp['DepartureDate'] . '"'
                    . ' Destination="' . $fareInfp['Destination'] . '" EffectiveDate="' . $fareInfp['EffectiveDate'] . '"'
                    . ' FareBasis="' . $fareInfp['FareBasis'] . '" Key="' . $fareInfp['Key'] . '"';
                $NotValidAfter;
                if (isset($fareInfp['NotValidAfter'])) {
                    $NotValidAfter = $fareInfp['NotValidAfter'];
                }
                $NotValidBefore;
                if (isset($fareInfp['NotValidBefore'])) {
                    $NotValidBefore = $fareInfp['NotValidBefore'];
                }
                $soapRequest .= ' NotValidAfter="' . $NotValidAfter . '" NotValidBefore="' . $NotValidBefore . '"'
                    . ' Origin="' . $fareInfp['Origin'] . '" PassengerTypeCode="' . $fareInfp['PassengerTypeCode'] . '" >'
                    . '<common_v32_0:Endorsement Value="NON-REF/NON-END"/>'
                    . '<air:FareRuleKey FareInfoRef="' . $fareInfp['Key'] . '"'
                    . ' ProviderCode="1G">'
                    . $jindex['airFareRuleKey'] . '</air:FareRuleKey>'
                    . '</air:FareInfo>';
            }
        }


        $temOtherInfo = $pricingData->priceDetails->otherInfo[$Key]->airBookingInfo;

        if (isset($temOtherInfo['@attributes'])) {
            $temOtherInfo = array($temOtherInfo);
        }

        foreach ($temOtherInfo as $airBookinInfo) {
            $temp = $airBookinInfo['@attributes'];
            $soapRequest .= '<air:BookingInfo BookingCode="' . $temp['BookingCode'] . '" 
                    CabinClass="' . $temp['CabinClass'] . '" 
                        FareInfoRef="' . $temp['FareInfoRef'] . '"
                        SegmentRef="' . $temp['SegmentRef'] . '"/>';
        }


        $temTaxInfo = $pricingData->priceDetails->otherInfo[$Key]->airTaxInfo;
        if (isset($temTaxInfo['@attributes'])) {
            $temTaxInfo = array($temTaxInfo);
        }
        foreach ($temTaxInfo as $airTaxInfo) {
            $temp = $airTaxInfo['@attributes'];
            $soapRequest .= '<air:TaxInfo Amount="' . $temp['Amount'] . '" Category="' . $temp['Category'] . '" Key="' . $temp['Key'] . '"/>';
        }

        $soapRequest .= '<air:FareCalc>' . $pricingData->priceDetails->otherInfo[$Key]->airFareCalc . '</air:FareCalc>';

        $temPasType = $pricingData->priceDetails->otherInfo[$Key]->airPassengerType;
        if (isset($temPasType['@attributes'])) {
            $temPasType = array($temPasType);
        }
        foreach ($temPasType as $passTpe) {
            $temp = $passTpe['@attributes'];
            $soapRequest .= '<air:PassengerType  Code="' . $temp['Code'] . '"  BookingTravelerRef="' . $keyValue . '"  />';
            $keyValue++;
        }

        $soapRequest .= '</air:AirPricingInfo>';
    }

    $soapRequest .= '</air:AirPricingSolution><com:ActionStatus xmlns:com="http://www.travelport.com/schema/common_v32_0"'
        . ' ProviderCode="' . PROVIDER . '" TicketDate="' . $ticketingTime . '" Type="TAW" />'

        . '</univ:AirCreateReservationReq>'
        . '</soapenv:Body>'
        . '</soapenv:Envelope>';


    error_log("Reservation Request ---- >" . $soapRequest);
    $response = $util->sendPost(ENDPOINT, $soapRequest, "ReservationResponse.xml");

    return process_reservation_response($response, $reReq);


}

function air_item_process($air)
{
     $itemList="";
     $mode=$_SESSION['searchdata']['mode'];
    if($mode=="oneway")
    {
        foreach ($air[0] as $item)
        {
         $itemList.=air_seqment($item);  
        }
    }
 else {
        foreach ($air[0] as $item)
        {
         $itemList.=air_seqment($item);  
        }
        foreach ($air[1] as $item)
        {
         $itemList.=air_seqment($item);  
        }
 }
 return $itemList;
}


function air_seqment($air)
{   
    $value=$air['@attributes'];
    $FlightDetails=$air['airFlightDetails']['@attributes'];
      $item='<air:AirSegment'
                           .' ArrivalTime="'.$value['ArrivalTime'].'"' 
			   .' AvailabilityDisplayType="'.$value['AvailabilityDisplayType'].'"'
			   .' Carrier="'.$value['Carrier'].'"'
			   .' ChangeOfPlane="'.$value['ChangeOfPlane'].'"'
			   .' ClassOfService="'.$value['ClassOfService'].'"'
			   .' DepartureTime="'.$value['DepartureTime'].'"'
			   .' Destination="'.$value['Destination'].'"' 
			   .' Distance="'.$value['Distance'].'"' 
			   .' Equipment="'.$value['Equipment'].'"' 
			   .' FlightNumber="'.$value['FlightNumber'].'"'
			   .' FlightTime="'.$value['FlightTime'].'"'
			   .' Group="'.$value['Group'].'" Key="'.$value['Key'].'"' 
			   .' LinkAvailability="'.$value['LinkAvailability'].'"' 
			   .' OptionalServicesIndicator="'.$value['OptionalServicesIndicator'].'"'
			   .' Origin="'.$value['Origin'].'"'
			   .' ParticipantLevel="'.$value['ParticipantLevel'].'" '
			   .' ProviderCode="'.$value['ProviderCode'].'" '
			   .' TravelTime="'.$value['TravelTime'].'">'
                            .'<air:FlightDetails '
                               .' ArrivalTime="'.$FlightDetails['ArrivalTime'].'"' 
                    
                               .' DepartureTime="'.$FlightDetails['DepartureTime'].'"' 
                               .' Destination="'.$FlightDetails['Destination'].'" '
                               .' Distance="'.$FlightDetails['Distance'].'"' 
                               .' FlightTime="'.$FlightDetails['FlightTime'].'" Key="'.$FlightDetails['Key'].'" '
                               .' Origin="'.$FlightDetails['Origin'].'" TravelTime="'.$FlightDetails['TravelTime'].'"/>'
                             .'</air:AirSegment>';
      
      return $item;
            
}