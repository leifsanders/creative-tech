<?php
	include_once("php_includes/check_login_status.php");
	// Initialize any variables that the page might echo
	$u = "";
	// Make sure the _GET username is set, and sanitize it
	if(isset($_GET["u"])){
		$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	} else {
	    header("location: http://www.cems.uwe.ac.uk/~l3-sanders/creative_tech/php/login.php");
	    exit();	
	}
	// Select the member from the users table
	$sql = "SELECT * FROM User WHERE Username='$u' AND activated='1' LIMIT 1";
	$user_query = mysqli_query($conn, $sql);
	// Now make sure that user exists in the table
	$numrows = mysqli_num_rows($user_query);
	if($numrows < 1){
		echo "That user does not exist or is not yet activated, press back";
	    exit();	
	}
	// Check to see if the viewer is the account owner
	$isOwner = "no";
	if($u == $log_username && $user_ok == true){
		$isOwner = "yes";
	}
	// Fetch the user row from the query above
	while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
		$profile_id = $row["UserID"];
		$lastlogin = $row["LastLogin"];
		}
	
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>App Name | <?php echo $u; ?></title>

  <link rel="stylesheet" href="../css/layout.css" type="text/css">
  <link rel="stylesheet" href="../css/menu.css" type="text/css">
  <script src="../js/ajax.js"></script>
  <script src="../js/main.js"></script>

</head>
<body>
<?php include_once("php_includes/navbar2.php"); ?>


	<div id="holder">
		<div id="header"></div>
		
		<div id="content">
			<div id="pageheading">
				<h1><?php echo $u; ?></h1>
			</div>
			<div id="contentleft">
				
			</div>

			<div id="contentright"></div>
		</div>
		<div id="footer"></div>
	</div>
</body>
</html>