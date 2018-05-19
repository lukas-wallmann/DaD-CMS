<?php

  $parts=json_decode($_POST["parts"]);

  for($i=0; $i<count($parts); $i++){
    $ID=$parts[$i][0];
    $table=$parts[$i][1];
    $code=$parts[$i][2];
    mysqli_query($_dbcon,"UPDATE `$table` SET `Code` = '$code' WHERE `$table`.`ID` = $ID");
  }

?>
