<div class="row h-100">
  <div class="col-md-3 leftsidebar">
    <button class="btn btn-primary w-100 save mb-3"><?php echo $lang->save ?></button>
    <div class="themepart"><b><?php echo $lang->themeParts ?></b><span class="b"><i class="fas fa-plus-square ml-2"></i></span></div>
    <br><div class="maincode selected" data-table="theme" data-id="<?php echo $_GET["ID"] ?>"><span class="name"><?php echo $lang->mainCode ?></span></div>
    <div class="menu parts"></div>
    <?php
      $res=mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT * FROM `theme` WHERE ID=".$_GET["ID"]))["LayoutFor"];
      if($res!="mail"){
    ?>
    <br><div class="themeplugin mt-3"><b><?php echo $lang->themePlugins ?></b><span class="b"><i class="fas fa-plus-square ml-2"></i></span></div>
    <br><div class="menu plugins"></div>
  <?php } ?>
  </div>
  <div class="col-md-9 codeEditor" id="editor"></div>
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
<style>
@media screen and (max-width: 768px) {
  main.container-fluid {
      height: auto !important;
  }

  main .row.h-100 {
      height: auto !important;
  }

  main div#editor {
      min-height: 500px;
  }

  main .leftsidebar div {
      display: inline-block;
      margin: 0 30px 0 0;
  }
}
@media screen and (min-width: 768px) {
main .leftsidebar br {
    display: none;
}
}
</style>
