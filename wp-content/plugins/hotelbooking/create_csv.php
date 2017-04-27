<?php
	$alink = mysqli_connect('localhost', 'i1611638_wp1', 'H(IXs5rqUc70.#4', 'i1611638_wp1');
    $booking = 'booking';
	$room = 'room';
	$tour = 'tour';
	$hotel = 'hotel';

	$SQL = "SELECT * FROM $booking  ORDER BY name ";
	$rs = mysqli_query($alink, $SQL);
	while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC)){

		$is_cancel = ($row['is_cancel'] == '0') ? 'No':'Yes';
		$status = ($row['status'] == '1') ? 'Unpublished':'Published';
		
		$sql = "SELECT * FROM $tour  WHERE id IN (".$row['tour_ids'].") ORDER BY tour ASC ";
		$res = mysqli_query($alink, $sql);
		while($r = mysqli_fetch_array($res, MYSQLI_ASSOC)){
			$tourpkgs .= $r['tour']."( ".$r['price']." ) @";
		}
		
		$myArr[] =	Array("Customer Name "=>$row['name'],"Booking ID"=>$row['booking_id'],"Booking Dt"=>$row['booking_dt'],"Email"=>$row['email'],"Price"=>$row['price'],"Payment TRN ID"=>$row['payment_trn_id'],"Payment TRN Date"=>$row['payment_dt'],"Person(s)"=>$row['persons'],"Sex"=>$row['sex'],"DOB"=>$row['dob'],"Mobile"=>$row['mobile'],"Cancel(?)"=>$is_cancel,"Cancel Dt"=>$row['cancel_dt'],"Room ID"=>$row['room_id'],"Tour Pkg IDs"=>$tourpkgs,"Description"=>$row['description'],"Checkin Dt"=>$row['checkin'],"Checkout Dt"=>$row['checkout'],"Status"=>$status);

	}


 $data = $myArr;

 function cleanData(&$str) 
 { 
	 $str = preg_replace("/\t/", "\\t", $str); 
	 $str = preg_replace("/\r?\n/", "\\n", $str); 
 }  

	 # filename for download 
	 $filename = "hotelbookingReport_".date('Y-m-d').".xls"; 
	 header("Content-Disposition: attachment; filename=\"$filename\""); 
	 header("Content-Type: application/vnd.ms-excel");  
	 $flag = false; 

	 foreach($data as $row) 
		 { 
			 if(!$flag) 
			 { 
			 # display field/column names as first row 
			 echo "Hotel Booking Report - ".date('Y-m-d')." \n\n ";
			 echo implode("\t", array_keys($row)) . "\n"; 
			 $flag = true; 
			 } 
		 array_walk($row, 'cleanData'); 
		 echo implode("\t", array_values($row)) . "\n"; 
		 } 
		 exit;
?>
