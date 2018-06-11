<div class="row h-100">
  <div class="col-md-3 leftsidebar">
    <button class="btn btn-primary w-100 save mb-1"><?php echo $lang->save ?></button>
    <button class="btn btn-secondary w-100 add mb-3"><i class="fas fa-plus-square"></i></button>
    <div class="menu"></div>
  </div>
  <div class="col-md-9 pluginEditor" id="editor"></div>
</div>
<script src="js/cmd.js"></script>
<script src="js/ace/ace.js"></script>
<script src="js/height100.js"></script>
<script>
  var mode="<?php echo $_GET["mode"]?>"
  
  var ID=<?php echo $_GET["ID"] ?>;
  var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>
<script src="js/cssjsEditor.js"></script>
<style>
  .selected{
    font-style: italic;
    text-decoration: underline!important;
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

    main .leftsidebar div {
        display: inline-block;
        margin: 0 30px 0 0;
    }
}
</style>