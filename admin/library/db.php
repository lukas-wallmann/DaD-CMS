<?php

	$_dbcon=mysqli_connect($_DB[0],$_DB[1],$_DB[2],$_DB[3]);
	mysqli_set_charset($_dbcon,"utf8");

	if (mysqli_connect_errno()) {
 		echo "Failed to connect to MySQL: " . mysqli_connect_error();
 	}

	function prepareToCopy($row,$tablename,$field="",$val="",$field2="",$val2=""){
		global $_dbcon;
		$keys=array();
		$values=array();
		$fields="";
		$vals="";
		foreach ($row as $key => $value) {
			if($key!="ID"){
				array_push($keys,$key);
				if($key!=$field && $key!=$field2){
					array_push($values,mysqli_real_escape_string($_dbcon,$value));
				}else{
					if($key==$field){
						array_push($values,mysqli_real_escape_string($_dbcon,$val));
					}else{
						array_push($values,mysqli_real_escape_string($_dbcon,$val2));
					}

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
			if($values[$i]===NULL){
				$vals.="NULL";
			}else{
				$vals.="'".$values[$i]."'";
			}
		}
		return "INSERT INTO `$tablename` ($fields) VALUES ($vals);";

	}

?>
