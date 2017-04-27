<?php
/*
Template Name: Holidays
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Template Name : Home
 *
 * Created by ShineTheme
 * Rajib Ganguly -- 18-11-2015
 */
if ( is_user_logged_in() ){
	if(isset($_SESSION['redirectoccurin'])&& $_SESSION['redirectoccurin']!=''){
		$ru=$_SESSION['redirectoccurin'];
		wp_redirect( home_url("/".$ru) ); exit;
	}
}else {
       
}

get_header();
while(have_posts()){
    the_post();
	the_content();
	?>
    <div class="bookingarea">
    <div class="booking-system">
      <div class="top-tab">
    <ul>
                <!--<li ><a href="<?php echo site_url(); ?>/flight-search/">Flight</a></li>-->
                <li class="active"><a href="<?php echo site_url(); ?>/hotel-search/">Holiday</a></li>
                <!--<li><a href="#">Flight+hotel</a></li>
                <li><a href="#">Flight Status</a></li>-->
            </ul>
            <div class="clearfix"></div>
        </div>
        <div id="content">
        <form action="<?php echo site_url(); ?>/holiday-result/" method="post" autocomplete="off">
         <input type="hidden" name="date_flexi" value="0" />
            <div class="booking-area">
                <div class="row">
                    <div class="col-sm-12 small-mrgn">
                    	<p>
                        <!-- Rajib changed Dropdown-->
                        <!--<input type="text" name="city" placeholder="Enter City Name"  id="city" />-->
                        <select name="city" >
                        	<option value="">-Select City-</option>
                                <?php
								global $wpdb;
								$vresult = $wpdb->get_results('SELECT * FROM city WHERE status = "0" ORDER BY city');
								foreach($vresult as $res)
								{
									echo '<option value="'.$res->city.','.$res->country.'">'.$res->city.','.$res->country.'</option>';
								}
								?>
                        </select>
                        </p>
                        <!--<div id="result"></div>-->
                    </div>
                    <div class="col-sm-6 small-mrgn">
                        <div class="date_picker">
                            <input type="text" class="date-pick search_arrival"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" id="checkin" name="checkin" placeholder="Check In" />
                        </div>
                    </div>
                    <div class="col-sm-6 small-mrgn">
                        <div class="date_picker">
                            <input type="text"  class="date-pick search_arrival"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" id="checkout" name="checkout" placeholder="Check Out" />
                        </div>
                    </div>
                    <hr />
                    <!-- Rajib - for time being -->
                    <!--<input type="hidden" name="person" id="person" value="1"  />-->
                    <!-- Rajib - for time being -->
                    <div class="col-sm-12 small-mrgn">
                        <div class="slt">
                            <label>Adult(s): </label>
                            <select name="adult">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                        </div>
                        <div class="slt">
                            <label>Child(s): </label>
                            <select name="child">
                            	<option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                        </div>
                        <div class="slt">
                            <label>Infant(s): </label>
                            <select name="infant">
                            	<option value="0">0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-sm-6 small-mrgn lbl">
                    </div>
                    <div class="col-sm-6 small-mrgn">
                        <button type="submit" id="sub" name="sub">Book Now</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </form>
        </div>
    </div>    
    </div>
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

jQuery('#checkin').change(function () {
    jQuery('#checkout').datepicker('setStartDate', jQuery('#checkin').val());
});
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
	}else if(jQuery('#checkout').val() == jQuery('#checkin').val()){
		alert('Checkin Date & Checkout Date Should Not Be Equal');
		jQuery('#checkout').val('');
		jQuery('#checkout').focus();
		return false;
	}
  return true;
});

});
</script>
<?php
}
get_footer();
