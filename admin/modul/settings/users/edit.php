<?php
  if(isset($_GET["msg"]) && $_GET["msg"]=="saved")echo '<div class="p-3 mb-2 bg-primary text-white">saved</div>';
  if(isset($_GET["q"])){
    if($_GET["q"]=="del"){
      die('<form action="?m=settings/users&f=edit&q=delnow&ID='.$_GET["ID"].'" method="post">
        <button type="submit" class="btn btn-danger mt-3">'.$lang->confirmDeleteUser.'</button>
      </form><a href="?m=settings/users"><button type="submit" class="btn btn-primary mt-3">'.$lang->cancel.'</button></a>');
    }
    if($_GET["q"]=="delnow"){
      checkWritePerm();
      mysqli_query($_dbcon,"DELETE FROM `users` WHERE `users`.`ID` =".$_GET["ID"]);
      header("Location:?m=settings/users");
    }
  }
  if(isset($_POST["username"])){
    checkWritePerm();
    $newRights=json_decode($_POST["rights"]);
    for($i=0; $i<count($_rights->disallowRead); $i++){
      if(!in_array($_rights->disallowRead[$i],$newRights->disallowRead))die("Nice try motherfucker!");
    }
    for($i=0; $i<count($_rights->disallowWrite); $i++){
      if(!in_array($_rights->disallowWrite[$i],$newRights->disallowWrite))die("Nice try motherfucker!");
    }
    if(isset($_POST["password"]) && $_POST["password"]!=""){
      mysqli_query($_dbcon,"UPDATE `users` SET `User` = '".$_POST["username"]."',`Password` = '".password_hash($_POST["password"], PASSWORD_DEFAULT)."', `Rights` = '".$_POST["rights"]."', `Language` = '".$_POST["lang"]."', `Email` = '".$_POST["email"]."' WHERE `users`.`ID` = ".$_GET["ID"].";");
    }else{
      mysqli_query($_dbcon,"UPDATE `users` SET `User` = '".$_POST["username"]."', `Rights` = '".$_POST["rights"]."', `Language` = '".$_POST["lang"]."', `Email` = '".$_POST["email"]."' WHERE `users`.`ID` = ".$_GET["ID"].";");
    }
    header("Location:?m=settings/users&f=edit&msg=saved&ID=".$_GET["ID"]);
  }

  $res=mysqli_query($_dbcon,"Select * From users Where ID=".$_GET["ID"]);
  $row=mysqli_fetch_assoc($res);
?>
<form action="?m=settings/users&f=edit&no=1&ID=<?php echo $_GET["ID"]; ?>" method="post" class="userform">
  <div class="form-group">
    <label for="username"><?php echo $lang->userName; ?></label>
    <input value="<?php echo $row["User"]; ?>" type="text" name="username" class="form-control" id="username" placeholder="<?php echo $lang->userName; ?>">
  </div>
  <div class="form-group">
    <label for="email"><?php echo $lang->email; ?></label>
    <input value="<?php echo $row["Email"]; ?>" type="text" name="email" class="form-control" id="email" placeholder="<?php echo $lang->email; ?>">
  </div>
  <div class="form-group">
    <label for="lang"><?php echo $lang->language; ?></label>
    <?php echo langSelect($row["Language"]); ?>
  </div>
  <div class="form-group">
    <label for="password"><?php echo $lang->newPassword; ?></label>
    <input type="text" name="password" class="form-control" id="password" placeholder="<?php echo $lang->password; ?>">
  </div>
  <h3><?php echo $lang->rightsRead ?></h3>
  <div class="rights read"></div>
  <h3><?php echo $lang->rightsWrite ?></h3>
  <div class="rights write"></div>
  <button type="submit" class="btn btn-primary mt-3"><?php echo $lang->save; ?></button>
  <input type="hidden" id="rights" name="rights">
</form>
<form action="?m=settings/users&f=edit&q=del&ID=<?php echo $_GET["ID"];?>" method="post">
  <button type="submit" class="btn btn-danger mt-3"><?php echo $lang->deleteUser; ?></button>
</form>

<script>

  var menu='<?php echo json_encode($lang->menu); ?>';
  var rights=<?php echo json_encode($_SESSION["rights"]); ?>;
  var curRights=<?php echo json_encode($row["Rights"]); ?>;
  menu=JSON.parse(menu);
  rights=JSON.parse(rights);
  curRights=JSON.parse(curRights);
</script>
<script src="js/rights.js"></script>
