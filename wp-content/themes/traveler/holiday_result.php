<?php
print_r($_POST);
//die('fgdgdg');
set_time_limit(0);
## Rajib Added - 15/10/2015
$perpageList = 10;
## Rajib Added - 15/10/2015

//$alink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$alink = mysqli_connect('localhost', 'i1611638_wp1', 'H(IXs5rqUc70.#4', 'i1611638_wp1');
//$alink = mysql_connect('localhost', 'i1611638_wp1', 'H(IXs5rqUc70.#4');
//$db = mysql_select_db('i1611638_wp1',$alink);
$cityArray = explode(',',$_REQUEST['city']);

		//$SQL   =  " SELECT * FROM  hotel ";

     $SQL  =  "SELECT *,h.id AS hid FROM  city c, hotel h WHERE c.id = h.city_id  AND ".
	          " c.city ='".$cityArray[0]."' AND h.status = '0'  "; 

$rs = mysqli_query($alink,$SQL) or die($SQL);
while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC))
{ 
?> 
<div class="container">
	<div class="results">
    	<div class="row_div">
        	<div class="row">
            	<div class="col-sm-4">
                	<div class="col_img">
                    	<a href="#" class="title"><span class="glyphicon glyphicon-tag"></span> Lorem Dolor</a>
                    	<a href="#"><img src="../wp-content/plugins/hotelbooking/uploads/hotels/<?php echo $row['photo'];?>" /></a>
                    </div>
              	</div>
                <div class="col-sm-8">
                	<div class="res_title"><a href="#"><h4><?php echo $row['hotel'];?></h4></a></div>
                	<div class="row">
                    	<div class="col-sm-8">
                            <div class="restaurant_info">
                            <p><span class="glyphicon glyphicon-map-marker"></span><?php echo $row['address'].'  '. $row['city'].','.$row['country'];?></p>
                            <div class="price">
                                <!--<ul>
                                    <li><p class="lkr">LKR <strong>4,999</strong></p></li>
                                    <li><p class="lkr">LKR <strong><?php echo $row['price'];?></strong></p></li>
                                </ul>-->
                            </div>
                            <div class="rooms">
                            	<p><i class="fa fa-bookmark"></i> Standard Double Room </p>
                            </div>
                            <div class="features">
                            	<p><i class="fa fa-check-square-o"></i> Free Wireless Internet</p>
                            </div>
                            </div>
                    	</div>
                        <div class="col-sm-4">
                   	    <div class="book_now_panel">
                            	<div class="rating">                                
                                <img src="../airimages/rating.png" />
                                <img src="../airimages/rating.png" />
                                <img src="../airimages/rating.png" />
                                <img src="../airimages/rating.png" />
                                <img src="../airimages/rating.png" />
                                </div>
                                <div class="book">
                                	<!-- Rajib Comented-->
                                    <!--<a href="#" class="book_now"><?php echo $row['hid'];?></a>-->
                                    <form name="frmAvail" action="../holiday-result-next" method="post">
                                    <input type="hidden" name="city" value="<?php echo $_REQUEST['city'];?>" />
                                    <input type="hidden" name="checkin" value="<?php echo $_REQUEST['checkin'];?>" />
                                    <input type="hidden" name="checkout" value="<?php echo $_REQUEST['checkout'];?>" />
                                    <input type="hidden" name="person" value="<?php echo $_REQUEST['person'];?>" />
                                    <input type="hidden" name="hid" value="<?php echo $row['hid'];?>" />
                                    <button type="submit" class="book_now">Select</button>
                                    </form>
                                </div>
                          </div>
                        </div>                      
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php 
} // End Loop
?>
<script type="application/javascript">
jQuery(document).ready(function(){
	jQuery(".srh-button").click(function(){
		jQuery(".edit-area").slideToggle('slow');
	});
	jQuery(".shw").on("click", function() {
		jQuery(this).parent().parent().parent().prev().children(".edit-visl").slideToggle('slow');
		if ( jQuery.trim(jQuery(this).text().toString()) == ("Show Flight Details").toString() ) {
			jQuery(this).html('<span><i class="fa fa-minus"></i> Hide Flight Details</span>');
		} else {
			jQuery(this).html('<span><i class="fa fa-plus"></i> Show Flight Details</span>');
		}
	});
});
function chng_way(ele){ 
	if(ele.value=='oneway')jQuery('#return_date_div').addClass('in-active');
	if(ele.value=='roundtrip')jQuery('#return_date_div').removeClass('in-active');
}
jQuery('.date-pick').datepicker({ autoclose: true,todayHighlight: true});
</script>

