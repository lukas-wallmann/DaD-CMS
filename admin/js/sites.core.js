$( function() {
  $("main").append('<div id="elements"></div><div id="content"></div>');
  $.getScript( "tmpplugins.js" ).done(function( script, textStatus ) {
    nCMS.init();
  })
} );

var nCMS={
  plugins:[],
  registerPlugin:function(plugin){
    nCMS.plugins.push(plugin);
    $('#elements').append('<div class="draggable btn bg-secondary" data-type="'+plugin.identifier+'">'+plugin.icon+'</div>');
  },
  init:function(){
    nCMS.dragger.setFunctions();
  },
  fieldset:{
    init:function(elm){
      nCMS.fieldset.get(elm.attr("data-type"));
    },
    get:function(identifier){
      for(var i=0; i<nCMS.plugins.length; i++){
        if(nCMS.plugins[i].identifier==identifier){
          console.log(nCMS.plugins[i].fieldset);
          break;
        }
      }
    },
    utils:[

    ]
  },
  dragger:{
    setFunctions:function(){
      $( "#elements .draggable" ).draggable({ scroll: true, scrollSensitivity: 100, helper:"clone", drag:function(){
        nCMS.dragger.checkHit($(".ui-draggable-dragging").offset())
      }, stop:function(){
        $("#placeholder").replaceWith("<div class='itm init' data-type='"+$(this).attr("data-type")+"'><div class='handle'><div class='dragger'><i class='fas fa-ellipsis-h'></i></div><div class='delete'><i class='fas fa-trash'></i></div></div><div class='content'>"+$(this).attr("data-type")+"</div></div>");
        nCMS.dragger.setFunctions();
      } });

      $('#content .itm.init').each(function(){
        $(this).removeClass("init");
        nCMS.fieldset.init($(this));
      })

      $( "#content .itm" ).draggable({ handle: ".handle .dragger",
       drag:function(){nCMS.dragger.checkHit($(this).offset())},
       stop:function(){
         $(this).css("left","auto");
         $(this).css("top","auto");
         $("#placeholder").replaceWith($(this));
       }
      });
      $('.itm .handle .delete').click(function(){
        $(this).parent().parent().remove();
      });
    },


    checkHit:function(offset){
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

  }
}
