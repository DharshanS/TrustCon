<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if($IssueElectronicTicket=='true'){
$soap_ticket ='<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:univ="http://www.travelport.com/schema/universal_v35_0" xmlns:com="http://www.travelport.com/schema/common_v35_0">
<soapenv:Header/>
<soapenv:Body>
 <AirRetrieveDocumentReq xmlns="http://www.travelport.com/schema/air_v35_0" xmlns:common="http://www.travelport.com/schema/common_v35_0" xmlns:rail="http://www.travelport.com/schema/rail_v35_0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"  TargetBranch="'.$TARGETBRANCH.'">
    <common:BillingPointOfSaleInfo OriginApplication="UAPI" />
    <AirReservationLocatorCode>'.$LocatorCode.'</AirReservationLocatorCode>
 </AirRetrieveDocumentReq> 
</soapenv:Body>
</soapenv:Envelope>';
$gzdata = gzencode($soap_ticket);
$auth = base64_encode("$CREDENTIALS"); 
//$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirRetrieveDocumentService'; 
// AirRetrieveDocument Retrieve request

$endpoint='https://apac.universal-api.travelport.com/B2BGateway/connect/uAPI/AirService'; 



$responseArrayTicket = json_decode($json,true);
}