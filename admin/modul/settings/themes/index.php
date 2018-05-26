<a class="btn btn-primary mr-3 p-2" href="?m=settings/themes&f=newtheme&nh=1"><?php echo $lang->newTheme ?></a>
<table class="table table-striped mt-3">
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from theme");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td data-table='theme' data-id='".$row["ID"]."'><a href='?m=settings/themes&f=edittheme&nh=1&ID=".$row["ID"]."'>".$row["Name"]."</a><span class='edit ml-3'><i class='fas fa-pen-square'></i></span><span class='delete ml-1'><i class='fas fa-trash-alt'></i></span></td></tr>";
  }
?>
</tbody>
</table>

<h3>Javascript</h3>
<a class="btn btn-primary mr-3 p-2" href="?m=settings/themes&f=newcssjs&mode=script&nh=1"><?php echo $lang->newJavascript ?></a>
<table class="table table-striped mt-3">
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from script");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td data-table='script' data-id='".$row["ID"]."'><a href='?m=settings/themes&f=editcssjs&mode=script&nh=1&ID=".$row["ID"]."'>".$row["Name"]."</a><span class='edit ml-3'><i class='fas fa-pen-square'></i></span><span class='delete ml-1'><i class='fas fa-trash-alt'></i></span></td></tr>";
  }
?>
</tbody>
</table>

<h3>CSS</h3>
<a class="btn btn-primary mr-3 p-2" href="?m=settings/themes&f=newcssjs&mode=css&nh=1"><?php echo $lang->newCSS ?></a>
<table class="table table-striped mt-3">
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from css");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td data-table='css' data-id='".$row["ID"]."'><a href='?m=settings/themes&f=editcssjs&nh=1&mode=css&ID=".$row["ID"]."'>".$row["Name"]."</a><span class='edit ml-3'><i class='fas fa-pen-square'></i></span><span class='delete ml-1'><i class='fas fa-trash-alt'></i></span></td></tr>";
  }
?>
</tbody>
</table>

<h3><?php echo $lang->plugins ?></h3>
<a class="btn btn-primary mr-3 p-2" href="?m=settings/themes&f=newplugin&nh=1"><?php echo $lang->newPlugin ?></a>
<table class="table table-striped mt-3">
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from plugins ORDER BY Name");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td data-table='plugins' data-id='".$row["ID"]."'><a href='?m=settings/themes&f=editplugin&nh=1&ID=".$row["ID"]."'>".$row["Name"]."</a><a class='ml-3' href='?m=settings/themes&f=syncplugin&nh=1&ID=".$row["ID"]."'><i class='fas fa-sync-alt'></i></a><span class='edit ml-1'><i class='fas fa-pen-square'></i></span><span class='delete ml-1'><i class='fas fa-trash-alt'></i></span></td></tr>";
  }
?>
</tbody>
</table>
<script>
var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>
<script src="js/cmd.js"></script>
<script src="js/themes.js"></script>
