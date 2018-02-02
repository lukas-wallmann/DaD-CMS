<?php
  $res=mysqli_query($_dbcon,"Select * From users Where ID=".$_GET["ID"]);
  $row=mysqli_fetch_assoc($res);
  print_r($row);
?>
