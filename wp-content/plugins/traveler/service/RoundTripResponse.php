<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once(PLUG_DIR . 'models/RoundTrip.php');
require_once(PLUG_DIR . 'view/RoundTripView.php');
require_once(PLUG_DIR . 'utility/FlightUtility.php');

$airPricePointList;
$airSegmentList;
error_log("search data".print_r($_SESSION['searchdata'],true));
function roundTrip()
{
    error_log('ROUND TRIP SERVICE') ;
  global $airPricePointList;
  global $airSegmentList;

        $resp=file_get_contents(PLUG_DIR.'xml/roundTripXml.xml', FILE_USE_INCLUDE_PATH);
        $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
	$xml = simplexml_load_string($xml);
	$json = json_encode($xml);
        //error_log('encoded--->'.print_r($json,true));
	$responseArray = json_decode($json,true);
        $airPricePointList=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirPricePointList']['airAirPricePoint'];
        $airSegmentList=$responseArray['SOAPBody']['airLowFareSearchRsp']['airAirSegmentList']['airAirSegment'];
       airAirPricePointList();
}        
        
    
function getAirsegment($Key)
{
   global $airSegmentList;
   foreach($airSegmentList as $index)
   {
       if(strcmp($index['@attributes']['Key'],$Key)==0)
       {
           return $index;
       }
   }
   
}

