<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col"><?php echo $lang->receiverlist ?></th>
      <th scope="col"><?php echo $lang->receivers ?></th>
    </tr>
  </thead>
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * From newsletterReceiverGroups");
  while($row=mysqli_fetch_assoc($res)){
    $_dbcon2=mysqli_connect($_DB[0],$_DB[1],$_DB[2],$_DB[3]);
    $row2=mysqli_fetch_assoc(mysqli_query($_dbcon2,"SELECT COUNT(ID) as count FROM `newsletterReceiversGroupLinks` WHERE GroupID=".$row["ID"]));
    echo "<tr><td><a href='?m=newsletter/receivers&f=edit&ID=".$row["ID"]."'>".$row["Name"]."</a></td><td>".$row2["count"]."</td></tr>";
  }
?>
</tbody>
</table>

<a href="?m=newsletter/receivers&f=edit&ID=NEW">
  <button class="btn btn-primary mt3"><?php echo $lang->newReceiverlist ?></button>
</a>
