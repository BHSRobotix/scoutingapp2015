<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

$query = "INSERT INTO ".$driverfeedbackTable." (teamnumber, eventkey, comments, scout) VALUES ("
		. $db->quote($_POST['teamnumber']) . "," 
		. $db->quote($currEvent) . "," 
		. $db->quote($_POST['comments']) . ","
		. $db->quote($_SESSION['username']) . ");";

$result = $db->query($query);

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Driver Feedback for Team <?= $_POST['teamnumber'] ?></title>
    <?php include "includes/allCss.php" ?>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
        <div class="page-header"><h2>Driver Feedback for Team <?= $_POST['teamnumber'] ?></h2></div>
        <p>
            <?php if ($result) { ?>
            	Successfully created a driver feedback entry!
            <?php } else { ?>
            	There was a problem with the database!
            <?php } ?>
	    </p>
    </div>
</body>
</html>