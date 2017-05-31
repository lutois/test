<?php
/* ------------------------------------------------------------------------------
	 Naam: 		alo_getBardiensten.php
	 Functie: Ophalen (XML) bardiensten voor huidige vereniging
	 Door:		Pim Spek (Zinster Webdesign)
	 
	 Notes:
	 ======
	 XML:
	 ----
		<root>
			<dienst>
				<datum>dinsdag 24 juni 2014</datum>
				<omschrijving>klaverjas</omschrijving>
				<tijd>19:45 - 23:00</tijd>
				<namen>
					<naam>Erna van der Smitte</naam>
					<naam>Dick Lansing</naam>
				</namen>
			</dienst>
			<dienst>
				...
			</dienst>
			...
		</root>
	
	 $json_out:
	 ----------
		{"kantinedienst":[
											{"datum":"dinsdag 24 juni 2014","omschrijving":"klaverjas","tijd":"19:45 - 23:00","namen":{"naam":["Dick Lansing","Erna van der Smitte"]}},
											{"datum":"dinsdag 1 juli 2014","omschrijving":"klaverjas","tijd":"19:45 - 23:00","namen":{"naam":["Paul Verdiesen","Martijn van Wensveen"]}},
											{...}
											]}
	
	 ------------------------------------------------------------------------------ */
	 
include 'config.php';

$soort = $_GET['d'];

$URL	= "http://www.antilopen.nl/competitie/diensten.asp?ci=267&d=$soort&dagen=60&inschrijven=false&h=f&xml=267";
$ch		= curl_init( $URL );
//
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Output in string
$xml_raw  = curl_exec( $ch );
curl_close( $ch );
//

// $xml	 = simplexml_load_string($xml_raw);

// $xml->dienst->creationDate = date('ymdHis00');

/*
echo "#001. ";
print_r($xml->dienst);
echo "<br>#002. ";
print_r($xml);
echo "<br>";
*/

// $json  = json_encode($xml);
$json  = json_encode($xml_raw);
$array = json_decode($json,TRUE);
if (is_array($array)) {
	echo $json;
} else {
	echo false;
}

// ------------------------------------------------------------------------------
// EOF
?>