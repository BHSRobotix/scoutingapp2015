<?php 
$ourTeamNum = "2876";

$eventstatusTable = "eventstatus";
$teamsTable = "teams";
$usersTable = "users";
$pitdataTable = "pitdata_v2";
$matchesTable = "matches_v2";
$matchresultsTable = "matchresults";
$driverfeedbackTable = "driverfeedback_v2";
$performancesTable = "performances_v2";
$scrapedrankingsTable = "scrapedrankings";

$teamsLoaded = true;
$matchesLoaded = false;

$showFutureTeammates = true;

require_once "Db.php";
$db = new Db();

$queryEventStatus = "SELECT * FROM ".$eventstatusTable." WHERE active = 'true';";
$resultEventStatus = $db->query($queryEventStatus);
$rowEvtStatus = mysqli_fetch_assoc($resultEventStatus);
$currEvent = $rowEvtStatus["eventkey"];
$currEventName = $rowEvtStatus["eventShortName"];
$currMatchNumber = $rowEvtStatus["currentMatchNumber"];
//$currEvent = "2015marea";
//$currMatchNumber = 1;

?>