<?php
function getSegmentKey($ref,$airsegments){
	foreach ($airsegments as $k => $v) {
       if ($v['Key'] === $ref) {
           return $k;
       }
   }
   return null;
}
function getFairInfoKey($ref,$fairinfolist){
	foreach ($fairinfolist as $k => $v) {
       if ($v['Key'] === $ref) {
           return $k;
       }
   }
   return null;
}
function getTimeInFormat($s){
$ret=$s;
$a=explode("T",$s);	
$ret=$a[1];
$b=explode("H",$ret);	
$h=$b[0];
$c=explode("M",$b[1]);	
$m=$c[0];

$ret='';
if($h)$ret.=$h;
if($h==1)$ret.= "hr ";
else if($h>1)$ret.= "hrs ";
if($m)$ret.=$m;
if($m==1)$ret.= "min";
else if($m>1)$ret.= "mins";
return $ret;
}
function getDaydiff($d1,$d2){
$date1 = new DateTime($d1);
$date2 = new DateTime($d2);	
$interval = $date1->diff($date2);
$d=$interval->days;
return $d;
}
function getTimeDiff($d1,$d2){
$date1 = new DateTime($d1);
$date2 = new DateTime($d2);	
$diff = $date2->diff($date1);
$h = $diff->h;
$h = $h + ($diff->days*24);	
$m=$diff->i;

$ret='';
if($h)$ret.=$h;
if($h==1)$ret.= "hr ";
else if($h>1)$ret.= "hrs ";
if($m)$ret.=$m;
if($m==1)$ret.= "min";
else if($m>1)$ret.= "mins";
return $ret;
}

function processFlightOptions($FlightOption){
$flightoption=array();	
if(isset($FlightOption['@attributes'])){ // for oneway
$flightoption=$FlightOption;

}else{
foreach($FlightOption as $fo){
//$fo=$FlightOption[0];
$fodata=array(
	'LegRef' => $fo['@attributes']['LegRef'],
	'Destination' => $fo['@attributes']['Destination'],
	'Origin' => $fo['@attributes']['Origin']
 );
 // option
$optionarr=array();
$Option=$fo['airOption'];
if(isset($Option['@attributes']['Key'])){ // single
$optdata=array(
	'Key' => $Option['@attributes']['Key'],
	'TravelTime' => $Option['@attributes']['TravelTime']
 );	
 $bookinginfodata=array();
 $BookingInfo=$Option['airBookingInfo'];
 $bookinginfodata=processBookingInfo($BookingInfo);
 $optdata['BookingInfo']=$bookinginfodata;
// connection
$connectiondata=array();
$Connection=$Option['airConnection'];
$connectiondata=processConnection($Connection);
$optdata['Connection']=$connectiondata;

 $optionarr[]=$optdata;
}else if(isset($Option[0])){
foreach($Option as $opt){
//$opt=$Option[0];
$optdata=array(
	'Key' => $opt['@attributes']['Key'],
	'TravelTime' => $opt['@attributes']['TravelTime']
 );
$bookinginfodata=array();
$BookingInfo=$opt['airBookingInfo'];
$bookinginfodata=processBookingInfo($BookingInfo);
$optdata['BookingInfo']=$bookinginfodata;
// connection
$connectiondata=array();
$Connection=$opt['airConnection'];
$connectiondata=processConnection($Connection);
$optdata['Connection']=$connectiondata;

$optionarr[]=$optdata;
}// foreach $opt
}//$Option[0]
$fodata['Option']=$optionarr;
$flightoption[]=$fodata;
}// foreach $fo
}
return $flightoption;	
}
function processBookingInfo($BookingInfo){
$bookinginfodata=array();
if(isset($BookingInfo['@attributes']['BookingCode'])){
$bookinginfodataraw=array(
    'BookingCode' => $BookingInfo['@attributes']['BookingCode'],
	'CabinClass' => $BookingInfo['@attributes']['CabinClass'],
	'FareInfoRef' => $BookingInfo['@attributes']['FareInfoRef'],
	'SegmentRef' => $BookingInfo['@attributes']['SegmentRef']
);	
$bookinginfodata[]=$bookinginfodataraw;
}else if(isset($BookingInfo[0])){
	foreach($BookingInfo as $binfo){
	    $bookinginfodataraw=array(
			'BookingCode' => $binfo['@attributes']['BookingCode'],
			'CabinClass' => $binfo['@attributes']['CabinClass'],
			'FareInfoRef' => $binfo['@attributes']['FareInfoRef'],
			'SegmentRef' => $binfo['@attributes']['SegmentRef']
		);	
		$bookinginfodata[]=$bookinginfodataraw;
	}
}
return $bookinginfodata;	
}
function processConnection($Connection){
$connectiondata=array();
if(isset($Connection['@attributes']['SegmentIndex'])){
$data=array(
    'SegmentIndex' => $Connection['@attributes']['SegmentIndex']
);	
$connectiondata[]=$data;
}else if(isset($Connection[0])){
	foreach($Connection as $c){
	    $data=array(
			'SegmentIndex' => $c['@attributes']['SegmentIndex']
		);	
		$connectiondata[]=$data;
	}
}

return $connectiondata;
}
?>