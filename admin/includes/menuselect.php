<select class="menu">
  <option value="all"><?php echo $lang->allMenus ?></option>
<?php
  $code="";
  $res=mysqli_query($_dbcon,"SELECT * FROM menus");
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
