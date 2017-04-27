<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FlightDeatilsStore
 *
 * @author mirdu
 */
final class FlightDeatilsStore {
   

//$alink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


      static $inst = null;
    public static function Instance()
    {
      
        if ($inst === null) {
            $inst = new FlightDeatilsStore();
        }
        return $inst;
    }

    /**
     * Private ctor so nobody else can instance it
     *
     */
    private function __construct()
    {

    }
}
