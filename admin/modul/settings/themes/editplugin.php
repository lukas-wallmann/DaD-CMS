<div class="row h-100">
  <div class="col-md-2 leftsidebar">
    <button class="btn btn-primary w-100 save mb-3"><?php echo $lang->save ?></button>
    <a class="plugincode selected">Plugincode</a>
    <a class="template">Template</a>
  </div>
  <div class="col-md-10 codeEditor" id="editor"></div>
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
    pluginEditor.init();
  });
  var pluginEditor={

    currentMode:"",
    cache:{},

    init:function(){
      $(".leftsidebar .plugincode").click(pluginEditor.getPluginCode);
      $(".leftsidebar .template").click(pluginEditor.getTemplateCode);
      codeEditor.getPluginCode();
    },

    getPluginCode:function(){
      $(".selected").removeClass("selected");
      $(".leftsidebar .plugincode").addClass("selected");
      codeEditor.saveTemp();
      codeEditor.currentMode="plugin";
    },

    getTemplateCode:function(){
      $(".selected").removeClass("selected");
      $(".leftsidebar .template").addClass("selected");
      codeEditor.saveTemp();
      codeEditor.currentMode="template";
    },

    saveTemp:function(){
      if(codeEditor.currentMode=="plugin"){
        codeEditor.cache.plugin=editor.getValue();
      }else if(codeEditor.currentMode=="template"){
        codeEditor.cache.template=editor.getValue();
      }
    }
  }
</script>
<style>
  .selected{
    font-style: italic;
    text-decoration: underline;
  }
</style>
