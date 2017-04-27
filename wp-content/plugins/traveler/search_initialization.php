<?php

/**
  Plugin Name: Traveler Ajax
  Plugin URI: http://localhost:8080/travel
  /*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define('TRAVELER__PLUGIN_URL', plugin_dir_url(__FILE__));
define('PLUG_DIR', plugin_dir_path(__FILE__));
define('TARGETBRANCH', 'P2721018');
define('CREDENTIALS', 'Universal API/uAPI4655248065-02590718:nA?9{c3YC8');
define('PROVIDER', '1G');
define('PCC', '3OS2');
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
    return require(plugin_dir_path(__FILE__) . '/service/MakeReservation.php' );
}



add_action("wp_ajax_StepThree", "process_Three");
add_action("wp_ajax_nopriv_StepThree", "process_Three");
function process_Three() {
    return require(plugin_dir_path(__FILE__) . '/service/StepThreeRequest.php' );
}

add_action("wp_ajax_ajaxUtility", "ajaxUtility");
add_action("wp_ajax_nopriv_ajaxUtility", "ajaxUtility");
function ajaxUtility() {
    error_log("ajaxUtility.................".plugin_dir_path(__FILE__) . '/utility/AjaxUtility.php');
    return require(plugin_dir_path(__FILE__) . '/utility/AjaxUtility.php' );
}

add_action('wp_enqueue_scripts', 'load_tr_scripts');
function load_tr_scripts() {
    wp_enqueue_script('js-booking', TRAVELER__PLUGIN_URL . 'js/tr-booking.js');
    // wp_enqueue_script('js-roundtrip', TRAVELER__PLUGIN_URL . 'js/tr-roundtrip.js');
    wp_enqueue_style('css-search', TRAVELER__PLUGIN_URL . 'css/tr-search.css');
}


add_action("wp_ajax_StepFour", "process_Four");
add_action("wp_ajax_nopriv_StepFour", "process_Four");
function process_Four() {
    return require(plugin_dir_path(__FILE__) . '/service/StepFourRequest.php' );
}