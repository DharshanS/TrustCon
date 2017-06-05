<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$resp = file_get_contents('C:\xampp\htdocs\travel\wp-content\plugins\traveler\xml\LowFareSearchRsp.xml', FILE_USE_INCLUDE_PATH);
$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
$xml = simplexml_load_string($xml);
$json = json_encode($xml);
//zecho('Response Json--->' . print_r($json, true));
$responseArray = json_decode($json, true);

require_once PLUG_DIR . 'models/OWFly.php';
require_once PLUG_DIR . 'view/OWSDisplay.php';

$airAirSegmentList;
$airSegmentList;
$airFlightDetailsList;
init($responseArray);

function init($response) {

    global $airAirPricePointList;
    global $airAirSegmentList;
    global $airFlightDetailsList;
    //identifying price segments
    $airAirSegmentList = $response['SOAPBody']['airLowFareSearchRsp']['airAirSegmentList']['airAirSegment'];
    $airFlightDetailsList = $response['SOAPBody']['airLowFareSearchRsp']['airFlightDetailsList']['airFlightDetails'];
    $airAirPricePointList = $response['SOAPBody']['airLowFareSearchRsp']['airAirPricePointList']['airAirPricePoint'];
   
    //error_log(print_r(airAirPricePointList(),true));
    init_display(airAirPricePointList());
}

function airAirPricePointList() {
    //check same irline get the flight deatails 
    global $airAirPricePointList;
    $modified_airAirPricePointList = array();
    $flyList="";

    for ($out = 0; $out < count($airAirPricePointList); $out++) {
        $flight="";
        $secFlag = false;
        $secFlyList="";
        $created=false;
        if (isset($airAirPricePointList[$out]) && !empty($airAirPricePointList[$out])) {
             $flight= new OWFly();
            $bestFly = new BesFly();
            $bestFly->priceDetails = $airAirPricePointList[$out];
            $bestFly->segDetails = airFlightOptionsList($airAirPricePointList[$out]['airAirPricingInfo']['airFlightOptionsList']);
             $flight->bestFly = $bestFly;
             $created=true;
        }

        for ($in = $out + 1; $in < count($airAirPricePointList); $in++) {

            //error_log($airAirPricePointList[$out]['airAirPricingInfo']['@attributes']['PlatingCarrier'].'=='.$airAirPricePointList[$in]['airAirPricingInfo']['@attributes']['PlatingCarrier']);
            if ($airAirPricePointList[$out]['airAirPricingInfo']['@attributes']['PlatingCarrier'] === $airAirPricePointList[$in]['airAirPricingInfo']['@attributes']['PlatingCarrier'] && $airAirPricePointList[$in]['airAirPricingInfo']['@attributes']['PlatingCarrier'] != null) {
                $secFly = new SecFly();
                $secFly->priceDetails = $airAirPricePointList[$in];
                $secFly->segDetails = airFlightOptionsList($airAirPricePointList[$out]['airAirPricingInfo']['airFlightOptionsList']);
                $secFlyList[] = $secFly;
                $airAirPricePointList[$in] = null;
                $secFlag = true;
            }
        }
       
        if ($secFlag) {
            $flight->secFly = $secFlyList;
        }
        if($created)
        {
        $flyList[] = $flight;
        }
    }
    return $flyList;
}

function airFlightOptionsList($airFlightOptionsList) {

    // error_log('airFlightOptionsList :'.print_r($airFlightOptionsList,true));
    $flyOpt=new FlyOpt();
    $airItemList;
    $airOptionList = $airFlightOptionsList['airFlightOption']['airOption'];
    if (isset($airOptionList['@attributes'])) {
        $airOptionList = array($airOptionList);
    }



    foreach ($airOptionList as $index) {
        $airBookingInfoList = $index;
        if (isset($airBookingInfoList['airBookingInfo']['@attributes'])) {
            $airBookingInfoList = array($airBookingInfoList);
        }
          $tempM;
        foreach ($airBookingInfoList as $Key => $airItem) {

            // error_log('airbook : '.print_r($airItem,true));
           
            if (isset($airItem['airBookingInfo']['@attributes']['SegmentRef'])) {
                $tempM[$Key]= airSegment($airItem['airBookingInfo']['@attributes']['SegmentRef']);
                array_push($tempM[$Key], $airItem);
               // $airItemList[]=$tempM;
            } elseif (isset($airItem['@attributes']['SegmentRef'])) {
                $tempM[$Key] = airSegment($airItem['@attributes']['SegmentRef']);
               array_push($tempM[$Key], $airItem);
              // $airItemList[]=$tempM[$Key];
            } elseif (isset($airItem[0])) {
               
                foreach ($airItem as $k => $air) {
                    if (isset($air['@attributes']['SegmentRef'])) {
                        $tempM[$k] = airSegment($air['@attributes']['SegmentRef']);
                        array_push($tempM[$k], $air);
                    }
                }
               // $tempM[]=$temp;
       
            }
           
        }
        $airItemList[]=$tempM;  
        $tempM=null;
     
    }



    return $airItemList;
}

function airSegment($infoKey) {
    global $airAirSegmentList;

    foreach ($airAirSegmentList as $index) {

        if ($index['@attributes']['Key'] == $infoKey) {
            return $index;
        }
    }
}
