<?php
  if(isset($_GET["no"])){
    checkWritePerm();
    mysqli_query($_dbcon,"INSERT INTO `".$_GET["mode"]."` (`ID`, `Name`) VALUES (NULL, '".$_POST["name"]."');");
    $last_id = $_dbcon->insert_id;
    header("Location:?m=themes&f=editcssjs&mode=".$_GET["mode"]."&nh=1&ID=".$last_id);
    die();
  }
?>
<form action="?m=themes&f=newcssjs&mode=<?php echo $_GET["mode"]?>&no=1" method="post">
  <?php
    if($_GET["mode"]=="css"){
      echo "<h1>$lang->newCSS</h1>";
    }else{
      echo "<h1>$lang->newJavascript</h1>";
    }
   ?>
  <label class="mr-3"><?php echo $lang->name ?></label><input name="name">
  <button type="submit" class="btn btn-primary"><?php echo $lang->save ?></button>
</form>
