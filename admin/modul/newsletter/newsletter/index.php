<button class="btn btn-secondary new"><?php echo $lang->newNewsletter ?></button><br><br>
<ul class="sites">
  <?php
    $res=mysqli_query($_dbcon,"Select * From newsletter");
    while($row=mysqli_fetch_assoc($res)){
      echo '<li><a href="?m=newsletter/newsletter&f=editnewsletter&nh=1&ID='.$row["ID"].'">'.$row["Title"].'</a><a class="ml-3" href="?m=newsletter/newsletter&f=send&ID='.$row["ID"].'"><i class="fas fa-envelope"></i></a></li>';
    }
   ?>
</ul>
<script>
var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/touchpunch.js"></script>
<script src="js/cmd.js"></script>
<script>
  $(document).ready(function(){
    $("button.new").click(function(){
      cmd(
        '<label class="mr-3">'+consts.name+'</label><input>',
        function(){
          var data={action:"new",name:$(".cmd input").val()};
          $.ajax({url:"?m=newsletter/newsletter&f=apinewsletter&no=1",type:"POST",data:data}).done(function(d){
            document.location.href="?m=newsletter/newsletter&f=editnewsletter&nh=1&ID="+d;
          })
        },
        function(){$(".cmd input").focus()}
      )
    })
  });

</script>
