/**
 * @author          Laurent JEN (aka DuGris)
 * @version         $Id$
 */

(function() {
    // Load plugin specific language pack
    tinymce.PluginManager.requireLangPack('xoops_smilies');

    tinymce.create('tinymce.plugins.Xoops_smiliesPlugin', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         *
         * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
         * @param {string} url Absolute URL to where the plugin is located.
         */
        init : function(ed, url) {
            // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceXoops_smilies');
            ed.addCommand('mceXoops_smilies', function() {
                ed.windowManager.open({
                    file : url + '/xoops_smilies.php',
                    width : 600,
                    height : 380,
                    inline : 1,
                    scrollbars : 1
                }, {
                    plugin_url : url // Plugin absolute URL
                });
            });

            // Register xoops_smilies button
            ed.addButton('xoops_smilies', {
                title : 'xoops_smilies.desc',
                cmd : 'mceXoops_smilies',
                image : url + '/img/xoops_smilies.png'
            });
        },
        /**
         * Returns information about the plugin as a name/value array.
         * The current keys are longname, author, authorurl, infourl and version.
         *
         * @return {Object} Name/value array containing information about the plugin.
         */
        getInfo : function() {
            return {
                longname : 'Xoops Smilies plugin',
                author : 'Laurent Jen (aka DuGris',
                version : ""
            };
        }
    });

    // Register plugin
    tinymce.PluginManager.add('xoops_smilies', tinymce.plugins.Xoops_smiliesPlugin);
})();