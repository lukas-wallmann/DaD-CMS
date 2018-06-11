var editor="";

var themeEditor={

  init:function(){
    themeEditor.helpers.getCodeEditor();
    themeEditor.helpers.load(themeEditor.helpers.fill);
  },

  setFunctions:function(){

    $('.leftsidebar .theme .b').off().click(function(){
      var addTo=$(this).parent().attr("data-add");
      var toggle=$($(this).parent().attr("data-toggle"));
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
            toggle.append(themeEditor.helpers.getMenuEntry(data.name,d,addTo,editable,subable,[],"",pluginsign));
            themeEditor.setFunctions();
          },"POST",data);
        },
        function(){$(".cmd input").focus()}
      )
    });

    $('.menu li .delete').off().click(function(){
      var r=confirm(consts.delete+": "+$(this).parent().text());
      if(r){
        var elm=$(this).parent();
        var data={action:"delete",table:elm.attr("data-table"),id:elm.attr("data-id")};
        themeEditor.helpers.load(function(){elm.remove()},"POST",data);
      }
    });

    $(".menu li .edit").off().click(function(){
      var elm=$(this).parent();
      var name=elm.text();
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
      var code='<li class="'+specialclass+'" data-table="'+table+'" data-id="'+id+'"><span class="name mr-2">'+name+'</span>'+edit+sub+'</li>';
      if(parts.length>0){
        code+="<ul>";
        for(var i=0; i<parts.length; i++){
          var o=parts[i];
          code+=themeEditor.helpers.getMenuEntry(o.Name,o.ID,table+"Parts");
        }
        code+="</ul>";
      }
      return code;
    },

  }

}

$(document).ready(themeEditor.init);
