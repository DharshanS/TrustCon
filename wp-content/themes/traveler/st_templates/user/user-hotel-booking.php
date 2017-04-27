<?php
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * User Print E-Ticket
 *
 * Created by H+Plus Designs - Sri Lanka 
 *
 */
?>
<div class="st-create">
    <h2>Hotel Bookings</h2>
</div>
<?php
$hoteldata=loadbooking();
?>
    <table class="table table-bordered table-striped table-booking-history">
        <thead>
        <tr>
		    <th>Sl.</th>
            <th>TRIPID<br>Booking Dt</th>
            <th>Name,Email,Mobile No.<br>Gender<br>DOB</th>
			<th>Hotel</th>
			<th>Checkin<br>Checkout<br>No. Of Rooms</th>
			<th>Tour Details</th>
			<th>Adult<br>Child<br>Infant</th>
            <th>Price</th>
            <th>Payment</th>
        </tr>
        </thead>
        <tbody id="data_history_book">
        <?php $sl=1;foreach($hoteldata as $d){
           $h=$d['booking'];
		   $hd=$d['hoteldtl'];
		?>
		 <tr>
		    <td><?php echo $sl++;?>.</td>
            <td><?php echo $h->booking_id;?><br><?php echo $h->booking_dt;?></td>
            <td><?php echo $h->name;?><br><?php echo $h->email;?><br><?php echo $h->mobile;?><br><?php echo $h->sex;?>  <br><?php echo $h->dob;?></td>
			<td><?php echo $hd['hotel'];?><?php //echo $hd['description'];?><br><?php echo $hd['address'];?><br><?php echo $hd['category'];?></td>
			<td><?php echo $h->checkin;?><br><?php echo $h->checkout;?><br><?php echo $h->noofrooms;?></td>
			<td><?php echo $h->tour_details;?></td>
			<td>Adult-<?php echo $h->adult;?><br>Child-<?php echo $h->child;?><br>Infant-<?php echo $h->infant;?></td>
			<td><?php echo $h->price;?></td>
			<td><?php echo $h->payment_trn_id;?><br><?php echo $h->payment_dt!='0000-00-00'?$h->payment_dt:'-';?></td>
        </tr>	   
		<?php
        }
        ?>
        </tbody>
    </table>

<?php
function loadbooking(){
global $wpdb; 
$userid=0;
if (is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	//$useremail=$current_user->user_email;
	$userid=$current_user->ID;
}
$SQL="SELECT *  FROM  `booking`  WHERE `userid`='".$userid."' ORDER BY `booking_dt` DESC, `id` DESC LIMIT 100"; 
$results = $wpdb->get_results($SQL);
$hoteldata=array();
foreach($results as $result){
	
	// get hotel details
$sql  =  'SELECT `hotel_id`,`description` FROM room WHERE  id = '.$result->room_id.' '; 
$results2 = $wpdb->get_results($sql);
$result2=current($results2);
$hotel_id=$result2->hotel_id;
$sql3  =  'SELECT * FROM `hotel` WHERE `id`="'.$hotel_id.'"'; 
$results3 = $wpdb->get_results($sql3);
$result3=current($results3);
$hotel=$result3->hotel;
$description=$result3->description;
$address=$result3->address;
$category=$result3->category;

$data=array(
 'booking'=>$result,
 'hoteldtl'=>array(
        'hotel'=>$hotel,
		'description'=>$description,
		'address'=>$address,
		'category'=>$category
	 )
);
	
	$hoteldata[]=$data;
}
return $hoteldata;
}
?>






