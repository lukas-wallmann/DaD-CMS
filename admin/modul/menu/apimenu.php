<?php

  if(isset($_POST["action"])){
    checkWritePerm();

    $id=$_POST["id"];
    $name=$_POST["name"];

    if($_POST["action"]=="new"){
      mysqli_query($_dbcon,"INSERT INTO `menus` (`ID`, `Name`, `Content`) VALUES (NULL, '$name', '[]');");
    }
    if($_POST["action"]=="delete"){
      mysqli_query($_dbcon,"DELETE FROM `menus` WHERE `menus`.`ID` = $id");
    }
    if($_POST["action"]=="rename"){
      mysqli_query($_dbcon,"UPDATE `menus` SET `Name` = '$name' WHERE `menus`.`ID` = $id;");
    }
    if($_POST["action"]=="update"){
      $content=$_POST["content"];
      mysqli_query($_dbcon,"UPDATE `menus` SET `Content` = '$content' WHERE `menus`.`ID` = $id;");
    }
  }

  $res=mysqli_query($_dbcon,"Select * FROM menus ORDER BY Name");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    array_push($tmp,$row);
  }
  echo json_encode($tmp);
?>
