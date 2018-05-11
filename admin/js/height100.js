function setHeight(){
    $('main').css({'height':(($(document).height())-$("main").offset().top-$("footer").height())+'px'});
}

$(function(){
  setHeight();
  $(window).resize(function(){
    setHeight();
  });
});
