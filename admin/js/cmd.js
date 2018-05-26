function cmd(html,onfinish,oninit=function(){}){
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
