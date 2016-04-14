<?php
	session_start();
	include_once("connect.php");
	include_once("posttodatabase.php");

	$db_id = $_SESSION['UserID'];
	$title = substr($_POST['Title'],0,140);

	add_post($db_id,$title);
	$_SESSION['message'] = "Your post has been added!";

	header("Location:../home.php");
?>