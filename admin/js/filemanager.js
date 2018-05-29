var fileManagers=0;

$.fn.fileManager = function() {

    fileManagers++;
    this.fileManager=fileManagers;
    this.formats=this.attr("data-formats");
    if(this.formats!=undefined)this.formats=JSON.parse(this.formats);
    var multiple=" multiple";
    var accept="";
    if($(this).attr("data-allow")=="image")accept=' accept="image/*"';
    if($(this).attr("data-multiple")==false)multiple="";
    this.append('<div class="preview"></div>');
    this.append('<input id="fileManager'+this.fileManager+'" style="display:none" type="file"'+multiple+accept+'/>');
    this.append('<button class="btn btn-secondary mr-2 upload" onclick="document.getElementById(\'fileManager'+this.fileManager+'\').click();"><i class="fas fa-upload"></i></button>');
    this.fi=$(this).find("#fileManager"+this.fileManager);
    this.fi.change(function(evt){
      fb.uploader.files=evt.target.files;
      fb.uploader.start();
    });

    var main=this;

    var fb={
      api:"?m=files&no=1&a=api",
      list:[],

      init:function(){

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

        resize:function(url,info=["fitin",1920,1080],name="file.jpg",callback=function(){},ident="",q=0.8){
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
                  y=0;
                var relw = widthto/width;
                var relh = heigthto/height;

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
              if(relto>1)relto=1;
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
              callback(dataUrl,image.name,ident);
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
                if(main.formats!=undefined){
                  fb.uploader.wait=2+main.formats.length;
                  fb.uploader.resize(e.target.result,["crop",120,120],"__thumps/"+theFile.name,fb.uploader.callback);
                  fb.uploader.callback(e.target.result,theFile.name);
                  for(var i=0; i<main.formats.length; i++){
                    var name=main.formats[i][0];
                    var format=main.formats[i][1].split(":");
                    format[1]=format[1].split("x");

                    fb.uploader.resize(e.target.result,[format[0],format[1][0],format[1][1]],"__"+name+"/"+theFile.name,fb.uploader.callback,name);
                  }
                }else{
                  fb.uploader.wait=1;
                  fb.uploader.callback(e.target.result,theFile.name);
                }
              }else{
                fb.uploader.wait=1;
                fb.uploader.callback(e.target.result,theFile.name);
              }
            };
          })(fb.uploader.files[fb.uploader.at]);

          reader.readAsDataURL(fb.uploader.files[fb.uploader.at]);
        },

        callback:function(data,filename,ident=""){
          fb.uploader.cache.push([filename,data,ident]);
          fb.uploader.wat++;
          if(fb.uploader.wat==fb.uploader.wait){
            fb.uploader.wat=0;
            fb.uploader.upload();
          }
        },

        upload:function(){
          var ident=fb.uploader.cache[fb.uploader.cat][2];
          console.log("ident:"+ident);
          $.post( fb.api, { filename: fb.uploader.cache[fb.uploader.cat][0], data:fb.uploader.cache[fb.uploader.cat][1], dir:fb.dir, mode:"upload"  } ).done(function(d){
           fb.uploader.cat++;
           $(".prog").show();
           $(".prog .bar").width(((fb.uploader.at+(fb.uploader.cat/fb.uploader.cache.length))/fb.uploader.files.length*100)+"%");
           if(ident!=""){
             if(fb.list[fb.uploader.at]==undefined){
               fb.list[fb.uploader.at]={};
             }
             var data=fb.list[fb.uploader.at];
             data[ident]=d;
             fb.list[fb.uploader.at]=data;
             console.log(fb.list);
           }
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

      }
    }


    return this;
};
