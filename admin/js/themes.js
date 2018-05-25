var themeManager={
  currentRename:{},

  init:function(){
    $(".edit").click(function(){
      themeManager.currentRename=$(this).parent();
      themeManager.cmd(
        '<label class="mr-3">'+consts.name+'</label><input value="'+$(this).parent().text()+'">',
        function(){themeManager.rename($(".cmd input").val(),themeManager.currentRename)},
        function(){$(".cmd input").focus()}
      )
    });
    $(".delete").click(function(){
      var r=confirm("Delete: "+$(this).parent().text());
      if(r)themeManager.del($(this).parent());
    });
  },

  rename:function(newname,elm){
    elm.find("a").text(newname);
    var data={};
    data.newname=newname;
    data.ID=elm.attr("data-id");
    data.table=elm.attr("data-table");
    $.ajax({type:"POST",data:data, url:"?m=settings/themes&f=apitheme&no=1&action=rename"});
  },

  del:function(elm){
    elm.parent().remove();
    var data={};
    data.ID=elm.attr("data-id");
    data.table=elm.attr("data-table");
    $.ajax({type:"POST",data:data, url:"?m=settings/themes&f=apitheme&no=1&action=delete"});
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
$(document).ready(themeManager.init);
