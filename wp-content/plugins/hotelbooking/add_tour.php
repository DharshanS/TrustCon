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
$table_name = 'tour';

if(isset($_REQUEST['sub']))
{

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
			$moved = move_uploaded_file($file_tmp,$plugin_dir_path."/uploads/tours/".$file_name);
			# Thumb
			imageResizing ( $plugin_dir_path."/uploads/tours/", $file_name, $maxWidth = 50, $maxHeight = 50, $prefix = 'thumb_'  );
			//echo "Success";
		}else{
			//print_r($errors);
		}
	}
	
$tour=ucfirst($_REQUEST['tour']);	
$description=$_REQUEST['description'];
$price=$_REQUEST['price'];
$available=$_REQUEST['available'];
//$hotel=$_REQUEST['hotel'];
$city=$_REQUEST['city'];

if($_FILES['photo']['name'] != '')
$fname=$_FILES['photo']['name']; 
else
$fname = '';

if($tour!="")
{
 $sql_q="INSERT INTO $table_name SET city_id = $city , `tour`='$tour', `description`='$description', `price`=$price , available = '$available' ,  photo = '$fname' ";

$res=$wpdb->query($sql_q);
}
else
{
	$msg = "<span style='color:red'>Please Fill all the field</span>";
}
	

if($res>0)
{
	$msg = "<span style='color:green'>Tour Successfully Added!</span>";
?>	
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=tour-master"; 
    }, 2000);
</script>
<?php	
}

}


if(isset($_REQUEST['edit']))
{
	
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
			move_uploaded_file($file_tmp,$plugin_dir_path."/uploads/tours/".$file_name);
			# Thumb
			imageResizing ( $plugin_dir_path."/uploads/tours/", $file_name, $maxWidth = 100, $maxHeight = 100, $prefix = 'thumb_'  );
			//echo "Success";
		}else{
			//print_r($errors);
		}
	}
###### upload	
	
$tour=ucfirst($_REQUEST['tour']);	
$description=$_REQUEST['description'];
$price=$_REQUEST['price'];
$available=$_REQUEST['available'];
//$hotel=$_REQUEST['hotel'];
$city=$_REQUEST['city'];
$old_photo = $_REQUEST['old_photo'];

if($_FILES['photo']['name'] != '' )
$fname = $file_name ;
else
$fname = $old_photo ;

