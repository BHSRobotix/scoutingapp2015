<?php
include "includes/sessionCheck.php";
include "includes/globalVars.php";

$team = $_POST['teamnumber'];
$picType = (empty($_POST['pictureType']) ? "robot" : $_POST['pictureType']);

// IMAGE PROCESSING
define('THUMBNAIL_IMAGE_MAX_WIDTH', 350);
define('THUMBNAIL_IMAGE_MAX_HEIGHT', 350);

function generate_image_thumbnail($source_image_path, $thumbnail_image_path) {
	list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
	switch ($source_image_type) {
		case IMAGETYPE_GIF:
			$source_gd_image = imagecreatefromgif($source_image_path);
			break;
		case IMAGETYPE_JPEG:
			$source_gd_image = imagecreatefromjpeg($source_image_path);
			break;
		case IMAGETYPE_PNG:
			$source_gd_image = imagecreatefrompng($source_image_path);
			break;
	}
	if ($source_gd_image === false) {
		return false;
	}

	$source_aspect_ratio = $source_image_width / $source_image_height;
	$thumbnail_aspect_ratio = THUMBNAIL_IMAGE_MAX_WIDTH / THUMBNAIL_IMAGE_MAX_HEIGHT;
	if ($source_image_width <= THUMBNAIL_IMAGE_MAX_WIDTH && $source_image_height <= THUMBNAIL_IMAGE_MAX_HEIGHT) {
		$thumbnail_image_width = $source_image_width;
		$thumbnail_image_height = $source_image_height;
	} elseif ($thumbnail_aspect_ratio > $source_aspect_ratio) {
		$thumbnail_image_width = (int) (THUMBNAIL_IMAGE_MAX_HEIGHT * $source_aspect_ratio);
		$thumbnail_image_height = THUMBNAIL_IMAGE_MAX_HEIGHT;
	} else {
		$thumbnail_image_width = THUMBNAIL_IMAGE_MAX_WIDTH;
		$thumbnail_image_height = (int) (THUMBNAIL_IMAGE_MAX_WIDTH / $source_aspect_ratio);
	}
	$thumbnail_gd_image = imagecreatetruecolor($thumbnail_image_width, $thumbnail_image_height);
	imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $thumbnail_image_width, $thumbnail_image_height, $source_image_width, $source_image_height);
	imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 90);
	
	// Fix Orientation
	$exif = exif_read_data($thumbnail_image_path);
	$orientation = $exif['Orientation'];
	echo $orientation ."<br/>";
	switch($orientation) {
		case 3:
			$thumbnail_gd_image = imagerotate($thumbnail_gd_image, 180, 0);
			break;
		case 6:
			$thumbnail_gd_image = imagerotate($thumbnail_gd_image, -90, 0);
			break;
		case 8:
			$thumbnail_gd_image = imagerotate($thumbnail_gd_image, 90, 0);
			break;
	}
		
	imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 90);
	imagedestroy($source_gd_image);
	imagedestroy($thumbnail_gd_image);
	return true;
}


define('UPLOADED_IMAGE_DESTINATION', 'robopics_orig/');
define('THUMBNAIL_IMAGE_DESTINATION', 'robopics/');

function process_image_upload($field, $evt, $team, $picType) {
	$temp_image_path = $_FILES[$field]['tmp_name'];
 	$temp_image_name = $evt . "_" . $team . "_" . $picType . "_" . $_FILES[$field]['name'];
	list(, , $temp_image_type) = getimagesize($temp_image_path);
	if ($temp_image_type === NULL) {
		return false;
	}
	switch ($temp_image_type) {
		case IMAGETYPE_GIF:
			break;
		case IMAGETYPE_JPEG:
			break;
		case IMAGETYPE_PNG:
			break;
		default:
			return false;
	}
	$uploaded_image_path = UPLOADED_IMAGE_DESTINATION . $temp_image_name;
	move_uploaded_file($temp_image_path, $uploaded_image_path);
	$thumbnail_image_path = THUMBNAIL_IMAGE_DESTINATION . preg_replace('{\\.[^\\.]+$}', '.jpg', $temp_image_name);
	$result = generate_image_thumbnail($uploaded_image_path, $thumbnail_image_path);
	return $result ? array($uploaded_image_path, $thumbnail_image_path) : false;
}


