<!--<script type="application/javascript" src='http://localhost:8080/travel/wp-content/plugins/traveler/js/tr-search.js'/>-->
<?php
include(PLUG_DIR . 'utility/FlightUtility.php'); ?>

<div class="sky-bg"><?php
function genarate_html_view($flyDetails) {


    
    $flightUtility =new FlightUtility();
    
    foreach ($flyDetails as $Key=>$outerIndex)
        {
      $flyDetails=$outerIndex->bestFly->segDetails;
        ?>

        <div class="col-sm-12">
            <!--flight Show Start-->
            <div class="show-flight">  
                <!--col-sm-8 Start-->
                <div class="col-sm-8">
                    <!--**********************************************************flights start**********************************************************--->
                    <div class="visl">
                        <!-- row start-->
                        <div class="row"> 
                            
                           
                            <?php 
                            $flyCount=count($flyDetails);
                            $firstfly=$flyDetails[0];
                            $firstflyCount=count($firstfly)-1;
                            $firstflyTop=$firstfly[0];
                            $fistflyLast=$firstfly[$firstflyCount];

                            ?>
                            <div class="col-sm-3 col-xs-6 flt-single arw">
                                <p><?php echo ''?><br>
                                    <?php echo date("h:i a", strtotime($firstflyTop['DepartureTime'])) ?> <br>
                                    <?php echo date("D, d M Y", strtotime($firstflyTop['DepartureTime']))?>
                                </p>
                            </div>
                            <div class="col-sm-3 col-xs-6 flt-single">
                                <p><?php echo ''?><br>
                               <?php echo date("h:i a", strtotime($fistflyLast['ArrivalTime'])) ?> <br>
                               <?php echo date("D, d M Y", strtotime($fistflyLast['ArrivalTime']))?>?>></p>
                            </div>
                            <div class="col-sm-3 col-xs-4 flt-single drsn">
                                <img src="../airimages/<?php echo $fistflyLast['Carrier'] ?>.GIF">
                               <?php $flightUtility->getAirLineName($fistflyLast['Carrier']) ?>
                            </div>
                            <div class="col-sm-1 col-xs-4 flt-single"><a>1 Stop</a></div>
                            <div class="col-sm-2 col-xs-4 flt-single"><?php echo '' ?></div>
                            
                            <?php }?>
                        </div>
                  
     
                                                          <a role="button" data-toggle="collapse" href="#moreFlights_<?php echo $Key; ?>" aria-expanded="false" 
                                                             accesskey="" class="flySeg" aria-controls="moreFlights_<?php echo $Key; ?>">view more flights</a>
                                                             <!-- More Flights-->
                                                          <div class="collapse" id="moreFlights_<?php echo $Key; ?>" aria-hidden="true" >
                                                            
                                                          <?php
                                                          for ($index=1;$index<count($moreFlights);$index++)
                                                              {
                                                                 $firstFly=$moreFlights[$index]['@attributes'] ;
                                                                 $lastFly=$moreFlights[$moreCount-1]['@attributes'];
                                                                 ?>
                                                              <div class="col-sm-12" style="border-style: solid;border-color: red;">
                                                               <div class="col-sm-1 flt flySeg-child">
                                                                                      <label><input type="radio" class="radio-stroke"  name="selectFlightOut" mode="outBound_<?php echo $Key ?>" value="<?php echo base64_encode(json_encode($mIndex)); ?>"></label> 
                                                               </div>

                                                                                  <div class="col-sm-3 col-xs-6 flt-single arw">
                                                                                      <p>from<br>
                                                                                          <?php echo $firstFly['depatureTime'] ?> <br>
                                                                                          <?php echo $firstFly['depatureTime'] ?>
                                                                                      </p>
                                                                                  </div>
                                                                                  <div class="col-sm-3 col-xs-6 flt-single">
                                                                                      <p><?php echo to ?><br>
                                                                                            <?php echo $lastFly['depatureTime'] ?> <br>
                                                                                          <?php echo $lastFly['depatureTime'] ?>
                                                                                  </div>
                                                                                  <div class="col-sm-3 col-xs-4 flt-single drsn">
                                                                                      <img src="../airimages/<?php echo $lastFly['Carrier'] ?>.GIF">
                                                                                      <?php $flightUtility->getAirLineName($lastFly['Carrier']) ?>
                                                                                      <div class="col-sm-1 col-xs-4 flt-single"><a>1 Stop</a></div>
                                                                                      <div class="col-sm-2 col-xs-4 flt-single"><?php echo journyTime ?></div>
                                                                                </div>
                                                            </div>  
                                                              <?php }?>
                                                         </div>
                                                          
                    <!--********************************************************** flights end**********************************************************--->


                    <!--************************************************************more flights details  start********************************************************--->
                    <?php ?> 
                    
                    <div class="edit-visl"> 
                        
                        <?php 
                        foreach($bestFlySegmentDetails as $index)
                            $flyDe=$index['@attributes'];
                            {?>
                                <div class="head">
                                    <i class="fa fa-plane"></i> 
                                    Depart
                                </div> 
                        <!-------------------------------------flt-dtl start ---------------------->
                                        <div class="row flt-dtl">
                                                    <div class="col-sm-4 col-xs-6 arw">
                                                        <p>  <?php echo $flightUtility->getCountryName($flyDe['Origin']) ?><br>
                                                             <?php echo $flyDe['Origin'] ?><br>
                                                           <?php echo $flightUtility->getAirportName($flyDe['Origin'])?><br>
                                                            <?php echo date("h:i a", strtotime($flyDe['DepartureTime'])) ?> <br>
                                                            <?php echo date("D, d M Y", strtotime($flyDe['DepartureTime']))?></p>
                                                    </div>
                                                    <div class="col-sm-4 col-xs-6">
                                                        <p><?php echo $flightUtility->getCountryName($flyDe['Destination']) ?><br>
                                                            <?php echo $flyDe['Destination'] ?><br>
                                                           <?php echo $flightUtility->getAirportName($flyDe['Destination'])?><br>
                                                            <?php echo date("h:i a", strtotime($flyDe['ArrivalTime'])) ?> <br>
                                                            <?php echo date("D, d M Y", strtotime($flyDe['ArrivalTime']))?></p>
                                                    </div>
                                             <?php $totaltime=$flightUtility->getTimeDiff( date("h:i a", strtotime($flyDe['DepartureTime'])),date("h:i a", strtotime($flyDe['ArrivalTime'])))?>
                                                    <div class="col-sm-4">
                                                        <p><i class="fa fa-clock-o"></i>  <?php echo $totaltime ?> </p>
                                                    </div>
                                        </div>
                        <!-------------------------------------flt-dtl End ---------------------->
                                <div class="vgn">
                                    <p><img src="../airimages/<?php echo $flyDe['Carrier'] ?>.GIF"> 
 <?php echo $flightUtility->getAirLineName($flyDe['Carrier']). $flyDe['Carrier']. $flyDe['FlightNumber'] ?>
                                        <a><?php echo $flyDe[0]['CabinClass'] .$flyDe[0]['BookingCode'] ?></a> - Aircraft <?php echo $flyDe['FlightNumber'] ?></p></div>
                   
                                <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong>   <?php echo $totaltime ?></div> 
                                
 <?php }?>
                    </div>
                    <!--************************************************************more flights details  end********************************************************--->     
                          <div class="clearfix"></div>
                </div>
                           </div>
  <!--************************************************************col 8  end********************************************************--->     
                <div class="col-sm-4 show-flight-prize">
                    <div class="flt-prz">
                        <label>Per Adult OneWay</label>
                        <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="" readonly="">
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
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
     
            </div> 
        </div>

    <?php } ?>
</div>


<!--more flights --->



