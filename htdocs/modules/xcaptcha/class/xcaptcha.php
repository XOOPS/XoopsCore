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

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Class Xcaptcha
 */
class Xcaptcha extends XoopsCaptcha
{
    public $captchaHandler;

    public $config = array();

    public $plugin_List = array();

    public $plugin_config = array();

    public $xcaptcha_path_plugin;

    /**
     *
     */
    public function __construct()
    {
        $this->captchaHandler = XoopsCaptcha::getInstance();
        $this->config = $this->loadConfig();
        $this->plugin_List = $this->getPluginList();
        $this->plugin_config = $this->loadConfigPlugin();
        $this->xcaptcha_path_plugin = XOOPS_ROOT_PATH . '/modules/xcaptcha/plugins';
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

    /**
     * @param string $name
     * @return array
     */
    public function loadConfig($name = 'config')
    {
        return $this->captchaHandler->loadConfig($name);
    }

    /**
     * @param null $filename
     * @return array
     */
    public function loadBasicConfig($filename = null)
    {
        return $this->captchaHandler->loadBasicConfig($filename);
    }

    /**
     * @param string $file
     * @return array
     */
    public function readConfig($file = 'config')
    {
        return $this->captchaHandler->readConfig($file);
    }

    /**
     * @param string $file
     * @param array $data
     * @return array
     */
    public function writeConfig($file = 'config', $data)
    {
        return $this->captchaHandler->writeConfig($file, $data);
    }

    /**
     * @return array
     */
    public function getPluginList()
    {
        $ret = array();

        foreach (glob($this->captchaHandler->path_basic . '/config.*.php') as $filename) {
            $plugin_List = preg_replace('/(config\.)(.*)(\.php)/', '$2', basename($filename));
            $ret[$plugin_List] = $plugin_List;
        }
        return $ret;
    }

    /**
     * @return array
     */
    public function loadConfigPlugin()
    {
        $config = array();
        foreach ($this->plugin_List as $key) {
            $config = $this->loadConfig($key);
        }
        return $config;
    }

    /**
     * @return array
     */
    public function VerifyData()
    {
        $system = System::getInstance();
        $config = array();
        $_POST['disabled'] = $system->CleanVars($_POST, 'disabled', false, 'boolean');
        $_POST['mode'] = $system->CleanVars($_POST, 'mode', 'image', 'string');
        $_POST['name'] = $system->CleanVars($_POST, 'name', 'xoopscaptcha', 'string');
        $_POST['skipmember'] = $system->CleanVars($_POST, 'skipmember', false, 'boolean');
        $_POST['maxattempts'] = $system->CleanVars($_POST, 'maxattempts', 10, 'int');
        foreach (array_keys($this->config) as $key) {
            $config[$key] = $_POST[$key];
        }
        return $config;
    }

    /**
     * @param null $name
     * @return null
     */
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
