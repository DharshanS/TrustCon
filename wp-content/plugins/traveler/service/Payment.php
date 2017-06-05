<?php
/**
 * Created by PhpStorm.
 * User: mirdu
 * Date: 6/4/2017
 * Time: 7:15 PM
 */
include_once (PLUG_DIR . '/service/dumpReservation.php');

error_log("store pay details ...................");
$reReq=$_POST['reservationRequest'];
$_SESSION['pay_details']=$reReq;
error_log('reservationRequest ---- >'.print_r($reReq,true));
$paymode=$_POST['paymode'];
$_SESSION['booking_reason']=$paymode;
before_reservation();
reservation_price_details($reReq);