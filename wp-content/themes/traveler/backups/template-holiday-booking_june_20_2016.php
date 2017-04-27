<?php
/*
Template Name: Holiday Booking
*/
/*
Rajib Ganguly
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

set_time_limit(0);
global $wpdb; 
$useremail='';
$userid=0;
if ( is_user_logged_in() ) {
	$current_user = wp_get_current_user();
	//echo 'Personal Message For ' . $current_user->user_firstname . '!';
	if(isset($_SESSION['redirectoccurin'])){
		$_SESSION['redirectoccurin']='';
		unset($_SESSION['redirectoccurin']);
	}
	$useremail=$current_user->user_email;
	$userid=$current_user->ID;
}else {
        $_SESSION['bookingdata']=$bookingdataoriginal;
        $_SESSION['searchdata']=$searchdataoriginal;
		$_SESSION['redirectoccurin']='holiday-booking';
		wp_redirect( home_url("/login") ); exit;
}
get_header();
/*echo '<pre>';
print_r($_REQUEST);*/
//die();

## Tour IDs
$tourids = '';
for($i=1; $i<=$_REQUEST['tourids'];$i++)
{
	if(isset($_REQUEST['selecttour_'.$i]))
	$tourids .= $_REQUEST['selecttour_'.$i].',';
}
$tourids  = rtrim($tourids, ",");
## Tour IDs

$city = explode(',',$_POST['city']);
$adult = $_POST['adult'];
$child = $_POST['child'];
$infant = $_POST['infant'];
$checkin = $_POST['checkin'];
$checkout = $_POST['checkout'] ;
$tourid = $_REQUEST['selecttour'];
$roomid = $_REQUEST['roomid'];

$hotelid=$_REQUEST['hid'];

//echo 'RoomID:-'.$roomid;
## Get data
/*$result = $wpdb->get_results(" SELECT * FROM city c,hotel h, room r,tour t WHERE c.id = h.city_id  AND h.id = r.hotel_id AND h.status = 0  AND c.id = t.city_id  AND r.id = ".$roomid."  AND t.id = ".$tourid." ");*/

$result = $wpdb->get_results(' SELECT * FROM  room  WHERE  id = '.$roomid.'  AND status = "0" AND available = "0" ');
foreach($result as $res){ }
//print_r($res);
## Room data
$roomamount = $res->price;
$roomtype =  $res->room_type.'( LKR '.$roomamount.')';

## Room Availibility check
if(!$result)
{
?>
<script>
setTimeout(function () {
	   alert('This Room is Not Available');
       window.history.back();
    }, 2000);
</script>
<?php
}
## Room Availibility check

## Tour data
//$output = $wpdb->get_results(' SELECT *,h.photo AS img FROM  hotel h,tour t  WHERE  h.id = t.hotel_id AND t.id IN ('.$tourids.')  AND t.status = "0"  ');

$SQL="SELECT * FROM `hotel` WHERE `id`='".$hotelid."'";
$output = $wpdb->get_results($SQL);
$out=current($output);
$touramount=0;
$tour='';

// $SQL="SELECT * FROM `tour` WHERE  `hotel_id`='".$hotelid."' AND id IN ('".$tourids."')  AND status = 0";
$SQL="SELECT * FROM `tour` WHERE  id IN (".$tourids.")  AND status = '0'";
$outputtour = $wpdb->get_results($SQL);
$n=1;
foreach($outputtour as $outtour)
{ 
   $touramount += $outtour->price;
   $tour .=  $n++.". ".$outtour->tour.'(LKR '.$outtour->price.')<br>';
}



//$touramount = $out->price;
//$tour =  $out->tour;
//$image =  $out->img;
$image =  $out->photo;
$hotelname = $out->hotel;

## Days for
$To = new DateTime($checkout);
$From = new DateTime($checkin);
$interval = $To->diff($From);
$days = $interval->d ;

## Total
$totAmount = $touramount + ($roomamount * $adult);
$totAmount = $totAmount * $days;
?>

