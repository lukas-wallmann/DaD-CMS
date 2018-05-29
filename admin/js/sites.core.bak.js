$( function() {
  $(".section .dropper").click(function(){
    $(this).next().toggle("fast");
  })
  $("main").append('<ul id="elements" class="drag"></ul><ul id="content" class="drag"></ul>');
  $.getScript( "?m=sites&f=apigetjs&no=1&ID="+$("#layout").val() ).done(function( script, textStatus ) {
    nCMS.init();
  })
} );

var nCMS={
  plugins:[],
  registerPlugin:function(plugin){
    nCMS.plugins.push(plugin);
    $('#elements').append('<li class="draggable btn bg-secondary" data-pluginid="'+plugin.id+'" data-name="'+plugin.name+'"><div class="icon">'+plugin.icon+'</div></li>');
  },

  init:function(){
    for(var i=0; i<contents.length; i++){
      var content=contents[i];
      var plugin=nCMS.helpers.getPlugin(content.pluginID);
      $("#content").append(nCMS.buildPlugin(plugin,content));
    }
    nCMS.setFunctions();
  },

  buildPlugin:function(plugin,content={}){
    var code=[];
    code.push("<li class='itm' data-name='"+plugin.name+"' data-pluginid='"+plugin.id+"'><div class='handle'><div class='icon'>"+plugin.icon+"</div><div class='delete'><i class='fas fa-trash'></i></div></div><div class='content'><div class='name'>"+plugin.name+"</div>");
    for(var i=0; i<plugin.fieldset.length; i++){
      code.push(nCMS.getField(plugin.fieldset[i],content));
    }
    code.push("</div></li>");
    return code.join("");
  },

  getField:function(field,content){
    var data="";
    var code=[];
    if(field.type=="imagemanager" || field.type=="filemanager"){
      data=nCMS.helpers.getData(field.name,content,[]);
    }else{
      data=nCMS.helpers.getData(field.name,content);
    }
    switch(field.type) {
      case "textfield":
          code.push('<label>'+field.name+'</label><br><input class="saveme form-control" data-name="'+field.name+'" value="'+data+'">');
          break;
      case "editor":
          code.push('<div class="saveme div texteditor">'+data+'</div>');
          break;
      case "select":
          if(field.data!=undefined){
            code.push('<label>'+field.name+'</label><br>');
            code.push('<select class="saveme form-control" data-name="'+field.name+'">');
            for(var i=0; i<field.data.length; i++){
              var selected="";
              if(field.data[i][1]==data)selected=" selected";
              code.push('<option value="'+field.data[i][1]+'"'+selected+'>'+field.data[i][0]+'</option>');
            }
            code.push('</select><br>');
          }
          break;
      case "filemanager":
          code.push('<label>'+field.name+'</label><br><div class="filemanager" data-multipe="'+field.settings.multipe+'" data-allow="*"><input type="hidden" class="saveme" data-name="'+field.name+'"></div>');
          break;
      case "imagemanager":
          code.push('<label>'+field.name+'</label><br><div class="filemanager" data-multipe="'+field.settings.multipe+'" data-allow="image"><input type="hidden" class="saveme" data-name="'+field.name+'"></div>');
          break;
      case "formmanager":
          break;
      default:
          code.push("<div>unknown fieldtype:"+field.type+"</div>");
    }
    return code.join("");
  },

  helpers:{
    quillcount:0,
    getPlugin:function(id){
      for(var i=0; i<nCMS.plugins.length; i++){
        if(nCMS.plugins[i].id==id){
          return nCMS.plugins[i];
          break;
        }
      }
    },
    getData:function(name,content,standard=""){
      var data=eval("content."+name);
      if(data==undefined)data=standard;
      return data;
    }
  },

  setFunctions:function(){
    nCMS.dragger.setFunctions();
    $(".texteditor").each(function(){
      if($(this).attr("id")=="" || $(this).attr("id")==undefined){
        nCMS.helpers.quillcount++;
        $(this).attr("id","quill"+nCMS.helpers.quillcount);
        var toolbarOptions = [
          ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
          ['blockquote', 'code-block'],

          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

          [{ 'align': [] }]
        ];

        var quill = new Quill('#quill'+nCMS.helpers.quillcount, {
          modules: {
            toolbar: toolbarOptions
          },
          theme: 'snow'
        });
      }
    })
  },


  dragger:{
    setFunctions:function(){
      $( "#elements .draggable" ).draggable({ scroll: true, scrollSensitivity: 100, helper:"clone", drag:function(){
        nCMS.dragger.checkHit($(".ui-draggable-dragging").offset())
      }, stop:function(){
        $("#placeholder").replaceWith(nCMS.buildPlugin(nCMS.helpers.getPlugin($(this).attr("data-pluginid"))));
        nCMS.setFunctions();
      } });

      $( "#content .itm" ).draggable({ handle: ".handle .icon",
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
