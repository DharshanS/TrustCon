

<style>
      .head_result{
    min-height: 100px;
}
.fly_dts_row{
    margin-top: 10%;
/*     border: solid;*/
/*    border-color: green;*/
    max-width: 100%;
}

.tk_header{
    background-color:skyblue;
    min-height: 50px;

}
.tk_body{
/*    border: solid;*/
/*    border-color: graytext;*/

/*    border-color: black;*/
    /*         height: 20px;*/
}
.main_con{
    max-width: 75%;
}
.sch_edit_con{
    position: relative;
    max-width: 100%;
}
.e_ori{


}
.head_edit{
/*    border: solid;*/
/*    border-color: grey;*/
}
.edit_dts
{
/*    border: solid;*/
/*    border-color: grey;  */
}

.edit_src_bt{
    position: inherit;
    border: solid;
/*    border-color: grey; */
    /*           min-height:100px;*/
    text-align: center;
    padding: 3.5%;
    /*         padding-top: 7%;*/

}
.edit_out{
    /*           border-color: grey; */
    /*           border: solid;*/
    min-height: 50px;
}
.edit_in{
    /*           border-color: grey; */
    /*           border: solid;*/
    min-height: 50px;
}
.edit_rd_options{
/*    border-color: grey; */
/*    border: solid;  */
    max-height: 100px;
    padding-top: 10px;

}
.edit_dts_con{
    position: relative;
    max-width: 100%;
    font-family: sans-serif;
    font-size: 12px;
}
.mode_rd{
    size: 100px;  
}
.fly_seg p{
    margin:0px;
    text-align: center;
}
.fly_seg
{
    margin-bottom: 10px;
    /*           border-color: grey; 
               border: solid; */
    text-align: right;  
}
.tkin_dts{
    /*          border-color: grey; 
               border: solid; */
}
.tkout_dts{
    /*           border-color: grey; 
               border: solid; */
}
.arrow_right{
    position: absolute;
    left: 23%;
    top: 30%;
}
.flt-dtl{
    max-width: 100%;
}
.edit-visl-con{

}
.edit-visl {
    height: auto;
    position:relative;
    top: 0;
    background: #fff;
    display:none;
max-width: 65%;
    left:0;
    z-index:9999999;
    box-shadow: 0 0 5px #545454;
    margin: 0px;
/*    border-style: solid;*/
/*    border-color: black;*/
}
.edit-visl .head {
    background: #082299;
   
    color: #fff;
    font-size: 16px;
/*    padding: 5px 10px;*/
    text-transform: uppercase;
      margin: 0;
/*      border-style: solid;*/

}
.edit-visl .head i {
  
}


.flySelect{
    margin-left: -30% !important;
    margin-right: 10% !important;
}
.modal-dialog{
    background-color: green !important;
    position: inherit;
    /*   top:70%;*/

}
#myModal
{
    max-height: 400px;
}
.more_time{
/*    border-color: grey; */
/*    border: solid;  */
    margin: 0px;
}
.moreFlyCon{

/*    border-color: grey; */
/*    border: solid;*/
    margin: 0px;
} 
.btn{
    height: 20px;
    padding: 1px;
    padding-left: 15px;
    padding-right:15px;
}

.dts_con{

    max-width: 50px;
/*    border-color: grey; */
/*    border: solid;*/
    background-color: burlywood;
}

.popover 
{
    background-color: red;
    align-items: center; 

}
.sky-bg {
    background:url(./images/c-background.jpg) no-repeat top left fixed;
    background-size:100% auto;
    width:100%;
    float:left;
}
/*.clearFix
{
    height: 30px;
}*/

.fly_dts{
     margin: 0px;
    background-color: white;
    margin-bottom: 10%;
    font-family: sans-serif;
    font-size: 12px;
/*    border-style: solid;*/
/*    border-color: black;*/
   
      
}
  br {
        line-height: 150%;
        background-color: black;
     }
       .br {
        line-height: 150%;
         background-color: black;
     }
