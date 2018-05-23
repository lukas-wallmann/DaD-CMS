<?php
  $id=$_GET["ID"];

  if(isset($_POST["cache"])){
    checkWritePerm();
    $cache=json_decode($_POST["cache"]);
    foreach($cache as &$insert){
      $template=mysqli_real_escape_string($_dbcon,$insert->Template);
      $pluginID=$insert->ID;
      mysqli_query($_dbcon,"INSERT INTO `themePlugins` (`ID`, `ThemeID`, `Code`, `PluginID`) VALUES (NULL, '".$id."', '".$template."', '".$pluginID."');");
    }
  }
  if(isset($_POST["deleteID"])){
    checkWritePerm();
    $did=$_POST["deleteID"];
    mysqli_query($_dbcon,"DELETE FROM `themePlugins` WHERE `themePlugins`.`ID` = $did;");
  }

  $res=mysqli_query($_dbcon,"SELECT * FROM `themePlugins` WHERE `ThemeID`=$id");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    $r=new stdClass();
    $r->Name=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From plugins Where ID=".$row["PluginID"]))["Name"];
    $r->ID=$row["ID"];
    //$r->Code=$row["Code"];
    $r->PluginID=$row["PluginID"];
    array_push($tmp,$r);
  }
  function cmp($a, $b){
    return strcmp($a->Name, $b->Name);
  }

  usort($tmp, "cmp");
  echo json_encode($tmp);

?>
