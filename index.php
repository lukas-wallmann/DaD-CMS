<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include "admin/library/_sitecore.php";

$basefolder=explode($_SERVER['DOCUMENT_ROOT'],dirname(__FILE__));
if(count($basefolder)==1){
  $basefolder="/";
}else{
  $basefolder=$basefolder[1]."/";
}
$basefolderlength=strlen($basefolder);
$url=substr($_SERVER['REQUEST_URI'],$basefolderlength,strlen($_SERVER['REQUEST_URI'])-$basefolderlength);

if($url==""){
  $standardlang=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * FROM settings WHERE Name='standardLanguage'"))["Value"];
  $langto=$standardlang;
  $userlang=substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  $res=mysqli_query($_dbcon,"Select * from languages");
  while($row=mysqli_fetch_assoc($res)){
    if($row["Name"]==$userlang)$langto=$row["Name"];
  }
  header("Location:$basefolder".$langto."/");
}else{
  include "includes/class.servefromcacheorgenerate.php";
  $server=new servefromcacheorgenerate();
  $server->serve($url);
}



?>
