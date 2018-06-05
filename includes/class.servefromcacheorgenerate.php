<?php
  class servefromcacheorgenerate{
    var $cachemode="db";
    public function serve($url){
      global $_dbcon;
      $this->cachemode=mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT * FROM `settings` WHERE `Name`='cache'"))["Value"];
      if($this->cachemode=="file"){
        $cachefile="cache/".str_replace("/","_",$url);
        if(file_exists($cachefile)){
          echo file_get_contents($cachefile);
        }else{
          $this->generate($url);
        }
      }else{
        $res=mysqli_query($_dbcon,"SELECT * FROM `cache` WHERE `URL`='$url'");
        if (mysql_num_rows($result)==0) {
          $this->generate($url);
        }else{
          echo mysqli_fetch_assoc($res)["Code"];
        }
      }

    }

    private function generate($url){
      include "includes/class.databuilder.php";
      include "includes/class.renderer.php";
      $databuilder=new databuilder();
      $data=$databuilder->build($url);
      $renderer=new renderer();
      $renderer->preRender($data);
      $code=$renderer->getCode();
      echo $code;
      $this->generateCache($url,$code);
    }

    private function generateCache($url,$code){
      global $_dbcon;
      if($this->cachemode=="file"){
        $cachefile="cache/".str_replace("/","_",$url);
        $fp = fopen($cachefile,"wb");
        fwrite($fp,$code);
        fclose($fp);
      }else{
        $code=mysqli_real_escape_string($_dbcon,$code);
        $url=mysqli_real_escape_string($_dbcon,$url);
        mysqli_query($_dbcon,"INSERT INTO `cache` (`ID`, `URL`, `Code`) VALUES (NULL, '$url', '$code');");
      }
    }
  }
 ?>
