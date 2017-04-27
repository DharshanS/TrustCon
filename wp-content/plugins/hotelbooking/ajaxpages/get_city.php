<?php
require_once "config.php";
$q = strtolower($_GET["q"]);
if (!$q) return;

$sql = "select * FROM city where city LIKE '%$q%'";
$rsd = mysql_query($sql);
while($rs = mysql_fetch_array($rsd)) {
	$city = $rs['city'];
	$country = $rs['country'];
	echo "$city,$country\n";
}
?>