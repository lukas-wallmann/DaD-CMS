$(document).ready(function(){
  function genPerm(mode){
    var valR=rights.disallowRead;
    if(mode=="write")valR=rights.disallowWrite;
    var code=[];
    for(var i=0; i<menu.length; i++){
      var name=menu[i][0];
      var value=menu[i][1];
      var id=mode+i;
      var disabled="";
      var checked=" checked";
      if(valR.indexOf(value)!=-1){
        disabled=" disabled";
        checked="";
      }
      if(menu[i][2]!=undefined){
        for(var j=0; j<menu[i][2].length; j++){
          var name=menu[i][0]+"-&gt;"+menu[i][2][j][0];
          var value=menu[i][2][j][1];
          var id=mode+i+j;
          var disabled="";
          var checked=" checked";
          if(valR.indexOf(value)!=-1){
            disabled=" disabled";
            checked="";
          }
          code.push('<div class="form-check form-check-inline"><input'+disabled+' id="'+id+'" class="form-check-input" type="checkbox" value="'+value+'"'+checked+'><label class="form-check-label" for="'+id+'">'+name+'</label></div>');
        }
      }else {
        code.push('<div class="form-check form-check-inline"><input'+disabled+' id="'+id+'" class="form-check-input" type="checkbox" value="'+value+'"'+checked+'><label class="form-check-label" for="'+id+'">'+name+'</label></div>');
      }
    }
    return code.join("");
  }
  $(".userform .rights.read").append(genPerm("read"));
  $(".userform .rights.write").append(genPerm("write"));

  $(".userform .rights.read input[type='checkbox']").change(function(){
    if(!$(this).is(":checked")){
      var val=$(this).val();
      $('.userform .rights.write input[value="'+val+'"]').prop("checked",false);
    }
  });
  function setRights(){
    function goT(sel){
      var disallowed=[];
      $(sel).each(function(){
        if(!$(this).is(":checked"))disallowed.push($(this).val());
      });
      return disallowed;
    }
    var ro={};
    ro.disallowRead=goT(".userform .rights.read input");
    ro.disallowWrite=goT(".userform .rights.write input");
    $("#rights").val(JSON.stringify(ro));
  }
  $(".userform .rights input").change(function(){
    setRights();
  });
  setRights();
});
