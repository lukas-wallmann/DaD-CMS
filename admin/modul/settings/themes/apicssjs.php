<?php
  $id=$_GET["ID"];
  $mode=$_GET["mode"];

  if(isset($_GET["code"])){
    echo mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From ".$mode."Parts WHERE ID=".$id))["Code"];
    die();
  }

  if(isset($_POST["cache"])){
    checkWritePerm();
    $cache=json_decode($_POST["cache"]);
    foreach($cache as &$save){
      mysqli_query($_dbcon,"UPDATE `".$mode."Parts` SET `Code` = '".$save[1]."' WHERE `".$mode."Parts`.`ID` = ".$save[0].";");
    }
    die();
  }

  if(isset($_POST["newName"])){
    checkWritePerm();
    $renameID=$_POST["renameID"];
    $newName=$_POST["newName"];
    mysqli_query($_dbcon,"UPDATE `".$mode."Parts` SET `Name` = '$newName' WHERE `".$mode."Parts`.`ID` = $renameID;");
  }

  if(isset($_POST["name"])){
    checkWritePerm();
    $name=$_POST["name"];
    mysqli_query($_dbcon,"INSERT INTO `".$mode."Parts` (`ID`, `Name`, `Code`, `ParentID`) VALUES (NULL, '$name', '', '$id');");
  }
  if(isset($_POST["deleteID"])){
    checkWritePerm();
    $deleteID=$_POST["deleteID"];
    mysqli_query($_dbcon,"DELETE FROM `".$mode."Parts` WHERE `scriptParts`.`ID` = ".$deleteID);
  }

  $res=mysqli_query($_dbcon,"Select * From ".$mode."Parts WHERE ParentID=".$id." ORDER BY Name");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    $part=new stdClass();
    $part->ID=$row["ID"];
    $part->Name=$row["Name"];
    array_push($tmp,$part);
  }
  echo json_encode($tmp);
?>
