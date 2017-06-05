<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(isset($responseArrayTicket['SOAPBody']['airAirRetrieveDocumentRsp']['airETR']['airTicket']['@attributes']['TicketNumber'])){
$TicketNumber=$responseArrayTicket['SOAPBody']['airAirRetrieveDocumentRsp']['airETR']['airTicket']['@attributes']['TicketNumber'];	
}