<div class="sky-bg">

  <div class="pop-edit"></div>

  <div class="container">

  <div class="main_wrapp">

    <div class="booking_panel">

    

      <div class="heading">

        <h3>Booking</h3>

        <h6>Book with simple 5 steps</h6>

      </div>

      <div id="reserved" style="width:100%;">

      <div class="step" id="step1" style="width:100%;">

       <div class="main_content br">

       <div class="booking_wrapp">

        <div class="heading pay">

          <h3><span>1</span>Itinerary</h3>

          <h6>Review your Holiday details</h6>

          <div class="clearfix"></div>

        </div>

        </div>


        <div class="row">

          <div class="col-sm-12"> <span class="hd"><strong>Booking For <?php echo $checkin;?> To <?php echo $checkout;?></strong></span>

            <div class="first_before">


              <div class="first_before_row">

                <ul>

                  <li><img src="<?php echo site_url();?>/wp-content/plugins/hotelbooking/uploads/hotels/<?php echo $image;?>" class="icn" /><span> <br />

                    No of Days <?php echo $days;?> <br />
                    Adult(s)  <?php echo $adult;?><br />
                    Child(s)  <?php echo $child;?><br />
                    Infant(s)  <?php echo $infant;?><br />

                    <strong> </strong><?php echo $hotelname;?></span></li>

                  <li><span><strong class="big">Room Type </strong><br /><?php echo $roomtype;?> <br />

                     </li>
                    <li><span><strong class="big">Tour </strong><br /><?php echo $tour;?> <br />

                     </li>
                     
                 <!-- <li>

                    <center>

                      <img src="<?php echo get_template_directory_uri(); ?>/images/booking_dets_oneway_right.png" /><br />

                      <span>Non-stop</span>

                    </center>

                  </li>

                  <li><span><strong class="big"><?php echo date("h:i a",strtotime($segmentdtls['ArrivalTime']));?></strong> <?php echo date("D, d M",strtotime($segmentdtls['ArrivalTime']));?> <br />

                    <strong class="big"><?php echo $airportscity[$segmentdtls['Destination']];?> (<?php echo $segmentdtls['Destination'];?>)</strong></span></li>

                  <li><span><?php echo $BI['CabinClass'];?> <?php echo  $BI['BookingCode'];?></span></li>-->

                </ul>

                <div class="clearfix"></div>

              </div>


              <!--end row-->

            </div>

          </div>

        </div>

        <!--row 1 end-->

        


        <!--ROW END-->

        <div class="button_panel">

          <div class="row">

           <!-- <div class="col-sm-3">

            <form name="frmFL" id="frmFL<?php echo $z?>" method="post" action="farerule.php" target="_blank">

            <input type="hidden" name="k" value="<?php echo $FareRuleKey;?>" />

            <input type="hidden" name="ref" value="<?php echo $FareInfoRef;?>" />

            <ul>

                <li><img src="<?php echo get_template_directory_uri(); ?>/images/booking_op.png" /></li>

                <li><span>Fare Rules <a onclick="jQuery('#frmFL<?php echo $z;?>').submit();">click here</a></span></li>

             </ul>

            </form>

            </div>-->

           <!-- <div class="col-sm-3">

              <ul>

                <li><img src="<?php echo get_template_directory_uri(); ?>/images/booking_op.png" /></li>

                <li><span>Baggage Allowances :<br />

                  <?php echo $BaggageRestriction;?></span></li>

              </ul>

            </div>-->

          <!--  <div class="col-sm-3">

              <ul>

                <li><img src="<?php echo get_template_directory_uri(); ?>/images/booking_op.png" /></li>

              </ul>

            </div>-->
          </div>

        </div>

        <!--button panel end-->

        

        <div class="col-sm-6">

          <div class="details">

            <table width="100%" border="0" cellspacing="0" cellpadding="0">

              <tr>

                <td align="left"><span>Fare : LKR </span></td>

                <td align="right"><span> <?php echo $totAmount;?></span></td>

              </tr>

              <tr>

                <td align="left"><span>Taxes : LKR </span></td>

                <td align="right"><span> <?php echo $Taxes;?></span></td>

              </tr>
              
              <tr>

                <td align="left"><span>Special Discount : </span></td>

                <td align="right"><span>0</span></td>

              </tr>


              <tr>

                <td align="left"><span><strong>Total Amount : LKR </strong></span></td>

                <td align="right"><span><strong><?php echo $totAmount;?></strong></span></td>

              </tr>

               <?php /*?><tr>

                <td align="left"><span>Webfare Discount : LKR </span></td>

                <td align="right"><span><strong>
                
                 </strong></span></td>

              </tr>
              <tr>

                <td align="left"><span><strong class="larg">Cash price : LKR </strong></span></td>

                <td align="right"><span><strong><strong>207,736</strong></strong></span></td>

              </tr>

              <tr>

                <td align="left"><span>VISA Catds 5% Discounted Price :* LKR </span></td>

                <td align="right"><span> 198,216</span></td>

              </tr>
              <?php */?>
            </table>

          </div>

        </div>

        <div align="right" class="but_section">

          <input name="" type="button" value="Continue Booking" onclick="process1();" class="booking_btn" />
          
          <!-- Rajib added - 21/12/2015-->
          <input type="hidden" name="tourids" id="tourids" value="<?php echo $tourids;?>" />
          <!-- Rajib added - 21/12/2015-->
          
        </div>

      </div>

      </div><!--step1 end-->

      <div class="step" id="step1_after"  style="display:none;">

        <div class="booking_wrapp">

        <div class="wrapp">

          <div class="edit" onclick="jQuery('#step1_after').hide();jQuery('#step1').show('slow');editit('step1');"><span>Edit</span></div>

          <div class="no"><span>1</span></div>

          <div class="row">

            <div class="col-sm-9">

              <div class="left_panel">

                <div class="row">


                </div>

              </div>

            </div>

            <!--Left end-->

            

            <div class="col-sm-3">

              <div class="right_panel">

                <table width="100%" border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td align="left"><span>Base Fare : LKR </span></td>

                    <td align="right"><span> <?php echo $totAmount;?></span></td>

                  </tr>

                  <tr>



                    <td align="left"><span>Taxes : LKR </span></td>

                    <td align="right"><span> <?php echo $Taxes;?></span></td>

                  </tr>

			  <!-- Rajib Added - Webbfaire Discount for All -->
               <tr>

                <td align="left"><span>Webfare Discount : LKR </span></td>

                <td align="right"><span><strong>
                <?php
                    //echo 'yes';
					/*$res = 'SELECT discount FROM airlines WHERE airline = "$thisairlinename" ';
                    $result = $wpdb->get_results($res);
					$discount = $result[0]->discount;
					//echo $tPrice;
					//echo $result[0]->option_value; 
                    if($discount>0)
					{
					$disAmt = (substr($tPrice,3) / 100) * $discount;
						echo $currency.number_format($disAmt);
					}else{
						echo 'Nil';
					}*/
				?>
                 </strong></span></td>

              </tr>
              <!-- Rajib Added - Webbfaire Discount for All -->
              
              <!-- Rajib Added - Webbfaire Discount for Agent -->
				<?php
                ## Raib Added
				/*$myRole =  get_user_role();
                if(isset($myRole) && $myRole == 'agent')
				{
			    ?>
               <tr>

                <td align="left"><span>Agent Discount :</span></td>

                <td align="right"><span><strong>
                <?php
                   //echo 'yes';
					$res = 'SELECT * FROM wp_options WHERE option_name = "agent_commission" ';
                    $result = $wpdb->get_results($res);
					$commission = $result[0]->option_value;
					//echo $tPrice;
					//echo $result[0]->option_value; 
                    $webfaireamt = (substr($tPrice,3) / 100) * $commission;
                    echo $currency.number_format($webfaireamt);
                ## Raib Added
				?>
                </strong></span></td>

              </tr>
              <?php
				}*/
			  ?>
              <!-- Rajib Added - Webbfaire Discount for Agent -->

                  <tr>

                    <td align="left"><span>Cash price : LKR </span></td>

                    <td align="right"><span><strong>207,736</strong></span></td>

                  </tr>

                  <tr>

                    <td align="left"><span>VISA Catds 5% Discounted Price :* LKR </span></td>

                    <td align="right"><span> 198,216</span></td>

                  </tr>

                </table>

              </div>

            </div>

            <!--Right end--> 

          </div>

        </div>

        </div>

      </div>

      <div class="step" id="step2" style="width:100%;">

       <div class="booking_wrapp">

        <div class="heading pay">

          <h3><span>2</span>Email Address</h3>

           <h6>Where do you want the ticket</h6>

          <div class="clearfix"></div>

        </div>

        <div id="step2_dtls" style="display:none;">

            <div class="mail_details"> <span>* <strong>FREE Reservation:</strong> You can make No-commitment FREE Reservation online without making any payments now.</span> <br />

                <span>* <strong>Come back and Pay Later:</strong> We will send a link to your e-mail to return back to the booking page to continue to make payment.</span><br />

                <span>* <strong>Confirmed Reservation:</strong> Please continue to get a confirmed reservation to your e-mail & mobile, by arranging payment later you can get the e-ticket to travel.</span> <br />

                <span>* <strong>Before you travel:</strong> Please check you have valid passport & VISA to travel to the destination your flying to.</span> </div>

              <div class="clearfix"></div>

              <div class="col-sm-6">

                <div class="mail_box" id="step2_mail_box"> <span>Your e-mail address</span> <span id="emailerror" class="err" style="color:#F00;"></span>

                  <label>

                    <input name="email_address" id="email_address" value="<?php echo $useremail;?>" type="text" class="txt_box" placeholder="E-mail address" />

                  </label>

                  <label>

                    <input name="send_offers" id="send_offers" type="checkbox" value="1" />

                    Send me travel offers, deals and news by email</label>

                  <div class="clearfix"></div>

                </div>

              </div>

              <div class="col-sm-12">

                <div align="right" class="btn_panel">

                  <input name="" type="button" value="Continue" onclick="process2();" class="booking_btn" />

                </div>

              </div>

        </div>

       </div>

      </div>

      <div class="step" id="step2_after" style="display:none;">

       <div class="booking_wrapp">

        <div class="main_content">

          <div class="wrapp">

            <div class="edit" onclick="jQuery('#step2_after').hide();jQuery('#step2').show('slow');editit('step2');"><span>Edit</span></div>

            <div class="no"><span>2</span></div>

            <div class="row">

              <div class="col-sm-12">

                <div class="gaping">

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">

                    <tr>

                      <td width="7%"><img src="<?php echo get_template_directory_uri(); ?>/images/email_summary.png" class="mail" /></td>

                      <td width="93%"><span id="entered_email"></span></td>

                    </tr>

                  </table>

                </div>

              </div>

            </div>

          </div>

        </div>

       </div>

      </div>

      <div class="step" id="step3" style="width:100%;">

       <div class="booking_wrapp">

        <div class="heading pay">

          <h3><span>3</span>Traveller</h3>

          <h6>Tell us who is travelling</h6>

          <div class="clearfix"></div>

        </div>

       </div>

       <div id="step3_details"  style="display:none;">

          <div class="traveller_details"> <span><strong>* Please provide the full name (including all names) as it is in your passport. please leave a space between each name. example on where to find your name in your passport <a href="#" target="_blank">click here</a> (will open in new tab)</strong></span> </div>


          <div class="form_panel"><span><strong><?php echo $ptypefull[$p];?> <?php echo $k+1;?></strong></span>

            <div class="form_wrapp">

            <input type="hidden" name="ptype[]" value="<?php echo $p;?>" />

              <ul>

                <li>

                  <label><span>Title</span><br />

                    <select name="title[]" class="txt_box">

                      <option value="MR">Mr</option>

                      <option value="MS">Ms</option>

                      <option value="MRS">Mrs</option>

                      <option value="DR">Dr</option>

                      <option value="REV">Rev</option>
                      <!-- Rajib Added -->
                      <option value="Master">Master</option>
                      <option value="Miss">Miss</option>
                      <!-- Rajib Added -->

                    </select>

                  </label>

                </li>

                <li>

                  <label><span>Other Name (Firstname)</span><span id="fnameerror" class="err" style="color:#F00;"></span><br />

                    <input name="first_name" type="text" class="txt_box" placeholder="Other Name" id="fname" />

                  </label>

                </li>

                <li>

                  <label><span>Surname (Lastname)</span><span id="lnameerror" class="err" style="color:#F00;"></span><br />

                    <input name="last_name" type="text" class="txt_box" placeholder="Surname" id="lname"/>

                  </label>

                </li>

                <li>

                  <label><span>Date of Birth</span><span id="doberror" class="err" style="color:#F00;"></span><br />

                    <input name="dob" type="text" class="txt_box date-pickR" placeholder="Date of Birth" data-date-format="yyyy-mm-dd"  data-date-end-date="0d" id="dob"/>

                  </label>

                </li>


                <!-- Rajib Added - 15/10/2015-->
                <li>

                  <label><span>Country Code (ex 009</span>)<span id="countryerror" class="err" style="color:#F00;"></span><br />

                    <input name="country" type="text" class="txt_box" placeholder="Country Code" id="country" />

                  </label>

                </li>
                <!-- Rajib Added - 15/10/2015-->

                <li>

                  <label><span>Mobile No (ex 4777123456</span>)<span id="moberror" class="err" style="color:#F00;"></span><br />

                    <input name="phone" type="text" class="txt_box" placeholder="Mobile No" id="mob" />

                  </label>

                </li>


              </ul>



              <div class="clearfix"></div>

            </div>

          </div>


          

          <div align="right" class="but_section">

          <input name="" type="button" class="booking_btn" onclick="process3();" value="Continue" />

          </div>

       </div>

      </div>

      <div class="step" id="step3_after" style="display:none;">

        <div class="booking_wrapp">

            <div class="main_content">

              <div class="wrapp">

                <div class="edit" onclick="jQuery('#step3_after').hide();jQuery('#step3').show('slow');editit('step3');"><span>Edit</span></div>

                <div class="no"><span>3</span></div>

                <div class="row" id="step3_after_content">

                  <div class="col-sm-12">

                    <div class="gaping traveller">

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>

                        </tr>

                      </table>

                    </div>

                  </div>

                  <div class="col-sm-12">

                    <div class="gaping traveller">

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>

                        </tr>

                      </table>

                    </div>

                  </div>

                  <div class="col-sm-12">

                    <div class="gaping traveller">

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>

                        </tr>

                      </table>

                    </div>

                  </div>

                  <div class="col-sm-12">

                    <div class="gaping traveller">

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>

                        </tr>

                      </table>

                    </div>

                  </div>

                  <div class="col-sm-12">

                    <div class="gaping traveller">

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/men.png" class="mail" /><span>John Doe</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/baby.png" class="mail" /><span>22/09/2015</span></td>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/mobile.png" class="mail" /><span>+0094777123456</span></td>

                        </tr>

                      </table>

                    </div>

                  </div>

                </div>

              </div>

            </div>

        </div>

      </div>

      <div class="step" id="step4" style="width:100%;">

       <div class="booking_wrapp">

        <div class="heading pay">

          <h3><span>4</span>Reserve</h3>

          <h6>Make your reservation</h6>

          <div class="clearfix"></div>

        </div>

        <div id="step4_details" style="display:none;">

           <div class="mail_details" id="step4_mail_details"> <span>* <strong>FREE Reservation:</strong> You can make No-commitment FREE Reservation online without making any payments now.</span> <br />

                <span>* <strong>Come back and Pay Later:</strong> We will send a link to your e-mail to return back to the booking page to continue to make payment.</span><br />

                <span>* <strong>Confirmed Reservation:</strong> Please continue to get a confirmed reservation to your e-mail & mobile, by arranging payment later you can get the e-ticket to travel.</span> <br />

                <span>* <strong>Before you travel:</strong> Please check you have valid passport & VISA to travel to the destination your flying to.</span> 

                <br /><br />

                <label><input name="terms" id="terms" type="checkbox" checked="checked" value="1" /> <span>I understand and agree with the Rules and Restrictions of this fare, the Privacy Policy, the Visa Rules and the <a href="#">Terms and Conditions</a> of Clickmybooking.com</span></label>

                <div class="term-condition">

                	<h1>Terms & Conditions</h1>

                    <p>Welcome! Click my booking is designed for users who exclusively sought for assistance in gathering travel information, making reservation and to carry out transactions with travel suppliers/ agents. Please note that your are bound to terms and conditions of Click my booking website which is maintained by Meera Travels , a company incorporated and registered under the laws of Democratic Socialist Republic of Sri Lanka.<p>

                    <p>You are allowed to print your travel itinerary for travelling purpose, but in case of modification, duplication or usage for transacting with a third party; of the original document is considered as an offence and legal charges will be pressed immediately.</p>

                    <p>Moreover, you are not allowed to interfere with the activity of this website by any means, attempting to do so is also considered as a punishable offence. Upon agreeing to abide by the terms and conditions mentioned above and in other posts in this website, you are granted to access Click my booking and obtain our excellent quality services.