.fly_seg p
{
display: block;
margin: 0px;
background-color: blanchedalmond;
    
}
.flyItem
{
margin-bottom: 10%; 
/*border-style: solid;*/
/*border-color: brown;*/
font-size: 12px;
   
}
.body
{
  font-family:Segoe,"Segoe UI","Open Sans","DejaVu Sans","Trebuchet MS",Verdana,sans-serif!important
}
.head
{
    margin: 0!important;
    padding-left: 100px !important;
/*    background-color: red !important;*/
}
.vgn
{
    margin: 0!important;
max-width: 100%;
padding-left: 100px;
}
.book
{
 text-align: center;
 font-weight: bolder;
 width: 20%;
}
  </style>
  

<?php
include(PLUG_DIR . 'utility/FlightUtility.php'); 
 
function init_display($response) {$flyUtil =new FlightUtility(); ?>

<div id="resultloader">
        <div class="sky-bg">
            
            <div  class="container head_result">
                <div class="row">
                    <label>Head</label>
                </div>

            </div>


            <div class="container main_con">
              

  <!--*************************************Edit details* start***************************************-->
<!--                <div class="row">
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
                </div>-->

               <!--*********************************Edit details* End********************************************-->
                
                <div class="container fly_dts_row">
                    
                    
                    
                    
                        <?php foreach($response as $index=>$item){ 
                         $bestfly=$item->bestFly->segDetails;
                         $bestPrice=$item->bestFly->priceDetails['@attributes']['TotalPrice'];
                         $bestflyCont=count($bestfly);
                         $flyInnerListCount=count($bestfly[0])-1;
                        $flyInnerListTop=$bestfly[0][0]['@attributes'];
                        $flyInnerListBot=$bestfly[0][$flyInnerListCount]['@attributes'];
                      
//                        if($index==2)
//                        {
//                        error_log(print_r($bestfly,true));
//                        return;
//                        }
                        ?>
                    
                        <div class="row flyItem">
                            <div class="col-sm-12 tk_header">


                            </div>

                            <div class="tk_body">
                                <div class="container">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-9 col-lg-8 tkout_dts">
                                                <div class="row">
                                                    <div class="col-xs-6 col-lg-3 fly_seg">
                                                        <p>
                                                            <?php echo $flyInnerListTop['Origin']?></p>
                                                        <p> 
                                                            
                                                            <input type="radio" class="flySelect" key="<?php echo $index?>" name="selectFly" value="<?php echo base64_encode(json_encode($bestfly[0]))?>" checked="checked"> 
                                                        <?php echo date("h:i a", strtotime($flyInnerListTop['DepartureTime'])) ?>
                                                        </p>
                                                        <p><?php echo date("D, d M Y", strtotime($flyInnerListTop['DepartureTime']))?></p> 
                                                       
                                                        <?php if($bestflyCont>1)
                                                            { ?>
                                                                    <p> 

                                                                        <span>
                                                                            <a role="button" class="btn btn-info btn-sm" data-toggle="collapse" href="#moreFlightsOut<?php echo $index ?>" aria-expanded="false" aria-controls="moreFlightsOut">more timings</a>

                                                                        </span>
                                                                    </p>
                                                        <?php } ?>
                                                    </div>

                                                    <div class="col-xs-6 col-lg-3 fly_seg">    
                                                      <p>
                                                            <?php echo $flyInnerListBot['Destination']?></p>
                                                        <p> 
                                                        
                                                        <?php echo date("h:i a", strtotime($flyInnerListBot['ArrivalTime'])) ?>
                                                        </p>
                                                        <p><?php echo date("D, d M Y", strtotime($flyInnerListBot['ArrivalTime']))?></p>
                                                    </div>
                                                    <div class="col-xs-6 col-lg-1">
                                                      <?php  $airLineCode=$flyInnerListBot['Carrier'];?>
                                                     <img src="../airimages/<?php echo $airLineCode ?>.GIF">
                                                    </div>
                                                    <div class="col-xs-6 col-lg-2"><?php
                                                    $airlineName=$flyUtil->getAirLineName($flyInnerListBot['Carrier']);
                                                    echo $airlineName ?></div>
                                                    <div class="col-xs-6 col-lg-1"><?php
                                                                if ($flyInnerListCount > 0) {
                                                                    echo $flyInnerListCount . ' Stops';
                                                                } else {
                                                                    echo 'Direct';
                                                                }
                                                                ?></div>
                                                    <div class="col-xs-6 col-lg-2">
                                                        <?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($flyInnerListTop['DepartureTime'])), date("h:i a", strtotime($flyInnerListBot['ArrivalTime']))) ?></div>
                                                </div>
                                                
                                                <?php if($bestflyCont>1)
                                                    { ?>
                                                <div class=" row collapse moreFlights" id="moreFlightsOut<?php echo $index?>" aria-hidden=true accesskey="" >
                                                    
                                                    <?php  
                                                    
                                                    for ($inerIdex=1;$inerIdex<$bestflyCont;$inerIdex++)
                                                    { 
                                                    
                                                        $indexCount=count($bestfly[$inerIdex])-1;
                                                        $item=$bestfly[$inerIdex];
                                                       $moreTop=$item[0]['@attributes'];
                                                       $moreBot=$item[$indexCount]['@attributes'];?>  
                                                  

                                                    
                                                    <div class="col-xs-6 col-lg-3 fly_seg">
                                                        <P>
                                                        <input type="radio" class="flySelect" key="<?php echo $index?>" name="selectFly" value="<?php echo base64_encode(json_encode($item))?>" checked="checked"> 
                                                       <?php echo date("h:i a", strtotime($moreTop['DepartureTime'])) ?>
                                                        </p>
                                                        <p><?php echo date("D, d M Y", strtotime($moreTop['DepartureTime']))?></p>
                                                       
                                                  
                                                    </div>
                                                    <div class="col-xs-6 col-lg-3 fly_seg">    
                                                     
                                                        <P>
                                                        <?php echo date("h:i a", strtotime($moreBot['ArrivalTime'])) ?>
                                                        </p>
                                                        <p><?php echo date("D, d M Y", strtotime($moreBot['ArrivalTime']))?></p>
                                                    </div>
                                                    <div class="col-xs-6 col-lg-1">
                                                     <img src="../airimages/<?php echo $airLineCode?>.GIF">
                                                    </div>
                                                    <div class="col-xs-6 col-lg-2"><?php echo $airlineName ?></div>
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
                                            </div>
                                        
                                            <div class="col-xs-12 col-md-3 col-lg-4 pr_dts">
                                                 
                                                <div>Per Adult</div>
                                                <div><?php echo $bestPrice ?></div>
                                                <div>
                                                    <form name="frmAvail" action="../booking" method="post">
                                                        <input type="hidden" name="bookingdata" value="<?php echo base64_encode(serialize($bestfly[0])); ?>">
                                                                <input type="hidden" name="searchdata" value="">
                                                                <input type="hidden" name="searchmode" value="">
                                                                <button type="submit" class="btn btn-sm btn-success book">BOOK</button> 
                                                       </form>
                                                </div></br>
                                                <div>
                                                    <a>Fare Rules</a>
                                                </div>
                                               
                                                <div><p><a class="shw" key="shw_fly_dts<?php echo $index?>"><span><i class="fa fa-plus"></i> Show Flight Details</span></a></p>
                                                    </p>
                                                </div>

                                            </div>
                                        </div>

<!-- ********************************************************************************/-->  
                                        
                                        <div class="row edit-visl col-lg-8 shw_fly_dts<?php echo $index?> ">
                                            
                                            <div class="head">
                                                <i class="fa fa-plane">    
                                                </i> Depart
                                            </div>
                                            <div class="row flt-dtl">
                                            <?php foreach($bestfly[0] as $in=>$item){
                                                $air=$item['@attributes'];
                                                if(isset($item[0]['airBookingInfo']['@attributes']))
                                                {
                                                $airInfo=$item[0]['airBookingInfo']['@attributes'];
                                                }
                                                elseif($item[0]['@attributes'])
                                                {
                                                  $airInfo = $item[0]['@attributes'];
                                                }
//                                               if($index==2)
//                                               {
//                                                     error_log(print_r($item,true));
//                                               return;
//                                               }
                                             ?>
                                                <div class="row">
                                                <div class="col-sm-4 col-xs-6 col-lg-4">
                                                    <p>
                                                     <?php echo $flyUtil->getCityName($air['Origin']) . '(' . $air['Origin'] . ')' ?>
                                                     </p>
                                                     <P>
                                                     <?php echo date("h:i a", strtotime($air['DepartureTime'])) ?>
                                                     </p>
                                                      <p>
                                                          <?php echo date("D, d M Y", strtotime($air['DepartureTime'])) ?>
                                                      </p>
                                                </div>
                                                <div class="col-sm-4 col-xs-6 col-lg-4">
                                                    <p>
                                                     <?php echo $flyUtil->getCityName($air['Destination']) . '(' . $air['Destination'] . ')' ?>
                                                     </p>
                                                     <P>
                                                     <?php echo date("h:i a", strtotime($air['ArrivalTime'])) ?>
                                                     </p>
                                                      <p>
                                                          <?php echo date("D, d M Y", strtotime($air['ArrivalTime'])) ?>
                                                      </p>
                                                </div>
                                              
                                                <div class="col-sm-4  col-lg-4">
                                                    <p><i class="fa fa-clock-o"></i>
                                                    <?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($air['DepartureTime'])), date("h:i a", strtotime($air['ArrivalTime']))) ?>
                                                </div>
                                          </div>
                                             <div class="row vgn">
                                                 <p>
                                                     <img src="../airimages/<?php echo $airLineCode?>.GIF"> 
                                                    <?php echo $airlineName .'-'.$airLineCode.$air['FlightNumber']?> <a> 
                                                     <?php echo $airInfo['CabinClass'].'-'.$airInfo['BookingCode'] ?></a> - Aircraft <?php echo $air['Equipment']?>
                                                 </p>
                                            </div>  
                                              
                                            <?php } ?>
                                           </div>
                                         
                                            <div class="btm"><i class="fa fa-clock-o"></i> <strong>TOTAL DURATION</strong>
                                             <?php echo $flyUtil->getTimeDiff(date("h:i a", strtotime($flyInnerListTop['DepartureTime'])), date("h:i a", strtotime($flyInnerListBot['ArrivalTime']))) ?>
                                            </div>
                                                
                                        </div>
                                       
    <!-- ********************************************************************************/-->                                   
                                        
                                        
                                        
                                    </div>  
                                
                                </div>

                            </div>

                        </div>
                     
