<?php
  if(isset($_GET["a"]) && $_GET["a"]=="import"){
    die("sucess");
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
          $(".row.data").each(function(){
            var e=$(this).find(".col."+email).text();
            var n="";
            if(name!=-1)n=$(this).find(".col."+name).text();
            var o={};
            o.Email=e;
            o.Name=n;
            objects.push(o);
            $(this).remove();
          });
          $(".row.selectes").remove();
          runImport(0,objects);
        }else{
            alert("<?php echo $lang->errorSelectAtLeastEmail ?>");
        }
      }

      function runImport(n,o){
        if(n==0)$("#output").append('<div class="prog" style="height:20px; background:#ccc"><div class="prog-bar bg-primary text-white" style="width: 0%; height:20px; text-align:center">0%</div></div>')
        var pro=Math.round(n/o.length*10000)/100;
        $(".prog-bar").css("width",pro+"%").text(pro+"%");
        var data=o[n];
        $.ajax({
          type: "POST",
          url: "?m=newsletter/receivers&f=edit&no=1&a=import&ID=<?php echo $_GET["ID"] ?>",
          data: data,
          success: function(){
            n++;
            if(n!=o.length){
              runImport(n,o);
            }else{
              document.location.href="?m=newsletter/receivers";
            }
          }
        });


      }

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
<h3><?php echo $lang->receiverImport ?></h3>
<input type='file' onchange='openFile(event)'><br>
<div id="dialog" class="mt-3" style="display:none">
  <input type="checkbox" class="firstLineHeading" id="checkbox"><label for="checkbox"><?php echo $lang->firstLineHeading ?></label><br>
  <button class="import btn btn-danger"><?php echo $lang->import ?></button>
</div>
<div id='output' class="mt-3">
</div>
