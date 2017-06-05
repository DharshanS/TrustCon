<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$SupplierLocatorCode='';
$universalUniversalRecord=array();
if(isset($responseArrayPNR['SOAPBody']['universalUniversalRecordRetrieveRsp']['universalUniversalRecord'])){
  $universalUniversalRecord=$responseArrayPNR['SOAPBody']['universalUniversalRecordRetrieveRsp']['universalUniversalRecord'];
}
if(isset($universalUniversalRecord['airAirReservation']['common_v35_0SupplierLocator']['@attributes']['SupplierLocatorCode'])){
  $SupplierLocatorCode=$universalUniversalRecord['airAirReservation']['common_v35_0SupplierLocator']['@attributes']['SupplierLocatorCode'];
}
$LocatorCode='';
if(isset($universalUniversalRecord['airAirReservation']['@attributes']['LocatorCode'])){
  $LocatorCode=$universalUniversalRecord['airAirReservation']['@attributes']['LocatorCode'];
}
//$PNR=$SupplierLocatorCode;
$PNR=$ProviderReservationInfoLocatorCode; // this is actual 
$TicketNumber='';

// retrive Ticket
// if we get DocumentSelect IssueElectronicTicket="true.  
//That means AirTicket(s) exists in this PNR. 
//In order to retrieve these Air Tickets we have to call AirDocumentRetriveReq

$IssueElectronicTicket='';
if(isset($universalUniversalRecord['airAirReservation']['airTicketingModifiers']['airDocumentSelect']['@attributes']['IssueElectronicTicket'])){
  $IssueElectronicTicket=$universalUniversalRecord['airAirReservation']['airTicketingModifiers']['airDocumentSelect']['@attributes']['IssueElectronicTicket'];
}
$ticketnumber='';