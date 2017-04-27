<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OnewayFlight
 *
 * @author Dharshan
 */
class Flight {
    var $_from;
    var $_to;
    var $orgin;
    var $destination;
    var $depatureTime;
    var $depatureDate;
    var $arrivalTime;
    var $arrivalDate;
    var $stops;
    var $airline;
    var $journyTime;
    var $fareRules;
    var $totalJuryTime;
    var $totalPrice;
    var $flightDetails=array();
    var $basePrice;
    var $taxes; 

    var $searchData;

    //put your code here
}

//class FlightDetails
//{
//    var $carrier;
//    var $flightNumber;
// var $origin;
//var $destination;
//var $dpartureTime;
//var $arrivalTime;
//var $flightTime;
// var $distance;
// var $eTicketability;
// var $equipment;
// var $changeOfPlane;
// var $participantLevel;
// var $linkAvailability;
//var $polledAvailabilityOption;
// var $optionalServicesIndicator;
//var $availabilitySource;
//var $availabilityDisplayType;
//    
//}