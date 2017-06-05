<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$soap_pnr ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:univ="http://www.travelport.com/schema/universal_v35_0" xmlns:com="http://www.travelport.com/schema/common_v35_0">
   <soapenv:Header/>
         <soapenv:Body>
      <univ:UniversalRecordRetrieveReq TargetBranch="'.$TARGETBRANCH.'" >
<com:BillingPointOfSaleInfo OriginApplication="UAPI" />
<univ:ProviderReservationInfo ProviderCode="1G" ProviderLocatorCode="'.$ProviderReservationInfoLocatorCode.'" />
</univ:UniversalRecordRetrieveReq>
   </soapenv:Body>
</soapenv:Envelope>';
$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/UniversalRecordService'; // PNR Record Retrieve request

$curlpnr = curl_init ($endpoint); 



$responseArrayPNR = json_decode($json,true);