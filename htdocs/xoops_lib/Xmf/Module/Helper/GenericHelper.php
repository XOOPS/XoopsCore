<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xmf\Module\Helper;

/**
 * GenericHelper implements a Xoops 2.6 Xoops_Module_Helper_Abstract.
 * We use it pre 2.6 systems so we can encapsulate many of the changes
 * needed to make modules more compatable with 2.6 in these methods.
 * The most common deprecated warnings can be avoided by using module
 * helper methods.
 *
 * @category  Xmf\Module\Helper\GenericHelper
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class GenericHelper
{
    /**
     * @var string module directory name
     */
    protected $dirname;

    /**
     * @var XoopsModule
     */
    protected $object;

    /**
     * @var array of XoopsObjectHandler|XoopsPersistableObjectHandler
     */
    private $_handlers;

    /**
     * @var array config items
     */
    protected $configs;

    /**
     * @var bool true if debug is enabled
     */
    protected $debug;

    /**
     * class constuctor
     *
     * @param string $dirname a module directory name
     */
    private function __construct($dirname)
    {
        $this->dirname = $dirname;
    }

    /**
     * Return instance of module Xmf\Module\GenericHelper for dirname
     *
     * @param string $dirname module directory name
     *
     * @return Xmf\Module\GenericHelper
     */
    public static function getInstance($dirname = 'notsetyet')
    {
        static $instance = array();
        if (!isset($instance[$dirname])) {
            $class = __CLASS__;
            $instance[$dirname] = new $class($dirname);
        }

        return $instance[$dirname];

    }

    /**
     * get the module object
     *
     * @return XoopsModule
     */
    public function getModule()
    {
        if ($this->object == null) {
            $this->_initObject();
        }
        if (!is_object($this->object)) {
            $this->addLog("ERROR :: Module '{$this->dirname}' does not exist");
        }

        return $this->object;
    }

    /**
     * get a module config item
     *
     * @param string $name name of config item, or blank for all items
     *
     * @return mixed string config item, array of config items,
     *                or null if config not found
     */
    public function getConfig($name)
    {
        if ($this->configs == null) {
            $this->_initConfig();
        }
        if (!$name) {
            $this->addLog("Getting all config");

            return $this->configs;
        }

        if (!isset($this->configs[$name])) {
            $this->addLog("ERROR :: Config '{$name}' does not exist");
            $ret = null;

            return $ret;
        }

        $this->addLog("Getting config '{$name}' : " . $this->configs[$name]);

        return $this->configs[$name];
    }

    /**
     * Get an Object Handler
     *
     * @param string $name name of handler to load
     *
     * @return bool|XoopsObjectHandler|XoopsPersistableObjectHandler
     */
    public function getHandler($name)
    {
        $ret = false;
        $name = strtolower($name);
        if (!isset($this->_handlers[$name])) {
            $this->_initHandler($name);
        }

        if (!isset($this->_handlers[$name])) {
            $this->addLog("ERROR :: Handler '{$name}' does not exist");
        } else {
            $this->addLog("Getting handler '{$name}'");
            $ret = $this->_handlers[$name];
        }

        return $ret;
    }

    /**
     * get a module object
     *
     * @return void
     */
    private function _initObject()
    {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule)
            && $xoopsModule->getVar('dirname') == $this->dirname
        ) {
            $this->object = $xoopsModule;
        } else {
            /* @var $module_handler XoopsModuleHandler */
            $module_handler = xoops_getHandler('module');
            $this->object = $module_handler->getByDirname($this->dirname);
        }
        $this->addLog('INIT MODULE OBJECT');
    }

    /**
     * get module configs
     *
     * @return void
     */
    private function _initConfig()
    {
        $this->addLog('INIT CONFIG');
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule)
            && $xoopsModule->getVar('dirname') == $this->dirname
        ) {
            global $xoopsModuleConfig;
            $this->configs =& $xoopsModuleConfig;
        } else {
            /* @var $config_handler XoopsConfigHandler */
            $config_handler = xoops_gethandler('config');
            $this->configs = $config_handler->getConfigsByCat(
                0, $this->getModule()->getVar('mid')
            );
        }
    }

    /**
     * get a handler instance and store in $this->_handlers
     *
     * @param string $name name of handler to load
     *
     * @return void
     */
    private function _initHandler($name)
    {
        $this->addLog('INIT ' . $name . ' HANDLER');

        if (!isset($this->_handlers[$name])) {
            $hnd_file = XOOPS_ROOT_PATH .
                "/modules/{$this->dirname}/class/{$name}.php";
            if (file_exists($hnd_file)) {
                include_once $hnd_file;
            }
            $class = ucfirst(strtolower($this->dirname))
                . ucfirst(strtolower($name)) . 'Handler';
            if (class_exists($class)) {
                $db = \XoopsDatabaseFactory::getDatabaseConnection();
                $this->_handlers[$name] = new $class($db);
                $this->addLog("Loading class '{$class}'");
            } else {
                $this->addLog("ERROR :: Class '{$class}' could not be loaded");
            }
        }
    }

    /**
     * load a language file for this module
     *
     * @param string $name basename of language file (i.e. 'admin')
     *
     * @return bool
     */
    public function loadLanguage($name)
    {
        if ($ret = \Xmf\Language::load($name, $this->dirname)) {
            $this->addLog("Loading language '{$name}'");
        } else {
            $this->addLog("ERROR :: Language '{$name}' could not be loaded");
        }

        return $ret;
    }

    /**
     * Set debug option on or off
     *
     * @param bool $bool true to turn on debug logging, false for off
     *
     * @return void
     */
    public function setDebug($bool = true)
    {
        $this->debug = (bool) $bool;
    }

    /**
     * Add a message to the module log
     *
     * @param string $log log message
     *
     * @return void
     */
    public function addLog($log)
    {
        if ($this->debug) {
            if (is_object($GLOBALS['xoopsLogger'])) {
                if (!is_scalar($log)) {
                    $log = serialize($log);
                }
                $GLOBALS['xoopsLogger']->addExtra(
                    is_object($this->object) ? $this->object->name()
                    : $this->dirname, $log
                );
            }
        }
    }

    // these added to mimic 2.6 Xoops_Module_Helper_Abstract

    /**
     * Is this the currently active module?
     *
     * @return bool
     */
    public function isCurrentModule()
    {
        if ($GLOBALS['xoopsModule']->getVar('dirname') == $this->dirname) {
            return true;
        }

        return false;
    }

    /**
     * Does user have admin rights to this module?
     *
     * @return bool true is user has admin right, else false
     */
    public function isUserAdmin()
    {
        return $GLOBALS['xoopsUser']->isAdmin($this->getModule()->getVar('mid'));
    }

    /**
     * Return absolute URL for a module relative URL
     *
     * @param string $url module relative URL
     *
     * @return string
     */
    public function url($url = '')
    {
        return XOOPS_URL . '/modules/' . $this->dirname . '/' . $url;
    }

    /**
     * Return absolute filesystem path for a module relative path
     *
     * @param string $path module relative file system path
     *
     * @return string
     */
    public function path($path = '')
    {
        return XOOPS_ROOT_PATH . '/modules/' . $this->dirname . '/' . $path;
    }

    /**
     * Redirect the user to a page within this module
     *
     * @param string $url     module relative url (i.e. index.php)
     * @param int    $time    time in seconds to show redirect message
     * @param string $message redirect message
     *
     * @return void
     */
    public function redirect($url, $time = 3, $message = '')
    {
        redirect_header($this->url($url), $time, $message);
        exit;
    }

}
