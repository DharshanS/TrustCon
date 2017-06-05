<?php
$smsmessage='Test';
$dstphone='00919830748641';
$smsgateway='http://203.153.222.25:5000/sms/send_sms.php?username=meera&password=Meera2016&src=Meera&dst='.$dstphone.'&msg='.$smsmessage.'&dr=1';
//echo $smsgateway;
//$smsret=file_get_contents($smsgateway);
//echo $smsret;

echo getUrlContent($smsgateway);

/* $ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $smsgateway);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$contents = curl_exec($ch);
echo $contents;
echo 'Curl error: ' . curl_error($ch);
curl_close($ch); */

function getUrlContent($url){
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $url);
 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16');
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
 curl_setopt($ch, CURLOPT_TIMEOUT, 0);
 $data = curl_exec($ch);
 $cerror = curl_error($ch);
 curl_close($ch);
 return ($data!='') ? $data : $cerror;
}

//$ret=http_request('GET','203.153.222.25',5000,'/sms/send_sms.php',array('username' => 'meera', 'password' => 'Meera2016', 'dst' => $dstphone, 'msg' => $smsmessage,'dr'=>1 ),array(),array(),array(),1000,false,2);

//echo $ret;

function http_request( 
	$verb = 'GET',             /* HTTP Request Method (GET and POST supported) */ 
	$ip,                       /* Target IP/Hostname */ 
	$port = 80,                /* Target TCP port */ 
	$uri = '/',                /* Target URI */ 
	$getdata = array(),        /* HTTP GET Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
	$postdata = array(),       /* HTTP POST Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
	$cookie = array(),         /* HTTP Cookie Data ie. array('var1' => 'val1', 'var2' => 'val2') */ 
	$custom_headers = array(), /* Custom HTTP headers ie. array('Referer: http://localhost/ */ 
	$timeout = 1000,           /* Socket timeout in milliseconds */ 
	$req_hdr = false,          /* Include HTTP request headers */ 
	$res_hdr = 0	           /* Include HTTP response headers, 0=No header, 1=complete header, 2=only content */ 
	) 
{ 
	$ret = ''; 
	$verb = strtoupper($verb); 
	$cookie_str = ''; 
	$getdata_str = count($getdata) ? '?' : ''; 
	$postdata_str = ''; 

	foreach ($getdata as $k => $v) 
		$getdata_str .= urlencode($k) .'='. urlencode($v) .'&'; 

	foreach ($postdata as $k => $v) 
		$postdata_str .= urlencode($k) .'='. urlencode($v) .'&'; 

	foreach ($cookie as $k => $v) 
		$cookie_str .= urlencode($k) .'='. urlencode($v) .'; '; 

	$crlf = "\r\n"; 
	$req = $verb .' '. $uri . $getdata_str .' HTTP/1.1' . $crlf; 
	$req .= 'Host: '. $ip . $crlf; 
	$req .= 'User-Agent: Mozilla/5.0 Firefox/3.6.12' . $crlf; 
	$req .= 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8' . $crlf; 
	$req .= 'Accept-Language: en-us,en;q=0.5' . $crlf; 
	$req .= 'Accept-Encoding: deflate' . $crlf; 
	$req .= 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7' . $crlf; 
	
	foreach ($custom_headers as $k => $v) 
		$req .= $k .': '. $v . $crlf; 
	
	if (!empty($cookie_str)) 
		$req .= 'Cookie: '. substr($cookie_str, 0, -2) . $crlf; 
	
	if ($verb == 'POST' && !empty($postdata_str)) 
	{ 
		$postdata_str = substr($postdata_str, 0, -1); 
		$req .= 'Content-Type: application/x-www-form-urlencoded' . $crlf; 
		$req .= 'Content-Length: '. strlen($postdata_str) . $crlf . $crlf; 
		$req .= $postdata_str; 
	} 
	else $req .= $crlf; 
	
	if ($req_hdr) 
		$ret .= $req; 
	
	if (($fp = @fsockopen($ip, $port, $errno, $errstr)) == false) 
		return "Error $errno: $errstr\n"; 
	
	stream_set_timeout($fp, 0, $timeout * 1000); 
	
	fputs($fp, $req);
	$retval = '';
	while ($line = fgets($fp)) $retval .= $line;
	fclose($fp); 
	
	if ($res_hdr == 1) {
		$ret .= $retval;
	}
	if ($res_hdr == 2) {
		// find last element 
		$ret .= substr($retval, strpos($retval, "\r\n\r\n") + 4); 
	}
	
	return $ret; 
}

function FwdSMS2Gateway ($username, $password, $phoneNoRecip, $msgText , $sender, $SenderCDMA) {
	$res = "";
	$res = http_request("GET","gatewayurl", 80, "/sendsms.php",array('user' => $username, 'password' => $password, 'PhoneNumber' => $phoneNoRecip, 'Sender' => $sender, 'SenderCDMA' => $SenderCDMA, 'Text' => $msgText ),array(),array(),array(),1000,false,2);
	
	return $res;
}
?>