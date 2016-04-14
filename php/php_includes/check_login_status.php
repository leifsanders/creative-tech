<?php

session_start();
include_once("connect.php");

	$user_ok = false;
	$log_id = "";
	$log_username = "";
	$log_password = "";
	// User Verify function
	function evalLoggedUser($conn,$id,$u,$p){
		$sql = "SELECT IP FROM User WHERE UserID='$id' AND Username='$u' AND Password='$p' AND activated='1' LIMIT 1";
	    $query = mysqli_query($conn, $sql);
	    $numrows = mysqli_num_rows($query);
		if($numrows > 0){
			return true;
		}
	}
	if(isset($_SESSION["UserID"]) && isset($_SESSION["Username"]) && isset($_SESSION["Password"])) {
		$log_id = preg_replace('#[^0-9]#', '', $_SESSION['UserID']);
		$log_username = preg_replace('#[^a-z0-9]#i', '', $_SESSION['Username']);
		$log_password = preg_replace('#[^a-z0-9]#i', '', $_SESSION['Password']);
		// Verify the user
		$user_ok = evalLoggedUser($conn,$log_id,$log_username,$log_password);
	} else if(isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])){
		$_SESSION['UserID'] = preg_replace('#[^0-9]#', '', $_COOKIE['id']);
	    $_SESSION['Username'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['user']);
	    $_SESSION['Password'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['pass']);
		$log_id = $_SESSION['UserID'];
		$log_username = $_SESSION['Username'];
		$log_password = $_SESSION['Password'];
		// Verify the user
		$user_ok = evalLoggedUser($conn,$log_id,$log_username,$log_password);
		if($user_ok == true){
			// Update their lastlogin datetime field
			$sql = "UPDATE User SET LastLogin=now() WHERE UserID='$log_id' LIMIT 1";
	        $query = mysqli_query($conn, $sql);
		}
	}
?>