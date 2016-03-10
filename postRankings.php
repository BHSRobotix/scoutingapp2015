<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";
if ($_SESSION['role'] != "admin") {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Functions: Load Rankings From Scraped Rankings Page</title>
  <?php include "includes/allCss.php" ?>
</head>
<body role="document">

  <!-- Fixed navbar -->
  <?php include "includes/userHeader.php" ?>
    
  <!-- Primary Page Layout --> 
  <div class="container" role="main">
      Put Rankings Here:<br/>
      <form method="post" action="postRankingsUpdate.php">
      <textarea rows="8" cols="40" name="jsonRankings"></textarea>
      <span class="input-group-btn"><input type="submit" class="btn btn-primary" value="Go"/></span>
      </form>
  </div>

</body>
</html>
