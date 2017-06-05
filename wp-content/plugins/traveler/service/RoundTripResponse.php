<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once(PLUG_DIR . 'models/RoundTrip.php');
require_once(PLUG_DIR . 'view/RWSDisplay.php');
require_once(PLUG_DIR . 'utility/FlightUtility.php');

$airPricePointList;
$airSegmentList;
$tripObj;
error_log("search data" . print_r($_SESSION['searchdata'], true));
function roundTrip($responseArray)
{
    error_log('ROUND TRIP SERVICE');
    global $airPricePointList;
    global $airSegmentList;

    $airPricePointList = $responseArray['SOAPBody']['airLowFareSearchRsp']['airAirPricePointList']['airAirPricePoint'];
    $airSegmentList = $responseArray['SOAPBody']['airLowFareSearchRsp']['airAirSegmentList']['airAirSegment'];
    airAirPricePointList();
}


function getAirsegment($Key)
{
    global $airSegmentList;
    foreach ($airSegmentList as $index) {
        if (strcmp($index['@attributes']['Key'], $Key) == 0) {
            return $index;
        }
    }

}

function airAirPricePointList()
{
    global $airPricePointList;
    $util = new FlightUtility();
    $count = 0;
    $flag = false;
    $tripArray = array();
    for ($out = 0; $out < count($airPricePointList); $out++) {

        if (isset($airPricePointList[$out]) && !empty($airPricePointList[$out])) {
            $tripArray[] = get_trip_fly($airPricePointList[$out]);
            $flag = true;
        }
        for ($in = $out + 1; $in < count($airPricePointList); $in++) {
            //  error_log($airPricePointList[$out]['airAirPricingInfo']['@attributes']['PlatingCarrier']."===".$airPricePointList[$in]['airAirPricingInfo']['@attributes']['PlatingCarrier']);
            if (!empty($airPricePointList[$out]) && ($airPricePointList[$out]['airAirPricingInfo']['@attributes']['PlatingCarrier'] === $airPricePointList[$in]['airAirPricingInfo']['@attributes']['PlatingCarrier'])) {


                $tripArray[$count]->moreFly[] = get_trip_fly($airPricePointList[$in]);


                $airPricePointList[$in] = null;


            }
        }
        if ($flag) {
            $count++;
            $flag = false;
        }
//         if($out==3)
//         {
//          error_log(print_r(json_encode($tripArray),true));
//          return;
//         }
    }


    init_display($tripArray);

}


function get_trip_fly($index)
{

    global $tripObj;
    $tripObj = new Trip();

    if (isset($index['@attributes'])) {
        $tripObj->basePrice = $index['@attributes'];
    }

    if (isset($index['airAirPricingInfo']['airFlightOptionsList']['airFlightOption'])) {
        $tripObj->airPrincingInfo = $index['airAirPricingInfo']['@attributes'];

        $outBoound = $index['airAirPricingInfo']['airFlightOptionsList']['airFlightOption'][0];
        $inBound = $index['airAirPricingInfo']['airFlightOptionsList']['airFlightOption'][1];

        $temOutBound = $outBoound['airOption'];
        if (isset($temOutBound['@attributes'])) {
            $temOutBound = array($temOutBound);
        } else if (isset($temOutBound['airBookingInfo']['@attributes'])) {
            $temOutBound = array($temOutBound['airBookingInfo']);
        } else if (isset($temOutBound['airBookingInfo'][0])) {
            $temOutBound = $temOutBound['airBookingInfo'];
        }

        $tripObj = get_outbond_fly($temOutBound, $tripObj);


        /*************************************************************************/
        $inBoundTemp = $inBound['airOption'];

        if (isset($inBoundTem['airBookingInfo']['@attributes'])) {
            $inBoundTemp = array($inBoundTemp['airBookingInfo']);
        } else if (isset($inBoundTemp['airBookingInfo'][0])) {
            $inBoundTemp = array($inBoundTemp['airBookingInfo']);
        } else if (isset($inBoundTemp['@attributes'])) {
            $inBoundTemp = array($inBoundTemp);
        }

        $tripObj = get_inbond_fly($inBoundTemp, $tripObj);


        //return;
    }

    return $tripObj;
}


function get_outbond_fly($out_bound, $tripObj)
{
    global $tripObj;

    foreach ($out_bound as $key => $out) {
        $bookInfo = '';
        if (isset($out['airBookingInfo'][0])) {
            $bookInfo = $out['airBookingInfo'];
        } else if (isset($out['airBookingInfo']['@attributes'])) {
            $bookInfo = array($out['airBookingInfo']);
        } else if (isset($out['@attributes'])) {
            $bookInfo = array($out);
        } else {
            $bookInfo = $out;
        }

        $temp = '';

        foreach ($bookInfo as $bookInfoItem) {


            $temp[] = getAirsegment($bookInfoItem['@attributes']['SegmentRef']);
            array_push($temp[count($temp) - 1]['@attributes'], $bookInfoItem['@attributes']);
        }
        if ($key == 0) {
            $tripObj->bastOptionOut = $temp;

        } elseif (isset($temp[0])) {
            array_push($tripObj->outBound, $temp);
        }
    }
    return $tripObj;
}

function get_inbond_fly($in_bound, $tripObj)
{
    global $tripObj;
    foreach ($in_bound as $key => $out) {
        $bookInfo = '';
        if (isset($out['airBookingInfo'][0])) {
            $bookInfo = $out['airBookingInfo'];
        } else if (isset($out['airBookingInfo']['@attributes'])) {
            $bookInfo = array($out['airBookingInfo']);
        } elseif (isset($out['@attributes'])) {
            $bookInfo = array($out);
        } else {
            $bookInfo = $out;
        }


        $temp = '';
        foreach ($bookInfo as $bookInfoItem) {
            $temp[] = getAirsegment($bookInfoItem['@attributes']['SegmentRef']);
            array_push($temp[count($temp) - 1]['@attributes'], $bookInfoItem['@attributes']);
        }

        if ($key == 0) {
            $tripObj->bastOptionIn = $temp;
        } elseif (isset ($temp[0])) {
            array_push($tripObj->inBound, $temp);
        }
    }
    return $tripObj;
}
     
