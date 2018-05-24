var cssjsEditor={

  init:function(){
    $("button.add").click(function(){
      cssjsEditor.cmd(
        '<label class="mr-3">'+consts.name+'</label><input>',
        function(){codeEditor.getThemeParts("new",$(".cmd input").val())},
        function(){$(".cmd input").focus()}
      )
    })
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

$(document).ready(cssjsEditor.init);
