<?php
  if(isset($_POST["Title"])){
    $row=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From sites Where ID=".$_GET["ID"]));
    mysqli_query($_dbcon,prepareToCopy($row,"sites","Title",mysqli_real_escape_string($_dbcon,$_POST["Title"]),"Language",mysqli_real_escape_string($_dbcon,$_POST["Language"])));
    header("Location:?m=sites&f=editpage&nh=1&ID=".$_dbcon->insert_id);
  }
 ?>

<form action="?m=sites&f=copy&no=1&ID=<?php echo $_GET["ID"]?>" method="post">
  <label><?php echo $lang->name ?></label>
  <?php
    $res=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From sites Where ID=".$_GET["ID"]));
   ?>
  <input class="form-control mb-3" name="Title" value="<?php echo $res["Title"]." (copy)" ?>">
  <label><?php echo $lang->language ?></label>
  <select class="form-control mb-3" name="Language">
    <?php
      $langs=mysqli_query($_dbcon,"Select * From languages");
      while($row=mysqli_fetch_assoc($langs)){
        $selected="";
        if($row["Name"]==$res["Language"])$selected=" selected";
        echo '<option value="'.$row["Name"].'"'.$selected.'>'.$row["Name"].'</option>';
      }
    ?>
  </select>
  <button class="btn btn-primary" type="submit"><?php echo $lang->save ?></button>
</form>
