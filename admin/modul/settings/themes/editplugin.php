<div class="row h-100">
  <div class="col-md-2 leftsidebar">
    <button class="btn btn-primary w-100 save mb-3"><?php echo $lang->save ?></button>
    <a class="plugincode selected">Plugincode</a><br>
    <a class="template">Template</a>
  </div>
  <div class="col-md-10 pluginEditor" id="editor"></div>
</div>

<script src="js/ace/ace.js"></script>
<script src="js/height100.js"></script>
<script>
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/monokai");
  editor.session.setMode("ace/mode/javascript");
  editor.setOptions({
    fontSize: "13pt"
  });
  var pluginID=<?php echo $_GET["ID"] ?>;
</script>
<script>
  $(document).ready(function(){
    $.getJSON("?m=settings/themes&f=apiplugins&no=1&ID="+pluginID, function( data ) {
      pluginEditor.init(data);
    });
  });
  var pluginEditor={

    currentMode:"",
    cache:{},

    init:function(code){
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
</script>
<style>
  .selected{
    font-style: italic;
    text-decoration: underline!important;
  }
</style>
