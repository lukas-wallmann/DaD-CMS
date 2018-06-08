<?php
  $siterow=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * FROM newsletter WHERE ID=".$_GET["ID"]));
  if(isset($_GET["no"])){
    checkWritePerm();
    $id=$_GET["ID"];
    $title=mysqli_real_escape_string($_dbcon,$_POST["title"]);
    $layout=mysqli_real_escape_string($_dbcon,$_POST["layout"]);
    $content=mysqli_real_escape_string($_dbcon,$_POST["content"]);
    mysqli_query($_dbcon,"UPDATE `newsletter` SET `Title` = '$title', `Content` = '$content', `Layout` = '$layout' WHERE `newsletter`.`ID` = $id;");
    header("Location:?m=newsletter/newsletter");
  }
 ?>
<form action="?m=newsletter/newsletter&f=editnewsletter&no=1&ID=<?php echo $_GET["ID"]?>" method="post">
<div class="fixedtop"><button type="submit" class="btn btn-primary save"><?php echo $lang->save ?></button></div>
<label><?php echo $lang->title ?></label><input class="form-control mb-3" name="title" value="<?php echo $siterow["Title"] ?>">
<label><?php echo $lang->layout ?></label>
<select id="layout" name="layout" class="form-control mb-3">
  <?php
    $res=mysqli_query($_dbcon,"Select * FROM theme WHERE LayoutFor='newsletter'");
    while($row=mysqli_fetch_assoc($res)){
      $selected="";
      if($row["ID"]==$siterow["Layout"])$selected=" selected";
      echo "<option value='".$row["ID"]."'>".$row["Name"]."</option>";
    }
   ?>
</select>
<textarea style="display:none" id="contents" name="content"><?php echo $siterow["Content"]?></textarea>
</form>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/sites.css">
<link rel="stylesheet" href="css/quill.snow.css">
<script src="js/quill.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/touchpunch.js"></script>
<script src="js/filemanager.js"></script>
<script src="js/formmanager.js"></script>
<script src="js/videomanager.js"></script>
<script src="js/cmd.js"></script>
<script>
var editMode="newsletter";
var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>
<script src="js/sites.core.js"></script>
<style>
.invalid {
    border: 1px solid red;
}
.fixedtop {
    position: fixed;
    top: 56px;
    left: 0;
    z-index: 100;
    background: #fff;
    padding: 10px 20px;
    width: 100%;
    box-shadow: 0 0 5px;
}

form {
    padding: 43px 0 0 0;
}
</style>
