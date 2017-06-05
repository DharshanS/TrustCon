<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'FlightUtility.php';
 error_log('AJAX UTILTY');

echo airDetails();
function airDetails()
{
    $fly=new FlightUtility();
  $dts='{"ori":"'.$fly->getCityName($_GET['ori']).'","oriAir":"'.$fly->getAirportName($_GET['ori']).'","des":"'.$fly->getCityName($_GET['des']).'","desAir":"'.$fly->getCityName($_GET['des']).'","air":"'.$fly->getAirLineName($_GET['air']).'"
}';


  
    
    return $dts;
   
}

