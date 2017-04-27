<?php
session_start();
if(!isset($_SESSION['booking_entries'])){
echo 'Something gone wrong';
die();
}
isset($_POST['letterhead_name'])?$_SESSION['booking_entries']['letterhead_name']=$_POST['letterhead_name']:'';
isset($_POST['letterhead_company'])?$_SESSION['booking_entries']['letterhead_company']=$_POST['letterhead_company']:'';
isset($_POST['letterhead_address'])?$_SESSION['booking_entries']['letterhead_address']=$_POST['letterhead_address']:'';
$html='done';
echo $html;
?>
