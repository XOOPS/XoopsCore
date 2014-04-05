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
 * XOOPS
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @since           2.6.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          formuss
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class Xoops
{
    /**
     * @var null|XoopsSessionHandler
     */
    public $sess_handler = null;

    /**
     * @var null|XoopsModule
     */
    public $module = null;

    /**
     * @var array
     */
    public $config = array();

    /**
     * @var array
     */
    public $moduleConfig = array();

    /**
     * @var array
     */
    public $moduleDirname = '';

    /**
     * @var XoopsUser|string
     */
    public $user = '';

    /**
     * @var bool
     */
    public $userIsAdmin = false;

    /**
     * @var array
     */
    public $option = array();

    /**
     * @var XoopsTpl|null
     */
    private $_tpl = null;

    /**
     * @var XoopsTheme|null
     */
    private $_theme = null;

    /**
     * @var array
     */
    public $paths = array(
        'XOOPS'  => array(), 'www' => array(), 'var' => array(), 'lib' => array(), 'modules' => array(),
        'themes' => array(), 'media' => array()
    );

    /**
     * @var string
     */
    public $tpl_name = '';

    /**
     * @var array
     */
    private $_kernelHandlers = array();

    /**
     * @var array
     */
    private $_moduleHandlers = array();

    /**
     * @var null|array
     */
    private $_activeModules = null;

    /**
     * @var array
     */
    private $_moduleConfigs = array();

    /**
     * @var bool
     */
    public $isAdminSide = false;

    /**
     * @var object
     */
    private $_db;


    /**
     * Actual Xoops OS
     */
    private function __construct()
    {
        $this->paths['XOOPS'] = array(XOOPS_PATH, XOOPS_URL . 'browse.php');
        $this->paths['www'] = array(XOOPS_ROOT_PATH, XOOPS_URL);
        $this->paths['var'] = array(XOOPS_VAR_PATH, null);
        $this->paths['lib'] = array(XOOPS_PATH, XOOPS_URL . 'browse.php');
        $this->paths['modules'] = array(XOOPS_ROOT_PATH . '/modules', XOOPS_URL . '/modules');
        $this->paths['themes'] = array(XOOPS_ROOT_PATH . '/themes', XOOPS_URL . '/themes');
        $this->paths['media'] = array(XOOPS_ROOT_PATH . '/media', XOOPS_URL . '/media');
        $this->paths['assets'] = array(XOOPS_ROOT_PATH . '/assets', XOOPS_URL . '/assets');

        $this->pathTranslation();

        $this->_db = $this->db();
    }

    /**
     * Access the only instance of this class
     *
     * @return Xoops
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
     * get database connection instance
     *
     * @return XoopsConnection
     */
    public function db()
    {
        return XoopsDatabaseFactory::getConnection();
    }

    /**
     * get the system logger instance
     *
     * @return \Xoops\Core\Logger
     */
    public function logger()
    {
        return \Xoops\Core\Logger::getInstance();
    }

    /**
     * get the event processor
     *
     * @return \Xoops\Core\Events instance
     */
    public function events()
    {
        return \Xoops\Core\Events::getInstance();
    }

    /**
     * Deprecated - use events() instead
     *
     * @return XoopsPreload
     */
    public function preload()
    {
        return $this->events();
    }

    /**
     * get the asset utility
     *
     * @return \Xoops\Core\Asset instance
     */
    public function assets()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new \Xoops\Core\Assets;
        }
        return $instance;
    }

    /**
     * get the service manager
     *
     * @return \Xoops\Core\Service\Manager instance
     */
    public function service($service)
    {
        static $instance;
        if (!isset($instance)) {
            $instance = \Xoops\Core\Service\Manager::getInstance();
        }
        return $instance->locate($service);
    }

    /**
     * provide registry instance
     *
     * @return Xoops_Registry
     */
    public function registry()
    {
        return Xoops_Registry::getInstance();
    }

    /**
     * get security instance
     *
     * @return XoopsSecurity
     */
    public function security()
    {
        static $instance;
        if (!isset($instance)) {
            $instance = new XoopsSecurity();
            $instance->checkSuperglobals();
        }
        return $instance;
    }

    /**
     * get current template engine
     *
     * @return null|XoopsTpl
     */
    public function tpl()
    {
        return $this->_tpl;
    }

    /**
     * set curent template engine
     *
     * @param XoopsTpl $tpl
     *
     * @return XoopsTpl
     */
    public function setTpl(XoopsTpl $tpl)
    {
        return $this->_tpl = $tpl;
    }

    /**
     * @param null|string $tpl_name
     *
     * @return null|XoopsTheme
     */
    public function theme($tpl_name = null)
    {
        if (!isset($this->_theme)) {
            if ($tpl_name) {
                $tpl_info = $this->getTplInfo($tpl_name);
                $this->tpl_name = $tpl_info['tpl_name'];
            } else {
                $tpl_name = 'module:system|system_dummy.html';
                $tpl_info = $this->getTplInfo($tpl_name);
                $this->tpl_name = $tpl_info['tpl_name'];
            }
            if (!$this->isAdminSide) {
                $xoopsThemeFactory = null;
                $xoopsThemeFactory = new XoopsThemeFactory();
                $xoopsThemeFactory->allowedThemes = $this->getConfig('theme_set_allowed');
                $xoopsThemeFactory->defaultTheme = $this->getConfig('theme_set');
                $this->setTheme($xoopsThemeFactory->createInstance(array('contentTemplate' => $this->tpl_name)));
            } else {
                $adminThemeFactory = new XoopsAdminThemeFactory();
                $this->setTheme($adminThemeFactory->createInstance(array(
                    'folderName'      => 'default', 'themesPath' => 'modules/system/themes',
                    'contentTemplate' => $this->tpl_name
                )));
                //$this->theme()->loadLocalization('admin');
                list($cssAssets, $jsAssets) = $this->theme()->getLocalizationAssets('admin');
                if (!empty($cssAssets)) {
                    $this->theme()->addBaseStylesheetAssets($cssAssets);
                }
                if (!empty($jsAssets)) {
                    $this->theme()->addBaseScriptAssets($jsAssets);
                }
            }
        } else {
            if ($tpl_name) {
                $tpl_info = $this->getTplInfo($tpl_name);
                $this->tpl_name = $tpl_info['tpl_name'];
                $this->_theme->contentTemplate = $this->tpl_name;
            }
        }
        $GLOBALS['xoTheme'] = $this->_theme;
        return $this->_theme;
    }

    /**
     * set theme
     *
     * @param XoopsTheme $theme theme
     *
     * @return XoopsTheme
     */
    public function setTheme(XoopsTheme $theme)
    {
        return $this->_theme = $theme;
    }

    /**
     * Convert a XOOPS path to a physical one
     *
     * @param string $url
     * @param bool   $virtual
     *
     * @return string
     */
    public function path($url, $virtual = false)
    {
        $url = str_replace('\\', '/', $url);
        $url = str_replace(XOOPS_ROOT_PATH, '', $url);
        $url = ltrim($url, '/');
        $parts = explode('/', $url, 2);
        $root = isset($parts[0]) ? $parts[0] : '';
        $path = isset($parts[1]) ? $parts[1] : '';
        if (!isset($this->paths[$root])) {
            list($root, $path) = array('www', $url);
        }
        if (!$virtual) { // Returns a physical path
            $path = $this->paths[$root][0] . '/' . $path;
            $path = str_replace('/', DIRECTORY_SEPARATOR, $path);
            return $path;
        }
        return !isset($this->paths[$root][1]) ? '' : ($this->paths[$root][1] . '/' . $path);
    }

    /**
     * Convert a XOOPS path to an URL
     *
     * @param string $url
     *
     * @return string
     */
    public function url($url)
    {
        return (false !== strpos($url, '://') ? $url : $this->path($url, true));
    }

    /**
     * Build an URL with the specified request params
     *
     * @param string $url
     * @param array  $params
     *
     * @return string
     */
    public function buildUrl($url, $params = array())
    {
        if ($url == '.') {
            $url = $_SERVER['REQUEST_URI'];
        }
        $split = explode('?', $url);
        if (count($split) > 1) {
            list($url, $query) = $split;
            parse_str($query, $query);
            $params = array_merge($query, $params);
        }
        if (!empty($params)) {
            foreach ($params as $k => $v) {
                $params[$k] = $k . '=' . rawurlencode($v);
            }
            $url .= '?' . implode('&', $params);
        }
        return $url;
    }

    /**
     * @param string $path
     * @param string $error_type
     *
     * @return string|bool
     */
    public function pathExists($path, $error_type)
    {
        if (XoopsLoad::fileExists($path)) {
            return $path;
        } else {
            trigger_error(XoopsLocale::E_FILE_NOT_FOUND, $error_type);
            return false;
        }
    }

    /**
     * @return void
     */
    public function gzipCompression()
    {
        /**
         * Disable gzip compression if PHP is run under CLI mode and needs refactored to work correctly
         */
        if (empty($_SERVER['SERVER_NAME']) || substr(PHP_SAPI, 0, 3) == 'cli') {
            $this->setConfig('gzip_compression', 0);
        }

        if ($this->getConfig('gzip_compression') == 1 && extension_loaded('zlib') && !ini_get('zlib.output_compression')) {
            if (@ini_get('zlib.output_compression_level') < 0) {
                ini_set('zlib.output_compression_level', 6);
            }
            ob_start('ob_gzhandler');
        }
    }

    /**
     * @return void
     */
    public function pathTranslation()
    {
        /**
         * *#@+
         * Host abstraction layer
         */
        if (!isset($_SERVER['PATH_TRANSLATED']) && isset($_SERVER['SCRIPT_FILENAME'])) {
            $_SERVER['PATH_TRANSLATED'] = $_SERVER['SCRIPT_FILENAME']; // For Apache CGI
        } else {
            if (isset($_SERVER['PATH_TRANSLATED']) && !isset($_SERVER['SCRIPT_FILENAME'])) {
                $_SERVER['SCRIPT_FILENAME'] = $_SERVER['PATH_TRANSLATED']; // For IIS/2K now I think :-(
            }
        }
        /**
         * User Mulitbytes
         */
        if (empty($_SERVER['REQUEST_URI'])) { // Not defined by IIS
            // Under some configs, IIS makes SCRIPT_NAME point to php.exe :-(
            if (!($_SERVER['REQUEST_URI'] = @$_SERVER['PHP_SELF'])) {
                $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'];
            }
            if (isset($_SERVER['QUERY_STRING'])) {
                $_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
            }
        }
    }

    /**
     * @return void
     */
    public function themeSelect()
    {
        if (!empty($_POST['xoops_theme_select']) && in_array($_POST['xoops_theme_select'], $this->getConfig('theme_set_allowed'))) {
            $this->setConfig('theme_set', $_POST['xoops_theme_select']);
            $_SESSION['xoopsUserTheme'] = $_POST['xoops_theme_select'];
        } else {
            if (!empty($_SESSION['xoopsUserTheme']) && in_array($_SESSION['xoopsUserTheme'], $this->getConfig('theme_set_allowed'))) {
                $this->setConfig('theme_set', $_SESSION['xoopsUserTheme']);
            }
        }
    }

    /**
     * Gets module, type and file from a tpl name
     * It also returns the correct tpl name in case it is not well formed
     *
     * @param string $tpl_name
     *
     * @return array;
     */
    public function getTplInfo($tpl_name)
    {
        $ret = array();
        $ret['type'] = $this->isAdminSide ? 'admin' : 'module';
        $info = explode(':', $tpl_name);
        if (count($info) == 2) {
            $ret['type'] = $info[0];
            $tpl_name = str_replace($ret['type'] . ':', '', $tpl_name);
        }

        if ($ret['type'] == 'db') {
            //For legacy compatibility
            $ret['type'] = $this->isAdminSide ? 'admin' : 'module';
        }

        $info = explode('|', $tpl_name);
        if (count($info) == 2) {
            $ret['module'] = $info[0];
            $ret['file'] = $info[1];
        } else {
            $ret['module'] = 'system';
            $ret['file'] = $tpl_name;
            if ($this->isModule()) {
                $ret['module'] = $this->module->getVar('dirname', 'n');
            }
        }
        $ret['tpl_name'] = $ret['type'] . ':' . $ret['module'] . '|' . $ret['file'];
        return $ret;
    }

    /**
     * @param string $tpl_name
     *
     * @return bool
     */
    public function header($tpl_name = null)
    {
        static $included = false;
        if ($included) {
            return false;
        }
        $included = true;

        $this->events()->triggerEvent('core.header.start');

        //For legacy
        if (!$tpl_name && isset($this->option['template_main'])) {
            $tpl_name = $this->option['template_main'];
            $this->deprecated('XoopsOption \'template_main\' is deprecated, please use $xoops->header(\'templatename.html\') instead');
        }
        $this->theme($tpl_name);
        $this->tpl()->assign('xoops', $this);

        if ($this->isAdminSide) {
            $this->events()->triggerEvent('system.class.gui.header');
            include_once $this->path('modules/system/themes/default/default.php');
            $gui = new XoopsGuiDefault();
            $gui->header();
        } else {
            $this->events()->triggerEvent('core.header.addmeta');
            // Temporary solution for start page redirection
            if (defined("XOOPS_STARTPAGE_REDIRECTED")) {
                $smarty = $repeat = null;
                $this->theme()
                        ->headContent(null, "<base href='" . XOOPS_URL . '/modules/' . $this->getConfig('startpage') . "/' />", $smarty, $repeat);
            }

            if (@is_object($this->theme()->plugins['XoopsThemeBlocksPlugin'])) {
                $aggreg = $this->theme()->plugins['XoopsThemeBlocksPlugin'];
                // Backward compatibility code for pre 2.0.14 themes
                $this->tpl()->assign_by_ref('xoops_lblocks', $aggreg->blocks['canvas_left']);
                $this->tpl()->assign_by_ref('xoops_rblocks', $aggreg->blocks['canvas_right']);
                $this->tpl()->assign_by_ref('xoops_ccblocks', $aggreg->blocks['page_topcenter']);
                $this->tpl()->assign_by_ref('xoops_clblocks', $aggreg->blocks['page_topleft']);
                $this->tpl()->assign_by_ref('xoops_crblocks', $aggreg->blocks['page_topright']);
                $this->tpl()->assign('xoops_showlblock', !empty($aggreg->blocks['canvas_left']));
                $this->tpl()->assign('xoops_showrblock', !empty($aggreg->blocks['canvas_right']));
                $this->tpl()
                        ->assign('xoops_showcblock', !empty($aggreg->blocks['page_topcenter']) || !empty($aggreg->blocks['page_topleft']) || !empty($aggreg->blocks['page_topright']));
            }

            // Sets cache time
            if ($this->isModule()) {
                $cache_times = $this->getConfig('module_cache');
                $this->theme()->contentCacheLifetime = isset($cache_times[$this->module->getVar('mid')]) ? $cache_times[$this->module->getVar('mid')] : 0;
                // Tricky solution for setting cache time for homepage
            } else {
                if ($this->tpl_name == 'module:system|system_homepage.html') {
                    // $this->theme->contentCacheLifetime = 604800;
                }
            }
            $this->events()->triggerEvent('core.header.checkcache');
            if ($this->theme()->checkCache()) {
                exit();
            }
        }

        if (!isset($this->tpl_name) && $this->isModule()) {
            ob_start();
        }

        $this->events()->triggerEvent('core.header.end');
        return true;
    }

    /**
     * @return bool
     */
    public function footer()
    {
        static $included = false;
        if ($included) {
            return false;
        }
        $included = true;

        $this->events()->triggerEvent('core.footer.start');

        if (!headers_sent()) {
            header('Content-Type:text/html; charset=' . XoopsLocale::getCharset());
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Cache-Control: private, no-cache');
            header('Pragma: no-cache');
        }

        if (isset($this->option['template_main']) && $this->option['template_main'] != $this->theme()->contentTemplate) {
            trigger_error("xoopsOption[template_main] should be defined before including header.php", E_USER_WARNING);
            $this->theme()->contentTemplate = $this->tpl_name;
        }
        $this->theme()->render();
        $this->events()->triggerEvent('core.footer.end');
        exit();
    }

    /**
     * @return bool
     */
    public function isModule()
    {
        return $this->module instanceof XoopsModule ? true : false;
    }

    /**
     * @return bool
     */
    public function isUser()
    {
        return $this->user instanceof XoopsUser ? true : false;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->userIsAdmin;
    }

    /**
     * @return Xoops_Request_Http
     */
    public function request()
    {
        return Xoops_Request::getInstance();
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsBlockHandler
     */
    public function getHandlerBlock($optional = false)
    {
        return $this->getHandler('block', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsBlockmodulelinkHandler
     */
    public function getHandlerBlockmodulelink($optional = false)
    {
        return $this->getHandler('blockmodulelink', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsCachemodelHandler
     */
    public function getHandlerCachemodel($optional = false)
    {
        return $this->getHandler('cachemodel', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsConfigHandler
     */
    public function getHandlerConfig($optional = false)
    {
        return $this->getHandler('config', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsConfigitemHandler
     */
    public function getHandlerConfigitem($optional = false)
    {
        return $this->getHandler('configitem', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsConfigoptionHandler
     */
    public function getHandlerConfigoption($optional = false)
    {
        return $this->getHandler('configoption', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsGroupHandler
     */
    public function getHandlerGroup($optional = false)
    {
        return $this->getHandler('group', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsGrouppermHandler
     */
    public function getHandlerGroupperm($optional = false)
    {
        return $this->getHandler('groupperm', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsMemberHandler
     */
    public function getHandlerMember($optional = false)
    {
        return $this->getHandler('member', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsMembershipHandler
     */
    public function getHandlerMembership($optional = false)
    {
        return $this->getHandler('membership', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsModuleHandler
     */
    public function getHandlerModule($optional = false)
    {
        return $this->getHandler('module', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsOnlineHandler
     */
    public function getHandlerOnline($optional = false)
    {
        return $this->getHandler('online', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsPrivmessageHandler
     */
    public function getHandlerPrivmessage($optional = false)
    {
        return $this->getHandler('privmessage', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsRanksHandler
     */
    public function getHandlerRanks($optional = false)
    {
        return $this->getHandler('ranks', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsSessionHandler
     */
    public function getHandlerSession($optional = false)
    {
        return $this->getHandler('session', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsTplfileHandler
     */
    public function getHandlerTplfile($optional = false)
    {
        return $this->getHandler('tplfile', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsTplsetHandler
     */
    public function getHandlerTplset($optional = false)
    {
        return $this->getHandler('tplset', $optional);
    }

    /**
     * @param mixed $optional
     *
     * @return XoopsUserHandler
     */
    public function getHandlerUser($optional = false)
    {
        return $this->getHandler('user', $optional);
    }

    /**
     * @param mixed $name
     * @param mixed $optional
     *
     * @return XoopsObjectHandler|XoopsPersistableObjectHandler|null
     */
    public function getHandler($name, $optional = false)
    {
        $name = strtolower(trim($name));
        $class = '';
        if (!isset($this->_kernelHandlers[$name])) {
            $class = 'Xoops' . ucfirst($name) . 'Handler';
            if (class_exists($class)) {
                $this->_kernelHandlers[$name] = new $class($this->_db);
            }
        }
        if (!isset($this->_kernelHandlers[$name])) {
            trigger_error('Class <strong>' . $class . '</strong> does not exist<br />Handler Name: ' . $name, $optional ? E_USER_WARNING : E_USER_ERROR);
        } else {
            return $this->_kernelHandlers[$name];
        }
        return false;
    }

    /**
     * @param string|null $name
     * @param string|null $module_dir
     * @param bool        $optional
     *
     * @return XoopsObjectHandler|XoopsPersistableObjectHandler|bool
     */
    public function getModuleHandler($name = null, $module_dir = null, $optional = false)
    {
        // if $module_dir is not specified
        if (!isset($module_dir)) {
            // if a module is loaded
            if ($this->module instanceof XoopsModule) {
                $module_dir = $this->module->getVar('dirname', 'n');
            } else {
                trigger_error('No Module is loaded', E_USER_ERROR);
            }
        } else {
            $module_dir = trim($module_dir);
        }
        $name = (!isset($name)) ? $module_dir : trim($name);
        if (!isset($this->_moduleHandlers[$module_dir][$name])) {
            if (XoopsLoad::fileExists($hnd_file = XOOPS_ROOT_PATH . "/modules/{$module_dir}/class/{$name}.php")) {
                include_once $hnd_file;
            }
            $class = ucfirst(strtolower($module_dir)) . ucfirst($name) . 'Handler';
            if (class_exists($class)) {
                $this->_moduleHandlers[$module_dir][$name] = new $class($this->_db);
            }
        }
        if (!isset($this->_moduleHandlers[$module_dir][$name])) {
            trigger_error('Handler does not exist<br />Module: ' . $module_dir . '<br />Name: ' . $name, $optional ? E_USER_WARNING : E_USER_ERROR);
        }
        if (isset($this->_moduleHandlers[$module_dir][$name])) {
            return $this->_moduleHandlers[$module_dir][$name];
        }
        return false;
    }

    /**
     * @param XoopsObject $obj
     * @param string      $name
     * @param string      $module_dir
     *
     * @return XoopsForm|bool
     */
    public function getModuleForm($obj, $name, $module_dir = null)
    {
        if (empty($name)) {
            return false;
        }
        if (empty($module_dir)) {
            if ($this->isModule()) {
                $module_dir = $this->module->getVar('dirname', 'n');
            } else {
                return false;
            }
        }
        if (XoopsLoad::fileExists($hnd_file = XOOPS_ROOT_PATH . "/modules/{$module_dir}/class/form/{$name}.php")) {
            include_once $hnd_file;
            $class = ucfirst(strtolower($module_dir)) . ucfirst($name) . 'Form';
            if (class_exists($class)) {
                $instance = new $class($obj);
                if ($instance instanceof XoopsForm) {
                    return $instance;
                }
            }
        }
        return false;
    }

    /**
     * @param string $dirname
     *
     * @return bool|Xoops_Module_Helper_Abstract
     */
    public function getModuleHelper($dirname)
    {
        return Xoops_Module_Helper::getHelper($dirname);
    }

    /**
     * XOOPS language loader wrapper
     * Temporary solution, not encouraged to use
     *
     * @param   string   $name       Name of language file to be loaded, without extension
     * @param   mixed    $domain     string: Module dirname; global language file will be loaded if $domain is set to 'global' or not specified
     *                               array:  example; array('Frameworks/moduleclasses/moduleadmin')
     * @param   string   $language   Language to be loaded, current language content will be loaded if not specified
     *
     * @return  boolean
     */
    public function loadLanguage($name, $domain = '', $language = null)
    {
        if (empty($name)) {
            return false;
        }

        $language = empty($language) ? XoopsLocale::getLegacyLanguage() : $language;
        // expanded domain to multiple categories, e.g. module:system, framework:filter, etc.
        if ((empty($domain) || 'global' == $domain)) {
            $path = '';
        } else {
            $path = (is_array($domain)) ? array_shift($domain) . '/' : "modules/{$domain}/";
        }
        $path .= 'language';

        if (!XoopsLoad::fileExists($file = $this->path("{$path}/{$language}/{$name}.php"))) {
            if (!XoopsLoad::fileExists($file = $this->path("{$path}/english/{$name}.php"))) {
                return false;
            }
        }
        $ret = include_once $file;
        return $ret;
    }

    /**
     * loadLocale
     *
     * @param string $domain Module dirname; global language file will be loaded if set to 'global' or not specified
     * @param string $locale Locale to be loaded, current language content will be loaded if not specified
     *
     * @return  boolean
     */
    public static function loadLocale($domain = 'xoops', $locale = null)
    {
        return Xoops_Locale::loadLocale($domain, $locale);
    }

    /**
     * @param string $key
     * @param string $dirname
     *
     * @return string
     */
    public function translate($key, $dirname = 'xoops')
    {
        return Xoops_Locale::translate($key, $dirname);
    }

    /**
     * Get active modules from cache file
     *
     * @return array
     */
    public function getActiveModules()
    {
        if (is_array($this->_activeModules)) {
            return $this->_activeModules;
        }

        try {
            if (!$this->_activeModules = \Xoops_Cache::read('system_modules_active')) {
                $this->_activeModules = $this->setActiveModules();
            }
        } catch (\Exception $e) {
            $this->_activeModules = array();
        }
        return $this->_activeModules;
    }

    /**
     * Write active modules to cache file
     *
     * @return array
     */
    public function setActiveModules()
    {
        $module_handler = Xoops::getInstance()->getHandlerModule();
        $modules_array = $module_handler->getAll(new Criteria('isactive', 1), array('dirname'), false, false);
        $modules_active = array();
        foreach ($modules_array as $module) {
            $modules_active[$module['mid']] = $module['dirname'];
        }
        \Xoops_Cache::write('system_modules_active', $modules_active);
        return $modules_active;
    }

    /**
     * Checks is module is installed and active
     *
     * @param string $dirname module directory
     *
     * @return bool
     */
    public function isActiveModule($dirname)
    {
        if (isset($dirname) && in_array($dirname, $this->getActiveModules())) {
            return true;
        }
        return false;
    }

    /**
     * @param string $dirname dirname of the module
     *
     * @return bool|XoopsModule
     */
    public function getModuleByDirname($dirname)
    {
        $key = "module_dirname_{$dirname}";
        if (!$module = Xoops_Cache::read($key)) {
            $module = $this->getHandlerModule()->getByDirname($dirname);
            Xoops_Cache::write($key, serialize($module));
            return $module;
        }
        return unserialize($module);
    }

    /**
     * @param int $id Id of the module
     *
     * @return bool|XoopsModule
     */
    public function getModuleById($id)
    {
        $key = "module_id_{$id}";
        if (!$module = Xoops_Cache::read($key)) {
            $module = $this->getHandlerModule()->getById($id);
            Xoops_Cache::write($key, serialize($module));
            return $module;
        }
        return unserialize($module);
    }

    /**
     * @param bool $closehead
     *
     * @return void
     */
    public function simpleHeader($closehead = true)
    {
        $this->events()->triggerEvent('core.header.start');
        $this->theme();
        $xoopsConfigMetaFooter = $this->getConfigs();

        if (!headers_sent()) {
            header('Content-Type:text/html; charset=' . XoopsLocale::getCharset());
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
            header('Cache-Control: no-store, no-cache, max-age=1, s-maxage=1, must-revalidate, post-check=0, pre-check=0');
            header("Pragma: no-cache");
        }

        echo "<!DOCTYPE html>\n";
        echo '<html lang="' . XoopsLocale::getLangCode() . '">
              <head>
              <meta http-equiv="content-type" content="text/html; charset=' . XoopsLocale::getCharset() . '" />
              <meta name="robots" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_robots']) . '" />
              <meta name="keywords" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_keywords']) . '" />
              <meta name="description" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_description']) . '" />
              <meta name="rating" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_rating']) . '" />
              <meta name="author" content="' . htmlspecialchars($xoopsConfigMetaFooter['meta_author']) . '" />
              <meta name="generator" content="XOOPS" />
              <title>' . htmlspecialchars($this->getConfig('sitename')) . '</title>
              <script type="text/javascript" src="' . XOOPS_URL . '/include/xoops.js"></script>
              <script type="text/javascript" src="' . XOOPS_URL . '/media/jquery/jquery.js"></script>
              <script type="text/javascript" src="' . XOOPS_URL . '/media/bootstrap/js/bootstrap.min.js"></script>';
        $themecss = $this->getCss($this->getConfig('theme_set'));
        echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/xoops.css" />';
        $locale = $this->getConfig('locale');
        if (XoopsLoad::fileExists($this->path('locale/' . $locale . '/style.css'))) {
            echo '<link rel="stylesheet" type="text/css" media="all" href="' . XOOPS_URL . '/locale/' . $locale . '/style.css" />';
        }
        if ($themecss) {
            echo '<link rel="stylesheet" type="text/css" media="all" href="' . $themecss . '" />';
            //echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $this->url('themes/' . $this->getConfig('theme_set') . '/media/bootstrap/css/bootstrap.css') .'" />';
            echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $this->url('themes/' . $this->getConfig('theme_set') . '/media/bootstrap/css/xoops.bootstrap.css') .'" />';
        }
        if ($closehead) {
            echo '</head><body>';
        }
    }

    /**
     * @return void
     */
    public function simpleFooter()
    {
        $this->events()->triggerEvent('core.header.footer');
        echo '</body></html>';
        ob_end_flush();
    }
    /**
     * @param string $type (info, error, success or warning)
     * @param mixed  $msg - string or array of strings
     * @param string $title
     *
     * @return string
     */
    public function alert($type, $msg, $title = '/')
    {
        $alert_msg = '';
        switch ($type) {
            case 'info':
            default:
                $this->tpl()->assign('alert_type', 'alert-info');
                if($title == '/'){
                    $title = XoopsLocale::INFORMATION;
                }
                break;

            case 'error':
                $this->tpl()->assign('alert_type', 'alert-error');
                if($title == '/'){
                    $title = XoopsLocale::ERROR;
                }
                break;

            case 'success':
                $this->tpl()->assign('alert_type', 'alert-success');
                if($title == '/'){
                    $title = XoopsLocale::SUCCESS;
                }
                break;

            case 'warning':
                $this->tpl()->assign('alert_type', '');
                if($title == '/'){
                    $title = XoopsLocale::WARNING;
                }
                break;
        }

        if ($title != '') {
            $this->tpl()->assign('alert_title', $title);
        }
        if (!is_scalar($msg) && !is_array($msg)) {
            $msg = ''; // don't know what to do with this, so make it blank
        }
        if (is_array($msg)) {
            // if this is not a simple array of strings, this might not work
            $alert_msg = @implode("<br />", $msg);
        } else {
            $alert_msg = $msg;
        }
        if ($alert_msg == '' ){
            return '';
        } else {
            $this->tpl()->assign('alert_msg', $alert_msg);
            $ret = $this->tpl()->fetch('module:system|system_alert.html');
            return $ret;
        }
    }

    /**
     * @param mixed  $msg
     * @param string $title
     *
     * @return void
     */
    public function error($msg, $title = '')
    {
        $this->deprecated(__CLASS__ . "->" . __FUNCTION__ . "() is deprecated since 2.6.0. Please use " . __CLASS__ . "->alert()");
        echo $this->alert('error', $msg, $title);
    }

    /**
     * @param mixed  $msg
     * @param string $title
     *
     * @return void
     */
    public function result($msg, $title = '')
    {
        $this->deprecated(__CLASS__ . "->" . __FUNCTION__ . "() is deprecated since 2.6.0. Please use " . __CLASS__ . "->alert()");
        echo $this->alert('info', $msg, $title);
    }

    /**
     * @param mixed  $hiddens
     * @param mixed  $action
     * @param mixed  $msg
     * @param string $submit
     * @param bool   $addtoken
     *
     * @return void
     */
    public function confirm($hiddens, $action, $msg, $submit = '', $addtoken = true)
    {
        $submit = ($submit != '') ? trim($submit) : XoopsLocale::A_SUBMIT;
        $this->tpl()->assign('msg', $msg);
        $this->tpl()->assign('action', $action);
        $this->tpl()->assign('submit', $submit);
        $str_hiddens = '';
        foreach ($hiddens as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $caption => $newvalue) {
                    $str_hiddens .= '<input type="radio" name="' . $name . '" value="' . htmlspecialchars($newvalue) . '" > ' . $caption . NWLINE;
                }
                $str_hiddens .= '<br />' . NWLINE;
            } else {
                $str_hiddens .= '<input type="hidden" name="' . $name . '" value="' . htmlspecialchars($value) . '" />' . NWLINE;
            }
        }
        if ($addtoken != false) {
            $this->tpl()->assign('token', $this->security()->getTokenHTML());
        }
        $this->tpl()->assign('hiddens', $str_hiddens);
        $this->tpl()->display('module:system|system_confirm.html');
    }

    /**
     * @param mixed  $time
     * @param string $timeoffset
     *
     * @return int
     */
    public function getUserTimestamp($time, $timeoffset = '')
    {
        if ($timeoffset == '') {
            if ($this->isUser()) {
                $timeoffset = $this->user->getVar('timezone_offset');
            } else {
                $timeoffset = $this->getConfig('default_TZ');
            }
        }
        $usertimestamp = intval($time) + (floatval($timeoffset) - $this->getConfig('server_TZ')) * 3600;
        return (int)$usertimestamp;
    }

    /**
     * Function to calculate server timestamp from user entered time (timestamp)
     *
     * @param int  $timestamp
     * @param null $userTZ
     *
     * @return int
     */
    public function userTimeToServerTime($timestamp, $userTZ = null)
    {
        if (!isset($userTZ)) {
            $userTZ = $this->getConfig('default_TZ');
        }
        $timestamp = $timestamp - (($userTZ - $this->getConfig('server_TZ')) * 3600);
        return (int)$timestamp;
    }

    /**
     * @return string
     */
    public function makePass()
    {
        $makepass = '';
        $syllables = array(
            'er', 'in', 'tia', 'wol', 'fe', 'pre', 'vet', 'jo', 'nes', 'al', 'len', 'son', 'cha', 'ir', 'ler', 'bo',
            'ok', 'tio', 'nar', 'sim', 'ple', 'bla', 'ten', 'toe', 'cho', 'co', 'lat', 'spe', 'ak', 'er', 'po', 'co',
            'lor', 'pen', 'cil', 'li', 'ght', 'wh', 'at', 'the', 'he', 'ck', 'is', 'mam', 'bo', 'no', 'fi', 've', 'any',
            'way', 'pol', 'iti', 'cs', 'ra', 'dio', 'sou', 'rce', 'sea', 'rch', 'pa', 'per', 'com', 'bo', 'sp', 'eak',
            'st', 'fi', 'rst', 'gr', 'oup', 'boy', 'ea', 'gle', 'tr', 'ail', 'bi', 'ble', 'brb', 'pri', 'dee', 'kay',
            'en', 'be', 'se'
        );
        for ($count = 1; $count <= 4; $count++) {
            if (1 == rand() % 10) {
                $makepass .= sprintf('%0.0f', (rand() % 50) + 1);
            } else {
                $makepass .= sprintf('%s', $syllables[rand() % 62]);
            }
        }
        return $makepass;
    }

    /**
     * @param string $email
     * @param bool   $antispam
     *
     * @return bool|mixed
     */
    public function checkEmail($email, $antispam = false)
    {
        if (!$email || !preg_match('/^[^@]{1,64}@[^@]{1,255}$/', $email)) {
            return false;
        }
        $email_array = explode("@", $email);
        $local_array = explode(".", $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])
            ) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $email_array[1])) {
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        if ($antispam) {
            $email = str_replace("@", " at ", $email);
            $email = str_replace(".", " dot ", $email);
        }
        return $email;
    }

    /**
     * formatURL - add default http:// if no valid protocol specified
     *
     * @param string $url full or partial url
     *
     * @return string
     */
    public function formatURL($url)
    {
        $url = trim($url);
        if ($url != '') {
            if (!preg_match('/^(https?|ftps?|ed2k)\:\/\//i', $url)) {
                $url = 'http://' . $url;
            }
        }
        return $url;
    }

    /**
     * Function to get banner html tags for use in templates
     *
     * @return string
     */
    public function getBanner()
    {
        $options = '';
        $this->events()->triggerEvent('core.banner.display', array(&$options));
        return $options;
    }

    /**
     * Function to redirect a user to certain pages
     *
     * @param string $url
     * @param int    $time
     * @param string $message
     * @param bool   $addredirect
     * @param bool   $allowExternalLink
     *
     * @return void
     */
    public function redirect($url, $time = 3, $message = '', $addredirect = true, $allowExternalLink = false)
    {
        $this->events()->triggerEvent('core.include.functions.redirectheader.start', array(
            $url, $time, $message, $addredirect, $allowExternalLink
        ));
        // if conditions are right, system preloads will exit on this call
        // so don't use it if you want to be called, use start version above.
        $this->events()->triggerEvent('core.include.functions.redirectheader', array(
            $url, $time, $message, $addredirect, $allowExternalLink
        ));

        if (preg_match("/[\\0-\\31]|about:|script:/i", $url)) {
            if (!preg_match('/^\b(java)?script:([\s]*)history\.go\(-[0-9]*\)([\s]*[;]*[\s]*)$/si', $url)) {
                $url = XOOPS_URL;
            }
        }
        if (!$allowExternalLink && $pos = strpos($url, '://')) {
            $xoopsLocation = substr(XOOPS_URL, strpos(XOOPS_URL, '://') + 3);
            if (strcasecmp(substr($url, $pos + 3, strlen($xoopsLocation)), $xoopsLocation)) {
                $url = XOOPS_URL;
            }
        }
        if (defined('XOOPS_CPFUNC_LOADED')) {
            $theme = 'default';
        } else {
            $theme = $this->getConfig('theme_set');
        }

        $xoopsThemeFactory = null;
        $xoopsThemeFactory = new XoopsThemeFactory();
        $xoopsThemeFactory->allowedThemes = $this->getConfig('theme_set_allowed');
        $xoopsThemeFactory->defaultTheme = $theme;
        $this->setTheme($xoopsThemeFactory->createInstance(array(
            "plugins" => array(), "renderBanner" => false
        )));
        $this->setTpl($this->theme()->template);
        $this->tpl()->assign(array(
            'xoops_theme'      => $theme, 'xoops_imageurl' => XOOPS_THEME_URL . '/' . $theme . '/',
            'xoops_themecss'   => $this->getCss($theme),
            'xoops_requesturi' => htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES),
            'xoops_sitename'   => htmlspecialchars($this->getConfig('sitename'), ENT_QUOTES),
            'xoops_slogan'     => htmlspecialchars($this->getConfig('slogan'), ENT_QUOTES),
            'xoops_dirname'    => $this->isModule() ? $this->module->getVar('dirname') : 'system',
            'xoops_pagetitle'  => $this->isModule() ? $this->module->getVar('name') : htmlspecialchars($this->getConfig('slogan'), ENT_QUOTES)
        ));

        $this->tpl()->assign('time', intval($time));
        if (!empty($_SERVER['REQUEST_URI']) && $addredirect && strstr($url, 'user.php')) {
            if (!strstr($url, '?')) {
                $url .= '?xoops_redirect=' . urlencode($_SERVER['REQUEST_URI']);
            } else {
                $url .= '&amp;xoops_redirect=' . urlencode($_SERVER['REQUEST_URI']);
            }
        }
        if (defined('SID') && SID && (!isset($_COOKIE[session_name()]) || ($this->getConfig('use_mysession') && $this->getConfig('session_name') != '' && !isset($_COOKIE[$this->getConfig('session_name')])))
        ) {
            if (!strstr($url, '?')) {
                $url .= '?' . SID;
            } else {
                $url .= '&amp;' . SID;
            }
        }
        $url = preg_replace("/&amp;/i", '&', htmlspecialchars($url, ENT_QUOTES));
        $this->tpl()->assign('url', $url);
        $message = trim($message) != '' ? $message : XoopsLocale::E_TAKING_YOU_BACK;
        $this->tpl()->assign('message', $message);
        $this->tpl()->assign('lang_ifnotreload', sprintf(XoopsLocale::F_IF_PAGE_NOT_RELOAD_CLICK_HERE, $url));

        $this->events()->triggerEvent('core.include.functions.redirectheader.end');
        $this->tpl()->display('module:system|system_redirect.html');
        exit();
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getEnv($key)
    {
        $ret = '';
        if (array_key_exists($key, $_SERVER) && isset($_SERVER[$key])) {
            $ret = $_SERVER[$key];
            return $ret;
        }
        if (array_key_exists($key, $_ENV) && isset($_ENV[$key])) {
            $ret = $_ENV[$key];
            return $ret;
        }
        return $ret;
    }

    /**
     * Function to get css file for a certain themeset
     *
     * @param string $theme
     *
     * @return string
     */
    public function getCss($theme = '')
    {
        if ($theme == '') {
            $theme = $this->getConfig('theme_set');
        }
        $uagent = $this->getEnv('HTTP_USER_AGENT');
        if (stristr($uagent, 'mac')) {
            $str_css = 'styleMAC.css';
        } elseif (preg_match("/MSIE ([0-9]\.[0-9]{1,2})/i", $uagent)) {
            $str_css = 'style.css';
        } else {
            $str_css = 'styleNN.css';
        }
        if (is_dir(XOOPS_THEME_PATH . '/' . $theme)) {
            if (XoopsLoad::fileExists(XOOPS_THEME_PATH . '/' . $theme . '/' . $str_css)) {
                return XOOPS_THEME_URL . '/' . $theme . '/' . $str_css;
            } elseif (XoopsLoad::fileExists(XOOPS_THEME_PATH . '/' . $theme . '/style.css')) {
                return XOOPS_THEME_URL . '/' . $theme . '/style.css';
            }
        }
        if (is_dir(XOOPS_THEME_PATH . '/' . $theme . '/css')) {
            if (XoopsLoad::fileExists(XOOPS_THEME_PATH . '/' . $theme . '/css/' . $str_css)) {
                return XOOPS_THEME_URL . '/' . $theme . '/css/' . $str_css;
            } elseif (XoopsLoad::fileExists(XOOPS_THEME_PATH . '/' . $theme . '/css/style.css')) {
                return XOOPS_THEME_URL . '/' . $theme . '/css/style.css';
            }
        }
        return '';
    }

    /**
     * @return XoopsMailer|XoopsMailerLocale
     */
    public function getMailer()
    {
        static $mailer;
        if (is_object($mailer)) {
            return $mailer;
        }
        Xoops_Locale::loadMailerLocale();
        if (class_exists('XoopsMailerLocale')) {
            $mailer = new XoopsMailerLocale();
        } else {
            $mailer = new XoopsMailer();
        }
        return $mailer;
    }

    /**
     * getRank - retrieve user rank
     *
     * @param integer $rank_id specified rank for user
     * @param int     $posts   number of posts for user
     *
     * @return array
     */
    public function getRank($rank_id = 0, $posts = 0)
    {
        $myts = MyTextSanitizer::getInstance();
        $rank_id = intval($rank_id);
        $posts = intval($posts);

        $sql = $this->_db->createXoopsQueryBuilder()
            ->select('r.rank_title AS title')
            ->addSelect('r.rank_image AS image')
            ->fromPrefix('ranks', 'r');
        $eb = $sql->expr();
        if ($rank_id != 0) {
            $sql->where($eb->eq('r.rank_id', ':rank'))
                ->setParameter(':rank', $rank_id, \PDO::PARAM_INT);
        } else {
            $sql->where($eb->lte('r.rank_min', ':posts'))
                ->andWhere($eb->gte('r.rank_max', ':posts'))
                ->andWhere($eb->eq('r.rank_special', 0))
                ->setParameter(':posts', $posts, \PDO::PARAM_INT);
        }

        $rank = $this->_db->fetchAssoc($sql->getSql(), $sql->getParameters());

        $rank['title'] = $myts->htmlspecialchars($rank['title']);
        $rank['id'] = $rank_id;
        return $rank;

    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getOption($key)
    {
        $ret = '';
        if (isset($this->option[$key])) {
            $ret = $this->option[$key];
        }
        return $ret;
    }

    /**
     * @param string $key
     * @param null   $value
     *
     * @return void
     */
    public function setOption($key, $value = null)
    {
        if (!is_null($value)) {
            $this->option[$key] = $value;
        }
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getConfig($key)
    {
        return $this->getModuleConfig($key, 'system');
    }

    /**
     * @return array
     */
    public function getConfigs()
    {
        return $this->getModuleConfigs('system');
    }

    /**
     * @param $configs array
     * @param $dirname string
     *
     * @return void
     */
    public function addConfigs($configs, $dirname = 'system')
    {
        $dirname = trim(strtolower($dirname));
        if (empty($dirname)) {
            $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
        }
        if (!empty($dirname)) {
            $this->_moduleConfigs[$dirname] = array_merge($this->_moduleConfigs[$dirname], (array)$configs);
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param string $dirname
     *
     * @return void
     */
    public function setConfig($key, $value = null, $dirname = 'system')
    {
        if (!is_null($value)) {
            $dirname = trim(strtolower($dirname));
            if (empty($dirname)) {
                $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
            }
            $this->_moduleConfigs[$dirname][$key] =& $value;
        }
    }

    /**
     * @param string $key
     * @param array  $values
     * @param bool   $appendWithKey
     * @param string $dirname
     *
     * @return void
     */
    public function appendConfig($key, array $values, $appendWithKey = false, $dirname = 'system')
    {
        $dirname = trim(strtolower($dirname));
        if (empty($dirname)) {
            $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
        }
        if ($appendWithKey) {
            foreach ($values as $key2 => $value) {
                if (!is_array($this->_moduleConfigs[$dirname][$key])) {
                    $this->_moduleConfigs[$dirname][$key] = array();
                }
                $this->_moduleConfigs[$dirname][$key][$key2] =& $value;
            }
        } else {
            $this->_moduleConfigs[$dirname][$key][] =& $values;
        }
    }

    /**
     * @param string $key
     * @param string $dirname
     *
     * @return mixed
     */
    public function getModuleConfig($key, $dirname = '')
    {
        $dirname = trim(strtolower($dirname));
        if (empty($dirname)) {
            $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
        }

        if (isset($this->_moduleConfigs[$dirname][$key])) {
            return $this->_moduleConfigs[$dirname][$key];
        }

        $this->getModuleConfigs($dirname);

        if (!isset($this->_moduleConfigs[$dirname][$key])) {
            $this->_moduleConfigs[$dirname][$key] = '';
        }
        return $this->_moduleConfigs[$dirname][$key];
    }

    /**
     * @param string $dirname
     *
     * @return
     */
    public function getModuleConfigs($dirname = '')
    {
        $dirname = trim($dirname);
        if (empty($dirname)) {
            $dirname = $this->isModule() ? $this->module->getVar('dirname') : 'system';
        }
        if (isset($this->_moduleConfigs[$dirname])) {
            return $this->_moduleConfigs[$dirname];
        }
        $this->_moduleConfigs[$dirname] = array();

        if (!$configs = Xoops_Cache::read("{$dirname}_configs")) {
            $module = $this->getModuleByDirname($dirname);
            if (is_object($module)) {
                $configs = $this->getHandlerConfig()->getConfigsByModule($module->getVar('mid'));
                Xoops_Cache::write("{$dirname}_configs", $configs);
                $this->_moduleConfigs[$dirname] =& $configs;
            }
        } else {
            $this->_moduleConfigs[$dirname] =& $configs;
        }

        if ($this->isModule()) {
            //for legacy
            $this->moduleConfig =& $this->_moduleConfigs[$this->module->getVar('dirname')];
        }
        if ($dirname == 'system') {
            $this->config =& $this->_moduleConfigs['system'];
        }
        return $this->_moduleConfigs[$dirname];
    }

    /**
     * Disables page cache by overriding module cache settings
     *
     * @return void
     */
    public function disableModuleCache()
    {
        if ($this->isModule()) {
            $this->appendConfig('module_cache', array($this->module->getVar('mid') => 0), true);
        }
    }

    /**
     * getBaseDomain
     *
     * @param string $url
     * @param int    $debug
     *
     * @return string
     * @deprecated
     */
    public function getBaseDomain($url, $debug = 0)
    {
        $base_domain = '';
        $url = strtolower($url);

        // generic tlds (source: http://en.wikipedia.org/wiki/Generic_top-level_domain)
        $G_TLD = array(
            'biz', 'com', 'edu', 'gov', 'info', 'int', 'mil', 'name', 'net', 'org', 'aero', 'asia', 'cat', 'coop',
            'jobs', 'mobi', 'museum', 'pro', 'tel', 'travel', 'arpa', 'root', 'berlin', 'bzh', 'cym', 'gal', 'geo',
            'kid', 'kids', 'lat', 'mail', 'nyc', 'post', 'sco', 'web', 'xxx', 'nato', 'example', 'invalid', 'localhost',
            'test', 'bitnet', 'csnet', 'ip', 'local', 'onion', 'uucp', 'co'
        );

        // country tlds (source: http://en.wikipedia.org/wiki/Country_code_top-level_domain)
        $C_TLD = array( // active
            'ac', 'ad', 'ae', 'af', 'ag', 'ai', 'al', 'am', 'an', 'ao', 'aq', 'ar', 'as', 'at', 'au', 'aw', 'ax', 'az',
            'ba', 'bb', 'bd', 'be', 'bf', 'bg', 'bh', 'bi', 'bj', 'bm', 'bn', 'bo', 'br', 'bs', 'bt', 'bw', 'by', 'bz',
            'ca', 'cc', 'cd', 'cf', 'cg', 'ch', 'ci', 'ck', 'cl', 'cm', 'cn', 'co', 'cr', 'cu', 'cv', 'cx', 'cy', 'cz',
            'de', 'dj', 'dk', 'dm', 'do', 'dz', 'ec', 'ee', 'eg', 'er', 'es', 'et', 'eu', 'fi', 'fj', 'fk', 'fm', 'fo',
            'fr', 'ga', 'gd', 'ge', 'gf', 'gg', 'gh', 'gi', 'gl', 'gm', 'gn', 'gp', 'gq', 'gr', 'gs', 'gt', 'gu', 'gw',
            'gy', 'hk', 'hm', 'hn', 'hr', 'ht', 'hu', 'id', 'ie', 'il', 'im', 'in', 'io', 'iq', 'ir', 'is', 'it', 'je',
            'jm', 'jo', 'jp', 'ke', 'kg', 'kh', 'ki', 'km', 'kn', 'kr', 'kw', 'ky', 'kz', 'la', 'lb', 'lc', 'li', 'lk',
            'lr', 'ls', 'lt', 'lu', 'lv', 'ly', 'ma', 'mc', 'md', 'mg', 'mh', 'mk', 'ml', 'mm', 'mn', 'mo', 'mp', 'mq',
            'mr', 'ms', 'mt', 'mu', 'mv', 'mw', 'mx', 'my', 'mz', 'na', 'nc', 'ne', 'nf', 'ng', 'ni', 'nl', 'no', 'np',
            'nr', 'nu', 'nz', 'om', 'pa', 'pe', 'pf', 'pg', 'ph', 'pk', 'pl', 'pn', 'pr', 'ps', 'pt', 'pw', 'py', 'qa',
            're', 'ro', 'ru', 'rw', 'sa', 'sb', 'sc', 'sd', 'se', 'sg', 'sh', 'si', 'sk', 'sl', 'sm', 'sn', 'sr', 'st',
            'sv', 'sy', 'sz', 'tc', 'td', 'tf', 'tg', 'th', 'tj', 'tk', 'tl', 'tm', 'tn', 'to', 'tr', 'tt', 'tv', 'tw',
            'tz', 'ua', 'ug', 'uk', 'us', 'uy', 'uz', 'va', 'vc', 've', 'vg', 'vi', 'vn', 'vu', 'wf', 'ws', 'ye', 'yu',
            'za', 'zm', 'zw', // inactive
            'eh', 'kp', 'me', 'rs', 'um', 'bv', 'gb', 'pm', 'sj', 'so', 'yt', 'su', 'tp', 'bu', 'cs', 'dd', 'zr'
        );

        // get domain
        if (!$full_domain = $this->getUrlDomain($url)) {
            return $base_domain;
        }

        // break up domain, reverse
        $DOMAIN = explode('.', $full_domain);
        if ($debug) {
            print_r($DOMAIN);
        }
        $DOMAIN = array_reverse($DOMAIN);
        if ($debug) {
            print_r($DOMAIN);
        }
        // first check for ip address
        if (count($DOMAIN) == 4 && is_numeric($DOMAIN[0]) && is_numeric($DOMAIN[3])) {
            return $full_domain;
        }

        // if only 2 domain parts, that must be our domain
        if (count($DOMAIN) <= 2) {
            return $full_domain;
        }

        /*
        finally, with 3+ domain parts: obviously D0 is tld now,
        if D0 = ctld and D1 = gtld, we might have something like com.uk so,
        if D0 = ctld && D1 = gtld && D2 != 'www', domain = D2.D1.D0 else if D0 = ctld && D1 = gtld && D2 == 'www',
        domain = D1.D0 else domain = D1.D0 - these rules are simplified below.
        */
        if (in_array($DOMAIN[0], $C_TLD) && in_array($DOMAIN[1], $G_TLD) && $DOMAIN[2] != 'www') {
            $full_domain = $DOMAIN[2] . '.' . $DOMAIN[1] . '.' . $DOMAIN[0];
        } else {
            $full_domain = $DOMAIN[1] . '.' . $DOMAIN[0];
        }
        // did we succeed?
        return $full_domain;
    }

    /**
     * Function to get the domain from a URL.
     *
     * @param string $url the URL to be stripped.
     *
     * @return string
     * @deprecated
     */
    public function getUrlDomain($url)
    {
        $domain = '';
        $_URL = parse_url($url);

        if (!empty($_URL['host'])) {
            $domain = $_URL['host'];
        }
        return $domain;
    }

    /**
     * function to update compiled template file in cache folder
     *
     * @param string $tpl_id
     *
     * @return boolean
     */
    public function templateTouch($tpl_id)
    {
        $tplfile = $this->getHandlerTplfile()->get($tpl_id);

        if (is_object($tplfile)) {
            $file = $tplfile->getVar('tpl_file', 'n');
            $module = $tplfile->getVar('tpl_module', 'n');
            $type = $tplfile->getVar('tpl_type', 'n');
            $tpl = new XoopsTpl();
            return $tpl->touch($type . ':' . $module . '|' . $file);
        }
        return false;
    }

    /**
     * Clear the module cache
     *
     * @param int $mid Module ID
     *
     * @return void
     */
    public function templateClearModuleCache($mid)
    {
        $block_arr = $this->getHandlerBlock()->getByModule($mid);
        $count = count($block_arr);
        if ($count > 0) {
            $xoopsTpl = new XoopsTpl();
            $xoopsTpl->caching = 2;
            /* @var XoopsBlock $block */
            foreach ($block_arr as $block) {
                if ($block->getVar('template') != '') {
                    $xoopsTpl->clear_cache(
                        XOOPS_ROOT_PATH . "/modules/" . $block->getVar('dirname')
                        . "/templates/blocks/" . $block->getVar('template'),
                        'blk_' . $block->getVar('bid')
                    );
                }
            }
        }
    }

    /**
     * Support for deprecated messages events
     *
     * @param string $message message
     *
     * @return void
     */
    public function deprecated($message)
    {
        $this->events()->triggerEvent('core.deprecated', array($message));
    }

    /**
     * Support for disabling error reporting
     *
     * @return void
     */
    public function disableErrorReporting()
    {
        //error_reporting(0);
        $this->events()->triggerEvent('core.disableerrorreporting');
    }
}
