<?php
  $siterow=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * FROM sites WHERE ID=".$_GET["ID"]));
 ?>
<div class="topbar">
  <div>
    <div>
      <label for="title"><?php echo $lang->title ?></label>
    </div>
    <div>
      <input id="title" class="form-control" value="<?php echo $siterow["Title"]?>"><br>
    </div>
  </div>
  <div>
    <div>
      <label for="menu"><?php echo $lang->menuAssignment ?></label>
    </div>
    <div>
      <select class="menu form-control" id="menu">
        <option value="0"><?php echo $lang->noMenu ?></option>
      <?php
        $code="";
        $res=mysqli_query($_dbcon,"SELECT * FROM menus");
        while($row=mysqli_fetch_assoc($res)){
          $code.='<option value="" disabled>'.$row["Name"].'</option>';
          $code.=getSubs(json_decode($row["Content"]),"-");
        }
        echo $code;

        function getSubs($data,$prefix){
          global $siterow;
          $code="";
          foreach($data as &$point){
               $selected="";
               if($siterow["MenuID"]==$point->id){
                 $selected=" selected";
               }
              $code.="<option value='$point->id'$selected>$prefix $point->name</option>";
              if(count($point->sub)>0)$code.=getSubs($point->sub,$prefix."-");
          }
          return $code;
        }
      ?>
      </select>
    </div>
  </div>

 <div class="section">
   <div class="dropper"><i class="fas fa-arrows-alt-v mr-2"></i><?php echo $lang->metaData ?></div>
   <div class="content">
     <div>
       <div>
         <label for="metatitle"><?php echo $lang->metaTitle ?></label>
       </div>
       <div>
         <input id="metatitle" class="form-control" value="<?php echo $siterow["MetaTitle"]?>"><br>
       </div>
     </div>
     <div>
       <div>
         <label for="metatags"><?php echo $lang->metaTags ?></label>
       </div>
       <div>
         <input id="metatags" class="form-control" value="<?php echo $siterow["MetaTags"]?>"><br>
       </div>
     </div>
     <div>
       <div>
         <label for="metadescription"><?php echo $lang->metaDescription ?></label>
       </div>
       <div>
         <textarea id="metadescription" class="form-control"><?php echo $siterow["MetaDescription"]?></textarea>
       </div>
     </div>
   </div>
 </div>



</div>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/sites.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/touchpunch.js"></script>
<script src="js/sites.core.js"></script>
<style>
  .section {
      margin: 20px 0;
  }
  .section .dropper {
      margin: 0 0 10px 0;
      font-size: 20px;
  }
  .section .content{
    display:none;
  }
</style>