</p>

                </div>

          </div>

          <div class="clearfix"></div>

          <div class="col-sm-12">

            <div align="right" class="btn_panel">

              <input name="" type="button" value="Make Reservation" id="make_reserv_btn" onclick="process4();" class="booking_btn" />

            </div>

          </div>

        </div>

       </div>

      </div>

      <div class="step" id="step4_after" style="display:none;">

        <div class="booking_wrapp">

           <div class="main_content">

              <div class="wrapp">

                <div class="edit" onclick="jQuery('#step4_after').hide();jQuery('#step4').show('slow');editit('step4');"><span>Edit</span></div>

                <div class="no"><span>4</span></div>

                <div class="row">

                  <div class="col-sm-12">

                    <div class="gaping traveller">

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td width="93%"><span class="bk">Your Booking is confirm.</span></td>

                        </tr>

                      </table>

                    </div>

                  </div>

                </div>

              </div>

            </div>

        </div>

      </div>

      </div>

      <div class="step" id="step5" style="width:100%;">

       <div class="booking_wrapp">

        <div class="heading pay">

          <h3><span>5</span>Choose Payment Mode</h3>

          <h6>Convenience fee will be charged based on payment mode</h6>

          <div class="clearfix"></div>

        </div>

       </div>

       

       <div id="step5_details" style="display:none;">

           <div class="row">

                <div class="col-sm-6">

                  <div class="payment_lft">

                    <center>

                      <h4>Ref No (My Trip ID) : <strong id="pnr">XXXXXX</strong></h4>

                    </center>

                    <div class="payment_details">

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td align="left"><span>Base Fare : LKR </span></td>

                          <td align="right"><span> <?php echo $totAmount;?></span></td>

                        </tr>

                        <tr>

                          <td align="left"><span>Taxs & Surcharge : LKR </span></td>

                          <td align="right"><span> <?php echo $Taxes;?></span></td>

                        </tr>

			  <!-- Rajib Added - Webbfaire Discount for All -->
               <tr>

                <td align="left"><span>Webfare Discount : LKR </span></td>

                <td align="right"><span><strong>
                <?php
                    //echo 'yes';
					/*$res = 'SELECT discount FROM airlines WHERE airline = "$thisairlinename" ';
                    $result = $wpdb->get_results($res);
					$discount = $result[0]->discount;
					//echo $tPrice;
					//echo $result[0]->option_value; 
                    if($discount>0)
					{
					$disAmt = (substr($tPrice,3) / 100) * $discount;
						echo $currency.number_format($disAmt);
					}else{
						echo 'Nil';
					}*/
				?>
                 </strong></span></td>

              </tr>
              <!-- Rajib Added - Webbfaire Discount for All -->
              
              <!-- Rajib Added - Webbfaire Discount for Agent -->
				<?php
                ## Raib Added
				/*$myRole = get_user_role();
                if(isset($myRole) && $myRole == 'agent')
                {
			    ?>
               <tr>

                <td align="left"><span>Agent Discount :</span></td>

                <td align="right"><span><strong>
                <?php
                   //echo 'yes';
					$res = 'SELECT * FROM wp_options WHERE option_name = "agent_commission" ';
                    $result = $wpdb->get_results($res);
					$commission = $result[0]->option_value;
					//echo $tPrice;
					//echo $result[0]->option_value; 
                    $webfaireamt = (substr($tPrice,3) / 100) * $commission;
                    echo $currency.number_format($webfaireamt);
                ## Raib Added
				?>
                </strong></span></td>

              </tr>
              <?php
				}*/
			  ?>
              <!-- Rajib Added - Webbfaire Discount for Agent -->

                        <tr>

                          <td align="left"><span><strong>Total</strong></span></td>

                          <td align="right"><span><strong><?php echo $totAmount;?></strong></span></td>

                        </tr>

                      </table>

                    </div>

                    <p><strong>We use the highest secure payment gateway to process your transaction.</strong></p>

                    <div class="payment_mode">

                      <ul>

                        <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/mster-c.jpg" /></a></li>

                        <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/visa-v.jpg" /></a></li>

                        <li><a href="#"><img src="<?php echo get_template_directory_uri(); ?>/images/amex-c.jpg" /></a></li>

                      </ul>

                    </div>

                  </div>

                </div>

                <!--left-->

                

                <div class="col-sm-6">

                  <div class="payment_options">

                    <div class="payment_options_wrapp">

                    <!--<form name="frmpay" action="<?php echo site_url(); ?>/pay/test.php" method="post">-->
                    
                    <form name="frmpay" action="<?php echo site_url(); ?>/payholiday/vpc_php_serverhost_me.php" method="post">
                    
                    <!--<form name="frmpay" action="<?php echo site_url(); ?>/pay/vpc_php_serverhost_do.php" method="post">-->
                    
                    <input type="hidden" name="Title" value="PHP VPC 3-Party">

                    <input type="hidden" name="vpc_Version" value="1" maxlength="8">

                    <input type="hidden" name="vpc_Command" value="pay" maxlength="16">

                    <input type="hidden" name="vpc_AccessCode" value="3F543073" maxlength="8">
                    
                    <input type="hidden" name="vpc_MerchTxnRef" id="vpc_MerchTxnRef" value="" maxlength="40">

                    <input type="hidden" name="vpc_Merchant" value="TEST037001642002" maxlength="16">

                    <input type="hidden" name="vpc_OrderInfo" value="Hotel Reservation" size="20" maxlength="34">
                    
                    <!-- Rajib -test-->
                    <input type="hidden" name="vpc_Amount" value="<?php echo $totAmount*100;?>" size="20" maxlength="10">

                    <!--<input type="hidden" name="vpc_Amount" value="1010" size="20" maxlength="10">-->
                    <!--Rajib test-->

                    <input type="hidden" name="vpc_Locale" value="en" size="20" maxlength="5">

                    <input type="hidden" name="vpc_ReturnURL" size="63" value="<?php echo site_url(); ?>/payholiday/vpc_php_serverhost_dr.php">

                    <input type="hidden" name="vpc_TxSourceSubType" value="SINGLE">

                    <input type="hidden" name="virtualPaymentClientURL" size="63" value="https://migs.mastercard.com.au/vpcpay">

                    

                    

                      <?php /*?><label>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/visa_logo_ipg.png" /></td>

                          <td><span>VISA cards upto 50% off on selected and 5% off on other Destinations.</span></td>

                          <td align="right"><input name="radio" type="radio" value="" /></td>

                        </tr>

                      </table>

                      </label>

                      <label>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/master_logo_ipg.png" /></td>

                          <td><span>VISA cards upto 50% off on selected and 5% off on other Destinations.</span></td>

                          <td align="right"><input name="radio" type="radio" value="" /></td>

                        </tr>

                      </table>

                      </label>

                      <label>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/amex.png" /></td>

                          <td><span>VISA cards upto 50% off on selected and 5% off on other Destinations.</span></td>

                          <td align="right"><input name="radio" type="radio" value="" /></td>

                        </tr>

                      </table>

                      </label>

                      <label>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/sampath.png" /></td>

                          <td><span>VISA cards upto 50% off on selected and 5% off on other Destinations.</span></td>

                          <td align="right"><input name="radio" type="radio" value="" /></td>

                        </tr>

                      </table>

                      </label>

                      <label>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/commercial_bank.png" /></td>

                          <td><span>VISA cards upto 50% off on selected and 5% off on other Destinations.</span></td>

                          <td align="right"><input name="radio" type="radio" value="" /></td>

                        </tr>

                      </table>

                      </label>

                      <label>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/foreign.png" /></td>

                          <td><span>VISA cards upto 50% off on selected and 5% off on other Destinations.</span></td>

                          <td align="right"><input name="radio" type="radio" value="" /></td>

                        </tr>

                      </table>

                      </label>

                      <label>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/sampath.png" /></td>

                          <td><span>VISA cards upto 50% off on selected and 5% off on other Destinations.</span></td>

                          <td align="right"><input name="radio" type="radio" value="" /></td>

                        </tr>

                      </table>

                      </label>

                      <label>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/cash.png" /></td>

                          <td><span>VISA cards upto 50% off on selected and 5% off on other Destinations.</span></td>

                          <td align="right"><input name="radio" type="radio" value="" /></td>

                        </tr>

                      </table>

                      </label>

                      <label>

                      <table width="100%" border="0" cellspacing="0" cellpadding="0">

                        <tr>

                          <td><img src="<?php echo get_template_directory_uri(); ?>/images/visa_logo_ipg.png" /></td>

                          <td><span>VISA cards upto 50% off on selected and 5% off on other Destinations.</span></td>

                          <td align="right"><input name="radio" type="radio" value="" /></td>

                        </tr>

                      </table>

                      </label><?php */?>

                      

                         <div class="btn_panel" align="right">

                            <input class="booking_btn" type="submit" value="Continue" name="">

                        </div>

                       </form>

                    </div>

                  </div>

                </div>

                <!--right--> 

            </div>

       </div>

       

      </div>

      <div>&nbsp;</div>

