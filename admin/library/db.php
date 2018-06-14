<?php

	$_dbcon=mysqli_connect($_DB[0],$_DB[1],$_DB[2],$_DB[3]);
	mysqli_set_charset($_dbcon,"utf8");

	if (mysqli_connect_errno()) {
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
 	}

	function prepareToCopy($row,$tablename,$field="",$val=""){
		global $_dbcon;
		$keys=array();
		$values=array();
		$fields="";
		$vals="";
		foreach ($row as $key => $value) {
			if($key!="ID"){
				if($key!=$field){
					array_push($keys,$key);
					array_push($values,mysqli_real_escape_string($_dbcon,$value));
				}else{
					array_push($keys,$key);
					array_push($values,mysqli_real_escape_string($_dbcon,$val));
				}

			}else{
				array_push($keys,$key);
				array_push($values,NULL);
			}
		}
		for($i=0; $i<count($keys); $i++){
			if($i!=0)$fields.=", ";
			$fields.="`".$keys[$i]."`";
			if($i!=0)$vals.=",";
			if($values[$i]==NULL){
				$vals.="NULL";
			}else{
				$vals.="'".$values[$i]."'";
			}
		}
		return "INSERT INTO `$tablename` ($fields) VALUES ($vals);";

	}

?>
