$( function() {
  $(".section .dropper").click(function(){
    $(this).next().toggle("fast");
  })
  $("main").append('<div class="elementslider"><div class="innner"><ul id="elements" class="drag"></ul></div><div class="buttons"><div class="left"><i class="fas fa-arrow-left"></i></div><div class="right"><i class="fas fa-arrow-right"></i></div></div></div><ul id="content" class="drag"></ul>');
  $.getScript( "?m=sites&f=apigetjs&no=1&ID="+$("#layout").val() ).done(function( script, textStatus ) {
    DaDCMS.init();
    elementslider.init();
  });


} );

var elementslider={

  display:0,
  currentpos:0,

  init:function(){
    var width=$("#elements li").length*75;
    $("#elements").width(width+"px");
    $(".elementslider .left").addClass("inactive");
    elementslider.resize();
    $(window).resize(elementslider.resize);
    $(".elementslider .left").click(elementslider.left);
    $(".elementslider .right").click(elementslider.right);

  },

  left:function(){
    var maxmove=0;
    if(elementslider.currentpos<maxmove){
      var move=elementslider.currentpos+elementslider.display-1;
      if(move>maxmove){
        move=maxmove;
        $(".elementslider .left").addClass("inactive");
      }else{
        $(".elementslider .left").removeClass("inactive");
      }
      $(".elementslider .right").removeClass("inactive");
      elementslider.currentpos=move;
      $('#elements').animate({left:(move*75)+"px"},500);
    }
  },
  right:function(){
    var maxmove=-$('#elements li').length+elementslider.display;
    if(elementslider.currentpos>maxmove){
      var move=elementslider.currentpos-elementslider.display+1;
      if(move<maxmove){
        move=maxmove;
        $(".elementslider .right").addClass("inactive");
      }else{
        $(".elementslider .right").removeClass("inactive");
      }
      $(".elementslider .left").removeClass("inactive");
      elementslider.currentpos=move;
      $('#elements').animate({left:(move*75)+"px"},500);
    }
  },

  resize:function(){
    var totalwidth=$('.elementslider').parent().width();
    var widthwithoutbuttons=totalwidth-$(".elementslider .left").width()-$(".elementslider .right").width();
    var widthto=Math.floor(widthwithoutbuttons/75)*75;
    elementslider.display=Math.floor(widthwithoutbuttons/75);
    if(widthto>=$("#elements").width()){
      widthto=$("#elements").width();
      $('.elementslider .right').addClass("inactive");
      $("#elements").css("left",0);
    }else{
      $('.elementslider .right').removeClass("inactive");
    }
    widthto=widthto+$(".elementslider .left").width()+$(".elementslider .right").width();
    $('.elementslider').width(widthto+"px");

  }
}

