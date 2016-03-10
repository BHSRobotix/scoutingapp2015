<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Reports - Alliance Ranking</title>
    <?php include "includes/allCss.php" ?>
</head>
<body>

    <?php include "includes/userHeader.php" ?>
    <div class="container">
        
        <h4>Overall Team Rankings</h4>
        <table class="table table-striped">
            <thead>
               	<tr><th scope="col">Rank</th><th scope="col">Team Number</th><th scope="col">Avg Score</th></tr>
            </thead>
            <tbody>
            <?php
            $query1 = "SELECT * FROM ".$scrapedrankingsTable." WHERE eventkey='".$currEvent."' ORDER BY rank ASC;";
	        $result1 = $db->query($query1);
    		while ($row = mysqli_fetch_assoc($result1)) {
    		?>
    		    <tr>
    		        <td><?= $row["rank"] ?></td>
    		        <td><a href="reportsSingleTeam.php?tmNum=<?= $row["teamNumber"] ?>"><?= $row['teamNumber'] ?></a></td>
    		        <td><?= $row['qualAvg'] ?></td>
    		    </tr>
            <?php
    		}
            ?>
            </tbody>
        </table>


        <h4>Team Rankings Without Coopertition</h4>
        <table class="table table-striped">
            <thead>
               	<tr><th scope="col">Rank</th><th scope="col">Team Number</th><th scope="col">Avg Score Without Coop</th></tr>
            </thead>
            <tbody>
            <?php
            $query1 = "SELECT teamNumber, (((matchesPlayed * qualAvg) - coopPts) / matchesPlayed) AS avgNoCoop FROM ".$scrapedrankingsTable." WHERE eventkey='".$currEvent."' ORDER BY avgNoCoop DESC;";
	        $result1 = $db->query($query1);
	        $rank = 1;
    		while ($row = mysqli_fetch_assoc($result1)) {
    		?>
    		    <tr>
    		        <td><?= $rank ?></td>
    		        <td><a href="reportsSingleTeam.php?tmNum=<?= $row["teamNumber"] ?>"><?= $row['teamNumber'] ?></a></td>
    		        <td><?= $row['avgNoCoop'] ?></td>
    		    </tr>
            <?php
                $rank = $rank+1;
    		}
            ?>
            </tbody>
        </table>
        

        <h4>Team Rankings By Container Points</h4>
        <table class="table table-striped">
            <thead>
               	<tr><th scope="col">Rank</th><th scope="col">Team Number (overall rank)</th><th scope="col">Container Points</th></tr>
            </thead>
            <tbody>
            <?php
            $query1 = "SELECT teamNumber, rank, contPts FROM ".$scrapedrankingsTable." WHERE eventkey='".$currEvent."' ORDER BY contPts DESC;";
	        $result1 = $db->query($query1);
	        $rank = 1;
    		while ($row = mysqli_fetch_assoc($result1)) {
    		?>
    		    <tr>
    		        <td><?= $rank ?></td>
    		        <td><a href="reportsSingleTeam.php?tmNum=<?= $row["teamNumber"] ?>"><?= $row['teamNumber'] ?></a> (<?= $row['rank'] ?>)</td>
    		        <td><?= $row['contPts'] ?></td>
    		    </tr>
            <?php
                $rank = $rank+1;
    		}
            ?>
            </tbody>
        </table>

        
        <h4>Team Rankings By Litter Points</h4>
        <table class="table table-striped">
            <thead>
               	<tr><th scope="col">Rank</th><th scope="col">Team Number (overall rank)</th><th scope="col">Litter Points</th></tr>
            </thead>
            <tbody>
            <?php
            $query1 = "SELECT teamNumber, rank, littPts FROM ".$scrapedrankingsTable." WHERE eventkey='".$currEvent."' ORDER BY littPts DESC;";
	        $result1 = $db->query($query1);
	        $rank = 1;
    		while ($row = mysqli_fetch_assoc($result1)) {
    		?>
    		    <tr>
    		        <td><?= $rank ?></td>
    		        <td><a href="reportsSingleTeam.php?tmNum=<?= $row["teamNumber"] ?>"><?= $row['teamNumber'] ?></a> (<?= $row['rank'] ?>)</td>
    		        <td><?= $row['littPts'] ?></td>
    		    </tr>
            <?php
                $rank = $rank+1;
    		}
            ?>
            </tbody>
        </table>        
        
        <?php
            // OLD QUERIES I STRANGELY CARE ABOUT
//             $query2 = "SELECT teamnumber, AVG(totalAllianceScore) AS avg_score, AVG(coopertitionPoints) AS avg_coop, (AVG(totalAllianceScore) - AVG(coopertitionPoints)) AS diff FROM ".$performancesTable." GROUP BY teamnumber ORDER BY diff DESC;";
//             $query3 = "SELECT teamnumber, AVG(compatibilityRating) AS avg_rating, GROUP_CONCAT(DISTINCT compatibilityReason) FROM ".$driverfeedbackTable." GROUP BY teamnumber ORDER BY avg_rating DESC;";
            ?>
        
        
    </div>
</body>
</html>
