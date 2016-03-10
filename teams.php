<?php include "includes/sessionCheck.php"; ?>
<?php include "includes/globalVars.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Teams</title>
	<?php include "includes/allCss.php" ?>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
	    <div class="page-header"><h2>All Teams at <?= $currEventName ?></h2></div>
	    
	    <table class="table table-striped">
	   		<thead>
	   		   	<tr>
	           	    <th scope="col" class="text-nowrap"><span class="glyphicon glyphicon-camera" aria-hidden="true"></span> Pictures</th>
			   	    <th scope="col">Number</th>
			   	    <th scope="col">Name</th>
   			   	    <th scope="col">Location</th>
    	   	    	<th scope="col">URL</th>
	        	</tr>
	        </thead>
	        <tbody>	
	    	<?php
	    		$queryGeneralData = "SELECT * FROM ".$teamsTable." WHERE eventkey = '" . $currEvent . "' ORDER BY number ASC;";
		   		$resultGeneralData = $db -> query($queryGeneralData);
	    		while ($row = mysqli_fetch_assoc($resultGeneralData)) {
	    	?>
	   	        <tr>
	   	            <?php 
	   	            // Find out if any pit data has happened yet
     	    		$queryPitDataTable = "SELECT * FROM ".$pitdataTable." WHERE teamnumber = '" . $row["number"] . "' AND  eventkey = '" . $currEvent . "';";
	   	    		$resultPitDataTable = $db->query($queryPitDataTable);
	           		$pitrow = mysqli_fetch_assoc($resultPitDataTable);
	           		?>
			        <td class="text-nowrap">
			        <?php
			            $robotPicBtnClass = "btn-primary";
			            $driverPicBtnClass = "btn-primary";
			            if (isset($pitrow)) {
			            	if (isset($pitrow['robotPicture'])) {
			                    $robotPicBtnClass = "btn-success";
			            	}
			            	if (isset($pitrow['driverPicture'])) {
			                    $driverPicBtnClass = "btn-success";
			            	}
			            }		            	
			        ?>
			            <a class="btn <?= $robotPicBtnClass ?>" href="pitscouting.php?tmNum=<?= $row["number"] ?>&pictureType=robot"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span></a>
			            <a class="btn <?= $driverPicBtnClass ?>" href="pitscouting.php?tmNum=<?= $row["number"] ?>&pictureType=driver"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
			        </td>
			        <td class="text-right"><a href="reportsSingleTeam.php?tmNum=<?= $row["number"] ?>"><?= $row["number"] ?></a></td>
			        <td><?= $row["name"] ?></td>
		            <td><?= $row["location"] ?></td>
	  	            <td><?= $row["url"] ?></td>
	   	        </tr>
	  	    <?php } ?>
	        </tbody>
		</table>
    </div>
</body>
</html>