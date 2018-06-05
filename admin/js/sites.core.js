$( function() {
  $(".section .dropper").click(function(){
    $(this).next().toggle("fast");
  })
  $("main").append('<ul id="elements" class="drag"></ul><ul id="content" class="drag"></ul>');
  $.getScript( "?m=sites&f=apigetjs&no=1&ID="+$("#layout").val() ).done(function( script, textStatus ) {
    DaDCMS.init();
  })
} );

var DaDCMS={
  plugins:[],
  registerPlugin:function(plugin){
    DaDCMS.plugins.push(plugin);
    $('#elements').append('<li class="draggable btn bg-secondary" data-pluginid="'+plugin.id+'" data-name="'+plugin.name+'"><div class="icon">'+plugin.icon+'</div></li>');
  },

  init:function(){
    if($("#contents").text()=="")$("#contents").text("[]");
    var contents=JSON.parse($("#contents").text());
    for(var i=0; i<contents.length; i++){
      var content=contents[i];
      var plugin=DaDCMS.helpers.getPlugin(content.pluginID);
      $("#content").append(DaDCMS.buildPlugin(plugin,content));
    }
    DaDCMS.setFunctions();
  },

  buildPlugin:function(plugin,content={}){
    var code=[];
    code.push("<li class='itm' data-name='"+plugin.name+"' data-pluginid='"+plugin.id+"'><div class='handle'><div class='icon'>"+plugin.icon+"</div><div class='delete'><i class='fas fa-trash'></i></div></div><div class='content'><div class='name'>"+plugin.name+"</div>");
    for(var i=0; i<plugin.fieldset.length; i++){
      code.push(DaDCMS.getField(plugin.fieldset[i],content));
    }
    code.push("</div></li>");
    return code.join("");
  },

  getField:function(field,content){
    var data="";
    var code=[];
    if(field.type=="imagemanager" || field.type=="filemanager"){
      data=DaDCMS.helpers.getData(field.name,content,[]);
    }else{
      data=DaDCMS.helpers.getData(field.name,content);
    }
    switch(field.type) {
      case "textfield":
          code.push('<label>'+field.name+'</label><br><input class="saveme form-control" data-name="'+field.name+'" value="'+data+'">');
          break;
      case "editor":
          code.push('<div class="saveme div texteditor" data-name="'+field.name+'">'+data+'</div>');
          break;
      case "select":
            code.push('<label>'+field.name+'</label><br>');
            var dataf=field.dataURL!=undefined;
            var dataclass="";
            var dataattr="";
            if(dataf){
              dataclass=" getdata";
              dataattr=" data-url='"+field.dataURL+"'";
            }
            code.push('<select class="saveme form-control'+dataclass+'" data-name="'+field.name+'" data-val="'+data+'"'+dataattr+'>');
            if(field.data!=undefined){
              for(var i=0; i<field.data.length; i++){
                var selected="";
                if(field.data[i][1]==data)selected=" selected";
                code.push('<option value="'+field.data[i].value+'"'+selected+'>'+field.data[i].name+'</option>');
              }
            }
            code.push('</select><br>');

          break;
      case "filemanager":
          var val=JSON.stringify(data);
          if(field.settings.multiple==false)val="["+val+"]";
          code.push('<label>'+field.name+'</label><br><div class="filemanager" data-multiple="'+field.settings.multiple+'" data-allow="*"><textarea style="display:none" class="saveme json multiple'+field.settings.multiple+'" data-name="'+field.name+'">'+val+'</textarea></div>');
          break;
      case "imagemanager":
          var val=JSON.stringify(data);
          if(field.settings.multiple==false && val!="[]")val="["+val+"]";
          code.push('<label>'+field.name+'</label><br><div class="filemanager" data-multiple="'+field.settings.multiple+'" data-allow="image" data-formats=\''+JSON.stringify(field.settings.formats)+'\'><textarea style="display:none" class="saveme json multiple'+field.settings.multiple+'" data-name="'+field.name+'">'+val+'</textarea></div>');
          break;
      case "formmanager":
          if(data.fields==undefined){
            data={fields:[]};
          }
          code.push('<div class="formmanager"><textarea style="display:none" class="template">'+JSON.stringify(field.fields)+'</textarea><textarea style="display:none" class="saveme json formfields" data-name="'+field.name+'">'+JSON.stringify(data.fields)+'</textarea></div>');
          break;
      default:
          code.push("<div>unknown fieldtype:"+field.type+"</div>");
    }
    return code.join("");
  },

  helpers:{
    quillcount:0,
    getPlugin:function(id){
      for(var i=0; i<DaDCMS.plugins.length; i++){
        if(DaDCMS.plugins[i].id==id){
          return DaDCMS.plugins[i];
          break;
        }
      }
    },
    getData:function(name,content,standard=""){
      var data=eval("content."+name);
      if(data==undefined)data=standard;
      return data;
    },
    slug:function(str) {
      str = str.replace(/^\s+|\s+$/g, ''); // trim
      str = str.toLowerCase();

      var replace=[["ü","ue"],["ä","ae"],["ö","oe"],["ß","ss"]];
      for(var i=0; i<replace.length; i++){
        str=str.split(replace[i][0]).join(replace[i][1]);
      }

      // remove accents, swap ñ for n, etc
      var from = "ÁÄÂÀÃÅČÇĆĎÉĚËÈÊẼĔȆÍÌÎÏŇÑÓÖÒÔÕØŘŔŠŤÚŮÜÙÛÝŸŽáäâàãåčçćďéěëèêẽĕȇíìîïňñóöòôõøðřŕšťúůüùûýÿžþÞĐđßÆa·/_,:;";
      var to   = "AAAAAACCCDEEEEEEEEIIIINNOOOOOORRSTUUUUUYYZaaaaaacccdeeeeeeeeiiiinnooooooorrstuuuuuyyzbBDdBAa------";
      for (var i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
      }

      str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

      return str;
    },
    updateURL:function(){
      if(!$('#fixurl').is(":checked")){
        $("#url").val(DaDCMS.helpers.slug($("#title").val()));
      }
    }
  },

  updateContentsVal:function(){
    var tmp=[];
    $("#content > li").each(function(){
      var data={};
      data.pluginID=$(this).attr("data-pluginid");
      $(this).find('.saveme').each(function(){
        var name=$(this).attr("data-name");
        var value=$(this).val();
        if($(this).hasClass("json")){
          value=JSON.parse($(this).text());
          if($(this).hasClass("multiplefalse"))value=value[0];
        }
        if($(this).hasClass("texteditor")){
          value=$(this).find('.ql-editor').html();
        }
        if($(this).hasClass("formfields")){
          data[name]={fields:value};
        }else{
          data[name]=value;
        }
      });
      tmp.push(data);
    });
    $("#contents").text(JSON.stringify(tmp));
  },


  setFunctions:function(){
    DaDCMS.dragger.setFunctions();
    DaDCMS.helpers.updateURL();

    $("#title").change(DaDCMS.helpers.updateURL).keyup(DaDCMS.helpers.updateURL);

    $(".fixedtop .save").click(function(e){
      e.preventDefault();
      DaDCMS.updateContentsVal();
      $("form").submit();
    });
    $(".texteditor").each(function(){
      if($(this).attr("id")=="" || $(this).attr("id")==undefined){
        DaDCMS.helpers.quillcount++;
        $(this).attr("id","quill"+DaDCMS.helpers.quillcount);
        var toolbarOptions = [
          ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
          ['blockquote', 'code-block'],

          [{ 'list': 'ordered'}, { 'list': 'bullet' }],
          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

          [{ 'align': [] }]
        ];

        var quill = new Quill('#quill'+DaDCMS.helpers.quillcount, {
          modules: {
            toolbar: toolbarOptions
          },
          theme: 'snow'
        });
      }
    })
    $(".filemanager").each(function(){
      if(!$(this).hasClass("hasfilemanager"))$(this).fileManager().addClass("hasfilemanager");
    });
    $(".formmanager").each(function(){
      if(!$(this).hasClass("hasformmanager"))$(this).formManager().addClass("hasformmanager");
    });
    $('select.getdata').each(function(){
      var elm=$(this);
      $.ajax({url:elm.attr("data-url")}).done(function(d){
        var data=JSON.parse(d);
        for(var i=0; i<data.length; i++){
          var selected="";
          if(data[i][1]==elm.attr("data-val"))selected=" selected";
          elm.append('<option value="'+data[i][1]+'"'+selected+'>'+data[i][0]+'</option>');
        }
      });
    })
  },

  dragger:{
    setFunctions:function(){
      $( "#elements .draggable" ).draggable({ scroll: true, scrollSensitivity: 100, helper:"clone", drag:function(){
        DaDCMS.dragger.checkHit($(".ui-draggable-dragging").offset())
      }, stop:function(){
        $("#placeholder").replaceWith(DaDCMS.buildPlugin(DaDCMS.helpers.getPlugin($(this).attr("data-pluginid"))));
        DaDCMS.setFunctions();
      } });

      $( "#content .itm" ).draggable({ handle: ".handle .icon",
       drag:function(){DaDCMS.dragger.checkHit($(this).offset())},
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
