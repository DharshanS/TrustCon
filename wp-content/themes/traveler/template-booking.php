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


/*--------------*/

require_once("library/cache.php");
$cache=new Cache;
$ptypefull=array('ADT'=>'Adult','CNN'=>'Child','INF'=>'Infant');

get_header();

$searchdata=$_SESSION['searchdata'];
if($searchdata['mode']=='oneway')
{
    error_log(" One way price initiated..............");
 include PLUG_DIR.'/service/LowFareAirPriceRequest.php';
//  include PLUG_DIR.'/service/RoundTripBookReq.php';
}
 else {
     error_log(" Round trip price initiated..............");
   include PLUG_DIR.'/service/RoundTripBookReq.php'; 
}

get_footer();



