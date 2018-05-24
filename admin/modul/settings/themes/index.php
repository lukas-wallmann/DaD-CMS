<a class="btn btn-primary mr-3 p-2" href="?m=settings/themes&f=newtheme&nh=1"><?php echo $lang->newTheme ?></a>
<a class="btn btn-primary p-2" href="?m=settings/themes&f=importtheme"><?php echo $lang->importTheme ?></a>
<table class="table table-striped mt-3">
  <thead>
    <tr>
      <th scope="col"><?php echo $lang->name ?></th>
    </tr>
  </thead>
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from theme");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td><a href='?m=settings/themes&f=edittheme&nh=1&ID=".$row["ID"]."'>".$row["Name"]."</a></td></tr>";
  }
?>
</tbody>
</table>

<h3><?php echo $lang->plugins ?></h3>
<a class="btn btn-primary mr-3 p-2" href="?m=settings/themes&f=newplugin&nh=1"><?php echo $lang->newPlugin ?></a>
<a class="btn btn-primary p-2" href="?m=settings/themes&f=importplugin"><?php echo $lang->importPlugin ?></a>
<table class="table table-striped mt-3">
  <thead>
    <tr>
      <th scope="col"><?php echo $lang->name ?></th>
    </tr>
  </thead>
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from plugins ORDER BY Name");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td><a href='?m=settings/themes&f=editplugin&nh=1&ID=".$row["ID"]."'>".$row["Name"]."</a><a class='ml-3' href='?m=settings/themes&f=syncplugin&nh=1&ID=".$row["ID"]."'><i class='fas fa-sync-alt'></i></a></td></tr>";
  }
?>
</tbody>
</table>
