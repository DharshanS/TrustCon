<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PricingFly
 *
 * @author mirdu
 */
class PricingFly {
    //put your code here
    
    var $airDetails;
    var $priceDetails;
    var $fareNotes;
    var $baggageAlowence;
    
}
class PricingDetails
{
    var $baseFare;
  
    var $webFareDiscount;
    
    var $fareInfo;
    var $otherInfo;
}

class Pricing 
{
    var $pricingInfo;
    var $fareInfo;
    var $pasType;
    var $count;
    var $headPrice;
    var $headTax;
    var $total;

    
}
class OtherInfo
{
    var $airBookingInfo;
    var $airTaxInfo;
    var $airFareCalc;
    var $airPassengerType;
    var $airChangePenalty;
    var $airCancelPenalty;
    var $airBaggageAllowances;
    
}