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
/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

abstract class HelperAbstract
{
    /**
     * @var string dirname of the module
     */
    protected $_dirname = '';

    /**
     * @var null|XoopsModule
     */
    protected $_module = null;

    /**
     * @var bool
     */
    protected $_debug = false;

    public function init()
    {
    }

    /**
     * @param string $dirname dirname of the module
     */
    protected function setDirname($dirname)
    {
        $this->_dirname = strtolower($dirname);
    }

    /**
     * @param bool $debug
     */
    protected function setDebug($debug)
    {
        $this->_debug = (bool)$debug;
    }

    /**
     * @return Xoops\Module\Helper\HelperAbstract
     */
    static function getInstance()
    {
        static $instance = false;
        $id = $className = get_called_class();
        if ($className == 'Xoops\Module\Helper\Dummy') {
            $id = @\Xoops::getInstance()->registry()->get('module_helper_id');
        }
        if (!isset($instance[$id])) {
            /* @var $class Xoops\Module\Helper\HelperAbstract */
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
        if ($this->_module == null) {
            $this->_initModule();
        }
        return $this->_module;
    }

    public function xoops()
    {
        return \Xoops::getInstance();
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getConfig($name)
    {
        $name = strtolower($name);
        $result = $this->xoops()->getModuleConfig($name, $this->_dirname);
        $this->_addLog("Getting config '{$name}' : " . $result);
        return $result;
    }

    /**
     * getConfigs
     *
     * @return array of config items for module
     */
    public function getConfigs()
    {
        $result = $this->xoops()->getModuleConfigs($this->_dirname);
        $this->_addLog("Getting configs for {$this->_dirname} module");
        return $result;
    }

    /**
     * @param string $name
     *
     * @return XoopsObjectHandler
     */
    public function getHandler($name)
    {
        $name = strtolower($name);
        $this->_addLog("Getting handler '{$name}'");
        return $this->xoops()->getModuleHandler($name, $this->_dirname);
    }

    public function disableCache()
    {
        $this->xoops()->appendConfig('module_cache', array($this->getModule()->getVar('mid') => 0), true, $this->_dirname);
        $this->_addLog("Disabling module cache");
    }

    /**
     * Is this the currently active module?
     *
     * @return bool
     */
    public function isCurrentModule()
    {
        if ($this->xoops()->moduleDirname == $this->_dirname) {
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
     * TODO - reevaluate this even existing here - this is not module
     * related and we should have easy access to this (and similar)
     * elsewhere (maybe a user helper?)
     */
    public function getUserGroups()
    {
        return $this->xoops()->isUser() ? $this->xoops()->user->getGroups() : XOOPS_GROUP_ANONYMOUS;
    }

    /**
     * Return absolute URL for a module relative URL
     *
     * @param string $url
     *
     * @return string
     */
    public function url($url = '')
    {
        return $this->xoops()->url('modules/' . $this->_dirname . '/' . $url);
    }

    /**
     * Return absolute filesystem path for a module relative path
     *
     * @param string $path
     *
     * @return string
     */
    public function path($path = '')
    {
        return $this->xoops()->path('modules/' . $this->_dirname . '/' . $path);
    }

    /**
     * Redirect the user to a page within this module
     *
     * TODO remove addredirect and allowExternalLink paramaters since
     * they are pointless in this context.
     *
     * @param        $url
     * @param int    $time
     * @param string $message
     * @param bool   $addredirect
     * @param bool   $allowExternalLink
     *
     * @return void
     */
    public function redirect($url, $time = 3, $message = '', $addredirect = true, $allowExternalLink = false)
    {
        $this->xoops()->redirect($this->url($url), $time, $message, $addredirect, $allowExternalLink);
    }

    /**
     * @param string $language
     *
     * @return string
     */
    public function loadLanguage($language)
    {
        $this->xoops()->loadLanguage($language, $this->_dirname);
        $this->_addLog("Loading language '{$language}'");
    }

    public function loadLocale()
    {
        $this->xoops()->loadLocale($this->_dirname);
        $this->_addLog("Loading locale");
    }

    /**
     * @param null|XoopsObject       $obj
     * @param string                 $name
     *
     * @return \Xoops\Form\Form|boolean
     */
    public function getForm($obj, $name)
    {
        $name = strtolower($name);
        $this->_addLog("Loading form '{$name}'");
        return $this->xoops()->getModuleForm($obj, $name, $this->_dirname);
    }

    /**
     * Initialize module
     */
    private function _initModule()
    {
        if ($this->isCurrentModule()) {
            $this->_module = $this->xoops()->module;
        } else {
            $this->_module = $this->xoops()->getModuleByDirname($this->_dirname);
        }
        if (!$this->_module instanceof \XoopsModule) {
            $this->_module = $this->xoops()->getHandlerModule()->create();
        }
        $this->_addLog('Loading module');
    }

    /**
     * @param string $log
     */
    protected function _addLog($log)
    {
        if ($this->_debug) {
            $this->xoops()->preload()->triggerEvent('core.module.addlog', array(
                $this->getModule()->getVar('name'),
                $log
            ));
        }
    }
}
