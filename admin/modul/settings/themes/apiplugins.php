<?php
  $id=$_GET["ID"];

  $res=mysqli_query($_dbcon,"SELECT * FROM `plugins` WHERE `ID`=$id");
  $code=new stdClass();
  $row=mysqli_fetch_assoc($res);
  $code->code=$row["Code"];
  $code->template=$row["Template"];
  echo json_encode($code);

  if(isset($_POST["cache"])){
    $cache=json_decode($_POST["cache"]);
    $code=mysqli_real_escape_string ($_dbcon,$cache->code);
    $template=mysqli_real_escape_string ($_dbcon,$cache->template);
    mysqli_query($_dbcon,"UPDATE `plugins` SET `Code` = '$code', `Template` = '$template' WHERE `plugins`.`ID` = $id;");
  }

?>
