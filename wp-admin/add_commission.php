<?php
global $wpdb;
$table_name = $wpdb->prefix .'options';

$search = "Select option_value from $table_name Where option_name = 'agent_commission' ";
$result=$wpdb->get_results($search);

if(isset($_POST['sub']))
{
	$commission=$_POST['commission'];	
	
	
	if($result[0]->option_value != '')
	{
		$sql_q="UPDATE $table_name SET option_value = '$commission' WHERE option_name = 'agent_commission' ";
		$res=$wpdb->query($sql_q);
	}else{
		$sql_q="INSERT INTO $table_name SET `option_name` = 'agent_commission' , option_value = '$commission'";
		$res=$wpdb->query($sql_q);
	}

	if($res)
	$msg = '<span style="color:green; font:bold">Successfully Updated!!</span>';
	else
	$msg = '<span style="color:red; font:bold"Error !</applet>';

$search = "Select option_value from $table_name Where option_name = 'agent_commission' ";
$result=$wpdb->get_results($search);

}

?>
<script language="javascript">


</script>
<body>
<form id="form1" name="form1" method="post" action="" >
  <p>Agent Commission Configuration Page</p>
  <p><?php echo $msg;?>  </p>
  <p>
    <label for="textfield">Agent Commission</label>
    <input type="text" name="commission" id="commission"  value="<?php echo $result[0]->option_value;?>"/> ( % )
  </p>
  <p>
    <input type="submit" name="sub" id="button" value="Update Agent Commission" />
  </p>
</form>
</form>
