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
      data.lang=ncmslang;
    }
    $.ajax({url:"?m=menu&f=apimenu&no=1&lang="+dadcmslang, type:type, data:data}).done(function(d){
      menuBuilder.data=JSON.parse(d);
      menuBuilder.build();
      menuBuilder.setFunctions();
    });
  },

  editMenuPoint:function(from,data,helper){
    var html='<label class="mr-3">'+consts.name+'</label><input class="name" value="'+data.name+'">';
    var selected_top="";
    var selected_blank="";
    if(data.target=="_top"){
      selected_top=" selected";
    }else{
      selected_blank=" selected";
    }
    html+='<br><label class="mr-3">Target</label><select class="target"><option value="_top"'+selected_top+'>_top</option><option value="_blank"'+selected_blank+'>_blank</option></select>';
    var selectedSite="";
    var selectedLink="";
    if(data.action=="site"){
      selectedSite=" selected";
    }else{
      selectedLink=" selected";
    }
    html+='<br><label class="mr-3">Action</label><select class="action"><option value="site"'+selectedSite+'>show site</option><option value="link"'+selectedLink+'>show link</option></select>';
    html+='<br><label class="mr-3">Link</label><input class="link" value="'+data.link+'">';
    menuBuilder.cmd(
      html,
      function(){
        var dat={};
        dat.link=$(".cmd .link").val();
        dat.name=$(".cmd .name").val();
        dat.action=$('.cmd .action').val();
        dat.target=$('.cmd .target').val();
        if(from=="new"){
          dat.sub=[];
          dat.id=Date.now();
          var code=menuBuilder.getPoint(dat);
          helper.append(code);
          menuBuilder.save(helper);
        }else{
          dat.id=data.id;
          dat.sub=data.sub;
          var code=menuBuilder.getPoint(dat);
          from.replaceWith(code);
          menuBuilder.save(helper)
        }
        menuBuilder.setFunctions();
      },
      function(){$(".cmd input.name").focus()}
    )
  },

  setFunctions(){

    $(".menu, .menu .edit, .menu .delete, .menu .plus").off();

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

    $(".menu .plus").click(function(){
      menuBuilder.editMenuPoint("new",{name:"",action:"site",target:"_top",link:""},$(this).parent().next());
    });

    $(".menu ul .edit").click(function(){
      menuBuilder.editMenuPoint($(this).parent().parent(),{name:$(this).parent().text(),action:$(this).parent().parent().attr("data-action"),target:$(this).parent().parent().attr("data-target"),link:$(this).parent().parent().attr("data-link"), sub:menuBuilder.getSub($(this).parent().parent())},$(this).parent().parent().parent());
    });

    $(".menu ul .delete").click(function(){
      var r=confirm(consts.delete+": "+$(this).parent().text());
      if(r){
        var helper=$(this).parent().parent().parent();
        $(this).parent().parent().remove();
        menuBuilder.save($(helper));
      }
    });

    $(".menus ul").sortable({
        stop:function(e,ui){
          menuBuilder.save(ui.item);
        }
    });

  },

  getSub:function(elm){
    var sub=[];
    elm.children("ul").each(function(){
      $(this).children("li").each(function(){
        var data={
          name:$(this).children("div").first().text(),
          id:$(this).attr("data-id"),
          action:$(this).attr("data-action"),
          target:$(this).attr("data-target"),
          link:$(this).attr("data-link"),
          sub:menuBuilder.getSub($(this))
        };
        sub.push(data);
      });
    });
    return sub;
  },

  getPoint:function(data){
    var code='<li data-id="'+data.id+'" data-action="'+data.action+'" data-link="'+data.link+'" data-target="'+data.target+'">';
    code+='<div class="point"><span>'+data.name+'</span><span class="edit ml-3"><i class="fas fa-pen-square"></i></span><span class="plus ml-1"><i class="fas fa-plus-square"></i></span><span class="delete ml-1"><i class="fas fa-trash-alt"></i></span></div>';
    code+=menuBuilder.getPoints(data.sub);
    code+="</li>";
    return code;
  },

  getPoints:function(data){
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
      $(".menus").append('<div class="menu" data-ID="'+menuBuilder.data[i].ID+'" data-name="'+menuBuilder.data[i].Name+'"><div class="title"><span>'+menuBuilder.data[i].Name+'</span><span class="edit ml-3"><i class="fas fa-pen-square"></i></span><span class="plus ml-1"><i class="fas fa-plus-square"></i></span><span class="delete ml-1"><i class="fas fa-trash-alt"></i></span></div>'+menuBuilder.getPoints(JSON.parse(menuBuilder.data[i].Content))+'</div>');
    }
  },

  save:function(helper){
    var elm=helper;
    var steps=0;
    while(!elm.hasClass("menu") && steps<100){
      elm=elm.parent();
      steps++;
    }
    var data={action:"update",id:elm.attr("data-id"),name:"",content:JSON.stringify(menuBuilder.getSub(elm))};
    $.ajax({url:"?m=menu&f=apimenu&no=1&lang="+dadcmslang,type:"POST",data:data});
  },

  cmd:function(html,onfinish,oninit=function(){}){
    html+="<div class='ctrl'><button class='btn btn-primary ok' type='submit'>"+consts.save+"</button><button class='btn btn-warning cancel'>"+consts.cancel+"</button></div>";
    html='<div class="cmd"><div class="inner"><form>'+html+'</form></div></div>';
    $("body").append(html);
    oninit();
    $(".cmd form").submit(function(e){
      e.preventDefault();
      onfinish();
      $(".cmd").remove();
    });
    $(".cmd .ctrl .cancel").click(function(){
      $(".cmd").remove();
    });
  }
}
$(document).ready(menuBuilder.init);
