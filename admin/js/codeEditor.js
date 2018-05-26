
  var codeEditor={

    isFirstCode:true,
    lastID:0,
    lastTable:"",
    cache:[],
    pluginData:[],
    plugins:[],

    init:function(){
      $(document).bind('keydown', function(e) {
        if(e.ctrlKey && (e.which == 83)) {
          e.preventDefault();
          codeEditor.save();
          return false;
        }
      });
      $(".themepart .b").click(function(){
        cmd(
          '<label class="mr-3">'+consts.name+'</label><input>',
          function(){codeEditor.getThemeParts("new",$(".cmd input").val())},
          function(){$(".cmd input").focus()}
        )
      });
      $(".themeplugin .b").click(function(){
        $.ajax({url:"?m=settings/themes&f=apigetplugins&no=1"}).done(function(d){
          codeEditor.pluginData=JSON.parse(d);
          var html=[];
          for(var i=0; i<codeEditor.pluginData.length; i++){
            var found=false;
            for(var j=0; j<codeEditor.plugins.length; j++){
              if(codeEditor.plugins[j].PluginID==codeEditor.pluginData[i].ID)found=true;
            }
            if(!found)html.push('<div class="addme"><input type="checkbox" id="'+i+'" data-value="'+codeEditor.pluginData[i].ID+'"><label class="ml-3" for="'+i+'">'+codeEditor.pluginData[i].Name+'</label></div>');
          }
          cmd(
            html.join(""),
            function(){
              var cache=[];
              $(".cmd .addme input").each(function(){
                if($(this).is(":checked")){
                  for(var i=0; i<codeEditor.pluginData.length; i++){
                    if(codeEditor.pluginData[i].ID==$(this).attr("data-value"))cache.push(codeEditor.pluginData[i]);
                  }
                }
              });
              codeEditor.getPlugins("new",cache);
            },
            function(){}
          )
        })
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
        url: "?m=settings/themes&f=apigetthemeparts&no=1&ID="+layoutID
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
          cmd(
            '<label class="mr-3">'+consts.name+'</label><input value="'+tmpname+'">',
            function(){codeEditor.getThemeParts("rename",$(".cmd input").val(),tmpID)},
            function(){$(".cmd input").focus()}
          )
        });
        $(".menu.parts .menu .delete").click(function(){
          var r=confirm(consts.delete+": "+$(this).parent().text());
          if(r){
            codeEditor.removeTemp($(this).parent().attr("data-id"),"themeParts");
            codeEditor.getThemeParts("delete","",$(this).parent().attr("data-id"));
            $(".maincode .name").click();
          }
        });
      });


    },

    getPlugins:function(action="",cache=[],id=0){
      var type="GET";
      if(action!="")type="POST";
      var data={};
      if(action=="new")data.cache=JSON.stringify(cache);
      if(action=="delete")data.deleteID=id;
      $.ajax({url:"?m=settings/themes&f=apiplugin&no=1&ID="+layoutID,type:type,data:data}).done(function(d){
        codeEditor.plugins=JSON.parse(d);
        $(".menu.plugins").html("");
        for(var i=0; i<codeEditor.plugins.length; i++){
          $(".menu.plugins").append('<div class="point" data-table="themePlugins" data-id="'+codeEditor.plugins[i].ID+'"><span class="name mr-3">'+codeEditor.plugins[i].Name+'</span><span class="sync"><i class="fas fa-sync-alt"></i><span class="delete ml-1"><i class="fas fa-trash-alt"></i></span></span></div>');
        }
        $(".menu.plugins .point .name").click(function(){
          $(".selected").removeClass("selected");
          $(this).parent().addClass("selected");
          codeEditor.getCode($(this).parent().attr("data-table"),$(this).parent().attr("data-id"));
        });
        $(".menu.plugins .point .delete").click(function(){
          var r=confirm(consts.delete+": "+$(this).parent().text());
          if(r){
            codeEditor.getPlugins("delete",[],$(this).parent().attr("data-id"));
          }
        })
      })
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
