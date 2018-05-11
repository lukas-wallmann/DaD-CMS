<div class="row h-100">
  <div class="col-md-3 leftsidebar">
    <b><?php echo $lang->themeParts ?></b><br>
    <b><?php echo $lang->themePlugins ?></b><br>
  </div>
  <div class="col-md-9 codeEditor" id="editor" style="min-height:500px">
    function someThing(){

    }
  </div>
  <script src="js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    editor.session.setMode("ace/mode/javascript");
    editor.setOptions({
      fontSize: "14pt"
    })
</script>
</div>
