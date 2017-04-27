<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
//session_start();

if(!isset($_SESSION['booking_entries'])){
echo 'Something gone wrong';
}

$bookReq =$_POST['bookRequest'];

foreach($bookReq['f_name'] as $index=>$value){
    error_log("Loop ".$index);
    ?>

<div class="col-sm-12">
<div class="gaping traveller">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	  <td><img src="/images/men.png" class="mail" /><span><?php echo $value.' '.$bookReq['l_name'][$index];?></span></td>
	  <td><img src="/images/baby.png" class="mail" /><span><?php echo $bookReq['dob'][$index];?></span></td>
	 <?php 
         if(isset($bookReq['mobNumber'])) 
         {?>
<td><img src="/images/mobile.png" class="mail" /><span><?php echo $bookReq['countryCode'].'-'.$bookReq['mobNumber'];?></span></td>
<?php 
         }else{?>
       <td><img src="/images/mobile.png" class="mail" /><span></span></td> <?php }?> 
</tr>
  </table>
</div>
</div>

<?php }?>