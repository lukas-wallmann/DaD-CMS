
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
      fb.uploader.wait=0;
      fb.uploader.wat=0;
      fb.uploader.cache=[];
      fb.uploader.run();
    },

    resize:function(url,info=["fitin",1920,1080],name="file.jpg",callback=function(){},type,q=0.8){
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
          var dataUrl = canvas.toDataURL(type,q);
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
          if (theFile.type=="image/jpeg" || theFile.type=="image/png") {
            fb.uploader.wait=2;
            fb.uploader.resize(e.target.result,["crop",120,120],"__thumps/"+theFile.name,fb.uploader.callback,theFile.type);
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
        fb.uploader.upload();
      }
    },

    upload:function(data,filename){
      $.post( fb.api, { filename: fb.uploader.cache[fb.uploader.cat][0], data:fb.uploader.cache[fb.uploader.cat][1], dir:fb.dir, mode:"upload"  } ).done(function(){
       fb.uploader.cat++;
       $(".prog").show();
       $(".prog .bar").width(((fb.uploader.at+(fb.uploader.cat/fb.uploader.cache.length))/fb.uploader.files.length*100)+"%");
       if(fb.uploader.cat<fb.uploader.cache.length){
         fb.uploader.upload();
       }else{
         fb.uploader.at++;
         fb.uploader.cat=0;
         if(fb.uploader.at<fb.uploader.files.length){
           fb.uploader.run();
         }else{
           $(".prog").hide();
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
    $("#filebrowser .toolbar .newfolder").click(function(){
      var folder = prompt("Please enter the folder name", "new folder");
      if (folder != null) {
          var o={mode:"createfolder",name:folder,dir:fb.dir};
          fb.req(o,fb.init);
      }
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

    function isImage(n){
      return n.split(".jpg").length>1 || n.split(".png").length>1;
    }

    var folders=[];
    var files=[];

    if(fb.dir!=""){
      folders.push("<div class='entry folder parent' data-dir='"+parentFolder(fb.dir)+"'><div class='icon'><i class='far fa-arrow-alt-circle-left'></i></div><div class='title'><span>..</span></div></div>");
    }

    for(var i=0; i<d.files.length; i++){
      f=d.files[i];
      if(f!="." && f!=".." && f.substring(0,2)!="__"){
        if(isFolder(f)){
          folders.push("<div class='entry folder' data-dir='"+fb.dir+f+"/'><div class='icon'><i class='fas fa-folder-open'></i></div><div class='title'><span>"+f+"<span></div></div>");
        }else{
          if(isImage(f)){
            var background=" style='background:url(\"uploads/"+fb.dir+"__thumps/"+f+"\")'";
            files.push("<div class='entry file' data-file='"+fb.dir+f+"'><div class='icon'"+background+"></div><div class='title'><span>"+f+"</span></div></div>");
          }else{
            files.push("<div class='entry file' data-file='"+fb.dir+f+"'><div class='icon'><i class='fas fa-file'></i></div><div class='title'><span>"+f+"</span></div></div>");
          }
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
      $(this).append("<div class='controls'><span class='del mr-2'><i class='fas fa-trash'></i></span><span class='edit'><i class='fas fa-pencil-alt'></i></span></div>")
    });
    $("#filebrowser .address button").click(function(){
      fb.dir=$(this).attr("data-dir");
      fb.init();
    });
    $("#filebrowser .entry .edit").click(function(){
      var curname=$(this).parent().parent().parent().find(".title > span").text();
      var newname = prompt("Please enter a new name", curname);
      if (newname != null) {
          var o={mode:"rename",curname:curname,newname:newname, dir:fb.dir};
          if(curname.split(".jpg").lenght==1){
            fb.req(o,fb.init);
          }else{
            fb.req(o,fb.init);
            o.curname="__thumps/"+o.curname;
            o.newname="__thumps/"+o.newname;
            fb.req(o,fb.init);
          }
      }
    });
    $("#filebrowser .entry .del").click(function(){
      var o={};
      var isFolder=$(this).parent().parent().parent().hasClass("folder");
      var name=$(this).parent().parent().parent().find(".title > span").text();
      if(isFolder){
        o.mode="deletefolder";
        o.dir=fb.dir+name;
      }else{
        o.mode="deletefiles";
        o.files=name;
        if(name.split(".jpg").length>1)o.files+=";__thumps/"+name;
        o.dir=fb.dir;
      }
      if(confirm("Delte "+name+"?"))fb.req(o,fb.init);
    });
  }

}

$(document).ready(function(){
  fb.api="?m=files&no=1&a=api";
  fb.init();
})
