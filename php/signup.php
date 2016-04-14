<?php
	session_start();
	// If user is logged in, error message
	if(isset($_SESSION["username"])){
		header("location: message.php?msg=You are already logged in!");
    	exit();
}
?>

<?php
	// Ajax calls this NAME CHECK code to execute
	if(isset($_POST["usernamecheck"])){
		include_once("php_includes/connect.php");
		$username = preg_replace('#[^a-z0-9]#i', '', $_POST['usernamecheck']);
		$sql = "SELECT Username FROM User WHERE Username='$username' LIMIT 1";
	    $query = mysqli_query($conn, $sql); 
	    $uname_check = mysqli_num_rows($query);
	    if (strlen($username) < 3 || strlen($username) > 16) {
		    echo '<strong style="color:#F00;">3 - 16 characters please</strong>';
		    exit();
	    }
		if (is_numeric($username[0])) {
		    echo '<strong style="color:#F00;">Usernames must begin with a letter</strong>';
		    exit();
	    }
	    if ($uname_check < 1) {
		    echo '<strong style="color:#009900;">' . $username . ' is OK</strong>';
		    exit();
	    } else {
		    echo '<strong style="color:#F00;">' . $username . ' is taken</strong>';
		    exit();
	    }
	}
?>

<?php
	// Ajax calls this REGISTRATION code to execute
	if(isset($_POST["u"])){
		// CONNECT TO THE DATABASE
		include_once("php_includes/connect.php");
		// GATHER THE POSTED DATA INTO LOCAL VARIABLES
		$u = preg_replace('#[^a-z0-9]#i', '', $_POST['u']);
		$e = mysqli_real_escape_string($conn, $_POST['e']);
		$p = $_POST['p'];
		// GET USER IP ADDRESS
	    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
		// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
		$sql = "SELECT Username FROM User WHERE Username='$u' LIMIT 1";
	    $query = mysqli_query($conn, $sql); 
		$u_check = mysqli_num_rows($query);
		// -------------------------------------------
		$sql = "SELECT Email FROM User WHERE Email='$e' LIMIT 1";
	    $query = mysqli_query($conn, $sql); 
		$e_check = mysqli_num_rows($query);
		// FORM DATA ERROR HANDLING
		if($u == "" || $e == "" || $p == ""){
			echo "The form submission is missing values.";
	        exit();
		} else if ($u_check > 0){ 
	        echo "The username you entered is alreay taken";
	        exit();
		} else if ($e_check > 0){ 
	        echo "That email address is already in use in the system";
	        exit();
		} else if (strlen($u) < 3 || strlen($u) > 16) {
	        echo "Username must be between 3 and 16 characters";
	        exit(); 
	    } else if (is_numeric($u[0])) {
	        echo 'Username cannot begin with a number';
	        exit();
	    } else {
		// END FORM DATA ERROR HANDLING
		    // Begin Insertion of data into the database
			// Hash the password and apply your own mysterious unique salt
			$p_hash = md5($p);
	    					//dodgy password method
								//$cryptpass = crypt($p);
								//include_once ("php_includes/randStrGen.php");
								//$p_hash = randStrGen(20)."$cryptpass".randStrGen(20);

			// Add user info into the database table for the main site table
			$sql = "INSERT INTO User (Username, Email, Password)       
			        VALUES('$u','$e','$p_hash')";
			$query = mysqli_query($conn, $sql); 
			$uid = mysqli_insert_id($conn);
			// Establish their row in the useroptions table
			$sql = "INSERT INTO UserOptions (UserID, Username, background) VALUES ('$uid','$u','original')";
			$query = mysqli_query($conn, $sql);
			// Create a folder in the public.html directory for the users files to be stored
			if (!file_exists("user/$u")) {
				mkdir("user/$u", 0755);
			}
			// Email the user their activation link
			$to = "$e";							 
			$from = "auto_responder@yoursitename.com";
			$subject = 'Confirm Account Details';
			$message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Collab</title></head><body style="margin:0px; font-family:Arial, Helvetica, sans-serif;"><div style="padding:10px; background:#333; font-size:24px; color:#CCC;"><a href="http://www.yoursitename.com"><img src="http://www.yoursitename.com/images/logo.png" width="36" height="30" alt="yoursitename" style="border:none; float:left;"></a>yoursitename Account Activation</div><div style="padding:24px; font-size:17px;">Hello '.$u.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="http://www.cems.uwe.ac.uk/~l3-sanders/creative_tech/php/activation.php?id='.$uid.'&u='.$u.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />Login after successful activation using your:<br />* Username: <b>'.$u.'</b></div></body></html>';
			$headers = "From: $from\n";
	        $headers .= "MIME-Version: 1.0\n";
	        $headers .= "Content-type: text/html; charset=iso-8859-1\n";
			mail($to, $subject, $message, $headers);
			echo "signup_success";
			exit();
		}
		exit();
	}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>App Name | Sign Up</title>

  <link rel="stylesheet" href="../css/layout.css" type="text/css">
  <link rel="stylesheet" href="../css/menu.css" type="text/css">
  <script src="../js/ajax.js"></script>
  <script src="../js/main.js"></script>
  <script src="../js/signup.js"></script>

</head>

<body>

<?php include_once("php_includes/navbar.php"); ?>


	<div id="holder">
		<div id="header"></div>
		
		<div id="content">
			<div id="pageheading">
				<h1>Sign Up</h1>
			</div>
			<div id="contentleft">
				<form name="signupform" id="signupform" onsubmit="return false;">
    				<div>Username: </div>
    				<input id="username" type="text" onblur="checkusername()" onkeyup="restrict('username')" maxlength="16">
    				<span id="unamestatus"></span>
    				<div>Email Address:</div>
    				<input id="email" type="text" onfocus="emptyElement('status')" onkeyup="restrict('email')" maxlength="88">
    				<div>Create Password:</div>
    				<input id="pass1" type="password" onfocus="emptyElement('status')" maxlength="160">
    				<div>Confirm Password:</div>
    				<input id="pass2" type="password" onfocus="emptyElement('status')" maxlength="160">
    				<br/><br/>
    				<button id="signupbtn" onclick="signup()">Create Account</button>
    				<span id="status"></span>
 				</form>
			</div>

			<div id="contentright"></div>
		</div>
		<div id="footer"></div>
	</div>
</body>
</html>