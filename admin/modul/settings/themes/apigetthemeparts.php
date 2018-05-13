<?php
  $ID=$_GET["ID"];
  if(isset($_POST["name"])){
    $name=$_POST["name"];
    mysqli_query($_dbcon,"INSERT INTO `themeParts` (`ID`, `ThemeID`, `Name`, `Code`) VALUES (NULL, $ID, '$name', '');");
  }
  if(isset($_POST["newname"])){
    $name=$_POST["newname"];
    $partID=$_POST["partID"];
    mysqli_query($_dbcon,"UPDATE `themeParts` SET `Name` = '$name' WHERE `themeParts`.`ID` = $partID;");
  }
  if(isset($_POST["deleteID"])){
      $deleteID=$_POST["deleteID"];
      mysqli_query($_dbcon,"DELETE FROM `themeParts` WHERE `themeParts`.`ID` = $deleteID");
  }

  $res=mysqli_query($_dbcon,"SELECT * FROM `themeParts` WHERE `ThemeID`=$ID ORDER BY `Name`");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    $r=new stdClass();
    $r->ID=$row["ID"];
    $r->Name=$row["Name"];
    array_push($tmp,$r);
  }
  echo json_encode($tmp);
?>
