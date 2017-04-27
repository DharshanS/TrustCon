<?php
global $wpdb;
$table_name = $wpdb->prefix .'options';

$search = "Select option_value from $table_name Where option_name = 'embacy_letter_fee' ";
$result=$wpdb->get_results($search);

if(isset($_POST['sub']))
{
	$embacy_letter_fee=$_POST['embacy_letter_fee'];	
	if($result[0]->option_value != '')
	{
		$sql_q="UPDATE $table_name SET option_value = '$cwb_price' WHERE option_name = 'embacy_letter_fee' ";
		$res=$wpdb->query($sql_q);
	}else{
		$sql_q="INSERT INTO $table_name SET `option_name` = 'embacy_letter_fee' , option_value = '$embacy_letter_fee'";
		$res=$wpdb->query($sql_q);
	}

	if($res)
	$msg = '<span style="color:green; font:bold">Successfully Updated!!</span>';
	else
	$msg = '<span style="color:red; font:bold"Error !</applet>';

$search = "Select option_value from $table_name Where option_name = 'embacy_letter_fee' ";
$result=$wpdb->get_results($search);
}
?>
<script language="javascript">


</script>
<body>
<form id="form1" name="form1" method="post" action="" >
  <p>Embacy Letter Fee</p>
  <p><?php echo $msg;?>  </p>
  <p>
    <label for="textfield">Letter Fee</label>
    <input type="text" name="embacy_letter_fee" id="embacy_letter_fee"  value="<?php echo $result[0]->option_value;?>"/>
  </p>
  <p>
    <input type="submit" name="sub" id="button" value="Update Letter Fee" />
  </p>
</form>
</form>
