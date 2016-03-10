<?php 
//This file outputs an XML that is used by list.php to populate the map with stores from the database,
//based on the lat, long, and radius of stores provided by the user on list.php
require_once "settings.php";

//Connect to the database 
$connection =mysql_connect("$host", "$username", "$password")or die("Cannot connect to the mysql server.");
mysql_select_db("$db_name")or die("Cannot select DB.");

//get URL parameters and load them.
$center_lat = $_GET["lat"];
$center_lng = $_GET["lng"];
$radius = $_GET["radius"];
// Initialize XML file, create parent node
$dom = new DOMDocument("1.0");
$node = $dom->createElement("stores");
$parnode = $dom->appendChild($node);

// Search the rows in the stores table, utilizing the Haversine Formula. 
$query = sprintf("SELECT address, name, phone, lat, lng, url, ( %s * acos( cos( radians('%s') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('%s') ) + sin( radians('%s') ) * sin( radians( lat ) ) ) ) AS distance FROM stores HAVING distance < '%s' ORDER BY distance LIMIT 0 , 50",
  $hav_int,
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($center_lng),
  mysql_real_escape_string($center_lat),
  mysql_real_escape_string($radius));

$result = mysql_query($query);

if ($result) {
	//Set the header to XML, so it can be parsed as such.
	header("Content-type: text/xml");
	// Iterate through the rows, adding XML nodes for each store returned as result
	while ($row = @mysql_fetch_assoc($result)){	
	  $node = $dom->createElement("store");
	  $newnode = $parnode->appendChild($node);
	  $newnode->setAttribute("name", $row['name']);
	  $newnode->setAttribute("address", $row['address']);
	  $newnode->setAttribute("phone", $row['phone']);
	  $newnode->setAttribute("lat", $row['lat']);
	  $newnode->setAttribute("lng", $row['lng']);
	  $newnode->setAttribute("url", $row['url']);
	  $newnode->setAttribute("distance", $row['distance']);
	}

	//Finally save and print out the XML we generated. It is now ready for parsing and output!
	echo $dom->saveXML();
} else {
	die("Mysql query failed, error: ".mysql_error());
}

mysql_close($connection);
?>
