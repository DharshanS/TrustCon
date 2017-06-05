<script type="application/javascript" src='http://localhost:8080/travel/wp-content/plugins/traveler/js/tr-roundtrip.js'/>





<?php
include(PLUG_DIR . 'utility/FlightUtility.php'); 
 
function init_display($response) {$flyUtil =new FlightUtility(); 


 
?>
<div class="container sky-bg flight_con">

    <div class="srh-result-area">

        <form>

            <div class="srh-lcl">

                <input type="text" name="srh-dpt-location" class="cls-srh-dpt-location" id="id-srh-dpt-location" value="Colombo (CMB)" readonly="">

            </div>

            <div class="srh-lcl">

                <input type="text" name="srh-arvl-location" class="cls-srh-arvl-location" id="id-srh-arvl-location" value="Bangkok (BKK)" readonly="">

            </div>

            <div class="srh-date">

                <label>Depart</label>

                <input type="text" name="srh-dpt-date" class="cls-srh-dpt-date" id="id-srh-dpt-date" value="06/06/2017" readonly="">

            </div>

            <div class="srh-date">


                <div>

                    <label>Return</label>

                    <input type="text" name="srh-dpt-date" class="cls-srh-dpt-date" id="id-srh-dpt-date" value="30/06/2017" readonly="">

                </div>


            </div>

            <div class="srh-psg">

                <input type="text" name="srh-passengers" class="cls-srh-psg-dtl" id="id-srh-psg-dtl" value="1 Adult 0 Infant 0 Child" readonly="">

                <input type="text" name="srh-travel-class" class="cls-srh-trvl-cls" id="id-srh-trvl-cls" value="Economy" readonly="">

            </div>

            <div class="srh-btn">


       <div class="srh-button-v1"><i class="fa fa-pencil"></i> Edit Search</div>

            </div>

        </form>

        <div class="clearfix"></div>

    </div>
    <div class="edit-area">

        <form action="/travel/flight-result/" method="post">

            <div class="select-trip-option">

                <div class="r-trip"><input type="radio" name="mode" value="roundtrip" onchange="chng_way(this);" checked=""> Round Trip</div>

                <div class="o-way"><input type="radio" name="mode" value="oneway" onchange="chng_way(this);"> One Way</div>

            </div>

            <div class="edit-book-dtl">

                <div class="edit-lcl">

                    <div>

                        <label >Depart From</label>

<!--                        <span class="twitter-typeahead">-->
<!--                             <input type="text" name="from_city" value="CMB,Colombo,Sri Lanka" placeholder="From" class="typeahead cls-booking-dtn" id="id-booking-dtn" />-->
<!---->
<!--                           </span>-->


<!--                        <i onclick="jQuery('#from_city').val('');" class="fa fa-times-circle"></i>-->

                    </div>

                </div>

                <div class="edit-lcl">

                    <div>
<!---->
<!--                        <label>Destination</label>-->
<!---->
<!--                        <span class="twitter-typeahead" >-->
<!--                            <input type="text" name="to_city" placeholder="To" class="typeahead cls-booking-arvl" id="id-booking-arvl" />-->
<!--                        <i onclick="jQuery('#id-edit-desti').val('');" class="fa fa-times-circle"></i>-->

                    </div>

                </div>

                <div class="date-pick-area">

                    <div>

                        <div class="date_picker">

                            <label>Depart Date</label>

                            <input type="text" class="date-pick search_arrival" data-date-format="yyyy-mm-dd" name="depart_date" placeholder="Departing" value="2017-06-28">

                        </div>

                    </div>

                </div>

                <div class="date-pick-area">

                    <div id="return_date_div" class="">

                        <div class="date_picker">

                            <label>Arrival Date</label>

                            <input type="text" class="date-pick search_arrival" data-date-format="yyyy-mm-dd" name="return_date" placeholder="Arrival" value="2017-06-30">

                        </div>

                    </div>

                </div>

            </div>

            <div class="edit-book-dtl2">

                <div class="edit-psg">

                    <div>

                        <div class="select-option">

                            <label>Adults: </label>

                            <select name="adult">

                                <option value="1" selected="selected">1 Adult</option>

                                <option value="2">2 Adults</option>

                                <option value="3">3 Adults</option>

                                <option value="4">4 Adults</option>

                                <option value="5">5 Adults</option>

                                <option value="6">6 Adults</option>

                                <option value="7">7 Adults</option>

                                <option value="8">8 Adults</option>

                                <option value="9">9 Adults</option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="edit-psg">

                    <div>

                        <div class="select-option">

                            <label>Children:</label>

                            <select name="child">

                                <option value="0" selected="selected">0 Children</option>

                                <option value="1">1 Child</option>

                                <option value="2">2 Children</option>

                                <option value="3">3 Children</option>

                                <option value="4">4 Children</option>

                                <option value="5">5 Children</option>

                                <option value="6">6 Children</option>

                                <option value="7">7 Children</option>

                                <option value="8">8 Children</option>

                                <option value="9">9 Children</option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="edit-psg">

                    <div>

                        <div class="select-option">

                            <label>Infants: </label>

                            <select name="infant">

                                <option value="0" selected="selected">0 Infants</option>

                                <option value="1">1 Infant</option>

                                <option value="2">2 Infants</option>

                                <option value="3">3 Infants</option>

                                <option value="4">4 Infants</option>

                                <option value="5">5 Infants</option>

                                <option value="6">6 Infants</option>

                                <option value="7">7 Infants</option>

                                <option value="8">8 Infants</option>

                                <option value="9">9 Infants</option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="edit-psg">

                    <div>

                        <div class="select-option">

                            <label>Class:</label>

                            <select name="cabinclass">

                                <option value="Economy" selected="selected">Economy</option>

                                <option value="Business">Business</option>

                                <option value="First">First Class</option>

                            </select>

                        </div>

                    </div>

                </div>

                <div class="edit-psg">
                    <div>
                        <div class="select-option">
                            <label>Select Airline: </label>
                            <select name="iata"></select>
                        </div>
                    </div>
                </div>


                <div class="edit-psg-btn-area">

                    <button>Find Flight <i class="fa fa-angle-double-right"></i></button>

                </div>


            </div>

        </form>

        <div class="clearfix"></div>

    </div>

<?php
    $flightUtility = new FlightUtility();
                    foreach ($response as $Key => $index) {
                        $baseprice=$index->basePrice;
                        $priceDetails=$index->airPrincingInfo;
                        error_log(print_r($baseprice,true));
                        $outBoundBestFlight = $index->bastOptionOut;
                        $outBoundBestFlightCount = count($outBoundBestFlight) - 1;
                        $outBoundBestFirstFlight = $outBoundBestFlight[0]['@attributes'];
                        $outBoundBestLastFlight = $outBoundBestFlight[$outBoundBestFlightCount]['@attributes'];
                        
                        $moreTimeOutCount=count($index->outBound);
                        


                        $inBoundBestFlight = $index->bastOptionIn;
                        $inBoundBestFlightCount = count($inBoundBestFlight) - 1;
                        $inBoundBestFirstFlight = $inBoundBestFlight[0]['@attributes'];
                        $inBoundBestLastFlight = $inBoundBestFlight[$inBoundBestFlightCount]['@attributes'];
                        
                         $moreTimeInCount=count($index->inBound);
                        
                        
                        $moreFlyList="";
                        if(isset($index->moreFly))
                        {
                          $moreFlyList=$index->moreFly;  
                        }
                    ?> 



  <div class="row ticket_details_r">
      
        <div class="container tiket_head_r">
            <div class="row">
               
            </div>
        </div>
      <!-- fly details start-->
        <div class="container tiket_body_r">
            <div class="row  item-rd ">
            <div class="col-lg-9 well-sm ">
      
                  <!--Out Bond start -->
                    <div class="row tkt_dts_r">
                                <div class="col-sm-3 col-xs-6 col-lg-4 flt rnd-out-1">
                                    <p class="out-p">  
                                        <input type="radio" class="radio-small out-select" name="outBound_select"  key="<?php echo $Key ?>"
                                               value="<?php echo base64_encode(json_encode($outBoundBestFlight)); ?>" >  &#160;&#160;&#160;&#160;   <?php echo $outBoundBestFirstFlight['Origin'] ?> <br>
                                        <?php echo date("h:i a ", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?> |   <?php echo date("D, d M y", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                       
                                      <?php if($moreTimeOutCount)  {?>
                                        <span>
                                            <a role="button" class="btn btn-info btn-sm out-moretime" data-toggle="collapse" href="#moreTimeOut<?php echo $Key ?>" aria-expanded="false" aria-controls="moreTimeOut<?php echo $Key ?>">more timings</a>

                                        </span> <?php } ?>
                                    </p>  
                                </div>
                                <div class="col-sm-3 col-xs-6 col-lg-4 flt">
                                    <p class="out-p2">&#160;&#160;&#160;&#160;&#160;&#160; <?php echo $outBoundBestLastFlight['Destination'] ?>  <br>
                                        <?php echo date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?> | <?php echo date("D, d M y", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                        </p>
                                </div>

                                <div class="col-sm-1 col-xs-4 col-lg-2 flt">1Stop</div>
                                <div class="col-sm-2 col-xs-4 col-lg-2 flt">
                                    <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundBestLastFlight['DepartureTime'])), date("h:i a", strtotime($outBoundBestFirstFlight['ArrivalTime']))) ?>
                                </div>
                    </div>
                  <div class="row rd-airimage">
                                   <div class="col-sm-3 col-xs-4 col-lg-3 airdiv ">
                                    <p><img src="../airimages/<?php echo $inBoundBestLastFlight['Carrier'] ?>.GIF">
                                    <?php echo $flightUtility->getAirLineName($inBoundBestLastFlight['Carrier']) ?></p> 
                                                                   </div>
                       <div class=" well line-img"></div>
                  </div>
                  <!--Out Bond End -->
                
                  <!--More timing out bound start-->
                    <?php if($moreTimeOutCount)  {?>
                    <div class=" row collapse moreFlights" id="moreTimeOut<?php echo $Key?>" aria-hidden=true accesskey="" >
                        
                      <?php  moretime_flys($index->outBound,$Key,'outBound') ?>
                       
                     </div>
                    <?php } ?>
                  <!--More timing out bound End--> 
                  
                   <!--Best inbound Start-->
                  <div class="row tkt_dts_r">           
                              <div class="col-sm-3 col-xs-6 col-lg-4 flt  rnd-out-1">                                             
                                  <p class="out-p">
                                      <input type="radio" class="radio-small out-select" name="inBound_select"  key="<?php echo $Key ?>"
                                             value="<?php echo base64_encode(json_encode($inBoundBestFlight)); ?>" > &#160;&#160;&#160;&#160;
                                      <?php echo $inBoundBestFirstFlight['Origin'] ?>  <br>
                                      <?php echo date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?> |
                                      <?php echo date("D, d M y", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                      <?php if($moreTimeInCount >0) { ?>
                                      <span>
                                          <a role="button" class="btn btn-info btn-sm out-moretime" data-toggle="collapse" href="#moreTimeIn<?php echo $Key ?>" aria-expanded="false" aria-controls="moreTimeIn<?php echo $Key ?>">more timings</a>
                                      </span>
                                     
                                      <?php } ?>
                                  </p>
                              </div>
                              <div class="col-sm-3 col-xs-6 col-lg-4 flt ">
                                  <p class="out-p2" >&#160;&#160;&#160;&#160;&#160;&#160;<?php echo $inBoundBestLastFlight['Destination'] ?> <br>
                                      <?php echo date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?>
                                      <?php echo date("D, d M y", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?><br>
                                      <?php if (count($moreFlyList) > 1) { ?>
                                          <span>
                                              <a role="button" class="btn btn-info btn-sm out-select out-moretime" data-toggle="collapse" href="#moreFlys<?php echo $Key ?>" aria-expanded="false" aria-controls="moreFlys<?php echo $Key ?>">more flights</a>

                                          </span>
                                      <?php } ?>
                                  </p>
                              </div>
                             
                              <div class="col-sm-1 col-xs-4 col-lg-2 flt ">1Stop</div>
                              <div class="col-sm-2 col-xs-4 col-lg-2 flt ">
                                  <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundBestLastFlight['DepartureTime'])), date("h:i a", strtotime($inBoundBestFirstFlight['ArrivalTime']))) ?>
                              </div>
             </div>
               <!--Best inbound End-->     
               
                 <?php if($moreTimeInCount >0) { ?>
               <div class=" row collapse moreFlights" id="moreTimeIn<?php echo $Key ?>" aria-hidden=true accesskey="" >
                       <?php  moretime_flys($index->inBound,$Key,"inBound") ?>      
                </div>  
              <?php } ?>
           
        
         <?php                                   fly_details($outBoundBestFlight, $inBoundBestFlight,$Key) ?>
             
            </div>
            
                
                 <div class="col-lg-3 prz_dts_r">
                  <label class="cls-flight-prz"><?php echo $baseprice['TotalPrice']?></label>
                          <form name="frmAvail" action="../booking" method="post">
                            <input type="hidden" name="outbound" id="bout_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($outBoundBestFlight)); ?>">
                            <input type="hidden" name="inbound" id="bin_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($inBoundBestFlight)); ?>">
                            <input type="hidden" name="pricedata" value="<?php echo base64_encode(json_encode($priceDetails)); ?>">
                            <input type="hidden" name="searchmode" value="">
                            <button type="submit" class="btn-sm btn-success bk-bt">Book Now</button>
                        </form>
                     
                        <form name="frmFL" id="frmFL9" method="post" action="../farerule.php" target="_blank">
                            <input type="hidden" name="k" value="">
                            <input type="hidden" name="ref" value="">
                            <p><a class="shw2" onclick="jQuery('#frmFL9').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>
                        </form>
                  <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>
                  
                 </div>
            </div>
        </div>
      <!-- fly details End-->
         
         
      <!--- More Flights Start-->
      <?php if(count($moreFlyList)>1)
     {?>
      <div class="container collapse tiket_body_r" id="moreFlys<?php echo $Key ?>" aria-hidden=true accesskey="">
                      <?php
                      foreach ($moreFlyList as $m=>$moreFly) {
                          $KeyLoc=$m."_".$Key;
                          $m_outBoundBestFlight = $moreFly->bastOptionOut;
                          $m_baseprice=$moreFly->basePrice;
                          $m_outBoundBestFlightCount = count($m_outBoundBestFlight) - 1;
                          $m_outBoundBestFirstFlight = $m_outBoundBestFlight[0]['@attributes'];
                          $m_outBoundBestLastFlight = $m_outBoundBestFlight[$m_outBoundBestFlightCount]['@attributes'];
                          
                          $m_moreTimeOut=$moreFly->outBound;
                          $m_moreTimeOutCount=count($m_moreTimeOut);
                          
                          $m_moreTimeIn=$moreFly->inBound;
                          $m_moreTimeInCount=count($m_moreTimeIn);

                          $m_inBoundBestFlight = $index->bastOptionIn;
                          $m_inBoundBestFlightCount = count($m_inBoundBestFlight) - 1;
                          $m_inBoundBestFirstFlight = $m_inBoundBestFlight[0]['@attributes'];
                          $m_inBoundBestLastFlight = $m_inBoundBestFlight[$m_inBoundBestFlightCount]['@attributes'];
                          ?>

          <div class="row  item-rd ">
            <div class="col-lg-9 well-sm ">
      
                  <!--Out Bond start -->
                    <div class="row tkt_dts_r">
                                <div class="col-sm-3 col-xs-6 col-lg-4 flt rnd-out-1">
                                    <p class="out-p">  
                                        <input type="radio" class="radio-small out-select" name="outBound_select"  key="<?php echo $KeyLoc ?>"
                                               value="<?php echo base64_encode(json_encode($m_outBoundBestFlight)); ?>" >  &#160;&#160;&#160;&#160;   <?php echo $outBoundBestFirstFlight['Origin'] ?> <br>
                                        <?php echo date("h:i a ", strtotime($m_outBoundBestFirstFlight['DepartureTime'])) ?> |   <?php echo date("D, d M y", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                       
                                      <?php if($m_moreTimeOutCount)  {?>
                                        <span>
                                            <a role="button" class="btn btn-info btn-sm out-moretime" data-toggle="collapse" href="#moreTimeOut<?php echo $KeyLoc ?>" aria-expanded="false" aria-controls="moreTimeOut<?php echo $KeyLoc ?>">more timings</a>

                                        </span> <?php } ?>
                                    </p>  
                                </div>
                                <div class="col-sm-3 col-xs-6 col-lg-4 flt">
                                    <p class="out-p2">&#160;&#160;&#160;&#160;&#160;&#160; <?php echo $m_outBoundBestLastFlight['Destination'] ?>  <br>
                                        <?php echo date("h:i a", strtotime($m_outBoundBestLastFlight['ArrivalTime'])) ?> | <?php echo date("D, d M y", strtotime($m_outBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                        </p>
                                </div>

                                <div class="col-sm-1 col-xs-4 col-lg-2 flt">1Stop</div>
                                <div class="col-sm-2 col-xs-4 col-lg-2 flt">
                                    <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($m_outBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($m_outBoundBestLastFlight['ArrivalTime']))) ?>
                                </div>
                    </div>
                  <div class="row rd-airimage">
                                   <div class="col-sm-3 col-xs-4 col-lg-3 airdiv ">
                                    <p><img src="../airimages/<?php echo $m_outBoundBestLastFlight['Carrier'] ?>.GIF">
                                    <?php echo $flightUtility->getAirLineName($m_outBoundBestLastFlight['Carrier']) ?></p>    
                                    <div class="break">erty</div>
                                </div>
                  </div>
                  <!--Out Bond End -->
                
                  <!--More timing out bound start-->
                    <?php if($m_moreTimeOutCount)  {?>
                    <div class=" row collapse moreFlights" id="moreTimeOut<?php echo $KeyLoc?>" aria-hidden=true accesskey="" >
                        
                      <?php  moretime_flys($index->outBound,$KeyLoc,'outBound') ?>
                       
                     </div>
                    <?php } ?>
                  <!--More timing out bound End--> 
                  
                   <!--Best inbound Start-->
                  <div class="row tkt_dts_r">           
                              <div class="col-sm-3 col-xs-6 col-lg-4 flt  rnd-out-1">                                             
                                  <p class="out-p">
                                      <input type="radio" class="radio-small out-select" name="inBound_select"  key="<?php echo $KeyLoc ?>"
                                             value="<?php echo base64_encode(json_encode($m_inBoundBestFlight)); ?>" > &#160;&#160;&#160;&#160;
                                      <?php echo $m_inBoundBestFirstFlight['Origin'] ?>  <br>
                                      <?php echo date("h:i a", strtotime($m_inBoundBestFirstFlight['DepartureTime'])) ?> |
                                      <?php echo date("D, d M y", strtotime($m_inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                      <?php if($m_moreTimeInCount >0) { ?>
                                      <span>
                                          <a role="button" class="btn btn-info btn-sm out-moretime" data-toggle="collapse" href="#moreTimeIn<?php echo $KeyLoc ?>" aria-expanded="false" aria-controls="moreTimeIn<?php echo $KeyLoc ?>">more timings</a>
                                      </span>
                                     
                                      <?php } ?>
                                  </p>
                              </div>
                              <div class="col-sm-3 col-xs-6 col-lg-4 flt ">
                                  <p class="out-p2" >&#160;&#160;&#160;&#160;&#160;&#160;<?php echo $m_inBoundBestLastFlight['Destination'] ?> <br>
                                      <?php echo date("h:i a", strtotime($m_inBoundBestLastFlight['ArrivalTime'])) ?> 
                                      <?php echo date("D, d M y", strtotime($m_inBoundBestLastFlight['ArrivalTime'])) ?><br>
                                     
                                  </p>
                              </div>
                             
                              <div class="col-sm-1 col-xs-4 col-lg-2 flt ">1Stop</div>
                              <div class="col-sm-2 col-xs-4 col-lg-2 flt ">
                                  <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($m_inBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($m_inBoundBestLastFlight['ArrivalTime']))) ?>
                              </div>
             </div>
               <!--Best inbound End-->     
               
                 <?php if($m_moreTimeInCount >0) { ?>
               <div class=" row collapse moreFlights" id="moreTimeIn<?php echo $KeyLoc ?>" aria-hidden=true accesskey="" >
                       <?php  moretime_flys($index->inBound,$KeyLoc,"inBound") ?>      
                </div>  
              <?php } ?>
           
        
         <?php                                   fly_details($outBoundBestFlight, $inBoundBestFlight,$KeyLoc) ?>
             
            </div>
            
                
                 <div class="col-lg-3 prz_dts_r">
<!--                     <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="<?php echo $baseprice['TotalPrice']?>" readonly="">-->
                          <form name="frmAvail" action="../booking" method="post">
                            <input type="hidden" name="outbound" id="bout_<?php echo $KeyLoc ?>" value="<?php echo base64_encode(json_encode($outBoundBestFlight)); ?>">
                            <input type="hidden" name="inbound" id="bin_<?php echo $KeyLoc ?>" value="<?php echo base64_encode(json_encode($inBoundBestFlight)); ?>">
                            <input type="hidden" name="pricedata" value="<?php echo base64_encode(json_encode($priceDetails)); ?>">
                            <input type="hidden" name="searchmode" value="">
                            <button type="submit" class="btn-sm btn-success bk-bt">Book Now</button>
                        </form>
                     
                        <form name="frmFL" id="frmFL9" method="post" action="../farerule.php" target="_blank">
                            <input type="hidden" name="k" value="">
                            <input type="hidden" name="ref" value="">
                            <p><a class="shw2" onclick="jQuery('#frmFL9').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>
                        </form>
                  <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>
                  
                 </div>
            </div>
            
                              
                        
          
          
        
       <?php } ?>
       </div> <?php } ?>
      <!-- More Flights End -->
        
  </div>

<?php
                                   } 
                                 ?>  </div> <?php
                                   
                 }
                    
                                   
                                   
  //*********************More Timing Start************************************                                 
 function moretime_flys($itemList, $Key, $mode) {
    $flightUtility = new FlightUtility();
    foreach ($itemList as $mt=>$item) {
        $flycount = count($item) - 1;
        
        $firstFly = $item[0]['@attributes'];
        $lasyFly = $item[$flycount]['@attributes'];
        ?>
        <div class="col-lg-9 ">
                                <div class="row mtf">
                                        <div class="col-sm-3 col-xs-6 col-lg-4 ">
                                            <p>
                                                <input type="radio" class="radio-small" name="<?php echo $mode?>_select"  key="<?php echo $Key ?>"
                                                       value="<?php echo base64_encode(json_encode($item)); ?>" >&#160;&#160;
             <?php echo date("h:i a", strtotime($firstFly['DepartureTime'])) ?> |
        <?php echo date("D, d M y", strtotime($firstFly['DepartureTime'])) ?><br>
                                             </p>  
                                        </div>
                                        <div class="col-sm-3 col-xs-6 col-lg-4 ">
                                            <p class="out-p2">
        <?php echo date("h:i a", strtotime($lasyFly['ArrivalTime'])) ?> 
        <?php echo date("D, d M Y", strtotime($lasyFly['ArrivalTime'])) ?></p>
                                        </div>
                                        
                                        <div class="col-sm-1 col-xs-4 col-lg-2 ">1Stop</div>
                                        <div class="col-sm-2 col-xs-4 col-lg-2 ">
        <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($lasyFly['ArrivalTime'])), date("h:i a", strtotime($firstFly['DepartureTime']))) ?>
                                        </div>
                            </div>
        </div> <?php }
}
//************************More Timing End ************************



