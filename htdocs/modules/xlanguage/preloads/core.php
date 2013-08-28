<?php
/**
 * Xlanguage extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         xlanguage
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Xlanguage core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class XlanguageCorePreload extends XoopsPreloadItem
{
    /**
     * @param array $args
     */
    static public function eventCoreIncludeCommonEnd($args)
    {
        if (XoopsLoad::fileExists($hnd_file = dirname(dirname(__FILE__)) . '/api.php')) {
            include_once $hnd_file;
        }
    }

    /**
     * @param array $args
     */
    static public function eventCoreHeaderCheckcache($args)
    {
        $xoops = Xoops::getInstance();
        xlanguage_select_show(explode('|', $xoops->registry()->get('XLANGUAGE_THEME_OPTIONS')));
    }
}