<div class="row h-100">
  <div class="col-md-2 leftsidebar">
    <button class="btn btn-primary w-100 save mb-3"><?php echo $lang->save ?></button>
    <div class="themepart"><b><?php echo $lang->themeParts ?></b><span class="b"><i class="fas fa-plus-square ml-2"></i></span></div>
    <div class="maincode selected" data-table="theme" data-id="<?php echo $_GET["ID"] ?>"><span class="name"><?php echo $lang->mainCode ?></span></div>
    <div class="menu parts"></div>
    <div class="themeplugin mt-3"><b><?php echo $lang->themePlugins ?></b><span class="b"><i class="fas fa-plus-square ml-2"></i></span></div>
    <div class="menu plugins"></div>
  </div>
  <div class="col-md-10 codeEditor" id="editor"></div>
</div>

<script src="js/ace/ace.js"></script>
<script src="js/height100.js"></script>
<script>
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/monokai");
  editor.session.setMode("ace/mode/html");
  editor.setOptions({
    fontSize: "13pt"
  });
  var layoutID=<?php echo $_GET["ID"] ?>;
</script>

<script>

  var codeEditor={

    isFirstCode:true,
    lastID:0,
    lastTable:"",
    cache:[],

    init:function(){
      $(document).bind('keydown', function(e) {
        if(e.ctrlKey && (e.which == 83)) {
          e.preventDefault();
          codeEditor.save();
          return false;
        }
      });
      $(".themepart .b").click(function(){
        codeEditor.cmd(
          '<label class="mr-3"><?php echo $lang->name?></label><input>',
          function(){codeEditor.getThemeParts("new",$(".cmd input").val())},
          function(){$(".cmd input").focus()}
        )
      });
      $(".themeplugin .b").click(function(){
        codeEditor.cmd(
          '<label class="mr-3"><?php echo $lang->name?></label><input>',
          function(){codeEditor.getPlugins("new",$(".cmd input").val())},
          function(){$(".cmd input").focus()}
        )
      });
      $(".save").click(function(){
        codeEditor.save();
      })
      codeEditor.getThemeParts();
      codeEditor.getPlugins();
    },

    save:function(){
      codeEditor.saveTemp();
      var data={};
      data.parts=JSON.stringify(codeEditor.cache);
      $.ajax({
        type: "POST",
        url: "?m=settings/themes&f=apisavecode&no=1",
        data: data
      }).done(function(){
        alert("done");
      });
          },

    getThemeParts:function(action="",name="", id=0){
      var type="GET";
      if(action!="")type="POST";
      var data={};
      if(action=="new")data.name=name;
      if(action=="rename"){
        data.newname=name;
        data.partID=id;
      }
      if(action=="delete")data.deleteID=id;
      $.ajax({
        type: type,
        data: data,
        url: "?m=settings/themes&f=apigetthemeparts&no=1&ID=<?php echo $_GET["ID"]?>"
      }).done(function(d) {
        var json=JSON.parse(d);
        $(".menu.parts").html("");
        for(var i=0; i<json.length; i++){
          $(".menu.parts").append('<div class="menu" data-id="'+json[i].ID+'" data-table="themeParts"><span class="name">'+json[i].Name+'</span><span class="edit ml-3"><i class="fas fa-pen-square"></i></span><span class="delete ml-1"><i class="fas fa-trash-alt"></i></span></div>');
        }
        $(".menu.parts .menu .name, .maincode .name").off().click(function(){
          $(".selected").removeClass("selected");
          $(this).parent().addClass("selected");
          codeEditor.getCode($(this).parent().attr("data-table"),$(this).parent().attr("data-id"));
        });
        $(".menu.parts .menu .edit").click(function(){
          var tmpname=$(this).parent().text();
          var tmpID=$(this).parent().attr("data-id");
          codeEditor.cmd(
            '<label class="mr-3"><?php echo $lang->name?></label><input value="'+tmpname+'">',
            function(){codeEditor.getThemeParts("rename",$(".cmd input").val(),tmpID)},
            function(){$(".cmd input").focus()}
          )
        });
        $(".menu.parts .menu .delete").click(function(){
          var r=confirm("<?php echo $lang->delete ?>: "+$(this).parent().text());
          if(r){
            codeEditor.removeTemp($(this).parent().attr("data-id"),"themeParts");
            codeEditor.getThemeParts("delete","",$(this).parent().attr("data-id"));
            $(".maincode .name").click();
          }
        });
      });


    },

    getPlugins:function(action="",name="", id=0){
      var type="GET";
      if(action!="")type="POST";
      var data={};
      if(action=="new")data.name=name;
      if(action=="rename"){
        data.newname=name;
        data.partID=id;
      }
      if(action=="delete")data.deleteID=id;
      $.ajax({
        type: type,
        data: data,
        url: "?m=settings/themes&f=apigetthemeparts&no=1&table=themePlugins&ID=<?php echo $_GET["ID"]?>"
      }).done(function(d) {
        var json=JSON.parse(d);
        $(".menu.plugins").html("");
        for(var i=0; i<json.length; i++){
          $(".menu.plugins").append('<div class="menu" data-id="'+json[i].ID+'" data-table="themePlugins"><span class="name">'+json[i].Name+'</span><span class="edit ml-3"><i class="fas fa-pen-square"></i></span><span class="delete ml-1"><i class="fas fa-trash-alt"></i></span></div>');
        }
        $(".menu.plugins .menu .name").off().click(function(){
          $(".selected").removeClass("selected");
          $(this).parent().addClass("selected");
          codeEditor.getCode($(this).parent().attr("data-table"),$(this).parent().attr("data-id"));
        });
        $(".menu.plugins .menu .edit").click(function(){
          var tmpname=$(this).parent().text();
          var tmpID=$(this).parent().attr("data-id");
          codeEditor.cmd(
            '<label class="mr-3"><?php echo $lang->name?></label><input value="'+tmpname+'">',
            function(){codeEditor.getPlugins("rename",$(".cmd input").val(),tmpID)},
            function(){$(".cmd input").focus()}
          )
        });
        $(".menu.plugins .menu .delete").click(function(){
          var r=confirm("<?php echo $lang->delete ?>: "+$(this).parent().text());
          if(r){
            codeEditor.removeTemp($(this).parent().attr("data-id"),"themePlugins");
            codeEditor.getPlugins("delete","",$(this).parent().attr("data-id"));
            $(".maincode .name").click();
          }
        });
      });

    },

    cmd:function(html,onfinish,oninit=function(){}){
      html+="<div class='ctrl'><button class='btn btn-primary ok' type='submit'><?php echo $lang->save ?></button><button class='btn btn-warning cancel'><?php echo $lang->cancel ?></button></div>";
      html='<div class="cmd"><div class="inner"><form>'+html+'</form></div></div>';
      $("body").append(html);
      oninit();
      $(".cmd form").submit(function(e){
        alert("DONE");

        onfinish();
        $(".cmd").remove();
        e.preventDefault();
      });
      $(".cmd .ctrl .cancel").click(function(){
        $(".cmd").remove();
      });
    },

    getCode:function(table,id){

      if(!codeEditor.isFirstCode){
        codeEditor.saveTemp();
      }else{
        codeEditor.isFirstCode=false;
      }

      codeEditor.lastID=id;
      codeEditor.lastTable=table;

      var found=false;
      for(var i=0; i<codeEditor.cache.length; i++){
        if(codeEditor.cache[i][0]==id && codeEditor.cache[i][1]==table){
          found=true;
          editor.setValue(codeEditor.cache[i][2]);
        }
      }

      if(!found){
        $.ajax({
          url: "?m=settings/themes&f=apigetthemecode&no=1&ID="+id+"&table="+table
        }).done(function(d) {
          editor.setValue(d,1);
        });
    }

    },

    removeTemp:function(id,table){
      var tmp=[];
      for(var i=0; i<codeEditor.cache.length; i++){
        if(codeEditor.cache[i][0]!=id){
          tmp.push(codeEditor.cache[i])
        }else if(codeEditor.cache[i][1]!=table){
          tmp.push(codeEditor.cache[i])
        }
      }
      codeEditor.cache=tmp;
    },

    saveTemp:function(){
      var temp=[codeEditor.lastID,codeEditor.lastTable,editor.getValue()];
      var found=false;
      for(var i=0; i<codeEditor.cache.length; i++){
        if(codeEditor.cache[i][0]==temp[0] && codeEditor.cache[i][1]==temp[1]){
          found=true;
          codeEditor.cache[i][2]=temp[2];
        }
      }
      if(!found)codeEditor.cache.push(temp);
      console.log(codeEditor.cache);
    }
  }



  $(document).ready(function(){
    codeEditor.init();
    codeEditor.getCode("theme",layoutID);
  });
</script>
<style>
.cmd {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 99999;
    background: rgba(0,0,0,0.5);
    width: 100%;
    height: 100%;
}

.cmd .inner {
    background: #fff;
    padding: 20px;
}
.selected{
  font-style: italic;
  text-decoration: underline;
}
</style>