var DaDCMS={
  plugins:[],
  registerPlugin:function(plugin){
    DaDCMS.plugins.push(plugin);
    $('#elements').append('<li class="draggable btn bg-secondary" data-name="'+plugin.name+'"><div class="icon">'+plugin.icon+'</div></li>');
  },

  init:function(){
    if($("#contents").text()=="")$("#contents").text("[]");
    var contents=JSON.parse($("#contents").text());
    for(var i=0; i<contents.length; i++){
      var content=contents[i];
      var plugin=DaDCMS.helpers.getPlugin(content.pluginName);
      $("#content").append(DaDCMS.buildPlugin(plugin,content));
    }
    DaDCMS.setFunctions();
  },

  buildPlugin:function(plugin,content={}){
    var code=[];
    code.push("<li class='itm' data-name='"+plugin.name+"'><div class='handle'><div class='icon'>"+plugin.icon+"</div><div class='delete'><i class='fas fa-trash'></i></div></div><div class='content'><div class='name'>"+plugin.name+"</div>");
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
      case "codeEditor":
          var codef=data;
          if(data.code!=undefined)codef=data.code;
          var mode="";
          if(data.mode!=undefined)mode=data.mode;
          var select="";
          if(field.mode=="selectable"){
            select="<select class='form-control mode'>";
            var parts=field.select.split(",");
            for(var i=0; i<parts.length; i++){
              if(i==0 && mode=="")mode=parts[i];
              var selected="";
              if(mode==parts[i])selected=" selected";
              select+='<option value="'+parts[i]+'"'+selected+'>'+parts[i]+"</option>";
            }
            select+="</select>";
          }else{
            mode=field.mode;
          }
          if(field.replaceChars==false){
            codef=codef.split("&").join("\&amp;").split(">").join("\&gt;").split("<").join("\&lt;");
          }else{
            codef=DaDCMS.helpers.undoHTML(codef);
          }
          code.push(select+'<div class="saveme codeEditor" style="height:200px" data-name="'+field.name+'" data-mode="'+mode+'" data-replaceChars="'+field.replaceChars+'">'+codef+'</div>');
          break;
      case "textarea":
          code.push('<label>'+field.name+'</label><br><textarea class="saveme textarea form-control" data-name="'+field.name+'">'+data+'</textarea>');
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
                if(field.data[i].value==data)selected=" selected";
                code.push('<option value="'+field.data[i].value+'"'+selected+'>'+field.data[i].name+'</option>');
              }
            }
            code.push('</select>');

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
      case "videomanager":
          var val=JSON.stringify(data);
          code.push('<label>'+field.name+'</label><br><div class="videomanager"><textarea style="display:none" class="saveme json" data-name="'+field.name+'">'+val+'</textarea></div>');
          break;

      default:
          code.push("<div>unknown fieldtype:"+field.type+"</div>");
    }
    return '<div class="elm mb-3">'+code.join("")+"</div>";
  },

  helpers:{
    quillcount:0,
    codeEditors:[],
    replaceHTML:function(s){
      return s.split("&").join("\&amp;").split(">").join("\&gt;").split("<").join("\&lt;").split("\n").join("<br>").split(" ").join("\&nbsp;");
    },
    undoHTML:function(s){
      return s.split("<br>").join("\n").split(" ").join(" ").split(">").join("\&gt;").split("<").join("\&lt;");
    },
    getPlugin:function(id){
      for(var i=0; i<DaDCMS.plugins.length; i++){
        if(DaDCMS.plugins[i].name==id){
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
        var slug=DaDCMS.helpers.slug($("#title").val());
        var add="";
        var i=0;
        while(siteurls.indexOf(slug+add)!=-1){
          i++;
          add="-"+i;
        }
        $("#url").val(slug+add);
      }
    }
  },

  updateContentsVal:function(){
    var tmp=[];
    var texts="";
    var headlines="";
    $("#content > li").each(function(){
      var data={};
      data.pluginName=$(this).attr("data-name");
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
        if($(this).hasClass("codeEditor")){
          var mode=$(this).attr("data-mode");
          var code=DaDCMS.helpers.codeEditors[$(this).attr("data-id")].getValue();
          if($(this).attr("data-replacechars")=="true"){
            code=DaDCMS.helpers.replaceHTML(code);
          }
          value={mode:mode,code:code};
        }
        if($(this).hasClass("formfields")){
          data[name]={fields:value};
        }else{
          data[name]=value;
        }

        if(!$(this).hasClass("json") && !$(this).is("select") && !$(this).is("input[type='checkbox']")){
          if($(this).parent().parent().parent().attr("data-name")=="headline"){
            headlines+=value+" ";
          }else if($(this).hasClass("texteditor")){
            texts+=value+" ";
          }
        }
      });
      tmp.push(data);
    });
    $("#contents").text(JSON.stringify(tmp));
    DaDCMS.updateMeta(headlines,texts);
  },

  updateMeta:function(headlines,texts){

    if(!$("#fixmeta").is(":checked")){

      $("body").append("<div id='metahelper'><div class='headlines'>"+headlines+" "+headlines+"</div> "+texts+"</div>"); //double prio for headline texts
      var text=removeShorts(removeUnwanted($('#metahelper').text()).split(" "));
      $("#metahelper .headlines").remove();
      var $text=$('#metahelper').text();
      $('#metahelper').remove();
      var metatags=count(text,128);
      var metaDescription=trimlen($text,255);
      $("#metatags").val(metatags);
      $('#metadescription').text(metaDescription);

      function removeUnwanted(s){
        return s.split(".").join("").split(",").join("").split(";").join("").split("!").join("").split("?").join("").split(":").join("");
      }

      function trimlen(t,l){
        t=t.split(" ");
        var words=[];
        var length=0;
        for(var i=0; i<t.length; i++){
          length+=t[i].length+1;
          if(length>l)break;
          words.push(t[i]);
        }
        return words.join(" ");
      }

      function removeShorts(a){
        var tmp=[];
        for(var i=0; i<a.length; i++){
          if(a[i].length>3)tmp.push(a[i]);
        }
        return tmp;
      }

      function count(array_elements, to) {

          var words=[];
          array_elements.sort();
          var length=0;
          var current="";
          for(var i=0; i<array_elements.length; i++){
            if(current!=array_elements[i]){
              length+=array_elements[i].length+2;
              if(length>to)break;
              words.push(array_elements[i]);
              current=array_elements[i];
            }

          }
          return words.join(", ");
      }


    }
  },

  setFunctions:function(){
    DaDCMS.dragger.setFunctions();

    if(editMode=="site"){
      DaDCMS.helpers.updateURL();
      $("#title").change(DaDCMS.helpers.updateURL).keyup(DaDCMS.helpers.updateURL);
    }
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
    $(".videomanager").each(function(){
      if(!$(this).hasClass("hasvideomanager"))$(this).videoManager().addClass("hasvideomanager");
    });
    $('select.getdata').each(function(){
      var elm=$(this);
      elm.removeClass("getdata");
      $.ajax({url:elm.attr("data-url")}).done(function(d){
        var data=JSON.parse(d);
        for(var i=0; i<data.length; i++){
          var selected="";
          if(data[i][1]==elm.attr("data-val"))selected=" selected";
          elm.append('<option value="'+data[i][1]+'"'+selected+'>'+data[i][0]+'</option>');
        }
      });
    })
    $('.codeEditor').each(function(){
      if($(this).attr("data-id")==undefined){
        $(this).attr("data-id",DaDCMS.helpers.codeEditors.length);
        $(this).attr("id","codeeditor-"+DaDCMS.helpers.codeEditors.length);
        var editor=ace.edit("codeeditor-"+$(this).attr("data-id"));
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/"+$(this).attr("data-mode"));
        editor.session.setUseWorker(false);
        editor.setOptions({
          fontSize: "13pt"
        });
        DaDCMS.helpers.codeEditors.push(editor);
        $(this).parent().find("select.mode").change(function(){
          var ed=$(this).parent().find(".codeEditor");
          ed.attr("data-mode",$(this).val());
          DaDCMS.helpers.codeEditors[ed.attr("data-id")].session.setMode("ace/mode/"+$(this).val());
        })
      }
    })
  },

  dragger:{
    setFunctions:function(){
      $( "#elements .draggable" ).draggable({ scroll: true, scrollSensitivity: 100, helper:"clone", drag:function(){
        DaDCMS.dragger.checkHit($(".ui-draggable-dragging").offset())
      }, stop:function(){
        $("#placeholder").replaceWith(DaDCMS.buildPlugin(DaDCMS.helpers.getPlugin($(this).attr("data-name"))));
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
