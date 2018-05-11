<?php
  header("Content-Type: plain/text");
  $ID=$_GET["ID"];
  $table=$_GET["table"];
  $res=mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT `Code` FROM `$table` WHERE `ID`=$ID"));
  echo $res["Code"];
?>
