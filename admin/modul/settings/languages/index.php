<?php
  if(isset($_GET["new"])){
    checkWritePerm();
    $name=mysqli_real_escape_string($_dbcon,$_GET["new"]);
    mysqli_query($_dbcon,"INSERT INTO languages (Name) SELECT * FROM (SELECT '$name') AS tmp WHERE NOT EXISTS (SELECT Name FROM languages WHERE Name = '$name') LIMIT 1;");
  };
  if(isset($_GET["delete"])){
    checkWritePerm();
    $id=$_GET["delete"];
    mysqli_query($_dbcon,"DELETE FROM `languages` WHERE `languages`.`ID` = ".$id);
  }
  if(isset($_POST["standardLanguage"])){
    checkWritePerm();
    $langnew=$_POST["standardLanguage"];
    mysqli_query($_dbcon,"UPDATE `settings` SET `Value` = '$langnew' WHERE `settings`.`Name` = 'standardLanguage';");
  }
?>
<table class="table table-striped">
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from languages");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td data-id='".$row["ID"]."'>".$row["Name"]."<span class='delete ml-2'><i class='fas fa-trash-alt'></i></span></td></tr>";
  }
?>
</tbody>
</table>
<button class="btn btn-primary new mb-5"><?php echo $lang->new ?></button>
<form action="?m=settings/languages" method="post">
  <label><?php echo $lang->standardLang ?></label>
  <select name="standardLanguage" class="form-control mb-3">
    <?php
      $standardlang=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * FROM settings WHERE Name='standardLanguage'"))["Value"];
      $res=mysqli_query($_dbcon,"Select * from languages");
      while($row=mysqli_fetch_assoc($res)){
        $selected="";
        if($row["Name"]==$standardlang)$selected=" selected";
        echo '<option value="'.$row["Name"].'"'.$selected.">".$row["Name"].'</option>';
      }
     ?>
  </select>
  <button class="btn btn-primary" type="submit"><?php echo $lang->save ?></button>
</form>
<script src="js/cmd.js"></script>
<script>
var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>
<script>
  $(document).ready(function(){
    $("button.new").click(function(){
      cmd(
        "<label><?php echo $lang->name ?></label><input class='form-control mb-3'>",
        function(){
          document.location.href="?m=settings/languages&new="+$(".cmd input").val();
        },
        function(){
          $(".cmd input").focus();
        }
      )
    });
    $(".delete").click(function(){
      var r=confirm(consts.delete+": "+$(this).parent().text());
      if(r){
        document.location.href="?m=settings/languages&delete="+$(this).parent().attr("data-id");
      }
    })
  })
</script>
