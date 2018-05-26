<div class="row h-100">
  <div class="col-md-2 leftsidebar">
    <button class="btn btn-primary w-100 save mb-3"><?php echo $lang->save ?></button>
    <div class="themepart"><b><?php echo $lang->themeParts ?></b><span class="b"><i class="fas fa-plus-square ml-2"></i></span></div>
    <div class="maincode selected" data-table="theme" data-id="<?php echo $_GET["ID"] ?>"><span class="name"><?php echo $lang->mainCode ?></span></div>
    <div class="menu parts"></div>
    <div class="themeplugin mt-3"><b><?php echo $lang->themePlugins ?></b><span class="b"><i class="fas fa-plus-square ml-2"></i></span></div>
    <div class="menu plugins"></div>
  </div>
  <div class="col-md-10 codeEditor" id="editor"></div>
</div>

<script src="js/cmd.js"></script>
<script src="js/ace/ace.js"></script>
<script src="js/height100.js"></script>
<script>
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/monokai");
  editor.session.setMode("ace/mode/html");
  editor.session.setUseWorker(false);
  editor.setOptions({
    fontSize: "13pt"
  });
  var layoutID=<?php echo $_GET["ID"] ?>;
  var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>

<script src="js/codeEditor.js"></script>
