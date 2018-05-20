nCMS.registerPlugin({
    icon:'<i class="fas fa-heading"></i>',
    identifier:"headline",
    fieldset:[
        {
            name:"variant",
            type:"select",
            data:[
                ["h1","h1"],
                ["h2","h2"],
                ["h3","h3"],
                ["h4","h4"],
                ["h5","h5"],
                ["h6","h6"]
            ]
        },
        {
            name:"text",
            type:"textfield"
        }
    ]
});
nCMS.registerPlugin({
  icon:'<i class="fas fa-align-left"></i>',
  identifier:"text",
  fieldset:[
      {
        name:"text",
        type:"editor"
      }
 ]
});
nCMS.registerPlugin({
   icon:'<i class="fas fa-download"></i>',
   identifier:"downloads",
   fieldset:[{name:"downloads",type:"filemanager",settings:{multiple:true}}]
});
nCMS.registerPlugin({
    icon:'<i class="far fa-images"></i>',
    identifier:"gallery",
    fieldset:[
        {name:"files", type:"imagemanager", settings:{multiple:true,formats:[
            ["thumb","cut:500x300"],
            ["big","fitin:2560x2560"]
        ]}}
    ]
});
nCMS.registerPlugin({
  icon:'<span style="font-size: 26px;margin: 0 0 0 -5px;"><i class="far fa-image"></i> <i class="fas fa-align-left"></i></span>',
  identifier:"image-text",
  fieldset:[{
    name:"image",
    type:"imagemanager",
    settings:{
      multiple:false,
      formats:[
        ["thumb","fitin:500x500"],
        ["big","fitin:2560x2560"]
      ]
    }},
    {
        name:"direction",
        type:"select",
        data:[["%%left%%","left"],["%%right%%","right"]]
    },
    {name:"text",type:"editor"}
  ]
});
nCMS.registerPlugin({
    icon:'<i class="fas fa-envelope-open"></i>',
    identifier:"form",
    fieldset:[
        {name:"receiver",type:"textfield"},
        {name:"handler",type:"textfield"},
        {name:"mailtemplate",type:"select",dataURL:"admin/?m=api&f=getmaillayouts&no=1"},
        {
            name:"form", type:"formmanager",fields:[
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
