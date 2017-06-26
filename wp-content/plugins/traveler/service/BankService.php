<?php
/**
 * Created by PhpStorm.
 * User: tharangi
 * Date: 6/24/2017
 * Time: 8:28 PM
 */

//echo 'Hit to Bank service...';
//error_log('Hit bank service');
include_once PLUG_DIR.'utility/DbUtility.php';
$bank=$_POST['bank'];
$db_connect=new DbUtility();

$bank_charge=$db_connect->get_bank_charge($bank);
$final_settle=$_SESSION['totalAmountPay']+$bank_charge;
$_SESSION['final_settle']=$final_settle;
echo($bank_charge);