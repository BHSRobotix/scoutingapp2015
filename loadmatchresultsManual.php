<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

if ($_SESSION['role'] != "admin") {
	header("Location: index.php");
} else {
	$infoMessages = "";
	$showForm = "";
	$matchnumber = "";
	if (($_SERVER['REQUEST_METHOD'] == "GET") || ($_POST['MATCHNUMBERONLY'] == "true")) {
		$matchnumber = ($_SERVER['REQUEST_METHOD'] == "GET" ? $_GET['matchnumber'] : $_POST['matchnumber']);
		// DO WE ALREADY HAVE THE DATA?  Check that first
		if (empty($matchnumber)) {
			$infoMessages .= "No match number given.  Put in a match below. <br/>";
			$showForm = "matchnumberOnly";
		} else {
			$queryMatchResults = "SELECT * FROM ".$matchresultsTablesTable." WHERE matchnumber=".$matchnumber." AND  eventkey=" . $db->quote($currEvent) . ";";
			$resultMatchResults = $db->query($queryMatchResults);
			if ($resultMatchResults->num_rows == 1) {
				$matchResultsData = mysqli_fetch_assoc($resultMatchResults);
				$infoMessages .= "<strong>The match number ".$matchnumber." already has match results data.</strong>  It is shown below in the form!<br/>";
			    $showForm = "matchresultsWithData";
			} else {
				// Assume coming from a link from another page
				$queryMatch = "SELECT * FROM ".$matchesTable." WHERE matchnumber=".$matchnumber." AND  eventkey=" . $db->quote($currEvent) . ";";
				$resultMatch = $db->query($queryMatch);
				if ($resultMatch->num_rows == 1) {
					$matchData = mysqli_fetch_assoc($resultMatch);
					$showForm = "matchresultsNoData";
				} else {
					$infoMessages .= "The match number ".$matchnumber." is not yet on the match schedule (or is on more than once.)<br/>";
					$showForm = "matchnumberOnly";
				}
			}
		} // we have a match number
	} else if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// Assuming it is coming from this page
		$queryTest = "SELECT * FROM ".$matchresultsTable." WHERE matchnumber=".$_POST['matchnumber']." AND  eventkey=" . $db->quote($currEvent) . ";";
		$resultTest = $db->query($queryTest);
		if ($resultTest->num_rows == 0) {
			// Insert
			$queryInsert = "INSERT INTO ".$matchresultsTable." (matchnumber, eventkey, redteam1, redteam2, redteam3, blueteam1, blueteam2, blueteam3, "
					."redscoreAuto, redscoreTote, redscoreContainer, redscoreLitter, redscoreFoul, redscoreTotal, "
					."bluescoreAuto, bluescoreTote, bluescoreContainer, bluescoreLitter, bluescoreFoul, bluescoreTotal, "
					."coopscore) VALUES ("
					.$db->quote($_POST['matchnumber']).","
					.$db->quote($currEvent).","
					.$db->quote($_POST['redteam1']).","
					.$db->quote($_POST['redteam2']).","
					.$db->quote($_POST['redteam3']).","
					.$db->quote($_POST['blueteam1']).","
					.$db->quote($_POST['blueteam2']).","
					.$db->quote($_POST['blueteam3']).","
					.$db->quote($_POST['redscoreAuto']).","
					.$db->quote($_POST['redscoreTote']).","
					.$db->quote($_POST['redscoreContainer']).","
					.$db->quote($_POST['redscoreLitter']).","
					.$db->quote($_POST['redscoreFoul']).","
					.$db->quote($_POST['redscoreTotal']).","
					.$db->quote($_POST['bluescoreAuto']).","
					.$db->quote($_POST['bluescoreTote']).","
					.$db->quote($_POST['bluescoreContainer']).","
					.$db->quote($_POST['bluescoreLitter']).","
					.$db->quote($_POST['bluescoreFoul']).","
					.$db->quote($_POST['bluescoreTotal']).","
					.$db->quote($_POST['coopscore']).");";
			$resultInsert = $db -> query($queryInsert);
			$infoMessages .= "Successfully <strong>inserted</strong> a new match result for match number ".$_POST['matchnumber'].".<br/>";
		} else if ($resultTest->num_rows == 1) {
			// Update
			$queryUpdate = "UPDATE ".$matchresultsTable." SET "
					." redteam1=".$db->quote($_POST['redteam1']).","
					." redteam2=".$db->quote($_POST['redteam2']).","
					." redteam3=".$db->quote($_POST['redteam3']).","
					." blueteam1=".$db->quote($_POST['blueteam1']).","
					." blueteam2=".$db->quote($_POST['blueteam2']).","
					." blueteam3=".$db->quote($_POST['blueteam3']).","
					." redscoreAuto=".$db->quote($_POST['redscoreAuto']).","
					." redscoreTote=".$db->quote($_POST['redscoreTote']).","
					." redscoreContainer=".$db->quote($_POST['redscoreContainer']).","
					." redscoreLitter=".$db->quote($_POST['redscoreLitter']).","
					." redscoreFoul=".$db->quote($_POST['redscoreFoul']).","
					." redscoreTotal=".$db->quote($_POST['redscoreTotal']).","
					." bluescoreAuto=".$db->quote($_POST['bluescoreAuto']).","
					." bluescoreTote=".$db->quote($_POST['bluescoreTote']).","
					." bluescoreContainer=".$db->quote($_POST['bluescoreContainer']).","
					." bluescoreLitter=".$db->quote($_POST['bluescoreLitter']).","
					." bluescoreFoul=".$db->quote($_POST['bluescoreFoul']).","
					." bluescoreTotal=".$db->quote($_POST['bluescoreTotal']).","
					." coopscore=".$db->quote($_POST['coopscore']).","
					." WHERE matchnumber=" . $_POST['matchnumber'] . " AND eventkey=" . $db->quote($currEvent) . ";";
			$resultUpdate = $db -> query($queryUpdate);
			$infoMessages .= "Successfully <strong>updated</strong> a match result for match number ".$_POST['matchnumber'].".<br/>";
		} else {
			// Houston we have a problem
			$infoMessages .= "<strong>ERROR:</strong> There were multiple rows with matchnumber " . $_POST['matchnumber'] . " and event " . $currEvent . ".";
		}
		// No matter what in this big else, we are only going to want a new match number
		$showForm = "matchnumberOnly";
	}
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Match Scouting</title>
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
	    <?php if (!empty($infoMessages)) { ?><div class="alert alert-danger" role="alert"><?= $infoMessages ?></div><?php } ?>
    
        <div class="page-header"><h2>Current Match Results</h2></div>

	        <table class="table table-striped">
	   		<thead>
	   		   	<tr>
	           	    <th scope="col" class="text-nowrap">Match #</th>
			   	    <th scope="col">Red Alliance</th>
			   	    <th scope="col">Blue Alliance</th>
			   	    <th scope="col">Coop</th>
			   	    </tr>
	        </thead>
	        <tbody>	
            <?php 
            $queryMatches = "SELECT * FROM ".$matchresultsTable." WHERE eventkey = '" . $currEvent . "' ORDER BY matchnumber ASC;";
			$resultMatches = $db->query($queryMatches);
			$zeroRows = true;

			while ($row = mysqli_fetch_assoc($resultMatches)) {
				$zeroRows = false;
			?>
			<tr>
			<td class="text-right"><?= $row["matchnumber"] ?></td>
			<td>
			    <span class="label label-danger"><?= $row["redteam1"] ?></span>
                <span class="label label-danger"><?= $row["redteam2"] ?></span>
	            <span class="label label-danger"><?= $row["redteam3"] ?></span>
	            <?= $row["redscoreAuto"] ?> / <?= $row["redscoreTote"] ?> / <?= $row["redscoreContainer"] ?> / <?= $row["redscoreLitter"] ?> / <?= $row["redscoreFoul"] ?> / <?= $row["redscoreTotal"] ?>
            </td>
	        <td>
			    <span class="label label-primary"><?= $row["blueteam1"] ?></span>
			    <span class="label label-primary"><?= $row["blueteam2"] ?></span>
			    <span class="label label-primary"><?= $row["blueteam3"] ?></span>
	            <?= $row["bluescoreAuto"] ?> / <?= $row["bluescoreTote"] ?> / <?= $row["bluescoreContainer"] ?> / <?= $row["bluescoreLitter"] ?> / <?= $row["bluescoreFoul"] ?> / <?= $row["bluescoreTotal"] ?>
			</td>
	        <td><?= $row["coopscore"] ?></td>
			<?php
            }
            if ($zeroRows) {
            ?>
            <tr><td colspan="4"><p class="alert alert-warning" role="alert">No match results yet.</p></td></tr>
            <?php	
            }
            ?>
            </tbody>
            </table>
            
        <!--  Show a different form depending on the situation -->
        
        <div class="page-header"><h2>Manually Update Match Results for <?= $currEventName ?></h2></div>
        <div class="alert alert-warning" role="alert">
            <strong> Note: </strong> Putting in a match number that is already in the database will
            result in updating that row, not adding a new row.  Any scores associated with the alliances
            of that match will remain in place, but the team numbers will be updated if they are changed.
        </div>
        
        <p>
            <form action="loadmatchresultsManual.php" method="post">
            <?php  if ($showForm == "matchnumberOnly") { ?>
                <input type="hidden" name="MATCHNUMBERONLY" value="true"/>
                <input class="form-control" type="text" name="matchnumber" placeholder="Match #" required autofocus>
                
            <?php  } elseif ($showForm == "matchresultsNoData") { ?>
                <p>Match # <?= $matchnumber ?></p>
                <input type="hidden" name="matchnumber" value="<?= $matchnumber ?>">

                <span class="label label-danger"><?= $matchData['redteam1'] ?></span>
                <input type="hidden" name="redteam1" value="<?= $matchData['redteam1'] ?>"/>
                <span class="label label-danger"><?= $matchData['redteam2'] ?></span>
                <input type="hidden" name="redteam2" value="<?= $matchData['redteam2'] ?>"/>
                <span class="label label-danger"><?= $matchData['redteam3'] ?></span>
                <input type="hidden" name="redteam3" value="<?= $matchData['redteam3'] ?>"/>
                <br/>
                <input class="form-control" type="text" name="redscoreAuto" placeholder="Red Score Autonomous" required >
                <input class="form-control" type="text" name="redscoreTote" placeholder="Red Score Tote" required >
                <input class="form-control" type="text" name="redscoreContainer" placeholder="Red Score Container" required >
                <input class="form-control" type="text" name="redscoreLitter" placeholder="Red Score Litter" required >
                <input class="form-control" type="text" name="redscoreFoul" placeholder="Red Score Foul" required >
                <input class="form-control" type="text" name="redscoreTotal" placeholder="Red Score Total" required >
                <br/>
                <span class="label label-primary"><?= $matchData['blueteam1'] ?></span>
                <input type="hidden" name="blueteam1" value="<?= $matchData['blueteam1'] ?>"/>
                <span class="label label-primary"><?= $matchData['blueteam2'] ?></span>
                <input type="hidden" name="blueteam2" value="<?= $matchData['blueteam2'] ?>"/>
                <span class="label label-primary"><?= $matchData['blueteam3'] ?></span>
                <input type="hidden" name="blueteam3" value="<?= $matchData['bluedteam3'] ?>"/>
                <br/>
                <input class="form-control" type="text" name="bluescoreAuto" placeholder="Blue Score Autonomous" required >
                <input class="form-control" type="text" name="bluescoreTote" placeholder="Blue Score Tote" required >
                <input class="form-control" type="text" name="bluescoreContainer" placeholder="Blue Score Container" required >
                <input class="form-control" type="text" name="bluescoreLitter" placeholder="Blue Score Litter" required >
                <input class="form-control" type="text" name="bluescoreFoul" placeholder="Blue Score Foul" required >
                <input class="form-control" type="text" name="bluescoreTotal" placeholder="Blue Score Total" required >
                <br/>
                <span class="label label-warning">Coopertition</span><br/>
                <input class="form-control" type="text" name="coopscore" placeholder="Coop Score" required >
                
            <?php  } elseif ($showForm == "matchresultsWithData") { ?>
                <p>Match # <?= $matchnumber ?></p>
                <input type="hidden" name="matchnumber" value="<?= $matchnumber ?>">

                <span class="label label-danger"><?= $matchResultsData['redteam1'] ?></span>
                <input type="hidden" name="redteam1" value="<?= $matchResultsData['redteam1'] ?>"/>
                <span class="label label-danger"><?= $matchResultsData['redteam2'] ?></span>
                <input type="hidden" name="redteam2" value="<?= $matchResultsData['redteam2'] ?>"/>
                <span class="label label-danger"><?= $matchResultsData['redteam3'] ?></span>
                <input type="hidden" name="redteam3" value="<?= $matchResultsData['redteam3'] ?>"/>
                <br/>
                <input class="form-control" type="text" name="redscoreAuto" placeholder="Red Score Autonomous" value="<?= $matchResultsData['redscoreAuto'] ?>" required >
                <input class="form-control" type="text" name="redscoreTote" placeholder="Red Score Tote" value="<?= $matchResultsData['redscoreTote'] ?>" required >
                <input class="form-control" type="text" name="redscoreContainer" placeholder="Red Score Container" value="<?= $matchResultsData['redscoreContainer'] ?>" required >
                <input class="form-control" type="text" name="redscoreLitter" placeholder="Red Score Litter" value="<?= $matchResultsData['redscoreLitter'] ?>" required >
                <input class="form-control" type="text" name="redscoreFoul" placeholder="Red Score Foul" value="<?= $matchResultsData['redscoreFoul'] ?>" required >
                <input class="form-control" type="text" name="redscoreTotal" placeholder="Red Score Total" value="<?= $matchResultsData['redscoreTotal'] ?>" required >
                <br/>
                <span class="label label-primary"><?= $matchResultsData['blueteam1'] ?></span>
                <input type="hidden" name="blueteam1" value="<?= $matchResultsData['blueteam1'] ?>"/>
                <span class="label label-primary"><?= $matchResultsData['blueteam2'] ?></span>
                <input type="hidden" name="blueteam2" value="<?= $matchResultsData['blueteam2'] ?>"/>
                <span class="label label-primary"><?= $matchResultsData['blueteam3'] ?></span>
                <input type="hidden" name="blueteam3" value="<?= $matchResultsData['bluedteam3'] ?>"/>
                <br/>
                <input class="form-control" type="text" name="bluescoreAuto" placeholder="Blue Score Autonomous" value="<?= $matchResultsData['bluescoreAuto'] ?>" required >
                <input class="form-control" type="text" name="bluescoreTote" placeholder="Blue Score Tote" value="<?= $matchResultsData['bluescoreTote'] ?>" required >
                <input class="form-control" type="text" name="bluescoreContainer" placeholder="Blue Score Container" value="<?= $matchResultsData['bluescoreContainer'] ?>" required >
                <input class="form-control" type="text" name="bluescoreLitter" placeholder="Blue Score Litter" value="<?= $matchResultsData['bluescoreLitter'] ?>" required >
                <input class="form-control" type="text" name="bluescoreFoul" placeholder="Blue Score Foul" value="<?= $matchResultsData['bluescoreFoul'] ?>" required >
                <input class="form-control" type="text" name="bluescoreTotal" placeholder="Blue Score Total" value="<?= $matchResultsData['bluescoreTotal'] ?>" required >
                <br/>
                <span class="label label-warning">Coopertition</span><br/>
                <input class="form-control" type="text" name="coopscore" placeholder="Coop Score" value="<?= $matchResultsData['coopscore'] ?>" required >
            
            <?php  } else { ?>
                <div class="alert alert-warning" role="alert">
                    <strong> DANGER: </strong> It's not clear how this could happen!!!  Still, start with a match number, and work from here.
                </div>
                <input type="hidden" name="MATCHNUMBERONLY" value="true"/>
                <input class="form-control" type="text" name="matchnumber" placeholder="Match #" required autofocus>
            
            <?php  } ?>
            
                <br/>
                <input type="submit" class="btn btn-lg btn-primary" value=" Add Match Results "/>
	        </form>
        </p>

        <p><a href="admin.php" class="btn btn-danger">Back to Admin Index</a></p>
        
    </div>
    
</body>
</html>
         