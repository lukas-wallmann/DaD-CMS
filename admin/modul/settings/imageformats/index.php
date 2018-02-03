<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col"><?php echo $lang->imageformatName ?></th>
      <th scope="col"><?php echo $lang->imageformatValue ?></th>
    </tr>
  </thead>
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from settings Where `Group`='imageformats'");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td><a href='?m=settings/imageformats&f=edit&ID=".$row["ID"]."'>".$row["Name"]."</a></td><td>".$row["Value"]."</td></tr>";
  }
?>
</tbody>
</table>
