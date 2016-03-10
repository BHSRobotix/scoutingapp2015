<?php
//Database Connection information.
$host="localhost"; //usually localhost
$username="yourusername";
$password="yourpassword";
$db_name="db_name";

//The unit of distance. If you want to use KM instead, put 'kilometers'
$unit = "miles";
$hav_int = 3959;    

//convert the unit string to a number.
if ($unit=="kilometers")  {
    $hav_int = 6371;
}
?>
