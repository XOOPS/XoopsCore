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
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         xcaptcha
 * @since           2.6.0
 * @author          Laurent JEN (Aka DuGris)
 * @version         $Id$
 */
use Xmf\Request;

class Xcaptcha extends XoopsCaptcha
{
    public $captchaHandler;

    public $config = [];

    public $plugin_List = [];

    public $plugin_config = [];

    public $xcaptcha_path_plugin;

    public function __construct()
    {
        $this->captchaHandler = XoopsCaptcha::getInstance();
        $this->config = $this->loadConfig();
        $this->plugin_List = $this->getPluginList();
        $this->plugin_config = $this->loadConfigPlugin();
        $this->xcaptcha_path_plugin = \XoopsBaseConfig::get('root-path') . '/modules/xcaptcha/plugins';
    }

    public static function getInstance()
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

    public function writeConfig($file, $data)
    {
        return $this->captchaHandler->writeConfig($file, $data);
    }

    public function getPluginList()
    {
        $ret = [];

        foreach (glob($this->captchaHandler->path_basic . '/config.*.php') as $filename) {
            $plugin_List = preg_replace('/(config\.)(.*)(\.php)/', '$2', basename($filename));
            $ret[$plugin_List] = $plugin_List;
        }

        return $ret;
    }

    public function loadConfigPlugin()
    {
        $config = [];
        foreach ($this->plugin_List as $key) {
            $config = $this->loadConfig($key);
        }

        return $config;
    }

    public function VerifyData()
    {
        $system = System::getInstance();
        $config = [];
        $_POST['disabled'] = Request::getBool('disabled', false, 'POST');
        $_POST['mode'] = Request::getString('mode', 'image', 'POST');
        $_POST['name'] = Request::getString('name', 'xoopscaptcha', 'POST');
        $_POST['skipmember'] = Request::getBool('skipmember', false, 'POST');
        $_POST['maxattempts'] = Request::getInt('maxattempts', 10, 'POST');
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
