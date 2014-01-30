<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Module;

use Xmf\Module\Helper\GenericHelper;

/**
 * Helper gets an instance of module helper for the specified module.
 * The helper is defined by the Xoops 2.6 Xoops_Module_Helper_Abstract
 * and in pre 2.6 systems we mimic that definition with using an
 * instance of Xmf\Module\GenericHelper.
 *
 * @category  Xmf\Module\Helper
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Helper
{
    /**
     * Get an instance to a module helper for a module directory
     *
     * @param string $dirname module direcory
     *
     * @return bool|Xoops_Module_Helper_Abstract
     */
    public static function getHelper($dirname = 'system')
    {
        // if this is a 2.6 system turn everything over to the core
        if (class_exists('Xoops', false)) {
            return \Xoops_Module_Helper::getHelper($dirname);
        }

        // otherwise get a GenericHelper instance for dirname
        $dirname = strtolower($dirname);
        if (xoops_isActiveModule($dirname)) {
            return GenericHelper::getInstance($dirname);
        }

        // not an active installed module
        return false;
    }
}
