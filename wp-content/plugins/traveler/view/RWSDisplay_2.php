<!--<script type="application/javascript" src='http://localhost:8080/travel/wp-content/plugins/traveler/js/tr-roundtrip.js'/>-->

<?php
include(PLUG_DIR . 'utility/FlightUtility.php'); 
 
function init_display($response) {$flyUtil =new FlightUtility(); 

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
                        
                        
                        $moreFlyList;
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
            <div class="row ">
            <div class="col-lg-9 item">
      
                  <!--Out Bond start -->
                    <div class="row tkt_dts_r">
                                <div class="col-sm-3 col-xs-6 col-lg-3 flt  flySeg">
                                    <p><?php echo $outBoundBestFirstFlight['Origin'] ?>  <br>
                                        <input type="radio" class="radio-small" name="outBound_select"  key="<?php echo $Key ?>"
                                               value="<?php echo base64_encode(json_encode($outBoundBestFlight)); ?>" >
                                        <?php echo date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                        <?php echo date("D, d M Y", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?>
                                      <?php if($moreTimeOutCount)  {?>
                                        <span>
                                            <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#moreTimeOut<?php echo $Key ?>" aria-expanded="false" aria-controls="moreTimeOut<?php echo $Key ?>">more timings</a>

                                        </span> <?php } ?>
                                    </p>  
                                </div>
                                <div class="col-sm-3 col-xs-6 col-lg-3 flt flySeg">
                                    <p><?php echo $outBoundBestLastFlight['Destination'] ?> <br>
                                        <?php echo date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                        <?php echo date("D, d M Y", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?></p>
                                </div>
                                <div class="col-sm-3 col-xs-4 col-lg-3 flt drsn flySeg">
                                    <img src="../airimages/<?php echo $inBoundBestLastFlight['Carrier'] ?>.GIF"><br> 
                                    <?php echo $flightUtility->getAirLineName($inBoundBestLastFlight['Carrier']) ?>                
                                </div>
                                <div class="col-sm-1 col-xs-4 col-lg-1 flt flySeg">1Stop</div>
                                <div class="col-sm-2 col-xs-4 col-lg-2 flt flySeg">
                                    <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundBestLastFlight['DepartureTime'])), date("h:i a", strtotime($outBoundBestFirstFlight['ArrivalTime']))) ?>
                                </div>
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
                              <div class="col-sm-3 col-xs-6 col-lg-3 flt  flySeg">                                             
                                  <p><?php echo $inBoundBestFirstFlight['Origin'] ?>  <br>
                                      <input type="radio" class="radio-small" name="inBound_select"  key="<?php echo $Key ?>"
                                             value="<?php echo base64_encode(json_encode($inBoundBestFlight)); ?>" >
                                      <?php echo date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                      <?php echo date("D, d M Y", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?>
                                      <?php if($moreTimeInCount >0) { ?>
                                      <span>
                                          <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#moreTimeIn<?php echo $Key ?>" aria-expanded="false" aria-controls="moreTimeIn<?php echo $Key ?>">more timings</a>
                                      </span>
                                     
                                      <?php } ?>
                                  </p>
                              </div>
                              <div class="col-sm-3 col-xs-6 col-lg-3 flt flySeg">
                                  <p><?php echo $inBoundBestLastFlight['Destination'] ?> <br>
                                      <?php echo date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                      <?php echo date("D, d M Y", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?>
                                      <?php if (count($moreFlyList) > 1) { ?>
                                          <span>
                                              <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#moreFlys<?php echo $Key ?>" aria-expanded="false" aria-controls="moreFlys<?php echo $Key ?>">more flights</a>

                                          </span>
                                      <?php } ?>
                                  </p>
                              </div>
                              <div class="col-sm-3 col-xs-4 col-lg-3 flt drsn flySeg">
                                  <img src="../airimages/<?php echo $inBoundBestLastFlight['Carrier'] ?>.GIF"><br> 
                                  <?php echo $flightUtility->getAirLineName($inBoundBestLastFlight['Carrier']) ?>                
                              </div>
                              <div class="col-sm-1 col-xs-4 col-lg-1 flt flySeg">1Stop</div>
                              <div class="col-sm-2 col-xs-4 col-lg-2 flt flySeg">
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
                     <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="<?php echo $baseprice['TotalPrice']?>" readonly="">
                          <form name="frmAvail" action="../booking" method="post">
                            <input type="hidden" name="outbound" id="bout_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($outBoundBestFlight)); ?>">
                            <input type="hidden" name="inbound" id="bin_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($inBoundBestFlight)); ?>">
                            <input type="hidden" name="pricedata" value="<?php echo base64_encode(json_encode($priceDetails)); ?>">
                            <input type="hidden" name="searchmode" value="">
                            <button type="submit">Book Now</button>
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

                          <div class="row ">
                              <div class="col-lg-9 item">
                                  <div class="row tkt_dts_r">
                                      <div class="col-sm-3 col-xs-6 col-lg-3 flt  flySeg">
                                                          <p><?php echo $m_outBoundBestFirstFlight['Origin'] ?>  <br>
                                                              <input type="radio" class="radio-small" name="outBound_select"  key="<?php echo $KeyLoc ?>"
                                                                     value="<?php echo base64_encode(json_encode($m_outBoundBestFlight)); ?>" >
                                                              <?php echo date("h:i a", strtotime($m_outBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                                              <?php echo date("D, d M Y", strtotime($m_outBoundBestFirstFlight['DepartureTime'])) ?>

                                                              <?php if( $m_moreTimeOutCount > 0 ) { ?>
                                                              <span>
                                                                  <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#m_moreTimeOut<?php echo $m?>" aria-expanded="false" aria-controls="m_moreTimeOut">more timings</a>
                                                              </span>
                                                              <?php } ?>

                                                          </p>
                                       </div>
                                      <div class="col-sm-3 col-xs-6 col-lg-3 flt flySeg">
                                          <p><?php echo $m_outBoundBestLastFlight['Destination'] ?> <br>
                                              <?php echo date("h:i a", strtotime($m_outBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                              <?php echo date("D, d M Y", strtotime($m_outBoundBestLastFlight['ArrivalTime'])) ?></p>
                                      </div>
                                      <div class="col-sm-3 col-xs-4 col-lg-3 flt drsn flySeg">
                                          <img src="../airimages/<?php echo $m_outBoundBestLastFlight['Carrier'] ?>.GIF"><br> 
                                          <?php echo $flightUtility->getAirLineName($m_outBoundBestLastFlight['Carrier']) ?>                
                                      </div>
                                      <div class="col-sm-1 col-xs-4 col-lg-1 flt flySeg">1Stop</div>
                                      <div class="col-sm-2 col-xs-4 col-lg-2 flt flySeg">
                                          <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($m_outBoundBestLastFlight['DepartureTime'])), date("h:i a", strtotime($m_outBoundBestFirstFlight['ArrivalTime']))) ?>
                                      </div>
                                  </div>
                                    <?php if($m_moreTimeOutCount> 0 ) { ?>
                                  <div class=" row collapse moreFlights" id="m_moreTimeOut<?php echo $m?>" aria-hidden=true accesskey="" >
                                    <?php  moretime_flys($m_moreTimeOut,$KeyLoc,'outBound') ?>
                                  </div>
                                   <?php } ?>
                                  
                                  <div class="row tkt_dts_r">
                                      <div class="col-sm-3 col-xs-6 col-lg-3 flt  flySeg">
                                          <p><?php echo $m_inBoundBestFirstFlight['Origin'] ?>  <br>
                                              <input type="radio" class="radio-small" name="inBound_select" key="<?php echo $KeyLoc ?>"
                                                     value="<?php echo base64_encode(json_encode($m_inBoundBestFlight)); ?>" >
                                              <?php echo date("h:i a", strtotime($m_inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                              <?php echo date("D, d M Y", strtotime($m_inBoundBestFirstFlight['DepartureTime'])) ?>
                                                <?php if( $m_moreTimeInCount > 0 ) { ?>
                                                              <span>
                                                                  <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#m_moreTimeIn<?php echo $m?>" aria-expanded="false" aria-controls="m_moreTimeOut">more timings</a>
                                                              </span>
                                                              <?php } ?>
                                             
                                          </p>
                                      </div>
                                      <div class="col-sm-3 col-xs-6 col-lg-3 flt flySeg">
                                          <p><?php echo $m_inBoundBestLastFlight['Destination'] ?> <br>
                                              <?php echo date("h:i a", strtotime($m_inBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                              <?php echo date("D, d M Y", strtotime($m_inBoundBestLastFlight['ArrivalTime'])) ?>

                                          </p>
                                      </div>
                                      <div class="col-sm-3 col-xs-4 col-lg-3 flt drsn flySeg">
                                          <img src="../airimages/<?php echo $m_inBoundBestLastFlight['Carrier'] ?>.GIF"><br> 
                                          <?php echo $flightUtility->getAirLineName($m_inBoundBestLastFlight['Carrier']) ?>                
                                      </div>
                                      <div class="col-sm-1 col-xs-4 col-lg-1 flt flySeg">1Stop</div>
                                      <div class="col-sm-2 col-xs-4 col-lg-2 flt flySeg">
                                          <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($m_inBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($m_inBoundBestLastFlight['ArrivalTime']))) ?>
                                      </div>
                                  </div>
                                   <?php if($m_moreTimeInCount> 0 ) { ?>
                                  <div class=" row collapse moreFlights" id="m_moreTimeIn<?php echo $m?>" aria-hidden=true accesskey="" >
                                      <?php  moretime_flys($m_moreTimeIn,$KeyLoc,"inBound") ?>   
                                  </div>
                                   <?php } ?>
                                  
                                   <?php                                   fly_details($m_outBoundBestFlight, $m_inBoundBestFlight,$KeyLoc) ?>
                                  
                                  </div>
                         




                              <div class="col-lg-3 prz_dts_r">
                                <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="<?php echo $m_baseprice['TotalPrice']?>" readonly="">
                          <form name="frmAvail" action="../booking" method="post">
                            <input type="hidden" name="bookingdata" value="">
                       
                            <input type="hidden" name="searchdata" value="">
                            <input type="hidden" name="searchmode" value="">
                            <button type="submit">Book Now</button>
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
                                   } }
                              
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
                                   
  //*********************More Timing Start************************************                                 
 function moretime_flys($itemList, $Key, $mode) {
    $flightUtility = new FlightUtility();
    foreach ($itemList as $mt=>$item) {
        $flycount = count($item) - 1;
        
        $firstFly = $item[0]['@attributes'];
        $lasyFly = $item[$flycount]['@attributes'];
        ?>
        <div class="col-lg-9 ">
                                <div class="row tkt_dts_r">
                                        <div class="col-sm-3 col-xs-6 col-lg-3 flt  flySeg">
                                            <p><?php echo $firstFly['Origin'] ?>  <br>
                                                <input type="radio" class="radio-small" name="<?php echo $mode?>_select"  key="<?php echo $Key ?>"
                                                       value="<?php echo base64_encode(json_encode($item)); ?>" >
        <?php echo date("h:i a", strtotime($firstFly['DepartureTime'])) ?> <br>
        <?php echo date("D, d M Y", strtotime($firstFly['DepartureTime'])) ?>
                                             </p>  
                                        </div>
                                        <div class="col-sm-3 col-xs-6 col-lg-3 flt flySeg">
                                            <p><?php echo $lasyFly['Destination'] ?> <br>
        <?php echo date("h:i a", strtotime($lasyFly['ArrivalTime'])) ?> <br>
        <?php echo date("D, d M Y", strtotime($lasyFly['ArrivalTime'])) ?></p>
                                        </div>
                                        <div class="col-sm-3 col-xs-4 col-lg-3 flt drsn flySeg">
                                            <img src="../airimages/<?php echo $lasyFly['Carrier'] ?>.GIF"><br> 
        <?php echo $flightUtility->getAirLineName($lasyFly['Carrier']) ?>                
                                        </div>
                                        <div class="col-sm-1 col-xs-4 col-lg-1 flt flySeg">1Stop</div>
                                        <div class="col-sm-2 col-xs-4 col-lg-2 flt flySeg">
        <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($lasyFly['ArrivalTime'])), date("h:i a", strtotime($firstFly['DepartureTime']))) ?>
                                        </div>
                            </div>
        </div> <?php
    }
}
//************************More Timing End ************************
?>


<!--***************************Flight Details Start***********************************-->
<?php function fly_details($itemOutList,$itemInList,$Key) {
    
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
                                                    <p><?php echo $flightUtility->getCityName($temp['Origin']) ?><br>
                                                        <?php echo $temp['Origin'] ?><br>
                                                        <?php echo $flightUtility->getAirLineName($temp['Carrier']) ?><br>
                                                        <?php echo date("h:i a", strtotime($temp['DepartureTime'])) ?> <br>
                                                        <?php echo date("D, d M Y", strtotime($temp['DepartureTime'])) ?></p>
                                                </div>
                                                <div class="col-sm-4 col-xs-6">
                                                    <p><?php echo $flightUtility->getCityName($temp['Destination']) ?><br>
                                                        <?php echo $temp['Destination'] ?><br>
                                                        <?php echo $flightUtility->getAirLineName($temp['Carrier']) ?><br>
                                                        <?php echo date("h:i a", strtotime($temp['ArrivalTime'])) ?> <br>
                                                        <?php echo date("D, d M Y", strtotime($temp['ArrivalTime'])) ?></p>
                                                </div>

                                                <div class="col-sm-4">
                                                    <p><i class="fa fa-clock-o"></i> 
                                                        <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($temp['ArrivalTime'])), date("h:i a", strtotime($temp['DepartureTime']))) ?></p>
                                                </div>

                                            </div>

                                            <div class="vgn"><p><img src="../airimages/<?php echo $flightUtility->getAirLineName($temp['Carrier']) ?>.GIF"> 
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
                                                    <p><?php echo $flightUtility->getCityName($temp['Origin']) ?><br>
                                                        <?php echo $temp['Origin'] ?><br>
                                                        <?php echo $flightUtility->getAirLineName($temp['Carrier']) ?><br>
                                                        <?php echo date("h:i a", strtotime($temp['DepartureTime'])) ?> <br>
                                                        <?php echo date("D, d M Y", strtotime($temp['DepartureTime'])) ?></p>
                                                </div>
                                                <div class="col-sm-4 col-xs-6">
                                                    <p><?php echo $flightUtility->getCityName($temp['Destination']) ?><br>
                                                        <?php echo $temp['Destination'] ?><br>
                                                        <?php echo $flightUtility->getAirLineName($temp['Carrier']) ?><br>
                                                        <?php echo date("h:i a", strtotime($temp['ArrivalTime'])) ?> <br>
                                                        <?php echo date("D, d M Y", strtotime($temp['ArrivalTime'])) ?></p>
                                                </div>
                                                <div class="col-sm-4">
                                                    <p><i class="fa fa-clock-o"></i> 
                                                        <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($temp['DepartureTime'])), date("h:i a", strtotime($temp['ArrivalTime']))) ?></p>
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="vgn"><p><img src="../airimages/<?php echo $flightUtility->getAirLineName($temp['Carrier']) ?>.GIF"> 
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