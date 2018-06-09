$(document).ready(function(){
  $.getJSON("?m=settings/themes&f=apiplugins&no=1&ID="+pluginID, function( data ) {
    pluginEditor.init(data);
  });
});
var editor="";
var pluginEditor={

  currentMode:"",
  cache:{},
  editorwasset:false,

  init:function(code){

    if(!pluginEditor.editorwasset){
      editor = ace.edit("editor");
      editor.setTheme("ace/theme/monokai");
      editor.session.setMode("ace/mode/javascript");
      editor.session.setUseWorker(false);
      editor.setOptions({
        fontSize: "13pt"
      });
      pluginEditor.editorwasset=true;
    }

    pluginEditor.cache=code;
    $(".leftsidebar .plugincode").click(pluginEditor.getPluginCode);
    $(".leftsidebar .template").click(pluginEditor.getTemplateCode);
    $(".leftsidebar .save").click(pluginEditor.save);
    $(document).bind('keydown', function(e) {
      if(e.ctrlKey && (e.which == 83)) {
        e.preventDefault();
        pluginEditor.save();
        return false;
      }
    });
    pluginEditor.getPluginCode();
  },

  getPluginCode:function(){
    $(".selected").removeClass("selected");
    $(".leftsidebar .plugincode").addClass("selected");
    pluginEditor.saveTemp();
    pluginEditor.currentMode="plugin";
    editor.session.setMode("ace/mode/javascript");
    editor.setValue(pluginEditor.cache.code);
  },

  getTemplateCode:function(){
    $(".selected").removeClass("selected");
    $(".leftsidebar .template").addClass("selected");
    pluginEditor.saveTemp();
    pluginEditor.currentMode="template";
    editor.session.setMode("ace/mode/html");
    editor.setValue(pluginEditor.cache.template);
  },

  saveTemp:function(){
    if(pluginEditor.currentMode=="plugin"){
      pluginEditor.cache.code=editor.getValue();
    }else if(pluginEditor.currentMode=="template"){
      pluginEditor.cache.template=editor.getValue();
    }
  },

  save:function(){
    pluginEditor.saveTemp();
    var data={};
    data.cache=JSON.stringify(pluginEditor.cache);
    $.ajax({
      type: "POST",
      url: "?m=settings/themes&f=apiplugins&no=1&ID="+pluginID,
      data: data
    }).done(function(){
      alert("done");
    });
  }
}
