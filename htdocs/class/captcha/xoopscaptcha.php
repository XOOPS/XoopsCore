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
 * CAPTCHA configurations for Image mode
 *
 * Based on DuGris' SecurityImage
 *
 * PHP 5.3
 *
 * @category  Xoops\Class\Captcha\Captcha
 * @package   Captcha
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   $Id$
 * @link      http://xoops.org
 * @since     2.6.0
 */
class XoopsCaptcha
{
    /**
     * @var bool
     */
    public $active;

    /**
     * @var XoopsCaptchaMethod
     */
    public $handler;

    /**
     * @var string
     */
    public $path_basic;

    /**
     * @var string
     */
    public $path_plugin;

    /**
     * @var string
     */
    public $configPath;

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $config = array();

    /**
     * @var array
     */
    public $message = array();

    /**
     * construct
     */
    protected function __construct()
    {
        // Load static configurations
		$xoops_root_path = \XoopsBaseConfig::get('root-path');
		$xoops_var_path = \XoopsBaseConfig::get('var-path');
        $this->path_basic = $xoops_root_path . '/class/captcha';
        $this->path_plugin = $xoops_root_path . '/Frameworks/captcha';
        $this->configPath = $xoops_var_path . '/configs/';
        $this->config = $this->loadConfig();
        $this->name = $this->config['name'];
    }

    /**
     * Get Instance
     *
     * @return XoopsCaptcha
     */
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
     * XoopsCaptcha::loadConfig()
     *
     * @param string $name
     *
     * @return array
     */
    function loadConfig( $name = 'config') {
        if ( $name === 'config' ) {
            $filename = 'captcha.config';
        } else {
            $filename = 'captcha.config.' . $name;
        }
        if ( !$config = $this->readConfig($filename) ) {
            $config = $this->loadBasicConfig( $name );
            $this->writeConfig($filename, $config );
        }

        return $config;
    }

    /**
     * XoopsCaptcha::loadBasicConfig()
     *
     * @param string $filename
     *
     * @return array
     */
    function loadBasicConfig($filename = 'config')
    {
        $basic_config = array();
        $plugin_config = array();
        $filename = ($filename === 'config') ? 'config.php' : 'config.' . $filename . '.php';
        if (XoopsLoad::fileExists($file = $this->path_basic . '/' . $filename)) {
            $basic_config = include $file;
        }
        if (XoopsLoad::fileExists($file = $this->path_plugin . '/' . $filename)) {
            $plugin_config = include $file;
        }

        $config = array_merge($basic_config, $plugin_config);
        return $config;
    }

    /**
     * XoopsCaptcha::readConfig()
     *
     * @param string $filename
     *
     * @return array
     */
    function readConfig($filename = 'config')
    {
        $path_file = $this->configPath . $filename . '.php';
        $file = XoopsFile::getHandler('file', $path_file);
        return eval(@$file->read());
    }

    /**
     * XoopsCaptcha::writeConfig()
     *
     * @param string $filename
     * @param array $config
     *
     * @return array
     */
    function writeConfig($filename = 'config', $config)
    {
        $path_file = $this->configPath . $filename . '.php';
        $file = XoopsFile::getHandler('file', $path_file);
        return $file->write( 'return ' . var_export($config, true) . ';');
    }

    /**
     * XoopsCaptcha::isActive()
     *
     * @return bool
     */
    public function isActive()
    {
        $xoops = Xoops::getInstance();

        if (isset($this->active)) {
            return $this->active;
        }
        if (!empty($this->config['disabled'])) {
            $this->active = false;
            return $this->active;
        }
        if (!empty($this->config['skipmember']) && $xoops->isUser()) {
            $this->active = false;
            return $this->active;
        }
        if (!isset($this->handler)) {
            $this->loadHandler();
        }
        $this->active = isset($this->handler);
        return $this->active;
    }

    /**
     * XoopsCaptcha::loadHandler()
     *
     * @param string $name
     *
     * @return XoopsCaptchaMethod|null
     */
    public function loadHandler($name = null)
    {
        $name = !empty($name) ? $name : (empty($this->config['mode']) ? 'text' : $this->config['mode']);
        $class = 'XoopsCaptcha' . ucfirst($name);
        if (!empty($this->handler) && get_class($this->handler) == $class) {
            return $this->handler;
        }
        $this->handler = null;
        if (XoopsLoad::fileExists($file = $this->path_basic . '/' . $name . '.php')) {
            require_once $file;
        } else {
            if (XoopsLoad::fileExists($file = $this->path_plugin . '/' . $name . '.php')) {
                require_once $file;
            }
        }

        if (!class_exists($class)) {
            $class = 'XoopsCaptchaText';
            require_once $this->path_basic . '/text.php';
        }
        /* @var $handler XoopsCaptchaMethod */
        $handler = new $class($this);
        if ($handler->isActive()) {
            $this->handler = $handler;
            $this->handler->loadConfig($name);
        }
        return $this->handler;
    }

