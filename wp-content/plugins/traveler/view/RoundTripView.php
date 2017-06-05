<!--<script type="application/javascript" src='http://localhost:8080/travel/wp-content/plugins/traveler/js/tr-roundtrip.js'/>-->
<?php
require_once(PLUG_DIR . 'models/RoundTrip.php');
include_once (PLUG_DIR . 'utility/FlightUtility.php');
$test = "WellCome";

function roundTripView($response) {
    error_log("ROUND TRIP VIEW");
    $flightUtility = new FlightUtility();
    error_log(print_r($response, true));
    ?> 
    <div id="resultloader">
        <div class="sky-bg">
            <div class="container">
                <div class="row">
<!-- ******************************* flights************************************************* -->
                          <?php
                    foreach ($response as $Key => $index) {
                        $outBoundBestFlight = $index->bastOptionOut;
                        $outBoundBestFlightCount = count($outBoundBestFlight) - 1;
                        $outBoundBestFirstFlight = $outBoundBestFlight[0]['@attributes'];
                        $outBoundBestLastFlight = $outBoundBestFlight[$outBoundBestFlightCount]['@attributes'];


                        $inBoundBestFlight = $index->bastOptionIn;
                        $inBoundBestFlightCount = count($inBoundBestFlight) - 1;
                        $inBoundBestFirstFlight = $inBoundBestFlight[0]['@attributes'];
                        $inBoundBestLastFlight = $inBoundBestFlight[$inBoundBestFlightCount]['@attributes'];
                    ?> 

   <div class="col-sm-12">
        <div class="show-flight">                    
            <div class="col-sm-9">

                <div class="visl">

                                    <!-- Best option OUT Flights -->
                               <div class="row brd-btm "><!--Added CSS-->  
                                                              <div class="col-sm-1 flt flySeg"> 
                                                                  <label><input type="radio" class="radio-small" name="roud_out_sel"  mode="outBound_<?php echo $Key ?>"
                                                                                value="<?php echo base64_encode(json_encode($outBoundBestFlight)); ?>" > vae</label> 
                                                              </div>
                                                              <div class="col-sm-3 flt arw flySeg">
                                                                  <p> <?php echo $outBoundBestFirstFlight['Origin'] ?>   <br>
                                                                      <?php echo date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                                                      <?php echo date("D, d M Y", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?></p>
                                                              </div>
                                                              <div class="col-sm-3  flt flySeg">
                                                                  <p><?php echo $outBoundBestLastFlight['Destination'] ?>  <br>
                                                                      <?php echo date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                                                      <?php echo date("D, d M Y", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?></p>
                                                              </div>
                                                              <div class="col-sm-3 flt drsn flySeg">
                                                                  <img src="../airimages/<?php echo $outBoundBestLastFlight['Carrier'] ?>.GIF">
                                                                  <?php echo $flightUtility->getAirLineName($outBoundBestLastFlight['Carrier']) ?>      
                                                              </div>
                                                              <div class="col-sm-1  flt flySeg"><a>1 Stop</a></div>
                                                              <div class="col-sm-2  flt flySeg">
                                                                  <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime']))) ?>
                                                              </div>
                                                          <div class="clearfix"></div>


                                                         <?php
                                                                  $moreOut = $index->outBound;
                                                                  $moreOutCount = count($moreOut);
                                                                  if ($moreOutCount >= 1) {
                                                                      ?>
                                                          <a role="button" data-toggle="collapse" href="#moreFlights_<?php echo $Key; ?>" aria-expanded="false" 
                                                             accesskey="" class="flySeg" aria-controls="moreFlights_<?php echo $Key; ?>">view more flights</a>
                                                             <!-- More Flights-->
                                                          <div class="collapse" id="moreFlights_<?php echo $Key; ?>" aria-hidden="true" >
                                                            
                                                          <?php
                                                          foreach ($moreOut as $mIndex)
                                                              {
                                                                              $outBoundFirstFlight = $mIndex[0]['@attributes'];
                                                                              $outBoundLast = count($mIndex) - 1;
                                                                              $outBoundLastFlight = $mIndex[$outBoundLast]['@attributes'];
                                                               ?>
                                                              <div class="col-sm-12" style="border-style: solid;border-color: red;">
                                                               <div class="col-sm-1 flt flySeg-child">
                                                                                      <label><input type="radio" class="radio-stroke"  name="selectFlightOut" mode="outBound_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($mIndex)); ?>"></label> 
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-6 flt arw flySeg">
                                                                                      <p> <?php echo $outBoundFirstFlight['Origin'] ?>   <br>
                                                                                          <?php echo date("h:i a", strtotime($outBoundFirstFlight['DepartureTime'])) ?> <br>
                                                                                          <?php echo date("D, d M Y", strtotime($outBoundFirstFlight['DepartureTime'])) ?></p>
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-6 flt flySeg">
                                                                                      <p><?php echo $outBoundLastFlight['Destination'] ?>  <br>
                                                                                          <?php echo date("h:i a", strtotime($outBoundLastFlight['ArrivalTime'])) ?> <br>
                                                                                          <?php echo date("D, d M Y", strtotime($outBoundLastFlight['ArrivalTime'])) ?></p>
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-4 flt drsn flySeg">
                                                                                      <img src="../airimages/<?php echo $outBoundLastFlight['Carrier'] ?>.GIF"></br>
                                                                                      <?php echo $flightUtility->getAirLineName($outBoundLastFlight['Carrier']) ?>      
                                                                                  </div>

                                                                                  <div class="col-sm-1 col-xs-4 flt flySeg"><a>1 Stop</a></div>
                                                                                  <div class="col-sm-2 col-xs-4 flt flySeg">

                                                                                      <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundLastFlight['ArrivalTime']))) ?>

                                                                                  </div>
                                                              </div>

                                                              <?php }?>
                                                            
                                                          </div>
                               <?php }?>                      <!-- More Flights End-->               
                               </div>
                              <!-- Best option OUT  end Flights -->

                                 <!-- Best Option In Start-->
                                 <div class="row" >
                                                              <div class="col-sm-1 flt flySeg"> 
                                                                  <label><input type="radio" class="radio-small" name="selectFlightIn"  mode="inBound_<?php echo $Key ?>"
                                                                                value="<?php echo base64_encode(json_encode($inBoundBestFlight)); ?>" ></label> 
                                                              </div>
                                                          <div class="col-sm-3 col-xs-6 flt arw flySeg">
                                                              <p><?php echo $inBoundBestFirstFlight['Origin'] ?>  <br>
                                                                  <?php echo date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                                                  <?php echo date("D, d M Y", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?></p>
                                                          </div>
                                                          <div class="col-sm-3 col-xs-6 flt flySeg">
                                                              <p><?php echo $inBoundBestLastFlight['Destination'] ?> <br>
                                                                  <?php echo date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                                                  <?php echo date("D, d M Y", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?></p>
                                                          </div>
                                                          <div class="col-sm-3 col-xs-4 flt drsn flySeg">
                                                              <img src="../airimages/<?php echo $inBoundBestLastFlight['Carrier'] ?>.GIF"> 
                                                              <?php echo $flightUtility->getAirLineName($inBoundBestLastFlight['Carrier']) ?>                
                                                          </div>
                                                          <div class="col-sm-1 col-xs-4 flt flySeg"><a>1 Stop</a></div>
                                                          <div class="col-sm-2 col-xs-4 flt flySeg">
                                                              <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime']))) ?>
                                                          </div>
                                                            <div class="clearfix"></div>
                                                            <!--More In flights start  -->
                                                            <div class="row">
                                                                
                                                                  <?php
                                                                  $moreIn = $index->inBound;
                                                                  $moreInCount = count($moreIn);
                                                                  if ($moreInCount >= 1) {
                                                                      ?>
                                                          <a role="button" data-toggle="collapse" href="#moreFlightsIn_<?php echo $Key; ?>" aria-expanded="false" 
                                                             accesskey="" class="flySeg" aria-controls="moreFlightsIn_<?php echo $Key; ?>">view more flights</a>
                                                             <!-- More Flights-->
                                                          <div class="collapse" id="moreFlightsIn_<?php echo $Key; ?>" aria-hidden="true" >
                                                             <?php
                                                          foreach ($moreIn as $mIndexM)
                                                              {
                                                                              $inBoundFirstFlight =$mIndexM[0]['@attributes'];
                                                                              $inBoundLast = count($mIndexM) - 1;
                                                                              $inBoundLastFlight = $mIndexM[$inBoundLast]['@attributes'];
                                                               ?>
                                                              <div class="col-sm-12" style="border-style: solid;border-color: red;">
                                                               <div class="col-sm-1 flt flySeg-child">
                                                                                      <label><input type="radio" class="radio-stroke"  name="selectFlightIn" mode="inBound_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($mIndexM)); ?>"></label> 
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-6 flt arw flySeg">
                                                                                      <p> <?php echo $inBoundFirstFlight['Origin'] ?>   <br>
                                                                                          <?php echo date("h:i a", strtotime($inBoundFirstFlight['DepartureTime'])) ?> <br>
                                                                                          <?php echo date("D, d M Y", strtotime($inBoundFirstFlight['DepartureTime'])) ?></p>
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-6 flt flySeg">
                                                                                      <p><?php echo $inBoundLastFlight['Destination'] ?>  <br>
                                                                                          <?php echo date("h:i a", strtotime($inBoundLastFlight['ArrivalTime'])) ?> <br>
                                                                                          <?php echo date("D, d M Y", strtotime($inBoundLastFlight['ArrivalTime'])) ?></p>
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-4 flt drsn flySeg">
                                                                                      <img src="../airimages/<?php echo $inBoundLastFlight['Carrier'] ?>.GIF"></br>
                                                                                      <?php echo $flightUtility->getAirLineName($inBoundLastFlight['Carrier']) ?>      
                                                                                  </div>

                                                                                  <div class="col-sm-1 col-xs-4 flt flySeg"><a>1 Stop</a></div>
                                                                                  <div class="col-sm-2 col-xs-4 flt flySeg">

                                                                                      <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundFirstFlight['DepartureTime'])), date("h:i a", strtotime($inBoundLastFlight['ArrivalTime']))) ?>

                                                                                  </div>
                                                              </div>

                                                              <?php }}?>
                                                          </div>
                                                             
                                                            </div>
                                                             <!--More In flights start End  -->     
                              </div>
                              <!-- Best Option In End-->
               </div> 
                
