
<?php
include_once (PLUG_DIR . '/service/dumpReservation.php');

function book_Init($reponse) {
$air = $reponse->airDetails;
$fareInfo = $reponse->priceDetails->fareInfo;
$_SESSION['air'] = serialize($air);
$_SESSION['fareInfo'] = serialize($fareInfo);

$pasArry = array("ADT" => "ADULT", "CNN" => "CHILD", "INF" => "INFANT");
$util = new FlightUtility();
?>
<div class="container book-page">
    <div class="row confrmtic">

        <?php
        $airItem = $air[0][0]['@attributes'];
        $aircount = count($air[0]) - 1;
        $airItemlast = $air[0][$aircount]['@attributes'];
        $price_details = array
        ("totalNetAmount" => "", "totalBaseAmount" => "",
            "totalDiscount" => "", "totalTaxAmount" => "", "serviceCharge" => "", "departureTime" => "", "arrivalTime" => "", "returnDate" => "");
        $price_details['serviceCharge']=100.00;
        $price_details['totalDiscount']=120.00;



        ?>

        <div class="col-lg-9 confrmcol ">
            <div class="row confrmcols-rd ">
                <div class="col-lg-2 clspad"><label class="flicon"></label></div>
                <div class="col-lg-3 ">
                    <label class="lblcls">
                        <?php echo $util->getCityName($airItem['Origin']);
                        echo "(" . $airItem['Origin'] . ")" ?>
                        <br/>
                        <?php echo date("D, d M y h:i a", strtotime($airItem['DepartureTime']));
                        $price_details['departureTime'] = $airItem['DepartureTime']; ?>
                        </p>
                    </label>
                </div>
                <div class="col-lg-1 clspad"><label class="arricon"></label></div>
                <div class="col-lg-3 ">
                    <label class="lblcls">
                        <?php echo $util->getCityName($airItemlast['Destination']);
                        echo "(" . $airItemlast['Destination'] . ")" ?>
                        <br/>
                        <?php echo date("D, d M y h:i a", strtotime($airItemlast['ArrivalTime']));
                        $price_details['arrivalTime'] = $airItemlast['ArrivalTime']; ?>
                        </p>
                    </label>
                </div>
                <div class="col-lg-1 clspad">
                    <label class="lblcls"> <?php if ($aircount > 0) {
                            echo $aircount . 'stops';
                        } else {
                            echo "direct";
                        } ?> </label>
                </div>
                <div class="col-lg-1 clspad time-d">
                    <label
                        class="lblcls"><?php echo $util->getTimeDiff($airItemlast['ArrivalTime'], $airItem['DepartureTime']) ?></label>
                </div>
            </div>
        </div>


        <?php
        $airItem = $air[1][0]['@attributes'];
        $aircount = count($air[1]) - 1;
        $airItemlast = $air[1][$aircount]['@attributes'];
        ?>

        <div class="col-lg-9 confrmcol ">

            <?php

            if ($_SESSION['searchdata']['mode'] == "roundtrip") {
                ?>
                <div class="row confrmcols-rd">
                    <div class="col-lg-2 clspad"><label class="flicon"></label></div>
                    <div class="col-lg-3 ">
                        <label class="lblcls">
                            <?php echo $util->getCityName($airItem['Origin']);
                            echo "(" . $airItem['Origin'] . ")" ?>
                            <br/>
                            <?php echo date("D, d M y h:i a", strtotime($airItem['DepartureTime']));
                            $price_details['returnDate'] = $airItem['DepartureTime']; ?>
                            </p>
                        </label>
                    </div>
                    <div class="col-lg-1 clspad"><label class="arricon"></label></div>
                    <div class="col-lg-3 ">
                        <label class="lblcls">
                            <?php echo $util->getCityName($airItemlast['Destination']);
                            echo "(" . $airItemlast['Destination'] . ")" ?>
                            <br/>
                            <?php echo date("D, d M y h:i a", strtotime($airItemlast['ArrivalTime'])) ?>
                            </p>
                        </label>
                    </div>
                    <div class="col-lg-1 clspad">
                        <label class="lblcls"> <?php if ($aircount > 0) {
                                echo $aircount . 'stops';
                            } else {
                                echo "direct";
                            } ?> </label>
                    </div>
                    <div class="col-lg-1 clspad time-d">
                        <label
                            class="lblcls"><?php echo $util->getTimeDiff($airItemlast['ArrivalTime'], $airItem['DepartureTime']) ?></label>
                    </div>

                </div>


            <?php }


            ?>

            <?php ?>


        </div>

        <div class="col-lg-3 ticbck">
            <div class='ticfront'>
                <label>Trevellers :1</label><br/>
                <label class='lbltfront'>Total Amount</label>
                <label class='lblsec'>LKR 26,000</label>


            </div>

        </div>


    </div>

    <div class="row price-dts-header">
        <div class="col-lg-1 pr-dts"> Pass Type</div>
        <div class="col-lg-1 pr-dts"> Head</div>
        <div class="col-lg-2 pr-dts"> Base</div>
        <div class="col-lg-2 pr-dts"> Tax</div>
        <div class="col-lg-2 pr-dts"> Discount</div>
        <div class="col-lg-2 pr-dts"> Service</div>
        <div class="col-lg-2 pr-dts"> Total</div>
    </div>
    <div class="row  price-dts-header price-dts-body">
        <?php foreach ($fareInfo as $fare)
        {
            $price_details['totalBaseAmount']=$price_details['totalBaseAmount']+$fare->headPrice;
            $price_details['totalTaxAmount']= $price_details['totalTaxAmount']+$fare->headTax;
            $price_details['totalNetAmount']= $price_details['totalNetAmount']+$fare->headTax+$fare->headPrice;

            ?>
            <div class="col-lg-1  pr-dts"><?php echo $fare->pasType?></div>
            <div class="col-lg-1  pr-dts"><?php echo $fare->count?></div>

            <div class="col-lg-2  pr-dts"><?php echo $fare->headPrice."LKR"?></div>
            <div class="col-lg-2  pr-dts"><?php echo $fare->headTax ."LKR"?></div>
            <div class="col-lg-2  pr-dts"><?php echo $fare->count?></div>
            <div class="col-lg-2  pr-dts"><?php echo $fare->count?></div>
            <div class="col-lg-2  pr-dts"><?php echo $fare->totalPrice+$fare->headTax."LKR"?></div>

        <?php }    $_SESSION['price_details']=$price_details;
        $totalAmountPay=($price_details['totalNetAmount']+$price_details['totalTaxAmount']+$price_details['serviceCharge'])-$price_details['totalDiscount'];
        $_SESSION['totalAmountPay']=$totalAmountPay;
        ?>
    </div>
    <div class="row">
        <div class="">


        </div>
    </div>
    <div class='row clshead'>
        <div class='col-lg-12'>
            <div class='col-lg-11'><h5 class='headersty'>TRAVELLER DETAILS</h5></div>
        </div>
    </div>
    <form action="" method="" id="frmflighttic">

        <?php $pas = $_SESSION['searchdata']['passengers'];

        foreach ($pas as $i => $ind) {
            error_log("BOOK-->" . print_r($ind, true));
            ?>

            <div class='row clshead1'>
                <div class='col-lg-12'>
                    <div class='col-lg-11'><h3 class='headersty'>TRAVELLER - <?php echo " ". $i + 1 ."(". $pasArry[$ind].")" ?></h3>
                    </div>
                </div>
                <div class='col-lg-12'>
                    <div class='col-lg-2'>
                        <label class='lblin'>TITLE</label><br/>
                        <select data-parsley-required="" name="traveler_titile[]" class="form-control ">

                            <option value="">Select</option>
                            <option value="1">Mr</option>
                            <option value="2">Ms</option>
                            <option value="3">Mrs</option>
                            <option value="4">Dr</option>
                            <option value="5">Rev</option>
                            <option value="7">Miss</option>
                            <option value="8">Prof</option>
                        </select>

                        <label class='lblin' id="traveler_titile_err<?php echo $i ?>"></label><br/>
                    </div>
                    <div class='col-lg-3'>
                        <label class='lblin'>OTHER NAMES (FIRST NAME)</label><br/>
                        <input type='text' class='infrm' id='trvother' name='first_name[]'>
                        <label class='lblin' id="first_name_err<?php echo $i ?>"></label><br/>
                    </div>
                    <div class='col-lg-3'>
                        <label class='lblin'>SURNAME (LAST NAME)</label><br/>
                        <input type='text' class='infrm' id='trvsurname' name="last_name[]">
                        <label class='lblin' id="last_name_err<?php echo $i ?>"></label><br/>
                    </div>
                    <div class='col-lg-2'>
                        <label class='lblin'>DATE OF BIRTH</label><br/>
                        <input name="dob[]" type="text" class="txt_box date-pickR date-meera"
                               placeholder="Date of Birth" data-date-format="yyyy-mm-dd" data-date-end-date="0d"
                               id="dobid<?php echo $i; ?>"/>
                        <label class='lblin' id="doberror<?php echo $i ?>"></label><br/>
                        </label>
                    </div>
                </div>

                <div class='col-lg-12'>
                    <div class='col-lg-11'>
                     <span>
                                          <a role="button" class="btn btn-info btn-sm out-moretime"
                                             data-toggle="collapse" href="#aditional<?php echo $i ?>"
                                             aria-expanded="false" aria-controls="aditional<?php echo $i ?>">ADD OPTIONAL (PASSPORT AND FREQUENT FLYER NUMBER)</a>
                                      </span>
                        <label class='lblnote'></label>
                    </div>
                </div>
            </div>
            <div class="row ticfour ticfoursub collapse addtional" id="aditional<?php echo $i ?>" aria-hidden=true
                 accesskey="">
                <div class='col-lg-12'>
                    <div class='col-lg-2'>
                        <label class='lblin'>PASSPORT NUMBER</label><br/>
                        <input type='text' class='infrm2' id='trvpass' name='nbtrvpass'>
                    </div>
                    <div class='col-lg-2'>
                        <label class='lblin'>EXPIARATION DATE</label><br/>
                        <input type='text' class='infrm2' id='trvexpdate' name='nbtrvexpdate'>
                    </div>
                    <div class='col-lg-2'>
                        <label class='lblin'>ISSUING COUNTRY</label><br/>
                        <input type='text' class='infrm2' id='trvcountry' name='nbtrvcountry'>
                    </div>

                </div>
            </div>

            <?php if ($i == 0) { ?>
                <div class='row ticfour subcontact'>
                    <div class='col-lg-12'>
                        <div class='col-lg-11 lblcontact'>
                            <label class='lblborder headersty'>CONTACT INFORMATION</label><br/>
                            <p>Your mobile Number will be used only for sending flight related communication</p>
                        </div>
                    </div>
                    <div class='col-lg-12 areacontact'>
                        <div class='col-lg-2'>
                            <label class='lblin'>MOBILE NUMBER</label><br/>
                            <input type='text' class='infrm2' id='trvmobile' name='mobile_no'>
                        </div>
                        <div class='col-lg-2'>
                            <label class='lblin'>PASSPORT DATE</label><br/>
                            <input type='text' class='infrm2' id='trvpdate' name='passport_date'>
                        </div>
                        <div class='col-lg-2'>
                            <label class='lblin'>E-MAIL</label><br/>
                            <input name="email_address" id="email_address" value="" type="text" class="infrm2"
                                   placeholder="E-mail"/>

                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

    </form>


    <div class='row ticfour subcontact'>
        <div class='col-lg-12'>
            <div class='col-lg-11'>
                <input type='radio' id='trvradio' name='nbtrvradio'>
                By clicking the "Continue Booking" button below, below, I understand and agree with the Rules and
                Restrictions of this fare.
                I agree to the Privacy Policy and the Terms and Conditions of Meera(pvt) ltd
            </div>
        </div>
    </div>

    <div class='row ticfour clshead'>
        <div class='col-lg-12'>
            <div class='col-lg-11'><h5 class='headersty'>PAYMENT OPTION</h5></div>
        </div>
    </div>
    <div class='row ticfour'>
        <div class='col-lg-12 ticfv'>
            <label class='ticfvsub'>Total to be paid is LKR 36,577</label>
        </div>
        <div class='col-lg-12'>
            <div class='col-lg-4 btncls1'>
                <button class='btnpaylate'></button>
            </div>
            <div class='col-lg-4 btncls'>
                    <button class='btnpaynow'></button>
            </div>
            <div class='col-lg-4 btncls1'>
                <button class='btnembassy'></button>
            </div>
        </div>
    </div>


    <form action="" method="" id="frmempassy">
        <div class='row'>
            <div class='col-lg-12 clshead'>
                <div class='col-lg-11'><h5 class='headersty'>EMBASSY PURPOSE</h5></div>
            </div>
        </div>
        <div class='row efrmcls'>
            <div class="col-lg-12 empcls">
                <label class="lblin">EMBASSY PURPOSE</label><br/>
                <input type="text" class="emppurpose" id="embassyid" name="embassynm"/>
            </div>
            <button class="btncontinue">continue</button>
            <div class="col-lg-4 empcls">
                <label class="lblin">SURNAME</label><br/>
                <input type="text" class="empsurname" id="emsureid" name="emsurenm"/>
            </div>
            <div class="col-lg-4 empcls">
                <label class="lblin">OTHER NAME</label><br/>
                <input type="text" class="empother" id="emotherid" name="emothernm"/>
            </div>
            <div class="col-lg-4 empcls">
                <label class="lblin">PASSPORT NUMBER</label><br/>
                <input type="text" class="empsurname" id="emppassportid" name="emppasspornm"/>
            </div>
        </div>
    </form>
</div>
<script>

    jQuery('.btnembassy').click(function () {
        jQuery("#frmempassy").fadeToggle("slow", "linear");
    });


</script>






<?php
   
} ?>