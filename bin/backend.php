<?php
/**
 * Executes CRUD functions for the admin panel via AJAX for the respective javascript files.
 * (Add, Edit, Duplicate check)
 */
require_once "settings.php";

//Fetch all the URL (GET) parameters, and store them in variables
$id = $_GET["id"];
$name = $_GET["name"];
$address = $_GET["address"]; 
$phone = $_GET["phone"];
$address = $_GET["address"]; 
$url = $_GET["url"];
$lat = $_GET["lat"];
$lng = $_GET["lng"];
$action = $_GET["action"]; 

//Connect to the database
$connection =mysql_connect("$host", "$username", "$password")or die("Cannot connect to the mysql server.");
mysql_select_db("$db_name")or die("Cannot select DB.");

/**
 * Check if the given address/name is found in the DB; if it matches a lowercase, no comma string then it prints out "OK"
 */
function duplicateCheck($address, $name)
{
	$address = strtolower(str_replace(',', '', $address));
	$name = strtolower($name);
	$dquery = sprintf("SELECT * FROM stores WHERE LOWER(REPLACE(address, ',', ''))='%s' AND LOWER(name)='%s';", 
			  		  mysql_real_escape_string($address),
			  		  mysql_real_escape_string($name)
			  );
			
	$dup_check = mysql_query($dquery);
	if (mysql_num_rows($dup_check)>0)
		echo "EXISTS";
	else
		echo "OK";
}
 
/**
 * Update the store with the information given
 */
function editStore($id, $name, $address, $phone, $lat, $lng, $url)
{
	$query = sprintf("UPDATE stores SET name='%s', address='%s', phone='%s', lat='%s', lng='%s', url='%s' WHERE id=%d;",
						mysql_real_escape_string($name),
						mysql_real_escape_string($address),
						mysql_real_escape_string($phone),
						mysql_real_escape_string($lat),
						mysql_real_escape_string($lng),
						mysql_real_escape_string($url),
					  	mysql_real_escape_string($id)
				    );
	$result = mysql_query($query);	
	echo "OK";
}

/**
 * Add a new store, with the provided variables. Prints "OK" if it succeeded. (probably should)
 */
function addStore($name, $address, $phone, $lat, $lng, $url)
{
	$query = sprintf("INSERT INTO stores (name, address, phone, lat, lng, url) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", 
						mysql_real_escape_string($name), 
						mysql_real_escape_string($address), 
						mysql_real_escape_string($phone),
						mysql_real_escape_string($lat),
						mysql_real_escape_string($lng),
						mysql_real_escape_string($url)
					); 
 	
   	$insert_result = mysql_query($query);
	if ($insert_result)
		echo "OK";
}

/**
 * Based on the provided action, execute the correct function
 */
if($action=="add")
{
	addStore($name, $address, $phone, $lat, $lng, $url);
}

if ($action=="edit")
{
	editStore($id, $name, $address, $phone, $lat, $lng, $url);
}

if($action=="dup_check")
{ 
	duplicateCheck($address, $name);
}

mysql_close($connection);
?>