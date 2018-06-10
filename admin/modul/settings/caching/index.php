<?php
  if(isset($_POST["cache"])){
    checkWritePerm();
    $cache=$_POST["cache"];
    mysqli_query($_dbcon,"UPDATE `settings` SET `Value` = '$cache' WHERE `settings`.`Name` = 'cache';");
    echo '<div class="bg-danger text-white mb-3 p-2">'.$lang->saved.'</div>';
  }
 ?>

<form action="?m=settings/caching" method="post">
<?php
  $caching=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From settings WHERE Name='cache'"))["Value"];
  echo "<p>".$lang->cacheInfo."</p>";
  echo "<select name='cache' class='form-control mb-3'>";
  $selected="";
  if($caching=="db")$selected=" selected";
  echo '<option value="db"'.$selected.'>'.$lang->useDbCache.'</option>';
  $selected="";
  if($caching=="file")$selected=" selected";
  echo '<option value="file"'.$selected.'>'.$lang->useFileCache.'</option>';
  echo "</select>";
 ?>
 <button type="submit" class="btn btn-primary"><?php echo $lang->save ?></button>
</form>