if($tour!="")
{
$sql_update="UPDATE $table_name SET photo = '$fname' , city_id = $city , `tour`='$tour', `description`='$description', `price`=$price , available = '$available'  WHERE id='".$_REQUEST['editid']."'";

	## Unlink old Pic
	if($file_name != '' && $old_photo != $file_name || file_exists($plugin_dir_path."/uploads/hotels/".$old_photo))
	{
		if($old_photo != '')
		{
			//unlink($plugin_dir_path."/uploads/tours/".$old_photo);
			//unlink($plugin_dir_path."/uploads/tours/thumb__".$old_photo);
		}
	}

$res=$wpdb->query($sql_update);
}
else
{
$msg = "<span style='color:red'>Please Fill all the fields</span>";	
}

	
if($res>0)
{
	$msg = "<span style='color:green'>Tour Update  Successfully!</span>";
	
	?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=tour-master"; 
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
$msg = "<span style='color:red'>Tour Deleted!</span>";
?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=tour-master"; 
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
$msg = "<span style='color:red'>Tour Undeleted!</span>";
?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=tour-master"; 
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
<h1>Hotel Booking System</h1>
<h2>Add Tour Package</h2>
<h3><?php echo $msg;?></h3>
<div class="wrap">
<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" >
  <p>
    <label for="textfield">Tour Package Name</label>
    <input type="text" name="tour" id="tour"  value=""/>
  </p>
  <p>
    <label for="textfield">Tour Description</label>
    <textarea  name="description" id="description"  ></textarea>
  </p>
  <p>
    <label for="textfield">Price (LKR)</label>
    <input type="text" name="price" id="price"  value=""/>
  </p>
 <!-- <p>
    <label for="textfield">Hotel Name</label>
    <select name="hotel" id="hotel">
    <option value="">-Select hotel-</option>
	<?php
	/*$cres = $wpdb->get_results( "SELECT * FROM hotel ORDER BY hotel ASC " );
	foreach($cres as $c)
    {
		echo '<option value="'.$c->id.'">'.$c->hotel.'</option>';
	}*/
	?>
    </select>
  </p>-->
  <p>
    <label for="textfield">City Name</label>
    <select name="city" id="city">
    <option value="">-Select City-</option>
	<?php
	$cres = $wpdb->get_results( "SELECT * FROM city ORDER BY city ASC " );
	foreach($cres as $c)
    {
		echo '<option value="'.$c->id.'">'.$c->city.'</option>';
	}
	?>
    </select>
  </p>
  <p>
    <label for="textfield">Availabel (Y/N)</label>
    <select name="available" id="available">
    <option value="">-Select -</option>
    <option value="0">Yes</option>
    <option value="1">No</option>
    </select>
  <p>
    <label for="textfield">Tour Image</label>
    <input type="file" name="photo" class="add-new-h2" id="photo"  value=""/>
  </p>
  <p>
<input type="submit" name="sub" id="sub"  class="add-new-h2" value="Add" />
  </p>
</form>
</div>
<?php }else { ?>
<h1>Hotel Booking System</h1>
<h2>Edit Tour Package</h2>
<h3><?php echo $msg;?></h3>
<div class="wrap">
<form id="form2" name="form2" method="post" action=""  enctype="multipart/form-data">

<input type="hidden" name="editid" id="editid" value="<?php echo $sql_res[0]->id; ?>" />

  <p>
    <label for="textfield">Tour Package Name</label>
    <input type="text" name="tour" id="tour"  value="<?php echo $sql_res[0]->tour; ?>"/>
  </p>
  <p>
    <label for="textfield">Tour Description</label>
    <textarea  name="description" id="description"  ><?php echo $sql_res[0]->description; ?></textarea>
  </p>
  <p>
    <label for="textfield">Price (LKR)</label>
    <input type="text" name="price" id="price"  value="<?php echo $sql_res[0]->price; ?>"/>
  </p>
<!--  <p>
    <label for="textfield">Hotel Name</label>
    <select name="hotel" id="hotel">
    <option value="">-Select Hotel-</option>
	<?php
	/*$cres = $wpdb->get_results( "SELECT * FROM hotel ORDER BY hotel ASC " );
	$selectd = 'selected=selected';
	foreach($cres as $c)
    {
		if($sql_res[0]->hotel_id == $c->id)
		echo '<option value="'.$c->id.'" '.$selectd.'>'.$c->hotel.'</option>';
		else
		echo '<option value="'.$c->id.'" >'.$c->hotel.'</option>';
	}*/
	?>
    </select>
  </p>
-->  <p>
    <label for="textfield">City Name</label>
    <select name="city" id="city">
    <option value="">-Select City-</option>
	<?php
	$cres = $wpdb->get_results( "SELECT * FROM city ORDER BY city ASC " );
	$selectd = 'selected=selected';
	foreach($cres as $c)
    {
		if($sql_res[0]->city_id == $c->id)
		echo '<option value="'.$c->id.'" '.$selectd.'>'.$c->city.'</option>';
		else
		echo '<option value="'.$c->id.'" >'.$c->city.'</option>';
	}
	?>
    </select>
  </p>
  <p>
    <label for="textfield">Availabel (Y/N)</label>
    <select name="available" id="available">
    <option value="">-Select -</option>
	<?php
		if($sql_res[0]->available == '0')
		{
			echo '<option value="0" selected="selected">Yes</option>';
			echo '<option value="1" >No</option>';
		}else{
			echo '<option value="0" >Yes</option>';
			echo '<option value="1" selected="selected">No</option>';
		}
	?>
    </select>
  <p>
    <input type="hidden" name="old_photo" id="old_photo" value="<?php echo $sql_res[0]->photo;?>" />
    <?php
	if($sql_res[0]->photo != '')
	{
	?>
    <img src="<?php echo $pluginurl.'/uploads/tours/thumb__'.$sql_res[0]->photo;?>" border="0" />
    <?php
	}else{
	?>
    <img src="<?php echo $pluginurl.'/img/nohotel.jpeg';?>" border="0" width="50" height="50"/>
	<?php
	}
    ?>
    <label for="textfield">Upload?</label>
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
<script src="js/jquery-1.11.3.min.js"></script>
<script>
jQuery(document).ready( function () {  //alert('fdgd');
	
	jQuery('#sub').click(  function () { //alert('fdgd');
	   
	   if(jQuery('#tour').val() == '')
	   { 
		  alert('Enter Tour Package Name');
		  jQuery('#tour').focus();
	   }else if(jQuery('#price').val() == ''){
		  alert('Enter Price');
		  jQuery('#price').focus();
	   }else if(jQuery('#hotel').val() == ''){
		  alert('Enter Hotel');
		  jQuery('#hotel').focus();
	   }
			   	
	});
	
	jQuery('#edit').click(  function () { //alert('fdgd');
	   
	   if(jQuery('#tour').val() == '')
	   { 
		  alert('Enter Tour Package Name');
		  jQuery('#tour').focus();
	   }else if(jQuery('#price').val() == ''){
		  alert('Enter Price');
		  jQuery('#price').focus();
	   }else if(jQuery('#hotel').val() == ''){
		  alert('Enter Hotel');
		  jQuery('#hotel').focus();
	   }
			   	
	});

});
</script>