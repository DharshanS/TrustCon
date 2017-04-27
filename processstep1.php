<?php
session_start();
if(!isset($_SESSION['booking_entries'])){
$_SESSION['booking_entries']=array();	
}
$booking_reason=$_POST['booking_reason'];
$_SESSION['booking_entries']['booking_reason']=$booking_reason;
echo $_SESSION['booking_entries']['booking_reason'];
?>
