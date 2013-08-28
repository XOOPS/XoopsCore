/**
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('xoops_code');

    tinymce.create('tinymce.plugins.Xoops_codePlugin', {
        init : function(ed, url) {
            // Register commands
            ed.addCommand('mceXoops_code', function() {
                ed.windowManager.open({
                    file : url + '/xoops_code.php',
                    width : 560,
                    height : 310,
                    inline : 1
                }, {
                    text_id : url
                });
            });

            // Register buttons
            ed.addButton('xoops_code', {
                title : 'xoops_code.code_desc',
                image : url + '/img/xoops_code.gif',
                cmd : 'mceXoops_code'
                });
        },

        getInfo : function() {
            return {
                longname : 'Xoops_code',
                author : 'Laurent JEN (aka DuGris)',
                version : '2'
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('xoops_code', tinymce.plugins.Xoops_codePlugin);
})();
