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
function processResponse($response)
{
   // error_log('Price Response --- >'.print_r($response,true));
    global $airsegment;
    global $airPricingInfo;


    $airsegment=$response['SOAPBody']['airAirPriceRsp']['airAirItinerary']['airAirSegment'];
    $airPricingInfo=$response['SOAPBody']['airAirPriceRsp']['airAirPriceResult']['airAirPricingSolution'];
   // $airFareNote=$response['SOAPBody']['airAirPriceRsp']['airAirPriceResult']['airAirPricingSolution']['airFareNote'];

   $pricingData=airSegment();
   $_SESSION['pricingData']=serialize($pricingData);
    
   return   $pricingData;
 
}




function getFlightDetails($index) {
   //  error_log("Price details -->".$index);
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

    $db_util=new DbUtility();

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
        //array_push($priceDet[$count],$pricing);
        $count++;
    }

    $pricingDetails->fareInfo = $priceDet;
     $pricingDetails->otherInfo=$otherDet;


    return $pricingDetails;
}
//
//$SQL = 'SELECT discount FROM airlines WHERE airline = "'.$thisairlinename.'" ';
//$result = $mysqli->query($SQL);
//$row = $result->fetch_object();
//$discount = $row->discount;
//if($discount>0)
//{
// $disAmt = ($grandbaseprice / 100) * $discount;
//}
//if($disAmt){
//	$discountamount=$currency."".number_format($disAmt);
//}else{
//	$discountamount=0;
//}
//$netprice= $grandtotalprice - $disAmt;
//$netpricewithcurrency=$currency."".number_format($netprice);
//
//
//
//// insert into wp post table
//
//// discount
//$disAmt=0;
//foreach($priceresult['BookingInfo'] as $BI){
//$segmentref=$BI['SegmentRef'];
//$skey=getSegmentKey($segmentref,$AirSegment);
//$segmentdtls=$airsegments[$skey];	
//// only departure leg
//if($segmentdtls['Group']==0){
//$thisCarrier=$segmentdtls['Carrier'];
//$thisairlinename=$airlines[$thisCarrier];
//break;
//}}
//
//if(count($priceresult)){
//$FareInfoRef=$priceresult['FareInfo']['0']['Key'];
//$FareRuleKey=$priceresult['FareInfo']['0']['FareRuleKey'];
//$BaggageRestriction=$priceresult['BaggageRestriction'];
//
//$EquivalentBasePrice=$priceresult['EquivalentBasePrice'];
//$currency=substr($EquivalentBasePrice,0,3);
//$EquivalentBasePrice=substr($EquivalentBasePrice,3);
//$EquivalentBasePrice=$currency."".number_format($EquivalentBasePrice);
//
//$Taxes=$priceresult['Taxes'];
//$currency=substr($Taxes,0,3);
//$Taxes=substr($Taxes,3);
//$Taxes=$currency."".number_format($Taxes);
//
//$TotalPrice=$priceresult['TotalPrice'];
//$tPrice=$TotalPrice;
//$currency=substr($TotalPrice,0,3);
//$TotalPrice=substr($TotalPrice,3);
//$TotalPrice=$currency."".number_format($TotalPrice);
//
//// discount
//foreach($priceresult['BookingInfo'] as $BI){
//$segmentref=$BI['SegmentRef'];
//$skey=getSegmentKey($segmentref,$AirSegment);
//$segmentdtls=$airsegments[$skey];	
//// only departure leg
//if($segmentdtls['Group']==0){
//$thisCarrier=$segmentdtls['Carrier'];
//$thisairlinename=$airlines[$thisCarrier];
//break;
//}}
//$res = 'SELECT discount FROM airlines WHERE airline = "'.$thisairlinename.'" ';
//$result = $mysqli->query($res); //$wpdb->get_results($res);
//$row = $result->fetch_object();
//$discount = $row->discount;
//if($discount>0)
//{
////$disAmt = (substr($tPrice,3) / 100) * $discount;
//$disAmt = ($grandbaseprice / 100) * $discount;
//}else{
//	$disAmt=0;
//}
//$netprice=substr($tPrice,3) - $disAmt;
//$netprice=$grandtotalprice - $disAmt;