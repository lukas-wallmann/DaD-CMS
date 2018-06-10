<?php
  $table=$_POST["table"];
  $id=$_POST["ID"];

  if($_GET["action"]=="delete"){
    checkWritePerm();
    mysqli_query($_dbcon,"DELETE FROM `$table` WHERE `$table`.`ID` = $id;");
  }
  if($_GET["action"]=="rename"){
    checkWritePerm();
    $name=$_POST["newname"];
    mysqli_query($_dbcon,"UPDATE `$table` SET `Name` = '$name' WHERE `$table`.`ID` = $id;");
  }
?>
