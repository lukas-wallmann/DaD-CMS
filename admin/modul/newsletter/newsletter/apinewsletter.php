<?php
  if(isset($_POST["action"])){

    checkWritePerm();

    if($_POST["action"]=="new"){
      $name=mysqli_real_escape_string($_dbcon,$_POST["name"]);
      mysqli_query($_dbcon,"INSERT INTO `newsletter` (`ID`, `Title`, `Content`,`Layout`, `SentTo`) VALUES (NULL, '$name', '[]', '0', '0');");
      echo $_dbcon->insert_id;
    }

  }

?>