$imgSuccess = true;
$imgMessage = "";
$pictureUrl = "";

try {
	if (!isset($_FILES['picture']['error']) || is_array($_FILES['picture']['error'])) {
		throw new RuntimeException('Image upload problem: Invalid parameters.');
	}

	// Check $_FILES['picture']['error'] value.
	switch ($_FILES['picture']['error']) {
		case UPLOAD_ERR_OK:
			break;
		case UPLOAD_ERR_NO_FILE:
			throw new RuntimeException('Image upload problem: No file sent.');
		case UPLOAD_ERR_INI_SIZE:
		case UPLOAD_ERR_FORM_SIZE:
			throw new RuntimeException('Image upload problem: Exceeded filesize limit.');
		default:
			throw new RuntimeException('Image upload problem: Unknown errors.');
	}

	$result = process_image_upload('picture', $currEvent, $team, $picType);
	if ($result === false) {
		$imgSuccess = false;
		$imgMessage = "An error occurred while processing upload";
	} else {
		$newFilename = $result[1];
		$pictureUrl = "http://devilbotz.achievasoft.com/".$newFilename;
		$imgMessage = "File is uploaded successfully.";
	}
	
	$imgMessage = "File is uploaded and resized successfully.";

} catch (RuntimeException $e) {
	$imgSuccess = false;
	$imgMessage = $e->getMessage();
}


// STORE IT!
$dbSuccess = true;
$dbMessage = "";
$resultTest = $db->query("SELECT * FROM ".$pitdataTable." WHERE teamnumber=" . $team . " AND eventkey=" . $db->quote($currEvent) . ";");
if ($resultTest->num_rows == 0) {
	// Let's insert a row with just the team number and then update it later
	$resultFinal =  $db->query("INSERT INTO ".$pitdataTable." (teamnumber, eventkey) VALUES (" . $db->quote($team) . "," . $db->quote($currEvent) . ");");
} else if ($resultTest->num_rows > 1) {
	$dbMessage .= "There are too many rows of pit data for team " . $team;
	$dbSuccess = false;
}

if ($dbSuccess) {
	// We should have a row, even if we did just insert it
	$queryUpdate ="UPDATE ".$pitdataTable." SET ";
	if (!empty($pictureUrl)) {
	    $queryUpdate = $queryUpdate . " " . $picType . "Picture=" . $db->quote($pictureUrl) . ",";
    }
	$queryUpdate = $queryUpdate
		. " scout=" . $db->quote($_SESSION['username'])
		. " WHERE teamnumber=" . $team . " AND eventkey=" . $db->quote($currEvent) . ";";
	$resultUpdate = $db->query($queryUpdate);
}

?>
<!DOCTYPE HTML>
<html>
<head>
    <title>Pit Scouting for Team <?= $_POST['teamnumber'] ?></title>
    <?php include "includes/allCss.php" ?>
</head>
<body>
    <?php include "includes/userHeader.php" ?>
    <div class="container">
        <div class="page-header"><h2>Pit Scouting for Team <?= $_POST['teamnumber'] ?></h2></div>
        <p>
            <?php if ($imgSuccess && $dbSuccess) { ?>
            	Successfully saved an image <?= $pictureUrl ?>.
            <?php } else { ?>
            	Failure saving the picture: <?= $imgMessage ?> or updating the database: <?= $dbMessage ?>
            <?php } ?>
	    </p>
	    <p>
	        Take another picture for Team <?= $team ?>:<br/>
    	    <a class="btn btn-default" href="pitscouting.php?tmNum=<?= $team ?>&pictureType=robot"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span></a>
	    	<a class="btn btn-default" href="pitscouting.php?tmNum=<?= $team ?>&pictureType=driver"><span class="glyphicon glyphicon-user" aria-hidden="true"></span></a>
	    </p>
		<p><a href="teams.php" class="btn btn-primary">Team List</a></p>
		
    </div>
</body>
</html>