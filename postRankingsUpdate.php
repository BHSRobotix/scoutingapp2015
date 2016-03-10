<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";
if ($_SESSION['role'] != "admin") {
    header("Location: index.php");
} else {
	
	$msg = "";
    $json = $_POST["jsonRankings"];
    if (empty($json)) {
    	$msg = "No rankings posted, nothing done.";
    } else {

    	$rankings = json_decode($json, true);
	
   	    $db -> query("TRUNCATE TABLE ".$scrapedrankingsTable.";"); //wipe everything
    	$msg .= $matchesTable . " TABLE truncated!<br/>";
	
	    foreach($rankings as &$rk) {
		    $query = "INSERT INTO ".$scrapedrankingsTable." (eventkey, rank, teamNumber, qualAvg, coopPts, autoPts, contPts, totePts, littPts, dq, matchesPlayed) VALUES ("
		    		.$db->quote($currEvent).","
		    		.$db->quote($rk['rank']).","
		    		.$db->quote($rk['teamNumber']).","
		    		.$db->quote($rk['qualAvg']).","
		    		.$db->quote($rk['coopPts']).","
		    		.$db->quote($rk['autoPts']).","
		    		.$db->quote($rk['contPts']).","
		    		.$db->quote($rk['totePts']).","
		    		.$db->quote($rk['littPts']).","
		    		.$db->quote($rk['dq']).","
		    		.$db->quote($rk['matchesPlayed']).");";
		    $msg .=  $query.'<br/><br/>';
		    $result = $db -> query($query);
	    }

	    $msg .= "Successfully reloaded match data for url:".$url."<br/>";
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Functions: Load Rankings From Scraped Rankings Page</title>
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