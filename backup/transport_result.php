<?php
//echo 'addd<pre>';
//print_r($_POST);
//die('fgdgdg');
set_time_limit(0);
## Rajib Added - 15/10/2015
$perpageList = 10;
## Rajib Added - 15/10/2015

//$alink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
$alink = mysqli_connect('localhost', 'i1611638_wp1', 'H(IXs5rqUc70.#4', 'i1611638_wp1');

		//$SQL   =  " SELECT * FROM  hotel ";

     /*$SQL  =  "SELECT *,h.id AS hid,c.id AS cid FROM  city c, hotel h WHERE c.id = h.city_id  AND ".
	          " c.city ='".$cityArray[0]."' AND h.status = '0'  "; 

$rs = mysqli_query($alink,$SQL) or die($SQL);
while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC))
{ */

## Getting Posted values
foreach($_POST as $k=>$v)
{
	$$k = $v;
}

$sql = ' select * from vehicle where id = '.$vehicle.' ';
$rs = mysqli_query($alink,$sql) or die($sql);
$vpic = mysqli_fetch_array($rs, MYSQLI_ASSOC);
$vehicle_pic = $vpic['photo'];

?>

<div class="container">
	<div class="results transport-res">
		<div class="row_div">
			<div class="transportform">
				<div class="booking-system2">
					<div class="top-tab">
						<ul>
							<!--<li ><a href="<?php //echo site_url(); ?>/flight-search/">Flight</a></li>-->
							<li class="active"><a href="<?php //echo site_url(); ?>/transport-search/">Transport Booking Form</a></li>
							<!--<li><a href="#">Flight+hotel</a></li>
                <li><a href="#">Flight Status</a></li>-->
						</ul>
						<div class="clearfix"></div>
					</div>
						<form action="<?php //echo site_url(); ?>/transport-result-next/" method="post" autocomplete="off" enctype="multipart/form-data">
							<input type="hidden" name="date_flexi" value="0" />
							<div class="booking-area">
								<div class="row">
									<div class="col-sm-6 small-mrgn">
											<input type="text" name="fname" id="fname" value="" placeholder="First Name" />
									</div>
									<div class="col-sm-6 small-mrgn">
											<input type="text" name="lname"  id="lname" value="" placeholder="Last Name"/>
									</div>
									<div class="col-sm-6 small-mrgn">
											<input type="text" name="email" id="email" value="" placeholder="Email" />
									</div>
									<div class="col-sm-6 small-mrgn">
											<input type="text" name="mobile"  id="mobile" value="" placeholder="Mobile"/>
									</div>
									<div class="col-sm-6 small-mrgn">
                                    	<div class="date_picker">
											<input type="text" class="date-pick search_arrival"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" name="dob" id="dob" value="" placeholder="Date of Birth" />
                                        </div>
									</div>
									<div class="col-sm-6 small-mrgn">
									  <label>Sex: </label> <input type="radio" name="sex"  id="sex" value="M" checked="checked" /> Male
                                          <input type="radio" name="sex"  id="sex" value="F" /> Female
									</div>
									<div class="col-sm-6 small-mrgn">
											<input type="text" name="start" id="start" value="<?php echo $start;?>" />
										<!--<div id="result"></div>--> 
									</div>
									<div class="col-sm-6 small-mrgn">
											<input type="text" name="end"  id="end" value="<?php echo $end;?>"/>
										<!--<div id="result"></div>--> 
									</div>
									<div class="col-sm-6 small-mrgn">
										<div class="date_picker">
											<input type="text" class="date-pick search_arrival"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" id="from" name="from" value="<?php echo $from;?>" />
										</div>
									</div>
									<div class="col-sm-6 small-mrgn">
										<div class="date_picker">
											<input type="text"  class="date-pick search_arrival"  data-date-format="yyyy-mm-dd" data-date-start-date="0d" id="to" name="to" value="<?php echo $to;?>" />
										</div>
									</div>
									<hr />
									<div class="col-sm-12 small-mrgn">
										<div class="slt">
                                        <img src="../wp-content/plugins/transportbooking/uploads/vehicles/<?php echo $vehicle_pic;?>" border="0" />
											<label>Vehicle: </label>
											<select name="vehicle" id="vehicle">
												<option value="">-Select Vehicle-</option>
								<?php
								$SQL = " SELECT * FROM vehicle WHERE status = '0' ORDER BY vehicle ";
								$rs = mysqli_query($alink,$SQL) or die($SQL);
								while($row = mysqli_fetch_array($rs, MYSQLI_ASSOC))
								{
									if($row['id'] == $vehicle)
									$selected = 'selected=selected';
									else
									$selected = '';
								?>
                                			  <option value="<?php echo $row['id'];?>" <?php echo $selected;?>><?php echo $row['vehicle'];?></option>
                                 <?php
								}
								?>
											</select>
										</div>
										<div class="slt">
											<label>Person(s): </label>
											<select name="person">
                                 <?php
								 for($i=1;$i<=9;$i++)
								 {
									if($i == $person)
									$selected = 'selected=selected';
									else
									$selected = '';
									echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
								 }
								 ?>
											</select>
										</div>
										<div class="clearfix"></div>
									</div>
									<div class="col-sm-6 small-mrgn">
										<button type="submit" id="sub" name="sub">Book Now</button>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="application/javascript">
