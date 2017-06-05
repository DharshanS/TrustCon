<?php
/*
Template Name: Holiday Tour Package
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Template Name : Home
 *
 * Created by ShineTheme
 */
get_header();
//print_r($_POST);


while(have_posts()){
    the_post();
	the_content();
$alink = mysqli_connect('localhost', 'i1611638_wp1', 'H(IXs5rqUc70.#4', 'i1611638_wp1');
?> 
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/jstourpackage/css/flexslider.css" />
<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/jstourpackage/css/new-style.css" />

<?php /*?><script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script><?php */?>

<script src="<?php echo get_template_directory_uri(); ?>/jstourpackage/jquery.flexslider.js"></script>
<script>
jQuery(window).load(function() {
  jQuery('.flexslider').flexslider({
	animation: "slide",
	controlNav: "thumbnails"
  });
  jQuery( function() {
	jQuery( ".datepicker" ).datepicker();
  } );
});
</script>
<script src="<?php echo get_template_directory_uri(); ?>/jstourpackage/jquery.easing.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/jstourpackage/jquery.mousewheel.js"></script>
<form name="frmAvail" id="frmAvail" action="../holiday-booking" method="post">
        <input type="hidden" name="city" value="<?php echo $_POST['city'];?>" />
        <input type="hidden" name="cid" value="<?php echo $_POST['cid'];?>" />
        <input type="hidden" name="checkin" value="<?php echo $_POST['checkin'];?>" />
        <input type="hidden" name="checkout" value="<?php echo $_POST['checkout'];?>" />
        <input type="hidden" name="adult" value="<?php echo $_POST['adult'];?>" />
        <input type="hidden" name="child" value="<?php echo $_POST['child'];?>" />
        <input type="hidden" name="infant" value="<?php echo $_POST['infant'];?>" />
        <input type="hidden" name="hid" value="<?php echo $_POST['hid'];?>" />
        <input type="hidden" name="tourids" value="<?php echo $_POST['tourids'];?>" />
        <input type="hidden" name="roomid" id="roomid" value="<?php echo $_POST['roomid'];?>">
        <input type="hidden" name="noofrooms" id="noofrooms" value="<?php echo $_POST['noofrooms'];?>">
		<input type="hidden" name="cwb" id="cwb" value="<?php echo $_POST['cwb'];?>">
        <table class="tbl-des-tbl" border="1" bordercolor="#ddd">
        <tr>
            <th style="width:40px;"></th>
            <?php /*?><th style="width:500px;">Tour Name</th><?php */?>
            <th style="width:300px;">Tour Name</th>
            <th style="width:250px;">Tour Description</th>
            <th style="width:100px;">Tour Date</th>
            <th style="width:100px;">Transport</th>
            <th style="width:100px;">Amount</th>
        </tr>
         <?php
		$sql = " SELECT * FROM tour WHERE city_id = '".$_POST['cid']."' AND status = '0'";
		$output = mysqli_query($alink,$sql) or die($sql);
		$cnt = 1;
		while($out = mysqli_fetch_array($output, MYSQLI_ASSOC))
		{ 
		?>
        <tr>
          <td align="left" valign="top"><input name="selecttour_<?php echo $cnt;?>" id="selecttour_<?php echo $cnt;?>" type="checkbox" value="<?php echo $out['id'];?>" />
          <input type="hidden" name="tourprice_<?php echo $cnt;?>"  value="<?php echo $out['price'];?>" />
          <input type="hidden" name="tourname_<?php echo $cnt;?>"  value="<?php echo $out['tour'];?>" />
          </td>
            <td align="left" valign="top">
                <table class="sub-tbl-des-tbl">
                    <tr>
                        <td>Tour Images</td>
                        <?php /*?><td>Tour Video</td><?php */?>
                    </tr>
                    <tr>
                        <td>
                            <div class="flexslider">
                              <ul class="slides">
                                <li data-thumb="../wp-content/plugins/hotelbooking/uploads/tours/<?php echo $out['photo'];?>">
                                  <img src="../wp-content/plugins/hotelbooking/uploads/tours/<?php echo $out['photo'];?>" />
                                </li>
                                <?php /*?><li data-thumb="<?php echo get_template_directory_uri(); ?>/jstourpackage/02.png">
                                  <img src="<?php echo get_template_directory_uri(); ?>/jstourpackage/02.png" />
                                </li>
                                <li data-thumb="<?php echo get_template_directory_uri(); ?>/jstourpackage/03.png">
                                  <img src="<?php echo get_template_directory_uri(); ?>/jstourpackage/03.png" />
                                </li>
                                <li data-thumb="<?php echo get_template_directory_uri(); ?>/jstourpackage/04.png">
                                  <img src="<?php echo get_template_directory_uri(); ?>/jstourpackage/04.png" />
                                </li><?php */?>
                              </ul>
                            </div>
                        </td>
                        <?php /*?><td>
                            <iframe width="250" height="165" src="https://www.youtube.com/embed/21mKbR-4VTA?rel=0" frameborder="0" allowfullscreen></iframe>
                        </td><?php */?>
                    </tr>
                </table>
            </td>
            <td align="left" valign="top">
                <?php echo $out['description'];?>
            </td>
            <?php /* ?><td align="left" valign="top"><input type="text" name="tour_date_<?php echo $cnt;?>"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" class="datepicker"></td><?php */ ?>
			 <td align="left" valign="top"><input type="text" name="tour_date_<?php echo $cnt;?>" id="tour_date_<?php echo $cnt;?>"  data-date-format="yyyy-mm-dd" data-date-start-date="<?php echo $_POST['checkin'];?>" data-date-end-date="<?php echo $_POST['checkout'];?>" class="datepicker"></td>
            <td align="left" valign="top">
                <table class="sub-tbl-des-tbl2">
                    <tr>
                        <td>SIC</td>
                        <td>PVT</td>
                    </tr>
                    <tr>
                        <td><input type="radio" name="transport_type_<?php echo $cnt;?>" value="sic" /></td>
                        <td><input type="radio" name="transport_type_<?php echo $cnt;?>" value="pvt"/></td>
                    </tr>
                </table>
            </td>
            <td align="left" valign="top">LKR<?php echo $out['price'];?></td>
        </tr>
       <?php
	   $cnt++;
		}
	   ?> 
    </table>
    <center>    
        <button type="button" onclick="history.go(-1);" class="btn btn-default" style="font-size:20px!important">Back</button>
        <button type="submit" class="btn btn-primary" style="font-size:20px!important">Book Now</button>
	</center>  
</form>

<?php
/*echo "<pre>";
print_r($_POST);*/
?>
<script>
 
 /** Days to be disabled as an array */
var disableddates = ["2016-09-02"];
 
function DisableSpecificDates(date) {
	 var m = date.getMonth();
	 m=m + 1; // JavaScript months are 0-11
	 var d = date.getDate();
	 var y = date.getFullYear();
	 
	if (m < 10)m = '0' + m;
	if (d <10)d = '0' + d;
	 
	 // date in the yyyy-mm-dd format 
	 var currentdate = y + '-' + m + '-' + d ;
	 for (var i = 0; i < disableddates.length; i++) {
 		 // Now check if the current date is in disabled dates array. 
		 if (currentdate==disableddates[i]+"") {
	       return false;
		 } 
	 }
   return true;
 }
 
<?php for($i=$cnt;$i>0;$i--){?>
 jQuery(function() {
 jQuery( "#tour_date_<?php echo $i;?>" ).datepicker({
	 autoclose: true,
     beforeShowDay: DisableSpecificDates
 }).on('change', function(){  // changeDate  use korle blank ta fire hacche na
        getselecteddates();
		redrawcalendar();
    });

 });
<?php } ?> 
function getselecteddates(){
disableddates=[];
<?php for($i=1;$i<$cnt;$i++){?>
   var dt=jQuery( "#tour_date_<?php echo $i;?>").val();
   if(dt!=''){
	  disableddates.push(dt); 
   }
<?php } ?> 
}
function redrawcalendar(){
<?php for($i=1;$i<$cnt;$i++){?>
 jQuery( "#tour_date_<?php echo $i;?>").datepicker('update');
<?php } ?> 
}
 </script>
<?php
}
get_footer();
