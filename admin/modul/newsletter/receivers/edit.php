<?php
  if(isset($_GET["a"])){
    if($_GET["a"]=="import"){
      checkWritePerm();
      $entrys=json_decode($_POST["entrys"]);
      foreach($entrys as &$entry){
        $mail=mysqli_real_escape_string($_dbcon,$entry->Email);
        $name=mysqli_real_escape_string($_dbcon,$entry->Name);
        $id=$_GET["ID"];
        mysqli_query($_dbcon,"INSERT INTO newsletterReceivers (Name, Email)
        SELECT * FROM (SELECT '".$name."', '".$mail."') AS tmp
        WHERE NOT EXISTS (
            SELECT name FROM newsletterReceivers WHERE Email = '".$mail."'
        ) LIMIT 1;");
        $ID=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * From newsletterReceivers WHERE Email='".$mail."'"))["ID"];
        $res=mysqli_query($_dbcon,"SELECT * FROM `newsletterReceiversGroupLinks` WHERE `ReceiverID`=$ID AND `GroupID`=$id");
        if (mysqli_num_rows($res)==0){
          mysqli_query($_dbcon,"INSERT INTO `newsletterReceiversGroupLinks` (`ReceiverID`, `GroupID`, `Active`, `ID`) VALUES ('$ID', '$id', '1', NULL);");
        };
      }
      die("success");

    }
    if($_GET["a"]=="del"){
      die("<h2>".$lang->confirmDeleteReceiverGroup."<h2><a href='?m=newsletter/receivers&no=1&f=edit&a=delnow&ID=".$_GET["ID"]."'><button class='btn btn-danger mr-3'>".$lang->delete."</button></a><a href='?m=newsletter/receivers'><button class='btn btn-primary'>".$lang->cancel."</button></a>");
    }
    if($_GET["a"]=="delnow"){
      checkWritePerm();
      mysqli_query($_dbcon,"DELETE FROM `newsletterReceiverGroups` WHERE `newsletterReceiverGroups`.`ID` = ".$_GET["ID"]);
      mysqli_query($_dbcon,"DELETE FROM `newsletterReceiversGroupLinks` WHERE `newsletterReceiversGroupLinks`.`GroupID` = ".$_GET["ID"]);
      header("Location:?m=newsletter/receivers");
      die("");
    }
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
<a class="delete" href="?m=newsletter/receivers&f=edit&a=del&ID=<?php echo $_GET["ID"]?>">
  <button class="btn btn-danger mt-3"><?php echo $lang->deleteReceiverGroup ?></button>
</a>
<script>

        var importData="";
        var rawData="";
        var stepsize=10;
        var last=0;

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
          $(".delete").hide();
          $(".row.selectes").remove();
          $("#output").append('<div class="prog" style="height:20px; background:#ccc"><div class="prog-bar bg-primary text-white" style="width: 0%; height:20px; text-align:center">0%</div></div><div class="stepsize mt-3">Stepsize calculating</div>');
          if(!opt.forgetFirstLine){
            runImport(0,email,name);
          }else{
            runImport(1,email,name);
          }
        }else{
            alert("<?php echo $lang->errorSelectAtLeastEmail ?>");
        }
      }

      function runImport(n,email,name){

        var pro=Math.round(n/importData.length*10000)/100;
        $(".prog-bar").css("width",pro+"%").text(pro+"%");
        var data={entrys:[]};
        var to=n+stepsize;
        if(to>importData.length-1)to=importData.length-1;
        for(var i=n; i<to; i++){
          var d={};
          var fields=importData[n];
          n++;
          d.Email=fields[email];
          d.Name=fields[name];
          data.entrys.push(d);
        }
        data.entrys=JSON.stringify(data.entrys);

        $.ajax({
          type: "POST",
          url: "?m=newsletter/receivers&f=edit&no=1&a=import&ID=<?php echo $_GET["ID"] ?>",
          data: data,
          success: function(){
            if(n!=importData.length-1){
              var now=Date.now();
              var diff=now-last;
              if(diff<1500){
                stepsize++;
              }else{
                stepsize--;
              }
              last=Date.now();
              $(".stepsize").text("Stepsize:"+stepsize);
              runImport(n,email,name);
            }else{
             document.location.href="?m=newsletter/receivers";
            }
          }
        });


      }

      // ref: http://stackoverflow.com/a/1293163/2343
// This will parse a delimited string into an array of
// arrays. The default delimiter is the comma, but this
// can be overriden in the second argument.
function CSVToArray( strData, strDelimiter ){
    // Check to see if the delimiter is defined. If not,
    // then default to comma.
    strDelimiter = (strDelimiter || ",");

    // Create a regular expression to parse the CSV values.
    var objPattern = new RegExp(
        (
            // Delimiters.
            "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +

            // Quoted fields.
            "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +

            // Standard fields.
            "([^\"\\" + strDelimiter + "\\r\\n]*))"
        ),
        "gi"
        );


    // Create an array to hold our data. Give the array
    // a default empty first row.
    var arrData = [[]];

    // Create an array to hold our individual pattern
    // matching groups.
    var arrMatches = null;


    // Keep looping over the regular expression matches
    // until we can no longer find a match.
    while (arrMatches = objPattern.exec( strData )){

        // Get the delimiter that was found.
        var strMatchedDelimiter = arrMatches[ 1 ];

        // Check to see if the given delimiter has a length
        // (is not the start of string) and if it matches
        // field delimiter. If id does not, then we know
        // that this delimiter is a row delimiter.
        if (
            strMatchedDelimiter.length &&
            strMatchedDelimiter !== strDelimiter
            ){

            // Since we have reached a new row of data,
            // add an empty row to our data array.
            arrData.push( [] );

        }

        var strMatchedValue;

        // Now that we have our delimiter out of the way,
        // let's check to see which kind of value we
        // captured (quoted or unquoted).
        if (arrMatches[ 2 ]){

            // We found a quoted value. When we capture
            // this value, unescape any double quotes.
            strMatchedValue = arrMatches[ 2 ].replace(
                new RegExp( "\"\"", "g" ),
                "\""
                );

        } else {

            // We found a non-quoted value.
            strMatchedValue = arrMatches[ 3 ];

        }


        // Now that we have our value string, let's add
        // it to the data array.
        arrData[ arrData.length - 1 ].push( strMatchedValue );
    }

    // Return the parsed data.
    return( arrData );
}


      function refreshPreview(){

        var html=[];
        importData=CSVToArray(rawData);
        var check=0;
        var email=0;
        var name=-1;
        if(opt.forgetFirstLine)check=1;
        var l=importData.length;

        for(var i=check; i<l && i<50; i++){
          var d=importData[i];
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
