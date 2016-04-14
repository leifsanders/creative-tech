<?php
 	$servername = "stocks";
 	$database = "fet13019968";
 	$username = "fet13019968";
 	$password = "9d3hii";

// Create connection
 	$conn = mysqli_connect($servername, $username, $password, $database);

 	// Check connection and feedback result
 	if ($conn->connect_error) {
 	    die("Connection failed: " . $conn->connect_error);
 	} 
?>