
<?php
include(PLUG_DIR . 'utility/FlightUtility.php'); 
 
function init_display($response) {$flyUtil =new FlightUtility(); ?>


<div class="container sky-bg flight_con">
    
        <div class="row fstrow">
            <div class="col-lg-12 fstmainrow">                
                <div class="col-lg-2 fstcol1">Colombo (CMB)</div>
                <div class="col-lg-2 fstcol1">Kuala Lumpur (KUL)</div>
                <div class="col-lg-2 fstcol1" >DEPART <br/>
                        18/04/2017</div>
                <div class="col-lg-2 fstcol1">RETURN <br/>
                        21/04/2017</div>
                <div class="col-lg-2 fstcol1">1 Adult 0 Infant 0 Child <br/>
                        Economy</div>
                <div  class="col-lg-2 fstcol1"><button class=" btn-lg btnsearch">Edit Search</button></div>
            </div>
        </div>
           
    
    
        <div class="row secrow fontMeera">
            <form action="" method="" id="frmflight">
            <div class="col-lg-12 secrowmain ">                
                <div class="col-lg-2 secrowcol1" >
                    <input type="radio" class='txtradio' name="tripval" id="roundtripval"/> Round Trip <br/>
                    <input type="radio" class='txtradio' name="tripval" id="onewayval"/> One Way
                </div>
                <div class="col-lg-2 secrowcol2 ">
                     <input type="text" class='lbltxtarea' style="width: 200px;" value="ORIGIN"/>
                     <input type="text" name="depfrom" id="departfromid" value="CMB, COLOMBO" style="width: 200px;border:none;"/>                    
                </div>
                <div class="col-lg-2 ">
                    <input type="text" class='lbltxtarea' style="width: 200px;" value="DESTINATION"/>
                    <input type="text" name="depdestination" id="depdestinationid" value='KUL,Kuala Lumpur (KUL)' style="width: 200px;border:none;"/>
                </div>
                <div class="col-lg-2 secrowcol3">
                    <input type="text" class='lbltxtarea' style="width: 50%;" value="ADULTS:" />
                    <input type="text" name="depadult"  id="depadultid" style="width: 50%;border:none;" id="adultcol" />
                </div>
                <div class="col-lg-2 secrowcol4">
                    <input type="text" class='lbltxtarea' style="width: 50%;float:left;margin: 0px 0px 0px -23px;" value="CHILDREN:"/>                   
                    <input type="text" class='lbltxtarea' style="width: 50%;float:left;margin: 0px 0px 0px 9px;" value="INFANTS:"/>
                     <input type="text" name="depchild"  id='depchildid'/>
                    <input type="text" name="depinfant" id='depinfantid'/> 
                </div>
                <div  class="col-lg-1 secrowcol5"><button class=' btn-lg flightbtn'></button></div>
            </div>
        
            <div class="col-lg-12 thdrowmain">                
                <div class="col-lg-2 thdrowcol1">
                    
                </div>
                <div class="col-lg-2">
                    <input type="text" class='lbltxtarea' style="width:200px;" value="DEPART DATE"/>
                     <input type="text" name="depdate" id="depdateid" style="width:200px;border:none;"/>                    
                </div>
                <div class="col-lg-2 thdrowcol2">
                    <input type="text" class='lbltxtarea' style="width:200px;" value="ARRIVAL DATE"/>
                    <input type="text" name="deparrive" id="deparriveid" style="width:200px;border:none;"/>
                </div>
                <div class="col-lg-3 thdrowcol3">
                    <input type="text" class='lbltxtarea' style="width: 110px;margin: 0px 0px 0px 6px;" value="CLASS:"/>
                    <input type="text" name="depclass" id="depclassid"/>
                </div>
                <div class="col-lg-3 thdrowcol4">
                     <input type="text" class='lbltxtarea'  style="margin: 0px 82px;float: left;width: 120px;" value="SELECT AIRLINE:"/>
                    <input type="text" name="depairln" id="depairlnid"/> 
                </div>
                
            </div>
            </form>    
        </div>  
          
    
      <?php foreach($response as $index=>$item){ 
                         $bestfly=$item->bestFly->segDetails;
                         $bestPrice=$item->bestFly->priceDetails['@attributes']['TotalPrice'];
                         $bestflyCont=count($bestfly);
                         $flyInnerListCount=count($bestfly[0])-1;
                        $flyInnerListTop=$bestfly[0][0]['@attributes'];
                        $flyInnerListBot=$bestfly[0][$flyInnerListCount]['@attributes'];
                        $secFlyList='';
                        if(isset($item->secFly))
                        {
                            error_log("inside secfly");    
                        $secFlyList=$item->secFly;
                        error_log(print_r($secFlyList,true));
                        }
//                        if($index==2)
//                        {
//                        error_log(print_r($bestfly,true));
//                        return;
//                        }
                        ?>
       
    <div class="row ticket_details">
        <div class="container tiket_head_ow">
            <div class="row">
               
            </div>
        </div>
        <div class="container tiket_body">
            <div class="row ow-tkt-dts-row">
            <div class="col-lg-9 ow-tkt-dts">
                <div class="row">
                    <div class="col-xs-6 col-lg-3 v1_fsq">
                                                        <p>
                                                         <?php echo $flyInnerListTop['Origin']?><br>
                                                        <input type="radio" class="flySelect" key="<?php echo $index?>" name="selectFly" value="<?php echo base64_encode(json_encode($bestfly[0]))?>" checked="checked"> 
                                                        <?php echo date("h:i a", strtotime($flyInnerListTop['DepartureTime'])) ?><br>
                                                        <?php echo date("D, d M Y", strtotime($flyInnerListTop['DepartureTime']))?></p> 
                                                       
                                                        <?php if($bestflyCont>1)
                                                            { ?>
                                                                    <p> 

                                                                        <span>
                                                                            <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#moreTimeOut<?php echo $index ?>" aria-expanded="false" aria-controls="moreFlightsOut">more timings</a>

                                                                        </span>
                                                                    </p>
                                                        <?php } ?>
                                                    </div>
                    <div class="col-xs-6 col-lg-3 v1_fsq">    
                                                      <p>
                                                            <?php echo $flyInnerListBot['Destination']?><br>
                                                             <?php echo date("h:i a", strtotime($flyInnerListBot['ArrivalTime'])) ?><br>
                                                        <?php echo date("D, d M Y", strtotime($flyInnerListBot['ArrivalTime']))?></p>
                                                     
 <?php if(count($secFlyList)>1)
                                                            { ?>
                                                                    <p> 

                                                                        <span>
                                                                            <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#moreFlyOut<?php echo $index ?>" aria-expanded="false" aria-controls="moreFlightsOut">more flights</a>

                                                                        </span>
                                                                    </p>
                                                        <?php } ?>
                                                    </div>
                     <div class="col-xs-6 col-lg-3 v1_fsq">
                                                      <?php  $airLineCode=$flyInnerListBot['Carrier'];?>
                         <img src="../airimages/<?php echo $airLineCode ?>.GIF"> <br>
                                                   <?php
                                                    $airlineName=$flyUtil->getAirLineName($flyInnerListBot['Carrier']);
                                                    echo $airlineName ?></div>
                                                    <div class="col-xs-6 col-lg-1 v1_fsq"><?php
                                                                if ($flyInnerListCount > 0) {
                                                                    echo $flyInnerListCount . ' Stops';
                                                                } else {
                                                                    echo 'Direct';
                                                                }
                                                                ?></div>
                                                    <div class="col-xs-6 col-lg-2 v1_fsq">
                                                        <?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($flyInnerListTop['DepartureTime'])), date("h:i a", strtotime($flyInnerListBot['ArrivalTime']))) ?></div>
                </div>
                
                 <?php if($bestflyCont>1)
                                                    { ?>
                                                <div class=" row collapse moreFlights" id="moreTimeOut<?php echo $index?>" aria-hidden=true accesskey="" >
                                                    
                                                    <?php  
                                                    
                                                    for ($inerIdex=1;$inerIdex<$bestflyCont;$inerIdex++)
                                                    { 
                                                    
                                                        $indexCount=count($bestfly[$inerIdex])-1;
                                                        $item=$bestfly[$inerIdex];
                                                       $moreTop=$item[0]['@attributes'];
                                                       $moreBot=$item[$indexCount]['@attributes'];?>  
                                                  

                                                    
                                                    <div class="col-xs-6 col-lg-3 ">
                                                        <P>
                                                        <input type="radio" class="flySelect" key="<?php echo $index?>" name="selectFly" value="<?php echo base64_encode(json_encode($item))?>" checked="checked"> 
                                                       <?php echo date("h:i a", strtotime($moreTop['DepartureTime'])) ?><br>
                                                        
                                                       <?php echo date("D, d M Y", strtotime($moreTop['DepartureTime']))?></p>
                                                       
                                                  
                                                    </div>
                                                    <div class="col-xs-6 col-lg-3 ">    
                                                     
                                                        <P>
                                                        <?php echo date("h:i a", strtotime($moreBot['ArrivalTime'])) ?><br>
                                                        
                                                        <p><?php echo date("D, d M Y", strtotime($moreBot['ArrivalTime']))?></p>
                                                    </div>
                                                    <div class="col-xs-6 col-lg-3">
                                                        <img src="../airimages/<?php echo $airLineCode?>.GIF"><br>
                                                    
                                                   <?php echo $airlineName ?></div>
                                                    <div class="col-xs-6 col-lg-1"><?php
                                                                if ($flyInnerListCount > 0) {
                                                                    echo $flyInnerListCount . ' Stops';
                                                                } else {
                                                                    echo 'Direct';
                                                                }
                                                                ?></div>
                                                    <div class="col-xs-6 col-lg-2">
                                                        <?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($flyInnerListTop['DepartureTime'])), date("h:i a", strtotime($flyInnerListBot['ArrivalTime']))) ?>
                                                    </div>

                                                </div>
                                                    <?php } } ?>
                
                
                <?php if(count($secFlyList)>1)
                                                    { ?>
                                                <div class=" row collapse moreFlights" id="moreFlyOut<?php echo $index?>" aria-hidden=true accesskey="" >
                                                    
                                                  
                                                    <?php foreach($secFlyList as $out)
                                                        {  
                                                        foreach($out->segDetails as $in)
                                                            {
                                                            $indexCount = count($in) - 1;

                                                                        $top = $in[0]['@attributes'];
                                                                        $bot = $in[$indexCount]['@attributes'];
                                                                        ?>
                                                    <div class="row moreFlys">
                                                                          <div class="col-xs-6 col-lg-3 v1_fsq">
                                                                            <P>
                                                                            <input type="radio" class="flySelect" key="<?php echo $index ?>" name="selectFly" value="<?php echo base64_encode(json_encode($item)) ?>" checked="checked"> 
                                                                        <?php echo date("h:i a", strtotime($top['DepartureTime'])) ?><br>
                                                                            
                                                                        <?php echo date("D, d M Y", strtotime($top['DepartureTime'])) ?></p>
                                                                        </div>
                                                                        
                                                                        <div class="col-xs-6 col-lg-3 v1_fsq">    
                                                                              <P>
                                                                        <?php echo date("h:i a", strtotime($bot['ArrivalTime'])) ?><br>
                                                                        <?php echo date("D, d M Y", strtotime($bot['ArrivalTime'])) ?></p>
                                                                        </div>
                                                                        <div class="col-xs-6 col-lg-3 v1_fsq">
                                                                            <img src="../airimages/<?php echo $airLineCode ?>.GIF"><br>

                                                                            <?php echo $airlineName ?>
                                                                        </div>
                                                                        <div class="col-xs-6 col-lg-1 v1_fsq"><?php
                                                                            if ($indexCount > 0) {
                                                                                echo $indexCount . ' Stops';
                                                                            } else {
                                                                                echo 'Direct';
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        <div class="col-xs-6 col-lg-2 v1_fsq">
                                                                            <?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($bot['ArrivalTime'])), date("h:i a", strtotime($top['DepartureTime']))) ?>
                                                                        </div>
                                                    </div>
                                                        <?php } 
                                                        
                                                                            } ?>

                                                </div>
                                                    <?php  } ?>
            </div>
            <div class="col-lg-3 ow-prz-dts">
               

