<?php 
	include_once("connect.php");
	function add_post($db_id,$title){
		$sql = "INSERT INTO Posts (UserID, Title, Stamp) 
				values ($db_id, '". mysql_real_escape_string($title). "',now())";
		$result = mysql_query($sql);
	}
?>