<!-- end steps  --> 

      

      </div>

    </div>

  </div>

</div> 

</div>  	

<?php


?>

<script type="text/javascript">

function editit(s){ 

	jQuery('.step').css({"z-index": "0"});

	jQuery('#'+s).css({"position": "relative","float":"left","width":"100%","z-index": "999","background": "#fff"});

	jQuery('.pop-edit').show();

}

function unblockall(){ 

	jQuery('.step').css({"position": "","z-index": "0","background": ""});

	jQuery('.pop-edit').hide();

	jQuery('#make_reserv_btn').prop( "disabled", false );

}

function isValidEmailAddress(emailAddress) {

    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

    return pattern.test(emailAddress);

};

// Rajib Added
function MobCountryValidate() {
        var country = document.getElementById("country").value;
        var pattern = /^\d{3}$/;
        if (pattern.test(country)) 
		return true;
        else
        return false;
    }
function MobValidate() {
        var mobile = document.getElementById("mob").value;
        var pattern = /^\d{10}$/;
        if (pattern.test(mobile)) 
		return true;
        else
        return false;
    }

function validateDateFormat(dateVal){
 
      var dateVal = dateVal;
 
      if (dateVal == null) 
          return false;
 
      var validatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/;
 
          dateValues = dateVal.match(validatePattern);
 
          if (dateValues == null) 
              return false;
 
      var dtYear = dateValues[1];        
          dtMonth = dateValues[3];
          dtDay=  dateValues[5];
 
       if (dtMonth < 1 || dtMonth > 12) 
          return false;
       else if (dtDay < 1 || dtDay> 31) 
         return false;
       else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
         return false;
       else if (dtMonth == 2){ 
         var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
         if (dtDay> 29 || (dtDay ==29 && !isleap)) 
                return false;
      }
 
     return true;
}
// Rajib Added	
	
