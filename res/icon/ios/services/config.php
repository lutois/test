<?php
$dbuser = "u020388_root";
$dbpass = "fen31xek";
$dbname = "db020388_alo_db";
// $dbHost = "dbint020388";
$dbhost = "dbext020388.bytenet.nl"; 
	
$s_h_p = '?.......!Zinster!.......?';

$db = new mysqli($dbhost,$dbuser,$dbpass,$dbname);

if($db->connect_errno > 0){
	die('Connectfout: [' . $db->connect_error . ']');
}
?>