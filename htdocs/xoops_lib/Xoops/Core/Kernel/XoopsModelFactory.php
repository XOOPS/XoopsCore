<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Kernel;

/**
 * Object factory class.
 *
 * @category  Xoops\Core\Kernel\XoopsModelFactory
 * @package   Xoops\Core\Kernel
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.3.0
 */
class XoopsModelFactory
{
    /**
     * static private
     */
    static private $handlers = array();

    /**
     * Get singleton instance
     *
     * @access public
     * @return XoopsModelFactory
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
     * Load object handler
     *
     * @param XoopsPersistableObjectHandler $oHandler handler to load
     * @param string                        $name     name
     * @param mixed                         $args     args
     *
     * @return null|XoopsModelAbstract
     */
    public function loadHandler(XoopsPersistableObjectHandler $oHandler, $name, $args = null)
    {
        if (!isset(self::$handlers[$name])) {
            $handler = null;
            $className = '\Xoops\Core\Kernel\Model\\' . ucfirst($name);
            @$handler = new $className();
            if (!is_object($handler)) {
                trigger_error('Handler ' . $className . ' not found in file ' . __FILE__, E_USER_WARNING);
                return null;
            }
            self::$handlers[$name] = $handler;
        }
        /* @var $handler XoopsModelAbstract */
        $handler = clone (self::$handlers[$name]);
        $handler->setHandler($oHandler);
        if (!empty($args) && is_array($args) && is_a($handler, 'Xoops\Core\Kernel\XoopsModelAbstract')) {
            $handler->setVars($args);
        }
        return $handler;
    }
}