function process1(){ 


var booking_reason='';

jQuery.ajax({

	type: 'post',

	data:{booking_reason:booking_reason},

	url: '<?php echo home_url("/process1.php");?>',

	success: function(h) {

		jQuery('#step1').hide();jQuery('#step1_after').show();jQuery('#step2_dtls').show('slow');unblockall();

	}

});

}

function process2(){ 

jQuery('#emailerror').text('');	

var eml=jQuery('#email_address').val();

var send_offers=jQuery('#send_offers').is(":checked");

if(send_offers==true)send_offers=1;

else send_offers=0;

if(isValidEmailAddress(eml)==false || eml == ''){

jQuery('#emailerror').text('Error, enter a valid email address! ( Email is must )!');	
jQuery('#email_address').focus();
return;

}

jQuery.ajax({

	type: 'post',

	data:{eml:eml,send_offers:send_offers},

	url: '<?php echo home_url("/process2.php");?>',

	success: function(h) {

	   jQuery('#entered_email').text(h);

	   jQuery('#step2').hide();jQuery('#step2_after').show();jQuery('#step3_details').show('slow');unblockall();	

	}

});

}



function process3(){ 

// Rajib added
// Fname Chk
jQuery('#fnameerror').text('');	

var fname=jQuery('#fname').val();

if(fname == '')
{
	jQuery('#fnameerror').text('Enter First Name!');	
	jQuery('#fname').focus();
	return;
}
// Lname Chk
jQuery('#lnameerror').text('');	

var lname=jQuery('#lname').val();

if(lname == '')
{
	jQuery('#lnameerror').text('Enter Last Name!');	
	jQuery('#lname').focus();
	return;
}
// DOB Chk
jQuery('#doberror').text('');	

var dob=jQuery('#dob').val();

if(dob == '' || validateDateFormat(dob) == false)
{
	jQuery('#doberror').text('Enter Date Of Birth!');	
	jQuery('#dob').focus();
	return;
}
// Mobile Country Code Chk
jQuery('#countryerror').text('');	

var country=jQuery('#country').val();

if(country == '' || MobCountryValidate(country) == false)
{
	jQuery('#countryerror').text('Enter Valid Country Code!');	
	jQuery('#country').focus();
	return;
}
// Mobile Chk
jQuery('#moberror').text('');	

var mob=jQuery('#mob').val();

if(mob == '' || MobValidate(mob) == false)
{
	jQuery('#moberror').text('Enter Valid Mobile No!');	
	jQuery('#mob').focus();
	return;
}
// Rajib added

jQuery.ajax({

	type: 'post', 

	data: jQuery('select[name=\'title[]\'], input[name=\'ptype[]\'], input[name=\'first_name[]\'], input[name=\'last_name[]\'], input[name=\'dob[]\'], input[name=\'country[]\'] , input[name=\'phone[]\'],  input[name=\'passport_no[]\'], input[name=\'passport_exp_date[]\'], input[name=\'passport_country[]\'],  select[name=\'flyer_club[]\'],  input[name=\'flyer_number[]\']'),

	url: '<?php echo home_url("/process3.php");?>',

	success: function(h) { 

	   // Rajib
	   //alert(h);
	   jQuery('#step3').hide();jQuery('#step3_after').show();jQuery('#step4_details').show('slow');unblockall();

	   processstep3_after(h);

	}

});

}



