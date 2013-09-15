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
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         logger
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Logger system preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class DebugbarSystemPreload extends XoopsPreloadItem
{

    /**
     * eventSystemPreferencesSave
     * 
     * @param mixed $args arguments supplied to triggerEvent
     * 
     * @return void
     */
    static function eventSystemPreferencesSave($args)
    {
        /*
        if (isset($_REQUEST['debug_plugin'])) {
            Xoops_Cache::delete('module_debugbar_plugin');
        }
        */
    }

}