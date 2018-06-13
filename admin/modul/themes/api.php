<?php

  $id=$_GET["ID"];


  if(isset($_POST["action"])){
    checkWritePerm();
    $action=$_POST["action"];

    if($action=="addnew"){
      $name=mysqli_real_escape_string($_dbcon,$_POST["name"]);
      $pos=$_POST["pos"];
      $to=$_POST["to"];
      if($to=="themeParts"){
        mysqli_query($_dbcon,"INSERT INTO `themeParts` (`ID`, `ThemeID`, `Name`, `Code`, `Pos`) VALUES (NULL, '$id', '$name', '', '$pos');");
      }
      if($to=="plugins"){
        mysqli_query($_dbcon,"INSERT INTO `plugins` (`ID`, `Name`, `Code`, `PluginCode`, `LayoutID`, `Pos`) VALUES (NULL, '$name', NULL, NULL, '$id', '$pos');");
      }
      if($to=="css"){
        mysqli_query($_dbcon,"INSERT INTO `css` (`ID`, `Name`, `LayoutID`, `Pos`) VALUES (NULL, '$name', '$id', '$pos');");
      }
      if($to=="script"){
        mysqli_query($_dbcon,"INSERT INTO `script` (`ID`, `Name`, `LayoutID`, `Pos`) VALUES (NULL, '$name', '$id', '$pos');");
      }
      if($to=="cssParts" || $to=="scriptParts"){
        $parentID=$_POST["parentID"];
        mysqli_query($_dbcon,"INSERT INTO `$to` (`ID`, `Name`, `Code`, `ParentID`, `Pos`) VALUES (NULL, '$name', '', '$parentID', '$pos');");
      }
      $last_id = $_dbcon->insert_id;
      echo $last_id;
      die();
    }

    if($action=="deletetheme"){
      $querys="";
      $querys.="DELETE FROM `theme` WHERE `theme`.`ID` = ".$id.";";
      $querys.="DELETE FROM `themeParts` WHERE `themeParts`.`ThemeID` = ".$id.";";
      $querys.="DELETE FROM `plugins` WHERE `plugins`.`LayoutID` = ".$id.";";
      $querys.="DELETE FROM `css` WHERE `css`.`LayoutID` = ".$id.";";
      $querys.="DELETE FROM `script` WHERE `script`.`LayoutID` = ".$id.";";
      $res=mysqli_query($_dbcon,"Select * From css WHERE LayoutID=".$id);
      while($row=mysqli_fetch_assoc($res)){
        $querys.="DELETE FROM `cssParts` WHERE `cssParts`.`ParentID` = ".$row["ID"].";";
      }
      $res=mysqli_query($_dbcon,"Select * From script WHERE LayoutID=".$id);
      while($row=mysqli_fetch_assoc($res)){
        $querys.="DELETE FROM `scriptParts` WHERE `scriptParts`.`ParentID` = ".$row["ID"].";";
      }
      $querys=explode(";",$querys);
      foreach($querys as &$query){
         mysqli_query($_dbcon,$query);
      }
      die();

    }

    if($action=="renametheme"){
      $name=mysqli_real_escape_string($_dbcon,$_POST["name"]);
      mysqli_query($_dbcon,"UPDATE `theme` SET `Name` = '$name' WHERE `theme`.`ID` = $id;");
      die();
    }

    if($action=="delete"){
      $table=$_POST["table"];
      $ID=$_POST["id"];
      mysqli_query($_dbcon,"DELETE FROM `$table` WHERE `$table`.`ID` = $ID");
      if($table=="css" || $table=="script"){
        mysqli_query($_dbcon,"DELETE FROM `".$table."Parts` WHERE `".$table."Parts`.`ParentID` = $ID");
      }
    }

    if($action=="rename"){
      $table=$_POST["table"];
      $ID=$_POST["id"];
      $name=mysqli_real_escape_string($_dbcon,$_POST["name"]);
      mysqli_query($_dbcon,"UPDATE `$table` SET `Name` = '$name' WHERE `$table`.`ID` = $ID;");
    }

    if($action=="save"){
      $parts=json_decode($_POST["parts"]);
      foreach($parts as &$part){
        $code=mysqli_real_escape_string($_dbcon,$part->code);
        $id=$part->id;
        $table=$part->table;
        $field=$part->field;
        mysqli_query($_dbcon,"UPDATE `$table` SET `$field` = '$code' WHERE `$table`.`ID` = $id;");
      }
    }

    if($action=="reposition"){
      $table=$_POST["table"];
      $arr=json_decode($_POST["arr"]);
      $index=0;
      foreach($arr as &$id){
        mysqli_query($_dbcon,"UPDATE `$table` SET `Pos` = '$index' WHERE `$table`.`ID` = $id;");
        $index+=1;
      }
    }

    if($action=="import"){
      $temp=array();
      $tmp=json_decode($_POST["tmp"]);
      foreach($tmp as &$plugin){
        $code=mysqli_real_escape_string($_dbcon,$plugin->code);
        $pluginCode=mysqli_real_escape_string($_dbcon,$plugin->pluginCode);
        $name=mysqli_real_escape_string($_dbcon,$plugin->name);
        $pos=$plugin->pos;
        mysqli_query($_dbcon,"INSERT INTO `plugins` (`ID`, `Name`, `Code`, `PluginCode`, `LayoutID`, `Pos`) VALUES (NULL, '$name', '$code', '$pluginCode', '$id', '$pos');");
        $t=new stdClass();
        $t->name=$name;
        $t->id=$_dbcon->insert_id;
        array_push($temp,$t);
      }
      echo json_encode($temp);
      die();
    }

  }

  if(isset($_POST["a"])){
    if($_POST["a"]=="getCode"){
      $table=$_POST["table"];
      $ID=$_POST["id"];
      $field=$_POST["field"];
      echo mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * FROM $table WHERE ID=$ID"))[$field];
      die();
    }
    if($_POST["a"]=="listplugins"){
      $res=mysqli_query($_dbcon,"SELECT * FROM `theme` WHERE `ID` != ".$id);
      $tmp=array();
      $not=$_POST["not"];
      while($row=mysqli_fetch_assoc($res)){
        $layout=new stdClass();
        $layout->name=$row["Name"];
        $layout->plugins=array();
        //echo "Select * FROM plugins WHERE LayoutID=".$row["ID"]." AND `Name` NOT IN ($not)";
        //die();
        $res2=mysqli_query($_dbcon,"Select * FROM plugins WHERE LayoutID=".$row["ID"]." AND `Name` NOT IN ($not)");
        while($row2=mysqli_fetch_assoc($res2)){
          $plugin=new stdClass();
          $plugin->name=$row2["Name"];
          $plugin->code=$row2["Code"];
          $plugin->pluginCode=$row2["PluginCode"];
          array_push($layout->plugins,$plugin);
        }
        if(count($layout->plugins)>0)array_push($tmp,$layout);
      }
      echo json_encode($tmp);
      die();
    }
  }

  $data=new stdClass();

