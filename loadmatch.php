<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";
if ($_SESSION['role'] != "admin") {
    header("Location: index.php");
} else {
	
	$msg = "";
	if ($matchesLoaded) {
		$msg .= "The matchesLoaded flag is set - cannot continue.";
	} else {
	
    	$url = "http://www.thebluealliance.com/api/v2/event/".$currEvent."/matches?X-TBA-App-Id=frc2876:scouting-system:v01";
	
	    $json = file_get_contents($url);	
	    $matches = json_decode($json, true);
	
	    if ($_GET["truncate"] == "true") {
    	    $db -> query("TRUNCATE TABLE ".$matchesTable.";"); //wipe everything
    	    $msg .= $matchesTable . " TABLE truncated!<br/>";
	    }
	
	    foreach($matches as &$match) {
	
		    $blueteam = $match['alliances']['blue'];
		    $redteam = $match['alliances']['red'];
	
		    //substr($str, 3) removes the frc part
	        // Only insert the QUALIFYING MATCHES!
		    if ($match['comp_level'] == "qm") {
			    $query = "INSERT INTO ".$matchesTable." (matchnumber, eventkey, redteam1, redteam2, redteam3, blueteam1, blueteam2, blueteam3) VALUES ("
			    		.$db->quote($match['match_number']).","
			    		.$db->quote($match['event_key']).","
			    		.$db->quote(substr($redteam['teams'][0],3)).","
			    		.$db->quote(substr($redteam['teams'][1],3)).","
			    		.$db->quote(substr($redteam['teams'][2],3)).","
			    		.$db->quote(substr($blueteam['teams'][0],3)).","
			    		.$db->quote(substr($blueteam['teams'][1],3)).","
			    		.$db->quote(substr($blueteam['teams'][2],3)).");";
			    $msg .=  $query.'<br/><br/>';
			    $result = $db -> query($query);
		    }
	    }

	    $msg .= "Successfully reloaded match data for url:".$url."<br/>";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Functions: Load Matches From TBA</title>
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
