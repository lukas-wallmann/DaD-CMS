<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col"><?php echo $lang->userName ?></th>
      <th scope="col"><?php echo $lang->language ?></th>
      <th scope="col"><?php echo $lang->rights ?></th>
    </tr>
  </thead>
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from users");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><th scope='row'>".$row['ID']."</th><td><a href='?m=settings/user&f=edit&ID=".$row["ID"]."'>".$row["User"]."</a></td><td>".$row["Language"]."</td><td>".$row["Rights"]."</td></tr>";
  }
?>
</tbody>
</table>
