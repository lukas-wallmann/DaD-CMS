<?php
  $siterow=mysqli_fetch_assoc(mysqli_query($_dbcon,"Select * FROM sites WHERE ID=".$_GET["ID"]));
 ?>
<div class="topbar">
  <div class="section">
    <div class="dropper"><i class="fas fa-arrows-alt-v mr-2"></i><?php echo $lang->siteSettings ?></div>
    <div class="content">
      <div class="row">
        <div class="col-sm-6">
          <div>
            <label for="title"><?php echo $lang->title ?></label>
          </div>
          <div>
            <input id="title" class="form-control" value="<?php echo $siterow["Title"]?>"><br>
          </div>
        </div>
        <div class="col-sm-6">
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
        <div class="col-sm-6">
          <div>
            <label for="url"><?php echo $lang->URL ?></label>
          </div>
          <div>
            <input id="url" class="form-control" value="<?php echo $siterow["SiteURL"]?>"><br>
          </div>
          <div>
            <label for="fixurl"><?php echo $lang->fixURL ?></label>
          </div>
          <div>
            <input id="fixurl" type="checkbox" value="1" <?php if($siterow["FixSiteURL"]==1)echo " checked";?>><br>
          </div>
        </div>
        <div class="col-sm-6">
          <div>
            <label for="layout"><?php echo $lang->layout ?></label>
          </div>
          <div>
            <select id="layout" class="form-control">
              <?php
                $res=mysqli_query($_dbcon,"Select * FROM theme WHERE LayoutFor='site'");
                while($row=mysqli_fetch_assoc($res)){
                  $selected="";
                  if($row["ID"]==$siterow["Layout"])$selected=" selected";
                  echo "<option value='".$row["ID"]."'>".$row["Name"]."</option>";
                }
               ?>
            </select><br>
          </div>
        </div>
      </div>
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

 <div class="section">
   <div class="dropper"><i class="fas fa-arrows-alt-v mr-2"></i><?php echo $lang->teaser ?></div>
   <div class="content">
     <div>
       <div>
         <label for="teasername"><?php echo $lang->teaserName ?></label>
       </div>
       <div>
         <input id="teasername" class="form-control" value="<?php echo $siterow["TeaserName"]?>"><br>
       </div>
     </div>
     <div>
       <div>
         <label for="teaserimage"><?php echo $lang->teaserPicture ?></label>
       </div>
       <div>
         <input id="teaserimage" class="form-control" value="<?php echo $siterow["TeaserPicture"]?>"><br>
       </div>
     </div>
     <div>
       <div>
         <label for="teasertext"><?php echo $lang->teaserText ?></label>
       </div>
       <div>
         <textarea id="teasertext" class="form-control"><?php echo $siterow["TeaserText"]?></textarea><br>
       </div>
     </div>
     <div>
       <div>
         <label for="teaserprice"><?php echo $lang->teaserPrice ?></label>
       </div>
       <div>
         <input id="teaserprice" class="form-control" value="<?php echo $siterow["TeaserPrice"]?>">
       </div>
     </div>
  </div>
</div>

<script>
  var contents=[
    {
      pluginID:5,
      text:"Hallo Welt"
    },
    {
      pluginID:4,
      text:"Test",
      variant:"h6"
    }
  ];
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/sites.css">
<link rel="stylesheet" href="css/quill.snow.css">
<script src="js/quill.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="js/touchpunch.js"></script>
<script src="js/filemanager.js"></script>
<script src="js/formmanager.js"></script>
<script src="js/cmd.js"></script>
<script>
var consts=JSON.parse('<?php echo json_encode($lang)?>');
</script>
<script src="js/sites.core.bak.js"></script>
