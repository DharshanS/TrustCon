<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$flightResult = array();
$airSegmentList = array();
$flightDetails = array();

$airAirPricePointList = array();
//$to_city = $_POST['to_city'];
include_once('/models/OnewayFlight.php');
include_once '/view/SearchView.php';

function initSearch($json) {
    error_log("initSearch.............");

    global $flightResult;
    global $airSegmentList;
    global $flightDetails;
    global $airAirPricePointList;

    $flightResult = $json;
    $airSegmentList = $flightResult['SOAPBody']['airLowFareSearchRsp']['airAirSegmentList']['airAirSegment'];
    $flightDetails = $flightResult['SOAPBody']['airLowFareSearchRsp']['airFlightDetailsList']['airFlightDetails'];
    $airAirPricePointList = $flightResult['SOAPBody']['airLowFareSearchRsp']['airAirPricePointList']['airAirPricePoint'];
//flightDetails();
//airPricePointList();
 return genarate_html_view(airPricePointList());
}

function airPricePointList() {
    global $airAirPricePointList;
    // error_log("airPricePointList.............".print_r($airAirPricePointList,true));
    return addingSameALtogether($airAirPricePointList);
}

function addingSameALtogether($airAirPricePointList) {


    $fDeatails = array();

    for ($index = 0; $index < count($airAirPricePointList); $index++) {
        //getsegRef($airAirPricePointList[$index]);
       if($index==1)
       {
        array_push($fDeatails, getFlightObject(getsegRef($airAirPricePointList[$index]), $airAirPricePointList[$index]['@attributes']));
        //  error_log(" ----adding----> ".print_r($fDeatails,true));
        return $fDeatails;
       }
    }
    return $fDeatails;
}

function airSegmentList($segmentRefKey) {
    global $airSegmentList;
    for ($jndex = 0; $jndex < count($airSegmentList); $jndex++) {

        if (isset($airSegmentList[$jndex]['@attributes']) &&
                (0 == strcmp($airSegmentList[$jndex]['@attributes']['Key'], $segmentRefKey))) {
            $airSegment = $airSegmentList[$jndex];
                    //  error_log("airSegment ---> " . print_r($airSegment, true));
            return $airSegment;
        }
    }
}

