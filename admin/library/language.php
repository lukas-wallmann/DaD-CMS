<?php
	$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
	$langfile="languages/".$lang.".php";
	if(file_exists($langfile)){
		include $langfile;
	}else{
		include "languages/en.php";
	}
?>