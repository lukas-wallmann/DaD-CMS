<?php
  $res=mysqli_query($_dbcon,"Select * From theme Where LayoutFor='newsletter'");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    array_push($tmp,array($row["Name"],$row["ID"]));
  }
  echo json_encode($tmp);
 ?>
