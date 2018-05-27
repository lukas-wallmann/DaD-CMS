<?php
  $res=mysqli_query($_dbcon,"SELECT * FROM `themePlugins` WHERE `ThemeID`=".$_GET["ID"]);
  while($row=mysqli_fetch_assoc($res)){
    echo mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT * FROM `plugins` WHERE `ID`=".$row["PluginID"]))["Code"]."\n";
  }
?>
