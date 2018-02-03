<?php
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	if(isset($_SESSION["lang"]))$lang=$_SESSION["lang"];
	$langfile="languages/".$lang.".php";
	if(file_exists($langfile)){
		include $langfile;
	}else{
		include "languages/en.php";
	}
?>
