<?php
session_start();
if(!isset($_SESSION['booking_entries'])){
$_SESSION['booking_entries']=array();	
}
$eml=$_POST['eml'];
$send_offers=$_POST['send_offers'];
$_SESSION['booking_entries']['eml']=$eml;
$_SESSION['booking_entries']['send_offers']=$send_offers;
echo $_SESSION['booking_entries']['eml'];
?>
