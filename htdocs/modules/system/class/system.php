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
 * System module
 *
 * @package     System
 * @author      Andricq Nicolas (AKA MusS)
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */
class System
{

    /**
     * @var null|SystemModule
     */
    public $module = null;

    /**
     * @var null|Xoops
     */
    private $xoops = null;

    /**
     * Actual System Module
     */
    private function __construct()
    {
        $this->xoops = Xoops::getInstance();
    }

    /**
     * Access the only instance of this class
     *
     * @return System
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
     * @return bool
     */
    public function checkRight()
    {
        if ($this->xoops->isUser()) {
            $this->xoops->module = $this->xoops->getModuleByDirname('system');
            if (!$this->xoops->user->isAdmin($this->xoops->module->mid())) {
                return false;
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * @param $global
     * @param $key
     * @param string $default
     * @param string $type
     * @return int|mixed|string
     */
    public function cleanVars(&$global, $key, $default = '', $type = 'int')
    {
        switch ($type) {
            case 'array':
                $ret = (isset($global[$key]) && is_array($global[$key])) ? $global[$key] : $default;
                break;
            case 'date':
                $ret = (isset($global[$key])) ? strtotime($global[$key]) : $default;
                break;
            case 'string':
                $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
                break;
            case 'int':
            default:
                $ret = (isset($global[$key])) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
                break;
        }
        if ($ret === false) {
            return $default;
        }
        return $ret;
    }

    /**
     * System language loader wrapper
     *
     * @param string $name     Name of language file to be loaded, without extension
     * @param string $domain   Module dirname; global language file will be loaded
     *                         if $domain is set to 'global' or not specified
     * @param string $language Language to be loaded, current language content will
     *                         be loaded if not specified
     * @return  boolean
     * @todo    expand domain to multiple categories, e.g. module:system, framework:filter, etc.
     *
     */
    public function loadLanguage($name, $domain = '', $language = null)
    {
        $xoops = Xoops::getInstance();
        /**
         * We must check later for an empty value. As xoops_getPageOption could be empty
         */
        if (empty($name)) {
            return false;
        }
        $language = empty($language) ? $xoops->getConfig('language') : $language;
        $path = 'modules/' . $domain . '/language/';
        if (XoopsLoad::fileExists($file = $xoops->path($path . $language . '/admin/' . $name . '.php'))) {
            $ret = include_once $file;
        } else {
            $ret = include_once $xoops->path($path . 'english/admin/' . $name . '.php');
        }
        return $ret;
    }

    /**
     * @param string $version
     * @param string $value
     * @return string
     */
    public function adminVersion($version, $value = '')
    {
        static $tblVersion = array();
        if (is_array($tblVersion) && array_key_exists($version . '.' . $value, $tblVersion)) {
            return $tblVersion[$version . '.' . $value];
        }
        $xoops = Xoops::getInstance();
        $path = $xoops->path('modules/system/admin/' . $version . '/xoops_version.php');
        if (XoopsLoad::fileExists($path)) {
            $modversion = array();
            include $path;
            $retvalue = $modversion[$value];
            $tblVersion[$version . '.' . $value] = $retvalue;
            return $retvalue;
        }
        return '';
    }

    /**
     * System Clean cache 'xoops_data/caches/'
     *
     * @param integer[] $cache cache caches to be cleaned
     *                                - 1 = Smarty cache
     *                                - 2 = Smarty compile
     *                                - 3 = cache
     *
     * Note: clearing of the actual cache is now handled by the cache system.
     * Only the 'default' cache is cleaned by this function. This function returns
     * only a boolean status, not a count as done before.
     *
     * The old behavior of cleaning the directory is maintained, but does not
     * clean the actual cache, only artifacts of legacy programs that write files
     * directly in the xoops_cache folder.
     *
     * @return array|false associative array of status/count for each cache
     */
    public function cleanCache($cache)
    {
        $cachePath = \XoopsBaseConfig::get('var-path') . '/caches/';
        $total_smarty_cache = 0;
        $total_smarty_compile = 0;
        $total_xoops_cache = 0;
        if (!empty($cache)) {
            for ($i = 0; $i < count($cache); ++$i) {
                switch ($cache[$i]) {
                    case 1:
                        $files = glob($cachePath . 'smarty_cache/*.*');
                        $total_smarty_cache = 0;
                        foreach ($files as $filename) {
                            if (basename(strtolower($filename)) != 'index.html') {
                                unlink($filename);
                                ++$total_smarty_cache;
                            }
                        }
                        break;

                    case 2:
                        $files = glob($cachePath . 'smarty_compile/*.*');
                        $total_smarty_compile = 0;
                        foreach ($files as $filename) {
                            if (basename(strtolower($filename)) != 'index.html') {
                                unlink($filename);
                                ++$total_smarty_compile;
                            }
                        }
                        break;

                    case 3:
                        // ask the cache to clear itself
                        $status = Xoops::getInstance()->cache()->clear();
                        // this section captures legacy cache use only
                        $files = glob($cachePath . 'xoops_cache/*.*');
                        $total_xoops_cache = 0;
                        foreach ($files as $filename) {
                            if (basename(strtolower($filename)) != 'index.html') {
                                unlink($filename);
                                ++$total_xoops_cache;
                            }
                        }
                        $total_xoops_cache = $status || ($total_xoops_cache>0);
                        break;
                }
            }
            $ret['smarty_cache'] = $total_smarty_cache;
            $ret['smarty_compile'] = $total_smarty_compile;
            $ret['xoops_cache'] = $total_xoops_cache;
            return $ret;
        } else {
            return false;
        }
    }
}
