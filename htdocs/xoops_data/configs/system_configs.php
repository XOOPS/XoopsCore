<?php

return array(
        /**#@+
         * Extended HTML editor for {@link XoopsFormDhtmlTextArea}
         *
         * <p>If an extended HTML editor is set, the renderer will be replaced by the specified editor, usually a visual or WYSIWYG editor.</p>
         *
         * <ul>Developer and user guide:
         *  <li><ul>For run-time settings per call
         *          <li>To use an editor pre-configured by {@link XoopsEditor}, e.g. 'fckeditor': <code>$options['editor'] = 'fckeditor';</code></li>
         *          <li>To use a custom editor, e.g. 'MyEditor' class located in "/modules/myeditor/myeditor.php": <code>$options['editor'] = array('MyEditor', XOOPS_ROOT_PATH . "/modules/myeditor/myeditor.php");</code></li>
         *      </ul></li>
         *  <li><ul>For pre-configured settings, which will force to use a editor if no specific editor is set for call
         *          <li><ul>Set up custom configs: in XOOPS_VAR_PATH . '/configs/xoopsconfig.php' set a editor as default, e.g.
         *                  <li>a pre-configured editor 'fckeditor': <code>return array('editor' => 'fckeditor');</code></li>
         *                  <li>a custom editor 'MyEditor' class located in "/modules/myeditor/myeditor.php": <code>return array('editor' => array('MyEditor', XOOPS_ROOT_PATH . "/modules/myeditor/myeditor.php");</code></li>
         *              </ul></li>
         *          <li>To disable the default editor, in XOOPS_VAR_PATH . '/configs/xoopsconfig.php': <code>return array();</code></li>
         *          <li>To disable the default editor for a specific call: <code>$options['editor'] = 'dhtmltextarea';</code></li>
         *      </ul></li>
         * </ul>
         */
        //"editor"    => "fckeditor",
        //"editor"    => "dhtmlext",
        /**#@-*/

        // iframe clickjack protection - value used to set X-Frame-Options header
        //'xFrameOptions' => 'sameorigin',

        // set to true to disable shortcode processing in theme
        //'disable_theme_shortcodes' => true,

        /** XOOPS admin security warnings
         *
         * <ul>Display admin security warnings:
         *  <li> 0 - Disabled</li>
         *  <li> 1 - Enabled</li>
         * </ul>
         */
        'admin_warnings_enable' => 1,
    );
