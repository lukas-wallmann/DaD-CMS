var menuBuilder={

  data:[],

  init:function(){
    menuBuilder.loadMenus();
    $("button.new").click(function(){
        menuBuilder.cmd(
        '<label class="mr-3">'+consts.name+'</label><input>',
        function(){menuBuilder.loadMenus("new",$(".cmd input").val())},
        function(){$(".cmd input").focus()}
      )
    });
  },

  loadMenus:function(action="",name="",id=0){
    var type="GET";
    var data={};
    if(action!=""){
      type="POST";
      data.action=action;
      data.name=name;
      data.id=id;
    }
    $.ajax({url:"?m=menu&f=apimenu&no=1", type:type, data:data}).done(function(d){
      menuBuilder.data=JSON.parse(d);
      menuBuilder.build();
      menuBuilder.setFunctions();
    });
  },

  setFunctions(){
    $(".menu .edit, .menu .delete, .menu .plus").off();
    $(".menu > .title > .delete").click(function(){
      var r=confirm(consts.delete+": "+$(this).parent().text());
      if(r)menuBuilder.loadMenus("delete","",$(this).parent().parent().attr("data-id"));
    });
    $(".menu > .title > .edit").click(function(){
      var tmpname=$(this).parent().text();
      var tmpID=$(this).parent().parent().attr("data-id");
      menuBuilder.cmd(
        '<label class="mr-3">'+consts.name+'</label><input value="'+tmpname+'">',
        function(){menuBuilder.loadMenus("rename",$(".cmd input").val(),tmpID)},
        function(){$(".cmd input").focus()}
      )
    });
  },

  getPoint:function(data){
    var code='<li data-id="'+data.ID+'" data-action="'+data.action+'" data-url="'+data.URL+'" data-target="'+data.target+'">';
    code+='<div class="point">'+data.name+'</div>';
    if(data.sub.length>0){
      code+=menuBuilder.getPoints(data.sub);
    }
    code+="</li>";
    return code;
  },

  getPoints:function(data){
    console.log(data.length);
    if(data.length==0){
      return "<ul></ul>";
    }else{
      var code=[];
      code.push("<ul>");
      for(var i=0; i<data.length; i++){
        code.push(menuBuilder.getPoint(data[i]));
      }
      code.push("</ul>");
      return code.join("");
    }

  },

  build:function(){
    $(".menus").html("");
    for(var i=0; i<menuBuilder.data.length; i++){
      $(".menus").append('<div class="menu" data-ID="'+menuBuilder.data[i].ID+'" data-name="'+menuBuilder.data[i].Name+'"><div class="title"><span>'+menuBuilder.data[i].Name+'</span><span class="edit ml-3"><i class="fas fa-pen-square"></i></span><span class="plus ml-1"><i class="fas fa-plus-square"></i></span><span class="delete ml-1"><i class="fas fa-trash-alt"></i></span></div><div class="points">'+menuBuilder.getPoints(JSON.parse(menuBuilder.data[i].Content))+'</div></div>');
    }
  },

  saveMenu:function(sel){

  },

  cmd:function(html,onfinish,oninit=function(){}){
    html+="<div class='ctrl'><button class='btn btn-primary ok' type='submit'>"+consts.save+"</button><button class='btn btn-warning cancel'>"+consts.cancel+"</button></div>";
    html='<div class="cmd"><div class="inner"><form>'+html+'</form></div></div>';
    $("body").append(html);
    oninit();
    $(".cmd form").submit(function(e){
      onfinish();
      $(".cmd").remove();
      e.preventDefault();
    });
    $(".cmd .ctrl .cancel").click(function(){
      $(".cmd").remove();
    });
  }
}
$(document).ready(menuBuilder.init);
