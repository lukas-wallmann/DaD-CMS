<?php
  if(isset($_GET["a"]) && $_GET["a"]=="api"){

    $_maindir="uploads/";
    $dir="";
    if(isset($_REQUEST["dir"]))$dir=$_REQUEST["dir"];
    $mode="list";
    if(isset($_REQUEST["mode"]))$mode=$_REQUEST["mode"];
    $_dir=$_maindir.$dir;

    function rrmdir($dir) {
       if (is_dir($dir)) {
         $objects = scandir($dir);
         foreach ($objects as $object) {
           if ($object != "." && $object != "..") {
             if (is_dir($dir."/".$object))
               rrmdir($dir."/".$object);
             else
               unlink($dir."/".$object);
           }
         }
         rmdir($dir);
       }
     }

     if($mode=="upload"){
      checkWritePerm();
      $path = $_dir.$_REQUEST['filename'];
      $dirname=dirname($path);
      if(!file_exists($dirname))mkdir($dirname);
      file_put_contents($path, file_get_contents($_POST['data']));
      die($_REQUEST["filename"]);
     }

    if($mode=="list"){
      $o=new stdClass();
      $o->dir=$dir;
      $o->files=scandir($_dir);
      echo json_encode($o);
    }

    if($mode=="createfolder"){
      checkWritePerm();
      mkdir($_dir.$_REQUEST["name"]);
    }

    if($mode=="deletefolder"){
      checkWritePerm();
      rrmdir($_dir);
    }

    if($mode=="deletefiles"){
      checkWritePerm();
      $files=explode(";",$_REQUEST["files"]);
      for($i=0; $i<count($files); $i++){
        unlink($_dir.$files[$i]);
      }
    }

    if($mode=="rename"){
      checkWritePerm();
      rename($_dir.$_REQUEST["curname"],$_dir.$_REQUEST["newname"]);
    }

    die("");
  }
?>
<div id="filebrowser">
  <div class="toolbar">
    <div class="top mb-3">
      <input id="fileInput" type="file" style="display:none;" multiple/>
      <button class="btn btn-secondary mr-2 upload" onclick="document.getElementById('fileInput').click();"><i class="fas fa-upload"></i></button>
      <button class="btn btn-secondary mr-2 newfolder"><i class="fas fa-folder"></i></button>
      <div class="prog mt-3" style="display:none; background:#ccc; height:10px"><div class="bar bg-primary" style="width:0%; height:10px"></div></div>
    </div>
    <div class="address mb-3"></div>
  </div>
  <div class="content"></div>
</div>
<script src="js/filebrowser.js"></script>
