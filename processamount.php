<?php
session_start();
$total=0;
$atprice=$_POST['atprice'];
if($atprice){
 $_SESSION['holidaybooking']['totalamount']=$_SESSION['holidaybooking']['hotelamount'] + $_SESSION['holidaybooking']['touramount'] + $_SESSION['holidaybooking']['airtransportprice'];
 $_SESSION['holidaybooking']['atprice']=1;
}else{
	 $_SESSION['holidaybooking']['totalamount']=$_SESSION['holidaybooking']['hotelamount'] + $_SESSION['holidaybooking']['touramount'];
	  $_SESSION['holidaybooking']['atprice']=0;
}
$total= $_SESSION['holidaybooking']['totalamount'];
echo $total;
?>
