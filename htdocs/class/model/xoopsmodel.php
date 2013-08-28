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
 * Object factory class.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      model
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Factory for object handlers
 *
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @package kernel
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
     * @access public
     *
     * @param XoopsPersistableObjectHandler $oHandler reference to {@link XoopsPersistableObjectHandler}
     * @param $name
     * @param null $args
     * @return null
     */
    public function loadHandler(XoopsPersistableObjectHandler $oHandler, $name, $args = null)
    {
        if (!isset(self::$handlers[$name])) {
            $handler = null;
            if (XoopsLoad::fileExists($file = dirname(__FILE__) . '/' . $name . '.php')) {
                include_once $file;
                $className = 'XoopsModel' . ucfirst($name);
                $handler = new $className();
            } else {
                if (XoopsLoad::load('model', 'framework')) {
                    $handler = XoopsModel::loadHandler($name);
                }
            }
            if (!is_object($handler)) {
                trigger_error('Handler not found in file ' . __FILE__ . 'at line ' . __LINE__, E_USER_WARNING);
                return null;
            }
            self::$handlers[$name] = $handler;
        }
        /* @var $handler XoopsModelAbstract */
        $handler = clone (self::$handlers[$name]);
        $handler->setHandler($oHandler);
        if (!empty($args) && is_array($args) && is_a($handler, 'XoopsModelAbstract')) {
            $handler->setVars($args);
        }
        return $handler;
    }
}

/**
 * abstract class object handler
 *
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @package kernel
 */
class XoopsModelAbstract
{
    /**
     * holds referenced to handler object
     *
     * @var XoopsPersistableObjectHandler $handler reference to {@link XoopsPersistableObjectHandler}
     * @access protected
     */
    protected $handler;

    /**
     * XoopsModelAbstract::setHandler()
     *
     * @param XoopsPersistableObjectHandler $handler reference to {@link XoopsPersistableObjectHandler}
     * @return true
     */
    public function setHandler(XoopsPersistableObjectHandler $handler)
    {
        $this->handler = $handler;
        return true;
    }

    /**
     * XoopsModelAbstract::setVars()
     *
     * @param mixed $args
     * @return true
     */
    public function setVars($args)
    {
        if (!empty($args) && is_array($args)) {
            foreach ($args as $key => $value) {
                $this->$key = $value;
            }
        }
        return true;
    }
}