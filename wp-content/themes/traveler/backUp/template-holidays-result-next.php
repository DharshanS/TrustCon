<?php
/*
Template Name: Holiday Result Next
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 */
 
if ( is_user_logged_in() ){
	if(isset($_SESSION['redirectoccurin'])&& $_SESSION['redirectoccurin']!=''){
		$ru=$_SESSION['redirectoccurin'];
		wp_redirect( home_url("/".$ru) ); exit;
	}
}else {
       
}

get_header();
//print_r($_POST);


while(have_posts()){
    the_post();
	the_content();
//$alink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$alink = mysqli_connect('localhost', 'i1611638_wp1', 'H(IXs5rqUc70.#4', 'i1611638_wp1');
//$alink = mysql_connect('localhost', 'i1611638_wp1', 'H(IXs5rqUc70.#4');
//$db = mysql_select_db('i1611638_wp1',$alink);
$hid = $_REQUEST['hid'];
$cid = $_REQUEST['cid'];

		//$SQL   =  " SELECT * FROM  hotel ";

     $SQL  =  "SELECT * FROM  hotel WHERE id =  $hid AND  status = '0'  "; 

$rs = mysqli_query($alink,$SQL) or die($SQL);
while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC))
{ 
?> 
<div class="container">
	<div class="results">
    	<div class="row_div">
        	<div class="row">
            	<div class="col-sm-4">
                	<div class="col_img">
                    	<a href="#" class="title"><span class="glyphicon glyphicon-tag"></span> <?php echo $row['category'];?></a>
                    	<a href="#"><img src="../wp-content/plugins/hotelbooking/uploads/hotels/<?php echo $row['photo'];?>" /></a>
                    </div>
              	</div>
                <div class="col-sm-8">
                	<div class="res_title"><a href="#"><h4><?php echo $row['hotel'];?></h4></a></div>
                	<div class="row">
                    	<div class="col-sm-12">
                            <div class="restaurant_info">
                            <p class="min-description"><span class="glyphicon glyphicon-map-marker"></span><?php echo $row['address'];?></p>
                            <!--<div class="price">
                                <ul>
                                    <li><p class="lkr">LKR <strong>4,999</strong></p></li>
                                    <li><p class="lkr">LKR <strong>2,999</strong></p></li>
                                </ul>
                            </div>-->
                            <!--<div class="rooms">
                            	<p><i class="fa fa-bookmark"></i> Standard Double Room </p>
                            </div>-->
                            <div class="features">
                            	<h2>Hotel Details</h2>
					<p><?php echo $row['description'];?></p>
                            </div>
                            </div>
                    	</div>                      
                </div>
            </div>
			<div class="col-sm-12">
				<div class="hotel-descr">
					<h2>Rooms Information</h2>
					<table width="100%" border="0" align="left" cellpadding="5" cellspacing="0">
						<tr>
							<th width="20%">Rooms</th>
							<th width="20%">Price Per Night</th>
                            <?php /*?><th width="25%">Available ?</th><?php */?>
                            <th width="20%">Rooms</th>
							<th width="20%">Child with bed or Extra Bed</th>
							<th>&nbsp;</th>
						</tr>
						<?php
						$SQL  =  "SELECT * FROM  room WHERE hotel_id =  $hid AND  status = '0'  "; 
						
						$r = mysqli_query($alink,$SQL) or die($SQL);
						$cnt = 1;
						while($res = mysqli_fetch_array($r, MYSQLI_ASSOC))
						{ 
						?>
                        <tr>
							<td><?php echo $res['room_type'];?></td>
							<?php /*?><td>LKR <?php echo $res['price'];  ($res['available'] == '1') ? $disabled = 'disabled="disabled"': $disabled = '';?></td><?php */?>
                            <td>LKR <?php echo $res['price'];?></td>
                            <?php /*?><td><?php echo ($res['available'] == '1') ? 'No': 'Yes';?></td><?php */?>
                            <td><select id="noofrooms_<?php echo $res['id'];?>">
                            <option value="1">1 Room</option>
                            <option value="2">2 Rooms</option>
                            <option value="3">3 Rooms</option>
                            <option value="4">4 Rooms</option>
                            <option value="5">5 Rooms</option>
                            </select></td>
							<td><select id="cwb_<?php echo $res['id'];?>">
                            <option value="n">No</option>
                            <option value="y">Yes</option>
                            </select></td>
							<td align="center">
                            <input type="hidden" id="roomid_<?php echo $cnt;?>" value="<?php echo $res['id'];?>" />
                            <input type="hidden" id="hotelid_<?php echo $cnt;?>" value="<?php echo $row['id'];?>" />
                           <?php /*?> <button <?php echo $disabled;?> onclick="getroom(<?php echo $res['id'];?>);" class="book-mx----" data-toggle="modal" data-target="#myModal" id="#myModal" >Book Now</button><?php */?>
                            <button onclick="getroom(<?php echo $res['id'];?>);" class="book-mx" data-toggle="modal" data-target="#myModal" id="#myModal" >Book Now</button>
                            </td>
						</tr>
                        <?php
						$cnt++ ;
						}
						?>
					</table>
					<h2>Other Information</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam molestie lectus risus, a scelerisque nibh semper id. Suspendisse faucibus consequat metus sed eleifend. Duis at eros ornare lorem vestibulum venenatis id eu odio. Etiam ultrices diam ipsum, eu faucibus nunc fringilla eget. In fringilla nunc magna, at ornare ante euismod euismod. Donec dignissim hendrerit urna, vehicula luctus justo lacinia in. Quisque egestas eu enim a finibus.</p>
				</div>
			</div>
        </div>
    </div>
</div>
</div>
<?php
}
?>
<?php
$sql = " SELECT count(*) as ct FROM tour WHERE city_id = $cid AND status = '0'  ";
$output = mysqli_query($alink,$sql) or die($sql);
$out = mysqli_fetch_array($output, MYSQLI_ASSOC);
$cnt = $out['ct'];
?>
<form name="frmAvail" id="frmAvail" action="../tour-package" method="post">
        <input type="hidden" name="city" value="<?php echo $_REQUEST['city'];?>" />
        <input type="hidden" name="cid" value="<?php echo $cid;?>" />
        <input type="hidden" name="checkin" value="<?php echo $_REQUEST['checkin'];?>" />
        <input type="hidden" name="checkout" value="<?php echo $_REQUEST['checkout'];?>" />
        <input type="hidden" name="adult" value="<?php echo $_REQUEST['adult'];?>" />
        <input type="hidden" name="child" value="<?php echo $_REQUEST['child'];?>" />
        <input type="hidden" name="infant" value="<?php echo $_REQUEST['infant'];?>" />
        <input type="hidden" name="hid" value="<?php echo $hid;?>" />
        <input type="hidden" name="tourids" value="<?php echo $cnt;?>" />
        <input type="hidden" name="roomid" id="roomid" value="">
        <input type="hidden" name="noofrooms" id="noofrooms" value="">
		<input type="hidden" name="cwb" id="cwb" value="">
