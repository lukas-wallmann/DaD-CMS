
$.fn.formManager = function() {

    var template=JSON.parse($(this).find(".template").text());
    var data=JSON.parse($(this).find(".saveme").text());
    var main=this;

    var fm={
      id:0,
      init:function(){
        main.append('<div class="form"></div><button class="btn btn-primary new">'+consts.newFormField+'</button>');
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
              $(".cmd select.type").change(fm.refreshCMD);
            }
          )
        });
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
        $(".databuilder .add").click(function(e){
          e.preventDefault();
          cmd(
            '<label>name</label><input class="form-control name"><label>value</label><input class="form-control value">',
            function(){$(".cmd .databuilder ul").append("<li><span class='icon mr-3'><i class='fas fa-bars'></i></span><span class='name'>"+$(".cmd").last().find(".name").val()+"</span>,<span class='value'>"+$(".cmd").last().find(".value").val()+"</span></li>")},
            function(){$(".cmd").last().find(".name").change(function(){
              var valfield=$(this).parent().find(".value");
              if(valfield.val()==""){
                valfield.val($(this).val());
              }
            })}
          )
        })
      },

      addFieldToCMD:function(field,data=""){
        var code=[];
        switch(field.type) {
            case "textfield":
                code.push('<label>'+field.name+'</label><input class="form-control saveme" data-name="'+field.name+'" value="'+data+'">')
                break;
            case "databuilder":
                code.push('<label>'+field.name+'</label><div class="databuilder"><ul></ul><button class="btn btn-primary add"><i class="fas fa-plus-square"></i></button><textarea class="form-control saveme" style="display:none" data-name="'+field.name+'">'+data+'</textarea></div>');
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

      addFromCMD:function(){

      }
    }
    fm.init();
    return this;
};
