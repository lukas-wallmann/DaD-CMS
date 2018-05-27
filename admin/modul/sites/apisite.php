<?php
  if(isset($_POST["action"])){

    checkWritePerm();

    if($_POST["action"]=="new"){
      $menuid=$_POST["menuid"];
      $name=mysqli_real_escape_string($_dbcon,$_POST["name"]);
      $pos=$_POST["pos"];
      mysqli_query($_dbcon,"INSERT INTO `sites` (`ID`, `Title`, `Pos`, `MenuID`, `MetaTitle`, `MetaTags`, `Content`, `TeaserName`, `TeaserText`, `TeaserPrice`) VALUES (NULL, '$name', '$pos', '$menuid', '', '', '', '', '', '');");
      echo $_dbcon->insert_id;
    }

    if($_POST["action"]=="reposition"){
      $pos=json_decode($_POST["pos"]);
      foreach($pos as &$set){
        mysqli_query($_dbcon,"UPDATE `sites` SET `Pos` = '".$set[1]."' WHERE `sites`.`ID` = ".$set[0].";");
      }
    }

  }

?>
