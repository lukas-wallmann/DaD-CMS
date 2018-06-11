var editor="";

var themeEditor={
  init:function(){
    themeEditor.helpers.getCodeEditor();
  },
  helpers:{
    getCodeEditor:function(){
      editor = ace.edit("editor");
      editor.setTheme("ace/theme/monokai");
      editor.session.setMode("ace/mode/html");
      editor.session.setUseWorker(false);
      editor.setOptions({
        fontSize: "13pt"
      });
    }
  }
}

$(document).ready(themeEditor.init);
