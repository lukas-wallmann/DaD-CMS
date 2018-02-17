<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <style>
   .draggable { width: 100px; height: 100px; background: #aa0000; border:1px solid; display:inline-block; margin: 0 10px 10px 0; }
   .itm{ height:100px; margin:20px 0; background: #ccc;}
   .ui-draggable-dragging{
     z-index:9999;
   }
   .itm:hover .handle{
     display: block;
   }
   .itm .handle{
     display: none;
     height: 1px;
     background:#000;
     cursor: pointer;
     position: relative;
   }
   .itm .handle::before {
       content: "";
       height: 20px;
       width: 50px;
       background: #000;
       display: block;
       position: absolute;
       left: 50%;
       top: -20px;
       margin-left: -25px;
   }
   #placeholder{
     position: relative;
     z-index: 99999;
   }
  </style>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="js/touchpunch.js"></script>
  <script>
  $( function() {
    $( ".draggable" ).draggable({ scroll: true, scrollSensitivity: 100, helper:"clone", drag:function(){
      checkHit($(".ui-draggable-dragging").offset())
    }, stop:function(){
      $("#placeholder").replaceWith("<div class='itm'><div class='handle'></div><div class='content'>"+$(this).attr("data-type")+"</div></div>");
      setFunctions();
    } });
    setFunctions();
  } );

  function setFunctions(){
    $( ".itm" ).draggable({ handle: ".handle",
     drag:function(){checkHit($(this).offset())},
     stop:function(){
       $(this).css("left","auto");
       $(this).css("top","auto");
       $("#placeholder").replaceWith($(this));
     }
    });
  }

  function checkHit(offset){
    snip=[];
    $("#placeholder").remove();
    $(".itm:not(.ui-draggable-dragging)").each(function(){
      var off=$(this).offset();
      var h=$(this).height();
      if(off.top<offset.top+10 && off.top>offset.top-10  ){
        $(this).before("<div id='placeholder' style='height:2px; background:#00ff00'></div>");
      }else if(off.top+h<offset.top+10 && off.top+h>offset.top-10  ){
        $(this).after("<div id='placeholder' style='height:2px; background:#00ff00'></div>");
      }
    });
    if($(".itm").length==0){
      $("#content").html("<div id='placeholder' style='height:2px; background:#00ff00'></div>");
    }
  }

  </script>

<div class="elements">
<div class="draggable" data-type="text"></div>
<div class="draggable" data-type="text-image"></div>
<div class="draggable" data-type="image"></div>
<div class="draggable" data-type="gallery"></div>
<div class="draggable" data-type="downloads"></div>
<div class="draggable" data-type="video"></div>
</div>

<div id="content">

</div>
