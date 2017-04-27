<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once ('DbUtility.php');
set_time_limit(0);
class FlightUtility {

    function getCountryName($ctycode) {

        if (empty($_SESSION['countries_new'])) {
         
       $db=new DbUtility ();
       $db->load_place_details();
        }

        $countries = $_SESSION['countries_new'];
        error_log(" CountryName -- >" . print_r($countries[$ctycode], true));

        return $countries[$ctycode];
    }

    function getAirportName($ctycode) {
        if (empty($_SESSION['airportsname'])) {
            $dbUtlity = new DbUtility();
            $dbUtlity->load_place_details();
        }
        $airportsname = $_SESSION['airportsname'];
        error_log(" AirportName  -- >" . print_r($airportsname[$ctycode], true));

        return $airportsname[$ctycode];
    }

    function getAirLineName($aircode) {
        if (empty($_SESSION['airlines'])) {
            $dbUtlity = new DbUtility();
            $dbUtlity->airlineload();
        }
        $airlines = $_SESSION['airlines'];
        error_log(" AirLineName -- >" . print_r($airlines[$aircode], true));

        return $airlines[$aircode];
    }
    
    function getCityName($aircode) {
        if (empty($_SESSION['airportscity'])) {
            $dbUtlity = new DbUtility();
            $dbUtlity->load_place_details();
        }
        $airportscity = $_SESSION['airportscity'];
        error_log(" AirLineName -- >" . print_r($airportscity[$aircode], true));

        return $airportscity[$aircode];
    }

   static  function getTimeDiff($d1, $d2)
        {

        $date1 = new DateTime($d1);

        $date2 = new DateTime($d2);

        $diff = $date2->diff($date1);

        $h = $diff->h;

        $h = $h + ($diff->days * 24);

        $m = $diff->i;



        $ret = '';

        if ($h)
            $ret .= $h;

        if ($h == 1)
            $ret .= "hr ";

        else if ($h > 1)
            $ret .= "hrs ";

        if ($m)
            $ret .= $m;

        if ($m == 1)
            $ret .= "min";

        else if ($m > 1)
            $ret .= "mins";

        return $ret;
    }
    
    
    function sendPost($endpoint,$soap)
    {
        error_log("Request Json--->".print_r($soap,true));
        
        $gzdata = gzencode($soap);
        $auth = base64_encode(CREDENTIALS); 

      
        
	$curl = curl_init($endpoint); // defined at top
	
	
	$header = array(
	"Content-Type: text/xml;charset=UTF-8", 
	"Content-Encoding: gzip", 
	"Cache-Control: no-cache", 
	"Pragma: no-cache", 
	"SOAPAction: \"\"",
	"Authorization: Basic $auth", 
	"Content-length: ".strlen($gzdata),
	); 

	//curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 

	//curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 

	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
	curl_setopt($curl, CURLOPT_POST, true ); 
	curl_setopt($curl, CURLOPT_POSTFIELDS, $gzdata); 
	curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        
	//$resp = curl_exec($curl);
        
	curl_close ($curl);
	
	
	$resp=file_get_contents(PLUG_DIR .'/xml/PriceResponse.xml', FILE_USE_INCLUDE_PATH);

	$xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
	$xml = simplexml_load_string($xml);
	$json = json_encode($xml);
        error_log('Response Json--->'.print_r($json,true));
	$responseArray = json_decode($json,true);
        //error_log('encoded--->'.print_r($responseArray,true));
        return $responseArray;
        
    }

}

//    
//}
// if(empty($_SESSION['airlines']))$_SESSION['airlines']=$airlines;