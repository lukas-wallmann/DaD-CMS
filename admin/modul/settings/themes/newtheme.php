<?php
  if(isset($_GET["no"])){
    echo "saving action";
    checkWritePerm();
    mysqli_query($_dbcon,"INSERT INTO `theme` (`ID`, `Name`, `Code`) VALUES (NULL, '".$_POST["name"]."', '');");
    $last_id = $_dbcon->insert_id;
    header("Location:?m=settings/themes&f=edittheme&nh=1&id=".$last_id);
    die();
  }
?>
<form action="?m=settings/themes&f=newtheme&no=1" method="post">
  <h1><?php echo $lang->newTheme ?></h1>
  <label class="mr-3"><?php echo $lang->name ?></label><input name="name">
  <button type="submit" class="btn btn-primary"><?php echo $lang->save ?></button>
</form>
