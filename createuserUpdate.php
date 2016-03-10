<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

if ($_SESSION['role'] != "admin") {
	header("Location: index.php");
} else {	
	$query = "INSERT INTO ".$usersTable." (username, password, realname, role) VALUES ("
			. $db->quote($_POST['username']) . ","
			. $db->quote($_POST['username']) . ","
			. $db->quote($_POST['realname']) . ","
			. $db->quote($_POST['role']) . ");";
	
	$result = $db->query($query);
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Create User Update</title>
    <?php include "includes/allCss.php" ?>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
        <div class="page-header"><h2 id="Header">Created user <?= $_POST['username'] ?></h2></div>

        <div>
            <?php if ($result) { ?>
            	Successfully created a new user!
            <?php } else { ?>
            	There was a problem with the database!
            <?php } ?>
	    </div>
    </div>
</body>
</html>