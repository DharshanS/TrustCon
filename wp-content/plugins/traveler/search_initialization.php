<?php

/**
  Plugin Name: Traveler Ajax
  Plugin URI: http://localhost:8080/travel
  /*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once(plugin_dir_path( __FILE__ ) . 'Constants.php');

error_log("SearchInitilization.................");
add_action("wp_ajax_search_flights", "search_flights");
add_action("wp_ajax_nopriv_search_flights", "search_flights");
function search_flights() {
     error_log("search_flights.................".plugin_dir_path(__FILE__) . '/search_init.php');
    return require(plugin_dir_path(__FILE__) . '/search_init.php' );
}



add_action("wp_ajax_makeReservation", "makeReservation");
add_action("wp_ajax_nopriv_makeReservation", "makeReservation");
function makeReservation() {
    return require(plugin_dir_path(__FILE__) . '/service/ReservationRequest.php' );
}

add_action("wp_ajax_paymentPage", "paymentPage");
add_action("wp_ajax_nopriv_paymentPage", "paymentPage");
function paymentPage() {
    return require(plugin_dir_path(__FILE__) . '/service/Payment.php' );
}



add_action("wp_ajax_getCity", "getCity");
add_action("wp_ajax_nopriv_getCity", "getCity");
function getCity() {
    return require(plugin_dir_path(__FILE__) . '/core/GetCity.php' );
}
add_action("wp_ajax_addCity", "addCity");
add_action("wp_ajax_nopriv_addCity", "addCity");
function addCity() {
    return require(plugin_dir_path(__FILE__) . '/core/AddCity.php' );
}


add_action("wp_ajax_StepThree", "process_Three");
add_action("wp_ajax_nopriv_StepThree", "process_Three");
function process_Three() {
    return require(plugin_dir_path(__FILE__) . '/service/StepThreeRequest.php' );
}



add_action("wp_ajax_bank_service", "bank_service");
add_action("wp_ajax_nopriv_bank_service", "bank_service");
function bank_service() {
    error_log("ajaxUtility.................".plugin_dir_path(__FILE__) . '/service/BankService.php');
    return require(plugin_dir_path(__FILE__) . '/service/BankService.php');
}


add_action('wp_enqueue_scripts', 'load_tr_scripts');
function load_tr_scripts() {
     
   wp_enqueue_script('js-booking', TRAVELER__PLUGIN_URL . 'js/tr-booking.js');
//    wp_enqueue_script('js-oneway', TRAVELER__PLUGIN_URL . 'js/oneway.js');
    wp_enqueue_script('findcity-js',TRAVELER__PLUGIN_URL.'js/findCity.js');
    wp_enqueue_script('js-book', TRAVELER__PLUGIN_URL . 'js/book.js');
   wp_enqueue_script('js-moment',TRAVELER__PLUGIN_URL.'js/moment.min.js');


    wp_enqueue_script('payment-js',TRAVELER__PLUGIN_URL.'js/payment.js');
    wp_enqueue_script('travel-js',TRAVELER__PLUGIN_URL.'js/travel.js');

 wp_enqueue_script('js-search', TRAVELER__PLUGIN_URL . 'js/tr-search.js',array('jquery'),'1.0', true);
  wp_enqueue_script('js-roundtrip', TRAVELER__PLUGIN_URL . 'js/tr-roundtrip.js');


    wp_enqueue_style('css-search', TRAVELER__PLUGIN_URL . 'css/tr-search.css');
    wp_enqueue_style('book-display', TRAVELER__PLUGIN_URL . 'css/bookdisplay.css');
    wp_enqueue_style('oneway-css', TRAVELER__PLUGIN_URL . 'css/oneway.css');
    wp_enqueue_style('travel-css', TRAVELER__PLUGIN_URL . 'css/travel.css');
    wp_enqueue_style('round-search', TRAVELER__PLUGIN_URL . 'css/round-search.css');
    wp_enqueue_style('payment-css', TRAVELER__PLUGIN_URL . 'css/payment.css');

   wp_register_script( 'custom-script', plugins_url('/js/tr-search.js', __FILE__ ), array( 'jquery' ) );
    // or
    // Register the script like this for a theme:
    wp_register_script( 'custom-script', get_template_directory_uri() . '/js/tr-search.js', array( 'jquery' ));

    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'custom-script' );
}


add_action("wp_ajax_StepFour", "process_Four");
add_action("wp_ajax_nopriv_StepFour", "process_Four");
function process_Four() {
    return require(plugin_dir_path(__FILE__) . '/service/StepFourRequest.php' );
}

