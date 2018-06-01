nCMS.registerPlugin({
    icon:'<i class="fas fa-envelope-open"></i>',
    name:"{{name}}",
    id:"{{id}}",
    fieldset:[
        {name:"receiver",type:"textfield"},
        {name:"handler",type:"textfield"},
        {name:"mailtemplate",type:"select",dataURL:"admin/?m=api&f=getmaillayouts&no=1"},
        {
            name:"form", type:"formmanager",fields:[              
              {name:"textfield",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {name:"email",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {name:"textarea",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {name:"select",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"},{name:"data", type:"databuilder"}]},
              {name:"number",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {name:"checkbox",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {name:"registernewsletter",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"},{name:"confirmmail",type:"checkbox"}]},
              {name:"sendcopy", fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"},{name:"setinvisible",type:"checkbox"}]},
              {name:"optiongroup",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {name:"editor",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {name:"upload",fieldset:[{name:"name",type:"textfield"},{name:"required",type:"checkbox"}]},
              {name:"capcha",fieldset:[{name:"name",type:"textfield"}]}
            ]
        }
    ]
});
