<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";
if ($_SESSION['role'] != "admin") {
    header("Location: index.php");
}
?>
<!DOCTYPE HTML>
<html>
<head>
	<title>Account Settings</title>
    <?php include "includes/allCss.php" ?>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
    Not yet implemented...
	</div>
</body>
</html>