function getsegRef($airAirPricePointList) {
    $segmentList = $airAirPricePointList['airAirPricingInfo']['airFlightOptionsList']['airFlightOption']['airOption'];

   // error_log("getsegRef ---> " . print_r($segmentList, true));

    if (isset($segmentList['airBookingInfo']['@attributes'])) {
        $temp = array();
        $temp = airSegmentList($segmentList['airBookingInfo']['@attributes']['SegmentRef']);
        $flyDe = flightDetails($temp['airFlightDetailsRef']['@attributes']['Key'], $temp['@attributes']['Origin'], $temp['@attributes']['Destination']);
        array_push($temp['@attributes'], $segmentList['airBookingInfo']['@attributes']);
        array_push($temp['@attributes'], $flyDe);


        return $collection = array('iternary' => $temp);
        //error_log("collection ---> " . print_r($collection, true));
    } else if (count($segmentList) > 1) {

        $tem_3 = array();
        $listTemp = array();
        foreach ($segmentList as $segIndex) {
            $tempSeg;
            if (isset($segIndex['airBookingInfo']['@attributes'])) {
                $temp = array();
                $temp = airSegmentList($segIndex['airBookingInfo']['@attributes']['SegmentRef']);
                $flyDe = flightDetails($temp['airFlightDetailsRef']['@attributes']['Key'], $temp['@attributes']['Origin'], $temp['@attributes']['Destination']);
                // error_log("airOption-->" . print_r($temp, true));
                array_push($temp['@attributes'], $segIndex['airBookingInfo']['@attributes']);
                array_push($temp['@attributes'], $flyDe);

                array_push($listTemp, $temp);
            } elseif (isset($segIndex['airBookingInfo'])) {
                $tempSeg = $segIndex['airBookingInfo'];
                $tem_0 = array();
                // error_log("1st for loop if else --->".PRINT_R($tempSeg,TRUE));
                foreach ($tempSeg as $jex) {
                    $temp = array();
                    if (isset($jex['@attributes']['SegmentRef'])) {
                        //   error_log("1st for loop if --->");
                        $temp = airSegmentList($jex['@attributes']['SegmentRef']);
                        $flyDe = flightDetails($temp['airFlightDetailsRef']['@attributes']['Key'], $temp['@attributes']['Origin'], $temp['@attributes']['Destination']);
                        array_push($temp['@attributes'], $jex['@attributes']);
                        array_push($temp['@attributes'], $flyDe);
                        $tem_0[] = $temp;
                    } elseif (isset($jex['@attributes'])) {

                        $temp = airSegmentList($jex['SegmentRef']);
                        $flyDe = flightDetails($temp['airFlightDetailsRef']['@attributes']['Key'], $temp['@attributes']['Origin'], $temp['@attributes']['Destination']);
                        array_push($temp['@attributes'], $jex['@attributes']);
                        array_push($temp['@attributes'], $flyDe);
                        $tem_0[] = $temp;
                    }
                }

                array_push($listTemp, $tem_0);
            } elseif (isset($segIndex[0])) {

                if (isset($segIndex))
                    $tempSeg = $segIndex;
                // error_log("tempSeg-->" . print_r($tempSeg, true));
                $tem_1 = array();
                foreach ($tempSeg as $jex) {
                    $temp = array();
                    if (isset($jex['@attributes']['SegmentRef'])) {
                        $temp = airSegmentList($jex['@attributes']['SegmentRef']);
                        array_push($temp['@attributes'], $jex['@attributes']);
                        $flyDe = flightDetails($temp['airFlightDetailsRef']['@attributes']['Key'], $temp['@attributes']['Origin'], $temp['@attributes']['Destination']);
                        array_push($temp['@attributes'], $airAirPricePointList['airAirPricingInfo']['@attributes']);
                        array_push($temp['@attributes'], $flyDe);
                        $tem_1[] = $temp;
                    }
                }
                array_push($listTemp, $tem_1);
            } elseif (isset($segIndex['airBookingInfo']['@attributes']['SegmentRef'])) {
                $temp = array();
                $temp = airSegmentList($segIndex['airBookingInfo']['@attributes']['SegmentRef']);
                array_push($temp['@attributes'], $airAirPricePointList['airAirPricingInfo']['@attributes']);
                $flyDe = flightDetails($temp['airFlightDetailsRef']['@attributes']['Key'], $temp['@attributes']['Origin'], $temp['@attributes']['Destination']);
                array_push($temp['@attributes'], $jex['@attributes']);
                array_push($temp['@attributes'], $flyDe);
                array_push($listTemp, $temp);
            }
        }

        //   error_log("collection listTemp ---> " . print_r($listTemp, true));
        $collection = array('iternary' => $listTemp);
        //   error_log("collection else if ---> " . print_r($collection, true));
    }
    // error_log("collection listTemp ---> " . print_r($collection, true));
    //error_log("collection return ---> " . print_r($collection, true));
    return $collection;
}

function getFlightObject($flightList, $iternary) {

 // error_log("getFlightObject 1st --> ".PRINT_R($flightList,TRUE) );
    $flightObjectList=array();
    if (isset($flightList['@attributes'])) {
     //    error_log("getFlightObject 1st --> " );
 // error_log("getFlightObject 1st --> " );
        array_push($flightObjectList,creatFlightObject($fly,$iternary));
    } elseif (isset($flightList['iternary'][0][0])) {
 
        foreach ($flightList['iternary'] as $fly) {
 //error_log("getFlightObject 2nd --> ".print_r($fly,true) );
           array_push($flightObjectList,creatFlightObject($fly,$iternary));
          // error_log("view upper--> " . print_r(creatFlightObject($fly,$iternary), true));
    }}
     elseif (isset($flightList['iternary'][0])) {
  //error_log("getFlightObject 3rd --> " );
        foreach ($flightList['iternary'] as $fly) {

           array_push($flightObjectList,creatFlightObject($fly,$iternary));
          // error_log("view upper--> " . print_r(creatFlightObject($fly,$iternary), true));
    }}
        elseif(isset($flightList['iternary']['@attributes']))
        { // error_log("getFlightObject 4th --> " );
               array_push($flightObjectList,creatFlightObject($flightList['iternary']['@attributes'],$iternary));
        }
    // error_log("getFlightObject RETURN -->".PRINT_R($flightObjectList,TRUE));
    return $flightObjectList;
}

