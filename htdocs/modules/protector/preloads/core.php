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
 * Protector
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         protector
 * @since           2.4.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Protector core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 */
class ProtectorCorePreload extends XoopsPreloadItem
{
    /**
     * @static
     *
     * @param $args
     */
    static function eventCoreIncludeCommonStart($args)
    {
        $xoops = Xoops::getInstance();
        include $xoops->path('modules/protector/include/precheck.inc.php');
    }

    /**
     * @static
     *
     * @param $args
     */
    static function eventCoreIncludeCommonEnd($args)
    {
        $xoops = Xoops::getInstance();
        include $xoops->path('modules/protector/include/postcheck.inc.php');
    }

    /**
     * @static
     *
     * @param $args
     */
    static function eventCoreClassDatabaseDatabasefactoryConnection($args)
    {
        // Protector class
        require_once dirname(__DIR__) . '/class/protector.php';

        // Protector object
        $protector = Protector::getInstance();
        $conf = $protector->getConf();
        // "DB Layer Trapper"
        $force_override = strstr(@$_SERVER['REQUEST_URI'], 'protector/admin/index.php?page=advisory') ? true : false;
        //$force_override = true ;
        if ($force_override || !empty($conf['enable_dblayertrap'])) {
            @define('PROTECTOR_ENABLED_ANTI_SQL_INJECTION', 1);
            $protector->dblayertrap_init($force_override);
        }
        if (defined('XOOPS_DB_ALTERNATIVE') && class_exists(XOOPS_DB_ALTERNATIVE)) {
            $args[0] = XOOPS_DB_ALTERNATIVE;
        }
    }
}
