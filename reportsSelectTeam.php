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
	<title>Reports - Select Team</title>
    <?php include "includes/allCss.php" ?>
<style>
.input-group {
  max-width: 330px;
}
</style>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
        <div class="page-header"><h2>Reports - Select a Team</h2></div>
        <p>
	        <h4>Query by Team Number</h4>
            <form action="reportsSingleTeam.php">
                <div class="input-group">
                    <input type="text" class="form-control" name="tmNum" placeholder="Team #">
                    <span class="input-group-btn"><input type="submit" class="btn btn-primary" value="Go"/></span>
                </div>
   	        </form>
	    </p>
	    <br/>
        <p>       
	        <?php if ($allMatches) { ?>
	            <h4>Query by Match - All matches (<a href="reportsSelectTeam.php?all=false">see only Devilbotz</a>)</h4>
	        <?php } else { ?>
	            <h4>Query by Match - Devilbotz matches (<a href="reportsSelectTeam.php?all=true">see all</a>)</h4>
	        <?php }
			
			$result = $db->query($query);
			$zeroRows = true;
				
			while ($row = mysqli_fetch_assoc($result)) {
				$zeroRows = false;
			?>
			    <p>Match <?= $row["matchnumber"] ?></p>
			    <p>
    	            <a class="btn btn-danger" href="reportsSingleTeam.php?tmNum=<?= $row["redteam1"] ?>"><?= $row["redteam1"] ?></a>
		            <a class="btn btn-danger" href="reportsSingleTeam.php?tmNum=<?= $row["redteam2"] ?>"><?= $row["redteam2"] ?></a>
		        	<a class="btn btn-danger" href="reportsSingleTeam.php?tmNum=<?= $row["redteam3"] ?>"><?= $row["redteam3"] ?></a>
		        </p>
		        <p>
		        	<a class="btn btn-primary" href="reportsSingleTeam.php?tmNum=<?= $row["blueteam1"] ?>"><?= $row["blueteam1"] ?></a>
		        	<a class="btn btn-primary" href="reportsSingleTeam.php?tmNum=<?= $row["blueteam2"] ?>"><?= $row["blueteam2"] ?></a>
		        	<a class="btn btn-primary" href="reportsSingleTeam.php?tmNum=<?= $row["blueteam3"] ?>"><?= $row["blueteam3"] ?></a>
			    </p>					
			<?php
            }
            if ($zeroRows) {
            ?>
                <p class="alert alert-warning" role="alert">No matches in schedule (likely reason: no schedule released yet)</p>
            <?php	
            }
            ?>
	    </p>
	</div>
</body>
</html>