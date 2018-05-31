var fileManagers=0;

$.fn.fileManager = function() {

    fileManagers++;
    this.fileManager=fileManagers;
    this.formats=this.attr("data-formats");
    var previewclass=" files";
    if(this.formats!=undefined){
      this.formats=JSON.parse(this.formats);
      previewclass=" images";
    }
    var multiple=" multiple";
    var accept="";
    if($(this).attr("data-allow")=="image")accept=' accept="image/*"';
    if($(this).attr("data-multiple")=="false")multiple="";
    this.append("<div class='pre mb-3' style='height:20px; display:none; background:#fff'><div class='bar bg-primary' style='height:20px'></div></div>");
    this.append('<ul class="preview'+previewclass+'"></ul>');
    this.append('<input id="fileManager'+this.fileManager+'" style="display:none" type="file"'+multiple+accept+'/>');
    this.append('<button class="btn btn-secondary mr-2 upload" onclick="document.getElementById(\'fileManager'+this.fileManager+'\').click();"><i class="fas fa-upload"></i></button>');
    this.append('<button class="btn btn-primary browse"><i class="fas fa-folder-open"></i></button>');
    this.fi=$(this).find("#fileManager"+this.fileManager);
    this.find(".browse").click(function(){
      fb.browse.init();
    });
    this.fi.change(function(evt){
      fb.uploader.files=evt.target.files;
      fb.uploader.start();
    });

    var main=this;

    var fb={
      api:"?m=files&no=1&a=api",
      list:[],

      browse:{
        dir:"",
        init:function(){
          fb.browse.load();
        },
        load:function(dir=""){
          fb.browse.dir=dir;
          var api=fb.api+"&dir="+dir;
          $.ajax({url:api}).done(function(d){
            fb.browse.build(JSON.parse(d));
          });
        },
        build:function(data){
          var code=[];
          code.push("<div class='top'><div class='nav mb-3 mt-3'>");
          code.push(fb.browse.buildNav(fb.browse.dir));
          code.push("</div>");
          code.push('<div class="buttons mb-3"><button class="btn btn-primary mr-3 save">'+consts.save+'</button><button class="btn btn-danger cancel">'+consts.cancel+'</button></div>');
          code.push('</div><div class="content">');
          code.push(fb.browse.buildEntrys(data.files));
          code.push("</div>");
          fb.browse.popup(code.join(""));
          fb.browse.setPopupFunctions();
        },
        buildNav:function(dir){
          var html=['<button type="button" data-dir="" class="btn btn-primary btn-sm mr-1"><i class="fas fa-home"></i></button>'];
          var parts=fb.browse.dir.split("/");
          var cur="";
          for(var i=0; i<parts.length-1; i++){
            cur+=parts[i]+"/";
            html.push('/<button type="button" data-dir="'+cur+'" class="btn btn-primary btn-sm mr-1 ml-1">'+parts[i]+'</button>');
          }
          return html.join("");
        },
        buildEntrys:function(data){
          var html=[];
          function getParent(dir){
            var parts=dir.split("/");
            var newpath="";
            for(var i=0; i<parts.length-2; i++){
              newpath+=parts[i]+"/";
            }
            return newpath;
          }
          function isFolder(p){
            return p.split(".").length==1;
          }
          function isImage(p){
            return p.split(".jpg").length>1 || p.split(".png").length>1;
          }
          if(fb.browse.dir!="")html.push('<div class="itm folder" data-url="'+getParent(fb.browse.dir)+'"><i class="far fa-arrow-alt-circle-left"></i></div>');
          for(var i=0; i<data.length; i++){
            var entry=data[i];
            if(entry!="." && entry!=".." && entry.substring(0,2)!="__"){
              if(isFolder(entry)){
                html.push('<div class="itm folder" data-url="'+fb.browse.dir+entry+"/"+'"><i class="fas fa-folder-open"></i><div class="name">'+entry+'</div></div>');
              }else{
                if(isImage(entry)){
                  html.push('<div class="itm file" data-url="'+fb.browse.dir+entry+'"><img src="uploads/'+fb.browse.dir+"__thumps/"+entry+'"><div class="name">'+entry+'</div></div>');
                }else{
                  html.push('<div class="itm file" data-url="'+fb.browse.dir+entry+'"><i class="fas fa-file"></i><div class="name">'+entry+'</div></div>');
                }
              }
            }
          }
          return html.join("");
        },
        popup:function(code){
          $("#popupwin").remove();
          $("body").css("padding-top","0").children("*").hide()
          var tmp=[];
          tmp.push('<div id="popupwin"><div class="inner">');
          tmp.push(code);
          tmp.push('</div></div>');
          $("body").append(tmp.join(""));
        },
        setPopupFunctions:function(){
          $("#popupwin .content").css("padding-top",($("#popupwin .top").height()+35)+"px");
          $('#popupwin .itm.file').click(function(){
            if(main.attr("data-multiple")=="false")$('#popupwin .itm.file.selected').removeClass("selected");
            $(this).toggleClass("selected");
          });
          $("#popupwin .itm.folder").click(function(){
            fb.browse.load($(this).attr("data-url"));
          });
          $('#popupwin .save').click(function(){
            var selected=[];
            $('#popupwin .selected').each(function(){
              selected.push($(this).attr("data-url"));
            });
            fb.uploader.generateForm(selected);
            $("#popupwin .cancel").click();
          })
          $("#popupwin .cancel").click(function(){
            $("#popupwin").remove();
            $("body").css("padding-top","5rem").children("*").show();
          })
        }
      },

      init:function(){
        var oldval=JSON.parse(main.find('.saveme').text());
        console.log(fb.list);
        for(var i=0; i<fb.list.length; i++){
          oldval.push(fb.list[i]);
        }
        fb.list=[];
        console.log("fb list resetted");
        console.log(fb.list);
        main.find('.saveme').text(JSON.stringify(oldval));
        main.find(".preview").html("");
        if(main.formats!=undefined){
          for(var i=0; i<oldval.length; i++){
              main.find(".preview").append('<li><img src="'+oldval[i].autothump+'"><div class="delete"><i class="fas fa-trash-alt"></i></div><textarea style="display:none">'+JSON.stringify(oldval[i])+'</textarea></li>');
          }
        }else{
          for(var i=0; i<oldval.length; i++){
              main.find(".preview").append('<li><div class="file">'+oldval[i].link+'</div><input class="form-control" value="'+oldval[i].name+'"><div class="delete"><i class="fas fa-trash-alt"></i></div></li>');
          }
        }
        main.find(".preview .delete").click(function(){
          $(this).parent().remove();
          fb.updateval();
        })

        main.find(".preview").sortable({stop:fb.updateval});


      },

      updateval:function(){
        var tmp=[];
        if(main.formats!=undefined){
          main.find(".preview li textarea").each(function(){
            tmp.push(JSON.parse($(this).text()));
          });
        }else{
          main.find(".preview li").each(function(){
            tmp.push({link:$(this).children(".file").text(),name:$(this).children("input").val()});
          })
        }
        main.find(".saveme").text(JSON.stringify(tmp));


      },


      uploader:{

        files:[],
        at:0,
        cache:[],
        wait:0,
        wat:0,
        cat:0,
        gFrom:false,

        start:function(){
          fb.uploader.reset();
          fb.uploader.run();
        },

        reset:function(){
          fb.uploader.at=0;
          fb.uploader.cat=0;
          fb.uploader.wait=0;
          fb.uploader.wat=0;
          fb.uploader.cache=[];
          fb.uploader.gFrom=false;
        },

        generateForm:function(arr){
          fb.uploader.reset();
          fb.uploader.gFrom=true;
          if(main.formats==undefined){
            for(var i=0; i<arr.length; i++){
                fb.list.push({name:arr[i], link:arr[i]})
                fb.init();
            }
          }else{
            fb.uploader.files=arr;
            fb.uploader.run();
          }

        },

        resize:function(url,info=["fitin",1920,1080],name="file.jpg",callback=function(){},type="image/jpg",ident="",q=0.8){
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
              var dataUrl = canvas.toDataURL(type,q);
              callback(dataUrl,image.name,ident);
          }

          image.src = url
          image.info=info;
          image.name=name;
        },

        run:function(){
          if(!fb.uploader.gFrom){
            var reader = new FileReader();

            reader.onload = (function(theFile) {
              return function(e) {
                // Render thumbnail.
                if (theFile.type=="image/jpeg" || theFile.type=="image/png") {
                  if(main.formats!=undefined){
                    fb.uploader.wait=2+main.formats.length;
                    fb.uploader.resize(e.target.result,["crop",120,120],"__thumps/"+theFile.name,fb.uploader.callback,theFile.type,"autothump");
                    fb.uploader.callback(e.target.result,theFile.name);
                    for(var i=0; i<main.formats.length; i++){
                      var name=main.formats[i][0];
                      var format=main.formats[i][1].split(":");
                      format[1]=format[1].split("x");

                      fb.uploader.resize(e.target.result,[format[0],format[1][0],format[1][1]],"__"+name+"/"+theFile.name,fb.uploader.callback,theFile.type,name);
                    }
                  }else{
                    fb.uploader.wait=2;
                    fb.uploader.resize(e.target.result,["crop",120,120],"__thumps/"+theFile.name,fb.uploader.callback,theFile.type,"autothump");
                    fb.uploader.callback(e.target.result,theFile.name);
                  }
                }else{
                  fb.uploader.wait=1;
                  fb.uploader.callback(e.target.result,theFile.name);
                }
              };
            })(fb.uploader.files[fb.uploader.at]);
            reader.readAsDataURL(fb.uploader.files[fb.uploader.at]);
          }else{
            var theFile=fb.uploader.files[fb.uploader.at];
            var parts=theFile.split(".");
            var type="image/"+parts[parts.length-1];
            fb.uploader.wait=1+main.formats.length;
            parts=theFile.split("/");
            var dir="";
            for(var i=0; i<parts.length-1; i++){
              dir+=parts[i]+"/";
            }
            var file=parts[parts.length-1];
            fb.uploader.resize("uploads/"+theFile,["crop",120,120],dir+"__thumps/"+file,fb.uploader.callback,type,"autothump");
            for(var i=0; i<main.formats.length; i++){
              var name=main.formats[i][0];
              var format=main.formats[i][1].split(":");
              format[1]=format[1].split("x");
              fb.uploader.resize("uploads/"+theFile,[format[0],format[1][0],format[1][1]],dir+"__"+name+"/"+file,fb.uploader.callback,type,name);
            }
          }
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
          $.post( fb.api, { filename: fb.uploader.cache[fb.uploader.cat][0], data:fb.uploader.cache[fb.uploader.cat][1], dir:fb.dir, mode:"upload"  } ).done(function(d){
           fb.uploader.cat++;
           main.find(".pre").show();
          main.find(".pre .bar").width(((fb.uploader.at+(fb.uploader.cat/fb.uploader.cache.length))/fb.uploader.files.length*100)+"%");
           if(main.formats!=undefined && ident!=""){
             if(fb.list[fb.uploader.at]==undefined){
               fb.list[fb.uploader.at]={};
             }
             var data=fb.list[fb.uploader.at];
             data[ident]=d;
             fb.list[fb.uploader.at]=data;
           }else if(main.formats==undefined && ident==""){
             fb.list[fb.uploader.at]={link:d,name:d};
           }
           if(fb.uploader.cat<fb.uploader.cache.length){
             fb.uploader.upload();
           }else{
             fb.uploader.at++;
             fb.uploader.cat=0;
             if(fb.uploader.at<fb.uploader.files.length){
               fb.uploader.run();
             }else{
               main.find(".pre").hide();
               fb.init();
             }
           }
         });
       }

      }
    }

    fb.init();
    return this;
};
