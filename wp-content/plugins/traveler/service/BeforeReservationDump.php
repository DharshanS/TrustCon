<?php
/**
 * Created by PhpStorm.
 * User: mirdu
 * Date: 6/5/2017
 * Time: 8:40 AM
 */
$db = new DBconnet();
$mysqli = $db->getDbConnetion();

$price_dts = unserialize($_SESSION['fareInfo']);
error_log("FareInfo ---> ".print_r($price_dts,true));
$price_details = $_SESSION['price_details'];
$searchdata = $_SESSION['searchdata'];
error_log("Price Details ---> ".print_r($price_details,true));

$from = $searchdata['from_city'];
$to = $searchdata['to_city'];
$cost = $price_details['totalNetAmount'];
$departdate = $searchdata['start_date'];
$returndate = $searchdata['end_date'];
$traveltype = $searchdata['mode']; // oneway, return, multicity

$userid = get_current_user_id();
$post_author = $userid;
$post_date = date("Y-m-d H:i:s");
$post_date_gmt = $post_date;
$post_content = '';
$post_title = 'Air Booking';
$post_excerpt = '';
$post_status = 'publish';
$comment_status = '';
$ping_status = '';
$post_password = '';
$post_name = 'Air Booking';
$to_ping = '';
$pinged = '';
$post_modified = $post_date;
$post_modified_gmt = $post_date;
$post_content_filtered = 'None';
$post_parent = 0;
$guid = '';
$menu_order = 0;
$post_type = 'st_order';
$post_mime_type = '';
$comment_count = 0;
// $city = $mysqli->real_escape_string($city);
$SQL = "INSERT INTO `wp_posts` SET `post_author`='" . $post_author . "',`post_date`='" . $post_date . "',`post_date_gmt`='" . $post_date_gmt . "',`post_title`='" . $post_title . "'";
$SQL .= ",`post_status`='" . $post_status . "',`post_name`='" . $post_name . "',`post_modified`='" . $post_modified . "',`post_modified_gmt`='" . $post_modified_gmt . "'";
$SQL .= ",`post_type`='" . $post_type . "'";
$result = $mysqli->query($SQL);
$post_id = $mysqli->insert_id;
## XXXX Added - 16/10/2015
error_log(" INSERT RESERVATION ---> " . $post_id);
$_SESSION['post_id'] = $post_id;