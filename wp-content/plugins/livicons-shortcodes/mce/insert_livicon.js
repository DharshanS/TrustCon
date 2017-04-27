(function(){
    tinymce.create("tinymce.plugins.LiscInsertLivicon",{
        init:function(a, b){
            a.addCommand("liscInsert", function(){
                a.windowManager.open({
                    file: b + "/dialog.php",
                    width: 1000,
                    height: 730,
                    inline: 1,
                    popup_css: false
                })
            });
          
            a.addButton("lisc_insert_livicon",{
                title: "Add LivIcon",
                cmd: "liscInsert",
                image: b + "/livicon-for-mce.png"
            });
        },

        getInfo:function(){
            return{
                longname:"Add LivIcon",
                author:"DeeThemes",
                authorurl:"http://codecanyon.net/user/DeeThemes",
                version:"1.1"
            }
        }
    });
    tinymce.PluginManager.add("lisc_insert_livicon",tinymce.plugins.LiscInsertLivicon)
})();