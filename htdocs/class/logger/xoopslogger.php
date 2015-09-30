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
 * @copyright  2013 XOOPS Project (http://xoops.org)
 * @license    GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link       http://xoops.org
 * @see        Xoops\Logger
 * @deprecated since 2.6.0
 *
 */
class XoopsLogger
{
    /**
     * getInstance - get only instance of this class
     * 
     * @return object XoopsLogger
     * @deprecated
     */
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
     * deprecatedWarning - centralized warning for all methods
     * 
     * @return void
     */
    private function deprecatedWarning()
    {
        Xoops::getInstance()->deprecated('XoopsLogger is deprecated since 2.6.0, see Xoops\Core\Logger');
    }

    /**
     * magic set method
     * 
     * @param string $var does nothing
     * @param mixed  $val does nothing
     *
     * @return void
     * @depreciated
     */
    public function __set($var, $val)
    {
        $this->deprecatedWarning();
    }

    /**
     * magic get method
     *
     * @param mixed $var does nothing
     *
     * @return void
     * @depreciated
     */
    public function __get($var)
    {
        $this->deprecatedWarning();
    }

    /**
     * magic call method
     * 
     * @param string $method does nothing
     * @param mixed  $args   does nothing
     *
     * @return void
     * @depreciated
     */
    public function __call($method, $args)
    {
        $this->deprecatedWarning();
    }
}
