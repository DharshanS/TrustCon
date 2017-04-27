<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DBconnet
 *
 * @author mirdu
 */
class DBconnet {
    //put your code here
    
   function getDbConnetion()
    {
        $alink = mysqli_connect('localhost', 'root','', 'v1');
        return $alink;
    }
}
