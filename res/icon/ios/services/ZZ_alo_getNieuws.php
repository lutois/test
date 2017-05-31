<?php
/* ------------------------------------------------------------------------------
	 Naam: 		alo_getNieuws.php
	 Functie: Ophalen (XML) nieuwsfeed
	 Door:		Pim Spek (Zinster Webdesign)
	 
	 Notes:
	 ======
	 XML:
	 ----
<?xml version="1.0" encoding="UTF-8"?>
	<rss version="2.0"
		xmlns:content="http://purl.org/rss/1.0/modules/content/"
		xmlns:wfw="http://wellformedweb.org/CommentAPI/"
		xmlns:dc="http://purl.org/dc/elements/1.1/"
		xmlns:atom="http://www.w3.org/2005/Atom"
		xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
		xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
		>

	<channel>
		<title>HKC ALO</title>
		<atom:link href="http://www.alo.nu/feed/" rel="self" type="application/rss+xml" />
		<link>http://www.alo.nu</link>
		<description></description>
		<lastBuildDate>Thu, 02 Apr 2015 09:28:05 +0000</lastBuildDate>
		<language>nl-NL</language>
			<sy:updatePeriod>hourly</sy:updatePeriod>
			<sy:updateFrequency>1</sy:updateFrequency>
		<generator>http://wordpress.org/?v=3.9.3</generator>
		<item>
			<title>Van de redactie</title>
			<link>http://www.alo.nu/2015/redactie/van-de-redactie-52/?utm_source=rss&#038;utm_medium=rss&#038;utm_campaign=van-de-redactie-52</link>
			<comments>http://www.alo.nu/2015/redactie/van-de-redactie-52/#comments</comments>
			<pubDate>Thu, 02 Apr 2015 08:44:40 +0000</pubDate>
			<dc:creator><![CDATA[Redactie]]></dc:creator>
			<category><![CDATA[Redactie]]></category>
			<guid isPermaLink="false">http://www.alo.nu/?p=18062</guid>
			<description><![CDATA[<p>haha</p></description>
			<content:encoded><![CDATA[<p>Een heleboel tekst</p>]></content:encoded>
			<wfw:commentRss>http://www.alo.nu/2015/redactie/van-de-redactie-52/feed/</wfw:commentRss>
			<slash:comments>0</slash:comments>
		</item>
		<item>...</item>
	</channel>
</rss>
	
	 $json_out:
	 ----------
		{"Nieuws":[
				{"title":"Titel","link":"[link]","comments":"[]","pubdata":"[]",...},
				{...}
		]}
	
	 ------------------------------------------------------------------------------ */
	 
$URL	= "http://www.alo.nu/feed/";

/*
$ch		= curl_init( $URL );
//
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Output in string
$xml_raw  = curl_exec( $ch );
curl_close( $ch );
*/
$xml_raw = file_get_contents($URL);
$xml_raw = str_replace("item","nieuwsitem",$xml_raw);
//
// echo "#001. $xml_raw<br>";
$xml	 = simplexml_load_string($xml_raw,null,LIBXML_NOCDATA);
// echo "#001a. $xml<br>";

/*
$feed_content = new DOMDocument();
if (!$feed_content->load($URL)) {
	$page .= "<p>Sorry, but we are unable to upload the latest entries.<br />$url is currently not available.</p>\n";
	echo $page;
}
else {
	echo "#001a. Let op<br>";
	$xml = $feed_content->saveXML();
	// echo $xml;
}

// $xml->dienst->creationDate = date('ymdHis00');
*/


/*
echo "#001. ";
print_r($xml->dienst);
echo "<br>#002. ";
print_r($xml);
echo "<br>";
*/

$json  = json_encode($xml);
// echo "#001b. $json<br>";
$array = json_decode($json,TRUE);
if (is_array($array)) {
	// echo "#002. ARRAY!<br>";
	echo $json;
} else {
	echo "#003.<br>";
	echo false;
}

// echo "#009.<br>";

// ------------------------------------------------------------------------------
// EOF
?>