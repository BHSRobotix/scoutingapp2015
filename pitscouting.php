<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

// Get the team to do a report on
$method = $_SERVER['REQUEST_METHOD'];
$team = $ourTeamNum;
$pictureType = "robot";
if ($method == "GET") {
	$team = $_GET["tmNum"];
	$pictureType = $_GET["pictureType"];
} else if ($method == "POST") {
	$team = $_POST["tmNum"];
	$pictureType = $_POST["pictureType"];
}

$queryPitData = "SELECT * FROM ".$pitdataTable." WHERE teamnumber=" . $team . " AND eventkey=" . $db->quote($currEvent) . ";";
$resultPitData = $db->query($queryPitData);
$row = mysqli_fetch_assoc($resultPitData);

$robotPictureUrl = "";
$driverPictureUrl = "";
if (isset($row)) {
	if (!empty($row['robotPicture'])) { 
		$robotPictureUrl = $row['robotPicture'];
	}
	if (!empty($row['driverPicture'])) { 
		$driverPictureUrl = $row['driverPicture'];
	}
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Pit Scouting for Team <?= $team ?></title>
    <?php include "includes/allCss.php" ?>

<script>
$(document).ready(function (){ 
   $(".btn-group").button();
});
</script>
<style>
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}

</style>
</head>

<body>
    <?php include "includes/userHeader.php" ?>

	<div class="container">
	    <div class="page-header"><h2>Pit Scouting for Team <?= $team ?></h2></div>
        <form action="pitscoutingUpdate.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="teamnumber" value="<?= $team ?>"/>
                
            <?php if (!empty($robotPictureUrl)) { ?>
            <strong> Current Robot Photo </strong> <br/>
            <img src="<?= $robotPictureUrl ?>"/>
            <?php } else {?>
            <div class="alert alert-warning" role="alert"><strong> No Robot Photo Yet </strong></div>
            <?php } ?>
            <br/><br/>
            <?php if (!empty($driverPictureUrl)) { ?>
            <strong> Current Driver Photo </strong> <br/>
            <img src="<?= $driverPictureUrl ?>"/>
            <?php } else {?>
            <div class="alert alert-warning" role="alert"><strong> No Driver Photo Yet </strong></div>
            <?php } ?>

            <div class="page-header"><h3> Replace one of the above pictures</h3></div>
            
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-default <?php if ($pictureType == "robot") { ?> active <?php } ?>">
                    <input type="radio" class="toggle" name="pictureType" value="robot" <?php if ($pictureType == "robot") { ?> checked <?php } ?>/>
                    <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> Robot
                 </label>
                <label class="btn btn-default <?php if ($pictureType == "driver") { ?> active <?php } ?>">
                    <input type="radio" class="toggle" name="pictureType" value="driver" <?php if ($pictureType == "driver") { ?> checked <?php } ?>/>
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Driver
                </label>
            </div>
            <br/> <br/>
            <div>
                <span class="btn btn-primary btn-file">
                    <span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Get Picture <input type="file" name="picture" >
                </span>
            </div>
               
            <br><br>
            <div>
                <input class="btn btn-lg btn-primary" type="submit" value="Save to Server" name="submit">
            </div>
        </form>
    </div>
    
</body>
</html>
                            