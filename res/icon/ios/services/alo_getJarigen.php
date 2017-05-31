<?php
/* ==================================================================================================
	 Naam:		alo_getJarig.php
	 Functie:	Haal alle vandaag jarigen op
	 
	 Wijzigingen:
	 ============
	 
	 Versie	Datum		Door	Omschrijving
	 ======	======	=====	============
	 1.01		220115	PS		Nieuw
	 
	 ================================================================================================== */
	
	include 'config.php';
	include 'inc_db.php';
	
// --------------------------------------------------------------------------------------------------
	
	$teller = 0;
	$query = "SELECT perscnaamv, perscnaama, persdgeb
							FROM pers
						 WHERE persdeind IS NULL
							 AND (
									 (    month(persdgeb) = month(now())
										AND dayofmonth(persdgeb) >= dayofmonth(now())
										AND dayofmonth(persdgeb) <  dayofmonth(now()) + 14 )
								OR (    month(persdgeb) = month(date_add(now(), interval 14 day))
										AND dayofmonth(persdgeb) <  dayofmonth(date_add(now(), interval 14 day))
										AND dayofmonth(persdgeb) >= dayofmonth(date_add(now(), interval 14 day)) - 14 )
										)
					ORDER BY month(pers.persdgeb), dayofmonth(pers.persdgeb)";
	$jarigen = haalObjecten("query=$query");
	if ($jarigen) foreach ($jarigen as $persoon) {
		$jarigen[$teller]["leeftijd"] = haalLeeftijd($persoon[persdgeb]);
		$teller++;
	}
	/*
	if ($nieuws) foreach ($nieuws as $item) {
		$item[newsdpubl] = formatDatum($item[newsdpubl]);
	}
	*/
	echo '{"jarigen":'. json_encode($jarigen) .'}';
	// echo json_encode($jarigen);
	//

// ---------------------------------------------------------------------------------------------
// Bepaal leeftijd
function haalLeeftijd($i_dgeb) {
	//
	$today 		= date('Y-m-d');
	$jaar_nu	= date('Y', strtotime ($today));
	$maand_nu	= date('m', strtotime ($today));
	//
	$pers_dgeb   = $i_dgeb;
	$datum_array = explode("-", $pers_dgeb);
	// if ($datum_array[1] == 12) {
		$jaar_geb		 = $datum_array[0];
		$dag				 = $datum_array[2] . "-" . $datum_array[1];
		$pers_aleef  = $jaar_nu - $jaar_geb;
		if ($maand_nu == 12 && $datum_array[1] == 1) {
			$pers_aleef++;
		}
		/*
		$jarige			 = $this->maakNaam ($row[perscnaama], $row[perscnaamv]);
		if ($dag == $dag_oud) {
			$dag_print = " ";
		} else {
			$dag_print = " <strong>" . $dag . "</strong>";
			$dag_oud	 = $dag;
		}
		$o_jarigen = $o_jarigen . "$dag_print $jarige ($pers_aleef)<br>";
		*/
	// }
	return $pers_aleef;
}

// =============================================================================================
// EOF
?>