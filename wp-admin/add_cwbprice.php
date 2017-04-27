<?php
global $wpdb;
$table_name = $wpdb->prefix .'options';

$search = "Select option_value from $table_name Where option_name = 'cwb_price' ";
$result=$wpdb->get_results($search);

if(isset($_POST['sub']))
{
	$cwb_price=$_POST['cwb_price'];	
	if($result[0]->option_value != '')
	{
		$sql_q="UPDATE $table_name SET option_value = '$cwb_price' WHERE option_name = 'cwb_price' ";
		$res=$wpdb->query($sql_q);
	}else{
		$sql_q="INSERT INTO $table_name SET `option_name` = 'cwb_price' , option_value = '$cwb_price'";
		$res=$wpdb->query($sql_q);
	}

	if($res)
	$msg = '<span style="color:green; font:bold">Successfully Updated!!</span>';
	else
	$msg = '<span style="color:red; font:bold"Error !</applet>';

$search = "Select option_value from $table_name Where option_name = 'cwb_price' ";
$result=$wpdb->get_results($search);
}
?>
<script language="javascript">


</script>
<body>
<form id="form1" name="form1" method="post" action="" >
  <p>Child with Bed (CWB)</p>
  <p><?php echo $msg;?>  </p>
  <p>
    <label for="textfield">CWB Price</label>
    <input type="text" name="cwb_price" id="cwb_price"  value="<?php echo $result[0]->option_value;?>"/>
  </p>
  <p>
    <input type="submit" name="sub" id="button" value="Update CWB Price" />
  </p>
</form>
</form>
