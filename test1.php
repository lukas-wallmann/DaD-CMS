nCMS.registerPlugin({
   icon:'<i class="fas fa-download"></i>',
   name:"{{name}}",
   id:"{{id}}",
   fieldset:[{name:"downloads",type:"filemanager",settings:{multiple:true}}]
});
nCMS.registerPlugin({
    icon:'<i class="fas fa-envelope-open"></i>',
    name:"{{name}}",
    id:"{{id}}",
    fieldset:[
        {name:"receiver",type:"textfield"},
        {name:"handler",type:"textfield"},
        {name:"mailtemplate",type:"select",dataURL:"?m=api&f=getmaillayouts&no=1"},
        {
            name:"form", type:"formmanager",fields:[
              {type:"textfield",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {type:"email",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {type:"textarea",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {type:"select",fieldset:[{name:"name",type:"textfield"},{name:"data", type:"databuilder"}]},
              {type:"number",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {type:"checkbox",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {type:"registernewsletter",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"},{name:"confirmmail",type:"checkbox"}]},
              {type:"sendcopy", fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"},{name:"setinvisible",type:"checkbox"}]},
              {type:"editor",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {type:"captcha",fieldset:[{name:"name",type:"textfield"}]}
            ]
        }
    ]
});

nCMS.registerPlugin({
    icon:'<i class="far fa-images"></i>',
    name:"{{name}}",
    id:"{{id}}",
    fieldset:[
        {name:"files", type:"imagemanager", settings:{multiple:true,formats:[
            ["thumb","cut:500x300",true],
            ["big","fitin:2560x2560"]
        ]}}
    ]
});
nCMS.registerPlugin({
    icon:'<i class="fas fa-heading"></i>',
    name:"{{name}}",
    id:"{{id}}",
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
  icon:'<span style="font-size: 26px;margin: 0 0 0 -5px;"><i class="far fa-image"></i> <i class="fas fa-align-left"></i></span>',
   name:"{{name}}",
   id:"{{id}}",
   fieldset:[{
    name:"image",
    type:"imagemanager",
    settings:{
      multiple:false,
      formats:[
        ["thumb","fitin:500x500",true],
        ["big","fitin:2560x2560"]
      ]
    }},
    {
        name:"alignment",
        type:"select",
        data:[["left","left"],["right","right"]]
    },
    {name:"text",type:"editor"}
  ]
});
nCMS.registerPlugin({
    icon:'<i class="fas fa-align-left"></i>',
    name:"{{name}}",
    id:"{{id}}",
    fieldset:[
      {
        name:"text",
        type:"editor"
      }
 ]
});
