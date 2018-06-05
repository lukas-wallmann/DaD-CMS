<?php
	$_modul=$lang->menu[0][1];
	$_rights=json_decode($_SESSION["rights"]);
	$i=0;
	while(in_array($_modul,$_rights->disallowRead)){
		$_modul=$lang->menu[$i][1];
		$i++;
	}
	if(isset($_GET["m"]))$_modul=$_GET["m"];
	if(in_array($_modul,$_rights->disallowRead) && $_modul!="settings/users"){
		die("Permission denied");
	}else if(in_array($_modul,$_rights->disallowRead) && $_modul=="settings/users"){
		if($_GET["ID"]!=$_SESSION["id"] || $_GET["f"]!="edit")die("Permission denied");
	}
	$f="index";
	if(isset($_GET["f"]))$f=$_GET["f"];
	$_modulFile="modul/".$_modul."/".$f.".php";

	function emptydir($dir) {
		 if (is_dir($dir)) {
			 $objects = scandir($dir);
			 foreach ($objects as $object) {
				 if ($object != "." && $object != "..") {
						 unlink($dir."/".$object);
				 }
			 }
		 }
	 }

	function clearCache(){
		global $_dbcon;
		mysqli_query($_dbcon,"TRUNCATE `cache`");
		emptydir("../cache/");
	}

	function checkWritePerm(){
		global $_rights, $_modul;
		if(in_array($_modul,$_rights->disallowWrite)){
			if($_modul=="settings/users"){
				if($_GET["ID"]!=$_SESSION["id"] || $_GET["f"]!="edit")die("Permission denied");
			}else{
				die("Permission denied");
			}
		}else{
			clearCache();
		}
	}



?>
