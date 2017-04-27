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
$table_name = 'vehicle';

if(isset($_REQUEST['sub']))
{
$file_name = '';

## Upload
if($_FILES['photo']['name'] !=''){
		$errors= array();
		$file_name = $_FILES['photo']['name'];
		$file_size =$_FILES['photo']['size'];
		$file_tmp =$_FILES['photo']['tmp_name'];
		$file_type=$_FILES['photo']['type'];   
		$file_ext=strtolower(end(explode('.',$_FILES['photo']['name'])));
		
		$expensions= array("jpeg","jpg","png","gif"); 		
		if(in_array($file_ext,$expensions)=== false){
			$errors[]="extension not allowed, please choose a JPEG or JPG or GIF or PNG file.";
		}
		if($file_size > 2097152){
		$errors[]='File size must be excately 2 MB';
		}				
		if(empty($errors)==true){
			# Original
			$moved = move_uploaded_file($file_tmp,$plugin_dir_path."/uploads/vehicles/".$file_name);
			# Thumb
			imageResizing ( $plugin_dir_path."/uploads/vehicles/", $file_name, $maxWidth = 50, $maxHeight = 50, $prefix = 'thumb_'  );
			//echo "Success";
		}else{
			//print_r($errors);
		}
	}
###### upload	
$vehicle=ucfirst($_REQUEST['vehicle']);	
$category=$_REQUEST['category'];
$vehicle_number=$_REQUEST['vehicle_number'];
$rate=$_REQUEST['rate'];

if($_FILES['photo']['name'] != '')
$fname=$_FILES['photo']['name']; 
else
$fname = '';

if($vehicle!="")
{
  $sql_q = "INSERT INTO $table_name SET rate_per_kilometer = $rate , `vehicle`='$vehicle', `vehicle_number`='$vehicle_number' , `category`='$category', photo = '$fname' "; 

$res=$wpdb->query($sql_q);
}
else
{
	$msg = "<span style='color:red'>Please Fill all the field</span>";
}
	

if($res>0)
{
	$msg = "<span style='color:green'>Vehicle Successfully Added!</span>";
?>	
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-master"; 
    }, 2000);
</script>
<?php	
}

}


