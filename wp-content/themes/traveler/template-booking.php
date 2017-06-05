<?php
/*
Template Name: Booking
*/
/*
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Page.php
 *
 * Created by ShineTheme
 *
 */
//session_start();
set_time_limit(0);
date_default_timezone_set('Asia/Colombo');

//$endpoint='https://apac.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService'; // Production end point
//$endpoint='https://twsprofiler.travelport.com/Service/Default.ashx/AirService'; // Profiler end point

$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService'; // LIVE end point

$GENERATELOG=1; // 1 OR 0

/*$TARGETBRANCH = 'P7040105';
$CREDENTIALS = 'Universal API/uAPI6986631472-c8b85ad8:P+t2rJ7&M6';
$PCC='3OS2';
$Provider = '1G'; // Any provider you want to use like 1G/1P/1V/ACH*/

/*--------LIVE CREDENTIAL ------*/
$TARGETBRANCH = 'P2721018'; 
$CREDENTIALS = 'Universal API/uAPI4655248065-02590718:nA?9{c3YC8';
$PCC='3OS2';
$Provider = '1G'; 
/*--------------*/

require_once("library/cache.php");
$cache=new Cache;
$ptypefull=array('ADT'=>'Adult','CNN'=>'Child','INF'=>'Infant');



error_log("BOOKING INVOKED".print_r($_SESSION['searchdata'],true));
get_header();

$searchdata=$_SESSION['searchdata'];
if($searchdata['mode']=='oneway')
{
    error_log(" One Way ");
 include PLUG_DIR.'/service/LowFareAirPriceRequest.php';   
}
 else {
   include PLUG_DIR.'/service/RoundTripBookReq.php'; 
}

get_footer();



