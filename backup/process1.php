<?php
session_start();
if(!isset($_SESSION['hotel_entries'])){
$_SESSION['hotel_entries']=array();	
}
$booking_reason=$_POST['booking_reason'];
$_SESSION['hotel_entries']['booking_reason']=$booking_reason;
echo $_SESSION['hotel_entries']['booking_reason'];
?>
