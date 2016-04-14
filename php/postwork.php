<?php
	include_once('php_includes/connect.php');
	include_once("php_includes/check_login_status.php");
	include_once('php_includes/posttodatabase.php');
	$_SESSION['UserID'] = $log_id;
?>

<?php
	if (isset($_SESSION['message'])){
		echo "<b>". $_SESSION['message']."</b>";
		unset($_SESSION['message']);
	}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">

  <title>App Name | Post an Opportunity</title>

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
				<h1>Post an Opportunity</h1>
			</div>

			<div id="contentleft">
				<form method='post' action='php_includes/add.php'>
					<p>Your status:</p>
					<textarea name='Title' rows='5' cols='40' wrap=VIRTUAL></textarea>
					<p><input type='submit' value='submit'/></p>
				</form>
			</div>

			<div id="contentright"></div>
		</div>
		<div id="footer"></div>
	</div>
</body>
</html>
