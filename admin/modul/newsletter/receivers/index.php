<table class="table table-striped">
  <thead>
    <tr>
      <th scope="col"><?php echo $lang->receiverlist ?></th>
    </tr>
  </thead>
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * From newsletterReceiverGroups");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td><a href='?m=newsletter/receivers&f=edit&ID=".$row["ID"]."'>".$row["Name"]."</a></td></tr>";
  }
?>
</tbody>
</table>

<a href="?m=newsletter/receivers&f=edit&ID=NEW">
  <button class="btn btn-primary mt3"><?php echo $lang->newReceiverlist ?></button>
</a>