//<!--***************************Flight Details Start***********************************-->
 function fly_details($itemOutList,$itemInList,$Key) {
    
     $itemOutListCount=count($itemOutList)-1;
     $itemInListCount=count($itemInList)-1;
     $flightUtility = new FlightUtility();
     
    
    ?>
  <div class="edit-visl round_tr">
            <div class="head">
              <i class="fa fa-plane"></i> Depart
          </div>
          <!-- *********************************outBound details******************************outBound******************************************************************************--> 
       <div class="<?php echo "outBound_".$Key ?>">   
                                        <?php
                                        foreach ($itemOutList as $outindex) {
                                            $temp = $outindex['@attributes'];
                                           
                                            ?>
                                            <div class="row flt-dtl <?php echo $Key ?>">
                                                <div class="col-sm-4 col-xs-6 arw">
                                                    <p><?php echo $flightUtility->getCityName($temp['Origin'])."(".$temp['Origin']. ")" ?><br>
                                                    <?php echo date("h:i a", strtotime($temp['DepartureTime'])) ?> <br>
                                                        <?php echo date("D, d M Y", strtotime($temp['DepartureTime'])) ?></p>
                                                </div>
                                                <div class="col-sm-4 col-xs-6">
                                                    <p><?php echo $flightUtility->getCityName($temp['Destination'])."(".$temp['Destination']. ")" ?><br>
                                                      
                                                        <?php echo date("h:i a", strtotime($temp['ArrivalTime'])) ?> <br>
                                                        <?php echo date("D, d M Y", strtotime($temp['ArrivalTime'])) ?></p>
                                                </div>

                                                <div class="col-sm-4">
                                                    <p><i class="fa fa-clock-o"></i> 
                                                        <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($temp['ArrivalTime'])), date("h:i a", strtotime($temp['DepartureTime']))) ?></p>
                                                </div>

                                            </div>

                                            <div class="vgn"><p><img src="<?php echo AIRLINE_IMG.$temp['Carrier']?>.GIF"> 
                                                    <?php echo $flightUtility->getAirLineName($temp['Carrier']) ?> -
                                                    <?php echo $temp['Carrier'] . $temp['Equipment'] ?> <a> 
                                                        <?php echo $temp[0]['CabinClass'] . ' ' . $temp[0]['BookingCode'] ?></a> - Aircraft  <?php echo $temp['FlightNumber'] ?></p>
                                            </div>


                                            <?php
                                        }
                                        $totaltime = $flightUtility->getTimeDiff(date("h:i a", strtotime($itemOutList[$itemOutListCount]['@attributes']['ArrivalTime'])), date("h:i a", strtotime($itemOutList[0]['@attributes']['DepartureTime'])))
                                        ?>

                                        <div class="btm">
                                            <i class="fa fa-clock-o"></i> 
                                            <strong>TOTAL DURATION</strong> <?php echo $totaltime ?>
                                        </div>
                                        </div>
          <!-- ***************************************In bound details*************************InBound*****************************************************************************-->                   

          <div class="head">
              <i class="fa fa-plane"></i> Return
          </div>
                       <div class="<?php echo "inBound_".$Key ?>">
                                        <?php
                                        
                                        foreach ($itemInList as $jindex) {
                                            $temp = $jindex['@attributes'];
//                                            error_log(print_r($jindex,true));
//                                            return;
                                            ?>
                                            <div class="row flt-dtl">
                                                <div class="col-sm-4 col-xs-6 arw">
                                                    <p><?php echo $flightUtility->getCityName($temp['Origin'])."(".$temp['Origin'].")" ?><br>
                                                         <?php echo date("h:i a", strtotime($temp['DepartureTime'])) ?> <br>
                                                        <?php echo date("D, d M Y", strtotime($temp['DepartureTime'])) ?></p>
                                                </div>
                                                <div class="col-sm-4 col-xs-6">
                                                    <p><?php echo $flightUtility->getCityName($temp['Destination'])."(".$temp['Destination'].")" ?><br>
                                                        
                                                        <?php echo date("h:i a", strtotime($temp['ArrivalTime'])) ?> <br>
                                                        <?php echo date("D, d M Y", strtotime($temp['ArrivalTime'])) ?></p>
                                                </div>
                                                <div class="col-sm-4">
                                                    <p><i class="fa fa-clock-o"></i> 
                                                        <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($temp['DepartureTime'])), date("h:i a", strtotime($temp['ArrivalTime']))) ?></p>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="vgn"><p><img src="<?php echo AIRLINE_IMG.$temp['Carrier']?>.GIF"> 
                                                    <?php echo $flightUtility->getAirLineName($temp['Carrier']) ?> -
                                                    <?php echo $temp['Carrier'] . $temp['Equipment'] ?> <a> 
                                                        <?php echo $temp[0]['CabinClass'] . ' ' . $temp[0]['BookingCode'] ?></a> - Aircraft  <?php echo $temp['FlightNumber'] ?></p>
                                            </div>


                                        <?php } 
                                        $totaltime = $flightUtility->getTimeDiff(date("h:i a", strtotime($itemInList[$itemInListCount]['@attributes']['ArrivalTime'])), date("h:i a", strtotime($itemInList[0]['@attributes']['DepartureTime']))) ?>

                                        <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong><?php echo $totaltime ?></div>
                                               </div>                              
                                          
                                        
                <div class="clearfix"></div>
           
            </div>
<?php } ?>
