
$.fn.videoManager = function() {

    var data=JSON.parse($(this).find(".saveme").text());
    if(data.url==undefined)data={url:"",videoid:"",type:""};
    var main=this;

    var vm={
      init:function(){
        main.append('<div class="video"><div class="input"><label>Video-URL</label><br><input class="form-control" value="'+data.url+'"></div>');
        vm.setFunctions();
      },
      setFunctions:function(){
        main.find(".input input").off().keyup(vm.refresh).change(vm.refresh);
        vm.refresh();
      },

      refresh:function(){

        main.find(".input input").removeClass("invalid");

        var url = main.find(".input input").val();
        data.url=url;
        var regExp = /https:\/\/(www\.)?vimeo.com\/(\d+)($|\/)/;

        function youtube_parser(url){
          var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
          var match = url.match(regExp);
          return (match&&match[7].length==11)? match[7] : false;
      }

        var match = url.match(regExp);

        if (match){
            data.videoid=match[2];
            data.type="vimeo";
        }else{
            data.videoid=youtube_parser(url);
            data.type="youtube";
            if(data.videoid==false){
              main.find(".input input").addClass("invalid");
            }
        }
        $(main).find(".saveme").text(JSON.stringify(data));
      }

    }

    vm.init();
    return this;
};
