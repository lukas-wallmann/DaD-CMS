<?php include "includes/langselect.php" ?>
<select class="menu form-control autowidth">
  <option value="all"><?php echo $lang->allMenus ?></option>
  <option value="0"><?php echo $lang->noMenu ?></option>
<?php
  $code="";
  $res=mysqli_query($_dbcon,"SELECT * FROM menus WHERE language='$dadcmslang'");
  while($row=mysqli_fetch_assoc($res)){
    $code.='<option value="" disabled>'.$row["Name"].'</option>';
    $code.=getSubs(json_decode($row["Content"]),"-");
  }
  echo $code;

  function getSubs($data,$prefix){
    $code="";
    foreach($data as &$point){
        $code.="<option value='$point->id'>$prefix $point->name</option>";
        if(count($point->sub)>0)$code.=getSubs($point->sub,$prefix."-");
    }
    return $code;
  }
?>
</select>
<button class="btn btn-secondary new"><?php echo $lang->newSite ?></button><br><br>
<ul class="sites">
  <?php
    $res=mysqli_query($_dbcon,"Select * From sites WHERE Language='$dadcmslang' ORDER BY Pos");
    while($row=mysqli_fetch_assoc($res)){
      echo '<li data-id="'.$row["ID"].'" data-pos="'.$row["Pos"].'" data-menuid="'.$row["MenuID"].'"><a href="?m=sites&f=editpage&nh=1&ID='.$row["ID"].'">'.$row["Title"].'</a></li>';
    }
   ?>
</ul>
<script>
var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/touchpunch.js"></script>
<script src="js/cmd.js"></script>
<script src="js/site.js"></script>
