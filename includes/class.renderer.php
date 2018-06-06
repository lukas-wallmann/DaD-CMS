<?php
class renderer{
    var $code="";
    var $baseurl="";

    public function preRender($data){
      $tmp=$data->theme->code;
      $this->baseurl=$data->baseurl;
      $tmp=$this->replaceBlocks($tmp,$data->theme->parts);
      $this->render($data,$tmp,"");
    }

    private function replaceBlocks($template,$data){
      $tmp=explode("[[",$template);
      $tmpcode=$tmp[0];
      for($i=1;$i<count($tmp);$i++){
        $parts=explode("]]",$tmp[$i]);
        $name=$parts[0];
        $rest=$parts[1];
        $tmpcode.=$data->$name.$rest;
      }
      return $tmpcode;
    }

    public function getCode(){
      return preg_replace('/\s+/S', " ", $this->code);
    }


    private function insertvar($parts,$data){
      $tmpcode="";
      if(count($parts)==1){
        $tmpcode=$parts[0];
      }else{
        $field=explode(".",$parts[0]);
        $d=$data;
        foreach($field as &$f){
          $d=$d->$f;
        }
        $tmpcode=$d.$parts[1];
      }
      return $tmpcode;
    }

    private function getForEachTo($part){
      $tmpcode=array("","");
      $wasend=false;
      $tmp2=explode("((/foreach))",$part);
      $open=0;
      for($i=0; $i<count($tmp2); $i++){
        $ass=$tmp2[$i];
        $sub=explode("((foreach",$ass);
        if(count($sub)>1){
          $open++;
        }else{
          $open--;
        }
        if($open>0){
          if(!$wasend){
            $tmpcode[0].=$ass."((/foreach))";
          }else{
            $tmpcode[1].=$ass."((/foreach))";
          }
        }else{
          if(!$wasend){
            $wasend=true;
            $tmpcode[0].=$ass;
          }else{
            $tmpcode[1].=$ass;
            if($i!=count($tmp2)-1)$tmpcode[1].="((/foreach))";
          }
        }
      }

      return($tmpcode);
    }


    private function getIfTo($part){
      $tmpcode=array("","");
      $wasend=false;
      $tmp2=explode("((/if))",$part);
      $open=0;
      for($i=0; $i<count($tmp2); $i++){
        $ass=$tmp2[$i];
        $sub=explode("((if",$ass);
        if(count($sub)>1){
          $open++;
        }else{
          $open--;
        }
        if($open>0){
          if(!$wasend){
            $tmpcode[0].=$ass."((/if))";
          }else{
            $tmpcode[1].=$ass."((/if))";
          }
        }else{
          if(!$wasend){
            $wasend=true;
            $tmpcode[0].=$ass;
          }else{
            $tmpcode[1].=$ass;
            if($i!=count($tmp2)-1)$tmpcode[1].="((/if))";
          }
        }
      }

      return($tmpcode);
    }


    public function render($data,$template,$action){

      global $basefolder;

      if($action==""){
        $temp=explode("((",$template,2);
        if(count($temp)==2){
          $this->code.=$this->render($data,$temp[0],"replacevars");
          $this->code.=$this->render($data,$temp[1],"((");
        }else{
          $this->code.=$this->render($data,$temp[0],"replacevars");
        }
      }

      if($action=="(("){
        if(substr($template,0,11)=="listPlugins"){
          $tmp=explode("listPlugins))",$template,2);
          foreach($data->site->Content as &$content){
            $tmpdata=$content;
            $tmpdata->site=$data->site;
            $tmpdata->theme=$data->theme;
            $tmpdata->nav=$data->nav;
            $tmpdata->uploadpath=$data->uploadpath;
            $tmpdata->baseurl=$data->baseurl;
            $pluginID=$content->pluginID;
            $tmptemplate=$data->theme->plugins->$pluginID;
            $this->code.=$this->render($tmpdata,$tmptemplate,"");
          }
          $this->code.=$this->render($data,$tmp[1],"");
        }
        if(substr($template,0,4)=="css:"){
          $tmp=explode("))",$template,2);
          $name=explode(":",$tmp[0])[1];
          $this->code.=$basefolder."css/".$name;
          $this->code.=$this->render($data,$tmp[1],"");
        }
        if(substr($template,0,3)=="js:"){
          $tmp=explode("))",$template,2);
          $name=explode(":",$tmp[0])[1];
          $this->code.=$basefolder."script/".$name;
          $this->code.=$this->render($data,$tmp[1],"");
        }
        if(substr($template,0,7)=="foreach"){

          $tmp=explode("))",$template,2);
          $foreachto=$this->getForEachTo($tmp[1]);
          $tmp=explode(" ",$tmp[0]);
          $field=explode(".",$tmp[1]);
          $d=$data;
          $key=$tmp[3];
          foreach($field as &$f){
            $d=$d->$f;
          }
          $index=0;
          foreach ($d as &$val) {
            $val->index=$index;
            $data->$key=$val;
            $data->baseurl=$this->baseurl;
            $index++;
            $this->render($data,$foreachto[0],"");
          }
          $this->render($data,$foreachto[1],"");

        }
        if(substr($template,0,2)=="if"){
          $tmp=explode("))",$template,2);
          $ifto=$this->getIfTo($tmp[1]);
          $tmp=explode(" ",$tmp[0]);
          $compare=explode("=",$tmp[1]);
          $action="=";
          if(count($compare)==1){
            $t=explode("<",$tmp[1]);
            if(count($t)==2)$action="<";
            $t=explode(">",$tmp[1]);
            if(count($t)==2)$action=">";
            $t=explode(">=",$tmp[1]);
            if(count($t)==2)$action=">=";
            $t=explode("<=",$tmp[1]);
            if(count($t)==2)$action="<=";
          }
          $compare=explode($action,$tmp[1]);
          $field=explode(".",$compare[0]);
          $d=$data;
          foreach($field as &$f){
            if($f!="length"){
              $d=$d->$f;
            }else{
              $d=count($d);
            }
          }
          if(count($compare)==2){
            if($action=="="){
              if($d==$compare[1]){
                $this->render($data,$ifto[0],"");
              }
            }
            if($action=="<"){
              if($d<$compare[1]){
                $this->render($data,$ifto[0],"");
              }
            }
            if($action==">"){
              if($d>$compare[1]){
                $this->render($data,$ifto[0],"");
              }
            }
            if($action=="<="){
              if($d<=$compare[1]){
                $this->render($data,$ifto[0],"");
              }
            }
            if($action==">="){
              if($d>=$compare[1]){
                $this->render($data,$ifto[0],"");
              }
            }
          }else{
            if($d){
              $this->render($data,$ifto[0],"");
            }
          }
          $this->render($data,$ifto[1],"");
        }
      }

      if($action=="replacevars"){
        $breaks=array(array("{{","}}","insertVar"));
        $returncode="";
        foreach($breaks as &$break){
          $tempcode="";
          $tmp=explode($break[0],$template);
          foreach($tmp as &$part){
            $p=explode($break[1],$part);
            $tempcode.=$this->{$break[2]}($p,$data);
          }
          $returncode.=$tempcode;
        }
        return $returncode;
      }
    }


}
 ?>
