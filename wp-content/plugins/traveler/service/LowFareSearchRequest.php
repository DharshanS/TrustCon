<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function LowFareSearchRequest($searchdata)
{
if($searchdata['mode']=='oneway'){

$message = '';
$message = '
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
	   <soapenv:Header/>
	   <soapenv:Body>
		<LowFareSearchReq xmlns="http://www.travelport.com/schema/air_v32_0" AuthorizedBy="User" TraceId="trace" TargetBranch="'.TARGETBRANCH.'" >
			  <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v32_0" OriginApplication="UAPI" />
			  <SearchAirLeg>
				<SearchOrigin>
				  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['from_airport'].'"  />
				</SearchOrigin>
				<SearchDestination>
				  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['to_airport'].'"  />
				</SearchDestination>
				<SearchDepTime PreferredTime="'.$searchdata['start_date'].'" />
				<AirLegModifiers>
				  <PreferredCabins>
					<CabinClass Type="'.$searchdata['cabinclass'].'"  xmlns="http://www.travelport.com/schema/common_v32_0"/>
				  </PreferredCabins>
				</AirLegModifiers>
			  </SearchAirLeg>
			  <AirSearchModifiers >
				<PreferredProviders>
				  <Provider xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.PROVIDER.'" />
				</PreferredProviders>
			  </AirSearchModifiers>';
				foreach($searchdata['passengers'] as $k=>$psgcode){
				 
				if($psgcode == 'ADT')	 
				{
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'"/>';
				}else if($psgcode == 'CNN'){
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" Age="8" />';
				}else if($psgcode == 'INF'){
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" PricePTCOnly="true" Age="1" />';
				} // endif
				
				} // endfor
	$message .='<AirPricingModifiers FaresIndicator="PublicAndPrivateFares" ETicketability="Yes" />
	</LowFareSearchReq>
	   </soapenv:Body>
	</soapenv:Envelope>
	';

}else if($searchdata['mode']=='roundtrip'){

$message = '';
$message = '
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Header/>
	<soapenv:Body>
		<LowFareSearchReq xmlns="http://www.travelport.com/schema/air_v32_0" AuthorizedBy="User" TraceId="trace" TargetBranch="'.$TARGETBRANCH.'" >
		  <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v32_0" OriginApplication="UAPI" />
		  <SearchAirLeg>
			<SearchOrigin>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['from_airport'].'"  />
			</SearchOrigin>
			<SearchDestination>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['to_airport'].'"  />
			</SearchDestination>
			<SearchDepTime PreferredTime="'.$searchdata['start_date'].'" />
			<AirLegModifiers>
			  <PreferredCabins>
				<CabinClass Type="'.$searchdata['cabinclass'].'"  xmlns="http://www.travelport.com/schema/common_v32_0"/>
			  </PreferredCabins>
			</AirLegModifiers>
		  </SearchAirLeg>
		  <SearchAirLeg>
			<SearchOrigin>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['to_airport'].'"  />
			</SearchOrigin>
			<SearchDestination>
			  <CityOrAirport xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$searchdata['from_airport'].'"  />
			</SearchDestination>
			<SearchDepTime PreferredTime="'.$searchdata['end_date'].'" />
			<AirLegModifiers>
			<PreferredCabins>
				<CabinClass Type="'.$searchdata['cabinclass'].'"  xmlns="http://www.travelport.com/schema/common_v32_0"/>
			  </PreferredCabins>
			</AirLegModifiers>
		  </SearchAirLeg>
		  <AirSearchModifiers>
			<PreferredProviders>
			  <Provider xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$Provider.'" />
			</PreferredProviders>
		  </AirSearchModifiers>';
				foreach($searchdata['passengers'] as $k=>$psgcode){
				 
				if($psgcode == 'ADT')	 
				{
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'"/>';
				}else if($psgcode == 'CNN'){
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" Age="8" />';
				}else if($psgcode == 'INF'){
				$message .='<SearchPassenger BookingTravelerRef= "'.md5($k+1).'" xmlns="http://www.travelport.com/schema/common_v32_0" Code="'.$psgcode.'" PricePTCOnly="true" Age="1" />';
				} // endif
				
				} // endfor
$message .= '<AirPricingModifiers FaresIndicator="PublicAndPrivateFares" ETicketability="Yes" />
             </LowFareSearchReq>
	 </soapenv:Body>
	</soapenv:Envelope>';	

}

return $message;
        
}
