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
    var $newsletterreceivergroup="";
    var $subject="";

    public function handle($baseurl){

      $this->receiver=$_POST["_receiver"];
      $this->fromid=$_POST["_fromid"];
      $this->redirectaftersend=$_POST["_redirectaftersend"];
      $this->mailtemplate=$_POST["_mailtemplate"];
      $this->subject=$_POST["_subject"];

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
            $this->registernewsletter=1;
            $this->emailfornewsletter=$_POST["value_".$_POST["value_".$field->id."_emailfield"]];
            $this->confirmnewsletter=$_POST["value_".$field->id."_confirmmail"];
            $this->newsletterreceivergroup=$_POST["value_".$field->id."_receivergroup"];
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
      $this->redirect($baseurl);
    }

    private function redirect($baseurl){
      global $_dbcon;
      $res=mysqli_query($_dbcon,"Select * From sites WHERE ID=".$this->redirectaftersend);
      if (mysqli_num_rows($res)==0){
        $res=mysqli_query($_dbcon,"Select * From sites WHERE ID=".$this->fromid);
        $row=mysqli_fetch_assoc($res);
        $link=$baseurl.$row["Language"]."/".$row["SiteURL"]."/";
        header("Location:".$link);
      }else{
        $row=mysqli_fetch_assoc($res);
        $link=$baseurl.$row["Language"]."/".$row["SiteURL"]."/";
        header("Location:".$link);
      }
    }

    private function send($mailtemplate){
      global $_dbcon;
      $mailsettings=json_decode(mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT * FROM `settings` WHERE `Name`='email'"))["Value"]);
      $this->utf8mail($this->receiver,$this->subject,$mailtemplate,$mailsettings->name,$mailsettings->email,$mailsettings->reply);
      if($this->sendcopy==1){
        $this->utf8mail($this->emailforcopy,$this->subject,$mailtemplate,$mailsettings->name,$mailsettings->email,$mailsettings->reply);
      }
    }

    private function registernl(){
      global $_dbcon;
      $mail=mysqli_real_escape_string($_dbcon,$this->emailfornewsletter);
      $name="";
      $id=$this->newsletterreceivergroup;
      mysqli_query($_dbcon,"INSERT INTO newsletterReceivers (Name, Email)
      SELECT * FROM (SELECT '".$name."', '".$mail."') AS tmp
      WHERE NOT EXISTS (
          SELECT name FROM newsletterReceivers WHERE Email = '".$mail."'
      ) LIMIT 1;");
      $ID=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From newsletterReceivers WHERE Email='".$mail."'"))["ID"];
      $res=mysqli_query($_dbcon,"SELECT * FROM `newsletterReceiversGroupLinks` WHERE `ReceiverID`=$ID AND `GroupID`=$id");
      if (mysqli_num_rows($res)==0){
        mysqli_query($_dbcon,"INSERT INTO `newsletterReceiversGroupLinks` (`ReceiverID`, `GroupID`, `Active`, `ID`) VALUES ('$ID', '$id', '1', NULL);");
      }else{
        $linkID=mysqli_fetch_assoc($res)["ID"];
        mysqli_query($_dbcon,"UPDATE `newsletterReceiversGroupLinks` SET `Active` = '1' WHERE `newsletterReceiversGroupLinks`.`ID` = $linkID;");
      }
    }

    private function utf8mail($to,$s,$body,$from_name="öäü",$from_a = "office@wallmanns-ideenwerkstatt.com", $reply="office@wallmanns-ideenwerkstatt.com")
    {
        $s= "=?utf-8?b?".base64_encode($s)."?=";
        $headers = "MIME-Version: 1.0\r\n";
        $headers.= "From: =?utf-8?b?".base64_encode($from_name)."?= <".$from_a.">\r\n";
        $headers.= "Content-Type: text/html;charset=utf-8\r\n";
        $headers.= "Reply-To: $reply\r\n";
        $headers.= "X-Mailer: PHP/" . phpversion();
        mail($to, $s, $body, $headers);
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
