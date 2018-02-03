<?php
  function select($n,$a,$v){
    $code='<select name="'.$n.'" class="form-control">';
    for($i=0; $i<count($a); $i++){
      $sel="";
      if($a[$i][1]==$v)$sel=" selected";
      $code.="<option value='".$a[$i][1]."'".$sel.">".$a[$i][0]."</option>";
    }
    $code.="</select>";
    return $code;
  }
?>