<!-- Flight Details Start-->
                <div class="edit-visl">
                    <div class="head">
                        <i class="fa fa-plane"></i> Depart
                    </div>
                                        <!-- *********************************outBound details******************************outBound******************************************************************************--> 
                                        <div class="outBound_<?php echo $Key ?>">   
                                        <?php
                                        foreach ($outBoundBestFlight as $outindex) {
                                            $temp = $outindex['@attributes'];
                                            error_log(print_r($temp,true));
                                            ?>
                                            <div class="row flt-dtl <?php echo "outBound_" . $Key ?>">
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
                                                </div>

                                            </div>

                                            <div class="vgn"><p><img src="../airimages/<?php echo $flightUtility->getAirLineName($temp['Carrier']) ?>.GIF"> 
                                                    <?php echo $flightUtility->getAirLineName($temp['Carrier']) ?> -
                                                    <?php echo $temp['Carrier'] . $temp['Equipment'] ?> <a> 
                                                        <?php echo $temp[0]['CabinClass'] . ' ' . $temp[0]['BookingCode'] ?></a> - Aircraft  <?php echo $temp['FlightNumber'] ?></p>
                                            </div>


                                            <?php
                                        }
                                        $totaltime = $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime'])))
                                        ?>

                                        <div class="btm">
                                            <i class="fa fa-clock-o"></i> 
                                            <strong>TOTAL DURATION</strong> <?php echo $totaltime ?>
                                        </div>
                                        </div>
                                        <!-- ***************************************In bound details*************************InBound*****************************************************************************-->                   

                                                     <div class="head"><i class="fa fa-plane"></i> Return</div>
                                               <div class="<?php echo "inBound_" . $Key ?>">
                                        <?php
                                        
                                        foreach ($inBoundBestFlight as $jindex) {
                                            $temp = $jindex['@attributes'];
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
                                                        <?php echo $temp[0]['CabinClass'] . ' ' . $temp[0]['BookingCode'] ?></a> - Aircraft  <?php echo $inBoundBestLastFlight['FlightNumber'] ?></p>
                                            </div>


                                        <?php } $totaltime = $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime']))) ?>

                                        <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong><?php echo $totaltime ?></div>
                                               </div>
                                        
                <div class="clearfix"></div>
           
            </div>
 <!-- Flight Details End-->
 <div class="clearfix"></div>
        </div>
        <!--************************************** Price Details End*****************************-->  
        
         <?php $basePrice=$index->airPrincingInfo ?>
            <div class="col-sm-3 show-flight-prize">
                <div class="flt-prz">

                    <label>Per Adult Return</label>

                    <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz"
                           value="<?php echo $basePrice['TotalPrice'] ?>" readonly="">

                    

                    <form name="frmAvail" action="../booking" method="post">
                    
                    <!-- Rajib added -->
                    <input type="hidden" name="connectiondata" value="Tjs=">
                    <!-- Rajib added -->

                    <input type="hidden" name="outbound" class="book_out<?php echo $Key ?>" value="">
                    <input type="hidden" name="inbound" class="book_in<?php echo $Key ?>" value="">
                    
                    <input type="hidden" name="priceData" value="<?php echo base64_encode(serialize($basePrice)) ?>">

                    <input type="hidden" name="searchdata" value="==">

                    <input type="hidden" name="searchmode" value="">

                    <button type="submit" name="book"  key="<?php echo $Key ?>">Book Now</button>

                    </form>

                    <form name="frmFL" id="frmFL1" method="post" action="../farerule.php" target="_blank">

                    <input type="hidden" name="k" value="gws-eJxNTtEKAjEM+5gj72knzL3Nqx7KvCmoyL34/59x3U7BQBNKQtqcs1Iidyr5DwM+Q3qjvgyoUJ+xFCQNCvFlAck9bvq4y3wt+FZEt2q3N5UetGAJxKQTN6cBS2ebx19rOwnPodHp7FIPx6ddtJ0Siga6EeFvrUp/J3s=">

                    <input type="hidden" name="ref" value="ErvqNukJ0BKAJBnh7AAAAA==">

                    <p><a class="shw2" onclick="jQuery('#frmFL1').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>

                    </form>

                    <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>

                </div>
                <div class="clearfix"></div>
            </div>
           <div class="clearfix"></div>
     </div>
   </div>


                    <?php }?>
