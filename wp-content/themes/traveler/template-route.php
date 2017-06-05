<?php
/*
Template Name: route
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 */
get_header();
include PLUG_DIR."paycorp-client-php/au.com.gateway.IT/pcw_payment-complete_paynow_UT.php";
get_footer();