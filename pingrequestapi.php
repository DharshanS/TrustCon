     <?php 
     /* 
     *    Language:  English 
     *    Author:    J. Lipman 
     *    Website:   www.joellipman.com 
     *    Copyright: Copyright (C) 2015 Joellipman.Com 
     *    License:   GNU/GPL http://www.gnu.org/copyleft/gpl.html 
     * 
     *    uAPI sample communication in php language 
     * 
     *    This example requires the cURL library to be installed and working. 
     * 
     *    This code is for illustration purposes only. 
     *    IMPORTANT: The SOAP envelope variables are case sensitive, be consistent! 
     */ 
      
     // setup up access credentials 
	 
	 #echo 'Rajib'; die;
     $api_username = 'uAPI6986631472-c8b85ad8';                              // set to your given username for the API here 
     $api_password = 'P+t2rJ7&M6';                              // set to your given password for the API here 
     $credentials = 'Universal API/'.$api_username.':'.$api_password;        // prefix with "Universal API/" and separate with a colon 
     $credentials_64 = base64_encode($credentials);                          // encode for basic authentication 
      
     // pick a regional API - here we are using Europe/Middle East/Africa 
     $api_url_emea = 'https://emea.universal-api.pp.travelport.com/B2BGateway/connect/uAPI/'; 
     $api_url_emea_sys = $api_url_emea.'SystemService'; 
     $api_url_emea_urs = $api_url_emea.'UniversalRecordService'; 
      
     // set to the TargetBranch provided by Travelport. 
     $target_branch = 'P7040105';                      
      
     // here is the basic ping request message XML 
     // Note the namespace schemas used (try to use most recent) 
     $this_message_xml = ' 
     <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"> 
         <soapenv:Header/> 
         <soapenv:Body> 
             <sys:PingReq TraceId="test"  
                 xmlns:sys="http://www.travelport.com/schema/system_v9_0"  
                 xmlns:com="http://www.travelport.com/schema/common_v28_0"> 
                     <com:BillingPointOfSaleInfo OriginApplication="UAPI"/> 
                     <sys:Payload>This is a Ping Request Test for Travelport API</sys:Payload> 
             </sys:PingReq> 
         </soapenv:Body> 
     </soapenv:Envelope>'; 
      
     // Initialize the CURL object with the uAPI endpoint URL (in this case the SystemService) 
     $soap_do = curl_init ($api_url_emea_sys); 
      
     // This is the header of the request 
     $header = array( 
          'Content-Type: text/xml;charset=UTF-8', 
          'Authorization: Basic '.$credentials_64, 
          'Accept: gzip,deflate', 
          'User-Agent: Travelport uAPI Test', 
          'Cache-Control: no-cache', 
          'Pragma: no-cache', 
          'Connection: Keep-Alive', 
          'Host: emea.universal-api.pp.travelport.com', 
          'Content-length: ' . strlen($this_message_xml), 
     ); 
      
     // set the cURL options 
     curl_setopt($soap_do, CURLOPT_VERBOSE, 1);                              // For debugging purposes (read CURL manual for nore info) 
     curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30);                      // Timeout options 
     curl_setopt($soap_do, CURLOPT_TIMEOUT, 30);                             // Timeout options 
     curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, 0);                       // Verify nothing about peer certificates 
     curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, 0);                       // Verify nothing about host certificates 
     curl_setopt($soap_do, CURLOPT_POST, 1 );                                // Sending post variables 
     curl_setopt($soap_do, CURLOPT_POSTFIELDS, $this_message_xml);           // Post variable being sent (The XML request) 
     curl_setopt($soap_do, CURLOPT_HEADER, 0 );                              // Omit headers (enable these during testing - malformed XML but more info) 
     curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, 1 );                      // curl_exec function will show the response directly on the page (if set to 0 curl_exec function will return the result) 
     curl_setopt($soap_do, CURLOPT_HTTPAUTH, CURLAUTH_ANY);                  // Authentication is BASIC but we'll put ANY just to make it work 
     //    curl_setopt($soap_do, CURLOPT_USERPWD, $credentials_64);          // The credentials username:password (not used in my example) 
     curl_setopt($soap_do, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);       // TLS version to use (1.0) 
     curl_setopt($soap_do, CURLOPT_PORT, 443);                               // SSL port to use (443) 
     curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);                     // Headers sent to the server 
      
     $ch_result = curl_exec($soap_do);                                       // Store the results in a cURL handle 
      
     // Check for errors and show them instead 
     if (curl_errno($soap_do) != '') { 
             $ch_result = curl_errno($soap_do) . ' - ' . curl_error($soap_do) . '<br/>'; 
     } 
     curl_close($soap_do); 
      
     // Print cURL result (XML) 
     echo $ch_result; 
      
     ?> 
