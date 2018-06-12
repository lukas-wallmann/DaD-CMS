var editor="";

var themeEditor={

  init:function(){
    themeEditor.helpers.getCodeEditor();
    themeEditor.helpers.load(themeEditor.helpers.fill);
    $(window).bind('keydown', function(event) {
      if (event.ctrlKey || event.metaKey) {
          switch (String.fromCharCode(event.which).toLowerCase()) {
          case 's':
              event.preventDefault();
              themeEditor.code.save();
              break;
          }
      }
    });
    $("button.save").click(themeEditor.code.save);
  },

  setFunctions:function(){

    $('.leftsidebar .theme .b').off().click(function(){
      var elm=$(this).parent();
      var addTo=elm.attr("data-add");
      var toggle=$(elm.attr("data-toggle"));
      var pos=toggle.children("li").length;
      cmd(
        '<label class="mr-3">'+consts.name+'</label><input>',
        function(){
          var data={action:"addnew",to:addTo, name:$('.cmd input').val(), pos:pos};
          themeEditor.helpers.load(function(d){
            var editable=true;
            var subable=false;
            var pluginsign=false;
            if(toggle.hasClass("plugins")){
              pluginsign=true;
            }
            if(toggle.hasClass("css") || toggle.hasClass("script")){
              subable=true;
            }
            if(toggle.css("display")=="none"){
              elm.find(".openclose").click();
            }
            toggle.append(themeEditor.helpers.getMenuEntry(data.name,d,addTo,editable,subable,[],"",pluginsign));
            themeEditor.setFunctions();
          },"POST",data);
        },
        function(){$(".cmd input").focus()}
      )
    });

    $('.menu li .delete').off().click(function(){
      var r=confirm(consts.delete+": "+$(this).parent().children(".name").text());
      if(r){
        var elm=$(this).parent();
        var data={action:"delete",table:elm.attr("data-table"),id:elm.attr("data-id")};
        themeEditor.helpers.load(function(){elm.remove()},"POST",data);
      }
    });

    $(".menu li .edit").off().click(function(){
      var elm=$(this).parent();
      var name=elm.children(".name").text();
      cmd(
        '<label class="mr-3">'+consts.name+'</label><input value="'+name+'">',
        function(){
          var data={action:"rename",table:elm.attr("data-table"), name:$('.cmd input').val(), id:elm.attr("data-id")};
          themeEditor.helpers.load(function(){elm.find(".name").text(data.name)},"POST",data);
        },
        function(){$(".cmd input").focus()}
      )
    });

    $(".menu li .add").off().click(function(){
      var addTo=$(this).parent().attr("data-table")+"Parts";
      var toggle=$(this).parent();
      var parentID=toggle.attr("data-id");
      var pos=toggle.find("li").length;
      cmd(
        '<label class="mr-3">'+consts.name+'</label><input>',
        function(){
          var data={action:"addnew",to:addTo, name:$('.cmd input').val(), pos:pos, parentID:parentID};
          themeEditor.helpers.load(function(d){
            var editable=true;
            var subable=false;
            var pluginsign=false;
            if(toggle.find("ul").length==0)toggle.append("<ul></ul>");
            toggle.find("ul").append(themeEditor.helpers.getMenuEntry(data.name,d,addTo,editable,subable,[],"",pluginsign));
            themeEditor.setFunctions();
          },"POST",data);
        },
        function(){$(".cmd input").focus()}
      )
    });

    $(".leftsidebar .theme .openclose").off().click(function(){
      if($(this).find(".opend").css("display")=="inline"){
        $(this).find(".opend").css("display","none");
        $(this).find(".closed").css("display","inline");
      }else{
        $(this).find(".opend").css("display","inline");
        $(this).find(".closed").css("display","none");
      }
      $($(this).parent().attr("data-toggle")).toggle("fast");
    })

    $(".menu li .name").off().click(function(){
      var table=$(this).parent().attr("data-table");
      if(table!="css" && table!="script"){
        $(".selected").removeClass("selected");
        $(this).parent().addClass("selected");
        themeEditor.code.get($(this).parent());
      }
    });

    if(themeEditor.code.info.id==undefined){
      $(".menu li").first().find(".name").click();
    }

    $(".menu li .plugin").off().click(function(){
      $(".selected").removeClass("selected");
      $(this).parent().addClass("selected");
      $(this).addClass("selected");
      themeEditor.code.get($(this).parent(),true)
    });

    $(".leftsidebar ul").off().fixedsortable({
      fixed:".nodrop",
      stop:function(e,ui){
        var data={action:"reposition"}
        data.table=ui.item.attr("data-table");
        data.arr=[];
        ui.item.parent().children("li").each(function(){
          if(!$(this).hasClass("nodrop"))data.arr.push($(this).attr("data-id"));
        });
        data.arr=JSON.stringify(data.arr);
        themeEditor.helpers.load(function(){},"POST",data);
      }
    });

  },

  code:{

    cache:[],
    info:{},

    get:function(elm,plugin=false){
      themeEditor.code.setCache();
      var data={};
      data.a="getCode";
      data.id=elm.attr("data-id");
      data.table=elm.attr("data-table");
      data.field="Code";
      data.mode="html";
      if(plugin){
        data.mode="javascript";
        data.field="PluginCode";
      }
      if(data.table=="cssParts"){
        data.mode="scss";
      }
      if(data.table=="scriptParts"){
        data.mode="javascript";
      }

      var found=false;
      for(var i=0; i<themeEditor.code.cache.length; i++){
        if(themeEditor.code.cache[i].id==data.id && themeEditor.code.cache[i].field==data.field && themeEditor.code.cache[i].table==data.table){
          found=true;
          themeEditor.code.set(themeEditor.code.cache[i].code,themeEditor.code.cache[i]);
        }
      }
      if(!found)themeEditor.helpers.load(function(d){themeEditor.code.set(d,data)},"POST",data);

    },

    set:function(d,data){
      editor.session.setMode("ace/mode/"+data.mode);
      editor.setValue(d);
      themeEditor.code.info=data;
    },

    save:function(){
      themeEditor.code.setCache();
      var tmp=[];
      for(var i=0; i<themeEditor.code.cache.length; i++){
        if(themeEditor.code.cache[i].save){
          tmp.push(themeEditor.code.cache[i]);
          themeEditor.code.cache[i].save=false;
        }
      }
      var data={};
      data.action="save";
      data.parts=JSON.stringify(tmp);
      themeEditor.helpers.load(function(){alert("done")},"POST",data);
    },

    setCache:function(){
      if(themeEditor.code.info.id!=undefined){
        var found=false;
        for(var i=0; i<themeEditor.code.cache.length; i++){
          if(themeEditor.code.cache[i].id==themeEditor.code.info.id && themeEditor.code.cache[i].field==themeEditor.code.info.field && themeEditor.code.cache[i].table==themeEditor.code.info.table){
            found=true;
            var data=themeEditor.code.info;
            data.code=editor.getValue();
            data.save=true;
            themeEditor.code.cache[i]=data;
            break;
          }
        }
        if(!found){
          var data=themeEditor.code.info;
          data.save=true;
          data.code=editor.getValue();
          themeEditor.code.cache.push(data);
        }
      }
    }

  },

  helpers:{

    getCodeEditor:function(){
      editor = ace.edit("editor");
      editor.setTheme("ace/theme/monokai");
      editor.session.setMode("ace/mode/html");
      editor.session.setUseWorker(false);
      editor.setOptions({
        fontSize: "13pt"
      });
    },

    load:function(doneF,type="GET",data={}){
      $.ajax({url:"?m=themes&f=api&no=1&ID="+layoutID,type:type,data:data}).done(doneF);
    },

    fill:function(d){
      d=JSON.parse(d);
      $(".leftsidebar .menu").html("");

      $('.leftsidebar .menu.parts').append(themeEditor.helpers.getMenuEntry(consts.mainCode,layoutID,"theme",false,false,[],"nodrop"));
      for(var i=0; i<d.themeParts.length; i++){
        var o=d.themeParts[i];
        $('.leftsidebar .menu.parts').append(themeEditor.helpers.getMenuEntry(o.Name,o.ID,"themeParts"));
      }
      for(var i=0; i<d.css.length; i++){
        var o=d.css[i];
        $('.leftsidebar .menu.css').append(themeEditor.helpers.getMenuEntry(o.Name,o.ID,"css",true,true,o.parts));
      }
      for(var i=0; i<d.plugins.length; i++){
        var o=d.plugins[i];
        $('.leftsidebar .menu.plugins').append(themeEditor.helpers.getMenuEntry(o.Name,o.ID,"plugins",true,false,[],"",true));
      }
      for(var i=0; i<d.script.length; i++){
        var o=d.script[i];
        $('.leftsidebar .menu.script').append(themeEditor.helpers.getMenuEntry(o.Name,o.ID,"script",true,true,o.parts));
      }
      themeEditor.setFunctions();
    },

    getMenuEntry:function(name,id,table,editable=true,subable=false,parts=[],specialclass="",pluginsign=false){
      var edit="";
      if(editable)edit='<span class="edit"><i class="fas fa-pen-square"></i></span><span class="delete ml-1"><i class="fas fa-trash"></i></span>';
      if(pluginsign)edit+='<span class="plugin ml-1"><i class="fas fa-plug"></i></span>';
      var sub="";
      if(subable)sub='<span class="add ml-1"><i class="fas fa-plus-square"></i></span>';
      var code='<li class="'+specialclass+'" data-table="'+table+'" data-id="'+id+'"><span class="name mr-2">'+name+'</span>'+edit+sub;
      if(parts.length>0){
        code+="<ul>";
        for(var i=0; i<parts.length; i++){
          var o=parts[i];
          code+=themeEditor.helpers.getMenuEntry(o.Name,o.ID,table+"Parts");
        }
        code+="</ul>";
      }
      code+="</li>";
      return code;
    },

  }

}

$(document).ready(themeEditor.init);
