<?php
/**
 * Created by PhpStorm.
 * User: tharangi
 * Date: 6/22/2017
 * Time: 7:11 PM
 */



// Same handler function...
add_action( 'wp_ajax_my_action', 'my_action' );
function my_action() {
    global $wpdb;
    $whatever = intval( $_POST['whatever'] );
    $whatever += 10;
    echo $whatever;
    wp_die();
}