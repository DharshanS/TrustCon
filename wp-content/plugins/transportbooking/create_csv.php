<?php
	$alink = mysqli_connect('localhost', 'i1611638_wp1', 'H(IXs5rqUc70.#4', 'i1611638_wp1');
    $booking = 'booking_transport';
	$room = 'room';
	$tour = 'tour';
	$vehicle = 'vehicle';

	$SQL = "SELECT * FROM $booking  ORDER BY name ";
	$rs = mysqli_query($alink, $SQL);
	while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){

		$is_cancel = ($row['is_cancel'] == '0') ? 'No':'Yes';
		$status = ($row['status'] == '1') ? 'Unpublished':'Published';

		$sql = "SELECT * FROM $vehicle  WHERE id = ".$row['vehicle_id']." ORDER BY vehicle ASC ";
		$res = mysqli_query($alink, $sql);
		while($r = mysqli_fetch_array($res, MYSQLI_ASSOC)){
			$vehiclename = $r['vehicle']."( ".$r['rate_per_kilometer']." )";
		}

		$myArr[] =	Array("Customer Name "=>$row['name'],"Booking ID"=>$row['booking_id'],"Booking Dt"=>$row['booking_dt'],"Email"=>$row['email'],"Price"=>$row['price'],"Payment TRN ID"=>$row['payment_trn_id'],"Payment TRN Date"=>$row['payment_dt'],"Person(s)"=>$row['persons'],"Sex"=>$row['sex'],"DOB"=>$row['dob'],"Mobile"=>$row['mobile'],"Cancel(?)"=>$is_cancel,"Cancel Dt"=>$row['cancel_dt'],"Vehicle"=>$vehiclename,"Dest From"=>$row['start_desti'],"Dest To"=>$row['end_desti'],"From Dt"=>$row['from_dt'],"To Dt"=>$row['to_dt'],"Status"=>$status);

	}


 $data = $myArr;

 function cleanData(&$str) 
 { 
	 $str = preg_replace("/\t/", "\\t", $str); 
	 $str = preg_replace("/\r?\n/", "\\n", $str); 
 }  

	 # filename for download 
	 $filename = "transportbookingReport_".date('Y-m-d').".xls"; 
	 header("Content-Disposition: attachment; filename=\"$filename\""); 
	 header("Content-Type: application/vnd.ms-excel");  
	 $flag = false; 

	 foreach($data as $row) 
		 { 
			 if(!$flag) 
			 { 
			 # display field/column names as first row 
			 echo "Transport Booking Report - ".date('Y-m-d')." \n\n ";
			 echo implode("\t", array_keys($row)) . "\n"; 
			 $flag = true; 
			 } 
		 array_walk($row, 'cleanData'); 
		 echo implode("\t", array_values($row)) . "\n"; 
		 } 
		 exit;
?>
