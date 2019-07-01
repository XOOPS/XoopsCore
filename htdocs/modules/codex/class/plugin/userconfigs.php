<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use CodexLocale as t;

/**
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */
class CodexUserconfigsPlugin extends Xoops\Module\Plugin\PluginAbstract implements UserconfigsPluginInterface
{
    /**
     * Expects an array of arrays containing:
     * name,        Name of the category
     * description, Description for the category, use constant
     * The keys must be unique identifiers
     *
     * @return array
     */
    public function categories()
    {
        $categories['cat_1']['name'] = t::UCONF_CAT1;
        $categories['cat_1']['title'] = t::UCONF_CAT1_DESC;
        $categories['cat_2']['name'] = t::UCONF_CAT2;
        $categories['cat_2']['title'] = t::UCONF_CAT2_DESC;

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
        $config[$i]['title'] = 'UCONF_ITEM1';
        $config[$i]['description'] = 'UCONF_ITEM1_DESC';
        $config[$i]['formtype'] = 'select';
        $config[$i]['valuetype'] = 'int';
        $config[$i]['default'] = 1;
        $config[$i]['options'] = array_flip(['Option 1', 'Option 2']);
        $config[$i]['category'] = 'cat_1';
        ++$i;
        $config[$i]['name'] = 'config_2';
        $config[$i]['title'] = 'UCONF_ITEM2';
        $config[$i]['description'] = 'UCONF_ITEM2_DESC';
        $config[$i]['formtype'] = 'text';
        $config[$i]['valuetype'] = 'text';
        $config[$i]['default'] = 'Type Something here';
        $config[$i]['category'] = 'cat_2';

        return $config;
    }
}
