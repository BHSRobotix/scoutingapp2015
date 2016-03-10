<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

if ($_SESSION['role'] != "admin") {
	header("Location: index.php");
} else {
	$errorMessages = "";
	
	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		// Assuming it is coming from this page
		$queryTest = "SELECT * FROM ".$matchesTable." WHERE matchnumber=".$_POST['matchnumber']." AND  eventkey=" . $db->quote($currEvent) . ";";
		$resultTest = $db->query($queryTest);
		if ($resultTest->num_rows == 0) {
			// Insert
			$queryInsert = "INSERT INTO ".$matchesTable." (matchnumber, eventkey, redteam1, redteam2, redteam3, blueteam1, blueteam2, blueteam3) VALUES ("
					.$db->quote($_POST['matchnumber']).","
					.$db->quote($currEvent).","
					.$db->quote($_POST['redteam1']).","
					.$db->quote($_POST['redteam2']).","
					.$db->quote($_POST['redteam3']).","
					.$db->quote($_POST['blueteam1']).","
					.$db->quote($_POST['blueteam2']).","
					.$db->quote($_POST['blueteam3']).");";
			$resultInsert = $db -> query($queryInsert);
		} else if ($resultTest->num_rows == 1) {
			// Update
			$queryUpdate = "Update ".$matchesTable." SET "
					." redteam1=".$db->quote($_POST['redteam1']).","
					." redteam2=".$db->quote($_POST['redteam2']).","
					." redteam3=".$db->quote($_POST['redteam3']).","
					." blueteam1=".$db->quote($_POST['blueteam1']).","
					." blueteam2=".$db->quote($_POST['blueteam2']).","
					." blueteam3=".$db->quote($_POST['blueteam3'])." "
				    ." WHERE matchnumber=" . $_POST['matchnumber'] . " AND eventkey=" . $db->quote($currEvent) . ";";
			$resultUpdate = $db -> query($queryUpdate);			
		} else {
			// Houston we have a problem
			$errorMessages .= "ERROR: There were multiple rows with matchnumber " . $_POST['matchnumber'] . " and event " . $currEvent . ".";
		}
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
	    <?php if (!empty($errorMessages)) { ?><div class="alert alert-danger" role="alert"><?= $errorMessages ?></div><?php } ?>
    
        <div class="page-header"><h2>Current Match Schedule</h2></div>

	        <table class="table table-striped">
	   		<thead>
	   		   	<tr>
	           	    <th scope="col" class="text-nowrap">Match #</th>
			   	    <th scope="col">Red Alliance</th>
			   	    <th scope="col">Blue Alliance</th>
	        	</tr>
	        </thead>
	        <tbody>	
            <?php 
            $queryMatches = "SELECT * FROM ".$matchesTable." WHERE eventkey = '" . $currEvent . "' ORDER BY matchnumber ASC;";
			$resultMatches = $db->query($queryMatches);
			$zeroRows = true;

			while ($row = mysqli_fetch_assoc($resultMatches)) {
				$zeroRows = false;
			?>
			<tr>
			<td><?= $row["matchnumber"] ?></td>
			<td>
			    <span class="label label-danger"><?= $row["redteam1"] ?></span>
                <span class="label label-danger"><?= $row["redteam2"] ?></span>
	            <span class="label label-danger"><?= $row["redteam3"] ?></span>
	        </td>
	        <td>
			    <span class="label label-primary"><?= $row["blueteam1"] ?></span>
			    <span class="label label-primary"><?= $row["blueteam2"] ?></span>
			    <span class="label label-primary"><?= $row["blueteam3"] ?></span>
	        </td>
			<?php
            }
            if ($zeroRows) {
            ?>
            <tr><td colspan="3"><p class="alert alert-warning" role="alert">No matches in schedule (likely reason: no schedule released yet)</p></td></tr>
            <?php	
            }
            ?>
            </tbody>
            </table>
        
        <div class="page-header"><h2>Manually Update Match Schedule for <?= $currEventName ?></h2></div>
        <div class="alert alert-warning" role="alert">
            <strong> Note: </strong> Putting in a match number that is already in the database will
            result in updating that row, not adding a new row.  Any scores associated with the alliances
            of that match will remain in place, but the team numbers will be updated if they are changed.
        </div>
        
        <p>
            <form action="loadmatchManual.php" method="post">
                <input type="hidden" name="eventkey" value="<?= $currEvent ?>"/>
                <input class="form-control" type="text" name="matchnumber" placeholder="Match #" required autofocus>
                <br/>
	            <input class="form-control" type="text" name="redteam1" placeholder="Red Tm #" required >
	            <input class="form-control" type="text" name="redteam2" placeholder="Red Tm #" required >
	            <input class="form-control" type="text" name="redteam3" placeholder="Red Tm #" required >
	            <br/>
	            <input class="form-control" type="text" name="blueteam1" placeholder="Blue Tm #" required >
	            <input class="form-control" type="text" name="blueteam2" placeholder="Blue Tm #" required >
	            <input class="form-control" type="text" name="blueteam3" placeholder="Blue Tm #" required >
                  
                <br/>
                <input type="submit" class="btn btn-lg btn-primary" value=" Add Match "/>
	        </form>
        </p>

        <p><a href="admin.php" class="btn btn-danger">Back to Admin Index</a></p>
        
    </div>
    
</body>
</html>
         