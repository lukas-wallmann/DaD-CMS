<?php
class databuilder{
  var $data="";
  public function build($url){
    global $_dbcon,$basefolder;
    $this->data=new stdClass();

    $this->data->uploadpath=$basefolder."admin/uploads/";
    $this->data->baseurl=$basefolder;
    $this->data->site=new stdClass();
    $this->data->site->Currentyear=date("Y");

    if(substr($url,0,1)=="/")$url=substr($url,1);
    $parts=explode("/",$url);

    if($parts[1]!=""){
      $url=mysqli_real_escape_string($_dbcon,$parts[1]);
      $lang=mysqli_real_escape_string($_dbcon,$parts[0]);
      $res=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From sites WHERE SiteURL='$url' AND Language='$lang' Limit 1"));

    }else{
      $lang=mysqli_real_escape_string($_dbcon,$parts[0]);
      $res=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From sites WHERE Language='$lang' ORDER BY Pos Limit 1"));
    }

    if($res==""){
      http_response_code(404);
      //include('my_404.php');
      die();
    }

    foreach($res as $key => $value) {
      $this->data->site->$key=$value;
    }
    $this->data->site->Content=json_decode($this->data->site->Content);
    $res=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From theme Where ID=".$this->data->site->Layout));
    $this->data->theme=new stdClass();
    $this->data->theme->code=$res["Code"];
    $this->data->theme->parts=new stdClass();

    $res=mysqli_query($_dbcon,"Select * From themeParts Where ThemeID=".$this->data->site->Layout);
    while($row=mysqli_fetch_assoc($res)){
      $name=$row["Name"];
      $this->data->theme->parts->$name=$row["Code"];
    }

    $this->data->theme->plugins=new stdClass();
    $res=mysqli_query($_dbcon,"Select * From plugins Where LayoutID=".$this->data->site->Layout);
    while($row=mysqli_fetch_assoc($res)){
      $name=$row["Name"];
      $this->data->theme->plugins->$name=$row["Code"];
    }

    $this->data->theme->css=new stdClass();
    $res=mysqli_query($_dbcon,"Select * From css Where LayoutID=".$this->data->site->Layout);
    while($row=mysqli_fetch_assoc($res)){
      $name=$row["Name"];
      $this->data->theme->css->$name=$row["ID"];
    }

    $this->data->theme->script=new stdClass();
    $res=mysqli_query($_dbcon,"Select * From script Where LayoutID=".$this->data->site->Layout);
    while($row=mysqli_fetch_assoc($res)){
      $name=$row["Name"];
      $this->data->theme->script->$name=$row["ID"];
    }

    $this->data->nav=new stdClass();
    $res=mysqli_query($_dbcon,"Select * From menus WHERE Language='$lang'");
    while($row=mysqli_fetch_assoc($res)){
      $name=$row["Name"];
      $this->data->nav->$name=$this->prepareMenu(json_decode($row["Content"]));
    }
    return $this->data;
  }

  private function getLink($id){
    global $_dbcon, $basefolder;
    $row=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From sites WHERE MenuID='$id' ORDER BY Pos Limit 1"));
    $url=$basefolder.$row["Language"]."/".$row["SiteURL"]."/";
    if($row["SiteURL"]=="")$url="#";
    return $url;
  }

  private function prepareMenu($menus){
      for($i=0; $i<count($menus); $i++){
        $menus[$i]->active=false;
        if($menus[$i]->action=="site"){
          $id=$menus[$i]->id;
          if($id==$this->data->site->MenuID)$menus[$i]->active=true;
          $menus[$i]->href=$this->getLink($id);
        }else{
          $menus[$i]->href=$menus[$i]->link;
        }
        $menus[$i]->hassub=false;
        if(count($menus[$i]->sub)>0)$menus[$i]->hassub=true;
        $menus[$i]->sub=$this->prepareMenu($menus[$i]->sub);
      }
      return $menus;
    }

}
 ?>
