<?php
 	$servername = "stocks";
 	$database = "fet13019968";
 	$username = "fet13019968";
 	$password = "9d3hii";

// Create connection
 	$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection and feedback result
 	if (mysqli_connect_errno()) {
 		echo mysqli_connect_error();
 		exit();
 	}
?>