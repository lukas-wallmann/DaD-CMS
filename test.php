<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$template='<form action="index.php?handler={{handler}}" method="post">
  <input type="hidden" name="formsent" value="1">
  <input type="hidden" name="receiver" value="{{receiver}}">
  <input type="hidden" name="mailtemplate" value="{{mailtemplate}}">
  ((foreach form.fields as field))
    fun
    ((foreach field.data as data))
      {{data.name}}{{data.value}}
    ((/foreach))
      ((if field.type=textarea))
          <div class="form-group">
            <label class="col-md-4 control-label" for="textarea">{{field.label}}</label>
            <div class="col-md-8">
              <textarea class="form-control" name="{{field.name}}" ((if field.required))required ((/if))placeholder="{{field.placeholder}}"></textarea>
            </div>
          </div>
      ((/if))
  ((/foreach))
</form>';

  $data=new stdClass();
  $data->handler="handlerx";
  $data->receiver="receiverx";
  $data->mailtemplate="templatex";
  $data->form=new stdClass();
  $data->form->fields=array();
  $field=new stdClass();
  $field->name="namex";
  $field->required=true;
  $field->type="textarea";
  $field->placeholder="placeholderx";
  $field->label="labelx";
  array_push($data->form->fields,$field);

  $field=new stdClass();
  $field->name="namexx";
  $field->required=false;
  $field->placeholder="placeholderxx";
  $field->label="labelxx";
  $field->type="textarea";
  array_push($data->form->fields,$field);

  $code="";

  rendern($data,$template,"");
  echo $code;

  function insertvar($parts,$data){
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

  function getForEachTo($part){
    //echo $part;
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


  function getIfTo($part){
    //echo $part;
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



  function rendern($data,$template,$action){
    global $code;
    //fix for unneeded space
    $template=join("))",explode("))\n",$template));

    if($action==""){
      $temp=explode("((",$template,2);
      if(count($temp)==2){
        $code.=rendern($data,$temp[0],"replacevars");
        $code.=rendern($data,$temp[1],"((");
      }else{
        $code.=rendern($data,$temp[0],"replacevars");
      }
    }

    if($action=="(("){
      if(substr($template,0,7)=="foreach"){

        $tmp=explode("))",$template,2);
        $foreachto=getForEachTo($tmp[1]);
        $tmp=explode(" ",$tmp[0]);

        $field=explode(".",$tmp[1]);
        $d=$data;
        $key=$tmp[3];
        foreach($field as &$f){
          $d=$d->$f;
        }
        foreach ($d as &$val) {
          $data->$key=$val;
          rendern($data,$foreachto[0],"");
        }
        rendern($data,$foreachto[1],"");

      }
      if(substr($template,0,2)=="if"){
        $tmp=explode("))",$template,2);
        $ifto=getIfTo($tmp[1]);
        $tmp=explode(" ",$tmp[0]);
        $compare=explode("=",$tmp[1]);
        $field=explode(".",$compare[0]);
        $d=$data;
        foreach($field as &$f){
          $d=$d->$f;
        }
        if(count($compare)==2){
          if($d==$compare[1]){
            rendern($data,$ifto[0],"");
          }
        }else{
          if($d){
            rendern($data,$ifto[0],"");
          }
        }
        rendern($data,$ifto[1],"");
      }
    }

    if($action=="replacevars"){

      $breaks=array(array("{{","}}",insertvar));
      $returncode="";
      foreach($breaks as &$break){
        $tempcode="";
        $tmp=explode($break[0],$template);
        foreach($tmp as &$part){
          $p=explode($break[1],$part);
          $tempcode.=$break[2]($p,$data);
        }
        $returncode.=$tempcode;
      }
      return $returncode;
    }
  }


?>
