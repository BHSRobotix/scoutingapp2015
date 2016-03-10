<?php 
//This file outputs a JSON object of all the stores based on the lat, long, and radius of stores
// provided through the URL parameters
//Usage (ignore parantheses): yoursite.com/storelocator/bin/jsonapi.php?lat=(LATITUDE)&lng=(LONGITUDE)&radius=(RADIUS) 
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
	//Set the header to JSON, so it can be parsed as such.
	header("Content-type: application/json");
	
	$output = array();
	
	// Iterate through the rows, adding an associative array to the output array for each row
	while ($row = @mysql_fetch_assoc($result)){	
			$store = array('name'=> $row['name'], 'address'=> $row['address'], 'lat'=> $row['lat'], 'lng' => $row['lng'], 'phone'=> $row['phone'], 'url'=> $row['url'], 'distance'=> $row['distance']);
		
  			array_push($output,$store);
	}
	
	echo json_encode($output);
		 
} else {
	die("Mysql query failed, error: ".mysql_error());
}

mysql_close($connection);
?>
