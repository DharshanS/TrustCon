<?php
/*
Template Name: PayRoute
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 */
get_header();
error_log("Routed to Pay ..............");
include PLUG_DIR."paycorp-client-php/au.com.gateway.IT/pcw_payment-complete_paynow_UT.php";
get_footer();