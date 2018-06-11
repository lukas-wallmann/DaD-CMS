<?php
  $res=mysqli_query($_dbcon,"SELECT * FROM `plugins`");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    array_push($tmp,$row);
  }
  echo json_encode($tmp);
?>