<!--                    <input type="text" name="flight-person-prize" class="cls-flight-prz" id="id-flight-prz"
                           value="<?php echo $basePrice['TotalPrice'] ?>" readonly="">-->

                    

                    <form name="frmAvail" action="../booking" method="post">
                    
                    <!-- Rajib added -->
                    <input type="hidden" name="connectiondata" value="Tjs=">
                    <!-- Rajib added -->

                    <input type="hidden" name="outbound" class="book_out<?php echo $Key ?>" value="">
                    
                    
                    <input type="hidden" name="priceData" value="<?php echo base64_encode(serialize($basePrice)) ?>">

                    <input type="hidden" name="searchdata" value="==">

                    <input type="hidden" name="searchmode" value="">

                    <button type="submit" name="book" class="btn-sm btn-success ow-bk-bt"  key="<?php echo $Key ?>">Book Now</button>

                    </form>

                    <form name="frmFL" id="frmFL1" method="post" action="../farerule.php" target="_blank">

                    <input type="hidden" name="k" value="gws-eJxNTtEKAjEM+5gj72knzL3Nqx7KvCmoyL34/59x3U7BQBNKQtqcs1Iidyr5DwM+Q3qjvgyoUJ+xFCQNCvFlAck9bvq4y3wt+FZEt2q3N5UetGAJxKQTN6cBS2ebx19rOwnPodHp7FIPx6ddtJ0Siga6EeFvrUp/J3s=">

                    <input type="hidden" name="ref" value="ErvqNukJ0BKAJBnh7AAAAA==">

                    <p><a class="ow-shw-fr" onclick="jQuery('#frmFL1').submit();"><span><i class="fa fa-plus"></i> Fare Rules</span></a></p>

                    </form>

                    <p><a class="ow-shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>
            </div>
            </div>
        </div>
    </div>
    
      <?php }?>
    
    </div>
<?php } ?>