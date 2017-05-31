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

$ploeg		 = $_GET['ploeg'];

$URL			 = "http://www.antilopen.nl/competitie/programma.asp?h=f&ci=267&w=1&voornamen=true&xml=267";
$opt_ploeg = "&t=$ploeg&";

if (!empty($ploeg)) {
	$URL .= $opt_ploeg;
}
$ch			  = curl_init( $URL );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Output in string
$xml_raw  = curl_exec( $ch );
curl_close( $ch );

/* ------------------------------------------------------------------------------
	 XML:
// ------------------------------------------------------------------------------
<root>
	<row>
		<Klassenaam>Toern</Klassenaam>
		...
		<Datum_Tijd>21-6-2014 12:00:00</Datum_Tijd>
		<Thuisploeg>Futura A1</Thuisploeg>
		<Uitploeg>ALO A1</Uitploeg>
		...
	
	</row>
	<row>
		...
	</row>
</root>
	 ------------------------------------------------------------------------------ */

$xml		  = simplexml_load_string($xml_raw);
$json		  = json_encode($xml);
$array	  = json_decode($json,TRUE);
$json_out = '';
if (is_array($array)) {
	foreach ($array as $key => $value) {
		// echo "array1: key=$key<br>";
		foreach ($value as $key2 => $value2) {
			// echo "array2: key=$key2<br>";
			$json_out .= '{';
			foreach ($value2 as $key3 => $value3) {
				// echo "array3: $key3 => $value3<br>";
				if (!is_array($value3)) {
					$json_out .= '"'.$key3.'":"'.$value3.'",';
				}
			}
			$json_out .= '},';
		}
	}
	
	$json_out = '{"wedstrijden":['. $json_out .']}';
	$json_out = str_replace(",]","]",$json_out);
	$json_out = str_replace(",}","}",$json_out);
	
	echo $json_out; // OUTPUT
} else {
	echo false;
}

/* ------------------------------------------------------------------------------
	 $json_out:
// ------------------------------------------------------------------------------
	 {"wedstrijden":[{"Klassenaam":"Toern","Klassetitel":"Toernooi Wedstrijden","Wedstrijdnummer":"T39478","Datum_Tijd":"21-6-2014 12:00:00","Thuisploeg":"Futura A1","Uitploeg":"ALO A1","LocatieAccommodatie":"Stokroosveld","LocatiePlaats":"Den Haag","LocatieAdres":"Zonnebloemstraat 159c","AanwezigVertrek":"11:15"},{...}]}
	 ------------------------------------------------------------------------------ */
?>