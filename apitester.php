<?php
$resp='<?xml version="1.0" encoding="UTF-8"?>
<SOAP:Envelope xmlns:SOAP="http://schemas.xmlsoap.org/soap/envelope/">
   <SOAP:Body>
      <universal:UniversalRecordRetrieveRsp xmlns:universal="http://www.travelport.com/schema/universal_v35_0" xmlns:air="http://www.travelport.com/schema/air_v35_0" xmlns:common_v35_0="http://www.travelport.com/schema/common_v35_0" TransactionId="621CB0DE0A0759D180FC812A20816D88" ResponseTime="1401">
         <universal:UniversalRecord LocatorCode="2S3JDF" Version="0" Status="Active">
            <common_v35_0:BookingTraveler Key="sVMhk/muTTCSCW2IwsHBgw==" TravelerType="ADT" Gender="M">
               <common_v35_0:BookingTravelerName Prefix="MR" First="ff" Last="hh" />
               <common_v35_0:Email Key="OHjTe/EVQbuP8ktigHf1jA==" Type="Home" EmailID="clickmybooking@sg2plcpnl0030.prod.sin2.secureserver.net">
                  <common_v35_0:ProviderReservationInfoRef Key="+t52GBsNSKSGPrlpb/LJdA==" />
               </common_v35_0:Email>
            </common_v35_0:BookingTraveler>
            <common_v35_0:ActionStatus Key="TgNGT53oQGS5K2fF4HDJ9Q==" Type="TAW" TicketDate="2016-04-29T20:01:00.000+05:30" ProviderReservationInfoRef="+t52GBsNSKSGPrlpb/LJdA==" ProviderCode="1G" />
            <universal:ProviderReservationInfo Key="+t52GBsNSKSGPrlpb/LJdA==" ProviderCode="1G" LocatorCode="VMN4GI" CreateDate="2016-04-29T13:02:06.590+00:00" ModifiedDate="2016-04-29T13:02:09.163+00:00" HostCreateDate="2016-04-29" OwningPCC="3OS2" />
            <air:AirReservation LocatorCode="K3UWNR" CreateDate="2016-04-29T13:02:04.854+00:00" ModifiedDate="2016-04-29T13:02:06.590+00:00">
               <common_v35_0:SupplierLocator SupplierCode="UL" SupplierLocatorCode="5KZ76R" ProviderReservationInfoRef="+t52GBsNSKSGPrlpb/LJdA==" CreateDateTime="2016-04-29T13:02:00.000+00:00" />
               <common_v35_0:BookingTravelerRef Key="sVMhk/muTTCSCW2IwsHBgw==" />
               <common_v35_0:ProviderReservationInfoRef Key="+t52GBsNSKSGPrlpb/LJdA==" />
               <air:AirSegment Key="yO9T132OTJGVvN3iHZlDsA==" Group="0" Carrier="UL" CabinClass="Economy" FlightNumber="402" ProviderCode="1G" Origin="CMB" Destination="BKK" DepartureTime="2016-04-30T01:15:00.000+05:30" ArrivalTime="2016-04-30T06:15:00.000+07:00" TravelTime="210" Distance="1485" ClassOfService="R" ETicketability="Yes" Equipment="320" Status="HK" ChangeOfPlane="false" GuaranteedPaymentCarrier="No" ProviderReservationInfoRef="+t52GBsNSKSGPrlpb/LJdA==" TravelOrder="1" OptionalServicesIndicator="false" ParticipantLevel="Secure Sell" LinkAvailability="true">
                  <air:FlightDetails Key="MZmQPSrXTk6ViN6kEQGSxw==" Origin="CMB" Destination="BKK" DepartureTime="2016-04-30T01:15:00.000+05:30" ArrivalTime="2016-04-30T06:15:00.000+07:00" FlightTime="210" TravelTime="210" Equipment="320" />
                  <common_v35_0:SellMessage>*TRANSIT EX BKK VISA RQRD REFER AIS P82*</common_v35_0:SellMessage>
                  <common_v35_0:SellMessage>ADD ADVANCE PASSENGER INFORMATION SSRS DOCA/DOCO/DOCS</common_v35_0:SellMessage>
                  <common_v35_0:SellMessage>PERSONAL DATA WHICH IS PROVIDED TO US IN CONNECTION</common_v35_0:SellMessage>
                  <common_v35_0:SellMessage>WITH YOUR TRAVEL MAY BE PASSED TO GOVERNMENT AUTHORITIES</common_v35_0:SellMessage>
                  <common_v35_0:SellMessage>FOR BORDER CONTROL AND AVIATION SECURITY PURPOSES</common_v35_0:SellMessage>
               </air:AirSegment>
               <air:AirSegment Key="Cph+x446SOyynJzlMePbPA==" Group="1" Carrier="UL" CabinClass="Economy" FlightNumber="883" ProviderCode="1G" Origin="BKK" Destination="CMB" DepartureTime="2016-05-04T21:50:00.000+07:00" ArrivalTime="2016-05-04T23:40:00.000+05:30" TravelTime="200" Distance="1485" ClassOfService="O" ETicketability="Yes" Equipment="332" Status="HK" ChangeOfPlane="false" GuaranteedPaymentCarrier="No" ProviderReservationInfoRef="+t52GBsNSKSGPrlpb/LJdA==" TravelOrder="2" OptionalServicesIndicator="false" ParticipantLevel="Secure Sell" LinkAvailability="true">
                  <air:FlightDetails Key="4JokubECTceUHT/0FGa8bA==" Origin="BKK" Destination="CMB" DepartureTime="2016-05-04T21:50:00.000+07:00" ArrivalTime="2016-05-04T23:40:00.000+05:30" FlightTime="200" TravelTime="200" Equipment="332" />
                  <common_v35_0:SellMessage>*TRANSIT EX BKK VISA RQRD REFER AIS P82*</common_v35_0:SellMessage>
                  <common_v35_0:SellMessage>ADD ADVANCE PASSENGER INFORMATION SSRS DOCA/DOCO/DOCS</common_v35_0:SellMessage>
                  <common_v35_0:SellMessage>PERSONAL DATA WHICH IS PROVIDED TO US IN CONNECTION</common_v35_0:SellMessage>
                  <common_v35_0:SellMessage>WITH YOUR TRAVEL MAY BE PASSED TO GOVERNMENT AUTHORITIES</common_v35_0:SellMessage>
                  <common_v35_0:SellMessage>FOR BORDER CONTROL AND AVIATION SECURITY PURPOSES</common_v35_0:SellMessage>
               </air:AirSegment>
               <air:AirPricingInfo Key="x7OCuhInRLeSU6eNnZPEjg==" TotalPrice="LKR49018" BasePrice="LKR30900" ApproximateTotalPrice="LKR49018" ApproximateBasePrice="LKR30900" Taxes="LKR18118" LatestTicketingTime="2016-04-30T23:59:00.000+05:30" TrueLastDateToTicket="2016-04-30T23:59:00.000+05:30" PricingMethod="Guaranteed" Refundable="true" Exchangeable="true" IncludesVAT="false" ETicketability="Yes" PlatingCarrier="UL" ProviderCode="1G" ProviderReservationInfoRef="+t52GBsNSKSGPrlpb/LJdA==" AirPricingInfoGroup="1" PricingType="StoredFare">
                  <air:FareInfo Key="IP0bpa05TKi2Y7+7/059oA==" FareBasis="RRTLK" PassengerTypeCode="ADT" Origin="CMB" Destination="BKK" EffectiveDate="2016-04-29T00:00:00.000+05:30" Amount="NUC144.50" NotValidBefore="2016-04-30" NotValidAfter="2016-04-30" PseudoCityCode="3OS2">
                     <common_v35_0:Endorsement Value="VALID ON UL ONLY" />
                     <common_v35_0:Endorsement Value="CHANGE FEE MAY APPLY" />
                     <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms" />
                     </air:BaggageAllowance>
                  </air:FareInfo>
                  <air:FareInfo Key="IvRyPG9tTmuQCZ5bSu1sNg==" FareBasis="OE14DLK" PassengerTypeCode="ADT" Origin="BKK" Destination="CMB" EffectiveDate="2016-04-29T00:00:00.000+05:30" Amount="NUC68.28" NotValidBefore="2016-05-04" NotValidAfter="2016-05-04" PseudoCityCode="3OS2">
                     <common_v35_0:Endorsement Value="VALID ON UL ONLY" />
                     <common_v35_0:Endorsement Value="CHANGE FEE MAY APPLY" />
                     <air:BaggageAllowance>
                        <air:MaxWeight Value="30" Unit="Kilograms" />
                     </air:BaggageAllowance>
                  </air:FareInfo>
                  <air:BookingInfo BookingCode="R" CabinClass="Economy" FareInfoRef="IP0bpa05TKi2Y7+7/059oA==" SegmentRef="yO9T132OTJGVvN3iHZlDsA==" />
                  <air:BookingInfo BookingCode="O" CabinClass="Economy" FareInfoRef="IvRyPG9tTmuQCZ5bSu1sNg==" SegmentRef="Cph+x446SOyynJzlMePbPA==" />
                  <air:TaxInfo Category="LK" Amount="LKR4200" Key="MnD0TqUaTbGq74qXu5K30g==" />
                  <air:TaxInfo Category="E7" Amount="LKR298" Key="Q5CEZrEzTj6Jf1NfRslh+g==" />
                  <air:TaxInfo Category="TS" Amount="LKR2980" Key="ecdidJx8QX6O/ZWQlOeaQw==" />
                  <air:TaxInfo Category="YQ" Amount="LKR10640" Key="Twu2gTfnT9CW/8W7Xwf6RQ==" />
                  <air:FareCalc>CMB UL BKK 144.50RRTLK UL CMB 68.28OE14DLK NUC212.78END ROE144.978</air:FareCalc>
                  <air:PassengerType Code="ADT" BookingTravelerRef="sVMhk/muTTCSCW2IwsHBgw==">
                     <air:FareGuaranteeInfo GuaranteeType="Guaranteed" />
                  </air:PassengerType>
                  <common_v35_0:BookingTravelerRef Key="sVMhk/muTTCSCW2IwsHBgw==" />
                  <air:ChangePenalty>
                     <air:Amount>LKR11000.0</air:Amount>
                  </air:ChangePenalty>
                  <air:TicketingModifiersRef Key="Pg4y+LzqR42/k+jyEn5mWQ==" />
               </air:AirPricingInfo>
               <air:TicketingModifiers PlatingCarrier="UL" Key="Pg4y+LzqR42/k+jyEn5mWQ==">
                  <air:DocumentSelect IssueElectronicTicket="true" />
               </air:TicketingModifiers>
            </air:AirReservation>
            <common_v35_0:AgencyInfo>
               <common_v35_0:AgentAction ActionType="Created" AgentCode="UAPI4655248065-02590718" BranchCode="P2721018" AgencyCode="S2721011" EventTime="2016-04-29T13:01:55.298+00:00" />
            </common_v35_0:AgencyInfo>
            <common_v35_0:AgencyContactInfo>
               <common_v35_0:PhoneNumber Key="bjYC05hyQGK5uf98oUtP3g==" Type="Agency" Location="DEL" CountryCode="0094" Number="774705553" AreaCode="11" Text="ITQ INDIA">
                  <common_v35_0:ProviderReservationInfoRef Key="+t52GBsNSKSGPrlpb/LJdA==" />
               </common_v35_0:PhoneNumber>
            </common_v35_0:AgencyContactInfo>
            <common_v35_0:FormOfPayment Key="RjEyMDE2MDQyOTEzMDE1NA==" Type="Cash" Reusable="false" ProfileKey="EceGZlmZSg2lHZiLAsJfXA==">
               <common_v35_0:ProviderReservationInfoRef Key="+t52GBsNSKSGPrlpb/LJdA==" />
            </common_v35_0:FormOfPayment>
         </universal:UniversalRecord>
      </universal:UniversalRecordRetrieveRsp>
   </SOAP:Body>
</SOAP:Envelope>';

$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
$xml = simplexml_load_string($xml);
$json = json_encode($xml);
$responseArray= json_decode($json,true);
$universalUniversalRecord=$responseArray['SOAPBody']['universalUniversalRecordRetrieveRsp']['universalUniversalRecord'];
echo "<pre>";
print_r($universalUniversalRecord['airAirReservation']['common_v35_0SupplierLocator']['@attributes']['SupplierLocatorCode']);
?>
