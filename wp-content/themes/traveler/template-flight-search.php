<?php
/*
Template Name: Flight Search
*/
/*
Abdul Manashi
*/
/**
 * @package WordPress
 * @subpackage Traveler
 * @since 1.0
 *
 * Page.php
 *
 * Created by ShineTheme
 *
 */

error_log("----------------- > Flight Search ");
get_header();
 $sidebar_id=apply_filters('st_blog_sidebar_id','blog');
?>
    <div class="container">
        <h1 class="page-title"><?php //the_title()?></h1>
        <div class="row mb20">
            <?php $sidebar_pos=apply_filters('st_blog_sidebar','right');
			error_log("flight result-search php  ");
            if($sidebar_pos=="left"){
                get_sidebar('blog');
            }
            ?>
            <div class="<?php echo apply_filters('st_blog_sidebar','right')=='no'?'col-sm-12':'col-sm-9'; ?>">
                <?php while(have_posts()){
                    the_post();
                    ?>
                    <div <?php post_class()?>>
                        <div class="entry-content">
                            <?php
                            //the_content();
                            ?>
                            <div class="booking-system">
                              <div class="top-tab">
                            <ul>
                                        <li class="active"><a href="<?php echo site_url(); ?>/flight-search/">Book</a></li>
                                        <li><a href="#">Schedule</a></li>
                                        <li><a href="#">Flight Status</a></li>
                                        <li><a href="#">Web Check-in</a></li>
                                    </ul>
                                    <div class="clearfix"></div>
                                </div>
                                <form action="<?php echo site_url(); ?>/Flight-Request" method="post">
                                    <div class="booking-area">
                                        <div class="row">
                                            <div class="col-sm-8 small-mrgn">
                                                <input type="radio" name="mode" onchange="chng_way(this);" value="oneway" />One Way
                                                <input type="radio" name="mode" onchange="chng_way(this);" id="roundtrip" value="roundtrip" checked />Round Trip
                                            </div>
                                            <div class="col-sm-4 small-mrgn">
                                                <a href="#" class="mlt">Multi City >></a>
                                            </div>
                                            <hr />
                                            <div class="col-sm-12 small-mrgn">
                                                <input type="text" name="from_city" placeholder="From" class="typeahead cls-booking-dtn" id="id-booking-dtn" />
                                            </div>
                                            <div class="col-sm-12 small-mrgn">
                                                <input type="text" name="to_city" placeholder="To" class="typeahead cls-booking-arvl" id="id-booking-arvl" />
                                            </div>
                                            <div class="col-sm-6 small-mrgn">
                                                <div class="date_picker">
                                                    <input type="text" class="date-pick search_arrival"  data-date-format="M d, D" name="depart_date" placeholder="Departing" />
                                                </div>
                                            </div>
                                            <div class="col-sm-6 small-mrgn">
                                                <div class="date_picker">
                                                    <input type="text"  class="date-pick search_arrival"  data-date-format="M d, D" name="return_date" id="return_date" placeholder="Returning" />
                                                </div>
                                            </div>
                                            <div class="col-sm-12 small-mrgn">
                                                <input type="radio" name="date_flexi" value="0" checked />Fixed Dates
                                                <input type="radio" name="date_flexi" value="1" />Flexible Dates
                                            </div>
                                            <hr />
                                            <div class="col-sm-12 small-mrgn">
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
                                            <div class="col-sm-6 small-mrgn lbl">
                                                <label></label>
                                                <select name="cabinclass">
                                                    <option value="Economy">Economy</option>
                                                    <option value="Business">Business</option>
                                                    <option value="First">First Class</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-6 small-mrgn">
                                                <button type="submit">Book Now</button>
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
                                                    <input type="text" class="date-pick search_arrival"  data-date-format="M d, D" name="depart_date[]" placeholder="Departing" />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="multi-option">
                                            <div class="col-sm-4 fl">
                                                <input type="text" name="from_city[]" placeholder="From" class="typeahead cls-multi-booking-dtn"/>
                                            </div>
                                            <div class="col-sm-4 fl">
                                                <input type="text" name="to_city[]" placeholder="To" class="typeahead cls-multi-booking-arvl"/>
                                            </div>
                                            <div class="col-sm-4 fl">
                                                <div class="date_picker">
                                                    <input type="text" class="date-pick search_arrival"  data-date-format="M d, D" name="depart_date[]" placeholder="Departing" />
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
                                                <select>
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
                                            <button type="submit">Book Now</button>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                            
                            
                        </div>
                        <div class="entry-meta">
                            <?php
                            wp_link_pages( );
                           // edit_post_link(st_get_language('edit_this_page'), '<p>', '</p>');
                            ?>
                        </div>
                    </div>
                <?php
                }?>
            </div>
            <?php
            if($sidebar_pos=="right"){
                get_sidebar('blog');
            }
            ?>
        </div>
    </div>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/flight-search.js"></script>
<?php
get_footer();