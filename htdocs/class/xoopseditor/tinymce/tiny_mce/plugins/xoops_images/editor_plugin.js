/**
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('xoops_images');

    tinymce.create('tinymce.plugins.Xoops_imagesPlugin', {
        init : function(ed, url)
        {
            // Register commands
            ed.addCommand('mceXoops_images', function() {
                var e = ed.selection.getNode();

                // Internal image object like a flash placeholder
                if (ed.dom.getAttrib(e, 'class').indexOf('mceItem') != -1)
                    return;

                ed.windowManager.open({
                    file : url + '/xoops_images.php',
                    width : 600,
                    height : 400,
                    inline : 1
                }, {
                    plugin_url : url
                });
            });

            // Register buttons
            ed.addButton('xoops_images', {
                title : 'xoops_images.desc',
                cmd : 'mceXoops_images',
                image : url + '/img/xoops_images.png'
            });

        },

        getInfo : function()
        {
            return {
                longname : 'Xoops Advanced Image Manager',
                author : 'Laurent JEN (aka DuGris',
                version : "2"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('xoops_images', tinymce.plugins.Xoops_imagesPlugin);
})();