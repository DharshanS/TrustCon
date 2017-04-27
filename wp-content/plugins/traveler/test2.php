<!--
                                            <div class="row">  More Flights 
                                                 <div class="more">  More Flights sTART !
                                                        
                                                    <?php
                                                    $moreOut = $index->outBound;
                                                    $moreOutCount = count($moreOut);
                                                    if ($moreOutCount >= 1) {
                                                        ?>
                                                        <div class="row">
                                                            <a role="button" data-toggle="collapse" href="#moreFlights_<?php echo $Key; ?>" aria-expanded="false" 
                                                               accesskey="    "aria-controls="moreFlights_<?php echo $Key; ?>">view more flights</a>
                                                        </div> 

                                                        <div class="collapse" id="moreFlights_<?php echo $Key; ?>" aria-hidden="true" >

                                                               *************************************************************** 

                                                            <?php
                                                            foreach ($moreOut as $mIndex)
                                                                {
                                                                $outBoundFirstFlight = $mIndex[0]['@attributes'];
                                                                $outBoundLast = count($mIndex) - 1;
                                                                $outBoundLastFlight = $mIndex[$outBoundLast]['@attributes'];
                                                                ?>

                                                                <div class="row col-sm-12">



                                                                    <div class="col-sm-1 flt flySeg">
                                                                        <label><input type="radio" class="radio-stroke"  name="selectFlight" mode="outBound_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($mIndex)); ?>"></label> 
                                                                    </div>

                                                                    <div class="col-sm-2 col-xs-6 flt arw flySeg">
                                                                        <p> <?php echo $outBoundFirstFlight['Origin'] ?>   <br>
                                                                            <?php echo date("h:i a", strtotime($outBoundFirstFlight['DepartureTime'])) ?> <br>
                                                                            <?php echo date("D, d M Y", strtotime($outBoundFirstFlight['DepartureTime'])) ?></p>
                                                                    </div>

                                                                    <div class="col-sm-2 col-xs-6 flt flySeg">
                                                                        <p><?php echo $outBoundLastFlight['Destination'] ?>  <br>
                                                                            <?php echo date("h:i a", strtotime($outBoundLastFlight['ArrivalTime'])) ?> <br>
                                                                            <?php echo date("D, d M Y", strtotime($outBoundLastFlight['ArrivalTime'])) ?></p>
                                                                    </div>

                                                                    <div class="col-sm-2 col-xs-4 flt drsn flySeg">
                                                                        <img src="../airimages/<?php echo $outBoundLastFlight['Carrier'] ?>.GIF"></br>
                                                                        <?php echo $flightUtility->getAirLineName($outBoundLastFlight['Carrier']) ?>      
                                                                    </div>

                                                                    <div class="col-sm-1 col-xs-4 flt flySeg"><a>1 Stop</a></div>
                                                                    <div class="col-sm-2 col-xs-4 flt flySeg">

                                                                        <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundLastFlight['ArrivalTime']))) ?>

                                                                    </div>

                                                                </div>
                                                            <?php } ?>
                                                     
                                                            <?php } ?>
                                                        </div> 
                                                 </div>  More Flights End !-- >
                                            </div>
                                            <!--*******************************************************************************************************************************************   
                                       



                                        <div class="row">
                                            <div class="col-sm-3 col-xs-6 flt arw">
                                                <p><?php echo $inBoundBestFirstFlight['Origin'] ?>  <br>
                                                    <?php echo date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                                    <?php echo date("D, d M Y", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?></p>
                                            </div>

                                            <div class="col-sm-3 col-xs-6 flt">
                                                <p><?php echo $inBoundBestLastFlight['Destination'] ?> <br>
                                                    <?php echo date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                                    <?php echo date("D, d M Y", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?></p>
                                            </div>

                                            <div class="col-sm-3 col-xs-4 flt drsn">
                                                <img src="../airimages/<?php echo $inBoundBestLastFlight['Carrier'] ?>.GIF"> 
                                                <?php echo $flightUtility->getAirLineName($inBoundBestLastFlight['Carrier']) ?>                
                                            </div>

                                            <div class="col-sm-1 col-xs-4 flt"><a>1 Stop</a></div>
                                            <div class="col-sm-2 col-xs-4 flt">
                                                <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime']))) ?>
                                            </div>
                                        </div>

                                    </div>-->
                                                    
                                                    
                                                        <div class="edit-visl">
                                        <div class="head"><i class="fa fa-plane"></i> Depart</div>
                                        <!-- ***************************************************************outBound******************************************************************************--> 
                                        <?php
                                        foreach ($outBoundBestFlight as $outindex) {
                                            $temp = $outindex['@attributes'];
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
                                                        <?php echo $temp[0]['CabinClass'] . ' ' . $temp[0]['BookingCode'] ?></a> - Aircraft  <?php echo $outBoundBestFirstFlight['FlightNumber'] ?></p>
                                            </div>


                                            <?php
                                        }
                                        $totaltime = $flightUtility->getTimeDiff(date("h:i a", strtotime($outBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($outBoundBestLastFlight['ArrivalTime'])))
                                        ?>

                                        <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> <?php echo $totaltime ?></div>

                                        <!-- ****************************************************************InBound*****************************************************************************-->                   

                                        <div class="head"><i class="fa fa-plane"></i> Return</div>
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
                                        <!-- **************************************************************************InBoundEnd*******************************************************************--> 
                                    </div>