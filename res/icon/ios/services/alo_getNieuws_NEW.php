<?php
/* ------------------------------------------------------------------------------
	 Naam: 		alo_getNieuws_NEW.php
	 Functie: Ophalen nieuws via db
	 Door:		Pim Spek (Zinster Webdesign)
	 
	 Notes:
	 ======
SELECT *  FROM `wp_posts` WHERE `post_status` = 'publish' AND `post_type` = 'post'
AND post_date > '2016-06-08'
ORDER BY post_date DESC
	 ------------------------------------------------------------------------------ */
	 
	include 'config_NEW.php';
	include 'inc_db.php';
	
// --------------------------------------------------------------------------------------------------
	
	$id	= $_GET[id];
	if (!empty($id)) {
		$query = "SELECT ID,post_title,post_date,post_content,guid
							  FROM wp_posts
							 WHERE ID = '$id'
							 LIMIT 1";
	} else {
		$query = "SELECT ID,post_title,post_date,post_content,guid
								FROM wp_posts
							 WHERE post_status = 'publish'
								 AND post_type = 'post'
								 AND post_date > '2016-06-08'
						ORDER BY post_date DESC";
	}
	$nieuws = haalObjecten("$query");
	$teller = 0; 
	if ($nieuws) foreach ($nieuws as $item) {
		// $jarigen[$teller]["leeftijd"] = haalLeeftijd($persoon[persdgeb]);
		// $item[$teller]["image"] = "haha"; // haalImage($item[ID]);
		$nieuws[$teller]["image"] = haalImage($item[ID]);
		$excerpt = strip_tags($item[post_content]);
		$excerpt = substr($excerpt,0,150);
		$nieuws[$teller]["excerpt"] = $excerpt;
		//
		$teller++;
		// print_r($item);
	}
	/*
	if ($nieuws) foreach ($nieuws as $item) {
		$item[newsdpubl] = formatDatum($item[newsdpubl]);
	}
	*/
	// print_r($nieuws);
	// echo "<hr>";
	echo '{"nieuws":'. json_encode($nieuws) .'}';
	// echo json_encode($jarigen);
	//

// ---------------------------------------------------------------------------------------------
// Bepaal image
function haalImage($i_ID) {
	//
	$o_path = "";
	$query = "SELECT meta_value FROM wp_postmeta
										 				 WHERE post_id = '$i_ID'
															 AND meta_key = 'post_image'";
	$image = haalObject($query);
	if ($image) {
		$o_path = $image[meta_value];
	}
	//
	return $o_path;
}
// ------------------------------------------------------------------------------
// EOF
?>