function processstep3_after(h){ 

	jQuery('#step3_after_content').html(h);

}

function process4(){

if(jQuery('#terms').is(":checked")==false){

	alert('Please visit the terms and condition page, you require to agree with those.');

	return;

}

// Rajib Getting data
var roomid = <?php echo $roomid;?>;
var tourids = jQuery('#tourids').val();
var email = jQuery('#email_address').val();
var city = '<?php echo $city[0].','.$city[1];?>';
var adult = <?php echo $adult;?>;
var child = <?php echo $child;?>;
var infant = <?php echo $infant;?>;
var checkin = '<?php echo $checkin;?>';
var checkout = '<?php echo $checkout;?>';
var name = jQuery('#fname').val()+' '+jQuery('#lname').val();
var amount = '<?php echo $totAmount;?>';
var hotel = '<?php echo $tour;?>';
var dob =  jQuery('#dob').val();
var phone = jQuery('#mob').val();

jQuery('#step4_mail_details').html('<img src="<?php echo get_template_directory_uri();?>/images/ajax-loader.gif"/> Please Wait while we process your booking with the Hotel & Holidays Packages, this may take a minute.');

jQuery('#make_reserv_btn').val('Please Wait');

jQuery('#make_reserv_btn').prop( "disabled", true );

 jQuery.ajax({  

	type: 'post', 

	//data: {p:'<?php echo base64_encode(serialize($_SESSION['PARSEDDATA']));?>',s:'<?php echo $searchdataoriginal;?>',r:'<?php echo base64_encode(serialize(array($userid)));?>'},
	
	// Rajib Added - 02122015
	data: 'email='+email+'&name='+name+'&amount='+amount+'&hotel='+hotel+'&city='+city+'&checkin='+checkin+'&checkout='+checkout+'&adult='+adult+'&child='+child+'&infant='+infant+'&phone='+phone+'&dob='+dob+'&roomid='+roomid+'&tourids='+tourids,
	/*data: 'email='+email+'&name='+name+'&amount='+amount+'&hotel='+hotel+'&city='+city+'&checkin='+checkin+'&checkout='+checkout+'&person='+person,*/
	url: '<?php echo home_url("/holiday_reservation_create.php");?>',

	success: function(h) { 

	   //alert(h);
	   jQuery('#reserved').html(h);

	   <?php /*?>getPNR();<?php */?>

	   getTripId();

	   jQuery('#step5_details').show('slow');unblockall();	

	}

});

 //jQuery('#step4').hide();jQuery('#step4_after').show();jQuery('#step5_details').show('slow');unblockall();	

}

