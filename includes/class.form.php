<?php
  class form{

    var $registernewsletter=0;
    var $emailfornewsletter="";
    var $confirmnewsletter=0;
    var $sendcopy=0;
    var $emailforcopy="";

    public function handle(){
      $tmp=array();
      $i=0;
      while(isset($_POST["_field_".$i])){
        $field=new stdClass();
        $field->name=$_POST["_field_".$i];
        $field->value="";
        $field->id=$_POST["_field_field_".$i];
        if(isset($_POST["value_".$field->id]))$field->value=$_POST["value_".$field->id];
        $field->type=$_POST["_field_type_".$i];
        if($field->type=="registernewsletter"){
          if($field->value!=""){
            $this->registernewsletter=1;
            $this->emailfornewsletter=$_POST["value_".$_POST["value_".$field->id."_emailfield"]];
            $this->confirmnewsletter=$_POST["value_".$field->id."_confirmmail"];
          }
        }
        if($field->type=="sendcopy" && $field->value!=""){
          $this->sendcopy=1;
          $this->emailforcopy=$_POST["value_".$_POST["value_".$field->id."_emailfield"]];
          echo "Send copy to:".$this->emailforcopy;
        }
        $tmp[$i]=$field;
        $i++;
      }
      print_r($tmp);
    }
  }

 ?>
