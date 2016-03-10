<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Reports</title>
    <?php include "includes/allCss.php" ?>
<style>
.button-actions-set {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
</style>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
        <div class="page-header"><h2>Reports</h2></div>

        <div class="button-actions-set">
            <p><a href="reportsSelectTeam.php" class="btn btn-block btn-primary">Individual Team Report</a></p>
            <p><a href="reportsAllianceRank.php" class="btn btn-block btn-success">Alliance Selection Team Ranking</a></p>
            <p><a href="reportsScoutsStats.php" class="btn btn-block btn-info">DevilScoutz Stats</a></p>
<!--             <p><a href="reportsMain.php" class="btn btn-block btn-warning">Reports</a></p> -->
        </div>

	</div>
</body>
</html>