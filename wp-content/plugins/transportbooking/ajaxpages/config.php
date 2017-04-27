<?php
$host_name = 'localhost';
$user_name = 'i1611638_wp1';
$pass_word = 'H(IXs5rqUc70.#4';
$database_name = 'i1611638_wp1';

$conn = mysql_connect($host_name, $user_name, $pass_word) or die ('Error connecting to mysql');
mysql_select_db($database_name);
?>