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
$table_name = 'room';

if(isset($_REQUEST['sub']))
{

## Upload
if(isset($_FILES['photo'])  && $_FILES['photo']['name'] !=''){
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
			$moved = move_uploaded_file($file_tmp,$plugin_dir_path."/uploads/rooms/".$file_name);
			# Thumb
			imageResizing ( $plugin_dir_path."/uploads/rooms/", $file_name, $maxWidth = 50, $maxHeight = 50, $prefix = 'thumb_'  );
			//echo "Success";
		}else{
			//print_r($errors);
		}
	}
###### upload	
$room_no=$_REQUEST['room_no'];	
$hotel=$_REQUEST['hotel'];
$description=$_REQUEST['description'];
$room_type=$_REQUEST['room_type'];
$accomodation_type=$_REQUEST['accomodation_type'];
$price=$_REQUEST['price'];
$available=$_REQUEST['available'];
$file_name=$_FILES['photo']['name']; 

if($hotel!="")
{
  $sql_q = "INSERT INTO $table_name SET `room_no`= '$room_no' , description = '$description' , hotel_id = $hotel , photo = '$file_name' , room_type = '$room_type' , accomodation_type = '$accomodation_type' , price = $price, available = '$available' "; 

$res=$wpdb->query($sql_q);
}
else
{
	$msg = "<span style='color:red'>Please Fill all the field</span>";
}
	

if($res>0)
{
	$msg = "<span style='color:green'>Hotel Successfully Added!</span>";
?>	
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=room-master"; 
    }, 2000);
</script>
<?php	
}

}


if(isset($_REQUEST['edit']))
{
## Upload
if(isset($_FILES['photo']) && $_FILES['photo']['name'] !=''){
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
			move_uploaded_file($file_tmp,$plugin_dir_path."/uploads/rooms/".$file_name);
			# Thumb
			imageResizing ( $plugin_dir_path."/uploads/rooms/", $file_name, $maxWidth = 100, $maxHeight = 100, $prefix = 'thumb_'  );
			//echo "Success";
		}else{
			//print_r($errors);
		}
	}
###### upload	

$room_no=$_REQUEST['room_no'];	
$hotel=$_REQUEST['hotel'];
$description=$_REQUEST['description'];
$room_type=$_REQUEST['room_type'];
$accomodation_type=$_REQUEST['accomodation_type'];
$price=$_REQUEST['price'];
$available=$_REQUEST['available'];
$old_photo = $_REQUEST['old_photo'];

if($_FILES['photo']['name'] =='' && $old_photo !='')
$file_name = $old_photo;
else
$file_name = $_FILES['photo']['name'] ;

