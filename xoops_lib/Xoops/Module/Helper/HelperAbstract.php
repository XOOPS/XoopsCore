<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Module\Helper;

use Xoops\Core\Kernel\Handlers\XoopsModule;

/**
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2011-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class HelperAbstract
{
    /**
     * @var string dirname of the module
     */
    protected $dirname = '';

    /**
     * @var null|XoopsModule
     */
    protected $module = null;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * initialize - nothing to do here
     *
     * @return void
     */
    public function init()
    {
    }

    /**
     * @param string $dirname dirname of the module
     *
     * @return void
     */
    protected function setDirname($dirname)
    {
        $this->dirname = strtolower($dirname);
    }

    /**
     * Set debug option on or off
     *
     * @param bool $debug true to enable debugging log, false to disable
     *
     * @return void
     */
    public function setDebug($debug)
    {
        $this->debug = (bool)$debug;
    }

    /**
     * get helper instance
     *
     * @return HelperAbstract
     */
    public static function getInstance()
    {
        static $instance = false;
        $id = $className = get_called_class();
        if ($className === 'Xoops\Module\Helper\Dummy') {
            $id = @\Xoops::getInstance()->registry()->get('module_helper_id');
        }
        if (!isset($instance[$id])) {
            /* @var $class HelperAbstract */
            $class = new $className();
            $class->init();
            $instance[$id] = $class;
        }
        return $instance[$id];
    }

    /**
     * @return null|XoopsModule
     */
    public function getModule()
    {
        if ($this->module == null) {
            $this->initModule();
        }
        return $this->module;
    }

    /**
     * return the Xoops instance
     *
     * @return \Xoops
     */
    public function xoops()
    {
        return \Xoops::getInstance();
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
        $configs = $this->xoops()->getModuleConfigs($this->dirname);
        if (empty($name)) {
            $this->addLog("Getting all config");
            return $configs;
        }
        $name = strtolower($name);
        if (!isset($configs[$name])) {
            $this->addLog("ERROR :: Config '{$name}' does not exist");
            return $default;
        }

        $this->addLog("Getting config '{$name}' : " . $configs[$name]);

        return $configs[$name];
    }

    /**
     * getConfigs
     *
     * @return array of config items for module
     */
    public function getConfigs()
    {
        $result = $this->xoops()->getModuleConfigs($this->dirname);
        $this->addLog("Getting configs for {$this->dirname} module");
        return $result;
    }

    /**
     * Get handler for object managed by this module
     *
     * @param string $name object name
     *
     * @return \Xoops\Core\Kernel\XoopsObjectHandler
     */
    public function getHandler($name)
    {
        $name = strtolower($name);
        $this->addLog("Getting handler '{$name}'");
        return $this->xoops()->getModuleHandler($name, $this->dirname);
    }

    /**
     * Turn off caching for this module
     *
     * @return void
     */
    public function disableCache()
    {
        $this->xoops()->appendConfig(
            'module_cache',
            array($this->getModule()->getVar('mid') => 0),
            true,
            $this->dirname
        );
        $this->addLog("Disabling module cache");
    }

    /**
     * Is this the currently active module?
     *
     * @return bool
     */
    public function isCurrentModule()
    {
        if ($this->xoops()->moduleDirname == $this->dirname) {
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
        if ($this->xoops()->isUser()) {
            return $this->xoops()->user->isAdmin($this->getModule()->getVar('mid'));
        }
        return false;
    }

    /**
     * Return absolute URL for a module relative URL
     *
     * @param string $url URL to resolve
     *
     * @return string
     */
    public function url($url = '')
    {
        return $this->xoops()->url('modules/' . $this->dirname . '/' . $url);
    }

    /**
     * Return absolute filesystem path for a module relative path
     *
     * @param string $path path to resolve
     *
     * @return string
     */
    public function path($path = '')
    {
        return $this->xoops()->path('modules/' . $this->dirname . '/' . $path);
    }

    /**
     * Redirect the user to a page within this module
     *
     * @param string $url     url to redirect to
     * @param int    $time    time to delay
     * @param string $message message to show
     *
     * @return void
     */
    public function redirect($url, $time = 3, $message = '')
    {
        $this->xoops()->redirect($this->url($url), $time, $message, false, false);
    }

    /**
     * @param string $language language file name
     *
     * @return string
     */
    public function loadLanguage($language)
    {
        $this->xoops()->loadLanguage($language, $this->dirname);
        $this->addLog("Loading language '{$language}'");
    }

    /**
     * Load locale for our dirname
     *
     * @return void
     */
    public function loadLocale()
    {
        $this->xoops()->loadLocale($this->dirname);
        $this->addLog("Loading locale");
    }

    /**
     * @param \Xoops\Core\Kernel\XoopsObject $obj  object used to populate form
     * @param string                         $name form name
     *
     * @return \Xoops\Form\Form|boolean
     */
    public function getForm($obj, $name)
    {
        $name = strtolower($name);
        $this->addLog("Loading form '{$name}'");
        return $this->xoops()->getModuleForm($obj, $name, $this->dirname);
    }

    /**
     * Get a XoopsModule object for this module
     *
     * @return void
     */
    private function initModule()
    {
        if ($this->isCurrentModule()) {
            $this->module = $this->xoops()->module;
        } else {
            $this->module = $this->xoops()->getModuleByDirname($this->dirname);
        }
        if (!$this->module instanceof XoopsModule) {
            $this->module = $this->xoops()->getHandlerModule()->create();
        }
        $this->addLog('Loading module');
    }

    /**
     * Add a message to the module helper's log
     *
     * @param string $message message to log
     *
     * @return void
     */
    public function addLog($message)
    {
        if ($this->debug) {
            $this->xoops()->events()->triggerEvent('core.module.addlog', array(
                $this->getModule()->getVar('name'),
                $message
            ));
        }
    }
}
