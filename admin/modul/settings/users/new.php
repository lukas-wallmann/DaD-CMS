<?php
  if(isset($_POST["username"])){
    checkWritePerm();
    mysqli_query($_dbcon,"INSERT INTO `users` (`ID`, `User`, `Password`, `Rights`, `Language`, `Email`) VALUES (NULL, '".$_POST["username"]."', '".password_hash($_POST["password"], PASSWORD_DEFAULT)."', '".$_POST["rights"]."', '".$_POST["lang"]."', '".$_POST["email"]."');");
    header("Location:?m=settings/users");
  }
?>

<form action="?m=settings/users&f=new&no=1" method="post" class="userform">
  <div class="form-group">
    <label for="username"><?php echo $lang->userName; ?></label>
    <input type="text" name="username" class="form-control" id="username" placeholder="<?php echo $lang->userName; ?>">
  </div>
  <div class="form-group">
    <label for="email"><?php echo $lang->email; ?></label>
    <input type="text" name="email" class="form-control" id="email" placeholder="<?php echo $lang->email; ?>">
  </div>
  <div class="form-group">
    <label for="lang"><?php echo $lang->language; ?></label>
    <?php echo langSelect(); ?>
  </div>
  <div class="form-group">
    <label for="password"><?php echo $lang->password; ?></label>
    <input type="text" name="password" class="form-control" id="password" placeholder="<?php echo $lang->password; ?>">
  </div>
  <h3><?php echo $lang->rightsRead ?></h3>
  <div class="rights read"></div>
  <h3><?php echo $lang->rightsWrite ?></h3>
  <div class="rights write"></div>
  <button type="submit" class="btn btn-primary mt-3"><?php echo $lang->createUser; ?></button>
  <input type="hidden" id="rights" name="rights">
</form>

<script>

  var menu='<?php echo json_encode($lang->menu); ?>';
  var rights=<?php echo json_encode($_SESSION["rights"]); ?>;
  menu=JSON.parse(menu);
  rights=JSON.parse(rights);

</script>
<script src="js/rights.js"></script>
