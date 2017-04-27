<?php
session_start();
$tripid='';
if(isset($_SESSION['Mytripid'])){
$tripid=$_SESSION['Mytripid'];
$tripid="".$tripid;
}
echo $tripid;
?>
