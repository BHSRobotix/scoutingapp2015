<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

// Handle the checkboxes that can have multiple values first
$teleTotesSource = "";
if (!empty($_POST['teleTotesSource'])) {
    foreach ($_POST['teleTotesSource'] as $totesSrc) {
    	$teleTotesSource .= $totesSrc . " ";
    }
}
$teleContainersOrientation = "";
if (!empty($_POST['teleContainersOrientation'])) {
    foreach ($_POST['teleContainersOrientation'] as $contOrient) {
	    $teleContainersOrientation .= $contOrient . " ";
    }
}

$query = "INSERT INTO ".$performancesTable." (matchnumber, eventkey, teamnumber, "
		. " isFunctional, autoRobotset, autoToteset, autoContainerset, "
		. " teleTotesStacked, teleTotesHeight, teleTotesSource, "
		. " teleContainersStacked, teleContainersHeight, teleContainersOrientation, teleContainersNoodled, "
		. " coopertitionHelp, comments, scout "
		. ") VALUES ("
		. $db->quote($_POST['matchnumber']) . "," 
		. $db->quote($currEvent) . "," 
		. $db->quote($_POST['teamnumber']) . "," 
		. $db->quote($_POST['isFunctional']) . "," 
		. $db->quote($_POST['autoRobotset']) . "," 
		. $db->quote($_POST['autoToteset']) . "," 
		. $db->quote($_POST['autoContainerset']) . "," 
		. $db->quote($_POST['teleTotesStacked']) . "," 
		. $db->quote($_POST['teleTotesHeight']) . "," 
		. $db->quote($teleTotesSource) . "," 
		. $db->quote($_POST['teleContainersStacked']) . "," 
		. $db->quote($_POST['teleContainersHeight']) . "," 
		. $db->quote($teleContainersOrientation) . "," 
		. $db->quote($_POST['teleContainersNoodled']) . "," 
		. $db->quote($_POST['coopertitionHelp']) . ","
		. $db->quote($_POST['comments']) . "," 
		. $db->quote($_SESSION['username']) . ");";

$result = $db->query($query);

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Match Scouting for Team <?= $_POST['teamnumber'] ?> and Match <?= $_POST['matchnumber'] ?></title>
    <?php include "includes/allCss.php" ?>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
        <div class="page-header"><h2>Match Scouting for Team <?= $_POST['teamnumber'] ?> and Match <?= $_POST['matchnumber'] ?></h2></div>
        <p>
            <?php if ($result) { ?>
            	Successfully created a match scouting report!
            <?php } else { ?>
            	There was a problem with the database!
            <?php } ?>
            <br/>
            <br/>
            
            <a class="btn btn-lg btn-primary" href="matchscoutingSelect.php">Scout another match</a>
            
	    </p>
    </div>
</body>
</html>
