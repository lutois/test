<?php
/* ------------------------------------------------------------------------------
	 Naam: 		alo_getScheidsrechters.php
	 Functie: Ophalen (XML) Scheidsrechters voor huidige vereniging
	 Door:		Pim Spek (Zinster Webdesign)
	 
	 Notes:
	 ======
	 XML:
	 ----
		<root>
			<script id="tinyhippos-injected"/>
				<scheidsrechters>
					<datum>24 januari 2015</datum>
					<tijd>10:00</tijd>
					<team>ALO E3</team>
					<scheidsrechter>Michiel Lansing</scheidsrechter>
				</scheidsrechters>
			<scheidsrechters>
				...
			</scheidsrechters>
			...
		</root>
	
	 $json_out:
	 ----------
		{"scheidsrechters":[{"datum":"24 januari 2015","tijd":"10:00","team":"ALO E3","scheidsrechter":"Michiel Lansing"},{"datum":"24 januari 2015","tijd":"11:00","team":"ALO D2","scheidsrechter":"Michiel Lansing"},{"datum":"24 januari 2015","tijd":"12:00","team":"ALO C1","scheidsrechter":"Arjan de Vries"}]}
	
	 ------------------------------------------------------------------------------ */
	 
include 'config.php';

$soort = $_GET['d'];

$URL	= "http://www.antilopen.nl/competitie/scheidsrechters.asp?ci=267&xml=267";
$ch		= curl_init( $URL );
//
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Output in string
$xml_raw  = curl_exec( $ch );
curl_close( $ch );
//
$xml	 = simplexml_load_string($xml_raw);
$json  = json_encode($xml);
$array = json_decode($json,TRUE);
if (is_array($array)) {
	echo $json;
} else {
	echo false;
}

// ------------------------------------------------------------------------------
// EOF
?>