function getPNR(){

	jQuery.ajax({  

	type: 'post', 

	url: '<?php echo home_url("/getpnr.php");?>',

	success: function(h) { 

	   jQuery('#pnr').text(h);

	}

});

}

function getTripId(){

	jQuery.ajax({  

	type: 'post', 

	url: '<?php echo home_url("/gettripid.php");?>',

	success: function(h) { 

	   jQuery('#pnr').text(h);

	   jQuery('#vpc_MerchTxnRef').val(h);

	}

  });

}

jQuery(document).ready(function(){

 jQuery('.date-pick').datepicker({ autoclose: true,todayHighlight: true});

});

</script>

<?php

function processFairInfo($FareInfo){

$fairinfo=array();

if(isset($FareInfo['@attributes'])){

	$data4=array(

		'Key'=> $FareInfo['@attributes']['Key'],

		'FareBasis'=> $FareInfo['@attributes']['FareBasis'],

		'PassengerTypeCode'=> $FareInfo['@attributes']['PassengerTypeCode'],

		'Origin'=> $FareInfo['@attributes']['Origin'],

		'Destination'=> $FareInfo['@attributes']['Destination'],

		'EffectiveDate'=> $FareInfo['@attributes']['EffectiveDate'],

		'DepartureDate'=> $FareInfo['@attributes']['DepartureDate'],

		'Amount'=> $FareInfo['@attributes']['Amount'],

		'NotValidBefore'=> $FareInfo['@attributes']['NotValidBefore'],

		'NotValidAfter'=> $FareInfo['@attributes']['NotValidAfter']

	);

	if(isset($FareInfo['airFareRuleKey'])){

		$data4['FareRuleKey']=$FareInfo['airFareRuleKey'];

	}

	$fairinfo[]=$data4;

}else if(isset($FareInfo[0]['@attributes'])){

	foreach($FareInfo as $FI){

		$data4=array(

			'Key'=> $FI['@attributes']['Key'],

			'FareBasis'=> $FI['@attributes']['FareBasis'],

			'PassengerTypeCode'=> $FI['@attributes']['PassengerTypeCode'],

			'Origin'=> $FI['@attributes']['Origin'],

			'Destination'=> $FI['@attributes']['Destination'],

			'EffectiveDate'=> $FI['@attributes']['EffectiveDate'],

			'DepartureDate'=> $FI['@attributes']['DepartureDate'],

			'Amount'=> $FI['@attributes']['Amount'],

			'NotValidBefore'=> $FI['@attributes']['NotValidBefore'],

			'NotValidAfter'=> $FI['@attributes']['NotValidAfter']

		);

		if(isset($FI['airFareRuleKey'])){

			$data4['FareRuleKey']=$FI['airFareRuleKey'];

		}

		$fairinfo[]=$data4;  

	}

}

return $fairinfo;	

}

