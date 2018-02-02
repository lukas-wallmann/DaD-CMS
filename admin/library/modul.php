<?php 
	$_modul=$lang->menu[0][1];
	if(isset($_GET["m"]))$_modul=$_GET["m"];
	function getModul(){
		global $_modul;
		$f="index.php";
		if(isset($_GET["f"]))$f=$_GET["f"];
		include "modul/".$_modul."/".$f;
	}
?>