<!-- ******************************* flights* End************************************************ -->               
                </div>
            </div>
        </div>
    </div>


<?php } 


function moreFlights($more)
{?>
    <div class="col-sm-12">
        <div class="show-flight">                    
            <div class="col-sm-9">

                <div class="visl">

                                    <!-- Best option OUT Flights -->
                               <div class="row brd-btm "><!--Added CSS-->  
                                                              <div class="col-sm-1 flt flySeg"> 
                                                                  <label><input type="radio" class="radio-small" name="roud_out_sel"  mode="outBound_<?php echo $Key ?>"
                                                                                value="<?php echo base64_encode(json_encode($outBoundBestFlight)); ?>" > vae</label> 
                                                              </div>
                                                              <div class="col-sm-3 flt arw flySeg">
                                                                  <p> <?php echo $outBoundBestFirstFlight['Origin'] ?>   <br>
                                                                      <?php echo date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                                                      <?php echo date("D, d M Y", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?></p>
                                                              </div>
                                                              <div class="col-sm-3  flt flySeg">
                                                                  <p><?php echo $outBoundBestLastFlight['Destination'] ?>  <br>
                                                                      <?php echo date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                                                      <?php echo date("D, d M Y", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?></p>
                                                              </div>
                                                              <div class="col-sm-3 flt drsn flySeg">
                                                                  <img src="../airimages/<?php echo $outBoundBestLastFlight['Carrier'] ?>.GIF">
                                                                  <?php echo $flightUtility->getAirLineName($outBoundBestLastFlight['Carrier']) ?>      
                                                              </div>
                                                              <div class="col-sm-1  flt flySeg"><a>1 Stop</a></div>
                                                              <div class="col-sm-2  flt flySeg">
                                                                  <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime']))) ?>
                                                              </div>
                                                          <div class="clearfix"></div>


                                                         <?php
                                                                  $moreOut = $index->outBound;
                                                                  $moreOutCount = count($moreOut);
                                                                  if ($moreOutCount >= 1) {
                                                                      ?>
                                                          <a role="button" data-toggle="collapse" href="#moreFlights_<?php echo $Key; ?>" aria-expanded="false" 
                                                             accesskey="" class="flySeg" aria-controls="moreFlights_<?php echo $Key; ?>">view more flights</a>
                                                             <!-- More Flights-->
                                                          <div class="collapse" id="moreFlights_<?php echo $Key; ?>" aria-hidden="true" >
                                                            
                                                          <?php
                                                          foreach ($moreOut as $mIndex)
                                                              {
                                                                              $outBoundFirstFlight = $mIndex[0]['@attributes'];
                                                                              $outBoundLast = count($mIndex) - 1;
                                                                              $outBoundLastFlight = $mIndex[$outBoundLast]['@attributes'];
                                                               ?>
                                                              <div class="col-sm-12" style="border-style: solid;border-color: red;">
                                                               <div class="col-sm-1 flt flySeg-child">
                                                                                      <label><input type="radio" class="radio-stroke"  name="selectFlightOut" mode="outBound_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($mIndex)); ?>"></label> 
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-6 flt arw flySeg">
                                                                                      <p> <?php echo $outBoundFirstFlight['Origin'] ?>   <br>
                                                                                          <?php echo date("h:i a", strtotime($outBoundFirstFlight['DepartureTime'])) ?> <br>
                                                                                          <?php echo date("D, d M Y", strtotime($outBoundFirstFlight['DepartureTime'])) ?></p>
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-6 flt flySeg">
                                                                                      <p><?php echo $outBoundLastFlight['Destination'] ?>  <br>
                                                                                          <?php echo date("h:i a", strtotime($outBoundLastFlight['ArrivalTime'])) ?> <br>
                                                                                          <?php echo date("D, d M Y", strtotime($outBoundLastFlight['ArrivalTime'])) ?></p>
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-4 flt drsn flySeg">
                                                                                      <img src="../airimages/<?php echo $outBoundLastFlight['Carrier'] ?>.GIF"></br>
                                                                                      <?php echo $flightUtility->getAirLineName($outBoundLastFlight['Carrier']) ?>      
                                                                                  </div>

                                                                                  <div class="col-sm-1 col-xs-4 flt flySeg"><a>1 Stop</a></div>
                                                                                  <div class="col-sm-2 col-xs-4 flt flySeg">

                                                                                      <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundLastFlight['ArrivalTime']))) ?>

                                                                                  </div>
                                                              </div>

                                                              <?php }?>
                                                            
                                                          </div>
                               <?php }?>                      <!-- More Flights End-->               
                               </div>
                              <!-- Best option OUT  end Flights -->

                                 <!-- Best Option In Start-->
                                 <div class="row" >
                                                              <div class="col-sm-1 flt flySeg"> 
                                                                  <label><input type="radio" class="radio-small" name="selectFlightIn"  mode="inBound_<?php echo $Key ?>"
                                                                                value="<?php echo base64_encode(json_encode($inBoundBestFlight)); ?>" ></label> 
                                                              </div>
                                                          <div class="col-sm-3 col-xs-6 flt arw flySeg">
                                                              <p><?php echo $inBoundBestFirstFlight['Origin'] ?>  <br>
                                                                  <?php echo date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                                                  <?php echo date("D, d M Y", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?></p>
                                                          </div>
                                                          <div class="col-sm-3 col-xs-6 flt flySeg">
                                                              <p><?php echo $inBoundBestLastFlight['Destination'] ?> <br>
                                                                  <?php echo date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                                                  <?php echo date("D, d M Y", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?></p>
                                                          </div>
                                                          <div class="col-sm-3 col-xs-4 flt drsn flySeg">
                                                              <img src="../airimages/<?php echo $inBoundBestLastFlight['Carrier'] ?>.GIF"> 
                                                              <?php echo $flightUtility->getAirLineName($inBoundBestLastFlight['Carrier']) ?>                
                                                          </div>
                                                          <div class="col-sm-1 col-xs-4 flt flySeg"><a>1 Stop</a></div>
                                                          <div class="col-sm-2 col-xs-4 flt flySeg">
                                                              <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime']))) ?>
                                                          </div>
                                                            <div class="clearfix"></div>
                                                            <!--More In flights start  -->
                                                            <div class="row">
                                                                
                                                                  <?php
                                                                  $moreIn = $index->inBound;
                                                                  $moreInCount = count($moreIn);
                                                                  if ($moreInCount >= 1) {
                                                                      ?>
                                                          <a role="button" data-toggle="collapse" href="#moreFlightsIn_<?php echo $Key; ?>" aria-expanded="false" 
                                                             accesskey="" class="flySeg" aria-controls="moreFlightsIn_<?php echo $Key; ?>">view more flights</a>
                                                             <!-- More Flights-->
                                                          <div class="collapse" id="moreFlightsIn_<?php echo $Key; ?>" aria-hidden="true" >
                                                             <?php
                                                          foreach ($moreIn as $mIndexM)
                                                              {
                                                                              $inBoundFirstFlight =$mIndexM[0]['@attributes'];
                                                                              $inBoundLast = count($mIndexM) - 1;
                                                                              $inBoundLastFlight = $mIndexM[$inBoundLast]['@attributes'];
                                                               ?>
                                                              <div class="col-sm-12" style="border-style: solid;border-color: red;">
                                                               <div class="col-sm-1 flt flySeg-child">
                                                                                      <label><input type="radio" class="radio-stroke"  name="selectFlightIn" mode="inBound_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($mIndexM)); ?>"></label> 
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-6 flt arw flySeg">
                                                                                      <p> <?php echo $inBoundFirstFlight['Origin'] ?>   <br>
                                                                                          <?php echo date("h:i a", strtotime($inBoundFirstFlight['DepartureTime'])) ?> <br>
                                                                                          <?php echo date("D, d M Y", strtotime($inBoundFirstFlight['DepartureTime'])) ?></p>
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-6 flt flySeg">
                                                                                      <p><?php echo $inBoundLastFlight['Destination'] ?>  <br>
                                                                                          <?php echo date("h:i a", strtotime($inBoundLastFlight['ArrivalTime'])) ?> <br>
                                                                                          <?php echo date("D, d M Y", strtotime($inBoundLastFlight['ArrivalTime'])) ?></p>
                                                                                  </div>

                                                                                  <div class="col-sm-3 col-xs-4 flt drsn flySeg">
                                                                                      <img src="../airimages/<?php echo $inBoundLastFlight['Carrier'] ?>.GIF"></br>
                                                                                      <?php echo $flightUtility->getAirLineName($inBoundLastFlight['Carrier']) ?>      
                                                                                  </div>

                                                                                  <div class="col-sm-1 col-xs-4 flt flySeg"><a>1 Stop</a></div>
                                                                                  <div class="col-sm-2 col-xs-4 flt flySeg">

                                                                                      <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundFirstFlight['DepartureTime'])), date("h:i a", strtotime($inBoundLastFlight['ArrivalTime']))) ?>

                                                                                  </div>
                                                              </div>

                                                              <?php }}?>
                                                          </div>
                                                             
                                                            </div>
                                                             <!--More In flights start End  -->     
                              </div>
                              <!-- Best Option In End-->
               </div> 
                