</form>
	<!--mododle-->

<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/flight-search.js"></script>
<script type="application/javascript">
//jQuery('.date-pick').datepicker({ autoclose: true});
</script>
<!--<script type="text/javascript" src="<?php echo site_url(); ?>/wp-content/plugins/hotelbooking/autocomplete/jquery.js"></script>-->
<script type='text/javascript' src='<?php echo site_url(); ?>/wp-content/plugins/hotelbooking/autocomplete/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>/wp-content/plugins/hotelbooking/autocomplete/jquery.autocomplete.css" />

<script type="application/javascript">


jQuery(document).ready(function() {
	jQuery("#city").autocomplete("<?php echo site_url(); ?>/wp-content/plugins/hotelbooking/ajaxpages/get_city.php", {
		width: 260,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});

//---- Added - Validation
jQuery('#sub').click( function () {
	
	if(jQuery('#city').val() == '')
	{
		alert('Please Enter City');
		jQuery('#city').focus();
		return false;
	}else if(jQuery('#checkin').val() == ''){
		alert('Please Enter Check In Date');
		jQuery('#checkin').focus();
		return false;
	}else if(jQuery('#checkout').val() == ''){
		alert('Please Enter Check Out Date');
		jQuery('#checkout').focus();
		return false;
	}
  return true;
});


});

// For RoomID
function getroom(roomid) {
	jQuery('#roomid').val(roomid);
	var noofrooms=jQuery('#noofrooms_'+roomid).val();
	var cwb=jQuery('#cwb_'+roomid).val();
	jQuery('#noofrooms').val(noofrooms);
	jQuery('#cwb').val(cwb);
	jQuery('#frmAvail').submit();
}

</script>
<!-- SUbir Added - 03/12/15-->
<script>
$(function(){
	$('.carousel').carousel({
	  interval: 2000
	})	
});
</script>
<?php
}
get_footer();
