
<?php 
 include_once PLUG_DIR.'/models/PricingFly.php';
 include_once (PLUG_DIR . 'utility/FlightUtility.php');
 
function freeBookingInit($response)
{
  
$flightUtility=new FlightUtility();
$ptypefull=array('ADT'=>'Adult','CNN'=>'Child','INF'=>'Infant');
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
<!--                                                              <?php echo plugin_dir_url().'/service/RoundTripResponse.php'?>  
                                                             Review your flight details-->
                                                              
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
                                                            foreach($response->airDetails as $key=>$index)
                                                                {
                                                                $air=$index['@attributes'];
                                                                $classDetails=$air[0][$key]['@attributes'];  
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

                                                <div class="col-sm-6 pull-left">
                                                     <div class="step" id="step2">
       <div class="booking_wrapp">
        <div class="heading pay">
          <h3><span>2</span>Email Address</h3>
           <h6>Where do you want the ticket</h6>
          <div class="clearfix"></div>
        </div>
        <div id="step2_dtls">
            <div class="mail_details"> <span>* <strong>FREE Reservation:</strong> You can make No-commitment FREE Reservation online without making any payments now.</span> <br />
                <span>* <strong>Come back and Pay Later:</strong> We will send a link to your e-mail to return back to the booking page to continue to make payment.</span><br />
                <span>* <strong>Confirmed Reservation:</strong> Please continue to get a confirmed reservation to your e-mail & mobile, by arranging payment later you can get the e-ticket to travel.</span> <br />
                <span>* <strong>Before you travel:</strong> Please check you have valid passport & VISA to travel to the destination your flying to.</span> </div>
              <div class="clearfix"></div>
              <div class="col-sm-6">
                <div class="mail_box" id="step2_mail_box"> <span>Your e-mail address</span> <span id="emailerror" class="err" style="color:#F00;"></span>
                  <label>
                    <input name="email_address" id="email_address" type="text" class="txt_box" placeholder="E-mail address" />
                  </label>
                  <label>
                    <input name="send_offers" id="send_offers" type="checkbox" value="1" />
                    Send me travel offers, deals and news by email</label>
                  <div class="clearfix"></div>
                </div>
              </div>
              
        </div>
       </div>
      </div>
                                                </div>
                                                
                                                
                                                <!--Fight Fare Details Start -->
                                                <div class="col-sm-6 pull-right">
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

             <div id="step3_details" >
                 <div class="traveller_details"> <span><strong>* Please provide the full name (including all names) as it is in your passport. please leave a space between each name. example on where to find your name in your passport <a href="#" target="_blank">click here</a> (will open in new tab)</strong></span> </div>
                
                 <?php foreach ($_SESSION['searchdata']['passengers'] as $index => $value) { ?>
                     <div class="form_panel"><span><strong>
                                  <?php error_log($value); ?>
                         <?php echo ($index + 1).".".$ptypefull[$value]; ?> </strong></span>
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
                     <input name="dob[]" type="text" typePass="<?php echo $value; ?>" class="txt_box date-pickR hasDatepicker" placeholder="Date of Birth"
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
                 <a role="button" data-toggle="collapse" href="#addOptional_<?php echo $index;?>" aria-expanded="false" 
                    aria-controls="addOptional_<?php echo $index;?>">Add optional details</a>
                 <div class="collapse" id="addOptional_<?php echo $index;?>" aria-hidden="true" >
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

               
                    <div class="col-sm-12 payOptions" >
                        <div class="col-sm-4 payLater">
                  
                                    <h2>I want to come back and pay later </h2>

                                    <p class="minh100px">

                                        You can check the available  payment options here, and make your reservation.
                                        Please arrange a payment before 11pm today or speak to our agents and get an extra time to make this payment.
                                    </p>
                                    <div class="col-md-12 text-center  pmfixnor">
                                        
                                        <a href="#" id="paylater-btn" data-type="0" class="btn btn-default"> 
                                            <i class="fa fa-clock-o" aria-hidden="true">
                                            </i>  Pay Later
                                        </a>
                                        
                                    </div>
                              
                        </div>
                        

                       <div class="col-sm-4 payNow">
                  
                                    <h2>I want to come pay now </h2>

                                    <p class="minh100px">

                                        You can check the available  payment options here, and make your reservation.
                                        Please arrange a payment before 11pm today or speak to our agents and get an extra time to make this payment.
                                    </p>
                                    <div class="col-md-12 text-center  pmfixnor">
                                        <a href="#" id="paynow-btn" data-type="0"
                                           class="btn btn-default"> 
                                            <i class="fa fa-clock-o" aria-hidden="true">
                                                
                                            </i>  Pay Now
                                        </a></div>
                              
                        </div>
                         <div class="col-sm-4 embassyPurpose">
                  
                                    <h2>I want to pay Embassy purpose </h2>

                                    <p class="minh100px">

                                        You can check the available  payment options here, and make your reservation.
                                        Please arrange a payment before 11pm today or speak to our agents and get an extra time to make this payment.
                                    </p>
                                    <div class="col-md-12 text-center  pmfixnor">
                                        <a href="#" id="payEmbassy-btn" data-type="0"
                                           class="btn btn-default"> 
                                            <i class="fa fa-clock-o" aria-hidden="true">
                                                
                                            </i>  Embassy
                                        </a></div>
                              
                        </div>
                       
                       
                    </div>
                 
             </div>
    </div>
    
    
     <!-- Step 3 End  -->   
     
     <!-- step 3 After -->

     
      <!-- step 3 End -->
     
     
     <!-- Step 4 Start-->
    

     <!--Step 4 End-->
    
   
   
   

                    </div>
                </div>
            </div>
        </div>
    </div>

        <?php }?>                                      

