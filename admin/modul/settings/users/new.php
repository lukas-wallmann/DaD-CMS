<?php
  if(isset($_POST["username"])){
    mysqli_query($_dbcon,"INSERT INTO `users` (`ID`, `User`, `Password`, `Rights`, `Language`, `Email`) VALUES (NULL, '".$_POST["username"]."', '".password_hash($_POST["password"], PASSWORD_DEFAULT)."', '".$_POST["rights"]."', '".$_POST["lang"]."', '".$_POST["email"]."');");
    header("Location:?m=settings/users");
  }
?>

<form action="?m=settings/users&f=new&no=1" method="post" class="userform">
  <div class="form-group">
    <label for="username"><?php echo $lang->userName; ?></label>
    <input type="text" name="username" class="form-control" id="username" placeholder="<?php echo $lang->userName; ?>">
  </div>
  <div class="form-group">
    <label for="email"><?php echo $lang->email; ?></label>
    <input type="text" name="email" class="form-control" id="email" placeholder="<?php echo $lang->email; ?>">
  </div>
  <div class="form-group">
    <label for="lang"><?php echo $lang->language; ?></label>
    <?php echo langSelect(); ?>
  </div>
  <div class="form-group">
    <label for="password"><?php echo $lang->password; ?></label>
    <input type="text" name="password" class="form-control" id="password" placeholder="<?php echo $lang->password; ?>">
  </div>
  <h3><?php echo $lang->rightsRead ?></h3>
  <div class="rights read"></div>
  <h3><?php echo $lang->rightsWrite ?></h3>
  <div class="rights write"></div>
  <button type="submit" class="btn btn-primary mt-3"><?php echo $lang->createUser; ?></button>
  <input type="hidden" id="rights" name="rights">
</form>

<script>

  $(document).ready(function(){

    var menu='<?php echo json_encode($lang->menu); ?>';
    var rights=<?php echo json_encode($_SESSION["rights"]); ?>;
    menu=JSON.parse(menu);
    rights=JSON.parse(rights);

    function genPerm(mode){
      var valR=rights.disallowRead;
      if(mode=="write")valR=rights.disallowWrite;
      var code=[];
      for(var i=0; i<menu.length; i++){
        var name=menu[i][0];
        var value=menu[i][1];
        var id=mode+i;
        var disabled="";
        var checked=" checked";
        if(valR.indexOf(value)!=-1){
          disabled=" disabled";
          checked="";
        }
        if(menu[i][2]!=undefined){
          for(var j=0; j<menu[i][2].length; j++){
            var name=menu[i][0]+"-&gt;"+menu[i][2][j][0];
            var value=menu[i][2][j][1];
            var id=mode+i+j;
            var disabled="";
            var checked=" checked";
            if(valR.indexOf(value)!=-1){
              disabled=" disabled";
              checked="";
            }
            code.push('<div class="form-check form-check-inline"><input'+disabled+' id="'+id+'" class="form-check-input" type="checkbox" value="'+value+'"'+checked+'><label class="form-check-label" for="'+id+'">'+name+'</label></div>');
          }
        }else {
          code.push('<div class="form-check form-check-inline"><input'+disabled+' id="'+id+'" class="form-check-input" type="checkbox" value="'+value+'"'+checked+'><label class="form-check-label" for="'+id+'">'+name+'</label></div>');
        }
      }
      return code.join("");
    }
    $(".userform .rights.read").append(genPerm("read"));
    $(".userform .rights.write").append(genPerm("write"));

    $(".userform .rights.read input[type='checkbox']").change(function(){
      if(!$(this).is(":checked")){
        var val=$(this).val();
        $('.userform .rights.write input[value="'+val+'"]').prop("checked",false);
      }
    });
    function setRights(){
      function goT(sel){
        var disallowed=[];
        $(sel).each(function(){
          if(!$(this).is(":checked"))disallowed.push($(this).val());
        });
        return disallowed;
      }
      var ro={};
      ro.disallowRead=goT(".userform .rights.read input");
      ro.disallowWrite=goT(".userform .rights.write input");
      $("#rights").val(JSON.stringify(ro));
    }
    $(".userform .rights input").change(function(){
      setRights();
    });
    setRights();
  })
</script>
