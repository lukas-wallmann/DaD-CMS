<?php
  if(isset($_GET["no"])){
    checkWritePerm();
    mysqli_query($_dbcon,"INSERT INTO `plugins` (`Name`) VALUES ('".$_POST["name"]."');");
    $last_id = $_dbcon->insert_id;
    header("Location:?m=settings/themes&f=editplugin&nh=1&ID=".$last_id);
    die();
  }
?>
<form action="?m=settings/themes&f=newplugin&no=1" method="post">
  <h1><?php echo $lang->newPlugin ?></h1>
  <label class="mr-3"><?php echo $lang->name ?></label><input name="name">
  <button type="submit" class="btn btn-primary"><?php echo $lang->save ?></button>
</form>
