<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('DbUtility.php');

//set_time_limit(0);

if (!class_exists('FlightUtility')) {
    class FlightUtility
    {

        function getCountryName($ctycode)
        {

            if (empty($_SESSION['countries_new'])) {

                $db = new DbUtility ();
                $db->load_place_details();
            }

            $countries = $_SESSION['countries_new'];


            return $countries[$ctycode];
        }

        function getAirportName($ctycode)
        {
            if (empty($_SESSION['airportsname'])) {
                $dbUtlity = new DbUtility();
                $dbUtlity->load_place_details();
            }
            $airportsname = $_SESSION['airportsname'];


            return $airportsname[$ctycode];
        }

        function getAirLineName($aircode)
        {
            if (empty($_SESSION['airlines'])) {
                $dbUtlity = new DbUtility();
                $dbUtlity->airlineload();
            }
            $airlines = $_SESSION['airlines'];


            return $airlines[$aircode];
        }

        function getCityName($aircode)
        {
            if (empty($_SESSION['airportscity'])) {
                $dbUtlity = new DbUtility();
                $dbUtlity->load_place_details();
            }
            $airportscity = $_SESSION['airportscity'];

            return $airportscity[$aircode];
        }

        static function getTimeDiff($d1, $d2)
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


        function sendPost($endpoint, $soap, $fileXml)
        {


           
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
                "Content-length: " . strlen($gzdata),
            );

            //curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30);

            //curl_setopt($soap_do, CURLOPT_TIMEOUT, 30);

            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $gzdata);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_ENCODING, 'gzip');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);



            if($fileXml!="") {
                $resp = file_get_contents(PLUG_DIR . '/xml/' . $fileXml, FILE_USE_INCLUDE_PATH);
            }
            else
            {  $resp = curl_exec($curl);
                curl_close($curl);
            }

           // error_log('Request Xml--->' . print_r($soap, true));
            $xml = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $resp);
          //  error_log('Response Xml--->' . print_r($xml, true));
            $xml = simplexml_load_string($xml);


            $json = json_encode($xml);


            $responseArray = json_decode($json, true);
    if($json=='false'|| isset($responseArray['SOAPBody']['SOAPFault'])){
    echo "Please search again changing your input or contact travel agent ................";
    return;}
            return $responseArray;

        }

        function tripIdGenerator($length = 6)
        {
            $ret = 'C';
            $pw = '';
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $len = strlen($chars);
            for ($i = 0; $i < 20; $i++)
                $pw .= substr($chars, rand(0, $len - 1), 1);
// the finished password
            $pw = str_shuffle($pw);
            $pw = md5($pw);
            $len = strlen($pw);
            for ($i = 0; $i < $length; $i++)
                $ret .= substr($pw, rand(0, $len - 1), 1);
            return strtoupper($ret);
        }

    }


}


//    
