<?php
class serverjscss{

  var $cachemode="db";
  var $type="script";
  var $name="";
  var $url="";
  public function serve($url){
    global $_dbcon;
    $this->url=$url;
    $this->cachemode=mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT * FROM `settings` WHERE `Name`='cache'"))["Value"];
    if(substr($url,0,3)=="css")$this->type="css";
    $this->name=substr($url,strlen($this->type)+1,strlen($url));

    if($this->cachemode=="file"){
      $cachefile="cache/".str_replace("/","_",$url);
      if(file_exists($cachefile)){
        echo file_get_contents($cachefile);
      }else{
        $this->generate();
      }
    }else{
      $res=mysqli_query($_dbcon,"SELECT * FROM `cache` WHERE `URL`='$url'");
      if (mysqli_num_rows($res)==0) {
        $this->generate();
      }else{
        echo mysqli_fetch_assoc($res)["Code"];
      }
    }
  }

  private function generate(){
    global $_dbcon;
    $id=mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT * FROM `$this->type` WHERE Name='$this->name'"))["ID"];
    if($id==""){
      http_response_code(404);
      echo "not found";
      die();
    }else{
      $res=mysqli_query($_dbcon,"Select * From ".$this->type."Parts WHERE ParentID=".$id);
      $tmp="";
      while($row=mysqli_fetch_assoc($res)){
        $tmp.=$row["Code"];
      }
      if($this->type=="script"){
        header('Content-Type: application/javascript');
        include "class.jsshrink.php";
        $tmp=\JShrink\Minifier::minify($tmp);
      }else{
        header("Content-type: text/css", true);
        include "class.scss.php";
        $scss = new scssc();
        $tmp=$scss->compile($tmp);
        $tmp = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $tmp);
        $tmp = str_replace(': ', ':', $tmp);
        $tmp = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $tmp);
      }
      echo $tmp;
      $this->generateCache($tmp);
    }

  }

  private function generateCache($code){
    global $_dbcon;
    if($this->cachemode=="file"){
      $cachefile="cache/".str_replace("/","_",$this->url);
      $fp = fopen($cachefile,"wb");
      fwrite($fp,$code);
      fclose($fp);
    }else{
      $code=mysqli_real_escape_string($_dbcon,$code);
      $url=mysqli_real_escape_string($_dbcon,$this->url);
      mysqli_query($_dbcon,"INSERT INTO `cache` (`ID`, `URL`, `Code`) VALUES (NULL, '$url', '$code');");
    }
  }
}
?>
