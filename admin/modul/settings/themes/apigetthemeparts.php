<?php
  $ID=$_GET["ID"];
  $table=isset($_GET["table"]) ? $_GET["table"] : "themeParts";

  if(isset($_POST["name"])){
    $name=$_POST["name"];
    mysqli_query($_dbcon,"INSERT INTO `$table` (`ID`, `ThemeID`, `Name`, `Code`) VALUES (NULL, $ID, '$name', '');");
  }
  if(isset($_POST["newname"])){
    $name=$_POST["newname"];
    $partID=$_POST["partID"];
    mysqli_query($_dbcon,"UPDATE `$table` SET `Name` = '$name' WHERE `$table`.`ID` = $partID;");
  }
  if(isset($_POST["deleteID"])){
      $deleteID=$_POST["deleteID"];
      mysqli_query($_dbcon,"DELETE FROM `$table` WHERE `$table`.`ID` = $deleteID");
  }

  $res=mysqli_query($_dbcon,"SELECT * FROM `$table` WHERE `ThemeID`=$ID ORDER BY `Name`");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    $r=new stdClass();
    $r->ID=$row["ID"];
    $r->Name=$row["Name"];
    array_push($tmp,$r);
  }
  echo json_encode($tmp);
?>
