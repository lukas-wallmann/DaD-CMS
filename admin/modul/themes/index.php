<a class="btn btn-primary mr-3 p-2" href="?m=themes&f=newtheme&nh=1"><?php echo $lang->newTheme ?></a>
<table class="table table-striped mt-3">
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from theme");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td data-table='theme' data-id='".$row["ID"]."'><a href='?m=themes&f=edittheme&nh=1&ID=".$row["ID"]."'>".$row["Name"]."</a><span class='edit ml-3'><i class='fas fa-pen-square'></i></span><span class='delete ml-1'><i class='fas fa-trash-alt'></i></span></td></tr>";
  }
?>
</tbody>
</table>
