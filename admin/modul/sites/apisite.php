<?php
  if(isset($_POST["action"])){

    checkWritePerm();

    if($_POST["action"]=="new"){
      $menuid=$_POST["menuid"];
      $name=mysqli_real_escape_string($_dbcon,$_POST["name"]);
      $pos=$_POST["pos"];
      mysqli_query($_dbcon,"INSERT INTO `sites` (`ID`, `Title`, `Pos`, `MenuID`, `MetaTitle`, `MetaDescription`, `MetaTags`, `Content`, `TeaserName`, `TeaserPicture`, `TeaserText`, `TeaserPrice`, `FixSiteURL`, `SiteURL`, `FixMeta`,`Layout`) VALUES (NULL, '$name', '$pos', '$menuid', '', '', '', '', '', '', '', '', '0', '', '0','0');");
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
