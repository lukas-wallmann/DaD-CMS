<?php
    $id=$_GET["ID"];
    if(isset($_POST["sync"])){
      checkWritePerm();
      $code=mysqli_real_escape_string($_dbcon,mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From plugins WHERE ID=".$id))["Template"]);

      $syncwith=json_decode($_POST["sync"]);
      foreach($syncwith as &$sync){
        mysqli_query($_dbcon,"UPDATE `themePlugins` SET `Code` = '$code' WHERE `themePlugins`.`ID` = $sync;");
      }
      die();
    }
    echo "<h1>$lang->syncPlugin</h1>";
    $res=mysqli_query($_dbcon,"Select * FROM themePlugins Where PluginID=".$id);
    $tmp=[];
    while($row=mysqli_fetch_assoc($res)){
      $name=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * FROM theme WHERE ID=".$row["ThemeID"]))["Name"];
      $themepart=new stdClass();
      $themepart->ID=$row["ID"];
      $themepart->ThemeName=$name;
      array_push($tmp,$themepart);
    }
?>
<div class="syncwith">
</div>
<button class="btn btn-primary sync"><?php echo $lang->save ?></button>
<script>
  var themeparts=JSON.parse('<?php echo json_encode($tmp) ?>');
  $(document).ready(function(){
    for(var i=0; i<themeparts.length; i++){
      $(".syncwith").append('<div><input class="mr-3" data-value="'+themeparts[i].ID+'" type="checkbox" id="'+i+'" checked><label for="'+i+'">'+themeparts[i].ThemeName+'</label></div>');
    }
    $(".sync").click(function(){
      var sync=[];
      $(".syncwith input").each(function(){
        if($(this).is(":checked"))sync.push($(this).attr("data-value"));
      })
      $.ajax({type:"POST",data:{sync:JSON.stringify(sync)},url:"?m=themes&f=syncplugin&no=1&ID=<?php echo $_GET["ID"] ?>"}).done(function(){
        document.location.href="?m=themes";
      });
    });
  });
</script>
