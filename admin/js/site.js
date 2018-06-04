var site={
  init:function(){
    $("button.new").click(function(){
      cmd(
        '<label class="mr-3">'+consts.name+'</label><input>',
        function(){site.new($(".cmd input").val(),$("select.menu").val())},
        function(){$(".cmd input").focus()}
      )
    });
    $("ul.sites").sortable({
      stop:site.setPositions
    });
    $("select.menu").change(site.filterByMenu);
    site.filterByMenu();
  },

  new:function(name,menuid){
    var data={};
    data.action="new";
    data.name=name;
    data.menuid=0;
    data.lang=ncmslang;
    data.pos=Number($("ul.sites li").last().attr("data-pos"))+1;
    if(isNaN(data.pos))data.pos=0;
    if(menuid!="all")data.menuid=menuid;
    $.ajax({url:"?m=sites&f=apisite&no=1", type:"POST", data:data}).done(function(d){
      document.location.href="?m=sites&f=editpage&nh=1&ID="+d;
    });
  },

  setPositions:function(){
    var pos=[];
    var index=0;
    $("ul.sites li").each(function(){
      if($(this).attr("data-pos")!=index){
        $(this).attr("data-pos",index);
        pos.push([$(this).attr("data-id"),$(this).attr("data-pos")]);
      }
      index++;
    });

    var data={action:"reposition",pos:JSON.stringify(pos)};
    $.ajax({url:"?m=sites&f=apisite&no=1", type:"POST", data:data});

  },

  filterByMenu:function(){
    var val=$("select.menu").val();
    if(val=="all"){
      $("ul.sites li").show();
    }else{
      $("ul.sites li").hide();
      $("ul.sites li[data-menuid='"+val+"']").show();
    }
  }
}

$(document).ready(site.init);
