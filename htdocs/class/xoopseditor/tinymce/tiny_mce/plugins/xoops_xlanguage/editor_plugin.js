/**
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('xoops_xlanguage');

    tinymce.create('tinymce.plugins.Xoops_xlanguagePlugin', {

        init : function(ed, url)
        {
            // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
            ed.addCommand('mceXoops_xlanguage', function() {
                ed.windowManager.open({
                    file : url + '/xoops_xlanguage.php',
                    width : 600,
                    height : 380,
                    inline : 1
                }, {
                    plugin_url : url, // Plugin absolute URL
                    some_custom_arg : 'custom arg' // Custom argument
                });
            });

            // Register example button
            ed.addButton('xoops_xlanguage', {
                title : 'xoops_xlanguage.desc',
                cmd : 'mceXoops_xlanguage',
                image : url + '/img/xoops_xlanguage.png'
            });
        },

        getInfo : function() {
            return {
                longname : 'Xoops xlanguage Content plugin',
                author : 'Laurent JEN (aka DuGris)',
                version : "2"
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('xoops_xlanguage', tinymce.plugins.Xoops_xlanguagePlugin);
})();