if($hotel!="")
{
	
  $sql_update = "UPDATE $table_name SET `room_no`= '$room_no' , description = '$description' , hotel_id = $hotel , photo = '$file_name' , room_type = '$room_type' , accomodation_type = '$accomodation_type' , price = $price, available = '$available'  WHERE id='".$_REQUEST['editid']."'"; 
	## Unlink old Pic
	if($file_name != '' && $old_photo != $file_name || file_exists($plugin_dir_path."/uploads/rooms/".$old_photo))
	{
		if($old_photo != '')
		{
			unlink($plugin_dir_path."/uploads/rooms/".$old_photo);
			unlink($plugin_dir_path."/uploads/rooms/thumb__".$old_photo);
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
	$msg = "<span style='color:green'>Room Update  Successfully!</span>";
	
	?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=room-master"; 
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
$msg = "<span style='color:red'>Room Deleted!</span>";
?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=room-master"; 
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
$msg = "<span style='color:red'>Room Undeleted!</span>";
?>
<script>
setTimeout(function () {
       window.location.href = "<?php echo bloginfo('url');?>/wp-admin/admin.php?page=room-master"; 
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
<h2>Add Room</h2>
<h3><?php echo $msg;?></h3>
<div class="wrap">
<form id="form1" name="form1" method="post" action="" enctype="multipart/form-data" >
  <p>
    <label for="textfield">Room Number</label>
    <input type="text" name="room_no" id="room_no"  value=""/>
  </p>
  <p>
    <label for="textfield" >Room Description</label>
    <textarea name="description" id="description"></textarea>
  </p>
  <p>
    <label for="textfield">Price (LKR)</label>
    <input type="text" name="price" id="price"  value=""/>
  </p>
  <p>
    <label for="textfield">Hotel Name</label>
    <select name="hotel" id="hotel">
    <option value="">-Select Hotel-</option>
	<?php
	$cres = $wpdb->get_results( "SELECT * FROM hotel ORDER BY hotel ASC " );
	foreach($cres as $c)
    {
		echo '<option value="'.$c->id.'">'.$c->hotel.'</option>';
	}
	?>
    </select>
  </p>
  <p>
    <label for="textfield">Room Type</label>
    <input type="text" name="room_type" id="room_type"  value=""/>
    <!--<select name="room_type" id="room_type">
    <option value="">-Select Room Type-</option>
    <option value="2 Bedrooms">2 Bedrooms</option>
    <option value="3 Bedrooms">3 Bedrooms</option>
    <option value="4 Bedrooms">4 Bedrooms</option>
    </select>-->
  </p>
  <p>
    <label for="textfield">Accomodation Type</label>
    <select name="accomodation_type" id="accomodation_type">
    <option value="">-Select Accomodation Type-</option>
    <option value="General">General</option>
    <option value="Delux">Delux</option>
    </select>
  </p>
  <p>
    <label for="textfield">Room Image</label>
    <input type="file" name="photo" class="add-new-h2" id="photo"  value=""/>
  </p>
  <p>
    <label for="textfield">Availabel (Y/N)</label>
    <select name="available" id="available">
    <option value="">-Select -</option>
    <option value="0">Yes</option>
    <option value="1">No</option>
    </select>
  <p>
  <p>
<input type="submit" name="sub" id="sub"  class="add-new-h2" value="Add" />
  </p>
</form>
</div>
<?php }else { ?>
<h1>Hotel Booking System</h1>
<h2>Edit Room</h2>
<h3><?php echo $msg;?></h3>
<div class="wrap">
<form id="form2" name="form2" method="post" action="" enctype="multipart/form-data" >

<input type="hidden" name="editid" id="editid" value="<?php echo $sql_res[0]->id; ?>" />

  <p>
    <label for="textfield">Room Number</label>
    <input type="text" name="room_no" id="room_no"  value="<?php echo $sql_res[0]->room_no; ?>"/>
  </p>
  <p>
    <label for="textfield" >Room Description</label>
    <textarea name="description" id="description"><?php echo $sql_res[0]->description; ?></textarea>
  </p>
  <p>
    <label for="textfield">Price (LKR)</label>
    <input type="text" name="price" id="price"  value="<?php echo $sql_res[0]->price; ?>"/>
  </p>
  <p>
    <label for="textfield">Hotel Name</label>
    <select name="hotel" id="hotel">
    <option value="">-Select Hotel-</option>
	<?php
	$cres = $wpdb->get_results( "SELECT * FROM hotel ORDER BY hotel ASC " );
	$selected = 'selected=selected';
	foreach($cres as $c)
    {
		if($sql_res[0]->hotel_id == $c->id)
		echo '<option value="'.$c->id.'" '.$selected.'>'.$c->hotel.'</option>';
		else
		echo '<option value="'.$c->id.'" >'.$c->hotel.'</option>';
	}
	?>
    </select>
  </p>
  <p>
    <label for="textfield">Room Type</label>
    <input type="text" name="room_type" id="room_type"  value="<?php echo $sql_res[0]->room_type; ?>"/>
    <!--<select name="room_type" id="room_type">
    <option value="">-Select Category-</option>
    <?php
	/*$arr = array("2 Bedrooms","3 Bedrooms","4 Bedrooms");
	$selected = 'selected=selected';
	foreach($arr as $k=>$v)
	{
		if($v == $sql_res[0]->room_type)
		echo '<option value="'.$v.'" '.$selected.' >'.$v.'</option>';
		else
		echo '<option value="'.$v.'" >'.$v.'</option>';
	}*/
	?>
    </select>-->
  </p>
  <p>
    <label for="textfield">Accomodation Type</label>
    <select name="accomodation_type" id="accomodation_type">
    <option value="">-Select Accomodation Type-</option>
    <?php
	$arr = array("General","Delux");
	$selected = 'selected=selected';
	foreach($arr as $k=>$v)
	{
		if($v == $sql_res[0]->accomodation_type)
		echo '<option value="'.$v.'" '.$selected.' >'.$v.'</option>';
		else
		echo '<option value="'.$v.'" >'.$v.'</option>';
	}
	?>
    </select>
  </p>
  <p>
    <input type="hidden" name="old_photo" id="old_photo" value="<?php echo $sql_res[0]->pic;?>" />
    <?php
	if($sql_res[0]->photo != '')
	{
	?>
    <img src="<?php echo $pluginurl.'/uploads/rooms/thumb__'.$sql_res[0]->photo;?>" border="0" />
    <?php
	}else{
	?>
    <img src="<?php echo $pluginurl.'/img/nohotel.jpeg';?>" border="0" />
	<?php
	}
    ?>
    <label for="textfield">Room Image Upload?</label>
    <input type="file" name="photo" class="add-new-h2" id="photo"  value=""/>
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
	   
	   if(jQuery('#room_type').val() == '')
	   {
		  alert('Enter Room Type');
		  jQuery('#room_type').focus();
	   }else if(jQuery('#hotel').val() == ''){
		  alert('Choose Hotel Name');
		  jQuery('#hotel').focus();
	   }else if(jQuery('#price').val() == ''){
		  alert('Choose Price');
		  jQuery('#price').focus();	
	   }
	});

	jQuery('#edit').click(  function () {
	   
	   if(jQuery('#room_type').val() == '')
	   {
		  alert('Enter Room Type');
		  jQuery('#room_type').focus();
	   }else if(jQuery('#hotel').val() == ''){
		  alert('Choose Hotel Name');
		  jQuery('#hotel').focus();
	   }else if(jQuery('#price').val() == ''){
		  alert('Choose Price');
		  jQuery('#price').focus();	
	   }
	
	});
	
});
</script>