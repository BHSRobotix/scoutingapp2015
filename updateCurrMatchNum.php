<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

if ($_SESSION['role'] != "admin") {
	header("Location: index.php");
} else {
	if (($_SERVER['REQUEST_METHOD'] == "POST") && (!empty($_POST['currMatchNum']))) {
		// Assuming it is coming from this page and update
	    $queryUpdate = "UPDATE ".$eventstatusTable." SET currentMatchNumber=".$db->quote($_POST['currMatchNum'])." WHERE active='true';";
		$resultUpdate = $db -> query($queryUpdate);	
	}
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Admin: Update Current Match Number</title>
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
        <div class="page-header"><h2>Update Current Match Number</h2></div>

        <p>
            <form action="updateCurrMatchNum.php" method="post">
                <input class="form-control" type="text" name="currMatchNum" placeholder="Current Match #" required autofocus>
                <br/>
                <input type="submit" class="btn btn-lg btn-primary" value=" Update "/>
	        </form>
        </p>

        <p><a href="admin.php" class="btn btn-danger">Back to Admin Index</a></p>
        
    </div>
    
</body>
</html>
         