if(isset($_REQUEST['edit']))
{
## Upload
$file_name = '';

if($_FILES['photo']['name'] !=''){
		$errors= array();
		$file_name = $_FILES['photo']['name'];
		$file_size =$_FILES['photo']['size'];
		$file_tmp =$_FILES['photo']['tmp_name'];
		$file_type=$_FILES['photo']['type'];   
		$file_ext=strtolower(end(explode('.',$_FILES['photo']['name'])));
		
		$expensions= array("jpeg","jpg","png","gif"); 		
		if(in_array($file_ext,$expensions)=== false){
			$errors[]="extension not allowed, please choose a JPEG or JPG or GIF or PNG file.";
		}
		if($file_size > 2097152){
		$errors[]='File size must be excately 2 MB';
		}				
		if(empty($errors)==true){
			# Original
			move_uploaded_file($file_tmp,$plugin_dir_path."/uploads/vehicles/".$file_name);
			# Thumb
			imageResizing ( $plugin_dir_path."/uploads/vehicles/", $file_name, $maxWidth = 100, $maxHeight = 100, $prefix = 'thumb_'  );
			//echo "Success";
		}else{
			//print_r($errors);
		}
	}
###### upload	

$vehicle=ucfirst($_REQUEST['vehicle']);	
$category=$_REQUEST['category'];
$vehicle_number=$_REQUEST['vehicle_number'];
$rate=$_REQUEST['rate'];
$old_photo = $_REQUEST['old_photo'];

if($_FILES['photo']['name'] != '' )
$fname = $file_name ;
else
$fname = $old_photo ;

if($vehicle != "")
{
	
 $sql_update = "UPDATE $table_name SET rate_per_kilometer = $rate , `vehicle`='$vehicle', `vehicle_number`='$vehicle_number' , `category`='$category', photo = '$fname' WHERE id='".$_REQUEST['editid']."'"; 
	
	## Unlink old Pic
	if($old_photo != $file_name || file_exists($plugin_dir_path."/uploads/vehicles/".$old_photo))
	{
			//unlink($plugin_dir_path."/uploads/vehicles/".$old_photo);
			//unlink($plugin_dir_path."/uploads/vehicles/thumb__".$old_photo);
	}

$res=$wpdb->query($sql_update);
}
else
{
$msg = "<span style='color:red'>Please Fill all the fields</span>";	
}

	
if($res>0)
{
	$msg = "<span style='color:green'>Vehicle Update  Successfully!</span>";
	
	?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-master"; 
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
$msg = "<span style='color:red'>Vehicle Deleted!</span>";
?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-master"; 
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
$msg = "<span style='color:red'>Vehicle Undeleted!</span>";
?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=transport-master"; 
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
<h2>Add Vehicle</h2>
<h3><?php echo $msg;?></h3>
<div class="wrap">
<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" >
  <p>
    <label for="textfield">Vehicle Name</label>
    <input type="text" name="vehicle" id="vehicle"  value=""/>
  </p>
  <p>
    <label for="textfield" >Vehicle Number</label>
    <input type="text" name="vehicle_number" id="vehicle_number"  value=""/>
  </p>
  <p>
    <label for="textfield" >Rate/Kilometer</label>
    <input type="text" name="rate" id="rate"  value=""/>
  </p>
  <p>
    <label for="textfield">Category</label>
    <select name="category" id="category">
    <option value="">-Select Category-</option>
    <option value="Taxi">Taxi</option>
    <option value="Medium Carrier">Medium Carrier</option>
    <option value="Large Carrier">Large Carrier</option>
    </select>
  </p>
  <p>
    <label for="textfield">Vehicle Image</label>
    <input type="file" name="photo" class="add-new-h2" id="photo"  value=""/>
  </p>
  <p>
<input type="submit" name="sub" id="sub"  class="add-new-h2" value="Add" />
  </p>
</form>
</div>
<?php }else { ?>
<h1>Transport Booking System</h1>
<h2>Edit Vehicle</h2>
<h3><?php echo $msg;?></h3>
<div class="wrap">
<form id="form2" name="form2" method="post" action="" enctype="multipart/form-data" >

<input type="hidden" name="editid" id="editid" value="<?php echo $sql_res[0]->id; ?>" />

  <p>
    <label for="textfield">Vehicle Name</label>
    <input type="text" name="vehicle" id="vehicle"  value="<?php echo $sql_res[0]->vehicle; ?>"/>
  </p>
  <p>
    <label for="textfield">Vehicle Number</label>
    <input type="text" name="vehicle_number" id="vehicle_number"  value="<?php echo $sql_res[0]->vehicle_number; ?>"/>
  </p>
  <p>
    <label for="textfield" >Rate/Kilometer</label>
    <input type="text" name="rate" id="rate"  value="<?php echo $sql_res[0]->rate_per_kilometer; ?>"/>
  </p>
  <p>
    <label for="textfield">Category</label>
    <select name="category" id="category">
    <option value="">-Select Category-</option>
    <?php
	$arr = array("Taxi","Medium Carrier","Large Carrier");
	$selected = 'selected=selected';
	foreach($arr as $k=>$v)
	{
		if($v == $sql_res[0]->category)
		echo '<option value="'.$v.'" '.$selected.' >'.$v.'</option>';
		else
		echo '<option value="'.$v.'" >'.$v.'</option>';
	}
	?>
    </select>
  </p>
  <p>
    <input type="hidden" name="old_photo" id="old_photo" value="<?php echo $sql_res[0]->photo;?>" />
    <?php
	if($sql_res[0]->photo != '')
	{
	?>
    <img src="<?php echo $pluginurl.'/uploads/vehicles/thumb__'.$sql_res[0]->photo;?>" border="0" />
    <?php
	}else{
	?>
    <img src="<?php echo $pluginurl.'/img/no-car.png';?>" border="0" width="50" height="50" />
	<?php
	}
    ?>
    <label for="textfield">Upload Vehicle?</label>
    <input type="file" name="photo" class="add-new-h2" id="photo"  value=""/>
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
jQuery(document).ready( function () { //alert('dsffs');
	
	jQuery('#sub').click(  function () {
	   
	   if(jQuery('#vehicle').val() == '')
	   {
		  alert('Enter Vehicle Name');
		  jQuery('#vehicle').focus();
	   }else if(jQuery('#vehicle_number').val() == ''){
		  alert('Enter Vehicle Number');
		  jQuery('#vehicle_number').focus();
	   }			   	
	
	});

	jQuery('#edit').click(  function () {
	   
	   if(jQuery('#vehicle').val() == '')
	   {
		  alert('Enter Vehicle Name');
		  jQuery('#vehicle').focus();
	   }else if(jQuery('#vehicle_number').val() == ''){
		  alert('Enter Vehicle Number');
		  jQuery('#vehicle_number').focus();
	   }			   	
	});
	
});
</script>