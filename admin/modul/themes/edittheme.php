<div class="row h-100">
  <div class="col-md-3 leftsidebar">
    <button class="btn btn-primary w-100 save mb-3"><?php echo $lang->save ?></button>
    <div class="theme parts" data-add="themeParts" data-toggle=".menu.parts"><span class="mr-2 openclose"><span class="closed"><i class="fas fa-arrow-right"></i></span><span class="opend"><i class="fas fa-arrow-down"></i></span></span><b><?php echo $lang->themeParts ?></b><span class="b"><i class="fas fa-plus-square ml-2"></i></span></div>
    <ul class="menu parts"></ul>
    <?php
      $res=mysqli_fetch_assoc(mysqli_query($_dbcon,"SELECT * FROM `theme` WHERE ID=".$_GET["ID"]))["LayoutFor"];
      if($res!="mail"){
    ?>
    <div class="theme plugins mt-3" data-add="plugins" data-toggle=".menu.plugins"><span class="mr-2 openclose"><span class="closed"><i class="fas fa-arrow-right"></i></span><span class="opend"><i class="fas fa-arrow-down"></i></span></span><b><?php echo $lang->themePlugins ?></b><span class="b"><i class="fas fa-plus-square ml-2"></i></span><span class="import"><i class="ml-1 fas fa-cloud-download-alt"></i></span></div>
    <ul class="menu plugins"></ul>
    <?php  if($res!="newsletter"){ ?>
    <div class="theme css mt-3" data-add="css" data-toggle=".menu.css"><span class="mr-2 openclose"><span class="closed"><i class="fas fa-arrow-right"></i></span><span class="opend"><i class="fas fa-arrow-down"></i></span></span><b>CSS</b><span class="b"><i class="fas fa-plus-square ml-2"></i></span></div>
    <ul class="menu css"></ul>
    <div class="theme script mt-3" data-add="script" data-toggle=".menu.script"><span class="mr-2 openclose"><span class="closed"><i class="fas fa-arrow-right"></i></span><span class="opend"><i class="fas fa-arrow-down"></i></span></span><b>Javascript</b><span class="b"><i class="fas fa-plus-square ml-2"></i></span></div>
    <ul class="menu script"></ul>
  <?php }
      }
  ?>
  </div>
  <div class="col-md-9 codeEditor" id="editor"></div>
</div>

<script src="js/cmd.js"></script>
<script src="js/ace/ace.js"></script>
<script src="js/height100.js"></script>
<script>
  var layoutID=<?php echo $_GET["ID"] ?>;
  var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/touchpunch.js"></script>
<script src="js/fixedsortable.js"></script>
<script src="js/themeEditor.js"></script>
<style>
.leftsidebar{
  overflow-y: scroll;
}
.leftsidebar span.closed {
    display: none;
}
.leftsidebar ul.menu {
    list-style: none;
    padding: 0;
}
.plugin.ml-1.selected{
  color:red;
}

.leftsidebar ul.menu ul {
    padding: 0px 18px;
}
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
}
@media screen and (min-width: 768px) {
main .leftsidebar br {
    display: none;
}
}
</style>
