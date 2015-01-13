<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class CodexUserconfigsPlugin extends Xoops\Module\Plugin\PluginAbstract implements UserconfigsPluginInterface
{
    /**
     * Expects an array of arrays containing:
     * name,        Name of the category
     * description, Description for the category, use constant
     * The keys must be unique identifiers
     */
    public function categories()
    {
        $categories['cat_1']['name'] = _MI_CODEX_UCONF_CAT1;
        $categories['cat_1']['title'] = _MI_CODEX_UCONF_CAT1_DSC;
        $categories['cat_2']['name'] = _MI_CODEX_UCONF_CAT2;
        $categories['cat_2']['title'] = _MI_CODEX_UCONF_CAT2_DSC;
        return $categories;
    }

    /**
     * Expects an array of arrays containing:
     * name,        Name of the config
     * title,       Display name for the config, use constant
     * description, Description for the config, use constant
     * formtype,    Form to use for the config
     * default,     Default value for the config
     * options,     Options available for the config
     * category,    Category for this config, use the unique identifier set on categories()
     */
    public function configs()
    {
        $i = 0;
        $config[$i]['name'] = 'config_1';
        $config[$i]['title'] = '_MI_CODEX_UCONF_ITEM1';
        $config[$i]['description'] = '_MI_CODEX_UCONF_ITEM1_DSC';
        $config[$i]['formtype'] = 'select';
        $config[$i]['valuetype'] = 'int';
        $config[$i]['default'] = 1;
        $config[$i]['options'] = array_flip(array('Option 1', 'Option 2'));
        $config[$i]['category'] = 'cat_1';
        $i++;
        $config[$i]['name'] = 'config_2';
        $config[$i]['title'] = '_MI_CODEX_UCONF_ITEM2';
        $config[$i]['description'] = '_MI_CODEX_UCONF_ITEM2_DSC';
        $config[$i]['formtype'] = 'text';
        $config[$i]['valuetype'] = 'text';
        $config[$i]['default'] = 'Type Something here';
        $config[$i]['category'] = 'cat_2';
        return $config;
    }
}
