<?php include "includes/langselect.php" ?>
<button class="btn btn-secondary new"><?php echo $lang->newMenu ?></button>

<div class="menus mt-3">

</div>

<script>
var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/touchpunch.js"></script>
<script src="js/menu.js"></script>
<style>
.menu .title {
  font-size: 20px;
  font-weight: bold;
  background: #FCFC51;
  padding: 10px;
}
ul.ui-sortable {
    padding-left: 20px;
}
</style>
