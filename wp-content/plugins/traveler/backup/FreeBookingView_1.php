
<?php 
 include_once PLUG_DIR.'/models/PricingFly.php';
 include_once (PLUG_DIR . 'utility/FlightUtility.php');
 
function freeBookingInit($response)
{
    error_log(print_r($_SESSION['searchData'],true));
    $flightUtility=new FlightUtility();
$ptypefull=array('ADT'=>'Adult','CHD'=>'Child','INF'=>'Infant');
?> 


    <div class="sky-bg">
        <div class="pop-edit"></div>
            <div class="container">
                <div class="main_wrapp">
                    <div class="booking_panel">
                <!-- -->
                         <div id="reserved" style="width:100%;">
                                <div class="step" id="step1" style="width:100%;">
                                    <div class="main_content br">
                                              
                                                <!--Flight Details Start-->
                                                        <div class="booking_wrapp">
                                                             <div class="heading pay">
                                                                
                                                             Review your flight details
                                                              
                                                            </div>
                                                        </div>
                                                 <!--Flight Details End-->
 
                                                <!--Flight Details Start -->
                                                <div class="row">
                                                    <div class="col-sm-12"> <span class="hd">
                                                            <strong>Colombo to Bangkok</strong></span>
                                                        <div class="first_before">
                                                           <!--[0] booking Info(class) [1] passenger details flight details Start   --->
                                                            <?php
                                                            $count=0;
                                                            foreach($response->airDetails as $index)
                                                                {
                                                                $air=$index['@attributes'];
                                                                $classDetails=$air[0]['@attributes'];  
                                                  // error_log( print_r($index,true) ); ?>
                                                            <div class="first_before_row">
                                                                <ul>
                                                                    <li><img src="../airimages/<?php echo $air['Carrier']?>.GIF" class="icn">
                                                                        <span> <br>
                                                                            <?php echo $air['Carrier']?> <?php echo $air['FlightNumber']?> <br>
                                                                            <strong>Aircraft <?php echo $air['Equipment']?><br>

                                                                    <li>
                                                                        <span>
                                                                            <strong class="small">
                                                                                <?php echo date("h:i a", strtotime($air['DepartureTime'])) ?>
                                                                            </strong></br>
                                                                                <?php echo date("D, d M Y", strtotime($air['DepartureTime']))?><br>
                                                                                <strong class="small">
                                                                                    <?php echo $flightUtility->getCountryName($air['Origin']).' '.$air['Origin']?>
                                                                              </strong>
                                                                        </span>
                                                                    </li>
                                                                    <li>
                                                                    <center >
                                                                        </br>
                                                                        <img  src="http://www.clickmybooking.com/wp-content/themes/traveler/images/booking_dets_oneway_right.png"><br>
                                                                    </center>
                                                                    </li>
                                                                    <li>
                                                                        <span>
                                                                            <strong class="small">
                 <?php echo date("h:i a", strtotime($air['ArrivalTime'])) ?></strong></br>
                 <?php echo date("D, d M Y", strtotime($air['ArrivalTime']))?> <br>
                                                                            <strong class="small">
                 <?php echo $flightUtility->getCountryName($air['Destination']).' '.$air['Destination']?></strong>
                                                                        </span>
                                                                    </li>
                                                                    <li>
                                                                        <span>
                                                                        <?php echo $classDetails['CabinClass'].' ('.$classDetails['BookingCode'].')'?>
                                                                        </span>
                                                                    </li>
                                                                </ul>

                                                                          <div class="clearfix"></div>

                                                            </div>

                                                            <?php if(count($response->airDetails)>1 && $count < count($response->airDetails)-1)
                                                                { ?>
                                                            <div class="intro"><span>Change of Planes | Connection Time :
                                                                    <?php echo $flightUtility->getTimeDiff( $air['ArrivalTime'],$response->airDetails[$count+1]['@attributes']['DepartureTime']) ?></li></span></div>
                                                                <?php } ?>

                                                                <?php $count++;
                                                                }
                                                            ?>

                                                            <!--flight details End   --->

                                                        </div>
                                                    </div>
                                                </div>
                                                <!--Flight Details End -->
                               
                                                <!--Start fare rules and baggage Details -->
                                                <div class="button_panel">
                                                    <div class="row">
                                                        <div class="col-sm-3">
                                                            <form name="frmFL" id="frmFL" method="post" action="farerule.php" target="_blank">
                                                                <input type="hidden" name="k" value="6UUVoSldxwhqddMCo0qZLcbKj3F8T9EyxsqPcXxP0TIjSPOlaHfQe5cuasWd6i8Dly5qxZ3qLwOXLmrFneovA5cuasWd6i8Dly5qxZ3qLwOXLmrFneovA/+vsMmYmMn+M3ExqSoG0517Efhtb54I2WXWBQb7xTe/VCulqMwbpNYRHrzWZukgtu/jCjAyI4vfpN1NjCeuj2rq/20RpEmAXlby9IE1rxlSp7GoYpQef1gXV1oPgUILB66cW4+txcdRFVHsNy1BaPCAdcY0m0a4yk4nKb2G5mOF8ibJ4hag+9BvMEMFLAmi0L+F729rtUMfv4Xvb2u1Qx+/he9va7VDH7+F729rtUMfv4Xvb2u1Qx80GqQ2wgDK/01BlpTwyo+r9GjARZYw7j/TROTkGAG0MWxL8OD6CMOB0hMuIUwnshJx4j4s3qKGsuVuG0bsK6zw">
                                                                <input type="hidden" name="ref" value="0l5B9rBAAA/BdqF15EAAAA==">
                                                                <ul>
                                                                    <li><img src="http://www.clickmybooking.com/wp-content/themes/traveler/images/booking_op.png"></li>
                                                                    <li><span>Fare Rules <a onclick="jQuery('#frmFL').submit();">click here</a></span></li>
                                                                </ul>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <ul>
                                                                <li><img src="http://www.clickmybooking.com/wp-content/themes/traveler/images/booking_op.png"></li>
                                                                <li><span>Baggage Allowances :<br>
                                                                        CHGS MAY APPLY IF BAGS EXCEED TTL WT ALLOWANCE</span></li>
                                                            </ul>
                                                        </div>
                                                        <div class="col-sm-3">
                                                            <ul>
                                                                <li><img src="http://www.clickmybooking.com/wp-content/themes/traveler/images/booking_op.png"></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--End fare rules and baggage Details -->

                                                
                                                <!--Fight Fare Details Start -->
                                                <div class="col-sm-6">
                                                    <div class="details">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tbody>
                                                                <tr class="nonembacytotal" style="border: none;">
                                                                    <td align="left" style="border: none;"><span><strong>Base Fare </strong></span></td>
                                                                    <td align="right" style="border: none;"><span><strong>  </strong></span></td>
                                                                </tr>

                                                                <?php foreach($response->priceDetails->fareInfo as $index)
                                                                { ?>
                                                                <tr class="nonembacytotal">

                                                                    <td align="left"><span><?php echo $index->pasType.'  ('.$index->count.'x'.$index->headPrice.')'?>   </span></td>
                                                                    <td align="right"><span><?php echo $index->count*$index->headPrice?></span></td>
                                                                </tr>			
                                                              <?php }?>

                                                                <tr class="nonembacytotal">
                                                                   <td align="left" style="border: none;"><span><strong>Tax </strong></span></td>
                                                                    <td align="right" style="border: none;"><span><strong>  </strong></span></td>
                                                                </tr>
                                                                       <?php foreach($response->priceDetails->fareInfo as $index)
                                                                { ?>
                                                                <tr class="nonembacytotal">

                                                                    <td align="left"><span><?php echo $index->pasType.'   ('.$index->count.'x'.$index->headTax.')'?> </span></td>
                                                                    <td align="right"><span><?php echo $index->count*$index->headTax?></span></td>
                                                                </tr>			
                                                              <?php }?>

                                                                <tr class="nonembacytotal">
                                                                    <td align="left"><span>Webfare Discount </span></td>
                                                                    <td align="right"><span><strong>
                                                                                0                 </strong></span></td>
                                                                </tr>

                                                                <tr class="embacytotal">
                                                                    <td align="left"><span><strong class="larg">Total price </strong></span></td>
                                                                    <td align="right"><span><strong><strong><?php echo $response->priceDetails->baseFare['TotalPrice'] ?></strong></strong></span></td>
                                                                </tr>


                                                            </tbody></table>
                                                    </div>
                                                </div>
                                                <!--Fight Fare Details End -->
                                                
                                                
                                             

                                     </div>
                                 </div>
                         </div>
                

    <!-- Step 2 after  End -->   
   
    
     <!-- Step 3 Start  -->   
    
     <div class="step" id="step3" style="width:100%;">

             <div id="step3_details"  style="display:none;">
                 <div class="traveller_details"> <span><strong>* Please provide the full name (including all names) as it is in your passport. please leave a space between each name. example on where to find your name in your passport <a href="#" target="_blank">click here</a> (will open in new tab)</strong></span> </div>
                 <?php error_log(print_r($_SESSION['searchData']['passengers'],true)); ?>
                 <?php foreach ($_SESSION['searchData']['passengers'] as $index => $value) { ?>
                     <div class="form_panel"><span><strong>
                         <?php echo $ptypefull[$value]; ?> <?php echo $index + 1; ?></strong></span>
                         <div class="form_wrapp">
                             <input type="hidden" name="ptype[]" 
                                    value="<?php echo $value; ?>" />
                             <ul>
                                 <li>
                                     <label><span>Title</span><br />
                                         <select name="title[]" class="txt_box">
                                             <?php
                                             if ($value == 'ADT') {
                                                 ?>
                                                 <option value="MR">Mr</option>

                                                 <option value="MS">Ms</option>

                                                 <option value="MRS">Mrs</option>

                                                 <option value="DR">Dr</option>

                                                 <option value="REV">Rev</option>
                                                 <?php
                                             } else {
                                                 ?>
                                                 <!-- XXXX Added -->
                                                 <option value="MSTR">Master</option>
                                                 <option value="MISS">Miss</option>
                                                 <!-- XXXX Added -->
                                                 <?php
                                             }
                                             ?>
                                         </select>
                                     </label>
                                 </li>
                                 <li>

                                     <label><span>Other Name (Firstname)</span>
                                         <span id="fnameerror<?php echo $index; ?>" class="err" style="color:#F00;"></span><br />

                                         <input name="first_name[]" type="text" class="txt_box " placeholder="Other Name" id="fname"/>

                                     </label>

                                 </li>

                                 <li>

                                     <label><span>Surname (Lastname)</span><span id="lnameerror<?php echo $index; ?>" class="err" style="color:#F00;"></span><br />

                                         <input name="last_name[]" type="text" class="txt_box" placeholder="Surname" id="lname"/>

                                     </label>

                                 </li>
                                 <li>
                                     <label><span>Gender</span><span id="sexerror" class="err" style="color:#F00;"></span><br />

                                         <select name="sex[]" class="txt_box" id="sex">

                                             <option value="M">Male</option>
                                             <option value="F">Female</option>

                                         </select>

                                     </label>
                                 </li>
                                 <li>

                                  <label><span>Date of Birth</span><span id="doberror0" 
                                                                         class="err" style="color:#F00;"></span><br>
                     <input name="dob[]" type="text" class="txt_box date-pickR hasDatepicker" placeholder="Date of Birth"
                            data-date-format="yyyy-mm-dd" data-date-end-date="0d" id="dobid0">
                  </label>

                                 </li>
                                 <?php if ($index == 0) { ?>
                                     <li>

                                         <label><span>Country Code (ex 0094)</span> <span id="countryerror" class="err" style="color:#F00;"></span><br />

                                             <input name="country[]" type="text" class="txt_box" placeholder="Country Code" id="country" />

                                         </label>

                                     </li>
                                     <li>

                                         <label><span>Mobile No (ex 774705553)</span> 
                                             <span id="moberror" class="err" style="color:#F00;"></span><br />
                                             <input name="phone[]" type="text" class="txt_box" placeholder="Mobile No" id="mob" maxlength="14" />

                                         </label>

                                     </li>
                                 <?php } else { ?>
                                     <li><label> &nbsp; </label></li>
                                 <?php } ?>
                             </ul>
                             
                             <div class="clearfix"></div>
                             
                   
                             
                 <!-- additional Info start-->            
                 <a role="button" data-toggle="collapse" href="#collapseExample<?php echo $index;?>" aria-expanded="false" 
                    aria-controls="collapseExample<?php echo $index;?>">Add optional details</a>
                 <div class="collapse in" id="collapseExample<?php echo $index;?>">
                <div class="well">
                  <ul>
                    <li>
                      <label><span>Passport Number</span> <span id="passportnoerror0" class="err" style="color:#F00;"></span><br>
                        <input name="passport_no[]" type="text" class="txt_box" placeholder="Passport No">
                      </label>
                    </li>
                    <li>
                      <label><span>Expiration date</span> <span id="passportexpdateerror0" class="err" style="color:#F00;"></span><br>
                         <input name="passport_exp_date[]" type="text" class="txt_box date-pickR hasDatepicker" data-date-format="yyyy-mm-dd" data-date-start-date="0d" placeholder="Expiration date" id="ppexpire0">
                      </label>
                    </li>
                    <li>
                      <label><span>Issuing country</span> <span id="passportcountryerror0" class="err" style="color:#F00;"></span><br>
                        <input name="passport_country[]" type="text" class="txt_box" placeholder="Issuing country">
                      </label>
                    </li>
					
					<li>
                          <label><span>Birth Country</span> <span id="birthcountryerror0" class="err" style="color:#F00;"></span><br>
                             <input name="birth_country[]" type="text" class="txt_box" placeholder="US" id="birth_country">
                          </label>
                        </li>
				    <li>
					   <label><span> </span><br>
                             <a target="_blank" href="http://www.clickmybooking.com/wp-content/themes/traveler/images/Passport-01.jpg">Sample passport</a>
						   </label>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
              </div>

                <!-- additional Info End-->    
              
              
                         </div>
                     </div>
                 <?php } ?>

               

                 <div align="right" class="but_section">
                     <input name="" type="button" class="booking_btn" onclick="processstep3();" value="Continue" />
                 </div>
                 
             </div>
    </div>
    
    
     <!-- Step 3 End  -->   
     
     <!-- step 3 After -->
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
     
      <!-- step 3 End -->
     
     
     <!-- Step 4 Start-->
    
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
                    <p>Moreover, you are not allowed to interfere with the activity of this website by any means, attempting to do so is also considered as a punishable offence. Upon agreeing to abide by the terms and conditions mentioned above and in other posts in this website, you are granted to access Click my booking and obtain our excellent quality services.</p>
                    
                    <h1>Privacy Policy</h1>
<p>Click my booking will not hold any responsibility for any details you have sent to this website, since this website could be read and intercepted by frauds unless it is properly encrypted. We also advise you to encrypt confidential information such as credit card numbers, bank details etc.</p>
 
<p>All copy rights of the content of this website are reserved by Click my booking. Thus, copying or reproducing the content of this website is a punishable offence, except when it is necessary in instances such as to ensure payment, submit travel itineraries and for other avail paid services.</p>
 
                    <h1>Safety Measures</h1>
                    
                    <p>SSL, is the technology which Click my booking uses to ensure the safety of your confidentiality. Secure Sockets Layer or SSL technology encrypts all your information with 128 bit encryption before it is been sent to our servers and this information can be read ONLY by the relevant recipient. Click my booking holds your basic personal information like Name, Contact numbers, Email IDs etc. while the finance related information are held by the relevant banks.</p>
                                                              
                    <h1>Return and cancellation Policy</h1>
                    
                    <p>All the requests of online payments should be directed to the email: meera1958@yahoo.co.in Refunding will be proceeded based on the conditions provided by the Cancellation policy. The amount will be Refunded at the same time as the time of online purchase. Please note that the time duration taken to transfer funds differ according to the policy of the particular bank. This might take 15-30 business days or might even postpone until the next billing cycle.</p>
                    <p>The amount refunded should be converted to the local currency by the bank associated with your credit cards. Finally you will be notified about your statement, with an email bearing 'Click my booking' as the line of the subject.</p>
                    
                </div>
          </div>
          <div class="clearfix"></div>
          <div class="col-sm-12">
            <div align="right" class="btn_panel">
              <input name="" type="button" value="Make Reservation" id="make_reserv_btn" onclick="processstep4();" class="booking_btn" />
            </div>
          </div>
        </div>
       </div>
      </div>
     <!--Step 4 End-->
    
   
   
   

                    </div>
                </div>
            </div>
        </div>
    </div>

        <?php }?>                                      

