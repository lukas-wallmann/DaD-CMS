<?php
  $res=mysqli_query($_dbcon,"SELECT * FROM `themePlugins` WHERE `ThemeID`=".$_GET["ID"]);
  while($row=mysqli_fetch_assoc($res)){
    $row2=mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT * FROM `plugins` WHERE `ID`=".$row["PluginID"]));
    $str=$row2["Code"]."\n";
    $str=str_replace("{{name}}",$row2["Name"],$str);
    $str=str_replace("{{id}}",$row2["ID"],$str);
    echo $str;
  }
?>
