<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	include "library/_core.php";

	if(isset($_GET["no"])){ //no = no output, its for saving operations
		include $_modulFile;
	}else{
		include "includes/header.php";
		include $_modulFile;
		include "includes/footer.php";
	}

?>
