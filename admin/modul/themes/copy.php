<?php
  if(isset($_GET["no"])){
    checkWritePerm();

    function prepare($row,$tablename,$field="",$val=""){
      global $_dbcon;
      $keys=array();
      $values=array();
      $fields="";
      $vals="";
      foreach ($row as $key => $value) {
        if($key!="ID"){
          if($key!=$field){
            array_push($keys,$key);
            array_push($values,mysqli_real_escape_string($_dbcon,$value));
          }else{
            array_push($keys,$key);
            array_push($values,mysqli_real_escape_string($_dbcon,$val));
          }

        }else{
          array_push($keys,$key);
          array_push($values,NULL);
        }
      }
      for($i=0; $i<count($keys); $i++){
        if($i!=0)$fields.=", ";
        $fields.="`".$keys[$i]."`";
        if($i!=0)$vals.=",";
        if($values[$i]==NULL){
          $vals.="NULL";
        }else{
          $vals.="'".$values[$i]."'";
        }
      }
      return "INSERT INTO `$tablename` ($fields) VALUES ($vals);";

    }


    $id=$_GET["ID"];
    $row=mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT * FROM theme WHERE ID=".$id));
    mysqli_query($_dbcon,prepare($row,"theme","Name",$_POST["name"]));

    $insert_id=$_dbcon->insert_id;

    $res=mysqli_query($_dbcon,"SELECT * FROM themeParts WHERE ThemeID=".$id);
    while($row=mysqli_fetch_assoc($res)){
      mysqli_query($_dbcon,prepare($row,"themeParts","ThemeID",$insert_id));
    }

    $res=mysqli_query($_dbcon,"SELECT * FROM plugins WHERE LayoutID=".$id);
    while($row=mysqli_fetch_assoc($res)){
      mysqli_query($_dbcon,prepare($row,"plugins","LayoutID",$insert_id));
    }

    $res=mysqli_query($_dbcon,"SELECT * FROM script WHERE LayoutID=".$id);
    while($row=mysqli_fetch_assoc($res)){
      $currentID=$row["ID"];
      mysqli_query($_dbcon,prepare($row,"script","LayoutID",$insert_id));
      $newID=$_dbcon->insert_id;
      $res2=mysqli_query($_dbcon,"SELECT * FROM scriptParts WHERE ParentID=".$currentID);
      while($row2=mysqli_fetch_assoc($res2)){
        mysqli_query($_dbcon,prepare($row2,"scriptParts","ParentID",$newID));
      }
    }

    $res=mysqli_query($_dbcon,"SELECT * FROM css WHERE LayoutID=".$id);
    while($row=mysqli_fetch_assoc($res)){
      $currentID=$row["ID"];
      mysqli_query($_dbcon,prepare($row,"css","LayoutID",$insert_id));
      $newID=$_dbcon->insert_id;
      $res2=mysqli_query($_dbcon,"SELECT * FROM cssParts WHERE ParentID=".$currentID);
      while($row2=mysqli_fetch_assoc($res2)){
        mysqli_query($_dbcon,prepare($row2,"cssParts","ParentID",$newID));
      }
    }

    header("Location:?m=themes&f=edittheme&nh=1&ID=".$insert_id);
    die();
  }

?>
<form action="?m=themes&f=copy&no=1&ID=<?php echo $_GET["ID"]; ?>" method="post">
  <h1><?php echo $lang->newTheme ?></h1>
  <label class="mr-3"><?php echo $lang->name ?></label><input name="name" value="copy">
  <select name="for">
    <option value="site">Site</option>
    <option value="newsletter">Newsletter</option>
    <option value="mail">Email</option>
  </select><br>
  <button type="submit" class="btn btn-primary"><?php echo $lang->save ?></button>
</form>
