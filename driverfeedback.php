<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

// Get the team to do a report on
$method = $_SERVER['REQUEST_METHOD'];
$team = $ourTeamNum;
if ($method == "GET") {
	$team = $_GET["tmNum"];
} else if ($method == "POST") {
	$team = $_POST["tmNum"];
}

$queryTeamsTable = "SELECT * FROM ".$teamsTable." WHERE number = '" . $team . "';";
$resultTeamsTable = $db->query($queryTeamsTable);


?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Driver Feedback for Team <?= $team ?></title>
    <?php include "includes/allCss.php" ?>
</head>

<body>
    <?php include "includes/userHeader.php" ?>
	<div class="container">
        <div class="page-header"><h2>Driver Feedback for Team <?= $team ?></h2></div>
        <form action="driverfeedbackUpdate.php" method="post">
            <input type="hidden" name="teamnumber" value="<?= $team ?>"/>
                    
            <strong>Comments</strong> <br/>
            <input class="form-control" type="text" name="comments" placeholder="Comments" required >
            <input type="submit" class="btn btn-lg btn-primary" value=" Give Feedback "/>
        </form>
    </div>
</body>
</html>
                                
                            
                            