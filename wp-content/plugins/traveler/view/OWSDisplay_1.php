<!--<script type="application/javascript" src='http://localhost:8080/travel/wp-content/plugins/traveler/js/tr-search.js'/>-->



<div id="resultloader">
    <div class="skyv1-bg">
        <div  class="container head_result">
            <div class="row">
                <label>Head</label>
            </div>
        </div>
        <div class="container main_con">
            <div class="row">
                <div class="container sch_edit_con">

                    <div class="row head_edit">

                        <div class="col-xs-6 col-sm-2 e_ori">colombo(CMB)</div>
                        <div class="col-xs-6 col-sm-2 e_des">bankok(BKK)</div>
                        <div class="col-xs-6 col-sm-2 e_dep">15-09-2045</div>
                        <div class="col-xs-6 col-sm-2 e_ret">15-09-2045</div>
                        <div class="col-xs-6 col-sm-3 e_pas"><div>1 adult 2child 1inf</div></div>
                        <div class="col-xs-6 col-sm-1 e_but"><div>Edit</div></div>

                    </div>

                    <div class="row edit_dts">

                        <div class="container edit_dts_con">
                            <div class="row">

                                <div class="col-lg-1 pull-left edit_rd_options ">
                                    <div class="row">
                                        <div class="col-xs-6 col-lg-7">
                                            <label class="radio-inline" >
                                                <input  type="radio" class="mode_rd" name="optradio">One Way 
                                            </label>
                                        </div>
                                        <div class="col-xs-6">
                                            <label class="radio-inline">
                                                <input  type="radio" class="mode_rd" name="optradio">Round Trip
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-10 ed_dts ">
                                    <div class="row edit_out">
                                        <div class="col-xs-6 col-lg-3">depature</div>
                                        <div class="col-xs-6 col-lg-3">destination</div>
                                        <div class="col-xs-4  col-lg-1">adult</div>
                                        <div class="col-xs-4 col-lg-1">child</div>
                                        <div class="col-xs-4 col-lg-1">infa</div>
                                    </div>
                                    <div class="row edit_in">
                                        <div class="col-xs-6 col-lg-3">depature</div>
                                        <div class="col-xs-6 col-lg-3">destination</div>
                                        <div class="col-xs-4 col-lg-1">adult</div>
                                        <div class="col-xs-4 col-lg-1">child</div>
                                        <div class="col-xs-4 col-lg-1">infa</div>
                                    </div>
                                </div> 
                                <div class="col-lg-1 edit_src_bt">
                                    Search
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <?php

            function init_disply($response) {

                foreach ($response as $items) {
                    ?> 



                    <div class="row fly_dts_row">


                        <div class="container fly_dts">
                            <div class="row ">
                                <div class="col-sm-12 tk_header">
                                </div>
                                <div class="tk_body">
                                    <div class="container">
                                        <div class="row">
                                            <div class="row">
                                                <div class="col-md-9 col-lg-8 tkout_dts">
                                                    <div class="row">
                                                        <div class="col-xs-6 col-lg-3 fly_seg">
                                                            <p>CMB</p></br>
                                                            <p> <input type="radio" class="flySelect" name="flySelectOut" value="" checked="checked"> 7:45 am</p></br>
                                                            <p>Tue,18 Apr 2017</p>
                                                            <p> <span>
                                                                    <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#moreFlightsOut" aria-expanded="false" aria-controls="moreFlightsOut">more timings</a>
                                                                    <i class="fa fa-plus"></i> 
                                                                </span></p>
                                                        </div>

                                                        <div class="col-xs-6 col-lg-3 fly_seg">    
                                                            <p>CMB</p></br>
                                                            <p>7:45 am</p></br>
                                                            <p>Tue,18 Apr 2017</p></div>
                                                        <div class="col-xs-6 col-lg-1">
                                                            image
                                                        </div>
                                                        <div class="col-xs-6 col-lg-2">Srilnak Airline</div>
                                                        <div class="col-xs-6 col-lg-1">Direct</div>
                                                        <div class="col-xs-6 col-lg-2">3 hrs 30 min</div>
                                                    </div>
                                                    <div class=" row collapse moreFlights" id="moreFlightsOut" aria-hidden=true accesskey="" >
                                                        more
                                                    </div>
                                                </div>
                                                <div class=" col-md-9 col-lg-8 tkin_dts">
                                                    <div class="row">
                                                        <div class="col-xs-6 col-lg-3 fly_seg">
                                                            <p>CMB</p></br>
                                                            <p><input type="radio" class="flySelect" name="flySelectOut">7:45 am</p></br>
                                                            <p>Tue,18 Apr 2017</p>
                                                            <p>
                                                                <span >
                                                                    <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#moreFlightsIn" aria-expanded="false" aria-controls="moreFlightsIn">more timings</a>
                                                                    <i class="fa fa-plus"></i> 
                                                                </span>

                                                            </p>
                                                        </div>
                                                        <div class=" col-xs-6 arrow_right">
                                                            <label>arrow</label>
                                                        </div>
                                                        <div class="col-xs-6 col-lg-3 fly_seg">    
                                                            <p>CMB</p></br>
                                                            <p>7:45 am</p></br>
                                                            <p>Tue,18 Apr 2017</p>

                                                        </div>
                                                        <div class="col-xs-6 col-lg-1">
                                                            image
                                                        </div>
                                                        <div class="col-xs-6 col-lg-2">Srilnak Airline
                                                            </br>
                                                            </br>
                                                            </br>
                                                            </br>        </br>

                                                            <p class="more_fly">
                                                                <span >
                                                                    <a role="button" class="btn btn-info btn-sm more_fly" data-toggle="collapse" href="#moreFly" aria-expanded="false" aria-controls="moreFly">more flights</a>
                                                                    <i class="fa fa-plus"></i> 
                                                                </span>
                                                            </p>
                                                        </div>
                                                        <div class="col-xs-6 col-lg-1">Direct</div>
                                                        <div class="col-xs-6 col-lg-2">3 hrs 30 min</div>


                                                    </div>
                                                    <div class=" row collapse moreFlyCon" id="moreFly" aria-hidden=true accesskey="" >
                                                        moreFly
                                                    </div>
                                                    <div class=" row collapse moreFlights" id="moreFlightsIn" aria-hidden=true accesskey="" >
                                                        more
                                                    </div>


                                                </div>
                                                <div class="col-xs-12 col-md-3 col-lg-4 pr_dts">
                                                    <div>Per Adult</div>
                                                    <div>LKR 89 000</div>
                                                    <div>BOOK</div>
                                                    <div>
                                                        <a>Fare Rules</a>
                                                    </div>
                                                    <div><p>

                                                            <a class="shw">
                                                                <span>
                                                                    <i class="fa fa-plus"></i> Show Flight Details
                                                                </span>
                                                            </a>
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row edit-visl col-lg-8 ">
                                                <div class="head">
                                                    <i class="fa fa-plane">    
                                                    </i> Depart
                                                </div>
                                                <div class="row flt-dtl ">
                                                    <div class="col-sm-4 col-xs-6 col-lg-6">
                                                        <p>Kuala Lumpur (KUL)<br>
                                                            colombo<br>
                                                            DepartureTime <br>
                                                            DepartureTime
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-4 col-xs-6 col-lg-6">
                                                        <p>Kuala Lumpur (KUL)<br>
                                                            colombo<br>
                                                            DepartureTime <br>
                                                            DepartureTime
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <p><i class="fa fa-clock-o"></i> time duration</p>
                                                    </div>

                                                </div>
                                                <div class="vgn"><p><img src="../airimages/OD.GIF"> Malindo Airways - OD186  <a>Economy V</a> - Aircraft 738</p></div>
                                                <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong> 3hrs 50mins</div>
                                            </div>
                                        </div>  

                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="clearFix">

                        </div>

                    </div>


                <?php }
            } ?>
        </div>
    </div>
</div >   