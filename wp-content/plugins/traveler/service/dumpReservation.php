<?php
include_once PLUG_DIR.'/models/PricingFly.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function after_reservation($resp,$data,$mytripid,$resReq)
{
    $price_details = $_SESSION['price_details'];
    $post_id=  $_SESSION['post_id'];
    $searchdata = $_SESSION['searchdata'];
    $price_dts = unserialize($_SESSION['fareInfo']);

    $userid = get_current_user_id();
    $post_author = $userid;

    $departdate = $searchdata['start_date'];
    $returndate = $searchdata['end_date'];
    $traveltype = $searchdata['mode'];
    $from = $searchdata['from_city'];
    $to = $searchdata['to_city'];
    $PNR = $resp['uniRecLocCode'];
     $booking_reason = ['booking_reason'];
    $proResInfoLocCode = $resp['proResInfoLocCode'];
    $ticketNumber = $resp['proResInfoLocCode'];
    $totalNetAmount = $price_details['totalNetAmount'];
    $totalBaseAmount = $price_details['totalBaseAmount'];
    $totalDiscont = $price_details['totalDiscount'];
    $totalTaxAmount = $price_details['totalTaxAmount'];
    $serviceChage = $price_details['serviceCharge'];
    $costOfTicket = ($totalNetAmount + $totalTaxAmount + $serviceChage);
    $arrivalTime = $price_details['arrivalTime'];
    $reurnDate = $price_details['returnDate'];
    $depatureTime = $price_details['departureTime'];
    $depatureDate = date("D, d M Y", $depatureTime);
    $booking_reason = $_SESSION['booking_reason'];
    $booking_date = $resp['booking_date'];
    $tickettype = $resp['ticket_type'];
    $ticketdate = $resp['ticket_date'];
    $email=$resReq['email'];


    $SQL = "INSERT INTO `wp_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES";
    $SQL .= "($post_id, 'TRIPID', '$mytripid'),";
    $SQL .= "($post_id, 'PNR', '$PNR'),";
    $SQL .= "($post_id, 'provider_code','$proResInfoLocCode'),";
    $SQL .= "($post_id, 'ticket_number', '$ticketNumber'),";
    $SQL .= "($post_id, 'AIRDATA', '" . base64_encode(serialize($data)) . "'),";
    $SQL .= "($post_id, 'from', '$from'),";
    $SQL .= "($post_id, 'to', '$to'),";
    $SQL .= "($post_id, 'totalprice', '$totalNetAmount'),";
    $SQL .= "($post_id, 'baseprice', '$totalBaseAmount'),";
    $SQL .= "($post_id, 'taxes', '$totalTaxAmount'),";
    $SQL .= "($post_id, 'servcharge', '$serviceChage'),";
    $SQL .= "($post_id, 'disamt', '$totalDiscont'),";
    $SQL .= "($post_id, 'cost', '$costOfTicket'),";
    $SQL .= "($post_id, 'departdate', '$depatureDate'),";
    $SQL .= "($post_id, 'departtime', '$depatureTime'),";
    $SQL .= "($post_id, 'returndate', '$reurnDate'),";
    $SQL .= "($post_id, 'traveltype', '$traveltype'),";
    $SQL.="($post_id, 'send_offers', '1'),";
    $SQL.="($post_id, 'contact_email', '$email'),";
    $SQL .= "($post_id, 'booking_reason', '" . $booking_reason . "'),";
    if ($booking_date != '')
        $SQL .= "($post_id, 'bookingdate', '" . date("Y-m-d H:i:s", strtotime($booking_date)) . "'),";
    else
        $SQL .= "($post_id, 'bookingdate', '" . date("Y-m-d H:i:s") . "'),";

    $SQL .= "($post_id, 'tickettype', '$tickettype'),";
    if ($ticketdate != '')
        $SQL .= "($post_id, 'ticketdate', '" . date("Y-m-d H:i:s", strtotime($ticketdate)) . "'),";
    else
        $SQL .= "($post_id, 'ticketdate', ''),";

    $SQL .= "($post_id, 'booking_entries', '" . base64_encode(serialize($price_dts)) . "'),";
    $SQL .= "($post_id, 'searchdata', '" . $searchdata . "')";
    $db = new DBconnet();
    $mysqli = $db->getDbConnetion();
    $result = $mysqli->query($SQL);
    $mysqli->commit();

// PNR history
    $SQL = "INSERT INTO `pnr_history` SET `post_author`='" . $post_author . "',`post_id`='" . $post_id . "',`PNR`='" . $PNR . "',`ticket_number`='" . $ticketNumber . "',`departdate`='" . $departdate . "',`departtime`='" . $depatureTime . "',  `returndate`='" . $returndate . "'";
    $mysqli->query($SQL);
}

function before_reservation()
{
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
}

