<?php
/*
Template Name: payroute
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 */
//session_start();

error_log("..............routed pay response ..............");
include PLUG_DIR."paycorp-client-php/au.com.gateway.IT/pcw_payment-complete_paynow_UT.php";
