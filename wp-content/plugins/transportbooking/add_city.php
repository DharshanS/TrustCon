<?php
include('allfunctions.php');
$pluginurl  = plugins_url( '', __FILE__ );
$plugin_dir_path = dirname(__FILE__);
/*function admin_load_js(){
    wp_enqueue_script( 'custom_js', plugins_url( '/js/jquery-1.11.3.min.js', __FILE__ ), array('jquery') );
}
add_action('admin_enqueue_scripts', 'admin_load_js');*/
//wp_enqueue_script('test', plugin_dir_url(__FILE__) . 'js/jquery-1.11.3.min.js');

global $wpdb;
$table_name = 'city_transport';

if(isset($_REQUEST['sub']))
{
$city=ucfirst($_REQUEST['city']);	
$country=$_REQUEST['country'];

if($city!="")
{
$sql_q="INSERT INTO $table_name SET `city`='$city',`country`='$country' ";

$res=$wpdb->query($sql_q);
}
else
{
	$msg = "<span style='color:red'>Please Fill all the field</span>";
}
	

if($res>0)
{
	$msg = "<span style='color:green'>City Successfully Added!</span>";
?>	
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-city-master"; 
    }, 2000);
</script>
<?php	
}

}


if(isset($_REQUEST['edit']))
{
$city=ucfirst($_REQUEST['city']);
$country=$_REQUEST['country'];	

if($city!="")
{
$sql_update="UPDATE $table_name SET `city`='$city' , country = '$country' WHERE id='".$_REQUEST['editid']."'";

$res=$wpdb->query($sql_update);
}
else
{
$msg = "<span style='color:red'>Please Fill all the fields</span>";	
}

	
if($res>0)
{
	$msg = "<span style='color:green'>City Update  Successfully!</span>";
	
	?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-city-master"; 
    }, 2000);
</script>
    <?php
}

}


if($_REQUEST['del_id']!="")
{
$sql_update="UPDATE $table_name SET `status`='1'  WHERE id='".$_REQUEST['del_id']."'";

$res=$wpdb->query($sql_update);
if($res>0)
{
$msg = "<span style='color:red'>City Deleted!</span>";
?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-city-master"; 
    }, 2000);
</script>
<?php
}

}

if($_REQUEST['avail_id']!="")
{
$sql_update="UPDATE $table_name SET `status`='0'  WHERE id='".$_REQUEST['avail_id']."'";

$res=$wpdb->query($sql_update);
if($res>0)
{
$msg = "<span style='color:red'>City Undeleted!</span>";
?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-city-master"; 
    }, 2000);
</script>
<?php
}

}


if($_REQUEST['edit_id']!="")
{
$sql_res=$wpdb->get_results("SELECT * FROM $table_name WHERE id ='".$_REQUEST['edit_id']."' ");	

}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="<?php echo $pluginurl;?>/css/style.css"  />
<script src="<?php echo $pluginurl;?>/js/jquery-1.11.3.min.js"></script>
</head>
<body>
<?php if($_REQUEST['edit_id']=="")
{?>
<h1>Transport Booking System</h1>
<h2>Add City</h2>
<h3><?php echo $msg;?></h3>
<div class="wrap">
<form id="form1" name="form1" method="post" action="" >
  <p>
    <label for="textfield">City Name</label>
    <input type="text" name="city" id="city"  value=""/>
  </p>
  <p>
    <label for="textfield">Country Name</label>
    <select name="country" id="country">
    <option value="">-Select Country-</option>
	<?php
	$cres = $wpdb->get_results( "SELECT name FROM countries ORDER BY name ASC " );
	foreach($cres as $c)
    {
		echo '<option value="'.$c->name.'">'.$c->name.'</option>';
	}
	?>
    </select>
  </p>
  <p>
<input type="submit" name="sub" id="sub"  class="add-new-h2" value="Add" />
  </p>
</form>
</div>
<?php }else { ?>
<h1>Transport Booking System</h1>
<h2>Edit City</h2>
<h3><?php echo $msg;?></h3>
<div class="wrap">
<form id="form2" name="form2" method="post" action=""  enctype="multipart/form-data">

<input type="hidden" name="editid" id="editid" value="<?php echo $sql_res[0]->id; ?>" />

  <p>
    <label for="textfield">City Name</label>
    <input type="text" name="city" id="city"  value="<?php echo $sql_res[0]->city; ?>"/>
  </p>
  <p>
    <label for="textfield">Country Name</label>
    <select name="country" id="country">
    <option value="">-Select Country-</option>
	<?php
	$cres = $wpdb->get_results( "SELECT name FROM countries ORDER BY name ASC " );
	foreach($cres as $c)
    {
		if($sql_res[0]->country == $c->name)
		echo '<option value="'.$c->name.'" selected="selected">'.$c->name.'</option>';
		else
		echo '<option value="'.$c->name.'" >'.$c->name.'</option>';
	}
	?>
    </select>
  </p>
  <p>
    <input type="submit" name="edit" id="edit"  class="add-new-h2" value="Update" />
  </p>
</form>
</div>
<?php } ?>
</body>
</html>
<script>
jQuery(document).ready( function () {  //alert('fdgd');
	
	jQuery('#sub').click(  function () { //alert('fdgd');
	   
	   if(jQuery('#city').val() == '')
	   { 
		  alert('Enter City Name');
		  jQuery('#city').focus();
	   }else if(jQuery('#country').val() == ''){
		  alert('Enter Country Name');
		  jQuery('#country').focus();
	   }
			   	
	});
	
	jQuery('#edit').click(  function () { //alert('fdgd');
	   
	   if(jQuery('#city').val() == '')
	   { 
		  alert('Enter City Name');
		  jQuery('#city').focus();
	   }else if(jQuery('#country').val() == ''){
		  alert('Enter Country Name');
		  jQuery('#country').focus();
	   }
			   	
	});

});
</script>