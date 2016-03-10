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

$queryPitDataTable = "SELECT * FROM ".$pitdataTable." WHERE teamnumber = '" . $team . "';";
$resultPitDataTable = $db->query($queryPitDataTable);

$queryPerfomancesTable = "SELECT * FROM ".$performancesTable." WHERE eventkey = '" . $currEvent . "' AND teamnumber = '" . $team . "' ORDER BY matchnumber ASC;";
$resultPerfomancesTable = $db->query($queryPerfomancesTable);

$queryDriverfeedbackTable = "SELECT * FROM ".$driverfeedbackTable." WHERE eventkey = '" . $currEvent . "' AND teamnumber = '" . $team . "' ORDER BY scout ASC;";
$resultDriverfeedbackTable = $db->query($queryDriverfeedbackTable);

$queryMatchesTable = "SELECT * FROM ".$matchesTable." WHERE eventkey = '" . $currEvent . "' AND ( "
			. "redteam1 = '" . $team . "' OR redteam2 = '" . $team . "' OR redteam3 = '" . $team . "' OR "
			. "blueteam1 = '" . $team . "' OR blueteam2 = '" . $team . "' OR blueteam3 = '" . $team . "' "
			. ") ORDER BY matchnumber ASC;";
$resultMatchesTable = $db->query($queryMatchesTable);

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Team <?= $team ?></title>
	<?php include "includes/allCss.php" ?>  
<style>
.table {
  margin-bottom: 0;
}
.page-header {
  padding-bottom: 4px;
  margin: 20px 0 20px;
}
/* .robot-disabled { */
/*   background-color:  */
/* } */
</style>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">

        <?php $row = mysqli_fetch_assoc($resultTeamsTable); ?>
        <div class="page-header">
            <table class="table table-striped">
                <tr><td rowspan="3"><h2><?= $team ?></h2></td><td><strong><?= $row['name'] ?></strong></td></tr>
                <tr><td><?= $row['location'] ?></td></tr>
                <tr><td><?= $row['url'] ?></td></tr>
            </table>
        </div>
        
	    
	    <!-- Pit data -->
	    <?php $row = mysqli_fetch_assoc($resultPitDataTable); ?>
	    <?php if (isset($row)) { ?>
	    <h6>Pictures (collected by <?= $row['scout'] ?>)</h6>
	    <?php if (isset($row['robotPicture'])) { ?><p><img src="<?= $row['robotPicture'] ?>"/></p><?php } else { ?><p class="alert alert-warning">No robot picture for this team</p><?php } ?>
	    <?php if (isset($row['driverPicture'])) { ?><p><img src="<?= $row['driverPicture'] ?>"/></p><?php } else { ?><p class="alert alert-warning">No driver picture for this team</p><?php } ?>
	    <?php } else { ?>
	    <p class="alert alert-warning">No pictures collected.  <a href="pitscouting.php?tmNum=<?= $team ?>">Collect now!</a></p>
	    <?php } ?>
	    

	    <!-- Scouted Performances data -->
	    <div class="page-header"><h4>Scouted Performances</h4></div>
	    <table class="table table-striped">
    		<thead>
	        	<tr>
	                <th scope="col"></th>
		    		<th scope="col"></th>
	                <th scope="col" colspan="7">Teleop</th>
	                <th scope="col"></th>
	                <th scope="col"></th>
	            </tr>
	        	<tr>
	                <th scope="col"></th>
		    		<th scope="col">Auto</th>
	                <th scope="col" colspan="3">Totes</th>
	                <th scope="col" colspan="4">Containers</th>
	                <th scope="col">Coop</th>
	                <th scope="col"></th>
	            </tr>
	        	<tr>
	                <th scope="col">Match #</th>
	                <th scope="col">Functions</th>
	                <th scope="col">#</th>
	                <th scope="col">Height</th>
	                <th scope="col">Source</th>
	                <th scope="col">#</th>
	                <th scope="col">Height</th>
	                <th scope="col">From</th>
	                <th scope="col">Noodled?</th>
	                <th scope="col">Help</th>
	                <th scope="col">Scout</th>
	            </tr>
	        </thead>
	        <tbody>	
	        <?php
	        $zeroPerfs = true;
	        $scoutComments = "";
	        while ($row = mysqli_fetch_assoc($resultPerfomancesTable)) {
	         	$zeroPerfs = false;
	         	if (!empty($row['comments'])) {
	         		$scoutComments .= "<tr><td>".$row['comments']."</td><td>".$row['scout']."</td></tr>";
	         	}
	         	
	        ?>
	            <tr <?php if ($row['isFunctional'] == "no") { ?> class="alert alert-warning"<?php } ?>>
	                <td><?= $row['matchnumber'] ?></td>
	                <td>
	                    <?php if ($row['autoRobotset'] == "yes") { ?>drove<?php } ?>
	                    <?php if ($row['autoToteset'] == "yes") { ?>tote<?php } ?>
	                    <?php if ($row['autoContainerset'] == "yes") { ?>container<?php } ?>
	                </td>
	                <td><?= $row['teleTotesStacked'] ?></td>
	                <td><?= $row['teleTotesHeight'] ?></td>
	                <td><?= $row['teleTotesSource'] ?></td>
	                <td><?= $row['teleContainersStacked'] ?></td>
	                <td><?= $row['teleContainersHeight'] ?></td>
	                <td><?= $row['teleContainersOrientation'] ?></td>
	                <td><?= $row['teleContainersNoodled'] ?></td>
	                <td><?= $row['coopertitionHelp'] ?></td>
	                <td><?= $row['scout'] ?></td>
	            </tr>
	         <?php 
	         }
	         if ($zeroPerfs) {
	         	echo "<tr><td colspan='10'>No performances scouted for this team.</td></tr>";
	         }
	         ?>
	        </tbody>
        </table>

	    <div class="page-header"><h4>Scouted Comments</h4></div>
	    <?php if (empty($scoutComments)) { ?>
	    <p class="alert alert-warning">No comments on this team.</p>
	    <?php } else { ?>
	    <table class="table table-striped">
    		<thead><tr><th scope="col">Comment</th><th scope="col">Scout</th></tr></thead>
	        <tbody><?= $scoutComments ?></tbody>	        
	    </table>
	    <?php } ?>

	    
	    <!-- Driver Feedback data -->
	    <div class="page-header"><h4>Drive Team Comments</h4></div>
        <?php
        $driveComments = "";
	    while ($row = mysqli_fetch_assoc($resultDriverfeedbackTable)) {
	        if (!empty($row['comments'])) {
	        	$driveComments .= "<tr><td>".$row['comments']."</td><td>".$row['scout']."</td></tr>";
	        }
	    }
	    ?>	    
	    <?php if (empty($driveComments)) { ?>
	    <p class="alert alert-warning">No comments on this team.</p>
	    <?php } else { ?>
	    <table class="table table-striped">
    		<thead><tr><th scope="col">Comment</th><th scope="col">Scout</th></tr></thead>
	        <tbody><?= $driveComments ?></tbody>	        
	    </table>
	    <?php } ?>
		

	    <!--  TODO - add match results data back in? -->
	    
	</div>
</body>
</html>