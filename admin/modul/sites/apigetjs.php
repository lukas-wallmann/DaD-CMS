<?php
  $res=mysqli_query($_dbcon,"SELECT * FROM `plugins` WHERE `LayoutID`=".$_GET["ID"]." ORDER BY Pos");
  while($row=mysqli_fetch_assoc($res)){
    $str=$row["PluginCode"]."\n";
    $str=str_replace("{{name}}",$row["Name"],$str);
    $str=str_replace("{{id}}",$row["ID"],$str);
    echo $str;
  }
?>
