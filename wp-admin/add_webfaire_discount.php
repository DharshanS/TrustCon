<?php
global $wpdb;
$table_name = 'airlines';

if(isset($_POST['sub']))
{
$airline=$_POST['airline'];	
$iata=$_POST['iata'];
$sname=$_POST['sname'];
$vtype=$_POST['vtype'];
$discount=$_POST['discount'];
if(($airline !="")&&($iata !="")&&($sname !="")&&($vtype !=""))
{
$sql_q="INSERT INTO $table_name SET `airline`='$airline',`iata`='$iata',`short_name`='$sname',`vend_type`='$vtype',`discount`=$discount ";

$res=$wpdb->query($sql_q);
}
else
{
	echo "Please Fill all the field"; 
}
	

if($res>0)
{
	echo "Airline Enter success";
}

}


if(isset($_POST['edit']))
{
$airline=$_POST['airline'];	
$iata=$_POST['iata'];
$sname=$_POST['sname'];
$vtype=$_POST['vtype'];
$discount=$_POST['discount'];
if(($airline !="")&&($iata !="")&&($sname !="")&&($vtype !=""))
{ 
$sql_update="UPDATE $table_name SET `airline`='$airline',`iata`='$iata',`short_name`='$sname',`vend_type`='$vtype',`discount`=$discount WHERE id='".$_REQUEST['editid']."'";

$res=$wpdb->query($sql_update);
}
else
{
echo "Please Fill all the field";	echo 'ff';
}

	
if($res>0)
{
	echo "Airline Update  success";
	
	?>
      <script>
function myFunction() 
{
    window.location.href('<?php echo bloginfo('url');?>/wp-admin/admin.php?page=list-webfaire-discount');
}
</script>
    <?php
}

}



if($_GET['edit_id']!="")
{
$sql_res=$wpdb->get_results("SELECT * FROM $table_name WHERE id ='".$_GET['edit_id']."'  ");	

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Airline Discount Manager - Admin</title>
</head>
<script language="javascript">
function general_valid()
{
if(document.getElementById('airline').value=='')
{
document.getElementById('airline').style.backgroundColor="#FF0000";
document.getElementById('airline').focus();	
return false;

}

if(document.getElementById('iata').value=='')
{
document.getElementById('iata').style.backgroundColor="";	
document.getElementById('iata').style.backgroundColor="#FF0000";
document.getElementById('iata').focus();
return false;	
}

if(document.getElementById('sname').value=='')
{
document.getElementById('sname').style.backgroundColor="";	
document.getElementById('sname').style.backgroundColor="#FF0000";
document.getElementById('sname').focus();
return false;	
}

if(document.getElementById('vtype').value=='')
{
document.getElementById('vtype').style.backgroundColor="";	
document.getElementById('vtype').style.backgroundColor="#FF0000";
document.getElementById('vtype').focus();
return false;	
}




if(document.getElementById('discount').value=='')
{
document.getElementById('discount').style.backgroundColor="";	
document.getElementById('discount').style.backgroundColor="#FF0000";
document.getElementById('discount').focus();
return false;	
}


}

</script>
<body>
<?php if($_GET['edit_id']=="")
{?>
<form id="form1" name="form1" method="post" action="" onSubmit="return general_valid();">
<h1>Webfaire Discount Manager - Add</h1>
  <p>
    <label for="textfield">Airline Name</label>
    <input type="text" name="airline" id="airline"  value=""/>
  </p>
  <p>
    <label for="textfield">IATA</label>
   <input type="text" name="iata" id="iata"  value=""/>
  </p>
  
  <p>
    <label for="textfield">Short Name</label>
    <input type="text" name="sname" id="snamesname"  value=""/>
  </p>
   <p>
    <label for="textfield">Vendor Type</label>
    <input type="text" name="vtype" id="vtype"  value=""/>
  </p>
  <p>
    <label for="textfield2">Discount(%)</label>
    <input type="text" name="discount" id="discount"  value=""/>
  </p>
  <!--<p>
    <label for="textfield2">Room Avalaible</label> 
    <label for="select"></label>
    <select name="avail" id="avail">
     <option value="-1">Select</option>
      <option value="1">YES</option>
      <option value="0">No</option>
    </select>
  </p>-->
  <p>
    <input type="submit" name="sub" id="button" value="Add" />&nbsp;&nbsp;<input type="reset"  value="Clear" />
  </p>
</form>
<?php }else { ?>
<h1>Webfaire Discount Manager - Edit</h1>
<form id="form2" name="form2" method="post" action="" onSubmit="return general_valid();">

<input type="hidden" name="editid" id="editid" value="<?php echo $sql_res[0]->id; ?>" /><img src="<?php echo site_url().'/airimages/'.$sql_res[0]->iata.'.GIF'; ?>" border="0" />

  <p>
    <label for="textfield">Airline Name</label>
    <input type="text" name="airline" id="airline"  value="<?php echo $sql_res[0]->airline; ?>"/>
  </p>
  <p>
    <label for="textfield">IATA</label>
   <input type="text" name="iata" id="iata"  value="<?php echo $sql_res[0]->iata; ?>"/>
  </p>
  
  <p>
    <label for="textfield">Short Name</label>
    <input type="text" name="sname" id="sname"  value="<?php echo $sql_res[0]->short_name; ?>"/>
  </p>
   <p>
    <label for="textfield">Vendor Type</label>
    <input type="text" name="vtype" id="vtype"  value="<?php echo $sql_res[0]->vend_type; ?>"/>
  </p>
  <p>
    <label for="textfield2">Discount(%)</label>
    <input type="text" name="discount" id="discount"  value="<?php echo $sql_res[0]->discount; ?>"/>
  </p>
  <!--<p>
    <label for="textfield2">Room Avalaible</label> 
    <label for="select"></label>
    <select name="avail" id="avail">
     <option value="-1">Select</option>
      <option value="1" <?php if($sql_res[0]->STATUS==1){ echo "selected";}?>>YES</option>
      <option value="0" <?php if($sql_res[0]->STATUS==0){ echo "selected";}?>>No</option>
    </select>
  </p>-->
  <p>
    <input type="submit" name="edit" id="button" value="Update" />
  </p>
</form>
<?php } ?>
</body>
</html>