<?php
set_time_limit(0);

$FareInfoRef=$_REQUEST['ref'];
$FareRuleKey=$_REQUEST['k'];

$TARGETBRANCH = 'P7040105'; 
$CREDENTIALS = 'Universal API/uAPI6986631472-c8b85ad8:P+t2rJ7&M6';
$PCC='3OS2';
$Provider = '1G'; // Any provider you want to use like 1G/1P/1V/ACH

$message='
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Header/>
	<soapenv:Body>
		<AirFareRulesReq xmlns="http://www.travelport.com/schema/air_v32_0" TraceId="'.md5("FR".date("YmdHis")).'" TargetBranch="'.$TARGETBRANCH.'" FareRuleType="long">
		  <BillingPointOfSaleInfo xmlns="http://www.travelport.com/schema/common_v32_0" OriginApplication="uAPI" />
            <FareRuleKey FareInfoRef="'.$FareInfoRef.'" ProviderCode="1G">'.$FareRuleKey.'</FareRuleKey>
        </AirFareRulesReq>
    </soapenv:Body>
</soapenv:Envelope>
';

    $auth = base64_encode("$CREDENTIALS"); 
	$soap_do = curl_init ("https://americas.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/AirService");
	$header = array(
	"Content-Type: text/xml;charset=UTF-8", 
	"Accept: gzip,deflate", 
	"Cache-Control: no-cache", 
	"Pragma: no-cache", 
	"SOAPAction: \"\"",
	"Authorization: Basic $auth", 
	"Content-length: ".strlen($message),
	); 
	//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
	//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false); 
	curl_setopt($soap_do, CURLOPT_POST, true ); 
	curl_setopt($soap_do, CURLOPT_POSTFIELDS, $message); 
	curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
	curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); 
	$resp = curl_exec($soap_do);
	curl_close ($soap_do);
	
	//die($resp);
	
	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
	$xml = simplexml_load_string($xml);
	$json = json_encode($xml);
	$responseArray = json_decode($json,true);
	
	$results=$responseArray['SOAPBody']['airAirFareRulesRsp']['airFareRule']['airFareRuleLong'];
    if(empty($results))$results=array();
    
	echo '<div style="border-bottom:1px solid;">';
	foreach($results as $result){
		$r=explode("\n",$result);
		if(is_array($r) && count($r))
		echo '<div style="padding:10px;border-top:1px solid;border-right:1px solid;border-left:1px solid; font-size:14px;">';
		echo '<div style="font-weight:bold;">';
		echo $r[0];
		echo "</div>";
		if(count($r)>1){
		$nr = array_shift($r);
		$s=implode("\n",$r);
		  echo "<pre>";
		   print_r($s);
		   
		}
		 echo "</div>";
		}
        echo "</div>";
		
?>