jQuery(document).ready(function(){
	
	jQuery('#sub').click( function () {
		
		if(jQuery('#fname').val() == '')
		{
			alert('Enter First Name');
			jQuery('#fname').focus();
			return false;
		}else if(jQuery('#lname').val() == ''){
			alert('Enter Last Name');
			jQuery('#lname').focus();
			return false;
		}else if(jQuery('#email').val() == '' || isValidEmailAddress(jQuery('#email').val())==false){
			alert('Enter Valid EmailID');
			jQuery('#email').val('');
			jQuery('#email').focus();
			return false;
		}else if(jQuery('#mobile').val() == '' || MobValidate(jQuery('#mobile').val()) == false){
			alert('Enter Valid Mobile No!');
			jQuery('#mobile').val('');
			jQuery('#mobile').focus();
			return false;
		}else if(jQuery('#dob').val() == '' || validateDateFormat(jQuery('#dob').val()) == false){
			alert('Enter Valid Date Format!');
			jQuery('#dob').val('');
			jQuery('#dob').focus();
			return false;
		}else if(jQuery('#vehicle').val() == ''){
			alert('Select Vehicle');
			jQuery('#vahicle').focus();
			return false;
		}
		return true;
	});
});

function isValidEmailAddress(emailAddress) {

    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);

    return pattern.test(emailAddress);

}

function MobValidate() {
        var mobile = document.getElementById("mobile").value;
        var pattern = /^\d{10}$/;
        if (pattern.test(mobile)) 
		return true;
        else
        return false;
    }

function validateDateFormat(dateVal){
 
      var dateVal = dateVal;
 
      if (dateVal == null) 
          return false;
 
      var validatePattern = /^(\d{4})(\/|-)(\d{1,2})(\/|-)(\d{1,2})$/;
 
          dateValues = dateVal.match(validatePattern);
 
          if (dateValues == null) 
              return false;
 
      var dtYear = dateValues[1];        
          dtMonth = dateValues[3];
          dtDay=  dateValues[5];
 
       if (dtMonth < 1 || dtMonth > 12) 
          return false;
       else if (dtDay < 1 || dtDay> 31) 
         return false;
       else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
         return false;
       else if (dtMonth == 2){ 
         var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
         if (dtDay> 29 || (dtDay ==29 && !isleap)) 
                return false;
      }
 
     return true;
}

jQuery('.date-pick').datepicker({ autoclose: true,todayHighlight: true});
</script>
