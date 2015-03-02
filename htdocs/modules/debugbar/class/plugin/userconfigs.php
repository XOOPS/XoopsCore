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
 * Debugbar core preloads
 *
 * @category  Debugbar
 * @package   Debugbar
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class DebugbarUserconfigsPlugin extends Xoops\Module\Plugin\PluginAbstract implements UserconfigsPluginInterface
{
    /**
     * Build an array of configurable item categories.
     *
     * Each category consists of an array with the following key - value pairs
     * name,        Name of the category
     * description, Description for the category, use constant
     *
     * Each category must have a unique key that can be specified in
     * each related configurable item.
     *
     * @return array of categories
     */
    public function categories()
    {
        $categories['cat_options'] = array(
            'name'  => _MI_DEBUGBAR_UCONF_CAT_OPT,
            'title' => _MI_DEBUGBAR_UCONF_CAT_OPT_DESC
        );
        return $categories;
    }

    /**
     * Build an array of user configurable items.
     *
     * Each item consists of an array with the following key - value pairs
     *  - name        Name of the config
     *  - title       Display name for the config, use constant
     *  - description Description for the config, use constant
     *  - formtype    Form to use for the config
     *  - default     Default value for the config
     *  - options     Options available for the config
     *  - category    Category for this config, use the unique identifier set on categories()
     *
     * @return array of user configurable items
     */
    public function configs()
    {
        $config[]=array(
            'name' => 'debugbar_enable',
            'title' => '_MI_DEBUGBAR_UCONF_ENABLE_BAR',
            'description' => '',
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 0,
            'options' => array(),
            'category' => 'cat_options'
        );

        $config[]=array(
            'name' => 'debug_smarty_enable',
            'title' => '_MI_DEBUGBAR_UCONF_ENABLE_SMARTY',
            'description' => '',
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 0,
            'options' => array(),
            'category' => 'cat_options'
        );

        return $config;
    }
}
