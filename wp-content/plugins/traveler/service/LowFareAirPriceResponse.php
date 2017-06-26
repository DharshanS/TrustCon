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
    global $airsegment;
    global $airPricingInfo;


    $airsegment=$response['SOAPBody']['airAirPriceRsp']['airAirItinerary']['airAirSegment'];
    $airPricingInfo=$response['SOAPBody']['airAirPriceRsp']['airAirPriceResult']['airAirPricingSolution'];

   $pricingData=airSegment();
   $_SESSION['price']=serialize($pricingData);
    
   return   $pricingData;
 
}




function getFlightDetails($index) {

    global $airsegment;
   
    $airsegmentList='';
    if (isset($airsegment[0])) {
        $airsegmentList = $airsegment;
    } else {
        $airsegmentList = array($airsegment);
    }

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
    $pricingFly = new PricingFly();
    $loop=1;
if(isset($airPricingInfoList[0]['airAirSegmentRef'][0]))
{
 $loop=count($airPricingInfoList[0]['airAirSegmentRef']);   
}

$outBound=array();
$inbound=array();
    for ($count=0;$count<$loop;$count++) {

        if(isset($airPricingInfoList[0]['airAirPricingInfo'][0]))
        {
        $airbookingInfo=$airPricingInfoList[0]['airAirPricingInfo'][0]['airBookingInfo'][$count];   
        }
        else
        {
         $airbookingInfo=$airPricingInfoList[0]['airAirPricingInfo']['airBookingInfo'];  
        }
        
        $temp = getFlightDetails($count);
        array_push($temp['@attributes'], $airbookingInfo);
        if($temp['@attributes']['Group']==0)
        {
            $outBound[]=$temp;
        }else{
            $inbound[]=$temp;
        }
       

    }
$flightDetails[]=$outBound;
$flightDetails[]=$inbound;
   
    $pricingFly->airDetails = $flightDetails;
    
   $pricingFly->priceDetails= getPriceDetails();
   return $pricingFly;
}

function getPriceDetails() {

    
    global $airPricingInfo;
    $db_util=new DbUtility();

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

    foreach ($airPricingInfoList as $index) {

        $pricing = new Pricing();
        $otherDestails = new OtherInfo();
        $otherDestails->airBookingInfo = $index['airBookingInfo'];
        $otherDestails->airTaxInfo = $index['airTaxInfo'];
        $otherDestails->airFareCalc = $index['airFareCalc'];
         if(isset($index['airPassengerType']))
        $otherDestails->airPassengerType = $index['airPassengerType'];
        if(isset($index['airChangePenalty']))
        $otherDestails->airChangePenalty = $index['airChangePenalty'];
         if(isset($index['airCancelPenalty']))
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
            $pricing->count =$_SESSION['searchdata']['child'];
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

        $count++;
    }

    $pricingDetails->fareInfo = $priceDet;
     $pricingDetails->otherInfo=$otherDet;
    $pricingDetails->webFareDiscount=$db_util->get_discount($_SESSION['air_line']);
    $pricingDetails->serviceCharge=$db_util->get_service_charge();
    
    return $pricingDetails;
}
