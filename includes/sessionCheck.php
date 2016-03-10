<?php 
session_start();
//login logic
if (!isset($_SESSION['loggedin']))
{
	header("Location: login.php");
} 
?>