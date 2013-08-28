/**
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('xoops_quote');

    tinymce.create('tinymce.plugins.Xoops_quotePlugin', {
        init : function(ed, url) {
            // Register commands
            ed.addCommand('mceXoops_quote', function() {
                ed.windowManager.open({
                    file : url + '/xoops_quote.php',
                    width : 560,
                    height : 310,
                    inline : 1
                }, {
                    text_id : url
                });
            });

            // Register buttons
            ed.addButton('xoops_quote', {
                title : 'xoops_quote_title',
                image : url + '/img/xoops_quote.gif',
                cmd : 'mceXoops_quote'
                });
        },

        getInfo : function() {
            return {
                longname : 'Xoops_quote',
                author : 'Laurent JEN (aka DuGris)',
                version : '2'
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('xoops_quote', tinymce.plugins.Xoops_quotePlugin);
})();
