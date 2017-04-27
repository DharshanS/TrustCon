<?php
session_start();
$pnr='';
if(isset($_SESSION['UniversalRecordLocatorCode'])){
$pnr=$_SESSION['UniversalRecordLocatorCode'];
}else{
$pnr=$_SESSION['AirReservationLocatorCode'];
}
echo $pnr;
?>
