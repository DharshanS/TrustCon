<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DbUtility
 *
 * @author mirdu
 */

include PLUG_DIR.'core/DBconnet.php';

class DbUtility {

    //put your code here
 var $alink;

 function __construct() {
        global $alink;
        $db=new DBconnet();
        $alink = $db->getDbConnetion();
        error_log(" alink !!!!!---> ".print_r($alink,true));
    }

    static function load_place_details() {
        global $alink;
         error_log(print_r($alink,true));
        $SQL = "SELECT `iata_code`,`city`,`airport_name`,`country` FROM `airports`";
        $rs = mysqli_query($alink, $SQL);
         while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            $airportscity[$row['iata_code']] = $row['city'];
            $airportsname[$row['iata_code']] = $row['airport_name'];
          //  $airlines[$row['iata']] = $row['iata_code'];
            $countries[$row['iata_code']] = $row['country'];
        }
        if (empty($_SESSION['airportscity']))
            $_SESSION['airportscity'] = $airportscity;
        if (empty($_SESSION['airportsname']))
            $_SESSION['airportsname'] = $airportsname;

        if (empty($_SESSION['countries_new']))
            $_SESSION['countries_new'] = $countries;
    }

     function airlineload() {
         global $alink;
        $SQL = "SELECT `iata`,`airline` FROM `airlines` WHERE `iata`!='' ORDER BY `airline`";
        $rs = mysqli_query($alink, $SQL);
        while ($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
            $airlines[$row['iata']] = $row['airline'];
        }
        $_SESSION['airlines'] = $airlines;
    }

    function get_discount($airline_code)
    {
        global $alink;
        $SQL = "SELECT `discount` FROM `airlines` WHERE `iata`='".$airline_code."'";
        $rs = mysqli_query($alink, $SQL)->fetch_object()->discount;

       return $rs;
    }

    function get_bank_charge($bank)
    {
        global $alink;
        $SQL = "SELECT `charge` FROM `bank_charge` WHERE `bank_Name`='".$bank."'";
        $rs = mysqli_query($alink, $SQL)->fetch_object()->charge;
        return $rs;

    }

    function get_service_charge()
    {
        global $alink;
        $SQL = "SELECT `amount` FROM `service_charge` WHERE `id`='1'";
        $rs = mysqli_query($alink, $SQL)->fetch_object()->amount;

        return $rs;
    }

}
