<script type="application/javascript" src='http://localhost:8080/travel/wp-content/plugins/traveler/js/oneway.js'/>

<?php
include(PLUG_DIR . 'utility/FlightUtility.php');

function oneway_init_display($response)
{
$flyUtil = new FlightUtility();?>
<div class="container sky-bg flight_con">
    <?php foreach ($response as $index => $item)
    {
        $bestfly = $item->bestFly->segDetails;
        $bestPrice = $item->bestFly->priceDetails;//['@attributes']['TotalPrice'];
        $best_fly_count = count($bestfly);
        $flyInnerListCount = count($bestfly[0]);
        $flyInnerListTop = $bestfly[0][0]['@attributes'];
        $flyInnerListBot = $bestfly[0][$flyInnerListCount]['@attributes'];
        $secFlyList = '';
        if (isset($item->secFly)) {

            $secFlyList = $item->secFly;

        }
        $more_fly_count = count($secFlyList);
        ?>

        <div class="row ticket_details">
            <div class="col-lg-8 col-md-9  col-sm-12 col-xs-12 tiket_head_ow">
                <img class="hdimg img-responsive">
            </div>

            <div class="tiket_body ticketbd col-lg-8 col-md-9  col-sm-12 col-xs-12">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ow-tkt-dts-row ow-tkt-dtsmain">
                    <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 ow-tkt-dts sub-tkt" >

                        <?php
                        get_flight_segment_origin($flyInnerListTop, $index,  $bestfly[0], $flyInnerListCount);

                        get_flight_segment_des($flyInnerListBot, $index,  $bestfly[0], $more_fly_count);
                        get_other_details($flyInnerListTop, $flyUtil, $flyInnerListCount, $flyInnerListBot);

                        ?>

                    </div>

                    <?php

                    get_price_details($bestfly[0], $index,$bestPrice);

                    ?>
                </div>
                <?php  get_flight_details($bestfly[0],$index,$flyUtil);
                ?>
                <?php if($flyInnerListCount >1)
                {?>
                    <div class="col-lg-9 more-time collapse more-flights" id="moreTime<?php echo $index ?>" aria-hidden=true
                         accesskey="">
                        <?php get_more_time_fly($bestfly,$index,$flyUtil)?>
                    </div>
                <?php }?>

            </div>

        </div>
        <?php
        if ($more_fly_count > 1) { ?>

            <?php
            more_flights_oneway($secFlyList,$index,$flyUtil); ?>

            <?php
        }

    }
    return;
    }
    function get_flight_segment_origin($tem,$index,$select_item,$flyInnerListCount)
    {?>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 v1_fsq">
            <p class="ow-tks-p">
                <?php echo $tem['Origin'] ?><br>
                <input type="radio" class="flySelect ow-tks-radio" key="<?php echo $index ?>"
                       name="one-way-select"
                       value="<?php echo base64_encode(json_encode($select_item)) ?>"
                       checked="checked">
                <?php echo date("h:i a", strtotime($tem['DepartureTime'])) ?><br>
                <?php echo date("D, d M Y", strtotime($tem['DepartureTime'])) ?></p>
            <?php
            if ($flyInnerListCount > 1)
            { ?>
                <span class="ow-tks-span">
            <a role="button" class="btn btn-info btn-sm out-select out-moretime"
               data-toggle="collapse" href="#moreTime<?php echo $index ?>"
               aria-expanded="false" aria-controls="moreTime<?php echo $index ?>">more timing</a>
        </span>
            <?php } ?>

        </div>
        <?php
    }


    function get_flight_segment_des($tem,$index,$best_fly,$more_fly_count)
    {?>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6 v1_fsq">
            <p class="ow-tks-p"><?php echo $tem['Destination'] ?>ggg<br>
                <?php echo date("h:i a", strtotime($tem['ArrivalTime'])) ?><br>
                <?php echo date("D, d M Y", strtotime($tem['ArrivalTime'])) ?></p>
            <?php
            if ($more_fly_count > 1) { ?>
                <span class="ow-tks-span">
            <a role="button" class="btn btn-info btn-sm out-select out-moretime"
               data-toggle="collapse" href="#moreFlys<?php echo $index ?>"
               aria-expanded="false" aria-controls="moreFlys<?php echo $index ?>">more flights</a>
        </span>
            <?php } ?>
        </div>

        <?php
    }
    function get_price_details($select_fly,$Key,$bestPrice)
    {
        $base_price= $bestPrice['@attributes']['TotalPrice'];?>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 ow-prz-dts">

            <form name="frmAvail" action="../booking" method="post">
                <label class="oneway-price"><?php echo $base_price?></label>

                <input type="hidden" name="outbound" class="book_out<?php echo $Key ?>"  value="<?php echo base64_encode(serialize($select_fly)); ?>">
                <input type="hidden" name="priceData" value="<?php echo base64_encode(serialize($bestPrice)) ?>">
                <input type="hidden" name="inbound" class="book_out<?php echo $Key ?>"  value="">

                <input type="hidden" name="searchdata" value="==">
                <input type="hidden" name="searchmode" value="oneway">
                <button type="submit" name="book" class="btn-sm btn-success ow-bk-bt"
                        key="<?php echo $Key ?>">Book Now
                </button>
            </form>

            <form name="frmFL" id="frmFL1" method="post" action="../farerule.php" target="_blank">
                <input type="hidden" name="k"
                       value="gws-eJxNTtEKAjEM+5gj72knzL3Nqx7KvCmoyL34/59x3U7BQBNKQtqcs1Iidyr5DwM+Q3qjvgyoUJ+xFCQNCvFlAck9bvq4y3wt+FZEt2q3N5UetGAJxKQTN6cBS2ebx19rOwnPodHp7FIPx6ddtJ0Siga6EeFvrUp/J3s=">
                <input type="hidden" name="ref" value="ErvqNukJ0BKAJBnh7AAAAA==">
                <p><a class="ow-shw-fr" onclick="jQuery('#frmFL1').submit();"><span><i
                                class="fa fa-plus"></i> Fare Rules</span></a></p>
            </form>

            <p><a class="ow-shw"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>

        </div> <?php
    }

    function get_other_details($itemTop,$flyUtil,$flyInnerListCount,$itemLast)
    { ?>
        <p  class="ow-tks-p">
        <div class="col-lg-3 col-md-2 col-sm-2 col-xs-6 imgaj">
            <?php $airLineCode = $itemTop['Carrier']; ?>
            <img src="../airimages/<?php echo $airLineCode ?>.GIF"> <br>
            <?php
            $airlineName = $flyUtil->getAirLineName($airLineCode);
            echo $airlineName ?></div> </p>
        <p  class="ow-tks-p">
        <div class="col-xs-2 col-md-2 col-lg-1 col-sm-2 phraj"><label><?php
                if ($flyInnerListCount > 0) {
                    echo $flyInnerListCount . ' Stops';
                } else {
                    echo 'Direct';
                }
                ?></label></div> </p>
        <p class="ow-tks-p">
        <div class="col-xs-2 col-md-2 col-lg-2 col-sm-2 v1_fsq phraj"><label>
                <?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($itemLast['ArrivalTime'])), date("h:i a", strtotime($itemTop['DepartureTime']))) ?>
            </label></div></p>
        <?php
    }
    function more_flights_oneway($secFlyList,$index,$flyUtil)
    {
        ?>
        <div class=" row collapse more-flights" id="moreFlys<?php echo $index ?>" aria-hidden=true
             accesskey="">
            <?php
            foreach ($secFlyList as $in => $it)
            {

                $item=$it->segDetails;

                $fly_item_count = count($item[0]) - 1;
                $fly_item_top = $item[0][0]['@attributes'];

                $fly_item_bot = $item[0][$fly_item_count ]['@attributes'];


                ?>
                <!--                 <div class="row ticket_details">-->
                <!--                     <div class="container tiket_head_ow">-->
                <!--                         <div class="row">-->
                <!--                         </div>-->
                <!--                     </div>-->

                <div class="container tiket_body">
                    <div class="row ow-tkt-dts-row more-fly-dts">
                        <div class="col-lg-9 col-md-9 ow-tkt-dts ">
                            <div class="row">

                                <?php
                                get_flight_segment_origin($fly_item_top,$index."_".$in,$item[0],0);
                                get_flight_segment_des($fly_item_bot,$index."_".$in,$item[0],0);
                                get_other_details($fly_item_top,$flyUtil,0,$fly_item_bot);
                                ?>

                            </div>
                        </div>
                        <?php get_price_details($item[0], $index); ?>

                    </div>
                    <?php   get_flight_details($item,$index."_".$in,$flyUtil); ?>
                </div>
            <?php  }?>



        </div>

    <?php }

    function  get_flight_details($item,$index,$fly_util)
    {
        ?>
        <!-- Flight Details Start-->
        <div class="edit-visl edit-vsl-ow">


            <div class="head">
                <i class="fa fa-plane"></i> Depart
            </div>
            <!-- *********************************outBound details******************************outBound******************************************************************************-->
            <div class="oneway_<?php echo $index ?>">
                <?php

                $temp_count=count($item)-1;

                foreach ($item as $in) {

                    $temp = $in['@attributes'];
                    $temp_dts=$in[0]['@attributes'];
                    error_log("details ---------->".print_r($in,true));
                    ?>
                    <div class="row flt-dtl">
                        <div class="col-sm-4 col-xs-6 arw">
                            <p><?php echo $fly_util->getCityName($temp['Origin']) ?><br>
                                <?php echo $temp['Origin'] ?><br>
                                <?php echo $fly_util->getAirLineName($temp['Carrier']) ?><br>
                                <?php echo date("h:i a", strtotime($temp['DepartureTime'])) ?> <br>
                                <?php echo date("D, d M Y", strtotime($temp['DepartureTime'])) ?></p>
                        </div>
                        <div class="col-sm-4 col-xs-6">
                            <p><?php echo $fly_util->getCityName($temp['Destination']) ?><br>
                                <?php echo $temp['Destination'] ?><br>
                                <?php echo $fly_util->getAirLineName($temp['Carrier']) ?><br>
                                <?php echo date("h:i a", strtotime($temp['ArrivalTime'])) ?> <br>
                                <?php echo date("D, d M Y", strtotime($temp['ArrivalTime'])) ?></p>
                        </div>

                        <div class="col-sm-4">
                            <p><i class="fa fa-clock-o"></i>
                                <?php echo $fly_util->getTimeDiff(date("h:i a", strtotime($temp['DepartureTime'])), date("h:i a", strtotime($temp['ArrivalTime']))) ?></p>
                        </div>

                    </div>

                    <div class="vgn"><p><img src="../airimages/<?php echo $temp['Carrier'] ?>.GIF">
                            <?php echo $fly_util->getAirLineName($temp['Carrier']) ?> -
                            <?php echo $temp['Carrier'] . $temp['Equipment'] ?> <a>
                                <?php echo $temp_dts['CabinClass'] . ' ' . $temp_dts['BookingCode'] ?></a> - Aircraft  <?php echo $temp['FlightNumber'] ?></p>
                    </div>


                    <?php
                }
                $totaltime = $fly_util->getTimeDiff(date("h:i a", strtotime($item[$temp_count]['@attributes']['ArrivalTime'])), date("h:i a", strtotime($item[0]['@attributes']['DepartureTime'])));

                ?><div class="btm">
                    <i class="fa fa-clock-o"></i>
                    <strong>TOTAL DURATION</strong> <?php echo $totaltime ?>
                </div>
                <?php
                ?>


            </div>
            <!-- ***************************************In bound details*************************InBound*****************************************************************************-->



            <div class="clearfix"></div>



        </div>
        <!-- Flight Details End--><?php



    }
    function get_more_time_fly($item,$index,$fly_util)

    {
        $itemCount=count($item);
        for($in=1;$in<$itemCount;$in++){
            $upper=$item[$in];
            $upperCount=count($upper)-1;
            foreach($item[$in] as $arr)
            {
                $temp=$arr['@attributes'];

                ?>

                <div class="col-lg-9 ow-tkt-dts more-time-dts">
                    <div class="row">
                        <div class= "col-lg-4 ">
                            <p class="ow-tks-p">
                                <input type="radio" class="flySelect ow-tks-radio-more-time" key="<?php echo $index ?>"
                                       name="selectFly"
                                       value="<?php echo base64_encode(json_encode($item)) ?>"
                                       checked="checked">
                                <?php echo date("h:i a D, d M y ", strtotime($temp['DepartureTime'])) ?><br>

                        </div>
                        <div class= "col-lg-4">
                            <p class="ow-tks-p">
                                <?php echo date("h:i a D, d M y ", strtotime($temp['ArrivalTime'])) ?><br>

                        </div>
                        <p  class="ow-tks-p">
                        <div class="col-xs-6 col-lg-2 "><label><?php
                                if ($itemCount > 0) {
                                    echo $itemCount . ' Stops';
                                } else {
                                    echo 'Direct';
                                }
                                ?></label></div> </p>

                        <p class="ow-tks-p">
                        <div class="col-xs-6 col-lg-2 "><label>
                                <?php echo $fly_util->getTimeDiff(date("h:i a", strtotime($upper[$upperCount]['@attributes']['ArrivalTime'])), date("h:i a", strtotime($upper[0]['@attributes']['DepartureTime']))) ?>
                            </label></div></p>
                    </div>
                </div>

                <?php


            } }} ?>



