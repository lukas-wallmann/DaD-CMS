nCMS.registerPlugin({
    icon:'<i class="fas fa-heading"></i>',
    identifier:"headline",
    fieldset:[
        {name:"variant",type:"select",data:[["h1","h1"],["h2","h2"],["h3","h3"],["h4","h4"],["h5","h5"],["h6","h6"]]},
        {name:"text",type:"textfield"}
    ]
});

nCMS.registerPlugin({
    icon:"form",
    identifier:"form",
    fieldset:[
        {name:"receiver",type:"textfield"},
        {name:"handler",type:"textfield"},
        {name:"mailtemplate",type:"select",dataURL:"admin/?m=api&f=getmaillayouts&no=1"},
        {
            name:"formbuilder",fields:[
              {type:"textfield"},
              {type:"email"},
              {type:"textarea"},
              {type:"select"},
              {type:"number"},
              {type:"checkbox"},
              {type:"registernewsletter", option:["noconfirm","confirm"]},
              {type:"sendcopy", options:["visible","invisible"]},
              {type:"optiongroup"},
              {type:"editor"},
              {type:"upload"},
              {type:"capcha"}
            ]
        }
    ]
});

nCMS.registerPlugin({
   icon:"download",
   identifier:"download",
   fieldset:[{name:"downloads",type:"filemanager",settings:{multiple:true}}]
});

nCMS.registerPlugin({
    icon:"gallery",
    identifier:"gallery",
    fieldset:[
        {name:"files", type:"imagemanager", settings:{multiple:true,formats:[
            ["thump","cut:500x300"],
            ["big","fitin:2560x2560"]
        ]}}
    ]
});

nCMS.registerPlugin({
     icon:"html",
     identifier:"html",
     fieldset:{name:"html",type:"codeeditor",settings:{format:"html"}}
});