/* Plugins */
  $res=mysqli_query($_dbcon,"SELECT * FROM `plugins` WHERE LayoutID=".$id." ORDER BY Pos");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    $elm=new stdClass();
    $elm->Name=$row["Name"];
    $elm->ID=$row["ID"];
    array_push($tmp,$elm);
  }

  $data->plugins=$tmp;

/* Themeparts */
  $res=mysqli_query($_dbcon,"SELECT * FROM `themeParts` WHERE ThemeID=".$id." ORDER BY Pos");
  $tmp=array();
  while($row=mysqli_fetch_assoc($res)){
    $elm=new stdClass();
    $elm->Name=$row["Name"];
    $elm->ID=$row["ID"];
    array_push($tmp,$elm);
  }

  $data->themeParts=$tmp;

  /* scripts */
    $res=mysqli_query($_dbcon,"SELECT * FROM `script` WHERE LayoutID=".$id." ORDER BY Pos");
    $tmp=array();
    while($row=mysqli_fetch_assoc($res)){
      $elm=new stdClass();
      $elm->Name=$row["Name"];
      $elm->ID=$row["ID"];
      $elm->parts=array();
      $res2=mysqli_query($_dbcon,"SELECT * FROM `scriptParts` WHERE ParentID=".$elm->ID." ORDER BY Pos");
      while($row2=mysqli_fetch_assoc($res2)){
        $elm2=new stdClass();
        $elm2->Name=$row2["Name"];
        $elm2->ID=$row2["ID"];
        array_push($elm->parts,$elm2);
      }
      array_push($tmp,$elm);
    }

    $data->script=$tmp;

    /* css */
      $res=mysqli_query($_dbcon,"SELECT * FROM `css` WHERE LayoutID=".$id." ORDER BY Pos");
      $tmp=array();
      while($row=mysqli_fetch_assoc($res)){
        $elm=new stdClass();
        $elm->Name=$row["Name"];
        $elm->ID=$row["ID"];
        $elm->parts=array();
        $res2=mysqli_query($_dbcon,"SELECT * FROM `cssParts` WHERE ParentID=".$elm->ID." ORDER BY Pos");
        while($row2=mysqli_fetch_assoc($res2)){
          $elm2=new stdClass();
          $elm2->Name=$row2["Name"];
          $elm2->ID=$row2["ID"];
          array_push($elm->parts,$elm2);
        }
        array_push($tmp,$elm);
      }

      $data->css=$tmp;


  echo json_encode($data);


?>
