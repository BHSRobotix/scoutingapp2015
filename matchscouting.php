<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

// Get the team to do a report on
$method = $_SERVER['REQUEST_METHOD'];
$team = $ourTeamNum;
$matchnumber = "1";
if ($method == "GET") {
	$team = $_GET["tmNum"];
	$matchnumber = $_GET["matchNum"];
	$source = $_GET["source"];
} else if ($method == "POST") {
	$team = $_POST["tmNum"];
	$matchnumber = $_POST["matchNum"];
	$source = $_POST["source"];
}

// Some quick and dirty error checking, in case manual team numbers put in
if ($source != "schedule") {
    $inputErrors = false;
    $inputErrorsMsg = "";
    $query = "SELECT * FROM ".$teamsTable." WHERE number=" . $team . " AND eventkey=" . $db->quote($currEvent) . ";";
    $result = $db->query($query);
    if ($result->num_rows != 1) {
    	$inputErrors = true;
	    $inputErrorsMsg .= "Team ".$team." is not at this competition.<br/>";
    }
    if (intval($matchnumber) > 120) {
	    $inputErrors = true;
	    $inputErrorsMsg .= "The match number appears invalid.<br/>";	
    }
}
?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Match Scouting</title>
    <?php include "includes/allCss.php" ?>

<style>
h4 {
    color: skyblue;
}
</style>
</head>

<body>
    <?php include "includes/userHeader.php" ?>

	<div class="container">
    
    <?php if ($inputErrors) { ?>
        <div class="alert alert-danger" role="alert"><?= $inputErrorsMsg ?></div>
        <a class="btn btn-primary" href="javascript:window.history.back()"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Go Back</a>
    <?php } else { ?>

        <div class="page-header"><h2>Match Scouting</h2></div>

        <div class="page-header"><h3>Team: <?= $team ?> ,  Match: <?= $matchnumber ?></h3></div>

        <p>
            <form action="matchscoutingUpdate.php" method="post">
                <input type="hidden" name="teamnumber" value="<?= $team ?>"/>
                <input type="hidden" name="matchnumber" value="<?= $matchnumber ?>"/>
                
                Are they functional? <br>
                <input type="radio" name="isFunctional" value="yes" id= "funcyes"> <label for= "funcyes"> Yes </label> 
                <input type="radio" name="isFunctional" value="no" id= "funcno"> <label for= "funcno"> No </label>

                <br/><br/>

                <h4> Autonomous </h4>

                What did they do during <strong> autonomous</strong>? <br/>
                <input type="checkbox" name="autoRobotset" value="yes" id="autors"> <label for= "autors"> Drove into the auto zone </label> <br/>
                <input type="checkbox" name="autoToteset" value="yes" id="autots"> <label for= "autots"> Brought a tote into the auto zone </label> <br/>
                <input type="checkbox" name="autoContainerset" value="yes" id="autocs"> <label for= "autocs"> Brought a container into the auto zone <br/> </label>

                <br/><br/>
                
                <h4> Tele-Op </h4>
                
                How many <strong> totes </strong> did they stack in total in the match?<br/>
                <input type="text" name="teleTotesStacked" style="width: 50px"/>
                <br/><br/>
                
                What was the highest height of <strong> totes </strong> they stacked? <br/>
                <input type="radio" name="teleTotesHeight" value="0" id="toteht0"> <label for="toteht0"> 0 </label>
		        <input type="radio" name="teleTotesHeight" value="1" id="toteht1"> <label for="toteht1"> 1 </label>
                <input type="radio" name="teleTotesHeight" value="2" id="toteht2"> <label for="toteht2"> 2 </label>
                <input type="radio" name="teleTotesHeight" value="3" id="toteht3"> <label for="toteht3"> 3 </label>
                <input type="radio" name="teleTotesHeight" value="4" id="toteht4"> <label for="toteht4"> 4 </label>
                <input type="radio" name="teleTotesHeight" value="5" id="toteht5"> <label for="toteht5"> 5 </label>
                <input type="radio" name="teleTotesHeight" value="6" id="toteht6"> <label for="toteht6"> 6 </label>

                <br/><br/>


                Where do they get their <strong> totes </strong> from? <br/>
                <input type="checkbox" name="teleTotesSource[]" value="Feeder" id= "srcfeeder"> <label for= "srcfeeder"> They hog the feeder station </label> <br/>
                <input type="checkbox" name="teleTotesSource[]" value="Landfill" id= "srclandfill"> <label for= "srclandfill"> They get them from the landfill </label> <br/>
                
                <br/><br/>

                How many <strong> containers </strong> did they place on tote stacks in the match?<br/>
                <input type="text" name="teleContainersStacked" style="width: 50px"/>
                
                <br/><br/>
                
                
                What is the highest number of totes they stacked a <strong> container </strong> on? <br/>
                <input type="radio" name="teleContainersHeight" value="0" id="contht0"> <label for="contht0"> 0 </label>
                <input type="radio" name="teleContainersHeight" value="1" id="contht1"> <label for="contht1"> 1 </label>
                <input type="radio" name="teleContainersHeight" value="2" id="contht2"> <label for="contht2"> 2 </label>
                <input type="radio" name="teleContainersHeight" value="3" id="contht3"> <label for="contht3"> 3 </label>
                <input type="radio" name="teleContainersHeight" value="4" id="contht4"> <label for="contht4"> 4 </label>
                <input type="radio" name="teleContainersHeight" value="5" id="contht5"> <label for="contht5"> 5 </label>
                <input type="radio" name="teleContainersHeight" value="6" id="contht6"> <label for="contht6"> 6 </label>

                <br/><br/>
                
                
    		    What orientation did they pick their <strong> containers </strong> up in? <br/>
        		<input type="checkbox" name="teleContainersOrientation[]" value="Sideways" id="totesside"> <label for= "totesside"> Sideways </label> <br/>
                <input type="checkbox" name="teleContainersOrientation[]" value="Standing" id="totesstand"> <label for= "totesstand"> Standing Up </label> <br/>
                <input type="checkbox" name="teleContainersOrientation[]" value="Landfill" id="totesland"> <label for= "totesland"> From the Landfill </label> <br/>

                <br/><br/>
                

    		    Did they put a noodle into a <strong> container</strong>? <br/>
		        <input type="radio" name="teleContainersNoodled" value="yes" id="noodleContYes"> <label for="noodleContYes"> Yes </label>
		        <input type="radio" name="teleContainersNoodled" value="no" id="noodleContNo"> <label for="noodleContNo"> No </label>

                <br/><br/>
		

                Did they help complete coopertition set or stack? <br/>
                <input type="radio" name="coopertitionHelp" value="no" id= "coopno"> <label for="coopno"> No </label> <br/>
                <input type="radio" name="coopertitionHelp" value="set" id= "coopyes"> <label for= "coopyes"> Yes, a set </label> <br/>
                <input type="radio" name="coopertitionHelp" value="stack" id= "coopstack"> <label for= "coopstack"> Yes, a stack </label> <br/>

                <br/><br/>
                                
                
                <strong><font size="3" color="deepskyblue"> Additional Comments </font></strong> <br>
                <input type="text" name="comments" style="width: 300px;" />

                <br/><br/>
                                
                <input type="submit" class="btn btn-primary" value="Save to Server" name="submit">
            </form>
        </p>

    <?php } ?>
        
    </div>
    
</body>
</html>
         