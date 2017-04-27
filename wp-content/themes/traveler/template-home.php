<?php
/*
Template Name: Home
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Template Name : Home
 *
 * Created by ShineTheme
 *
 */
error_log("----------------- > Home tamplate ");
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

                <li class="active"><a href="<?php echo site_url(); ?>/flight-search/">Flight</a></li>


            </ul>
            <div class="clearfix"></div>
        </div>
        <form action="<?php echo site_url(); ?>/flight-result/" method="post">
         <input type="hidden" name="date_flexi" value="0" />
            <div class="booking-area">
                <div class="row">
                    <div class="col-sm-8 small-mrgn">
                        <input type="radio" name="mode" onchange="chng_way(this);" value="oneway" />One Way
                        <input type="radio" name="mode" onchange="chng_way(this);" id="roundtrip" value="roundtrip" checked />Round Trip
                    </div>
                    <div class="col-sm-4 small-mrgn">
                        <a style="cursor:pointer;" class="mlt" id="mlt-st">Multi City >></a>
                    </div>
                    <hr />
                    <div class="col-sm-12 small-mrgn">
                        <input type="text" name="from_city" value="CMB,Colombo,Sri Lanka" placeholder="From" class="typeahead cls-booking-dtn" id="id-booking-dtn" />
                    </div>
                    <div class="col-sm-12 small-mrgn">
                        <input type="text" name="to_city" placeholder="To" class="typeahead cls-booking-arvl" id="id-booking-arvl" />
                    </div>
                    <div class="col-sm-6 small-mrgn">
                        <div class="date_picker">
                            <input type="text" class="date-pick search_arrival"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" name="depart_date" id="depart_date" placeholder="Departing" />
                        </div>
                    </div>
                    <div class="col-sm-6 small-mrgn">
                        <div class="date_picker">
                            <input type="text"  class="date-pick search_arrival"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" name="return_date" id="return_date" placeholder="Returning" />
                        </div>
                    </div>
                    <?php /*?><div class="col-sm-12 small-mrgn">
                        <input type="radio" name="date_flexi" value="0" checked />Fixed Dates
                        <input type="radio" name="date_flexi" value="1" />Flexible Dates
                    </div><?php */?>
                    <hr />
                    <div class="col-sm-12 small-mrgn">
                        <div class="slt">
                            <label>Adults: </label>
                            <select name="adult" id="adutl_no_one">
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
                            <label>Children:</label>
                            <select name="child" id="child_no_one">
                             <option value="0">0</option>
                             <option value="1">1</option>
                             <option value="2">2</option>
                            </select>
                        </div>
                        <div class="slt">
                            <label>Infants: </label>
                            <select name="infant" id="infant_no_one">
                                 <option value="0">0</option>
                                 <option value="1">1</option>
                            </select>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="col-sm-6 small-mrgn lbl">
                        <label></label>
                        <select name="cabinclass">
                            <option value="Economy">Economy</option>
                            <option value="Business">Business</option>
                            <option value="First">First Class</option>
                        </select>
                    </div>
                    <div class="col-sm-6 small-mrgn">
                        <button type="submit" id="sub">Book Now</button>
                        <!-- Rajib Added -->
                        <input type="hidden" name="passport_type" id="passport_type" value=""  />
                        <!-- Rajib Added -->
                   </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </form>
        <div class="multiple-system booking-area">
         <form action="<?php echo site_url(); ?>/flight-result/" method="post">
          <input type="hidden" name="mode" value="multicity" />
            <div class="row">
                <div class="multi-option">
                    <div class="col-sm-4 fl">
                        <input type="text" name="from_city[]" placeholder="From" class="typeahead cls-multi-booking-dtn"/>
                    </div>
                    <div class="col-sm-4 fl">
                        <input type="text" name="to_city[]" placeholder="To" class="typeahead cls-multi-booking-arvl"/>
                    </div>
                    <div class="col-sm-4 fl">
                        <div class="date_picker">
                            <input type="text" class="date-pick"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" name="depart_date[]" placeholder="Departing" />
                        </div>
                    </div>
                </div>
                <div class="multi-option">
                    <div class="col-sm-4 fl">
                        <input type="text" name="from_city[]" placeholder="From" class="typeahead cls-multi-booking-dtn"/>
                        <!-- Rajib Added -->
                        <input type="hidden" name="passport_type" id="passport_type" value=""  />
                        <!-- Rajib Added -->
                    </div>
                    <div class="col-sm-4 fl">
                        <input type="text" name="to_city[]" placeholder="To" class="typeahead cls-multi-booking-arvl"/>
                    </div>
                    <div class="col-sm-4 fl">
                        <div class="date_picker">
                            <input type="text" class="date-pick"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" name="depart_date[]" placeholder="Departing" />
                        </div>
                    </div>
                </div>
                <span id="pps"></span>
                <div class="col-sm-6 fl">
                    <button type="button" id="addcity-btn">Add City(+)</button>
                </div>
                <hr />
                <div class="col-sm-12 fl">
                    <div class="slt">
                        <label>Adults: </label>
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
                        <label>Children:</label>
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
                        <label>Infants: </label>
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
                <div class="col-sm-6 fl lbl">
                    <label></label>
                    <select name="cabinclass">
                        <option value="Economy">Economy</option>
                        <option value="Business">Business</option>
                        <option value="First">First Class</option>
                    </select>
                </div>
                <div class="col-sm-6 fl">
                    <button type="submit" id="sub">Book Now</button>
                        <!-- Rajib Added -->
                        <input type="hidden" name="passport_type" id="passport_type" value=""  />
                        <!-- Rajib Added -->
                </div>
            </div>
        </form>
        </div>
    </div>    
    </div>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/flight-search.js"></script>
<script type="application/javascript">
//jQuery('.date-pick').datepicker({ autoclose: true});
jQuery(document).ready( function () {
	jQuery('#id-booking-arvl').blur( function () {
	if(jQuery('#id-booking-dtn').val() != '')
	{	
		var from = jQuery('#id-booking-dtn').val();
		var to = jQuery('#id-booking-arvl').val();
		var vto = to.split(",");
		var vfrom = from.split(",");
		//alert(vfrom[2]+'==='+vto[2]);
		if(vfrom[2] == vto[2])
		jQuery('#passport_type').val('domestic');
		else
		jQuery('#passport_type').val('international');
		//alert(jQuery('#passport_type').val());
	}else{
		jQuery('#id-booking-dtn').focus();
	}
 });
});
</script>
<?php
}
get_footer();
