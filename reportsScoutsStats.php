<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Reports - Scouts Stats</title>
    <?php include "includes/allCss.php" ?>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
        <div class="page-header"><h2>Reports - Scouts Stats</h2></div>
        
	    <!-- Match Scouting -->
	    <div class="page-header"><h4>Match Scouting All-Stars</h4></div>
	    <table class="table table-striped">
    		<thead>
	        	<tr><th scope="col">Scout</th><th scope="col"># Matches Scouted</th></tr>
	        </thead>
	        <tbody>	
            <?php
            $query1 = "SELECT scout, COUNT(*) AS num_reports FROM ".$performancesTable." GROUP BY scout ORDER BY num_reports DESC;";
	        $result1 = $db->query($query1);
    		while ($row = mysqli_fetch_assoc($result1)) {
    		?>
                <tr><td><?= $row['scout'] ?></td><td><?= $row['num_reports'] ?></td></tr>
            <?php
    		}
            ?>
	        </tbody>
        </table>        
        
	    <!-- Pit Scouting -->
	    <div class="page-header"><h4>Pit Scouting All-Stars</h4></div>
	    <table class="table table-striped">
    		<thead>
	        	<tr><th scope="col">Scout</th><th scope="col"># Pits Scouted</th></tr>
	        </thead>
	        <tbody>	
            <?php
            $query2 = "SELECT scout, COUNT(*) AS num_reports FROM ".$pitdataTable." GROUP BY scout ORDER BY num_reports DESC;";
	        $result2 = $db->query($query2);
    		while ($row = mysqli_fetch_assoc($result2)) {
    		?>
                <tr><td><?= $row['scout'] ?></td><td><?= $row['num_reports'] ?></td></tr>
            <?php
    		}
            ?>
	        </tbody>
        </table>        
        
        
    </div>
        
</body>
</html>