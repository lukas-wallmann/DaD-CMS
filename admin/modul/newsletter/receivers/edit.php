<?php
  if($_GET["ID"]=="NEW"){
    $row=array();
    $row["Name"]=$lang->newReceiverlist;
    if(isset($_POST["name"])){
      checkWritePerm();
      mysqli_query($_dbcon,"INSERT INTO `newsletterReceiverGroups` (`ID`, `Name`) VALUES (NULL, '".$_POST["name"]."');");
      header("Location:?m=newsletter/receivers");
    }
  }else{
    if(isset($_POST["name"])){
      checkWritePerm();
      mysqli_query($_dbcon,"UPDATE `newsletterReceiverGroups` SET `Name` = '".$_POST["name"]."' WHERE `newsletterReceiverGroups`.`ID` = ".$_GET["ID"].";");
      header("Location:?m=newsletter/receivers");
    }else{
      $row=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From newsletterReceiverGroups Where ID=".$_GET["ID"]));
    }
  }
?>
<form method="post" action="?m=newsletter/receivers&no=1&f=edit&ID=<?php echo $_GET["ID"] ?>">
    <input type="text" name="name" value="<?php echo $row["Name"] ?>" class="form-control">
    <button type="submit" class="btn btn-primary mt-3"><?php echo $lang->save ?></button>
</form>
<script>

        var importData="";

        function openFile(event) {
          var input = event.target;

          var reader = new FileReader();
          reader.onload = function(){
            importData = reader.result;
            openDialog();
          };
          reader.readAsText(input.files[0]);
      };

      var opt={};
      opt.forgetFirstLine=false;

      function openDialog(){
        $("#dialog").show();
        $("#dialog .firstLineHeading").change(function(){
          if($(this).is(":checked")){
            opt.forgetFirstLine=true;
          }else{
            opt.forgetFirstLine=false;
          }
          refreshPreview();
        });
        $("#dialog .import").click(function(){
          alert("import now")
        });
        refreshPreview();
      };

      function refreshPreview(){

        var html=[];
        var data=importData.split("\n");
        var check=0;
        var email=0;
        var name=-1;
        if(opt.forgetFirstLine)check=1;

        for(var i=check; i<data.length; i++){

          var d=data[i].split(",");

          if(i==check){
            for(var j=0; j<d.length; j++){
              alert(d[j].split("@"));
              if(d[j].split("@").length>1){
                email=j;
              }else{
                name=j;
              }
            }
            alert("email:"+email+" name:"+name)

          }
        }
        $("#output").append(html.join(""));
      }

</script>
<h3><?php echo $lang->receiverImport ?></h3>
<input type='file' onchange='openFile(event)'><br>
<div id="dialog" class="mt-3" style="display:none">
  <input type="checkbox" class="firstLineHeading" id="checkbox"><label for="checkbox">First line is heading</label><br>
  <button class="import btn btn-danger">import</button>
</div>
<div id='output'>
</div>
