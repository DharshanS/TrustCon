<?php
/**
 * Created by PhpStorm.
 * User: tharangi
 * Date: 6/22/2017
 * Time: 9:49 PM
 */

include "/../../../wp-load.php";
$bank =  $_POST['bank'];
$amount= intval( $_POST['amount'] );
$sqlquery = "UPDATE bank_charge SET charge=$amount where bank_Name='".$bank."'" ;
error_log("Admin Serviece".$sqlquery);
global $wpdb;
//$whatever = intval( $_POST['whatever'] );
$rows =$wpdb->query($sqlquery);

echo($rows);


die(); // this is required to terminate immediately a

