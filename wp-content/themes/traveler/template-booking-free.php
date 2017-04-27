<?php
/*
Template Name: FreeBA BABooking
*/
/*
Abdul Manashi
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
set_time_limit(0);
get_header();

error_log("BOOKING INVOKED");
include PLUG_DIR.'/service/FreeBookingRequest.php';

get_footer();