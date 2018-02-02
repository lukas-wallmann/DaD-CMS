<?php

	$_maindir=getDir();

	function getDir(){
		$url = $_SERVER['REQUEST_URI']; //returns the current URL
		$parts = explode('/',$url);
		$dir = "";
		$m=1;
		if($parts[count($parts) - 2]=="admin")$m=2;
		for ($i = 0; $i < count($parts) - $m; $i++) {
		 $dir .= $parts[$i] . "/";
		}
		return $dir;
	}
?>