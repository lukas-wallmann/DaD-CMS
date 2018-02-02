<?php
	$_modul=$lang->menu[0][1];
	if(isset($_GET["m"]))$_modul=$_GET["m"];
	$f="index";
	if(isset($_GET["f"]))$f=$_GET["f"];
	$_modulFile="modul/".$_modul."/".$f.".php";
?>
