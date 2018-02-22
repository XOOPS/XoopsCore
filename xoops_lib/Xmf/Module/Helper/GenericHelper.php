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

use Xmf\Language;

/**
 * GenericHelper implements a Xoops 2.6 Xoops\Module\Helper\HelperAbstract.
 * We use it pre 2.6 systems so we can encapsulate many of the changes
 * needed to make modules more compatible with 2.6 in these methods.
 * The most common deprecated warnings can be avoided by using module
 * helper methods.
 *
 * @category  Xmf\Module\Helper\GenericHelper
 * @package   Xmf
 * @author    trabis <lusopoemas@gmail.com>
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2018 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class GenericHelper extends AbstractHelper
{
    /**
     * @var string module directory name
     */
    protected $dirname;

    /**
     * @var \XoopsModule
     */
    protected $object;

    /**
     * @var array of XoopsObjectHandler|XoopsPersistableObjectHandler
     */
    protected $handlers;

    /**
     * @var array config items
     */
    protected $configs;

    /**
     * Initialize parent::__construct calls this after verifying module object.
     *
     * @return void
     */
    public function init()
    {
        $this->object = $this->module;
        $this->dirname = $this->object->getVar('dirname');
    }

    /**
     * get the module object
     *
     * @return \XoopsModule
     */
    public function getModule()
    {
        if ($this->object == null) {
            $this->initObject();
        }
        if (!is_object($this->object)) {
            $this->addLog("ERROR :: Module '{$this->dirname}' does not exist");
        }

        return $this->object;
    }

    /**
     * get a module config item
     *
     * @param string $name    name of config item, or blank for all items
     * @param mixed  $default default value to return if config $name is not set
     *
     * @return mixed string config item, array of config items,
     *                or null if config not found
     */
    public function getConfig($name = null, $default = null)
    {
        if ($this->configs == null) {
            $this->initConfig();
        }
        if (empty($name)) {
            $this->addLog("Getting all config");

            return $this->configs;
        }

        if (!isset($this->configs[$name])) {
            $this->addLog("ERROR :: Config '{$name}' does not exist");
            return $default;
        }

        $this->addLog("Getting config '{$name}' : " . $this->serializeForHelperLog($this->configs[$name]));

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
        if (!isset($this->handlers[$name])) {
            $this->initHandler($name);
        }

        if (!isset($this->handlers[$name])) {
            $this->addLog("ERROR :: Handler '{$name}' does not exist");
        } else {
            $this->addLog("Getting handler '{$name}'");
            $ret = $this->handlers[$name];
        }

        return $ret;
    }

    /**
     * get a module object
     *
     * @return void
     */
    protected function initObject()
    {
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule)
            && $xoopsModule->getVar('dirname') == $this->dirname
        ) {
            $this->object = $xoopsModule;
        } else {
            /* @var $module_handler \XoopsModuleHandler */
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
    protected function initConfig()
    {
        $this->addLog('INIT CONFIG');
        global $xoopsModule;
        if (isset($xoopsModule) && is_object($xoopsModule)
            && $xoopsModule->getVar('dirname') == $this->dirname
        ) {
            global $xoopsModuleConfig;
            $this->configs = $xoopsModuleConfig;
        } else {
            /* @var $config_handler \XoopsConfigHandler */
            $config_handler = xoops_getHandler('config');
            $this->configs = $config_handler->getConfigsByCat(0, $this->getModule()->getVar('mid'));
        }
    }

    /**
     * get a handler instance and store in $this->_handlers
     *
     * @param string $name name of handler to load
     *
     * @return void
     */
    protected function initHandler($name)
    {
        $this->addLog('INIT ' . $name . ' HANDLER');

        if (!isset($this->handlers[$name])) {
            $hnd_file = XOOPS_ROOT_PATH . "/modules/{$this->dirname}/class/{$name}.php";
            if (file_exists($hnd_file)) {
                include_once $hnd_file;
            }
            $class = ucfirst(strtolower($this->dirname))
                . ucfirst(strtolower($name)) . 'Handler';
            if (class_exists($class)) {
                $db = \XoopsDatabaseFactory::getDatabaseConnection();
                $this->handlers[$name] = new $class($db);
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
        if ($ret = Language::load($name, $this->dirname)) {
            $this->addLog("Loading language '{$name}'");
        } else {
            $this->addLog("ERROR :: Language '{$name}' could not be loaded");
        }

        return $ret;
    }

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
        return (isset($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser'] instanceof \XoopsUser)
            ? $GLOBALS['xoopsUser']->isAdmin($this->getModule()->getVar('mid')) : false;
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
    }
}