    /**
     * XoopsCaptcha::setConfigs()
     *
     * @param mixed $configs
     *
     * @return bool
     */
    public function setConfigs($configs)
    {
        foreach ($configs as $key => $val) {
            $this->setConfig($key, $val);
        }
        return true;
    }

    /**
     * XoopsCaptcha::setConfig()
     *
     * @param mixed $name
     * @param mixed $val
     *
     * @return bool
     */
    public function setConfig($name, $val)
    {
        if (isset($this->$name)) {
            $this->$name = $val;
        } else {
            $this->config[$name] = $val;
        }
        return true;
    }

    /**
     * XoopsCaptcha::verify()
     *
     * Verify user submission
     *
     * @param bool $skipMember
     * @param string $name
     *
     * @return bool
     */
    public function verify($skipMember = null, $name = null)
    {
        $xoops = Xoops::getInstance();
        $sessionName = empty($name) ? $this->name : $name;
        $skipMember = ($skipMember === null) ? $_SESSION["{$sessionName}_skipmember"] : $skipMember;
        $maxAttempts = $_SESSION["{$sessionName}_maxattempts"];
        $attempt = $_SESSION["{$sessionName}_attempt"];
        $is_valid = false;
        // Skip CAPTCHA verification if disabled
        if (!$this->isActive()) {
            $is_valid = true;
            // Skip CAPTCHA for member if set
        } else {
            if ($xoops->isUser() && !empty($skipMember)) {
                $is_valid = true;
                // Kill too many attempts
            } else {
                if (!empty($maxAttempts) && $attempt > $maxAttempts) {
                    $this->message[] = XoopsLocale::E_TO_MANY_ATTEMPTS;
                    // Verify the code
                } else {
                    $is_valid = $this->handler->verify($sessionName);
                }
            }
        }

        if (!$is_valid) {
            // Increase the attempt records on failure
            $_SESSION["{$sessionName}_attempt"]++;
            // Log the error message
            $this->message[] = XoopsLocale::E_INVALID_CONFIRMATION_CODE;
        } else {
            // reset attempt records on success
            $_SESSION["{$sessionName}_attempt"] = null;
        }
        $this->destroyGarbage(true);
        return $is_valid;
    }

    /**
     * XoopsCaptcha::getCaption()
     *
     * @return string
     */
    public function getCaption()
    {
        return XoopsLocale::CONFIRMATION_CODE;
    }

    /**
     * XoopsCaptcha::getMessage()
     *
     * @return string
     */
    public function getMessage()
    {
        return implode('<br />', $this->message);
    }

    /**
     * Destory historical stuff
     *
     * @param bool $clearSession
     *
     * @return bool
     */
    public function destroyGarbage($clearSession = false)
    {
        $this->loadHandler();
        $this->handler->destroyGarbage();

        if ($clearSession) {
            foreach ($this->config as $k => $config ) {
                $_SESSION[$this->name . '_' . $k] = null;
            }
        }
        return true;
    }

    /**
     * XoopsCaptcha::render()
     *
     * @return string
     */
    public function render()
    {
        $sessionName = $this->config['name'];
        $_SESSION[$sessionName . '_name'] = $sessionName;
        foreach ($this->config as $k => $config ) {
            $_SESSION[$sessionName . '_' . $k] = $config;
        }
        $form = '';
        if (!$this->active || empty($this->config['name'])) {
            return $form;
        }

        $maxAttempts = $this->config['maxattempts'];
        $attempt = isset($_SESSION[$sessionName . '_attempt']) ? $_SESSION[$sessionName . '_attempt'] : 0;
        $_SESSION[$sessionName . '_attempt'] = $attempt;

        // Failure on too many attempts
        if (!empty($maxAttempts) && $attempt > $maxAttempts) {
            $form = XoopsLocale::E_TO_MANY_ATTEMPTS;
            // Load the form element
        } else {
            $form = $this->loadForm();
        }
        return $form;
    }

    /**
     * XoopsCaptcha::renderValidationJS()
     *
     * @return string
     */
    public function renderValidationJS()
    {
        if (!$this->active || empty($this->config['name'])) {
            return '';
        }
        return $this->handler->renderValidationJS();
    }

    /**
     * XoopsCaptcha::setCode()
     *
     * @param mixed $code
     *
     * @return bool
     */
    public function setCode($code = null)
    {
        $code = ($code === null) ? $this->handler->getCode() : $code;
        if (!empty($code)) {
            $_SESSION[$this->name . '_code'] = $code;
            return true;
        }
        return false;
    }

    /**
     * XoopsCaptcha::loadForm()
     *
     * @return string
     */
    public function loadForm()
    {
        $form = $this->handler->render();
        $this->setCode();
        return $form;
    }
}
