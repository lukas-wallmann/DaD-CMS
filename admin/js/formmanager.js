
$.fn.formManager = function() {

    var template=JSON.parse($(this).find(".template").text());
    var data=JSON.parse($(this).find(".saveme").text());
    var main=this;

    var fm={
      id:0,

      init:function(){
        main.append('<ul class="form"></ul><button class="btn btn-primary new">'+consts.newFormField+'</button>');
        fm.buildFields();
        main.find(".new").click(function(){
          var code=[];
          code.push("<select class='type form-control mb-3'><option value=''>"+consts.pleaseChooseFormFieldType+"</option>");
          for(var i=0; i<template.length; i++){
            code.push('<option value="'+template[i].type+'">'+template[i].type+'</option>')
          }
          code.push("</select>");
          code.push('<div class="fieldset mb-3"></div>');
          cmd(
            code.join(""),
            fm.addFromCMD,
            function(){
              $(".cmd select.type").change(fm.refreshCMD).focus();
            }
          )
        });
      },

      buildFields:function(){
         data=JSON.parse($(main).find(".saveme").text());
         var code=[];
         for(var i=0; i<data.length; i++){
           code.push('<li><div class="buttons"><div class="drag"><i class="fas fa-bars"></i></div><div class="delete"><i class="fas fa-trash-alt"></i></div></div><div class="content">');
           code.push(fm.buildFieldset(fm.helpers.getPlugin(data[i].type),data[i]));
           code.push("</div></li>");
         }
         $(main).find(".form").html(code.join(""));
         $(main).find(".form").sortable({handle:".drag",stop:fm.actFormData});
         $(main).find(".form li .buttons .delete").click(function(){
           $(this).parent().parent().remove();
           fm.actFormData();
         })
         $(main).find(".form li label").click(function(e){
           fm.editByCMD($(this).parent());
         });
         $(".quilleditor").each(function(){
           if($(this).attr("id")=="" || $(this).attr("id")==undefined){
             fm.helpers.quillcount++;
             $(this).attr("id","quilleditor"+fm.helpers.quillcount);
             var toolbarOptions = [
               ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
               ['blockquote', 'code-block'],

               [{ 'list': 'ordered'}, { 'list': 'bullet' }],
               [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

               [{ 'align': [] }]
             ];

             var quill = new Quill('#quilleditor'+fm.helpers.quillcount, {
               modules: {
                 toolbar: toolbarOptions
               },
               theme: 'snow'
             });
           }
         })
      },

      actFormData:function(){
        var tmp=[];
        $(main).find(".form li").each(function(){
          tmp.push(JSON.parse($(this).find(".data").text()));
        })
        main.find(".saveme").text(JSON.stringify(tmp));
      },

      buildFieldset:function(plugin,data){
        var code=[];
        code.push("<label>"+data.name+"<span class='edit'><i class='fas fa-pen-square ml-1'></i></span></label>");
        code.push('<textarea style="display:none" class="plugin">'+JSON.stringify(plugin)+'</textarea>');
        code.push('<textarea style="display:none" class="data">'+JSON.stringify(data)+'</textarea>');
        switch (data.type) {
          case "select":
            code.push("<select class='form-control'>");
            for(var i=0; i<data.data.length; i++){
              code.push('<option value="'+data.data[i].value+'">'+data.data[i].name+'</option>');
            }
            code.push("</select>");
            break;

          case "editor":
            code.push("<div class='quilleditor'></div>");
            break;

          case "textarea":
            code.push("<textarea class='form-control'></textarea>");
            break;

          case "registernewsletter":
            code.push("<input type='checkbox' class='form-control'>");
            break;

          case "sendcopy":
            code.push("<input type='checkbox' class='form-control'>");
            break;

          case "checkbox":
            code.push("<input type='checkbox' class='form-control'>");
            break;

          default:
            code.push('<input class="form-control">');
        }
        return code.join("");
      },

      helpers:{
        quillcount:0,
        getPlugin:function(type){
          for(var i=0; i<template.length; i++){
            if(template[i].type==type){
              return template[i];
              break;
            }
          }
        }
      },

      refreshCMD:function(){
        var val=$(".cmd select.type").val();
        $(".cmd .fieldset").html("");
        var fields=[];
        for(var i=0; i<template.length; i++){
          if(template[i].type==val)fields=template[i].fieldset;
        }
        for(var i=0; i<fields.length; i++){
          fm.addFieldToCMD(fields[i]);
        }
        fm.setDataBuilderFunctions();
      },

      setDataBuilderFunctions:function(){
        $(".databuilder .add").click(function(e){
          e.preventDefault();
          cmd(
            '<label>name</label><input class="form-control name"><label>value</label><input class="form-control value">',
            function(){
              $(".cmd .databuilder ul").append("<li><span class='icon mr-3'><i class='fas fa-bars'></i></span><span class='name'>"+$(".cmd").last().find(".name").val()+"</span>,<span class='value'>"+$(".cmd").last().find(".value").val()+"</span><span class='delete ml-3'><i class='fas fa-trash-alt'></i></span></li>")
              fm.setDataBuilderFunctions();
              fm.updateDataBuilderVal($(".cmd .databuilder"));
            },
            function(){$(".cmd").last().find(".name").change(function(){
              var valfield=$(this).parent().find(".value");
              if(valfield.val()==""){
                valfield.val($(this).val());
              }
            }).focus()}
          )
        });
        $(".cmd .databuilder ul").sortable({handle:".icon",stop:function(e,ui){fm.updateDataBuilderVal(ui.item.parent().parent())}});
        $('.cmd .databuilder ul li .delete').click(function(){
          var parent=$(this).parent().parent().parent();
          $(this).parent().remove();
          fm.updateDataBuilderVal(parent);
        })
        $(".cmd .databuilder ul li .name,.cmd .databuilder ul li .value").click(function(){
          var name=$(this).parent().find(".name").text();
          var value=$(this).parent().find(".value").text();
          var li=$(this).parent();
          cmd(
            '<label>name</label><input class="form-control name" value="'+name+'"><label>value</label><input class="form-control value" value="'+value+'">',
            function(){
              $(li).html("<span class='icon mr-3'><i class='fas fa-bars'></i></span><span class='name'>"+$(".cmd").last().find(".name").val()+"</span>,<span class='value'>"+$(".cmd").last().find(".value").val()+"</span><span class='delete ml-3'><i class='fas fa-trash-alt'></i></span>")
              fm.updateDataBuilderVal(li.parent().parent());
              fm.setDataBuilderFunctions();
            },
            function(){$(".cmd").last().find(".name").change(function(){
              var valfield=$(this).parent().find(".value");
              if(valfield.val()==""){
                valfield.val($(this).val());
              }
            });
            $(".cmd").last().find("input").first().focus();
            }
          )
        })
      },

      updateDataBuilderVal:function(elm){
        var tmp=[];
        elm.find("li").each(function(){
          tmp.push({name:$(this).find(".name").text(),value:$(this).find(".value").text()});
        });
        elm.find(".saveme").text(JSON.stringify(tmp));
      },


      addFieldToCMD:function(field,data=""){
        var code=[];
        switch(field.type) {
            case "textfield":
                code.push('<label>'+field.name+'</label><input class="form-control saveme" data-name="'+field.name+'" value="'+data+'">')
                break;
            case "databuilder":
                code.push('<label>'+field.name+'</label><div class="databuilder"><ul></ul><button class="btn btn-primary add"><i class="fas fa-plus-square"></i></button><textarea class="form-control saveme json" style="display:none" data-name="'+field.name+'">'+data+'</textarea></div>');
                break;
            case "checkbox":
                var checked="";
                if(data==1)checked=" checked";
                fm.id++;
                code.push('<input type="checkbox" class="saveme mr-3" id="'+fm.id+'" data-name="'+field.name+'"'+checked+'><label for="'+fm.id+'">'+field.name+'</label>')
                break;
            default:
                code.push("unknown type:"+field.type);
        }
        $('.cmd .fieldset').append('<div class="mb-3">'+code.join("")+"</div>");
      },

      editByCMD:function(elm){
        cmd(
          '<div class="fieldset"></div>',
          function(){
            var data={};
              data.type=JSON.parse($(elm).find(".data").text()).type;
              $(".cmd .fieldset .saveme").each(function(){
                var name=$(this).attr("data-name");
                var value=$(this).val();
                if($(this).is("input[type='checkbox']")){
                  if($(this).is(":checked")){
                    value=1;
                  }else{
                    value=0;
                  }
                }
                if($(this).hasClass("json"))value=JSON.parse($(this).text());
                data[name]=value;
            });


              $(elm).find(".data").text(JSON.stringify(data));
              fm.actFormData();
              fm.buildFields();
          },
          function(){
            var plugin=JSON.parse(elm.find(".plugin").text());
            var data=JSON.parse(elm.find(".data").text());
            for(var i=0; i<plugin.fieldset.length; i++){
              var name=plugin.fieldset[i].name;
              var dataf=data[name];
              if(plugin.fieldset[i].type=="databuilder")dataf=JSON.stringify(dataf);
              fm.addFieldToCMD(plugin.fieldset[i],dataf);
            }
            $(".databuilder").each(function(){
              var data=JSON.parse($(this).find(".saveme").text());
              for(var i=0; i<data.length; i++){
                $(this).find("ul").append("<li><span class='icon mr-3'><i class='fas fa-bars'></i></span><span class='name'>"+data[i].name+"</span>,<span class='value'>"+data[i].value+"</span><span class='delete ml-3'><i class='fas fa-trash-alt'></i></span></li>")
              }
            });
            fm.setDataBuilderFunctions();
          },
        );
      },

      addFromCMD:function(){
        var data={};
        if($("select.type").val()!=""){
          data.type=$("select.type").val();
          $(".cmd .fieldset .saveme").each(function(){
            var name=$(this).attr("data-name");
            var value=$(this).val();
            if($(this).is("input[type='checkbox']")){
              if($(this).is(":checked")){
                value=1;
              }else{
                value=0;
              }
            }
            if($(this).hasClass("json"))value=JSON.parse($(this).text());
            data[name]=value;
        });

          var dataTemp=JSON.parse($(main).find(".saveme").text());
          dataTemp.push(data);
          $(main).find(".saveme").text(JSON.stringify(dataTemp));
          fm.buildFields();
        }
      }
    }
    fm.init();
    return this;
};
