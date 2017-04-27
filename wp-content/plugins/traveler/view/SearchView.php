<script type="application/javascript" src='http://localhost:8080/travel/wp-content/plugins/traveler/js/tr-search.js'/>
<?php
include(PLUG_DIR . 'utility/FlightUtility.php');

function genarate_html_view($flyDetails) {

 // error_log(" ----adding----> ".print_r($flyDetails,true));
    
    $flightUtility =new FlightUtility();
    foreach ($flyDetails as $outerIndex) {
  //error_log(" ----adding----> ".print_r($outerIndex,true));
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
                            <div class="col-sm-3 col-xs-6 flt-single arw">
                                <p><?php echo $outerIndex[0]->_from ?><br>
                                    <?php echo $outerIndex[0]->depatureTime ?> <br>
                                    <?php echo $outerIndex[0]->depatureDate ?>
                                </p>
                            </div>
                            <div class="col-sm-3 col-xs-6 flt-single">
                                <p><?php echo $outerIndex[0]->_to ?><br>
                                    <?php echo $outerIndex[0]->arrivalTime ?> <br>
                                    <?php echo $outerIndex[0]->arrivalDate ?></p>
                            </div>
                            <div class="col-sm-3 col-xs-4 flt-single drsn">
                                <img src="../airimages/<?php echo $outerIndex[0]->airline ?>.GIF">
                               <?php $flightUtility->getAirLineName($outerIndex[0]->airline) ?>
                            </div>
                            <div class="col-sm-1 col-xs-4 flt-single"><a>1 Stop</a></div>
                            <div class="col-sm-2 col-xs-4 flt-single"><?php echo $outerIndex[0]->journyTime ?></div>
                        </div>
                        <p><a class="more-fly"><span><i class="fa fa-plus"></i> more timing option </span></a></p>
                    </div>
                    <!--********************************************************** flights end**********************************************************--->


                    <!--************************************************************more flights details  start********************************************************--->
                    <div class="edit-visl"> 
                        
                        <?php 
                        if(isset($outerIndex[0]->flightDetails['Key']))
                        {
                            $flydetails=array($outerIndex[0]->flightDetails);
                            error_log('flydetails------------->'.print_r($flydetails,true));
                        }
                        else
                        {
                            $flydetails=$outerIndex[0]->flightDetails;
                        }
                        foreach($flydetails as $flyDe)
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
  <!--************************************************************col 8  end********************************************************--->     
                <div class="col-sm-4 show-flight-prize">
                    <div class="flt-prz">
                        <label>Per Adult OneWay</label>
                        <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="<?php echo $outerIndex[0]->totalPrice ?>" readonly="">
                        <form name="frmAvail" action="../free-booking" method="post">
                            <input type="hidden" name="bookingdata" value="<?php echo base64_encode(serialize($outerIndex[0]))?>">
                            <?php error_log("search data --- >".print_r($outerIndex[0]->searchData,true))?>
                            <input type="hidden" name="searchdata" value="<?php echo base64_encode(serialize($outerIndex[0]->searchData))?>">
                            <input type="hidden" name="searchmode" value="<?php echo $outerIndex[0]->searchData['mode'] ?> ">
                            <button type="submit">Make Free Booking</button>
                        </form>
                        <form name="frmAvail" action="../booking" method="post">
                            <input type="hidden" name="connectiondata" value="YToxOntzOjEyOiJTZWdtZW50SW5kZXgiO3M6MToiMCI7fQ==">
                            <!-- Rajib added -->
                            <input type="hidden" name="bookingdata" value="YTo1OntzOjExOiJzZWdtZW50ZHRscyI7YToyOntpOjA7YToyMjp7czozOiJLZXkiO3M6MjQ6IndELzE1cUJBQUEvQmVjcnU5T0FBQUE9PSI7czo1OiJHcm91cCI7czoxOiIwIjtzOjc6IkNhcnJpZXIiO3M6MjoiVUwiO3M6MTI6IkZsaWdodE51bWJlciI7czozOiIzMDIiO3M6NjoiT3JpZ2luIjtzOjM6IkNNQiI7czoxMToiRGVzdGluYXRpb24iO3M6MzoiU0lOIjtzOjEzOiJEZXBhcnR1cmVUaW1lIjtzOjI5OiIyMDE3LTAzLTI5VDA3OjI1OjAwLjAwMCswNTozMCI7czoxMToiQXJyaXZhbFRpbWUiO3M6Mjk6IjIwMTctMDMtMjlUMTQ6MDU6MDAuMDAwKzA4OjAwIjtzOjEwOiJGbGlnaHRUaW1lIjtzOjM6IjI1MCI7czo4OiJEaXN0YW5jZSI7czo0OiIxNzA5IjtzOjE0OiJFVGlja2V0YWJpbGl0eSI7czozOiJZZXMiO3M6OToiRXF1aXBtZW50IjtzOjM6IjMzMyI7czoxMzoiQ2hhbmdlT2ZQbGFuZSI7czo1OiJmYWxzZSI7czoxNjoiUGFydGljaXBhbnRMZXZlbCI7czoxMToiU2VjdXJlIFNlbGwiO3M6MTY6IkxpbmtBdmFpbGFiaWxpdHkiO3M6NDoidHJ1ZSI7czoyNDoiUG9sbGVkQXZhaWxhYmlsaXR5T3B0aW9uIjtzOjQyOiJDYWNoZSBzdGF0dXMgdXNlZC4gTm8gcG9sbGVkIGF2YWlsIGV4aXN0cy4iO3M6MjU6Ik9wdGlvbmFsU2VydmljZXNJbmRpY2F0b3IiO3M6NToiZmFsc2UiO3M6MTg6IkF2YWlsYWJpbGl0eVNvdXJjZSI7czoxOiJFIjtzOjIzOiJBdmFpbGFiaWxpdHlEaXNwbGF5VHlwZSI7czoyMjoiRmFyZSBTaG9wL09wdGltYWwgU2hvcCI7czoxNjoiYWlyQ29kZXNoYXJlSW5mbyI7czowOiIiO3M6MTM6IkJvb2tpbmdDb3VudHMiO3M6NTY6Iko0fEM0fEQ0fEkwfFk3fEI3fFA3fEg3fEs3fFc3fE03fEU3fEw3fFI3fFY3fFM3fE43fFE3fE83IjtzOjE5OiJhaXJGbGlnaHREZXRhaWxzUmVmIjtzOjI0OiJ3RC8xNXFCQUFBL0JmY3J1OU9BQUFBPT0iO31pOjE7YToyMjp7czozOiJLZXkiO3M6MjQ6IndELzE1cUJBQUEvQmljcnU5T0FBQUE9PSI7czo1OiJHcm91cCI7czoxOiIwIjtzOjc6IkNhcnJpZXIiO3M6MjoiM0siO3M6MTI6IkZsaWdodE51bWJlciI7czozOiI1MTMiO3M6NjoiT3JpZ2luIjtzOjM6IlNJTiI7czoxMToiRGVzdGluYXRpb24iO3M6MzoiQktLIjtzOjEzOiJEZXBhcnR1cmVUaW1lIjtzOjI5OiIyMDE3LTAzLTI5VDE5OjE1OjAwLjAwMCswODowMCI7czoxMToiQXJyaXZhbFRpbWUiO3M6Mjk6IjIwMTctMDMtMjlUMjA6NDA6MDAuMDAwKzA3OjAwIjtzOjEwOiJGbGlnaHRUaW1lIjtzOjM6IjE0NSI7czo4OiJEaXN0YW5jZSI7czozOiI4ODkiO3M6MTQ6IkVUaWNrZXRhYmlsaXR5IjtzOjM6IlllcyI7czo5OiJFcXVpcG1lbnQiO3M6MzoiMzIwIjtzOjEzOiJDaGFuZ2VPZlBsYW5lIjtzOjU6ImZhbHNlIjtzOjE2OiJQYXJ0aWNpcGFudExldmVsIjtzOjIyOiJHdWFyIGFnYWluc3QgYWxwaGEgQVZTIjtzOjE2OiJMaW5rQXZhaWxhYmlsaXR5IjtOO3M6MjQ6IlBvbGxlZEF2YWlsYWJpbGl0eU9wdGlvbiI7czoyMjoiTm8gcG9sbGVkIGF2YWlsIGV4aXN0cyI7czoyNToiT3B0aW9uYWxTZXJ2aWNlc0luZGljYXRvciI7czo1OiJmYWxzZSI7czoxODoiQXZhaWxhYmlsaXR5U291cmNlIjtzOjE6IkEiO3M6MjM6IkF2YWlsYWJpbGl0eURpc3BsYXlUeXBlIjtzOjIyOiJGYXJlIFNob3AvT3B0aW1hbCBTaG9wIjtzOjE2OiJhaXJDb2Rlc2hhcmVJbmZvIjtzOjA6IiI7czoxMzoiQm9va2luZ0NvdW50cyI7czo1NjoiWTR8QjR8VjR8VDR8UzR8UjR8UTR8UDR8TzR8TjR8TTR8RTR8RjR8TDR8QTR8WjR8STB8SzR8SDQiO3M6MTk6ImFpckZsaWdodERldGFpbHNSZWYiO3M6MjQ6IndELzE1cUJBQUEvQmpjcnU5T0FBQUE9PSI7fX1zOjEwOiJDb25uZWN0aW9uIjthOjI6e2k6MDthOjE6e3M6MTI6IlNlZ21lbnRJbmRleCI7czoxOiIwIjt9aToxO2E6MTp7czoxMjoiU2VnbWVudEluZGV4IjtzOjE6IjAiO319czoxMDoiQ2FiaW5DbGFzcyI7YToyOntpOjA7czo3OiJFY29ub215IjtpOjE7czo3OiJFY29ub215Ijt9czoxMToiQm9va2luZ0NvZGUiO2E6Mjp7aTowO3M6MToiTiI7aToxO3M6MToiSCI7fXM6MTA6InBhc3NlbmdlcnMiO2E6Mjp7aTowO2E6MTp7aTowO3M6MzoiQURUIjt9aToxO2E6MTp7aTowO3M6MzoiQURUIjt9fX0=">
                            <input type="hidden" name="searchdata" value="YToxNDp7czoxMjoiZnJvbV9haXJwb3J0IjtzOjM6IkNNQiI7czo5OiJmcm9tX2NpdHkiO3M6NzoiQ29sb21ibyI7czoxMDoidG9fYWlycG9ydCI7czozOiJCS0siO3M6NzoidG9fY2l0eSI7czo3OiJCYW5na29rIjtzOjEwOiJzdGFydF9kYXRlIjtzOjEwOiIyMDE3LTAzLTI5IjtzOjg6ImVuZF9kYXRlIjtzOjA6IiI7czoxMDoicGFzc2VuZ2VycyI7YToxOntpOjA7czozOiJBRFQiO31zOjEwOiJjYWJpbmNsYXNzIjtzOjc6IkVjb25vbXkiO3M6NToiYWR1bHQiO3M6MToiMSI7czo1OiJjaGlsZCI7czoxOiIwIjtzOjY6ImluZmFudCI7czoxOiIwIjtzOjEwOiJkYXRlX2ZsZXhpIjtzOjE6IjAiO3M6NDoibW9kZSI7czo2OiJvbmV3YXkiO3M6NDoiaWF0YSI7czowOiIiO30=">
                            <input type="hidden" name="searchmode" value="oneway">
                            <button type="submit">Book Now</button>
                        </form>
                        <form name="frmFL" id="frmFL9" method="post" action="../farerule.php" target="_blank">
                            <input type="hidden" name="k" value="gws-eJxNTkEOgzAMewzy3SliHbdudIiJEg6AJi77/zNIyzQRybESW3FCCI7iWTsJl6rwrbYE3TpA4QzLWyFsmhvEph0k79D5k0b8DrS216KdLMUV6+hA9OzlVHJhL72bnv+bORFmRG6vwUgfcU0jxYIM9LMJHvbVAS+nJ4c=">
                            <input type="hidden" name="ref" value="wD/15qBAAA/BEeru9OAAAA==">
                            <p><a class="shw2" onclick="jQuery('#frmFL9').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>
                        </form>
                        <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div> 





<!--more flights --->
<?php for($index=1;$index<count($outerIndex);$index++) {?>
<div class="col-sm-12">
            <!--flight Show Start-->
            <div class="show-flight">  
                <!--col-sm-8 Start-->
                <div class="col-sm-8">
                    <!--**********************************************************flights start**********************************************************--->
                    <div class="visl <?php echo 'more_flight index_'.$index ?> ">
                        <!-- row start-->
                        <div class="row">
                            <div class="col-sm-3 col-xs-6 flt-single arw">
                                <p><?php echo $outerIndex[$index]->depatureTime ?><br>
                                   <?php echo $outerIndex[$index]->depatureTime ?> <br>
                                    <?php echo $outerIndex[$index]->depatureDate ?>                         </p>
                            </div>
                            <div class="col-sm-3 col-xs-6 flt-single">
                                <p>BKK<br>
                                     <?php echo $outerIndex[$index]->arrivalTime ?> <br>
                                    <?php echo $outerIndex[$index]->arrivalDate ?></p>
                            </div>
                            <div class="col-sm-3 col-xs-4 flt-single drsn">
                                <img src="../airimages/UL.GIF">
                                SriLankan Airlines
                            </div>
                            <div class="col-sm-1 col-xs-4 flt-single"><a>1 Stop</a></div>
                            <div class="col-sm-2 col-xs-4 flt-single">11hrs 45mins</div>
                        </div>
                        <p><a class="more-fly"><span><i class="fa fa-plus"></i> more timing option </span></a></p>
                    </div>
                    <!--********************************************************** flights end**********************************************************--->


                    <!--************************************************************more flights details  start********************************************************--->
                    <div class="edit-visl"> 
                        <div class="head">
                            <i class="fa fa-plane"></i> 
                            Depart
                        </div> 
                        <!-------------------------------------flt-dtl start ---------------------->
                        <div class="row flt-dtl">
                            <div class="col-sm-4 col-xs-6 arw">
                                <p>Colombo<br>
                                    CMB<br>
                                    Bandaranayake International Airport<br>
                                    07:25 am <br>
                                    Wed, 29 Mar 2017</p>
                            </div>
                            <div class="col-sm-4 col-xs-6">
                                <p>Singapore<br>
                                    SIN<br>
                                    Changi Airport<br>
                                    11:35 am <br>
                                    Wed, 29 Mar 2017</p>
                            </div>
                            <div class="col-sm-4">
                                <p><i class="fa fa-clock-o"></i> 4hrs 10mins </p>
                            </div>
                        </div>
                        <!-------------------------------------flt-dtl End ---------------------->
                        <div class="vgn"><p><img src="../airimages/UL.GIF"> SriLankan Airlines - UL302  <a>Economy N</a> - Aircraft 333</p></div>
                        <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> 4hrs 10mins</div>
                        <div class="head"><i class="fa fa-plane"></i> Depart</div>
                        <!-------------------------------------flt-dtl Start ---------------------->    
                        <div class="row flt-dtl">
                            <div class="col-sm-4 col-xs-6 arw">
                                <p>Singapore<br>
                                    SIN<br>
                                    Changi Airport<br>
                                    04:45 pm <br>
                                    Wed, 29 Mar 2017</p>
                            </div>
                            <div class="col-sm-4 col-xs-6">
                                <p>Bangkok<br>
                                    BKK<br>
                                    Airport<br>
                                    07:10 pm <br>
                                    Wed, 29 Mar 2017</p>
                            </div>
                            <div class="col-sm-4">
                                p&gt;<i class="fa fa-clock-o"></i> 2hrs 25mins <p></p>
                            </div>
                        </div>
                        <!-------------------------------------flt-dtl End ---------------------->   
                        <div class="vgn"><p><img src="../airimages/3K.GIF"> Jetstar Asia Airways - 3K513  <a>Economy H</a> - Aircraft 320</p></div>
                        <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> 2hrs 25mins</div>
                    </div>

                    <!--************************************************************more flights details  end********************************************************--->     
                    <!-- edit-visl end-->
                    <div class="clearfix"></div>
                </div>

                <div class="col-sm-4 show-flight-prize">
                    <div class="flt-prz">
                        <label>Per Adult OneWay</label>
                        <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="LKR 37511" readonly="">
                        <form name="frmAvail" action="../free-booking" method="post">
                            <input type="hidden" name="bookingdata" value="YTo1OntzOjExOiJzZWdtZW50ZHRscyI7YToyOntpOjA7YToyMjp7czozOiJLZXkiO3M6MjQ6IndELzE1cUJBQUEvQmVjcnU5T0FBQUE9PSI7czo1OiJHcm91cCI7czoxOiIwIjtzOjc6IkNhcnJpZXIiO3M6MjoiVUwiO3M6MTI6IkZsaWdodE51bWJlciI7czozOiIzMDIiO3M6NjoiT3JpZ2luIjtzOjM6IkNNQiI7czoxMToiRGVzdGluYXRpb24iO3M6MzoiU0lOIjtzOjEzOiJEZXBhcnR1cmVUaW1lIjtzOjI5OiIyMDE3LTAzLTI5VDA3OjI1OjAwLjAwMCswNTozMCI7czoxMToiQXJyaXZhbFRpbWUiO3M6Mjk6IjIwMTctMDMtMjlUMTQ6MDU6MDAuMDAwKzA4OjAwIjtzOjEwOiJGbGlnaHRUaW1lIjtzOjM6IjI1MCI7czo4OiJEaXN0YW5jZSI7czo0OiIxNzA5IjtzOjE0OiJFVGlja2V0YWJpbGl0eSI7czozOiJZZXMiO3M6OToiRXF1aXBtZW50IjtzOjM6IjMzMyI7czoxMzoiQ2hhbmdlT2ZQbGFuZSI7czo1OiJmYWxzZSI7czoxNjoiUGFydGljaXBhbnRMZXZlbCI7czoxMToiU2VjdXJlIFNlbGwiO3M6MTY6IkxpbmtBdmFpbGFiaWxpdHkiO3M6NDoidHJ1ZSI7czoyNDoiUG9sbGVkQXZhaWxhYmlsaXR5T3B0aW9uIjtzOjQyOiJDYWNoZSBzdGF0dXMgdXNlZC4gTm8gcG9sbGVkIGF2YWlsIGV4aXN0cy4iO3M6MjU6Ik9wdGlvbmFsU2VydmljZXNJbmRpY2F0b3IiO3M6NToiZmFsc2UiO3M6MTg6IkF2YWlsYWJpbGl0eVNvdXJjZSI7czoxOiJFIjtzOjIzOiJBdmFpbGFiaWxpdHlEaXNwbGF5VHlwZSI7czoyMjoiRmFyZSBTaG9wL09wdGltYWwgU2hvcCI7czoxNjoiYWlyQ29kZXNoYXJlSW5mbyI7czowOiIiO3M6MTM6IkJvb2tpbmdDb3VudHMiO3M6NTY6Iko0fEM0fEQ0fEkwfFk3fEI3fFA3fEg3fEs3fFc3fE03fEU3fEw3fFI3fFY3fFM3fE43fFE3fE83IjtzOjE5OiJhaXJGbGlnaHREZXRhaWxzUmVmIjtzOjI0OiJ3RC8xNXFCQUFBL0JmY3J1OU9BQUFBPT0iO31pOjE7YToyMjp7czozOiJLZXkiO3M6MjQ6IndELzE1cUJBQUEvQmljcnU5T0FBQUE9PSI7czo1OiJHcm91cCI7czoxOiIwIjtzOjc6IkNhcnJpZXIiO3M6MjoiM0siO3M6MTI6IkZsaWdodE51bWJlciI7czozOiI1MTMiO3M6NjoiT3JpZ2luIjtzOjM6IlNJTiI7czoxMToiRGVzdGluYXRpb24iO3M6MzoiQktLIjtzOjEzOiJEZXBhcnR1cmVUaW1lIjtzOjI5OiIyMDE3LTAzLTI5VDE5OjE1OjAwLjAwMCswODowMCI7czoxMToiQXJyaXZhbFRpbWUiO3M6Mjk6IjIwMTctMDMtMjlUMjA6NDA6MDAuMDAwKzA3OjAwIjtzOjEwOiJGbGlnaHRUaW1lIjtzOjM6IjE0NSI7czo4OiJEaXN0YW5jZSI7czozOiI4ODkiO3M6MTQ6IkVUaWNrZXRhYmlsaXR5IjtzOjM6IlllcyI7czo5OiJFcXVpcG1lbnQiO3M6MzoiMzIwIjtzOjEzOiJDaGFuZ2VPZlBsYW5lIjtzOjU6ImZhbHNlIjtzOjE2OiJQYXJ0aWNpcGFudExldmVsIjtzOjIyOiJHdWFyIGFnYWluc3QgYWxwaGEgQVZTIjtzOjE2OiJMaW5rQXZhaWxhYmlsaXR5IjtOO3M6MjQ6IlBvbGxlZEF2YWlsYWJpbGl0eU9wdGlvbiI7czoyMjoiTm8gcG9sbGVkIGF2YWlsIGV4aXN0cyI7czoyNToiT3B0aW9uYWxTZXJ2aWNlc0luZGljYXRvciI7czo1OiJmYWxzZSI7czoxODoiQXZhaWxhYmlsaXR5U291cmNlIjtzOjE6IkEiO3M6MjM6IkF2YWlsYWJpbGl0eURpc3BsYXlUeXBlIjtzOjIyOiJGYXJlIFNob3AvT3B0aW1hbCBTaG9wIjtzOjE2OiJhaXJDb2Rlc2hhcmVJbmZvIjtzOjA6IiI7czoxMzoiQm9va2luZ0NvdW50cyI7czo1NjoiWTR8QjR8VjR8VDR8UzR8UjR8UTR8UDR8TzR8TjR8TTR8RTR8RjR8TDR8QTR8WjR8STB8SzR8SDQiO3M6MTk6ImFpckZsaWdodERldGFpbHNSZWYiO3M6MjQ6IndELzE1cUJBQUEvQmpjcnU5T0FBQUE9PSI7fX1zOjEwOiJDb25uZWN0aW9uIjthOjI6e2k6MDthOjE6e3M6MTI6IlNlZ21lbnRJbmRleCI7czoxOiIwIjt9aToxO2E6MTp7czoxMjoiU2VnbWVudEluZGV4IjtzOjE6IjAiO319czoxMDoiQ2FiaW5DbGFzcyI7YToyOntpOjA7czo3OiJFY29ub215IjtpOjE7czo3OiJFY29ub215Ijt9czoxMToiQm9va2luZ0NvZGUiO2E6Mjp7aTowO3M6MToiTiI7aToxO3M6MToiSCI7fXM6MTA6InBhc3NlbmdlcnMiO2E6Mjp7aTowO2E6MTp7aTowO3M6MzoiQURUIjt9aToxO2E6MTp7aTowO3M6MzoiQURUIjt9fX0=">
                            <input type="hidden" name="searchdata" value="YToxNDp7czoxMjoiZnJvbV9haXJwb3J0IjtzOjM6IkNNQiI7czo5OiJmcm9tX2NpdHkiO3M6NzoiQ29sb21ibyI7czoxMDoidG9fYWlycG9ydCI7czozOiJCS0siO3M6NzoidG9fY2l0eSI7czo3OiJCYW5na29rIjtzOjEwOiJzdGFydF9kYXRlIjtzOjEwOiIyMDE3LTAzLTI5IjtzOjg6ImVuZF9kYXRlIjtzOjA6IiI7czoxMDoicGFzc2VuZ2VycyI7YToxOntpOjA7czozOiJBRFQiO31zOjEwOiJjYWJpbmNsYXNzIjtzOjc6IkVjb25vbXkiO3M6NToiYWR1bHQiO3M6MToiMSI7czo1OiJjaGlsZCI7czoxOiIwIjtzOjY6ImluZmFudCI7czoxOiIwIjtzOjEwOiJkYXRlX2ZsZXhpIjtzOjE6IjAiO3M6NDoibW9kZSI7czo2OiJvbmV3YXkiO3M6NDoiaWF0YSI7czowOiIiO30=">
                            <input type="hidden" name="searchmode" value="oneway">
                            <button type="submit">Make Free Booking</button>
                        </form>
                        <form name="frmAvail" action="../booking" method="post">
                            <input type="hidden" name="connectiondata" value="YToxOntzOjEyOiJTZWdtZW50SW5kZXgiO3M6MToiMCI7fQ==">
                            <!-- Rajib added -->
                            <input type="hidden" name="bookingdata" value="YTo1OntzOjExOiJzZWdtZW50ZHRscyI7YToyOntpOjA7YToyMjp7czozOiJLZXkiO3M6MjQ6IndELzE1cUJBQUEvQmVjcnU5T0FBQUE9PSI7czo1OiJHcm91cCI7czoxOiIwIjtzOjc6IkNhcnJpZXIiO3M6MjoiVUwiO3M6MTI6IkZsaWdodE51bWJlciI7czozOiIzMDIiO3M6NjoiT3JpZ2luIjtzOjM6IkNNQiI7czoxMToiRGVzdGluYXRpb24iO3M6MzoiU0lOIjtzOjEzOiJEZXBhcnR1cmVUaW1lIjtzOjI5OiIyMDE3LTAzLTI5VDA3OjI1OjAwLjAwMCswNTozMCI7czoxMToiQXJyaXZhbFRpbWUiO3M6Mjk6IjIwMTctMDMtMjlUMTQ6MDU6MDAuMDAwKzA4OjAwIjtzOjEwOiJGbGlnaHRUaW1lIjtzOjM6IjI1MCI7czo4OiJEaXN0YW5jZSI7czo0OiIxNzA5IjtzOjE0OiJFVGlja2V0YWJpbGl0eSI7czozOiJZZXMiO3M6OToiRXF1aXBtZW50IjtzOjM6IjMzMyI7czoxMzoiQ2hhbmdlT2ZQbGFuZSI7czo1OiJmYWxzZSI7czoxNjoiUGFydGljaXBhbnRMZXZlbCI7czoxMToiU2VjdXJlIFNlbGwiO3M6MTY6IkxpbmtBdmFpbGFiaWxpdHkiO3M6NDoidHJ1ZSI7czoyNDoiUG9sbGVkQXZhaWxhYmlsaXR5T3B0aW9uIjtzOjQyOiJDYWNoZSBzdGF0dXMgdXNlZC4gTm8gcG9sbGVkIGF2YWlsIGV4aXN0cy4iO3M6MjU6Ik9wdGlvbmFsU2VydmljZXNJbmRpY2F0b3IiO3M6NToiZmFsc2UiO3M6MTg6IkF2YWlsYWJpbGl0eVNvdXJjZSI7czoxOiJFIjtzOjIzOiJBdmFpbGFiaWxpdHlEaXNwbGF5VHlwZSI7czoyMjoiRmFyZSBTaG9wL09wdGltYWwgU2hvcCI7czoxNjoiYWlyQ29kZXNoYXJlSW5mbyI7czowOiIiO3M6MTM6IkJvb2tpbmdDb3VudHMiO3M6NTY6Iko0fEM0fEQ0fEkwfFk3fEI3fFA3fEg3fEs3fFc3fE03fEU3fEw3fFI3fFY3fFM3fE43fFE3fE83IjtzOjE5OiJhaXJGbGlnaHREZXRhaWxzUmVmIjtzOjI0OiJ3RC8xNXFCQUFBL0JmY3J1OU9BQUFBPT0iO31pOjE7YToyMjp7czozOiJLZXkiO3M6MjQ6IndELzE1cUJBQUEvQmljcnU5T0FBQUE9PSI7czo1OiJHcm91cCI7czoxOiIwIjtzOjc6IkNhcnJpZXIiO3M6MjoiM0siO3M6MTI6IkZsaWdodE51bWJlciI7czozOiI1MTMiO3M6NjoiT3JpZ2luIjtzOjM6IlNJTiI7czoxMToiRGVzdGluYXRpb24iO3M6MzoiQktLIjtzOjEzOiJEZXBhcnR1cmVUaW1lIjtzOjI5OiIyMDE3LTAzLTI5VDE5OjE1OjAwLjAwMCswODowMCI7czoxMToiQXJyaXZhbFRpbWUiO3M6Mjk6IjIwMTctMDMtMjlUMjA6NDA6MDAuMDAwKzA3OjAwIjtzOjEwOiJGbGlnaHRUaW1lIjtzOjM6IjE0NSI7czo4OiJEaXN0YW5jZSI7czozOiI4ODkiO3M6MTQ6IkVUaWNrZXRhYmlsaXR5IjtzOjM6IlllcyI7czo5OiJFcXVpcG1lbnQiO3M6MzoiMzIwIjtzOjEzOiJDaGFuZ2VPZlBsYW5lIjtzOjU6ImZhbHNlIjtzOjE2OiJQYXJ0aWNpcGFudExldmVsIjtzOjIyOiJHdWFyIGFnYWluc3QgYWxwaGEgQVZTIjtzOjE2OiJMaW5rQXZhaWxhYmlsaXR5IjtOO3M6MjQ6IlBvbGxlZEF2YWlsYWJpbGl0eU9wdGlvbiI7czoyMjoiTm8gcG9sbGVkIGF2YWlsIGV4aXN0cyI7czoyNToiT3B0aW9uYWxTZXJ2aWNlc0luZGljYXRvciI7czo1OiJmYWxzZSI7czoxODoiQXZhaWxhYmlsaXR5U291cmNlIjtzOjE6IkEiO3M6MjM6IkF2YWlsYWJpbGl0eURpc3BsYXlUeXBlIjtzOjIyOiJGYXJlIFNob3AvT3B0aW1hbCBTaG9wIjtzOjE2OiJhaXJDb2Rlc2hhcmVJbmZvIjtzOjA6IiI7czoxMzoiQm9va2luZ0NvdW50cyI7czo1NjoiWTR8QjR8VjR8VDR8UzR8UjR8UTR8UDR8TzR8TjR8TTR8RTR8RjR8TDR8QTR8WjR8STB8SzR8SDQiO3M6MTk6ImFpckZsaWdodERldGFpbHNSZWYiO3M6MjQ6IndELzE1cUJBQUEvQmpjcnU5T0FBQUE9PSI7fX1zOjEwOiJDb25uZWN0aW9uIjthOjI6e2k6MDthOjE6e3M6MTI6IlNlZ21lbnRJbmRleCI7czoxOiIwIjt9aToxO2E6MTp7czoxMjoiU2VnbWVudEluZGV4IjtzOjE6IjAiO319czoxMDoiQ2FiaW5DbGFzcyI7YToyOntpOjA7czo3OiJFY29ub215IjtpOjE7czo3OiJFY29ub215Ijt9czoxMToiQm9va2luZ0NvZGUiO2E6Mjp7aTowO3M6MToiTiI7aToxO3M6MToiSCI7fXM6MTA6InBhc3NlbmdlcnMiO2E6Mjp7aTowO2E6MTp7aTowO3M6MzoiQURUIjt9aToxO2E6MTp7aTowO3M6MzoiQURUIjt9fX0=">
                            <input type="hidden" name="searchdata" value="YToxNDp7czoxMjoiZnJvbV9haXJwb3J0IjtzOjM6IkNNQiI7czo5OiJmcm9tX2NpdHkiO3M6NzoiQ29sb21ibyI7czoxMDoidG9fYWlycG9ydCI7czozOiJCS0siO3M6NzoidG9fY2l0eSI7czo3OiJCYW5na29rIjtzOjEwOiJzdGFydF9kYXRlIjtzOjEwOiIyMDE3LTAzLTI5IjtzOjg6ImVuZF9kYXRlIjtzOjA6IiI7czoxMDoicGFzc2VuZ2VycyI7YToxOntpOjA7czozOiJBRFQiO31zOjEwOiJjYWJpbmNsYXNzIjtzOjc6IkVjb25vbXkiO3M6NToiYWR1bHQiO3M6MToiMSI7czo1OiJjaGlsZCI7czoxOiIwIjtzOjY6ImluZmFudCI7czoxOiIwIjtzOjEwOiJkYXRlX2ZsZXhpIjtzOjE6IjAiO3M6NDoibW9kZSI7czo2OiJvbmV3YXkiO3M6NDoiaWF0YSI7czowOiIiO30=">
                            <input type="hidden" name="searchmode" value="oneway">
                            <button type="submit">Book Now</button>
                        </form>
                        <form name="frmFL" id="frmFL9" method="post" action="../farerule.php" target="_blank">
                            <input type="hidden" name="k" value="gws-eJxNTkEOgzAMewzy3SliHbdudIiJEg6AJi77/zNIyzQRybESW3FCCI7iWTsJl6rwrbYE3TpA4QzLWyFsmhvEph0k79D5k0b8DrS216KdLMUV6+hA9OzlVHJhL72bnv+bORFmRG6vwUgfcU0jxYIM9LMJHvbVAS+nJ4c=">
                            <input type="hidden" name="ref" value="wD/15qBAAA/BEeru9OAAAA==">
                            <p><a class="shw2" onclick="jQuery('#frmFL9').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>
                        </form>
                        <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <!--flight Show End-->

        <!--**********************************************************more flights start**********************************************************--->

        <!--************************************************************more flights end********************************************************--->

    <?php }}} ?>

