<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Theme;

/**
 * Admin theme factory
 *
 * @category  Xoops\Core
 * @package   Theme
 * @author    Andricq Nicolas (AKA MusS)
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2009-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class AdminFactory extends Factory
{
    /**
     * Create an admin theme instance
     *
     * @param array $options theme options
     *
     * @return XoopsTheme
     */
    public function createInstance($options = array())
    {
        $options["plugins"] = array();
        $options['renderBanner'] = false;
        $inst = parent::createInstance($options);
        $inst->path = \XoopsBaseConfig::get('adminthemes-path') . '/' . $inst->folderName;
        $inst->url = \XoopsBaseConfig::get('adminthemes-url') . '/' . $inst->folderName;
        $inst->template->assign(array(
            'theme_path' => $inst->path, 'theme_tpl' => $inst->path . '/xotpl', 'theme_url' => $inst->url,
            'theme_img'  => $inst->url . '/img', 'theme_icons' => $inst->url . '/icons',
            'theme_css'  => $inst->url . '/css', 'theme_js' => $inst->url . '/js',
            'theme_lang' => $inst->url . '/language',
        ));

        return $inst;
    }
}