function airAirPricePointList()
{
     global $airPricePointList;
     $util=new FlightUtility();
     //$tripArray;
     $count=0;
     foreach($airPricePointList as $index)
     {
            $trip = new Trip();
            
     if(isset($index['@attributes']))
     {
        $trip->basePrice=$index['@attributes']; 
     }

    
     
         
     if(isset($index['airAirPricingInfo']['airFlightOptionsList']['airFlightOption']))
         {
             $trip->airPrincingInfo=$index['airAirPricingInfo']['@attributes'];
               
            $outBoound = $index['airAirPricingInfo']['airFlightOptionsList']['airFlightOption'][0];
            $inBound = $index['airAirPricingInfo']['airFlightOptionsList']['airFlightOption'][1];
         
      
            
           //  error_log(print_r($outBoound,true));
             
            $temOutBound = $outBoound['airOption'];
            if (isset($temOutBound[0])) {
                $temOutBound = $temOutBound;
            } else {
                $temOutBound = array($temOutBound);
            }
            
            foreach ($temOutBound as $Key => $outer) {
              
                if (isset($outer['airBookingInfo'])) {
                        $outerTemp = $outer['airBookingInfo'];
                        if (isset($outerTemp[0])) {
                            $outerTemp = $outerTemp;
                        } else {
                            $outerTemp = array($outerTemp);
                        }

                        $tempOut=null;
                    foreach ($outerTemp as $inner) {

                        
                        if (isset($inner['@attributes']['SegmentRef'])) {
                            if ($Key == 0) {
                                $tem= getAirsegment($inner['@attributes']['SegmentRef']);
                                $cityOr=$util->getCityName($tem['@attributes']['Origin']);
                                $orTime=date("h:i a", strtotime($tem['@attributes']['DepartureTime']));
                                $orDate=date("D, d M Y", strtotime($tem['@attributes']['DepartureTime']));
                                
                                $cityDe=$util->getCityName($tem['@attributes']['Destination']);
                                $deTime=date("h:i a", strtotime($tem['@attributes']['ArrivalTime']));
                                $deDate=date("D, d M Y", strtotime($tem['@attributes']['ArrivalTime']));
                                $airline=$util->getAirLineName($tem['@attributes']['Carrier']);
                                array_push($tem['@attributes'],$inner['@attributes']);
                                
                                array_push($tem['@attributes'],$cityOr);
                                array_push($tem['@attributes'],$orTime);
                                array_push($tem['@attributes'],$orDate);
                                
                          
                                 array_push($tem['@attributes'],$cityDe);
                                 array_push($tem['@attributes'],$deTime);
                                 array_push($tem['@attributes'],$deDate);
                                 
                                 array_push($tem['@attributes'],$airline);
                                 
                                array_push($trip->bastOptionOut, $tem);
                            } else {
                                $tem= getAirsegment($inner['@attributes']['SegmentRef']);
                                $cityOr=$util->getCityName($tem['@attributes']['Origin']);
                                
                                 $orTime=date("h:i a", strtotime($tem['@attributes']['DepartureTime']));
                                 $orDate=date("D, d M Y", strtotime($tem['@attributes']['DepartureTime']));
                                 $cityDe=$util->getCityName($tem['@attributes']['Destination']);
                                 $deTime=date("h:i a", strtotime($tem['@attributes']['ArrivalTime']));
                                 $airline=$util->getAirLineName($tem['@attributes']['Carrier']);
                                 $deDate=date("D, d M Y", strtotime($tem['@attributes']['ArrivalTime']));
                                 
                                array_push($tem['@attributes'],$inner['@attributes']);

                                array_push($tem['@attributes'],$cityOr);
                                array_push($tem['@attributes'],$orTime);
                                array_push($tem['@attributes'],$orDate);
                                
                                    array_push($tem['@attributes'],$cityDe);
                                 array_push($tem['@attributes'],$deTime);
                                 array_push($tem['@attributes'],$deDate);
                                 array_push($tem['@attributes'],$airline);
                                 
                                $tempOut[] = $tem;
                               
                            }
                        }
                    }
                    if (isset($tempOut[0])) {

                        array_push($trip->outBound, $tempOut);
                    }
                }
            }
            
/*************************************************************************/
            $inBoundTemp = $inBound['airOption'];
            if (isset($inBoundTemp[0])) {
                $inBoundTem = $inBoundTemp;
            } else {
                $inBoundTem = array($inBoundTemp);
            }
            foreach($inBoundTem as $Key=>$outer)
            {
                if (isset($outer['airBookingInfo'])) {
                   
                    if (isset($outer['airBookingInfo'][0])) {
                        $outerTemp = $outer['airBookingInfo'];
                    } else {
                        $outerTemp = array($outer['airBookingInfo']);
                    }
                     $temp = null;
                    foreach ($outerTemp as $inner) {
                        if (isset($inner['@attributes']['SegmentRef'])) {

                            if ($Key == 0) {
                                $tem=getAirsegment($inner['@attributes']['SegmentRef']);
                                array_push($tem['@attributes'],$inner['@attributes']);
                                 $orTime=date("h:i a", strtotime($tem['@attributes']['DepartureTime']));
                                 $orDate=date("D, d M Y", strtotime($tem['@attributes']['DepartureTime']));
                                 $cityDe=$util->getCityName($tem['@attributes']['Destination']);
                                 $deTime=date("h:i a", strtotime($tem['@attributes']['ArrivalTime']));
                                 $airline=$util->getAirLineName($tem['@attributes']['Carrier']);
                                 $deDate=date("D, d M Y", strtotime($tem['@attributes']['ArrivalTime']));
                                 array_push($tem['@attributes'],$cityOr);
                                    array_push($tem['@attributes'],$orTime);
                                    array_push($tem['@attributes'],$orDate);
                                
                                 array_push($tem['@attributes'],$cityDe);
                                 array_push($tem['@attributes'],$deTime);
                                 array_push($tem['@attributes'],$deDate);
                                 array_push($tem['@attributes'],$airline);
                                array_push($trip->bastOptionIn,$tem);
                          
                            } else {
                                $tem=getAirsegment($inner['@attributes']['SegmentRef']);
                                 array_push($tem['@attributes'],$inner['@attributes']);
                                 $orTime=date("h:i a", strtotime($tem['@attributes']['DepartureTime']));
                                 $orDate=date("D, d M Y", strtotime($tem['@attributes']['DepartureTime']));
                                 $cityDe=$util->getCityName($tem['@attributes']['Destination']);
                                 $deTime=date("h:i a", strtotime($tem['@attributes']['ArrivalTime']));
                                 $airline=$util->getAirLineName($tem['@attributes']['Carrier']);
                                 $deDate=date("D, d M Y", strtotime($tem['@attributes']['ArrivalTime']));
                                 array_push($tem['@attributes'],$cityOr);
                                    array_push($tem['@attributes'],$orTime);
                                    array_push($tem['@attributes'],$orDate);
                                
                                 array_push($tem['@attributes'],$cityDe);
                                 array_push($tem['@attributes'],$deTime);
                                 array_push($tem['@attributes'],$deDate);
                                 array_push($tem['@attributes'],$airline);
                                $temp[] = $tem;
                            }
                        }
                    }
                    if (isset($temp[0])) {

                        array_push($trip->inBound, $temp);
                    }
                }
            }
            
       
             $count++;
            $tripArray[]=$trip;     
         }
         
       }
           
     
      roundTripView($tripArray);

}