<?php
  if(isset($_POST["mode"])){
    checkWritePerm();
    mysqli_query($_dbcon,"UPDATE `settings` SET `Value` = '".$_POST["mode"].":".$_POST["width"]."x".$_POST["height"]."' WHERE `settings`.`ID` = ".$_GET[ID].";");
    header("Location:?m=settings/imageformats");
    die("no more to say");
  }
 ?>
<form action="?m=settings/imageformats&f=edit&no=1&ID=<?php echo $_GET["ID"]; ?>" method="post">
  <?php
    $res=mysqli_query($_dbcon,"Select * from settings Where `Group`='imageformats' AND `ID`=".$_GET["ID"]);
    $row=mysqli_fetch_assoc($res);
    echo "<h2 class='mb-3'>".$row["Name"]."</h2>";
    $m=explode(":",$row["Value"]);
    $f=explode("x",$m[1]);
    $m=$m[0];
    echo select("mode",array(array($lang->imageformatFitin,"fitin"),array($lang->imageformatCut,"cut")),$m);
  ?>
  <div class="row mt-3">
    <div class="col">
      <input name="width" type="text" class="form-control" value="<?php echo $f[0]; ?>" placeholder="<?php echo $lang->imageformatWidth; ?>">
    </div>
    <div class="col">
      <input name="height" type="text" class="form-control" value="<?php echo $f[1]; ?>" placeholder="<?php echo $lang->imageformatHeight; ?>">
    </div>
  </div>
  <button type="submit" class="btn btn-primary mt-3"><?php echo $lang->save; ?></button>
</form>
