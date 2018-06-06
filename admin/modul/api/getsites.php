<?php
  $res=mysqli_query($_dbcon,"Select * From sites");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    array_push($tmp,array($row["Title"],$row["ID"]));
  }
  echo json_encode($tmp);
 ?>
