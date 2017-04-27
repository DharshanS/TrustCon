<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
function getCacheKey($searchdata){
	$cachekey='';	
	if($searchdata['mode']=='oneway'){
$cachekey="o".$searchdata['from_airport'].$searchdata['to_airport'].str_replace("-","",$searchdata['start_date']).$searchdata['cabinclass'].$searchdata['adult'].$searchdata['child'].$searchdata['infant'];
	}else if($searchdata['mode']=='roundtrip'){
$cachekey="r".$searchdata['from_airport'].$searchdata['to_airport'].str_replace("-","",$searchdata['start_date']).str_replace("-","",$searchdata['end_date']).$searchdata['cabinclass'].$searchdata['adult'].$searchdata['child'].$searchdata['infant'];
	}else if($searchdata['mode']=='multicity'){
		$from='';
		if(isset($searchdata['from_airport']) && is_array($searchdata['from_airport']))
		   $from=implode(":",$searchdata['from_airport']);
		$to='';
		if(isset($searchdata['to_airport']) && is_array($searchdata['to_airport']))
		   $to=implode(":",$searchdata['to_airport']); 
		$dates='';
		if(isset($searchdata['start_date']) && is_array($searchdata['start_date']))
		   $dates=implode(":",$searchdata['start_date']);   
		$dates=str_replace("-","",$dates);  
		$cachekey="m".$from.$to.$dates.$searchdata['cabinclass'].$searchdata['adult'].$searchdata['child'].$searchdata['infant'];
	}
	$cachekey=strtolower($cachekey);	
	return $cachekey;	
}

function getExpiretime($dt=''){
	$ex=0;
	if($dt=='')$dt=date("Y-m-d");
	$secdiff=strtotime($dt) - time();
	$hourdiff=floor($secdiff/(60*60));
	if($hourdiff>0 && $hourdiff<(4 *24)){ // 0 to 3 days
		$ex=(60 * 5); // 5 min	
	}else if($hourdiff>=(4 *24) && $hourdiff<(8 *24)){ // 4 to 7 days
		$ex=(60 * 15); // 15 min	
	}else if($hourdiff>=(8 *24) && $hourdiff<(16 *24)){ // 8 to 15 days
	   $ex=(60 * 30); // 30 min	
	}else if($hourdiff>=(16 *24)){ //>=16 days
	   $ex=(60 * 60); // 60 min	
	}	
	return $ex;
}

