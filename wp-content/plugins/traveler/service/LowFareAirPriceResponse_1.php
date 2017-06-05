<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 $airsegment='';
 $airPricingInfo='';
 $airFareNote='';
 $airFeeInfo='';

include PLUG_DIR.'/models/PricingFly.php';
function process_price_response($response)
{
   // error_log('Price Response --- >'.print_r($response,true));
    global $airsegment;
    global $airPricingInfo;
    global $airFareNote;

    $airsegment=$response['SOAPBody']['airAirPriceRsp']['airAirItinerary']['airAirSegment'];
    $airPricingInfo=$response['SOAPBody']['airAirPriceRsp']['airAirPriceResult']['airAirPricingSolution'];
   // $airFareNote=$response['SOAPBody']['airAirPriceRsp']['airAirPriceResult']['airAirPricingSolution']['airFareNote'];

   $pricingData=airSegment();
   $_SESSION['pricingdata']=serialize($pricingData);
    
   return   $pricingData;
 
}




function getFlightDetails($index) {
     error_log("Price details -->".$index);
    global $airsegment;
   
    $airsegmentList='';
    if (isset($airsegment[0])) {
        $airsegmentList = $airsegment;
    } else {
        $airsegmentList = array($airsegment);
    }
   //  error_log("Price details --> ".print_r($airsegmentList[$index],true) );
     return $airsegmentList[$index];
}


function airSegment()
{
    global $airPricingInfo;

    if (isset($airPricingInfo[0])) {
        $airPricingInfoList = $airPricingInfo;
    } else {
        $airPricingInfoList = array($airPricingInfo);
    }
  //  error_log('airPricingInfoList -->'.print_r($airPricingInfoList,true));
    $pricingFly = new PricingFly();
    $loop=1;
if(isset($airPricingInfoList[0]['airAirSegmentRef'][0]))
{
 $loop=count($airPricingInfoList[0]['airAirSegmentRef']);   
}


    for ($count=0;$count<$loop;$count++) {


        $flightDetails[] = getFlightDetails($count);

        if(isset($airPricingInfoList[0]['airAirPricingInfo'][0]))
        {
        $airbookingInfo=$airPricingInfoList[0]['airAirPricingInfo'][0]['airBookingInfo'][$count];   
        }
        else
        {
         $airbookingInfo=$airPricingInfoList[0]['airAirPricingInfo']['airBookingInfo'];  
        }
        
        array_push($flightDetails[$count]['@attributes'], $airbookingInfo);

    }

   
    $pricingFly->airDetails = $flightDetails;
    
   $pricingFly->priceDetails= getPriceDetails();
   return $pricingFly;
}

function getPriceDetails() {

    
    global $airPricingInfo;

    $pricingDetails = new PricingDetails();
    if (isset($airPricingInfo[0])) {
        $airPricingInfoList = $airPricingInfo;
    } else {
        $airPricingInfoList = array($airPricingInfo);
        
    }
    
    $pricingDetails->baseFare = $airPricingInfoList[0]['@attributes'];
    $count=0;
    
    if(empty($airPricingInfoList[0]['airAirPricingInfo'][0]))
    {
       $airPricingInfoList=array($airPricingInfoList[0]['airAirPricingInfo']);
    }
    else
    {
       $airPricingInfoList=$airPricingInfoList[0]['airAirPricingInfo'];
    }
    // error_log('/////\\\\\\'.print_r($airPricingInfoList,true));
   
    foreach ($airPricingInfoList as $index) {

        //  $priceDetails[] = $index['@attributes'];
        //error_log("Index---->" . print_r($index, true));
        $pricing = new Pricing();
        $otherDestails = new OtherInfo();
        $otherDestails->airBookingInfo = $index['airBookingInfo'];
        $otherDestails->airTaxInfo = $index['airTaxInfo'];
        $otherDestails->airFareCalc = $index['airFareCalc'];
        $otherDestails->airPassengerType = $index['airPassengerType'];
        $otherDestails->airChangePenalty = $index['airChangePenalty'];
        $otherDestails->airCancelPenalty = $index['airCancelPenalty'];
        $otherDestails->airBaggageAllowances = $index['airBaggageAllowances'];

        $airFareInfo = $index['airFareInfo'];
        $airFareInfoList = '';
        $pricing->pricingInfo = $index['@attributes'];
        $pricing->fareInfo = $airFareInfo;
        if (isset($airFareInfo[0])) {
            $airFareInfoList = $airFareInfo;
        } else {
            $airFareInfoList = array($airFareInfo);
        }
        //$priceDet[$count]=$index['@attributes'];
        // error_log(print_r($index['@attributes'],true));
        if (strcmp($airFareInfoList[0]['@attributes']['PassengerTypeCode'], 'ADT') == 0) {
            $pricing->pasType = 'Adult';
            $pricing->count = $_SESSION['searchdata']['adult'];

            $pricing->headPrice = str_replace('LKR', '', $index['@attributes']['BasePrice']);
            $pricing->headTax = str_replace('LKR', '', $index['@attributes']['Taxes']);
            $pricing->totalPrice = $pricing->count * $pricing->headPrice;
            $pricing->totalTax = $pricing->count * $pricing->headTax;
        } elseif (strcmp($airFareInfoList[0]['@attributes']['PassengerTypeCode'], 'CNN') == 0) {
            $pricing->pasType = 'Child';
            $pricing->count = $_SESSION['searchdata']['child'];
            $pricing->headPrice = str_replace('LKR', '', $index['@attributes']['BasePrice']);
            $pricing->headTax = str_replace('LKR', '', $index['@attributes']['Taxes']);
            $pricing->totalPrice = $pricing->count * $pricing->headPrice;
            $pricing->totalTax = $pricing->count * $pricing->headTax;
        } elseif (strcmp($airFareInfoList[0]['@attributes']['PassengerTypeCode'], 'INF') == 0) {
            $pricing->pasType = 'Infant';
            $pricing->count = $_SESSION['searchdata']['infant'];
            $pricing->headPrice = str_replace('LKR', '', $index['@attributes']['BasePrice']);
            $pricing->headTax = str_replace('LKR', '', $index['@attributes']['Taxes']);
            $pricing->totalPrice = $pricing->count * $pricing->headPrice;
            $pricing->totalTax = $pricing->count * $pricing->headTax;
        }
        $priceDet[$count] = $pricing;
        $otherDet[$count]=$otherDestails;
        //array_push($priceDet[$count],$pricing);
        $count++;
    }

    $pricingDetails->fareInfo = $priceDet;
    $pricingDetails->otherInfo=$otherDet;
   // error_log('getPriceDetails --- >'.print_r($pricingDetails,true));
    return $pricingDetails;
}
