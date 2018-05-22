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
  editor.session.setUseWorker(false);
  editor.setOptions({
    fontSize: "13pt"
  });
  var pluginID=<?php echo $_GET["ID"] ?>;
</script>
<script src="js/pluginEditor.js"></script>
<style>
  .selected{
    font-style: italic;
    text-decoration: underline!important;
  }
</style>
