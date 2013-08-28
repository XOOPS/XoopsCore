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
 * Xoops Logger handlers - component main class file
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      logger
 * @since           2.3.0
 * @author          Kazumi Ono  <onokazu@xoops.org>
 * @author          Skalpa Keo <skalpa@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Collects information for a page request
 *
 * Records information about database queries, blocks, and execution time
 * and can display it as HTML. It also catches php runtime errors.
 *
 * @deprecated
 */
class XoopsLogger
{

     public static function getInstance()
     {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
     }


    public function __set($var, $val)
    {
        $xoops = Xoops::getInstance();
        $xoops->deprecated("XoopsLogger is deprecated since 2.6.0, use the module 'logger' instead");
    }

    public function __get($var)
    {
        $xoops = Xoops::getInstance();
        $xoops->deprecated("XoopsLogger is deprecated since 2.6.0, use the module 'logger' instead");
    }

    public function __call($method, $args)
    {
        $xoops = Xoops::getInstance();
        $xoops->deprecated("XoopsLogger is deprecated since 2.6.0, use the module 'logger' instead");
    }
}