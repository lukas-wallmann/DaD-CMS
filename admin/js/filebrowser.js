
var fb={

  api:"",
  dir:"",
  firstView:true,

  init:function(){
    var o={"dir":fb.dir};
    fb.req(o,fb.view);
    if(fb.firstView){
      fb.firstView=false;
      fb.setCoreFunctions();
    }
  },

  uploader:{

    files:[],
    at:0,
    cache:[],
    wait:0,
    wat:0,
    cat:0,

    start:function(){
      fb.uploader.at=0;
      fb.uploader.cat=0;
      fb.uploader.cache=[];
      fb.uploader.run();
    },

    resize:function(url,info=["fitin",1920,1080],name="file.jpg",callback=function(){},q=0.8){
      var image = new Image();
      image.onload = function (imageEvent) {

          // Resize the image
          var canvas = document.createElement('canvas'),
              width = image.width,
              height = image.height,
              mode=image.info[0],
              widthto=image.info[1],
              heigthto=image.info[2],
              x=0,
              y=0,
              relw = widthto/width,
              relh = heigthto/height;

          if(mode=="fitin"){
            if (relh > relw) {
                relto=relw;
            } else {
                relto=relh;
            }
          }else{
            if (relh < relw) {
                relto=relw;
            } else {
                relto=relh;
            }
          }
          width*=relto;
          height*=relto;

          if(mode=="fitin"){
            canvas.width = width;
            canvas.height = height;
          }else{
            x=(widthto-width)/2;
            y=(heigthto-height)/2;
            canvas.width = widthto;
            canvas.height = heigthto;
          }
          canvas.getContext('2d').drawImage(image, x, y, width, height);
          var dataUrl = canvas.toDataURL('image/jpeg',q);
          callback(dataUrl,image.name);
      }

      image.src = url
      image.info=info;
      image.name=name;
    },

    run:function(){
      var reader = new FileReader();

      reader.onload = (function(theFile) {
        return function(e) {
          // Render thumbnail.
          if (theFile.type=="image/jpeg") {
            fb.uploader.wait=2;
            resize(e.target.result,["crop",120,120],"thumps/"+theFile.name,fb.uploader.callback);
            fb.uploader.callback(e.target.result,theFile.name);
          }else{
            fb.uploader.wait=1;
            fb.uploader.callback(e.target.result,theFile.name);
          }
        };
      })(fb.uploader.files[fb.uploader.at]);

      reader.readAsDataURL(fb.uploader.files[fb.uploader.at]);
    },

    callback:function(data,filename){
      fb.uploader.cache.push([filename,data]);
      fb.uploader.wat++;
      if(fb.uploader.wat==fb.uploader.wait){
        fb.uploader.wat=0;
        alert("upload now");
        fb.uploader.upload();
      }
    },

    upload:function(data,filename){
      $.post( fb.api, { filename: fb.uploader.cache[fb.uploader.cat][0], data:fb.uploader.cache[fb.uploader.cat][1], dir:fb.dir, mode="upload"  } ).done(function(){
       fb.uploader.cat++;
       $(".ajaxUploader_"+fb.uploader.uploaderID+" .preloader .bar").width(((fb.uploader.at+(fb.uploader.cat/fb.uploader.cache.length))/fb.uploader.files.length*100)+"%");
       if(fb.uploader.cat<fb.uploader.cache.length){
         upload();
       }else{
         fb.uploader.at++;
         fb.uploader.cat=0;
         if(fb.uploader.at<fb.uploader.files.length){
           run();
         }else{
           fb.init();
         }
       }
     });
   }

  },

  setCoreFunctions:function(){
    $("#fileInput").change(function(evt){
      fb.uploader.files=evt.target.files;
      fb.uploader.start();
    });
  },

  actAdress:function(){
    var html=['<button type="button" data-dir="" class="btn btn-primary btn-sm mr-1"><i class="fas fa-home"></i></button>'];
    var parts=fb.dir.split("/");
    var cur="";
    for(var i=0; i<parts.length-1; i++){
      cur+=parts[i]+"/";
      html.push('/<button type="button" data-dir="'+cur+'" class="btn btn-primary btn-sm mr-1 ml-1">'+parts[i]+'</button>');
    }
    $("#filebrowser .address").html(html.join(""));
  },

  req:function(o,f){
    $.post( fb.api, o).done(function( data ) {
      f(data);
    });
  },

  view:function(d){

    d=JSON.parse(d);
    var html=[];

    function isFolder(f){
      return f.split(".").length == 1;
    }

    function parentFolder(f){
      var parts=fb.dir.split("/");
      var newf="";
      for(var i=0; i<parts.length-2; i++){
        newf+=parts[i]+"/";
      }
      return newf;
    }

    var folders=[];
    var files=[];

    if(fb.dir!=""){
      folders.push("<div class='entry folder parent' data-dir='"+parentFolder(fb.dir)+"'><div class='icon'><i class='far fa-arrow-alt-circle-left'></i></div><div class='title'><span>..</span></div></div>");
    }

    for(var i=0; i<d.files.length; i++){
      f=d.files[i];
      if(f!="." && f!=".."){
        if(isFolder(f)){
          folders.push("<div class='entry folder' data-dir='"+fb.dir+f+"/'><div class='icon'><i class='fas fa-folder-open'></i></div><div class='title'><span>"+f+"<span></div></div>");
        }else{
          files.push("<div class='entry file' data-file='"+fb.dir+f+"'><div class='icon'><i class='fas fa-file'></i></div><div class='title'><span>"+f+"</span></div></div>");
        }
      }
    }

    $("#filebrowser .content").html(folders.join("")+files.join(""));
    fb.actAdress();
    fb.setFunctions();

  },

  setFunctions:function(){
    $("#filebrowser .content .entry.folder .title span").click(function(){
      fb.dir=$(this).parent().parent().attr("data-dir");
      fb.init();
    });
    $("#filebrowser .content .entry.folder .icon").click(function(){
      fb.dir=$(this).parent().attr("data-dir");
      fb.init();
    });
    $("#filebrowser .content .entry:not(.parent) .title").each(function(){
      $(this).append("<div class='controls'><i class='fas fa-trash'></i><i class='fas fa-pencil-alt'></i></div>")
    });
    $("#filebrowser .address button").click(function(){
      fb.dir=$(this).attr("data-dir");
      fb.init();
    });
  }

}

$(document).ready(function(){
  fb.api="?m=files&no=1&a=api";
  fb.init();
})
