<?php
/* @author Megana */

// TODO: get pit form element values 

// get pit image file, assuming the robot number comes in form values
if(isset($_FILES['robopic']) && $_FILES['robopic']['size'] > 0) {
    $image_file = $_FILES["robopic"]; 
	$robotNumber = $_POST['roboId']; 
	processPitImage($image_file, $robotNumber);
}

// other form elements
$driveTrainSelected = $_POST['drivetrain'];
$strategyValue = $_POST['strategy'];
$autoValue = $_POST['auto'];
$commentsValue = $_POST['comments'];

// process pit image file
function processPitImage($image_file, $robotNumber) {
	$target_dir = "images/";
	$isUploadOK = 1; // 1 = ok, 2 = File Exists, 3 = File unsupported, 4 = File too large
	
	// Check file size
	if ($image_file["size"] > 500000) {	    
	    $isUploadOK = 4; // file size too big
	}
	
	$image_name = basename($image_file["name"]);
		
	// Check if file already exists
	if (file_exists($target_dir . $image_name)) {	    
	    $isUploadOK = 2; // file already exists
	}	
	
	$imageFileType = pathinfo($image_name,PATHINFO_EXTENSION);
	// Allow certain file formats
	// if( $imageFileType != "png" && $imageFileType != "PNG" ) {
	    // $isUploadOK = 3; // only PNG is supported
	// }
	
	// if all checks are ok, upload the file , if checks failed do nothing for now
	if ($isUploadOK == 1) {
		// $imageFileType = "png"; // support for png's enough ?
	    if (move_uploaded_file($image_file["tmp_name"], ($target_dir . $image_name) )) {
	        // image successfully copied to target folder
	    } 
	}
}

echo $driveTrainSelected . " " . $strategyValue . " " . $autoValue . " " . $commentsValue;

?>