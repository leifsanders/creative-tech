<?php
	include_once("php_includes/check_login_status.php");
	// If user is already logged in, direct them to their profile page
	if($user_ok == true){
		header("location: user.php?u=".$_SESSION["Username"]);
	    exit();
	}
?>

<?php
	// AJAX CALLS THIS LOGIN CODE TO EXECUTE
	if(isset($_POST["u"])){
		// CONNECT TO THE DATABASE
		include_once("php_includes/connect.php");
		// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
		$u = mysqli_real_escape_string($conn, $_POST['u']);
		$p = md5($_POST['p']);
		// GET USER IP ADDRESS
	    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
		// FORM DATA ERROR HANDLING
		if($u == "" || $p == ""){
			echo "login_failed";
	        exit();
		} else {
		// END FORM DATA ERROR HANDLING
			$sql = "SELECT UserID, Username, Password FROM User WHERE Username='$u' AND activated='1' LIMIT 1";
	        $query = mysqli_query($conn, $sql);
	        $row = mysqli_fetch_row($query);
			$db_id = $row[0];
			$db_username = $row[1];
	        $db_pass_str = $row[2];
			if($p != $db_pass_str){
				echo "login_failed";
	            exit();
			} else {
				// CREATE THEIR SESSIONS AND COOKIES
				$_SESSION['UserID'] = $db_id;
				$_SESSION['Username'] = $db_username;
				$_SESSION['Password'] = $db_pass_str;
				setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
				setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
	    		setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE); 
				// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
				$sql = "UPDATE User SET IP='$ip', LastLogin=now() WHERE Username='$db_username' LIMIT 1";
	            $query = mysqli_query($conn, $sql);
				echo $db_username;
			    exit();
			}
		}
		exit();
	}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>App Name | Log In</title>

  <link rel="stylesheet" href="../css/layout.css" type="text/css">
  <link rel="stylesheet" href="../css/menu.css" type="text/css">
  <script src="../js/ajax.js"></script>
  <script src="../js/main.js"></script>
  <script src="../js/login.js"></script>

</head>
<body>
<?php include_once("php_includes/navbar.php"); ?>


	<div id="holder">
		<div id="header"></div>
		
		<div id="content">
			<div id="pageheading">
				<h1>Log In</h1>
			</div>
			<div id="contentleft">
				<form id="loginform" onsubmit="return false;">
					<div>Username:</div>
					<input type="text" id="username" onfocus="emptyElement('status')" maxlength="88">
					<div>Password:</div>
					<input type="password" id="password" onfocus="emptyElement('status')" maxlength="100">
					<br /><br />
					<button id="loginbtn" onclick="login()">Log In</button> 
					<p id="status"></p>
				</form>
			</div>

			<div id="contentright"></div>
		</div>
		<div id="footer"></div>
	</div>
</body>
</html>