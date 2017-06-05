<?php


error_log(" INIT");
set_time_limit(0);
date_default_timezone_set('Asia/Colombo');


require_once(plugin_dir_path( __FILE__ ) . 'service/LowFareSearchRequest.php');
require_once(plugin_dir_path( __FILE__ ) . 'service/RoundTripResponse.php');
include_once(plugin_dir_path(__FILE__) . 'utility/FlightUtility.php');

require_once(plugin_dir_path(__FILE__) . 'utility/CacheUtility.php');
require_once(plugin_dir_path( __FILE__ ) .'Constants.php');

require_once(get_home_path() . '/library/cache.php');
require_once(get_home_path() . '/travelportsettings.php');
//require_once(get_home_path().'/tconnect.php');

$fly = new FlightUtility();

$perpageList = 10;
$jsondata = array();
$carriers = array();
$prices = array();
$dt = array();
if (isset($_POST['dt'])) {
    $dt = unserialize(base64_decode($_POST['dt']));
}
if (is_array($dt)) {
    foreach ($dt as $k => $v) {
        $_POST[$k] = $v;
    }
}

$alink = mysqli_connect(DB_HOST, DB_USER, '', DB_NAME);

$countries = array();
if (isset($_SESSION['countries'])) {
    $countries = $_SESSION['countries'];
} else {
    $SQL = "SELECT * FROM `countries` WHERE `code`!='' ORDER BY `name`";
    $rs = mysqli_query($alink, $SQL) or die($SQL);
    while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
        $countries[] = $row;
    }
    $_SESSION['countries'] = $countries;
}
$airlines = array();
if (isset($_SESSION['airlines'])) {
    $airlines = $_SESSION['airlines'];
} else {
    $SQL = "SELECT `iata`,`airline` FROM `airlines` WHERE `iata`!='' ORDER BY `airline`";
    $rs = mysqli_query($alink, $SQL);
    while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
        $airlines[$row['iata']] = $row['airline'];
    }
    $_SESSION['airlines'] = $airlines;
}
$airportscity = array();
$airportsname = array();
if (isset($_SESSION['airportscity'])) {
    $airportscity = $_SESSION['airportscity'];
    $airportsname = $_SESSION['airportsname'];
} else {
    $SQL = "SELECT `iata_code`,`city`,`airport_name`,`country` FROM `airports`";
    $rs = mysqli_query($alink, $SQL);
    while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
        $airportscity[$row['iata_code']] = $row['city'];
        $airportsname[$row['iata_code']] = $row['airport_name'];
        $airlines[$row['iata']] = $row['iata_code'];
        $countries['countries'] = $row['country'];
    }
    $_SESSION['airportscity'] = $airportscity;
    $_SESSION['airportsname'] = $airportsname;
    $_SESSION['airlines'] = $airlines;
    $_SESSION['countries'] = $countries;
}


$cache = new Cache;

if (isset($_SESSION['baseresponse']) && is_array($_SESSION['baseresponse']) && 1 == 2) {
//if(isset($_SESSION['baseresponse']) && is_array($_SESSION['baseresponse'])){ // test mode
    $responseArray = $_SESSION['baseresponse'];
    $searchdata = $_SESSION['searchdata'];
} else

    if (isset($_POST['mode']) && ($_POST['mode'] == 'roundtrip' || $_POST['mode'] == 'oneway')) {
        $from_city = '';
        $from_city_full = '';
        $from_country = '';
        $to_city = '';
        $to_city_full = '';
        $to_country = '';
        $searchdata = array();
        $startdt = '';
        $enddt = '';
        $adult = isset($_POST['adult']) ? $_POST['adult'] : 1;
        $child = isset($_POST['child']) ? $_POST['child'] : 0;
        $infant = isset($_POST['infant']) ? $_POST['infant'] : 0;
        $cabinclass = isset($_POST['cabinclass']) ? $_POST['cabinclass'] : 'Economy';
        $date_flexi = isset($_POST['date_flexi']) ? $_POST['date_flexi'] : 0;
        $iata = isset($_POST['iata']) ? $_POST['iata'] : '';


        if (isset($_POST['from_city'])) {
            $from_city = $_POST['from_city'];
            $fromcityarr = explode(",", $from_city);
            $from_city = $fromcityarr[0];
            if (isset($fromcityarr[1])) {
                $from_city_full = $fromcityarr[1];
            } else {
                $from_city_full = '';
            }
            if (count($fromcityarr) > 1) $from_country = end($fromcityarr);
        }

        $searchdata['from_airport'] = $from_city;
        $searchdata['from_city'] = $from_city_full;

        if (isset($_POST['to_city'])) {
            $to_city = $_POST['to_city'];
            $tocityarr = explode(",", $to_city);
            $to_city = $tocityarr[0];
            if (isset($tocityarr[1])) {
                $to_city_full = $tocityarr[1];
            } else {
                $to_city_full = '';
            }
            if (count($tocityarr) > 1) $to_country = end($tocityarr);
        }

        $searchdata['to_airport'] = $to_city;
        $searchdata['to_city'] = $to_city_full;

        if (isset($_POST['depart_date'])) {
            $startdt = $_POST['depart_date'];

        }

        if (isset($_POST['return_date'])) {
            $enddt = $_POST['return_date'];

        }

        $searchdata['start_date'] = $startdt;
        $searchdata['end_date'] = $enddt;

        $passengers = array();
        for ($i = 0; $i < $adult; $i++) {
            $passengers[] = 'ADT';
        }

        for ($i = 0; $i < $child; $i++) {
            $passengers[] = 'CNN';
        }

        for ($i = 0; $i < $infant; $i++) {
            $passengers[] = 'INF';
        }

        $searchdata['passengers'] = $passengers;
        $searchdata['cabinclass'] = $cabinclass;
        $searchdata['adult'] = $adult;
        $searchdata['child'] = $child;
        $searchdata['infant'] = $infant;

        $searchdata['date_flexi'] = $date_flexi;
        $searchdata['mode'] = $_POST['mode'];

        $searchdata['iata'] = $iata;

        $_SESSION['searchdata'] = $searchdata;

// generate cache key
        $cachekey = getCacheKey($searchdata);
        //set cache expire time
        $cache->expire = getExpiretime($searchdata['start_date']);


    }


if (isset($_POST['mode']) && ($_POST['mode'] == 'roundtrip' || $_POST['mode'] == 'oneway')) {
    if ($_POST['mode'] == 'roundtrip') {
       // require_once(plugin_dir_path(__FILE__) . 'service/RoundTripResponse.php');
        error_log("......................Round trip search initiated .......................");
        roundTrip($fly->sendPost(ENDPOINT, LowFareSearchRequest($searchdata),""));
    } else if ($_POST['mode'] == 'oneway') {
       // require_once(plugin_dir_path(__FILE__) . 'service/LowFareSearchResponse.php');
        error_log("......................One way search initiated ..........................");
        initSearch($fly->sendPost(ENDPOINT, LowFareSearchRequest($searchdata),""));

    }
}