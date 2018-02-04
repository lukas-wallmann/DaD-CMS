<?php
$_currentNavPoint="";

function writeNav(){
  global $_currentNavPoint,$lang,$_modul;
  $rights=json_decode($_SESSION["rights"]);
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
      if(in_array($a[1],$rights->disallowRead)==false)echo '<li class="nav-item '.$active.'"><a class="nav-link" href="?m='.$a[1].'">'.$a[0].'</a></li>';
    }else{
      $dropdownpoints="";
      for($j=0; $j<count($a[2]);$j++){
          $b=$a[2][$j];
          $act="";
          if($_modul==$b[1]){
            $act="active";
            $active="active";
            $_currentNavPoint=$b[0];
          }
          if(in_array($b[1],$rights->disallowRead)==false){
            $dropdownpoints.='<a class="dropdown-item '.$act.'" href="?m='.$b[1].'">'.$b[0].'</a>';
          }else if($b[1]=="settings/users"){
            $dropdownpoints.='<a class="dropdown-item '.$act.'" href="?m=settings/users&f=edit&ID='.$_SESSION["id"].'">'.$b[0].'</a>';
          }
      }
      echo '<li class="nav-item dropdown '.$active.'"><a class="nav-link dropdown-toggle" href="?m='.$a[1].'" id="dropdown'.$dropdowns.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$a[0].'</a><div class="dropdown-menu" aria-labelledby="dropdown'.$dropdowns.'">'.$dropdownpoints.'</div></li>';
    }
  }
}
?>
