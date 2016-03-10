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
	<title>Create User</title>
    <?php include "includes/allCss.php" ?>
<style>
form {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
</style>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
        <div class="page-header"><h2>Create User</h2></div>
        <div>
	        <form action="createuserUpdate.php" method="post">
                <input class="form-control"  type="text" name="realname" placeholder="Real name" required autofocus>
	            <input class="form-control"  type="text" name="username" placeholder="Username" required >
	            <input class="form-control"  type="text" name="role" placeholder="Role (i.e. scout, drive, admin)" required >
                  
                <br/>
                <input type="submit" class="btn btn-lg btn-primary btn-block" value="Create User"/>
	        </form>
	    </div>
	</div>
</body>
</html>