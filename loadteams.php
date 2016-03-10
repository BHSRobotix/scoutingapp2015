<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

if ($_SESSION['role'] != "admin") {
    header("Location: index.php");
} else {
	$msg = "";
	if ($teamsLoaded) {
		$msg .= "The teamsLoaded flag is set - cannot continue.";
	} else {
    	$url = "http://www.thebluealliance.com/api/v2/event/".$currEvent."/teams?X-TBA-App-Id=frc2876:scouting-system:v01";
	
	    $json = file_get_contents($url);
	    $teams = json_decode($json, true);
	
	    if ($_GET["truncate"] == "true") {
    	    $db -> query("TRUNCATE TABLE ".$teamsTable.";"); //wipe everything
	    	$msg .= $teamsTable . " TABLE truncated!<br/>";
	    }
	
	    foreach($teams as &$team) {
		    $query = "INSERT INTO ".$teamsTable." (eventkey, number, name, location, url) VALUES (".$db->quote($currEvent).",".$db->quote($team['team_number']).",".$db->quote($team['nickname']).",".$db->quote($team['location']).",".$db->quote($team['website']).");";
		    $msg .=  $query.'<br/><br/>';	
		    $result = $db -> query($query);
	    }
		
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Functions: Load Teams from TBA</title>
  <?php include "includes/allCss.php" ?>
</head>
<body role="document">

  <!-- Fixed navbar -->
  <?php include "includes/userHeader.php" ?>
    
  <!-- Primary Page Layout --> 
  <div class="container" role="main">
      <div class="alert alert-warning" role="alert"><strong> <?= $msg ?> </strong></div>
  </div>

</body>
</html>
