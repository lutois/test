<?php
/* ------------------------------------------------------------------------------
	 Voorbeeld MySQL:
// ------------------------------------------------------------------------------
$sql = "select e.id, e.firstName, e.lastName, e.title, e.picture, count(r.id) reportCount " . 
		"from employee e left join employee r on r.managerId = e.id " .
		"group by e.id order by e.lastName, e.firstName";

try {
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$stmt = $dbh->query($sql);  
	$employees = $stmt->fetchAll(PDO::FETCH_OBJ);
	$dbh = null;
	echo '{"items":'. json_encode($employees) .'}'; 
} catch(PDOException $e) {
	echo '{"error":{"text":'. $e->getMessage() .'}}'; 
}
	 ------------------------------------------------------------------------------ */
// include 'config.php';

$wnr = $_GET['wnr'];

$URL = "http://www.antilopen.nl/competitie/clubadmin/ws/wedstrijdformulier.asp?n=true&r=true&ct=true&sr=true&wnr=L$wnr";
$ch			  = curl_init( $URL );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Output in string
$xml_raw  = curl_exec( $ch );
curl_close( $ch );

// $xml		  = simplexml_load_string($xml_raw);
$xml		  = $xml_raw;
$json_out = '{"wedstrijd":[{"formulier":"'.$xml.'"}]}';

echo $json_out; // OUTPUT

echo "<hr>$URL<hr>"; // OUTPUT

?>