<?php
session_start();
if(!isset($_SESSION['hotel_entries'])){
$_SESSION['hotel_entries']=array();	
}
$eml=$_POST['eml'];
$send_offers=$_POST['send_offers'];
$_SESSION['hotel_entries']['eml']=$eml;
$_SESSION['hotel_entries']['send_offers']=$send_offers;
echo $_SESSION['hotel_entries']['eml'];
?>
