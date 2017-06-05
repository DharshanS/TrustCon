<!--<script type="application/javascript" src='http://localhost:8080/travel/wp-content/plugins/traveler/js/tr-search.js'/>-->
<?php
include(PLUG_DIR . 'utility/FlightUtility.php'); ?>

<div class="sky-bg"><?php
function genarate_html_view($flyDetails) {
    $flyUtil =new FlightUtility();
//      error_log(print_r($flyDetails,true));
//              return;
?>
    
<div id="resultloader">
    <div class="sky-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    
                    <?php foreach($flyDetails as $i=>$index) {
                          
                        $bestfly=$index->bestFly->segDetails;
                        $bestSegDetailsCount=count($bestfly);
                        $flyInnerListCount=count($bestfly[0])-1;
                        $flyInnerListTop=$bestfly[0][0]['@attributes'];
                        $flyInnerListBot=$bestfly[0][$flyInnerListCount]['@attributes'];

                        if($i==1)
                        {
                        error_log(print_r($index,true));
                        //return;
                        }
                        ?>
                 
                    
                    <div class="show-flight">                    
                        <div class="col-sm-8">
                            
                            
                            <div class="visl">
                                
                                <div class="row">
                                  
                                    <div class="col-sm-3 col-xs-6 flt-single arw">
                                          <input type="radio" class="radio-small bstradio " id="bestRo_<?php echo $i?>" name="selectFly"
                                                 value="<?php echo base64_encode(json_encode($bestfly[0])); ?>" />
                                       <p><?php echo ''?><br>
                                    <?php echo date("h:i a", strtotime($flyInnerListTop['DepartureTime'])) ?> <br>
                                    <?php echo date("D, d M Y", strtotime($flyInnerListTop['DepartureTime']))?>
                                </p>

                                    </div>
                                    <div class="col-sm-3 col-xs-6 flt-single">
                                      <p><?php echo ''?><br>
                               <?php echo date("h:i a", strtotime($flyInnerListBot['ArrivalTime'])) ?> <br>
                               <?php echo date("D, d M Y", strtotime($flyInnerListBot['ArrivalTime']))?></p>
                                    </div>
                                    <div class="col-sm-3 col-xs-4 flt-single drsn"><img src="../airimages/<?php echo $flyInnerListBot['Carrier']?>.GIF"> 
                                       <?php echo $flyUtil->getAirLineName($flyInnerListBot['Carrier'])?></div>
                                    <div class="col-sm-1 col-xs-4 flt-single"><a>
                                           <?php if($flyInnerListCount>0)
                                               {  
                                               echo $flyInnerListCount.' Stops';     
                                           }
                                           else
                                           {
                                             echo 'Direct' ;
                                           }
                                          ?>
                                        </a>
                                    </div>
                                    <div class="col-sm-2 col-xs-4 flt-single"><?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($flyInnerListTop['DepartureTime'])), date("h:i a", strtotime($flyInnerListBot['ArrivalTime']))) ?></div>
                                </div>
                                <?php 
                                if($bestSegDetailsCount >1)
                                    {
                           for($mtime=1;$mtime<$bestSegDetailsCount;$mtime++)
                                    {
                                     
                                        $moreFly=$bestfly[$mtime];
                                        $count=count($moreFly)-1;
                                        $more_fst=$moreFly[0]['@attributes'];
                                        $more_lst=$moreFly[$count]['@attributes'];
                                    ?>
                                
                                
                                <div class="row more_time">
                                    <a role="button" data-toggle="collapse" href="#moreFlights_<?php echo $mtime?>" aria-expanded="false" 
                                                             accesskey="<?php echo $i.'_'.$mtime?>" class="moretime_btn" id="<?php echo $mtime?>" aria-controls="moreFlights_<?php echo $mtime?>">view more TIMING</a>
                                     <div class="collapse" id="moreFlights_<?php echo $mtime?>" aria-hidden=true accesskey="" >
                                                             <div class="row">
                                    <div class="col-sm-3 col-xs-6 flt-single arw">
                                          <input type="radio" class="radio-small mTradio" id="bestRo_<?php echo $i?>@moreRo_<?php echo $mtime?>" name="selectFly"  
                                                 value="<?php echo base64_decode($moreFly)?>"  />
                                       <p><?php echo ''?><br>
                                    <?php echo date("h:i a", strtotime($more_fst['DepartureTime'])) ?> <br>
                                    <?php echo date("D, d M Y", strtotime($more_fst['DepartureTime']))?>
                                </p>

                                    </div>
                                    <div class="col-sm-3 col-xs-6 flt-single">
                                      <p><?php echo ''?><br>
                               <?php echo date("h:i a", strtotime($more_lst['ArrivalTime'])) ?> <br>
                               <?php echo date("D, d M Y", strtotime($more_lst['ArrivalTime']))?></p>
                                    </div>
                                    <div class="col-sm-3 col-xs-4 flt-single drsn"><img src="../airimages/<?php echo $more_lst['Carrier']?>.GIF"> 
                                       <?php echo $flyUtil->getAirLineName($more_lst['Carrier'])?></div>
                                    <div class="col-sm-1 col-xs-4 flt-single"><a>
                                           <?php if($count>0)
                                               {  
                                               echo $flyInnerListCount.' Stops';     
                                           }
                                           else
                                           {
                                             echo 'Direct' ;
                                           }
                                          ?>
                                        </a>
                                    </div>
                                    <div class="col-sm-2 col-xs-4 flt-single"><?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($more_fst['DepartureTime'])), date("h:i a", strtotime($more_lst['ArrivalTime']))) ?></div>
                                </div>
                                                          </div>
                                 </div>
                                
                                
                                       <?php } 
                                       
                                           } ?>
                            </div>

                            
                            
                            <div class="edit-visl">
                                
                                
                                <div class="head"><i class="fa fa-plane"></i> Depart</div>
                                <?php
                                $flyDetailsList=$bestfly[0];
                                foreach($flyDetailsList as $index)
                                {
                                    $item=$index['@attributes'];
                                    ?>
                                <div class="row flt-dtl">
                                    <div class="col-sm-4 col-xs-6 arw">
                                         <p>Kuala Lumpur (KUL)<br>
                                         <?php echo 'colombo'?><br>
                                    <?php echo date("h:i a", strtotime($item['DepartureTime'])) ?> <br>
                                    <?php echo date("D, d M Y", strtotime($item['DepartureTime']))?>
                                            </p>
                                    </div>
                                    <div class="col-sm-4 col-xs-6">
                                          <p>Kuala Lumpur (KUL)<br>
                                         <?php echo 'colombo'?><br>
                                    <?php echo date("h:i a", strtotime($item['ArrivalTime'])) ?> <br>
                                    <?php echo date("D, d M Y", strtotime($item['ArrivalTime']))?>
                                            </p>
                                    </div>

                                    <div class="col-sm-4">
                                        <p><i class="fa fa-clock-o"></i> <?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($item['DepartureTime'])), date("h:i a", strtotime($item['ArrivalTime']))) ?></p>
                                    </div>

                                </div>
                                <?php }?>
                                <div class="vgn"><p><img src="../airimages/OD.GIF"> Malindo Airways - OD186  <a>Economy V</a> - Aircraft 738</p></div>
                                <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> 3hrs 50mins</div>
       
                            </div>

                            <div class="clearfix"></div>

                        </div>

                        <div class="col-sm-4 show-flight-prize">

                            <div class="flt-prz">

                                <label>Per Adult OneWay</label>

                                <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz" value="LKR 27667" readonly="">

                                <form name="frmAvail" action="../free-booking" method="post">

                                    <input type="hidden" name="bookingdata" value="YTo1OntzOjExOiJzZWdtZW50ZHRscyI7YToyOntpOjA7YToyMjp7czozOiJLZXkiO3M6MjQ6InhMVWpOdUJBQUEvQm1xUmJQUkFBQUE9PSI7czo1OiJHcm91cCI7czoxOiIwIjtzOjc6IkNhcnJpZXIiO3M6MjoiT0QiO3M6MTI6IkZsaWdodE51bWJlciI7czozOiIxODYiO3M6NjoiT3JpZ2luIjtzOjM6IkNNQiI7czoxMToiRGVzdGluYXRpb24iO3M6MzoiS1VMIjtzOjEzOiJEZXBhcnR1cmVUaW1lIjtzOjI5OiIyMDE3LTA1LTI3VDAwOjI1OjAwLjAwMCswNTozMCI7czoxMToiQXJyaXZhbFRpbWUiO3M6Mjk6IjIwMTctMDUtMjdUMDY6NDU6MDAuMDAwKzA4OjAwIjtzOjEwOiJGbGlnaHRUaW1lIjtzOjM6IjIzMCI7czo4OiJEaXN0YW5jZSI7czo0OiIxNTI2IjtzOjE0OiJFVGlja2V0YWJpbGl0eSI7czozOiJZZXMiO3M6OToiRXF1aXBtZW50IjtzOjM6IjczOCI7czoxMzoiQ2hhbmdlT2ZQbGFuZSI7czo1OiJmYWxzZSI7czoxNjoiUGFydGljaXBhbnRMZXZlbCI7czoxMToiU2VjdXJlIFNlbGwiO3M6MTY6IkxpbmtBdmFpbGFiaWxpdHkiO3M6NDoidHJ1ZSI7czoyNDoiUG9sbGVkQXZhaWxhYmlsaXR5T3B0aW9uIjtzOjE3OiJQb2xsZWQgYXZhaWwgdXNlZCI7czoyNToiT3B0aW9uYWxTZXJ2aWNlc0luZGljYXRvciI7czo1OiJmYWxzZSI7czoxODoiQXZhaWxhYmlsaXR5U291cmNlIjtzOjE6IkwiO3M6MjM6IkF2YWlsYWJpbGl0eURpc3BsYXlUeXBlIjtzOjIyOiJGYXJlIFNob3AvT3B0aW1hbCBTaG9wIjtzOjE2OiJhaXJDb2Rlc2hhcmVJbmZvIjtzOjA6IiI7czoxMzoiQm9va2luZ0NvdW50cyI7czo1OToiQzd8Sjd8RDd8STV8WjN8WTd8QTd8Rzd8Vzd8Uzd8Qjd8SDd8Szd8TDd8TTd8Tjd8UTd8VDd8VjN8WDAiO3M6MTk6ImFpckZsaWdodERldGFpbHNSZWYiO3M6MjQ6InhMVWpOdUJBQUEvQm5xUmJQUkFBQUE9PSI7fWk6MTthOjIyOntzOjM6IktleSI7czoyNDoieExVak51QkFBQS9Cb3FSYlBSQUFBQT09IjtzOjU6Ikdyb3VwIjtzOjE6IjAiO3M6NzoiQ2FycmllciI7czoyOiJPRCI7czoxMjoiRmxpZ2h0TnVtYmVyIjtzOjM6IjUyMiI7czo2OiJPcmlnaW4iO3M6MzoiS1VMIjtzOjExOiJEZXN0aW5hdGlvbiI7czozOiJETUsiO3M6MTM6IkRlcGFydHVyZVRpbWUiO3M6Mjk6IjIwMTctMDUtMjdUMTY6MDA6MDAuMDAwKzA4OjAwIjtzOjExOiJBcnJpdmFsVGltZSI7czoyOToiMjAxNy0wNS0yN1QxNzoxMDowMC4wMDArMDc6MDAiO3M6MTA6IkZsaWdodFRpbWUiO3M6MzoiMTMwIjtzOjg6IkRpc3RhbmNlIjtzOjM6Ijc1NCI7czoxNDoiRVRpY2tldGFiaWxpdHkiO3M6MzoiWWVzIjtzOjk6IkVxdWlwbWVudCI7czozOiI3MzgiO3M6MTM6IkNoYW5nZU9mUGxhbmUiO3M6NToiZmFsc2UiO3M6MTY6IlBhcnRpY2lwYW50TGV2ZWwiO3M6MTE6IlNlY3VyZSBTZWxsIjtzOjE2OiJMaW5rQXZhaWxhYmlsaXR5IjtzOjQ6InRydWUiO3M6MjQ6IlBvbGxlZEF2YWlsYWJpbGl0eU9wdGlvbiI7czoxNzoiUG9sbGVkIGF2YWlsIHVzZWQiO3M6MjU6Ik9wdGlvbmFsU2VydmljZXNJbmRpY2F0b3IiO3M6NToiZmFsc2UiO3M6MTg6IkF2YWlsYWJpbGl0eVNvdXJjZSI7czoxOiJMIjtzOjIzOiJBdmFpbGFiaWxpdHlEaXNwbGF5VHlwZSI7czoyMjoiRmFyZSBTaG9wL09wdGltYWwgU2hvcCI7czoxNjoiYWlyQ29kZXNoYXJlSW5mbyI7czowOiIiO3M6MTM6IkJvb2tpbmdDb3VudHMiO3M6NTk6IkM3fEo3fEQ3fEk0fFoyfFk3fEE3fEc3fFc3fFM3fEI3fEg3fEs3fEw3fE03fE4yfFEyfFQwfFYwfFgwIjtzOjE5OiJhaXJGbGlnaHREZXRhaWxzUmVmIjtzOjI0OiJ4TFVqTnVCQUFBL0JwcVJiUFJBQUFBPT0iO319czoxMDoiQ29ubmVjdGlvbiI7YToyOntpOjA7YToxOntzOjEyOiJTZWdtZW50SW5kZXgiO3M6MToiMCI7fWk6MTthOjE6e3M6MTI6IlNlZ21lbnRJbmRleCI7czoxOiIwIjt9fXM6MTA6IkNhYmluQ2xhc3MiO2E6Mjp7aTowO3M6NzoiRWNvbm9teSI7aToxO3M6NzoiRWNvbm9teSI7fXM6MTE6IkJvb2tpbmdDb2RlIjthOjI6e2k6MDtzOjE6IlYiO2k6MTtzOjE6IlEiO31zOjEwOiJwYXNzZW5nZXJzIjthOjI6e2k6MDthOjE6e2k6MDtzOjM6IkFEVCI7fWk6MTthOjE6e2k6MDtzOjM6IkFEVCI7fX19">

                                    <input type="hidden" name="searchdata" value="YToxNDp7czoxMjoiZnJvbV9haXJwb3J0IjtzOjM6IkNNQiI7czo5OiJmcm9tX2NpdHkiO3M6NzoiQ29sb21ibyI7czoxMDoidG9fYWlycG9ydCI7czozOiJCS0siO3M6NzoidG9fY2l0eSI7czo3OiJCYW5na29rIjtzOjEwOiJzdGFydF9kYXRlIjtzOjEwOiIyMDE3LTA1LTI3IjtzOjg6ImVuZF9kYXRlIjtzOjA6IiI7czoxMDoicGFzc2VuZ2VycyI7YToxOntpOjA7czozOiJBRFQiO31zOjEwOiJjYWJpbmNsYXNzIjtzOjc6IkVjb25vbXkiO3M6NToiYWR1bHQiO3M6MToiMSI7czo1OiJjaGlsZCI7czoxOiIwIjtzOjY6ImluZmFudCI7czoxOiIwIjtzOjEwOiJkYXRlX2ZsZXhpIjtzOjE6IjAiO3M6NDoibW9kZSI7czo2OiJvbmV3YXkiO3M6NDoiaWF0YSI7czowOiIiO30=">

                                    <input type="hidden" name="searchmode" value="oneway">

                                    <button type="submit">Make Free Booking</button>

                                </form>

                                <form name="frmAvail" action="../booking" method="post">

                                    <!-- Rajib added -->
                                    <input type="hidden" name="connectiondata" value="YToxOntzOjEyOiJTZWdtZW50SW5kZXgiO3M6MToiMCI7fQ==">
                                    <!-- Rajib added -->
                                    <input type="hidden" name="bookingdata" value="YTo1OntzOjExOiJzZWdtZW50ZHRscyI7YToyOntpOjA7YToyMjp7czozOiJLZXkiO3M6MjQ6InhMVWpOdUJBQUEvQm1xUmJQUkFBQUE9PSI7czo1OiJHcm91cCI7czoxOiIwIjtzOjc6IkNhcnJpZXIiO3M6MjoiT0QiO3M6MTI6IkZsaWdodE51bWJlciI7czozOiIxODYiO3M6NjoiT3JpZ2luIjtzOjM6IkNNQiI7czoxMToiRGVzdGluYXRpb24iO3M6MzoiS1VMIjtzOjEzOiJEZXBhcnR1cmVUaW1lIjtzOjI5OiIyMDE3LTA1LTI3VDAwOjI1OjAwLjAwMCswNTozMCI7czoxMToiQXJyaXZhbFRpbWUiO3M6Mjk6IjIwMTctMDUtMjdUMDY6NDU6MDAuMDAwKzA4OjAwIjtzOjEwOiJGbGlnaHRUaW1lIjtzOjM6IjIzMCI7czo4OiJEaXN0YW5jZSI7czo0OiIxNTI2IjtzOjE0OiJFVGlja2V0YWJpbGl0eSI7czozOiJZZXMiO3M6OToiRXF1aXBtZW50IjtzOjM6IjczOCI7czoxMzoiQ2hhbmdlT2ZQbGFuZSI7czo1OiJmYWxzZSI7czoxNjoiUGFydGljaXBhbnRMZXZlbCI7czoxMToiU2VjdXJlIFNlbGwiO3M6MTY6IkxpbmtBdmFpbGFiaWxpdHkiO3M6NDoidHJ1ZSI7czoyNDoiUG9sbGVkQXZhaWxhYmlsaXR5T3B0aW9uIjtzOjE3OiJQb2xsZWQgYXZhaWwgdXNlZCI7czoyNToiT3B0aW9uYWxTZXJ2aWNlc0luZGljYXRvciI7czo1OiJmYWxzZSI7czoxODoiQXZhaWxhYmlsaXR5U291cmNlIjtzOjE6IkwiO3M6MjM6IkF2YWlsYWJpbGl0eURpc3BsYXlUeXBlIjtzOjIyOiJGYXJlIFNob3AvT3B0aW1hbCBTaG9wIjtzOjE2OiJhaXJDb2Rlc2hhcmVJbmZvIjtzOjA6IiI7czoxMzoiQm9va2luZ0NvdW50cyI7czo1OToiQzd8Sjd8RDd8STV8WjN8WTd8QTd8Rzd8Vzd8Uzd8Qjd8SDd8Szd8TDd8TTd8Tjd8UTd8VDd8VjN8WDAiO3M6MTk6ImFpckZsaWdodERldGFpbHNSZWYiO3M6MjQ6InhMVWpOdUJBQUEvQm5xUmJQUkFBQUE9PSI7fWk6MTthOjIyOntzOjM6IktleSI7czoyNDoieExVak51QkFBQS9Cb3FSYlBSQUFBQT09IjtzOjU6Ikdyb3VwIjtzOjE6IjAiO3M6NzoiQ2FycmllciI7czoyOiJPRCI7czoxMjoiRmxpZ2h0TnVtYmVyIjtzOjM6IjUyMiI7czo2OiJPcmlnaW4iO3M6MzoiS1VMIjtzOjExOiJEZXN0aW5hdGlvbiI7czozOiJETUsiO3M6MTM6IkRlcGFydHVyZVRpbWUiO3M6Mjk6IjIwMTctMDUtMjdUMTY6MDA6MDAuMDAwKzA4OjAwIjtzOjExOiJBcnJpdmFsVGltZSI7czoyOToiMjAxNy0wNS0yN1QxNzoxMDowMC4wMDArMDc6MDAiO3M6MTA6IkZsaWdodFRpbWUiO3M6MzoiMTMwIjtzOjg6IkRpc3RhbmNlIjtzOjM6Ijc1NCI7czoxNDoiRVRpY2tldGFiaWxpdHkiO3M6MzoiWWVzIjtzOjk6IkVxdWlwbWVudCI7czozOiI3MzgiO3M6MTM6IkNoYW5nZU9mUGxhbmUiO3M6NToiZmFsc2UiO3M6MTY6IlBhcnRpY2lwYW50TGV2ZWwiO3M6MTE6IlNlY3VyZSBTZWxsIjtzOjE2OiJMaW5rQXZhaWxhYmlsaXR5IjtzOjQ6InRydWUiO3M6MjQ6IlBvbGxlZEF2YWlsYWJpbGl0eU9wdGlvbiI7czoxNzoiUG9sbGVkIGF2YWlsIHVzZWQiO3M6MjU6Ik9wdGlvbmFsU2VydmljZXNJbmRpY2F0b3IiO3M6NToiZmFsc2UiO3M6MTg6IkF2YWlsYWJpbGl0eVNvdXJjZSI7czoxOiJMIjtzOjIzOiJBdmFpbGFiaWxpdHlEaXNwbGF5VHlwZSI7czoyMjoiRmFyZSBTaG9wL09wdGltYWwgU2hvcCI7czoxNjoiYWlyQ29kZXNoYXJlSW5mbyI7czowOiIiO3M6MTM6IkJvb2tpbmdDb3VudHMiO3M6NTk6IkM3fEo3fEQ3fEk0fFoyfFk3fEE3fEc3fFc3fFM3fEI3fEg3fEs3fEw3fE03fE4yfFEyfFQwfFYwfFgwIjtzOjE5OiJhaXJGbGlnaHREZXRhaWxzUmVmIjtzOjI0OiJ4TFVqTnVCQUFBL0JwcVJiUFJBQUFBPT0iO319czoxMDoiQ29ubmVjdGlvbiI7YToyOntpOjA7YToxOntzOjEyOiJTZWdtZW50SW5kZXgiO3M6MToiMCI7fWk6MTthOjE6e3M6MTI6IlNlZ21lbnRJbmRleCI7czoxOiIwIjt9fXM6MTA6IkNhYmluQ2xhc3MiO2E6Mjp7aTowO3M6NzoiRWNvbm9teSI7aToxO3M6NzoiRWNvbm9teSI7fXM6MTE6IkJvb2tpbmdDb2RlIjthOjI6e2k6MDtzOjE6IlYiO2k6MTtzOjE6IlEiO31zOjEwOiJwYXNzZW5nZXJzIjthOjI6e2k6MDthOjE6e2k6MDtzOjM6IkFEVCI7fWk6MTthOjE6e2k6MDtzOjM6IkFEVCI7fX19">

                                    <input type="hidden" name="searchdata" value="YToxNDp7czoxMjoiZnJvbV9haXJwb3J0IjtzOjM6IkNNQiI7czo5OiJmcm9tX2NpdHkiO3M6NzoiQ29sb21ibyI7czoxMDoidG9fYWlycG9ydCI7czozOiJCS0siO3M6NzoidG9fY2l0eSI7czo3OiJCYW5na29rIjtzOjEwOiJzdGFydF9kYXRlIjtzOjEwOiIyMDE3LTA1LTI3IjtzOjg6ImVuZF9kYXRlIjtzOjA6IiI7czoxMDoicGFzc2VuZ2VycyI7YToxOntpOjA7czozOiJBRFQiO31zOjEwOiJjYWJpbmNsYXNzIjtzOjc6IkVjb25vbXkiO3M6NToiYWR1bHQiO3M6MToiMSI7czo1OiJjaGlsZCI7czoxOiIwIjtzOjY6ImluZmFudCI7czoxOiIwIjtzOjEwOiJkYXRlX2ZsZXhpIjtzOjE6IjAiO3M6NDoibW9kZSI7czo2OiJvbmV3YXkiO3M6NDoiaWF0YSI7czowOiIiO30=">

                                    <input type="hidden" name="searchmode" value="oneway">

                                    <button type="submit">Book Now</button>

                                </form>

                                <form name="frmFL" id="frmFL1" method="post" action="../farerule.php" target="_blank">

                                    <input type="hidden" name="k" value="gws-eJxNTkEOwjAMe8zkuxtghVthZUJq6S4MtAv/f8bcVkhYiqMkTpwQgtF5nmjhDwO+wxJR1gkoMEVaM0Ybj3AqNpA84718ckLfN69+abOeXVPFQzQQM2frkwpsjafn7Xey+kE6VLo/lMo1vnKiyeciqO2hj3aV2Sa3">

                                    <input type="hidden" name="ref" value="xLUjNuBAAA/ByrRbPRAAAA==">

                                    <p><a class="shw2" onclick="jQuery('#frmFL1').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>

                                </form>

                                <p><a class="shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>

                            </div>

                            <div class="clearfix"></div>

                        </div>

                        <div class="clearfix"></div>

                    </div>

                   
                    <?php }?>
                    
                    
                </div>

            </div>

        </div>

    </div>
</div>

<?php }?>