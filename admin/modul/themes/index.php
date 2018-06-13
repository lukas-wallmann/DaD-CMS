<a class="btn btn-primary mr-3 p-2" href="?m=themes&f=newtheme&nh=1"><?php echo $lang->newTheme ?></a>
<table class="table table-striped mt-3 themes">
  <tbody>

<?php
  $res=mysqli_query($_dbcon,"Select * from theme");
  while($row=mysqli_fetch_assoc($res)){
    echo "<tr><td data-table='theme' data-id='".$row["ID"]."'><a href='?m=themes&f=edittheme&nh=1&ID=".$row["ID"]."'>".$row["Name"]."</a><span class='edit ml-3'><i class='fas fa-pen-square'></i></span><span class='delete ml-1'><i class='fas fa-trash-alt'></i></span></td></tr>";
  }
?>
</tbody>
</table>
<script src="js/cmd.js"></script>
<script>
  var consts=JSON.parse('<?php echo json_encode($lang)?>');

  $(document).ready(function(){
    $(".themes .edit").click(function(){
      var elm=$(this).parent();
      cmd(
        '<label>'+consts.name+'</label><input class="form-control" value="'+elm.text()+'">',
        function(){
          elm.find("a").text($(".cmd input").val());
          $.ajax({url:"?m=themes&f=api&no=1&ID="+elm.attr("data-id"),type:"POST",data:{action:"renametheme",name:$(".cmd input").val()}});
        },
        function(){$(".cmd input").focus()}
      )
    });
    $(".themes .delete").click(function(){
      var r=confirm(consts.delete+": "+$(this).parent().text());
      if(r){
        $.ajax({url:"?m=themes&f=api&no=1&ID="+$(this).parent().attr("data-id"),type:"POST",data:{action:"deletetheme"}}).done(function(){

        });
        $(this).parent().remove();
      }
    });
  });
</script>