<!-- Flight Details Start-->
                <div class="edit-visl">
                    <div class="head">
                        <i class="fa fa-plane"></i> Depart
                    </div>
                                        <!-- *********************************outBound details******************************outBound******************************************************************************--> 
                                        <div class="outBound_<?php echo $Key ?>">   
                                        <?php
                                        foreach ($outBoundBestFlight as $outindex) {
                                            $temp = $outindex['@attributes'];
                                            error_log(print_r($temp,true));
                                            ?>
                                            <div class="row flt-dtl <?php echo "outBound_" . $Key ?>">
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
                                                </div>

                                            </div>

                                            <div class="vgn"><p><img src="../airimages/<?php echo $flightUtility->getAirLineName($temp['Carrier']) ?>.GIF"> 
                                                    <?php echo $flightUtility->getAirLineName($temp['Carrier']) ?> -
                                                    <?php echo $temp['Carrier'] . $temp['Equipment'] ?> <a> 
                                                        <?php echo $temp[0]['CabinClass'] . ' ' . $temp[0]['BookingCode'] ?></a> - Aircraft  <?php echo $temp['FlightNumber'] ?></p>
                                            </div>


                                            <?php
                                        }
                                        $totaltime = $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime'])))
                                        ?>

                                        <div class="btm">
                                            <i class="fa fa-clock-o"></i> 
                                            <strong>TOTAL DURATION</strong> <?php echo $totaltime ?>
                                        </div>
                                        </div>
                                        <!-- ***************************************In bound details*************************InBound*****************************************************************************-->                   

                                                     <div class="head"><i class="fa fa-plane"></i> Return</div>
                                               <div class="<?php echo "inBound_" . $Key ?>">
                                        <?php
                                        
                                        foreach ($inBoundBestFlight as $jindex) {
                                            $temp = $jindex['@attributes'];
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
                                                        <?php echo date("D, d M Y", strtotime($temp['ArrivalTime'])) ?>
                                                     <?php if($bestflyCont>1)
                                                            { ?>
                                                                    <p> 

                                                                        <span>
                                                                            <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#moreTimeOut<?php echo $index ?>" aria-expanded="false" aria-controls="moreFlightsOut">more timings</a>

                                                                        </span>
                                                                    </p>
                                                        <?php } ?>
                                                    </p>
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
                                                        <?php echo $temp[0]['CabinClass'] . ' ' . $temp[0]['BookingCode'] ?></a> - Aircraft  <?php echo $inBoundBestLastFlight['FlightNumber'] ?></p>
                                            </div>


                                        <?php } $totaltime = $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime']))) ?>

                                        <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong><?php echo $totaltime ?></div>
                                               </div>
                                        
                <div class="clearfix"></div>
           
            </div>
 <!-- Flight Details End-->
 <div class="clearfix"></div>
        </div>
        <!--************************************** Price Details End*****************************-->  
        
         <?php $basePrice=$index->airPrincingInfo ?>
            <div class="col-sm-3 show-flight-prize">
                <div class="flt-prz">

                    <label>Per Adult Return</label>

                    <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz"
                           value="<?php echo $basePrice['TotalPrice'] ?>" readonly="">

                    

                    <form name="frmAvail" action="../booking" method="post">
                    
                    <!-- Rajib added -->
                    <input type="hidden" name="connectiondata" value="Tjs=">
                    <!-- Rajib added -->

                    <input type="hidden" name="outbound" class="book_out<?php echo $Key ?>" value="">
                    <input type="hidden" name="inbound" class="book_in<?php echo $Key ?>" value="">
                    
                    <input type="hidden" name="priceData" value="<?php echo base64_encode(serialize($basePrice)) ?>">

                    <input type="hidden" name="searchdata" value="==">

                    <input type="hidden" name="searchmode" value="">

                    <button type="submit" name="book"  key="<?php echo $Key ?>">Book Now</button>

                    </form>

                    <form name="frmFL" id="frmFL1" method="post" action="../farerule.php" target="_blank">

                    <input type="hidden" name="k" value="gws-eJxNTtEKAjEM+5gj72knzL3Nqx7KvCmoyL34/59x3U7BQBNKQtqcs1Iidyr5DwM+Q3qjvgyoUJ+xFCQNCvFlAck9bvq4y3wt+FZEt2q3N5UetGAJxKQTN6cBS2ebx19rOwnPodHp7FIPx6ddtJ0Siga6EeFvrUp/J3s=">

                    <input type="hidden" name="ref" value="ErvqNukJ0BKAJBnh7AAAAA==">

                    <p><a class="shw2" onclick="jQuery('#frmFL1').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>

                    </form>

                    <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>

                </div>
                <div class="clearfix"></div>
            </div>
           <div class="clearfix"></div>
     </div>
   </div>

<?php }
?>


