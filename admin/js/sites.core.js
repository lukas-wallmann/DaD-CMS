$( function() {
  $("main").append('<div id="elements"></div><div id="content"></div>');
  registerPlugin(
    "heading",
    '<i class="fas fa-heading"></i>',
    function(itm){console.log(itm)}
  );
  registerPlugin(
    "text",
    '<i class="fas fa-align-left"></i>',
    function(){}
  );
  registerPlugin(
    "text-image",
    '<span style="font-size: 26px;margin: 0 0 0 -5px;"><i class="far fa-image"></i> <i class="fas fa-align-left"></i></span>',
    function(){}
  );
  registerPlugin(
    "image",
    '<i class="far fa-image"></i>',
    function(){}
  );
  registerPlugin(
    "downloads",
    '<i class="fas fa-download"></i>',
    function(){}
  );
  registerPlugin(
    "form",
    '<i class="far fa-address-card"></i>',
    function(){}
  )
  setFunctions();
} );

var $plugins=[];
function registerPlugin(name,icon,fninit){
  $plugins.push([name,fninit]);
  $('#elements').append('<div class="draggable btn bg-secondary" data-type="'+name+'">'+icon+'</div>');

}

function getFn(name,fn){
  for(var i=0; i<$plugins.length; i++){
    if($plugins[i][0]==name){
      return $plugins[i][fn];
      break;
    }
  }
}

function setFunctions(){


  $( "#elements .draggable" ).draggable({ scroll: true, scrollSensitivity: 100, helper:"clone", drag:function(){
    checkHit($(".ui-draggable-dragging").offset())
  }, stop:function(){
    $("#placeholder").replaceWith("<div class='itm "+$(this).attr("data-type")+" init' data-type='"+$(this).attr("data-type")+"'><div class='handle'><div class='dragger'><i class='fas fa-ellipsis-h'></i></div><div class='delete'><i class='fas fa-trash'></i></div></div><div class='content'>"+$(this).attr("data-type")+"</div></div>");
    setFunctions();
  } });

  $('#content .itm.init').each(function(){
    getFn($(this).attr("data-type"),1)($(this));
    $(this).removeClass("init");
  })

  $( "#content .itm" ).draggable({ handle: ".handle .dragger",
   drag:function(){checkHit($(this).offset())},
   stop:function(){
     $(this).css("left","auto");
     $(this).css("top","auto");
     $("#placeholder").replaceWith($(this));
   }
  });
  $('.itm .handle .delete').click(function(){
    $(this).parent().parent().remove();
  });
}

function checkHit(offset){
  snip=[];
  $("#placeholder").remove();
  $(".itm:not(.ui-draggable-dragging)").each(function(){
    var off=$(this).offset();
    var h=$(this).height();
    if(off.top<offset.top+10 && off.top>offset.top-(h/2)  ){
      $(this).before("<div id='placeholder'></div>");
    }else if(off.top+(h/2)<offset.top+10 && off.top+h>offset.top-10  ){
      $(this).after("<div id='placeholder'></div>");
    }
  });
  if($(".itm").length==0){
    $("#content").html("<div id='placeholder' style='height:2px; background:#00ff00'></div>");
  }
}
