<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>newCMS</title>
</head>

<body>
	<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="index.php">newCMS</a>
      <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsDefault" aria-controls="navbarsDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="navbar-collapse collapse" id="navbarsDefault" style="">
        <ul class="navbar-nav mr-auto">

          <?php
            $dropdowns=0;
            for($i=0; $i<count($lang->menu); $i++){
              $a=$lang->menu[$i];
              $active="";
              $dropdown="";
              if($_modul==$a[1])$active="active";
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
                    if($_modul==$b[1])$act="active";
                    $dropdownpoints.='<a class="dropdown-item '.$act.'" href="?m='.$b[1].'">'.$b[0].'</a>';

                }
                echo '<li class="nav-item dropdown '.$active.'"><a class="nav-link dropdown-toggle" href="?m='.$a[1].'" id="dropdown'.$dropdowns.'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$a[0].'</a><div class="dropdown-menu" aria-labelledby="dropdown'.$dropdowns.'">'.$dropdownpoints.'</div></li>';
              }
            }
          ?>
        </ul>
      </div>
    </nav>    
    <main role="main" class="container-fluid">

