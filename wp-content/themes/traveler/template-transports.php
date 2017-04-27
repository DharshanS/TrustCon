<?php
/*
Template Name: Transports
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
                <li class="active"><a href="<?php echo site_url(); ?>/transport-search/">Transports</a></li>
                <!--<li><a href="#">Flight+hotel</a></li>
                <li><a href="#">Flight Status</a></li>-->
            </ul>
            <div class="clearfix"></div>
        </div>
        <div id="content">
        <form action="<?php echo site_url(); ?>/transport-result/" method="post" autocomplete="off">
         <input type="hidden" name="date_flexi" value="0" />
            <div class="booking-area">
                <div class="row">
                    <div class="col-sm-12 small-mrgn">
                    	<p>
                        <input type="text" name="start" placeholder="Start City Name"  id="start" />
                        </p>
                        <!--<div id="result"></div>-->
                    </div>
                    <div class="col-sm-12 small-mrgn">
                    	<p>
                        <input type="text" name="end" placeholder="End City Name"  id="end" />
                        </p>
                        <!--<div id="result"></div>-->
                    </div>
                    <div class="col-sm-6 small-mrgn">
                        <div class="date_picker">
                            <input type="text" class="date-pick search_arrival"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" id="from" name="from" placeholder="From" />
                        </div>
                    </div>
                    <div class="col-sm-6 small-mrgn">
                        <div class="date_picker">
                            <input type="text"  class="date-pick search_arrival"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" id="to" name="to" placeholder="To" />
                        </div>
                    </div>
                    <hr />
<div class="col-sm-12 small-mrgn">
                        <div class="slt">
                            <label>Vehicle: </label>
                            <select name="vehicle" id="vehicle">
                            <option value="">-Select-</option>
                                <?php
								global $wpdb;
								$vresult = $wpdb->get_results('SELECT * FROM vehicle WHERE status = "0" ORDER BY vehicle');
								foreach($vresult as $res)
								{
									echo '<option value="'.$res->id.'">'.$res->vehicle.'('.$res->vehicle_number.')'.'</option>';
								}
								?>
                            </select>
                        </div>
                        <div class="slt">
                            <label>Person(s): </label>
                            <select name="person">
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
<script type='text/javascript' src='<?php echo site_url(); ?>/wp-content/plugins/transportbooking/autocomplete/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>/wp-content/plugins/transportbooking/autocomplete/jquery.autocomplete.css" />

<script type="application/javascript">


jQuery(document).ready(function() {
	jQuery("#start").autocomplete("<?php echo site_url(); ?>/wp-content/plugins/transportbooking/ajaxpages/get_city.php", {
		width: 260,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});

	jQuery("#end").autocomplete("<?php echo site_url(); ?>/wp-content/plugins/transportbooking/ajaxpages/get_city.php", {
		width: 260,
		matchContains: true,
		//mustMatch: true,
		//minChars: 0,
		//multiple: true,
		//highlight: false,
		//multipleSeparator: ",",
		selectFirst: false
	});


// Rajib Added - Validation
jQuery('#sub').click( function () {
	
	if(jQuery('#start').val() == '')
	{
		alert('Please Enter Start City');
		jQuery('#start').focus();
		return false;
	}else if(jQuery('#end').val() == ''){
		alert('Please Enter End City');
		jQuery('#end').focus();
		return false;
	}else if(jQuery('#from').val() == ''){
		alert('Please Enter Journer From Date');
		jQuery('#from').focus();
		return false;
	}else if(jQuery('#to').val() == ''){
		alert('Please EnterJourner To Date');
		jQuery('#to').focus();
		return false;
	}else if(jQuery('#vehicle').val() == ''){
		alert('Please Select Vehicle');
		jQuery('#vehicle').focus();
		return false;
	}
  return true;
});

});
</script>
<?php
}
get_footer();
