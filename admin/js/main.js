$(document).ready(function(){
  $(".langselect select").change(function(){
    setCookie('DaDCMS-lang', $(this).val(),7);
    location.reload();
  })

})

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
