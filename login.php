<?php
include "includes/globalVars.php";
session_start();

if(isset($_POST['submit'])) {
	if(!empty($_POST['username']) && !empty($_POST['password'])) {
		
		$username = $db -> quote($_POST['username']);
		$password = $db -> quote($_POST['password']);
		
		$query = "SELECT * FROM " . $usersTable . " WHERE username=".$username." AND password=".$password.";";
		$result = $db -> query($query);
		if ($result->num_rows == 1) {
			$_SESSION['loggedin'] = true;
			$_SESSION['username'] = $_POST['username'];
			$row = mysqli_fetch_assoc($result);
			$_SESSION['role'] = $row['role'];
			header("Location: index.php");
		} else {
			//we put errors in a variable $msg then print out $msg below
			$msg.="Either your username or password was invalid; please check them then try again.";
// 			$msg.="<br/>Username submitted: ".$_POST['username']."<br/>Password submitted: ".$_POST['password']."<br/>Num rows returned: ". $result->num_rows ."<br/>";
				
		}
	} else {
		$msg.="You must type in both a username <strong>and</strong> password.";
	}
}											
?>
<!DOCTYPE html>
<html>
<head>
    <title>Please log in</title>
    <?php include "includes/allCss.php" ?>
<style>
body {
  padding-top: 40px;
  padding-bottom: 40px;
  background-color: #eee;
}
.form-signin {
  max-width: 330px;
  padding: 15px;
  margin: 0 auto;
}
.form-signin .form-signin-heading {
  margin-bottom: 10px;
}
.form-signin .form-control {
  position: relative;
  height: auto;
  -webkit-box-sizing: border-box;
     -moz-box-sizing: border-box;
          box-sizing: border-box;
  padding: 10px;
  font-size: 16px;
}
.form-signin .form-control:focus {
  z-index: 2;
}
.form-signin input[type="text"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}
.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>

</head>

<body>
    <div class="container">

        <form method="post" action="login.php" class="form-signin">
            <h2 class="form-signin-heading">Please sign in</h2>

            <input class="form-control"  type="text" name="username" placeholder="Username" required autofocus>
            <input class="form-control"  type="password" name="password" placeholder="Password" required>
            
            <input class="btn btn-lg btn-primary btn-block" type="submit" name="submit" value="Sign in">
            <br/>
            <?php if (!empty($msg)) { ?><div class="alert alert-danger" role="alert"><?= $msg ?></div><?php } ?>
        </form>

        
    </div>
    
</body>
</html>