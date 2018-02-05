<?php
  if(isset($_GET["a"]) && $_GET["a"]=="import"){
    $mail=$_POST["Email"];
    $name="";
    if(isset($_POST["Name"]))$name=$_POST["Name"];
    checkWritePerm();
    die("sucess:".$mail." :".$name);
  }
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
<?php   if($_GET["ID"]!="NEW"){ ?>
<script>

        var importData="";
        var rawData="";

        function openFile(event) {
          var input = event.target;

          var reader = new FileReader();
          reader.onload = function(){
            importData = reader.result;
            rawData = reader.result;
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
          importNow();
        });
        refreshPreview();
      };

      function importNow(){
        var objects=[];
        var name=-1;
        var email=-1;
        $(".row.selectes select").each(function(){
          if($(this).val()=="Email")email=$(this).parent().attr("data-col");
          if($(this).val()=="Name")name=$(this).parent().attr("data-col");
        })
        if(email>-1){
          $("#output").html("");
          $("#dialog").hide();
          $("#fileselect").hide();
          $("form").hide();
          $(".row.selectes").remove();
          runImport(0,email,name);
        }else{
            alert("<?php echo $lang->errorSelectAtLeastEmail ?>");
        }
      }

      function runImport(n,email,name){

        if(n==0)$("#output").append('<div class="prog" style="height:20px; background:#ccc"><div class="prog-bar bg-primary text-white" style="width: 0%; height:20px; text-align:center">0%</div></div>')
        var pro=Math.round(n/importData.length*10000)/100;
        $(".prog-bar").css("width",pro+"%").text(pro+"%");
        var fields=importData[n].split(",");
        var data={};
        data.Email=fields[email];
        data.Name=fields[name];
        $.ajax({
          type: "POST",
          url: "?m=newsletter/receivers&f=edit&no=1&a=import&ID=<?php echo $_GET["ID"] ?>",
          data: data,
          success: function(){
            n++;
            if(n!=importData.length){
              runImport(n,email,name);
            }else{
              document.location.href="?m=newsletter/receivers";
            }
          }
        });


      }

      function refreshPreview(){

        var html=[];
        importData=rawData.split("\n");
        var check=0;
        var email=0;
        var name=-1;
        if(opt.forgetFirstLine)check=1;
        var l=importData.length;

        for(var i=check; i<l && i<50; i++){
          var d=importData[i].split(",");
          if(i==check){
            html.push("<div class='row selectes'>");
            for(var j=0; j<d.length; j++){
              var email="";
              if(d[j].split("@").length>1)email="<option value='Email' selected>Email</option>";
              html.push("<div class='col' data-col='"+j+"'><select><option value='not'>not import</option>"+email+"<option value='Name'>Name</option></select></div>");
            }
            html.push("</div>");
          }
          html.push("<div class='row data'>");
          for(var j=0; j<d.length; j++){
            html.push("<div class='col "+j+"'>"+d[j]+"</div>");
          }
          html.push("</div>");

        }
        $("#output").html(html.join(""));
      }


</script>
<h3 class="mt-5"><?php echo $lang->receiverImport ?></h3>
<input id="fileselect" type='file' onchange='openFile(event)'><br>
<div id="dialog" class="mt-3" style="display:none">
  <input type="checkbox" class="firstLineHeading" id="checkbox"><label for="checkbox"><?php echo $lang->firstLineHeading ?></label><br>
  <button class="import btn btn-danger"><?php echo $lang->import ?></button>
</div>
<div id='output' class="mt-3">
</div>
<?php } ?>