function creatFlightObject($flight,$iternary) {

    // error_log("creatFlightObject --> " . print_r($airAirPricingInfo, true));
     // error_log("creatFlightObject --> " . count($flight));
   

      
      
    if(isset($flight[0]['@attributes']) && !EMPTY($flight[0]['@attributes']['Key']) )
    {
      
      
    $originPos=0;
    $arrivalPos=count($flight)-1;
  //  error_log("view single --> " . print_r($flight[$arrivalPos]['@attributes'], true));
    
    $flyIternary = new Flight();
    $flyIternary->totalPrice = $iternary['TotalPrice'];
    $flyIternary->basePrice = $iternary['BasePrice'];
    $flyIternary->taxes = $iternary['Taxes'];
    // $flyIternary->airline = $airAirPricingInfo['PlatingCarrier'];

    $flyIternary->orgin = $flight[$originPos]['@attributes']['Origin'];
    $flyIternary->depatureTime = date("h:i a", strtotime($flight[$originPos]['@attributes']['DepartureTime']));
    $flyIternary->depatureDate = date("D, d M Y", strtotime($flight[$originPos]['@attributes']['DepartureTime']));
    $flyIternary->arrivalTime = date("h:i a", strtotime($flight[$arrivalPos]['@attributes']['ArrivalTime']));
    $flyIternary->arrivalDate = date("D, d M Y", strtotime($flight[$arrivalPos]['@attributes']['ArrivalTime']));
    $flyIternary->journyTime = FlightUtility::getTimeDiff($flyIternary->depatureTime, $flyIternary->arrivalTime);
    $flyIternary->airline = $flight[$originPos]['@attributes']['Carrier'];
    $flyIternary->stops = 1;
    $flyIternary->searchData=$_SESSION['searchdata'];
    $tem = array();
    foreach ($flight as $index) {

        $temp[]= $index['@attributes'];
    }
    $flyIternary->flightDetails=$temp;
    //error_log("view single --> " . print_r($flyIternary, true));
   return  $flyIternary;
 
    }
    elseif(!empty($flight))
    {
      if(isset($flight['@attributes']))
      {
          $flight=$flight['@attributes'];
      }
 // error_log("view single --> " . print_r($flight, true));
    $flyIternary = new Flight();
    $flyIternary->totalPrice = $iternary['TotalPrice'];
    $flyIternary->basePrice = $iternary['BasePrice'];
    $flyIternary->taxes = $iternary['Taxes'];
   // $flyIternary->airline = $airAirPricingInfo['PlatingCarrier'];
    $flyIternary->orgin = $flight['Origin'];
    $flyIternary->depatureTime = date("h:i a", strtotime($flight['DepartureTime']));
    $flyIternary->depatureDate = date("D, d M Y", strtotime($flight['DepartureTime']));
    $flyIternary->arrivalTime = date("h:i a", strtotime($flight['ArrivalTime']));
    $flyIternary->arrivalDate = date("D, d M Y", strtotime($flight['ArrivalTime']));
    $flyIternary->journyTime = FlightUtility::getTimeDiff($flyIternary->depatureTime, $flyIternary->arrivalTime);
    $flyIternary->airline = $flight['Carrier'];
    $flyIternary->stops = 1; 
    $flyIternary->flightDetails=$flight;
    $flyIternary->searchData=$_SESSION['searchdata'];
   // error_log("view single --> " . print_r($flyIternary, true));
   return  $flyIternary;
    }
     
    
    
 
 
}
function flightDetails($key,$origin,$destination)
 {
     //error_log('flightDetails--->');
     global $flightDetails;
     foreach ($flightDetails as $fly)
     {
         if(strcmp($fly['@attributes']['Key'], $key)==0 && strcmp($fly['@attributes']['Origin'], $origin)==0 && strcmp($fly['@attributes']['Destination'], $destination)==0)
         {
             return $fly['@attributes'];
         }
     }

 }