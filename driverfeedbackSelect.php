<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

$allMatches = false;
$query = "";
if ($_GET["all"] == "true" || $_POST["all"] == "true") {
	$query = "SELECT * FROM ".$matchesTable." WHERE eventkey = '" . $currEvent . "' ORDER BY matchnumber ASC;";
	$allMatches = true;
} else {
	$query = "SELECT * FROM ".$matchesTable." WHERE eventkey = '" . $currEvent . "' AND ( "
		    . "redteam1 = '" . $ourTeamNum . "' OR redteam2 = '" . $ourTeamNum . "' OR redteam3 = '" . $ourTeamNum . "' OR "
	        . "blueteam1 = '" . $ourTeamNum . "' OR blueteam2 = '" . $ourTeamNum . "' OR blueteam3 = '" . $ourTeamNum . "' "
	        . ") ORDER BY matchnumber ASC;";
}

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Select Team for Driver Feedback</title>
    <?php include "includes/allCss.php" ?>
<style>
form {
  max-width: 200px;
  padding: 15px;
}
</style>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
        <div class="page-header"><h2>Select the team to Give Feedback On</h2></div>

        <p>
            <strong>Query by Team Number</strong>
	        <form action="driverfeedback.php">
                <input class="form-control" type="text" name="tmNum" placeholder="Team #" required >&nbsp;<input type="submit" class="btn btn-primary" value="Go"/>
	        </form>
	    </p>
	    
	    <div class="page-header">
	        <?php if ($allMatches) { ?>
	            <h2>All matches (<a href="reportsMain.php?all=false">see only Devilbotz</a>)</h2>
	        <?php } else { ?>
	            <h2>Devilbotz matches (<a href="reportsMain.php?all=true">see all</a>)</h2>
	        <?php } ?>
	    </div>

    	<?php
		$result = $db->query($query);
		$zeroRows = true;
				
		while ($row = mysqli_fetch_assoc($result)) {
			$zeroRows = false;
		?>
		<p>
			Match <?= $row["matchnumber"] ?><br/>
            <a class="btn btn-danger" href="driverfeedback.php?tmNum=<?= $row["redteam1"] ?>"><?= $row["redteam1"] ?></a>
            <a class="btn btn-danger" href="driverfeedback.php?tmNum=<?= $row["redteam2"] ?>"><?= $row["redteam2"] ?></a>
        	<a class="btn btn-danger" href="driverfeedback.php?tmNum=<?= $row["redteam3"] ?>"><?= $row["redteam3"] ?></a>
        </p>
        <p>
        	<a class="btn btn-primary" href="driverfeedback.php?tmNum=<?= $row["blueteam1"] ?>"><?= $row["blueteam1"] ?></a>
        	<a class="btn btn-primary" href="driverfeedback.php?tmNum=<?= $row["blueteam2"] ?>"><?= $row["blueteam2"] ?></a>
        	<a class="btn btn-primary" href="driverfeedback.php?tmNum=<?= $row["blueteam3"] ?>"><?= $row["blueteam3"] ?></a>
	    </p>

		<?php
        }
        if ($zeroRows) {
        ?>  
            <div class="alert alert-warning" role="alert">No matches (likely reason: no schedule released yet)</div>              
        <?php	
        }
        ?>	    
	</div>
</body>
</html>