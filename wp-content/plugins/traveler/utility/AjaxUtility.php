<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'FlightUtility.php';
 error_log('AJAX UTILTY');
 $action=$_POST['method'];
 switch ($action)
 {
     case'details':echo formatFlightDetails();break;
 }
 
function formatFlightDetails()
{
    $data=$_POST['data'];
    foreach($data as $index)
    {
        
    }
   
}
function getCityName()
{
    
}
function getAirportName()
{
    
}

function getTimeDiff()
{
    
}