<div class="row h-100">
  <div class="col-md-2 leftsidebar">
    <b><?php echo $lang->themeParts ?></b><br>
    <b><?php echo $lang->themePlugins ?></b><br>
  </div>
  <div class="col-md-10 codeEditor" id="editor"></div>
</div>

<script src="js/ace/ace.js"></script>
<script src="js/height100.js"></script>
<script>
  var editor = ace.edit("editor");
  editor.setTheme("ace/theme/monokai");
  editor.session.setMode("ace/mode/html");
  editor.setOptions({
    fontSize: "13pt"
  });
  ?m=settings/themes&f=apigetthemecode&no=1&ID=6&table=theme
</script>
