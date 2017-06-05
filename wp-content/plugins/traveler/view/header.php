 <?php if(count($moreFlyList)>1)
     {?>
      <div class="container collapse tiket_body_r" id="moreFlys<?php echo $Key ?>" aria-hidden=true accesskey="">
                      <?php
                      foreach ($moreFlyList as $m=>$moreFly) {
                          $m_outBoundBestFlight = $moreFly->bastOptionOut;
                          $m_outBoundBestFlightCount = count($m_outBoundBestFlight) - 1;
                          $m_outBoundBestFirstFlight = $m_outBoundBestFlight[0]['@attributes'];
                          $m_outBoundBestLastFlight = $m_outBoundBestFlight[$m_outBoundBestFlightCount]['@attributes'];
                          
                          $m_moreTimeOut=$moreFly->outBound;
                          $m_moreTimeOutCount=count($m_moreTimeOut);


                          $m_inBoundBestFlight = $index->bastOptionIn;
                          $m_inBoundBestFlightCount = count($m_inBoundBestFlight) - 1;
                          $m_inBoundBestFirstFlight = $m_inBoundBestFlight[0]['@attributes'];
                          $m_inBoundBestLastFlight = $m_inBoundBestFlight[$m_inBoundBestFlightCount]['@attributes'];
                          ?>

                          <div class="row ">
                              <div class="col-lg-9 ">
                                  <div class="row tkt_dts_r">
                                      <div class="col-sm-3 col-xs-6 col-lg-3 flt  flySeg">
                                                          <p><?php echo $m_outBoundBestFirstFlight['Origin'] ?>  <br>
                                                              <input type="radio" class="radio-small" name="selectFlightIn"  mode="m_outBound_<?php echo $Key.'_'.$m ?>"
                                                                     value="<?php echo base64_encode(json_encode($m_outBoundBestFirstFlight)); ?>" >
                                                              <?php echo date("h:i a", strtotime($m_outBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                                              <?php echo date("D, d M Y", strtotime($m_outBoundBestFirstFlight['DepartureTime'])) ?>

                                                              <?php if($m_moreTimeOutCount> 1 ) { ?>
                                                              <span>
                                                                  <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#m_moreTimeOut<?php echo $m?>" aria-expanded="false" aria-controls="m_moreTimeOut<?php echo $m?>">more timings</a>
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
                                    <?php if($m_moreTimeOutCount> 1 ) { ?>
                                  <div class=" row collapse moreFlights" id="m_moreTimeOut<?php echo $m?>" aria-hidden=true accesskey="" >
                                     more more 
                                  </div>
                                   <?php } ?>
                                  
                                  <div class="row tkt_dts_r">
                                      <div class="col-sm-3 col-xs-6 col-lg-3 flt  flySeg">
                                          <p><?php echo $m_inBoundBestFirstFlight['Origin'] ?>  <br>
                                              <input type="radio" class="radio-small" name="selectFlightIn"  mode="inBound_<?php echo $Key ?>"
                                                     value="<?php echo base64_encode(json_encode($m_inBoundBestFirstFlight)); ?>" >
                                              <?php echo date("h:i a", strtotime($m_inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                              <?php echo date("D, d M Y", strtotime($m_inBoundBestFirstFlight['DepartureTime'])) ?>
                                             
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
                                  
                                  </div>
                         




                              <div class="col-lg-3 prz_dts_r">
                                  we are button
  <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>
                              </div>
                              
                               </div>
            
                              
                        
          
          
        
       <?php } ?>
       </div> <?php } ?>




 <!--More timing out bound start-->
                    <div class=" row collapse moreFlights" id="moreTimeOut<?php echo $Key?>" aria-hidden=true accesskey="" >
                        <div class="col-lg-9 ">
                            <div class="row tkt_dts_r_m">
                                <div class="col-sm-3 col-xs-6 col-lg-3 flt  flySeg">
                                        <p><?php echo $inBoundBestFirstFlight['Origin'] ?>  <br>
                                                <input type="radio" class="radio-small" name="selectFlightIn"  mode="inBound_<?php echo $Key ?>"
                                                       value="<?php echo base64_encode(json_encode($inBoundBestFlight)); ?>" >
                                                <?php echo date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                                <?php echo date("D, d M Y", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?>
                                        </p>
                                </div>
                                <div class="col-sm-3 col-xs-6 col-lg-3 flt flySeg">
                                            <p><?php echo $inBoundBestLastFlight['Destination'] ?> <br>
                                                <?php echo date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                                <?php echo date("D, d M Y", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?></p>
                                </div>
                                <div class="col-sm-3 col-xs-4 col-lg-3 flt drsn flySeg">
                                            <img src="../airimages/<?php echo $inBoundBestLastFlight['Carrier'] ?>.GIF"><br> 
                                            <?php echo $flightUtility->getAirLineName($inBoundBestLastFlight['Carrier']) ?>                
                                </div>
                                <div class="col-sm-1 col-xs-4 col-lg-1 flt flySeg">1Stop</div>
                                <div class="col-sm-2 col-xs-4 col-lg-2 flt flySeg">
                                            <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime']))) ?>
                                </div>
                            </div>
                        </div>
                 </div>
                  <!--More timing out bound End--> 
                  
                   <div class="col-lg-9 ">
                               <div class="row tkt_dts_r_m">
                                   <div class="col-sm-3 col-xs-6 col-lg-3 flt  flySeg">
                                       <p><?php echo $inBoundBestFirstFlight['Origin'] ?>  <br>
                                           <input type="radio" class="radio-small" name="selectFlightIn"  mode="inBound_<?php echo $Key ?>"
                                                  value="<?php echo base64_encode(json_encode($inBoundBestFlight)); ?>" >
                                           <?php echo date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?> <br>
                                           <?php echo date("D, d M Y", strtotime($inBoundBestFirstFlight['DepartureTime'])) ?></p>
                                   </div>
                                   <div class="col-sm-3 col-xs-6 col-lg-3 flt flySeg">
                                       <p><?php echo $inBoundBestLastFlight['Destination'] ?> <br>
                                           <?php echo date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?> <br>
                                           <?php echo date("D, d M Y", strtotime($inBoundBestLastFlight['ArrivalTime'])) ?></p>
                                   </div>
                                   <div class="col-sm-3 col-xs-4 col-lg-3 flt drsn flySeg">
                                       <img src="../airimages/<?php echo $inBoundBestLastFlight['Carrier'] ?>.GIF"><br> 
                                       <?php echo $flightUtility->getAirLineName($inBoundBestLastFlight['Carrier']) ?>                
                                   </div>
                                   <div class="col-sm-1 col-xs-4 col-lg-1 flt flySeg">1Stop</div>
                                   <div class="col-sm-2 col-xs-4 col-lg-2 flt flySeg">
                                       <?php echo $flightUtility->getTimeDiff(date("h:i a", strtotime($inBoundBestFirstFlight['DepartureTime'])), date("h:i a", strtotime($inBoundBestLastFlight['ArrivalTime']))) ?>
                                   </div>
                                </div>
                           </div>
                  
                  
                       <div class="<?php echo "inBound_" . $Key ?>">
                                        <?php
                                        
                                        foreach ($itemInList as $jindex) {
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
                                                        <?php echo $temp[0]['CabinClass'] . ' ' . $temp[0]['BookingCode'] ?></a> - Aircraft  <?php echo $temp['FlightNumber'] ?></p>
                                            </div>


                                        <?php } 
                                        $totaltime = $flightUtility->getTimeDiff(date("h:i a", strtotime($itemInList[$itemInListCount]['@attributes']['ArrivalTime'])), date("h:i a", strtotime($itemInList[0]['@attributes']['DepartureTime']))) ?>

                                        <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong><?php echo $totaltime ?></div>
                                               </div>
                  
<!--                  <div class="outBound_<?php echo $Key ?>">   
                                        <?php
                                        foreach ($itemOutList as $outindex) {
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
                                        $totaltime = $flightUtility->getTimeDiff(date("h:i a", strtotime($itemOutList[$itemOutListCount]['@attributes']['ArrivalTime'])), date("h:i a", strtotime($itemOutList[0]['@attributes']['DepartureTime'])))
                                        ?>

                                        <div class="btm">
                                            <i class="fa fa-clock-o"></i> 
                                            <strong>TOTAL DURATION</strong> <?php echo $totaltime ?>
                                        </div>
                                        </div>-->