<?php
  class form{

    var $registernewsletter=0;
    var $emailfornewsletter="";
    var $confirmnewsletter=0;
    var $sendcopy=0;
    var $emailforcopy="";
    var $receiver="";
    var $fromid="";
    var $redirectaftersend="";
    var $mailtemplate="";

    public function handle($baseurl){

      $this->receiver=$_POST["_receiver"];
      $this->fromid=$_POST["_fromid"];
      $this->redirectaftersend=$_POST["_redirectaftersend"];
      $this->mailtemplate=$_POST["_mailtemplate"];

      $tmp=array();
      $i=0;

      while(isset($_POST["_field_".$i])){
        $field=new stdClass();
        $field->name=$_POST["_field_".$i];
        $field->value="";
        $field->id=$_POST["_field_field_".$i];
        if(isset($_POST["value_".$field->id]))$field->value=$_POST["value_".$field->id];
        $field->type=$_POST["_field_type_".$i];
        if($field->type=="registernewsletter" && $field->value!=""){
            echo "send nl";
            $this->registernewsletter=1;
            $this->emailfornewsletter=$_POST["value_".$_POST["value_".$field->id."_emailfield"]];
            $this->confirmnewsletter=$_POST["value_".$field->id."_confirmmail"];

        }
        if($field->type=="sendcopy" && $field->value!=""){
          $this->sendcopy=1;
          $this->emailforcopy=$_POST["value_".$_POST["value_".$field->id."_emailfield"]];
        }
        $tmp[$i]=$field;
        $i++;
      }
      $data=new stdClass();
      $data->form=new stdClass();
      $data->form->fields=$tmp;
      $data->baseurl=$baseurl;
      $mailtemplate=$this->render($data);
      $this->send($mailtemplate);
      if($this->registernewsletter==1)$this->registernl();
    }

    private function send($mailtemplate){
      echo "email sent".$mailtemplate;
    }

    private function registernl(){
      echo "newletter registered";
    }

    private function render($data){
      global $_dbcon;
      $res=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From theme Where ID=".$this->mailtemplate));
      $data->theme=new stdClass();
      $data->theme->code=$res["Code"];
      $data->theme->parts=new stdClass();
      $res=mysqli_query($_dbcon,"Select * From themeParts Where ThemeID=".$this->mailtemplate);
      while($row=mysqli_fetch_assoc($res)){
        $name=$row["Name"];
        $data->theme->parts->$name=$row["Code"];
      }
      include "includes/class.renderer.php";
      $renderer=new renderer();
      $renderer->preRender($data);
      return $renderer->getCode();
    }


  }

 ?>
