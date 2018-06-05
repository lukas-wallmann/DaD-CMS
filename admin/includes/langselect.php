<div class="langselect">
  <label class="mr-3"><?php echo $lang->language ?></label>
  <select>
  <?php
    $dadcmslang=$_COOKIE["DaDCMS-lang"];
    $langres=mysqli_query($_dbcon,"Select * From languages ORDER BY Name");
    $index=0;
    while($row=mysqli_fetch_assoc($langres)){
      $selected="";
      if($row["Name"]==$dadcmslang)$selected=" selected";
      echo "<option value='".$row["Name"]."'".$selected.">".$row["Name"]."</option>";
      if($index==0){
        if($dadcmslang=="")$dadcmslang=$row["Name"];
      }
      $index++;
    }
   ?>
 </select>
</div>
<script>
  var dadcmslang="<?php echo $dadcmslang ?>";
</script>