<?php }?>
                  

                   
                </div>
                
                
            </div>
        </div>
    </div >  
<?php } ?>

  
    <script>



	jQuery(".srh-button").click(function(){

		jQuery(".edit-area").slideToggle('slow');

	});

        
	jQuery(".shw").on("click", function() {

var ele="."+jQuery(this).attr("key");

		jQuery(ele).slideToggle('slow');

		if ( jQuery.trim(jQuery(this).text().toString()) == ("Show Flight Details").toString() ) {

			jQuery(this).html('<span><i class="fa fa-minus"></i> Hide Flight Details</span>');

		} else {

			jQuery(this).html('<span><i class="fa fa-plus"></i> Show Flight Details</span>');

		}
                

	});
        
        jQuery(".more-fly").click(function(){

		//jQuery(".more-flights").slideToggle('slow');
              //  alert('more-fly'+ jQuery(this).parent().parent().parent().children('.more-flights'));
              jQuery(this).parent().parent().parent().children('.more-flights').slideToggle('slow');
                if ( jQuery.trim(jQuery(this).text().toString()) == ("more timing option").toString() ) {

			jQuery(this).text('Hide more timing option');

		} else {

			jQuery(this).html('<span><i class="fa fa-plus"></i> more timing option</span>');

		}
	});
       // jQuery('.more-flights').slideToggle('slow');


