<?php
  if(isset($_POST["email"])){
    $tmp=new stdClass();
    $tmp->email=$_POST["email"];
    $tmp->reply=$_POST["reply"];
    $tmp=mysqli_real_escape_string($_dbcon,json_encode($tmp));
    mysqli_query($_dbcon,"UPDATE `settings` SET `Value` = '$tmp' WHERE `settings`.`Name` = 'email';");
  }
  $res=json_decode(mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * FROM settings WHERE Name='email'"))["Value"]);
 ?>
 <form action="?m=settings/email" method="post">
   <label>E-Mail</label>
   <input class="form-control mb-3" name="email" value="<?php echo $res->email ?>">
   <label>Reply-To</label>
   <input class="form-control mb-3" name="reply" value="<?php echo $res->reply ?>">
   <button type="submit" class="btn btn-primary"><?php echo $lang->save ?></button>
 </form>
