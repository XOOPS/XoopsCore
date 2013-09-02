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
 * Records information about database queries, blocks, and execution time
 * and can display it as HTML. It also catches php runtime errors.
 *
 * @category   Xoops\Class\Logger\Logger
 * @package    Logger
 * @author     Kazumi Ono  <onokazu@xoops.org>
 * @author     Skalpa Keo <skalpa@xoops.org>
 * @author     Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright  2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version    $Id$
 * @link       http://xoops.org
 * @since      2.6.0
 * @deprecated
 *
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


    /**
     *
     * @param $var does nothing
     * @param $val does nothing
     *
     * @return void
     * @depreciated
     */
    public function __set($var, $val)
    {
        $xoops = Xoops::getInstance();
        $xoops->deprecated("XoopsLogger is deprecated since 2.6.0, use the module 'logger' instead");
    }

    /**
     *
     * @param $var does nothing
     *
     * @return void
     * @depreciated
     */
    public function __get($var)
    {
        $xoops = Xoops::getInstance();
        $xoops->deprecated("XoopsLogger is deprecated since 2.6.0, use the module 'logger' instead");
    }

    /**
     *
     * @param $method does nothing
     * @param $args   does nothing
     *
     * @return void
     * @depreciated
     */
    public function __call($method, $args)
    {
        $xoops = Xoops::getInstance();
        $xoops->deprecated("XoopsLogger is deprecated since 2.6.0, use the module 'logger' instead");
    }
}
