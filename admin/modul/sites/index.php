<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <style>
   .draggable { width: 100px; height: 100px; background: #aa0000; border:1px solid;float: left; margin: 0 10px 10px 0; }
   .itm{ height:100px; margin:20px 0; background: #ccc}
  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="js/touchpunch.js"></script>
  <script>
  $( function() {
    $( ".draggable" ).draggable({ scroll: true, scrollSensitivity: 100, helper:"clone", drag:function(){
      checkHit($(".ui-draggable-dragging").offset())
    }, stop:function(){
      $("#placeholder").before("<div class='itm'></div>");
      $("#placeholder").remove();
    } });
  } );

  var snip=[];

  function checkHit(offset){
    snip=[];
    $("#placeholder").remove();
    $(".itm").each(function(){
      var off=$(this).offset();
      var h=$(this).height();
      if(off.top<offset.top+10 && off.top>offset.top-10  ){
        $(this).css("background","#000");
        snip=[$(this),"before"];
      }else if(off.top+h<offset.top+10 && off.top+h>offset.top-10  ){
        $(this).css("background","#ff0000");
        snip=[$(this),"after"];
      }
    });
    if(snip!=[]){
      if(snip[1]=="after"){
        snip[0].after("<div id='placeholder' style='height:2px; background:#00ff00'></div>");
      }else{
        $(snip[0]).before("<div id='placeholder' style='height:2px; background:#00ff00'></div>");
      }
    }
  }

  </script>

<div class="draggable" class="ui-widget-content">
  <p>scrollSensitivity set to 100</p>
</div>

<div class="itm"></div>
<div class="itm"></div>
<div class="itm"></div>
<div class="itm"></div>
<div style="height: 5000px; width: 1px;"></div>