function processBookingInfo($BookingInfo){

$bookinginfo=array();

if(isset($BookingInfo['@attributes'])){

	$data5=array(

		'BookingCode'=> $BookingInfo['@attributes']['BookingCode'],

		'CabinClass'=> $BookingInfo['@attributes']['CabinClass'],

		'FareInfoRef'=> $BookingInfo['@attributes']['FareInfoRef'],

		'SegmentRef'=> $BookingInfo['@attributes']['SegmentRef']

	);

	$bookinginfo[]=$data5;

}else if(isset($BookingInfo[0]['@attributes'])){

	 foreach($BookingInfo as $BI){

		$data5=array(

			'BookingCode'=> $BI['@attributes']['BookingCode'],

			'CabinClass'=> $BI['@attributes']['CabinClass'],

			'FareInfoRef'=> $BI['@attributes']['FareInfoRef'],

			'SegmentRef'=> $BI['@attributes']['SegmentRef']

		);

		$bookinginfo[]=$data5;

	 }

}

return $bookinginfo;	

}

function processTaxInfo($TaxInfo){

$taxinfo=array();

if(isset($TaxInfo['@attributes'])){

	$data6=array(

		'Category'=> $TaxInfo['@attributes']['Category'],

		'Amount'=> $TaxInfo['@attributes']['Amount'],

		'Key'=> $TaxInfo['@attributes']['Key']

	);

	$taxinfo[]=$data6;

}else if(isset($TaxInfo[0]['@attributes'])){

	 foreach($TaxInfo as $TI){

		$data6=array(

			'Category'=> $TI['@attributes']['Category'],

			'Amount'=> $TI['@attributes']['Amount'],

			'Key'=> $TI['@attributes']['Key']

		);

		$taxinfo[]=$data6;

	 }

}

return $taxinfo;	

}

function processPassengerType($PassengerType){

$ptypedata=array();

 if(isset($PassengerType['@attributes']['Code'])){

 $psgtypedata=array(

	'Code' => $PassengerType['@attributes']['Code']

 );

 if(isset($PassengerType['@attributes']['Age'])){

	$psgtypedata['Age'] =$PassengerType['@attributes']['Age'];

 }

 $ptypedata[]=$psgtypedata;

 }else if(isset($PassengerType[0]['@attributes'])){

	foreach($PassengerType as $psgtype){

		$psgtypedata=array(

			'Code' => $psgtype['@attributes']['Code']

		 );

		  if(isset($PassengerType['@attributes']['Age'])){

		     $psgtypedata['Age'] =$psgtype['@attributes']['Age'];

		  }

		 $ptypedata[]=$psgtypedata;

	}

 }	

return $ptypedata;

}

function processBaggageRestriction($BaggageAllowances){

$baggageRestriction='';

$airBaggageAllowanceInfo=$BaggageAllowances['airBaggageAllowanceInfo'];

$airBagDetails=array();

if(isset($airBaggageAllowanceInfo['airBagDetails'])){

$airBagDetails=$airBaggageAllowanceInfo['airBagDetails'];

}else if(isset($airBaggageAllowanceInfo[0]['airBagDetails'])){

$airBagDetails=$airBaggageAllowanceInfo[0]['airBagDetails'];	

}



$airBaggageRestriction=array();

if(isset($airBagDetails['airBaggageRestriction'])){

$airBaggageRestriction=$airBagDetails['airBaggageRestriction'];

}else if(isset($airBagDetails[0]['airBaggageRestriction'])){

$airBaggageRestriction=$airBagDetails[0]['airBaggageRestriction'];	

}



if(isset($airBaggageRestriction['airTextInfo']['airText']))

$baggageRestriction=$airBaggageRestriction['airTextInfo']['airText'];



return $baggageRestriction;	

}

function getSegmentKey($ref,$airsegments){

	foreach ($airsegments as $k => $v) {

       if ($v['Key'] === $ref) {

           return $k;

       }

   }

   return null;

}

function getFairInfoKey($ref,$fairinfolist){

	foreach ($fairinfolist as $k => $v) {

       if ($v['Key'] === $ref) {

           return $k;

       }

   }

   return null;

}

function getTimeDiff($d1,$d2){

$date1 = new DateTime($d1);

$date2 = new DateTime($d2);	

$diff = $date2->diff($date1);

$h = $diff->h;

$h = $h + ($diff->days*24);	

$m=$diff->i;



$ret='';

if($h)$ret.=$h;

if($h==1)$ret.= "hr ";

else if($h>1)$ret.= "hrs ";

if($m)$ret.=$m;

if($m==1)$ret.= "min";

else if($m>1)$ret.= "mins";

return $ret;

}

get_footer();
?>
<!-- Rajib Added - 30/10/15 -->
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script>
  $(function() {
    $( "#dob" ).datepicker({
      changeMonth: true,
      changeYear: true,
	  dateFormat: 'yy-mm-dd',
	  yearRange: "-100:+0"
    });
  });
  </script>