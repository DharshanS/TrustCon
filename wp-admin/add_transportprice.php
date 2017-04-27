<?php
global $wpdb;
$table_name = $wpdb->prefix .'options';

$search = "Select option_value from $table_name Where option_name = 'transportsic_price' ";
$resultsic=$wpdb->get_results($search);

$search = "Select option_value from $table_name Where option_name = 'transportpvt_price' ";
$resultpvt=$wpdb->get_results($search);

if(isset($_POST['sub']))
{
	$transportsic_price=$_POST['transportsic_price'];	
	$transportpvt_price=$_POST['transportpvt_price'];	
	if($resultsic[0]->option_value != '')
	{
		$sql_q="UPDATE $table_name SET option_value = '$transportsic_price' WHERE option_name = 'transportsic_price' ";
		$res=$wpdb->query($sql_q);
	}else{
		$sql_q="INSERT INTO $table_name SET `option_name` = 'transportsic_price' , option_value = '$transportsic_price'";
		$res=$wpdb->query($sql_q);
	}
	
	if($resultpvt[0]->option_value != '')
	{
		$sql_q="UPDATE $table_name SET option_value = '$transportpvt_price' WHERE option_name = 'transportpvt_price' ";
		$res=$wpdb->query($sql_q);
	}else{
		$sql_q="INSERT INTO $table_name SET `option_name` = 'transportpvt_price' , option_value = '$transportpvt_price'";
		$res=$wpdb->query($sql_q);
	}

	if($res)
	$msg = '<span style="color:green; font:bold">Successfully Updated!!</span>';
	else
	$msg = '<span style="color:red; font:bold"Error !</applet>';

$search = "Select option_value from $table_name Where option_name = 'transportsic_price' ";
$resultsic=$wpdb->get_results($search);

$search = "Select option_value from $table_name Where option_name = 'transportpvt_price' ";
$resultpvt=$wpdb->get_results($search);
}
?>
<script language="javascript">


</script>
<body>
<form id="form1" name="form1" method="post" action="" >
  <p>Transport Price</p>
  <p><?php echo $msg;?>  </p>
  <p>
    <label for="textfield">SIC Price</label>
    <input type="text" name="transportsic_price" id="transportsic_price"  value="<?php echo $resultsic[0]->option_value;?>"/>
  </p>
  <p>
    <label for="textfield">PVT Price</label>
    <input type="text" name="transportpvt_price" id="transportpvt_price"  value="<?php echo $resultpvt[0]->option_value;?>"/>
  </p>
  <p>
    <input type="submit" name="sub" id="button" value="Update Transport Price" />
  </p>
</form>
</form>
