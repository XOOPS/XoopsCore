<?php
/**
 * Xcaptcha extension module
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */

class Xcaptcha extends XoopsCaptcha
{
    public $captchaHandler;

    public $config = array();

    public $plugin_List = array();

    public $plugin_config = array();

    public $xcaptcha_path_plugin;

    public function __construct()
    {
        $this->captchaHandler = XoopsCaptcha::getInstance();
        $this->config = $this->loadConfig();
        $this->plugin_List = $this->getPluginList();
        $this->plugin_config = $this->loadConfigPlugin();
        $this->xcaptcha_path_plugin = \XoopsBaseConfig::get('root-path') . '/modules/xcaptcha/plugins';
    }

    static function getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    public function loadConfig($name = 'config')
    {
        return $this->captchaHandler->loadConfig($name);
    }

    public function loadBasicConfig($filename = null)
    {
        return $this->captchaHandler->loadBasicConfig($filename);
    }

    public function readConfig($file = 'config')
    {
        return $this->captchaHandler->readConfig($file);
    }

    public function writeConfig($file = 'config', $data)
    {
        return $this->captchaHandler->writeConfig($file, $data);
    }

    public function getPluginList()
    {
        $ret = array();

        foreach (glob($this->captchaHandler->path_basic . '/config.*.php') as $filename) {
            $plugin_List = preg_replace('/(config\.)(.*)(\.php)/', '$2', basename($filename));
            $ret[$plugin_List] = $plugin_List;
        }
        return $ret;
    }

    public function loadConfigPlugin()
    {
        $config = array();
        foreach ($this->plugin_List as $key) {
            $config = $this->loadConfig($key);
        }
        return $config;
    }

    public function VerifyData()
    {
        $system = System::getInstance();
        $config = array();
        $_POST['disabled'] = $system->cleanVars($_POST, 'disabled', false, 'boolean');
        $_POST['mode'] = $system->cleanVars($_POST, 'mode', 'image', 'string');
        $_POST['name'] = $system->cleanVars($_POST, 'name', 'xoopscaptcha', 'string');
        $_POST['skipmember'] = $system->cleanVars($_POST, 'skipmember', false, 'boolean');
        $_POST['maxattempts'] = $system->cleanVars($_POST, 'maxattempts', 10, 'int');
        foreach (array_keys($this->config) as $key) {
            $config[$key] = $_POST[$key];
        }
        return $config;
    }

    public function loadPluginHandler($name = null)
    {
        $name = empty($name) ? 'text' : $name;
        $class = 'Xcaptcha' . ucfirst($name);
        $this->Pluginhandler = null;
        if (XoopsLoad::fileExists($file = $this->xcaptcha_path_plugin . '/' . $name . '.php')) {
            require_once $file;
            $this->Pluginhandler = new $class($this);
        }
        return $this->Pluginhandler;
    }
}
