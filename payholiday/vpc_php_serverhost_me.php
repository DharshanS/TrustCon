<?php
$testarr = array(
"vpc_Amount" => $_POST['vpc_Amount'],//Final price should be multifly by 100
"vpc_AccessCode" => $_POST['vpc_AccessCode'],//Put your access code here
"vpc_Command"=> "pay",
"vpc_Locale"=> "en",
"vpc_MerchTxnRef"=> $_POST['vpc_MerchTxnRef'], //This should be something unique number, i have used the session id for this
"vpc_Merchant"=> $_POST['vpc_Merchant'],//Add your merchant number here
"vpc_OrderInfo"=> $_POST['vpc_OrderInfo'],//this also better to be a unique number
"vpc_ReturnURL"=> "http://www.clickmybooking.com/payholiday/vpc_php_serverhost_dr.php",//Add the return url here so you have to code here to capture whether the payment done successfully or not
"vpc_Version"=> "1");

ksort($testarr); // You have to ksort the arry to make it according to the order that it needs

$SECURE_SECRET = "45F98EC6376767E215ADCB5384B61344";//Add the secure secret you have get

$securehash = $SECURE_SECRET;
$url = "";
foreach ($testarr as $key => $value)
{
$securehash .= $value;
$url .= $key."=".urlencode($value)."&";
}

$securehash = md5($securehash);//Encoding
$url .= "vpc_SecureHash=".$securehash;

header("location:https://migs.mastercard.com.au/vpcpay?$url");
?>