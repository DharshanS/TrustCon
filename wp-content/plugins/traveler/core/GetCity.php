<?php

include PLUG_DIR.'core/DBconnet.php';

$q=$_REQUEST['q'];
$db=new DBconnet();
$alink = $db->getDbConnetion();
$data=array();
$query="SELECT `iata_code`,`city`,`country` FROM `airports` WHERE `city` LIKE '".$q."%' OR `iata_code` LIKE '".$q."%'";
$result = mysqli_query($alink, $query);
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $res="".$row['iata_code'].",".$row['city'].",".$row['country']."";
    $data[]=$res;
}
echo json_encode($data);
wp_die();
?>