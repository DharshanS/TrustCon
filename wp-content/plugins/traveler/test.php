<!--<script type="application/javascript" src='http://localhost:8080/travel/wp-content/plugins/traveler/js/tr-roundtrip.js'/>
<?php
require_once(PLUG_DIR . 'models/RoundTrip.php');
include_once (PLUG_DIR . 'utility/FlightUtility.php');
$test = "WellCome";
function roundTripView($response) {
    error_log("ROUND TRIP VIEW");
    $flightUtility = new FlightUtility();
    error_log(print_r($response, true));
} ?> -->
<div id="resultloader">
    <div class="sky-bg">
        <div class="container">
            <div class="row">
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
                   <div class="col-sm-12"><!--1-->
                            <div class="show-flight"><!--2-->                    
                                <div class="col-sm-9"><!--3-->
                                    <div class="visl"><!--4-->
                                        <div class="row brd-btm "><!--5-->
                                            <div class="col-sm-12"><!--6 outBound-->
                                                <div class="col-sm-1 flt flySeg"><!--7--> 
                                                        <label><input type="radio" class="radio-small" name="selectFlight"  mode="outBound_<?php echo $Key ?>"
                                                                      value="<?php echo base64_encode(json_encode($outBoundBestFlight)); ?>" ></label> 
                                                    <div class="col-sm-3 flt arw flySeg"><!-- 8-->
                                                            <p> <?php echo $outBoundBestFirstFlight['Origin'] ?>   <br>
                                                                <?php echo date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                                                <?php echo date("D, d M Y", strtotime($outBoundBestFirstFlight['DepartureTime'])) ?></p>
                                                    </div><!-- 8-->
                                                     <div class="col-sm-3  flt flySeg"><!--9-->
                                                    <p><?php echo $outBoundBestLastFlight['Destination'] ?>  <br>
                                                        <?php echo date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                                        <?php echo date("D, d M Y", strtotime($outBoundBestLastFlight['ArrivalTime'])) ?></p>
                                                    </div><!--9-->
                                                     <div class="col-sm-3 flt drsn flySeg"><!--10-->
                                                    <img src="../airimages/<?php echo $outBoundBestLastFlight['Carrier'] ?>.GIF">
                                                    <?php echo $flightUtility->getAirLineName($outBoundBestLastFlight['Carrier']) ?>      
                                                    </div><!--10-->
                                                    <div class="col-sm-1  flt flySeg"><!--11-->
                                                        <a>1 Stop</a>
                                                    </div><!--11-->
                                                    <div class="col-sm-2  flt flySeg"><!--12-->
                                                    <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime']))) ?>
                                                    </div><!--12-->
                                                    <div class="row"><!--13-->
                                                       <div class="more"><!--14-->
                                                            <?php
                                                    $moreOut = $index->outBound;
                                                    $moreOutCount = count($moreOut);
                                                    if ($moreOutCount >= 1) 
                                                        {?>
                                                           <div class="row"><!--15-->
                                                            <a role="button" data-toggle="collapse" href="#moreFlights_<?php echo $Key; ?>" aria-expanded="false" 
                                                               accesskey="    "aria-controls="moreFlights_<?php echo $Key; ?>">view more flights</a>
                                                            </div> <!--15-->
                                                            <div class="collapse" id="moreFlights_<?php echo $Key; ?>" aria-hidden="true" > <!--16-->
                                                                  <?php
                                                            foreach ($moreOut as $mIndex)
                                                                {
                                                                $outBoundFirstFlight = $mIndex[0]['@attributes'];
                                                                $outBoundLast = count($mIndex) - 1;
                                                                $outBoundLastFlight = $mIndex[$outBoundLast]['@attributes'];
                                                                ?>
                                                                <div class="row col-sm-12"><!-- 17 -->
                                                                    <div class="col-sm-1 flt flySeg"><!--18 -->
                                                                        <label><input type="radio" class="radio-stroke"  name="selectFlight" mode="outBound_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($mIndex)); ?>"></label> 
                                                                    </div><!--18 -->
                                                                     <div class="col-sm-2 col-xs-6 flt arw flySeg"><!--19-->
                                                                        <p> <?php echo $outBoundFirstFlight['Origin'] ?>   <br>
                                                                            <?php echo date("h:i a", strtotime($outBoundFirstFlight['DepartureTime'])) ?> <br>
                                                                            <?php echo date("D, d M Y", strtotime($outBoundFirstFlight['DepartureTime'])) ?></p>
                                                                    </div><!--19-->
                                                                     <div class="col-sm-2 col-xs-6 flt flySeg"><!-- 20-->
                                                                        <p><?php echo $outBoundLastFlight['Destination'] ?>  <br>
                                                                            <?php echo date("h:i a", strtotime($outBoundLastFlight['ArrivalTime'])) ?> <br>
                                                                            <?php echo date("D, d M Y", strtotime($outBoundLastFlight['ArrivalTime'])) ?></p>
                                                                    </div><!-- 20-->
                                                                    
                                                                     <div class="col-sm-2 col-xs-4 flt drsn flySeg"><!-- 21-->
                                                                        <img src="../airimages/<?php echo $outBoundLastFlight['Carrier'] ?>.GIF"></br>
                                                                        <?php echo $flightUtility->getAirLineName($outBoundLastFlight['Carrier']) ?>      
                                                                    </div><!-- 21-->
                                                                    
                                                                    <div class="col-sm-1 col-xs-4 flt flySeg"><!-- 22-->
                                                                        <a>1 Stop</a>
                                                                    </div><!-- 22-->
                                                                    
                                                                    <div class="col-sm-2 col-xs-4 flt flySeg"><!-- 23-->
                                                                      <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundLastFlight['ArrivalTime']))) ?>
                                                                    </div><!-- 23--> 
                                                                </div><!-- 17 -->
                                                                <?php }?>
                                                            </div>    <!--16-->
                                                    <?php }?>
                                                       </div><!--14-->
                                                    </div><!--13-->
                                                    
                                                    
                                                </div> <!--7-->
                                            </div>  <!-- 6 outBound- end  -->
                                            
                                        </div> <!-- 5-->
                                    </div><!-- 4-->
                                </div><!-- 3-->
                            </div><!-- 2-->
                   </div><!-- 1  -->
                <?php }?>
            </div>
        </div>
    </div>
</div>    