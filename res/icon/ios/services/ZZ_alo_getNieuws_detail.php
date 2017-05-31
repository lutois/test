<?php
/* ==================================================================================================
	 Naam:		alo_getNieuws_details.php
	 Functie:	Haal details gegeven nieuwsitem op
	 
	 Wijzigingen:
	 ============
	 
	 Versie	Datum		Door	Omschrijving
	 ======	======	=====	============
	 1.01		090615	PS		Nieuw
	 
	 ================================================================================================== */
	
	include 'config.php';
	include 'inc_db.php';
	
// --------------------------------------------------------------------------------------------------
	
$URL	= $_GET[l];
//
// $URL = "http://www.alo.nu/app/www/alo_nieuws.html";
$html = new DOMDocument();
// Load the url's contents into the DOM
@$html->loadHTMLFile($URL);
// $element = $html->getElementById('content');
// $element = $html->getElementByClass('entry-content');


$finder = new DomXPath($html);
$classname="entry-content";
$nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");

foreach ($nodes as $element) {
	$test = DOMinnerHTML($element);
}

// $test = htmlentities($test);
// $test = html_entity_decode($test);

// $test = str_replace("<","&lt;",$test);
// $test = str_replace(">","&gt;",$test);
// echo "<hr><br>";
// echo "#html_det Begin:<br><hr><br>$test<br><hr><br>#html_det Einde.<br><hr>";




	// echo '{"details":[{"nieuws":"'.$test.'"}]}';
	echo $test;
	
// ---------------------------------------------------------------------------------------------
// http://stackoverflow.com/questions/2087103/how-to-get-innerhtml-of-domnode
function DOMinnerHTML(DOMNode $element) { 
	//
	$innerHTML = "";
	$children  = $element->childNodes;
	//
	foreach ($children as $child) { 
		$test = $element->ownerDocument->saveHTML($child);
		// $test = str_replace("\"","'",$test);
		// $test = trim($test);
		// if (!empty($test)) {
			// $innerHTML .= json_encode($test);
			$innerHTML .= $test;
		// }
	}
	//
	return $innerHTML; 
} 
// =============================================================================================
// EOF
?>