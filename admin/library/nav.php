<?php
$_currentNavPoint="";

function writeNav(){
  global $_currentNavPoint,$lang,$_modul;
  $dropdowns=0;
  for($i=0; $i<count($lang->menu); $i++){
    $a=$lang->menu[$i];
    $active="";
    $dropdown="";
    if($_modul==$a[1]){
      $active="active";
      $_currentNavPoint=$a[0];
    }
    if(isset($a[2])){
      $dropdowns++;
      $dropdown="dropdown";
    }
    if($dropdown!="dropdown"){
      echo '<li class="nav-item '.$active.'"><a class="nav-link" href="?m='.$a[1].'">'.$a[0].'</a></li>';
    }else{
      $dropdownpoints="";
      for($j=0; $j<count($a[2]);$j++){
          $b=$a[2][$j];
          $act="";
          if($_modul==$b[1]){
            $act="active";
            $_currentNavPoint=$b[0];
          }
          $dropdownpoints.='<a class="dropdown-item '.$act.'" href="?m='.$b[1].'">'.$b[0].'</a>';

      }
      echo '<li class="nav-item dropdown '.$active.'"><a class="nav-link dropdown-toggle" href="?m='.$a[1].'" id="dropdown'.$dropdowns.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$a[0].'</a><div class="dropdown-menu" aria-labelledby="dropdown'.$dropdowns.'">'.$dropdownpoints.'</div></li>';
    }
  }
}
?>
