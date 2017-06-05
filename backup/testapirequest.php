<?php
$CREDENTIALS = 'Universal API/uAPI6986631472-c8b85ad8:P+t2rJ7&M6';
$message = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
   <soapenv:Header/>
   <soapenv:Body>
 <AirTicketingReq xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" TraceId="'.md5("ACRR".date("ymdHis")).'" TargetBranch="P7040105" xmlns="http://www.travelport.com/schema/air_v28_0">
  <BillingPointOfSaleInfo OriginApplication="UAPI" xmlns="http://www.travelport.com/schema/common_v28_0" />
  <AirReservationLocatorCode xmlns="http://www.travelport.com/schema/air_v28_0">ZMGXXJ</AirReservationLocatorCode>
</AirTicketingReq>
  </soapenv:Body>
</soapenv:Envelope>';

$auth = base64_encode($CREDENTIALS); //should base_64_encode() this!
$soap_do = curl_init("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
$header = array( 
"Content-Type: text/xml;charset=UTF-8",
"Accept: gzip,deflate",
"Cache-Control: no-cache",
"Pragma: no-cache",
"SOAPAction: \"\"", 
"Authorization: Basic $auth",
"Content-length: ".strlen($message),
); 
curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 60); 
curl_setopt($soap_do, CURLOPT_TIMEOUT, 60); 
curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
curl_setopt($soap_do, CURLOPT_POST, true );
curl_setopt($soap_do, CURLOPT_POSTFIELDS, $message);
curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($soap_do);

echo '<pre>';
echo $res; /*echo '<br>';
var_dump($soap_do);
echo '<br>'.$soap_do;*/

?>