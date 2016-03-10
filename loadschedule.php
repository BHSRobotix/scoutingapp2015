<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";
if ($_SESSION['role'] != "admin") {
    header("Location: index.php");
} else {
	header("Access-Control-Allow-Origin: https://private-09461-frcevents.apiary-proxy.com");
	require_once("Db.php");
		
// 	$url = "http://www.thebluealliance.com/api/v2/event/".$currEvent."/matches?X-TBA-App-Id=frc2876:scouting-system:v01";
// 	$url = "https://frc-api.usfirst.org/api/v1.0/2015";
	$url = "https://private-09461-frcevents.apiary-proxy.com/api/v1.0/2015";
	
	$httpsopts = array( 'http'=>array( 'method'=>"GET", 'header'=>"Authorization: Basic cndvZG9ubmVsbDoxMDQ0NTNGRi01NkQ3LTQ1NzAtODQ5Ni05NzNGNDI1OTc1NDA=\r\n" ) );
	$context = stream_context_create($httpsopts);
	
	$json = file_get_contents($url, false, $context);	
	$matches = json_decode($json, true);

	echo "<br/>";
	
	var_dump($json);
	
	echo "<br/>";
	
	var_dump($matches);
	
}
?>
<html>
<head>

<script>

var xhr = new XMLHttpRequest();
xhr.open('GET', 'https://private-09461-frcevents.apiary-proxy.com/api/v1.0/2015');
xhr.setRequestHeader("Accept", "application/json");
xhr.setRequestHeader("Authorization", "Basic cndvZG9ubmVsbDoxMDQ0NTNGRi01NkQ3LTQ1NzAtODQ5Ni05NzNGNDI1OTc1NDA=");
xhr.onreadystatechange = function () {
  if (this.readyState == 4) {
      alert('Status: '+this.status+'\nHeaders: '+JSON.stringify(this.getAllResponseHeaders())+'\nBody: '+this.responseText);
  }
};
xhr.send(null);

</script>

</head>

</html>