<?php
	$_dbcon=mysqli_connect($_DB[0],$_DB[1],$_DB[2],$_DB[3]);
	mysqli_set_charset($_dbcon,"utf8");

	if (mysqli_connect_errno()) {
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
 	}
?>