/*************************************/
    jQuery(".moretime_btn").click(function () {
        var ele = '#bestRo_' + jQuery(this).attr("id");
         alert(ele);
        alert('flag -- > '+jQuery(this).siblings(".collapse").attr("aria-hidden"));
  
       
        if (jQuery(this).siblings(".collapse").attr("aria-hidden")=='true')
        {
     
            jQuery(ele).css("visibility","visible");
            jQuery(this).siblings(".collapse").attr("aria-hidden","false");
        } 
        else
        {
         
            jQuery(ele).css("visibility","hidden");
             jQuery(this).siblings(".collapse").attr("aria-hidden","true");
        }


    });
    

   /*************************************/ 
     jQuery("input[name='selectFly']").click(function () {
  


     var months = new Array( "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
     var days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
     var jsRs; 
     
         var radioValue = JSON.parse(atob(jQuery(this).val()));
         var htmlEla=".shw_fly_dts"+jQuery(this).attr("key");
           alert(htmlEla);
          var fly_dts="";
       
          
           jQuery.each(radioValue, function (index,element) {
               
             console.log(element);
             var air=element["@attributes"];
              var b_dts;
            if(element["0"].airBookingInfo["@attributes"]===undefined)
             {b_dts=element["0"]["@attributes"];}else{b_dts=element["0"].airBookingInfo["@attributes"];};
             jQuery.ajax({
                        type: 'get', 
                        action:'ajaxUtility',
                        data:{action:'ajaxUtility'},
                        url: '/travel/wp-admin/admin-ajax.php?ori='+air.Origin+'&des='+ air.Destination+'&air='+air.Carrier,
                        success: function(response,status){      
                        jsRs=JSON.parse(response.split("0")[0]);  
                        },async: false
            });
             var depDate=new Date(air.DepartureTime);
                 var arrDate=new Date(air.ArrivalTime);
                 var deHrs=depDate.getHours();
                 var deT;
                 var deH;
                 var arrHrs=arrDate.getHours();
                 var arT;
                 var arH;
                 var tdh=arrDate.getHours()-depDate.getHours();
                 var tdm=arrDate.getMinutes()-depDate.getMinutes();
                      if(deHrs > 12 && deHrs < 23 ) {deT="pm";deH=deHrs%12;}
                      else{deT="am";deH=deHrs; if(deHrs===24){deH=12;}if(deHrs===12){deH=12;deT="pm";}
                      }
                        if(arrHrs > 12 && arrHrs < 23 ) {arT="pm";arH=arrHrs%12;}
                      else{arT="am";arH=arrHrs; if(arrHrs===24){arH=12;}if(arrHrs===12){arH=12;arT="pm";}
                      }
                 fly_dts=fly_dts+'<div class="row">'+
                                                '<div class="col-sm-4 col-xs-6 col-lg-4">'+
                                                    '<p>'+
                                                   jsRs.ori+'(' +air.Origin + ')'+
                                                     '</p>'+
                                                     '<P>'+
                                                     deH+":"+depDate.getMinutes()+" "+deT+
                                                     '</p>'+
                                                      '<p>'+
                                               
                                                   days[depDate.getDay()-1]+" "+depDate.getDate()+" "+months[depDate.getMonth()-1]+" "+depDate.getFullYear()+
                                                      '</p>'+
                                                '</div>'+
                                                '<div class="col-sm-4 col-xs-6 col-lg-4">'+
                                                    '<p>'+
                                                      jsRs.des+'(' + air.Destination +')'+
                                                     '</p>'+
                                                     '<P>'+
                                                     arH+":"+arrDate.getMinutes()+" "+arT+
                                                     '</p>'+
                                                      '<p>'+
                                                      days[arrDate.getDay()-1]+" "+arrDate.getDate()+" "+months[arrDate.getMonth()-1]+" "+arrDate.getFullYear()+
                                                      '</p>'+
                                                '</div>'+
                                              
                                                '<div class="col-sm-4  col-lg-4">'+
                                                    '<p><i class="fa fa-clock-o"></i> '+
                                                  tdh +"hrs "+tdm +"mins"+
                                                '</div>'+
                                          '</div>'+
                                             '<div class="row vgn">'+
                                                 '<p>'+
                                                     '<img src="../airimages/'+air.Carrier+'.GIF">'+ 
                                                    '<a>'+air.FlightNumber+'<a>'+ 
                                                    b_dts.CabinClass+'-'+b_dts.BookingCode+'</a> - Aircraft'+air.Equipment+
                                                 '</p>'+
                                            '</div>';  
            
           });
           
     
 
           jQuery(htmlEla).children(".flt-dtl").html(fly_dts);
           
     });
     jQuery('.typeahead').typeahead( 
	{ 
		hint: true, 
		highlight: true, 
		minLength: 3, 
		limit: 8 
	}, 
	{ 
	source: function(q, cb) { 
		return jQuery.ajax({ 
			dataType: 'json', 
			type: 'get', 
			url: 'http://www.clickmybooking.com/getcity_airport.php?q=' + q , 
			cache: false, 
			success: function(data) { 
				var result = []; 
				jQuery.each(data, function(index, val) { 
					result.push({ 
						value: val 
					}); 
				}); 
				cb(result); 
			} 
		}); 
	} 
});
    </script>