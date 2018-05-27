$( function() {
  $(".section .dropper").click(function(){
    $(this).next().toggle("slow");
  })
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
      var fieldset=nCMS.fieldset.get(elm.attr("data-type"));
      nCMS.fieldset.build(elm,fieldset);
    },

    get:function(identifier){
      for(var i=0; i<nCMS.plugins.length; i++){
        if(nCMS.plugins[i].identifier==identifier){
          return(nCMS.plugins[i].fieldset);
          break;
        }
      }
    },

    build:function(elm,fieldset){
      for(var i=0; i<fieldset.length; i++){
        var field=fieldset[i];
        var fn=eval("nCMS.fieldset.utils."+field.type);
        console.log(field.type);
        fn.init(elm,field);
      }
    },

    utils:{
      add:function(elm,code){
        code='<div class="util">'+code+'</div>';
        elm.append(code);
      },

      select:{
        init:function(elm,field){
          var code=[];
          code.push('<label>'+field.name+'</label>');
          code.push("<select name='"+field.name+"'>");
          for(var i=0; i<field.data.length; i++){
            code.push('<option value="'+field.data[i][0]+'">'+field.data[i][0]+'</option>')
          }
          code.push("</select>");
          nCMS.fieldset.utils.add(elm,code.join(""));
        }
      },
      textfield:{
        init:function(elm,field){
          var code=[];
          code.push('<label>'+field.name+'</label>');
          code.push('<input name="'+field.name+'">');
          nCMS.fieldset.utils.add(elm,code.join(""));
        }
      },
      filemanager:{
        init:function(elm,field){

        }
      },
      formmanager:{
        init:function(elm,field){

        }
      },
      editor:{
        init:function(elm,field){

        }
      },
      imagemanager:{
        init:function(elm,field){

        }
      }
    }

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
