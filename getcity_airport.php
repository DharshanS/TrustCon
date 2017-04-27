<?php
//require_once("wp-config.php");
$q=$_REQUEST['q'];
//$alink = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
//$alink = mysqli_connect('localhost', 'penmypap_pmpuser', '8@agNdI(nTZ5', 'penmypap_pmpdb');
$alink = mysqli_connect('localhost', 'root', '', 'v1');
$data=array();
$query="SELECT `iata_code`,`city`,`country` FROM `airports` WHERE `city` LIKE '".$q."%' OR `iata_code` LIKE '".$q."%'";
$result = mysqli_query($alink, $query);
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
 $res="".$row['iata_code'].",".$row['city'].",".$row['country'].""; 
 $data[]=$res;
}
echo json_encode($data);
?>