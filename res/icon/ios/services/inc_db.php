<?php
/* ==================================================================================================
	 Naam:		inc_db.php
	 Functie:	database-functies
	 
	 Wijzigingen:
	 ============
	 Versie	Datum		Door	Omschrijving
	 ======	======	=====	============
	 1.01		220115	PS		Nieuw
	 
	 ================================================================================================== */
	 
// ------------------------------------------------------------------------------------------------
// haal een Object uit de database
function haalObject($i_object_naam,$i_sequence=0,$i_foreign="",$i_veld_naam="",$i_order="") {
	
	// echo "<!-- #haalObject. #T101. ($i_object_naam,$i_sequence,$i_foreign,$i_veld_naam,$i_order) -->\n";
	
	global $db,$g_taalseq;
	
	if (substr(strtolower($i_object_naam),0,6) == "select") {
		$query = $i_object_naam;
	} else if (substr($i_object_naam,0,6) == "query=") {
		$query = substr($i_object_naam,6);
	} else {
		// --------------------------------------	
		// Bepaal tabelnaam gebruikt in veldnamen
		if (substr($i_object_naam,0,1) == "_") {
			$l_tabe_naam = substr($i_object_naam,1);
		} else {
			$l_tabe_naam = $i_object_naam;
		}
		// Veld of alles
		if (empty($i_veld_naam)) {
			$fields = "*";
		} else {
			$fields = "$i_veld_naam";
		}
		// Custom-where-phrase?
		$order = ""; // Initieel
		if (strtolower(substr($i_sequence,0,5)) == "where") {
			$where = "WHERE ".substr($i_sequence,5);
		} else {
			// Foreign key?
			if (empty($i_foreign)) {
				if ($i_sequence > 0) { // Als seq is nul: Zoeken zonder where-clause
					$where = "WHERE $i_object_naam.".$l_tabe_naam."seq = '$i_sequence'";
				}
			} else {
				$where = "WHERE $i_foreign = '$i_sequence'";
			}
			//
			if (!empty($i_order)) {
				$order = " ORDER BY $i_order ";
			}
			//
		}
		//
		$query = "SELECT $fields
								FROM $i_object_naam
							 $where
							 $order
							 LIMIT 1";
		// --------------------------------------	
	} // else (if (substr($i_object_naam,0,6) == "query="
												
	// echo "<!-- #haalObject. #T102. $query -->\n";
	if(!$result = $db->query($query)){
		die("haalObject. #1001. display_db_query:" . $db->error."<br>$query<br>");
	}
	// echo "#haalObject. #T101. $query<br>#: ".$result->num_rows."<br>";
	if ($result && $result->num_rows > 0) {
		$o_object = $result->fetch_assoc();
	} else {
		return FALSE;
	}
	
	$result->free();
	
	/* FF ERUIT!!!!! Begin
	// ----- Talen: Begin P!M #taal2 20/01/14 ----------------------------------------------
	// Als gebruik gemaakt wordt van talen: Haal voor ieder TEXT-veld de juiste vertaling op
	if (is_array($o_object) && $i_object_naam <> "text" && substr($i_object_naam,0,1) <> "_" && haalTalen()) {
		// echo "<!-- #haalObject $i_object_naam: Array: ".is_array($o_object)." -->\n";
		foreach ($o_object as $l_taal_veld => $l_taal_waarde) {
			// Haal op indien vertaald
			// echo "<!-- #haalObject: haalText($g_taalseq,$i_object_naam,$l_taal_veld,".$o_object[$l_tabe_naam."seq"]." ($l_tabe_naam),$l_taal_waarde) -->\n";
			$taalctext = haalText($g_taalseq,$i_object_naam,$l_taal_veld,$o_object[$l_tabe_naam."seq"],$l_taal_waarde);
			if ($taalctext <> $l_taal_waarde) {
				$o_object[$l_taal_veld] = $taalctext;
			}
		}
	}
	// ----- Talen: Einde P!M #taal2 20/01/14 ----------------------------------------------
	FF ERUIT!!!!! Einde */
	
	//
	if (!empty($i_veld_naam)) {
		return $o_object[$i_veld_naam];
	} else {
		return $o_object;
	}
} // function haalObject

// ------------------------------------------------------------------------------------------------
// haalObjecten
function haalObjecten($i_tabel,$i_where="",$i_order="",$i_extra="") {
	
	global $db,$g_taalseq;
	
	$o_objecten = ""; // Initieel
	$l_tell			= 0;
	//
	if (substr(strtolower($i_tabel),0,6) == "select") {
		$query = $i_tabel;
	} else if (substr($i_tabel,0,6) == "query=") {
		$query = substr($i_tabel,6);
	} else {
		if (empty($i_where)) {
			$where = "";
		} else {
			$i_where = str_replace("where ","",$i_where);
			$i_where = str_replace("WHERE ","",$i_where);
			$where = " WHERE $i_where ";
		}
		//
		if (empty($i_order)) {
			$order = "";
		} else {
			$order = " ORDER BY $i_order ";
		}
		//
		$query = "SELECT *
								FROM $i_tabel
								$where
								$order";
	}
	//
	if(!$result = $db->query($query)){
		die("haalObjecten. #1001. display_db_query:" . $db->error."<br>$query<br>");
	}
	if ($i_extra == "TEST") {
		echo "<p>#haalObjecten. $query<br>#".$result->num_rows."</p>";
	}
	while($objecten = $result->fetch_assoc()) {
		// Vullen variabelen
		
		
		/* FF ERUIT!!!!! Begin
		// ----- Talen: Begin P!M #taal2 20/01/14 ----------------------------------------------
		// Als gebruik gemaakt wordt van talen: Haal voor ieder TEXT-veld de juiste vertaling op
		if (is_array($objecten) && $i_tabel <> "text" && substr($i_tabel,0,1) <> "_" && haalTalen()) {
			// echo "<!-- #haalObject $i_object_naam: Array: ".is_array($o_object)." -->\n";
			foreach ($objecten as $l_taal_veld => $l_taal_waarde) {
				$l_seq = $objecten[$i_tabel."seq"];
				if (!is_numeric($l_taal_veld) && !is_numeric($l_taal_waarde) && $l_taal_veld <> $i_tabel."seq") {
					// Haal op indien vertaald
					// echo "<!-- #haalObjecten: haalText(g_taalseq=$g_taalseq,i_tabel=$i_tabel,l_taal_veld=$l_taal_veld,l_seq=$l_seq,l_taal_waarde=$l_taal_waarde) -->\n";
					$taalctext = haalText($g_taalseq,$i_tabel,$l_taal_veld,$l_seq,$l_taal_waarde);
					// echo "<!-- #haalObjecten: #haalText. taalctext: $l_taal_waarde/$taalctext -->\n";
					if ($taalctext <> $l_taal_waarde) {
						$objecten[$l_taal_veld] = $taalctext;
					}
				} else {
					// echo "<!-- #haalObjecten: #!num. ".$objecten[appaseq].". veld: $l_taal_veld/waarde: $l_taal_waarde -->\n";
				}
			}
		}
		// ----- Talen: Einde P!M #taal2 20/01/14 ------------------------------------------------
		FF ERUIT!!!!! Einde */
		
		$o_objecten[$l_tell] = $objecten;
		$l_tell++;
	} // while($objecten = $result->fetch_assoc())
	//
	$result->free();
	//
	return $o_objecten;
	
} // function haalObjecten

// ------------------------------------------------------------------------------------------------
?>