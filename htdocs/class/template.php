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
 * XOOPS template engine class
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Kazumi Ono <onokazu@xoops.org>
 * @author          Skalpa Keo <skalpa@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @package         class
 * @version         $Id$
 */

//defined('XOOPS_ROOT_PATH') or die('Restricted access');

//define('SMARTY_DIR', XOOPS_PATH . '/smarty/'); // now defined when Smarty autoloads
// TODO XOOPS_COMPILE_PATH define should move to mainfile.php so it can be easily customized
define('XOOPS_COMPILE_PATH', XOOPS_VAR_PATH . '/caches/smarty_compile');

/**
 * Template engine
 *
 * @package kernel
 * @subpackage core
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright (c) 2000-2003 The Xoops Project - www.xoops.org
 */
class XoopsTpl extends Smarty
{
    /**
     * @var XoopsTheme
     */
    public $currentTheme = null;

    public function __construct()
    {
        parent::__construct();
        $xoops = Xoops::getInstance();
        $xoops->preload()->triggerEvent('core.template.construct.start', array($this));
        $this->left_delimiter = '<{';
        $this->right_delimiter = '}>';
        $this->template_dir = XOOPS_THEME_PATH;
        $this->cache_dir = XOOPS_VAR_PATH . '/caches/smarty_cache';
        $this->compile_dir = XOOPS_COMPILE_PATH;
        $this->compile_check = ($xoops->getConfig('theme_fromfile') == 1);
        $this->setPluginsDir(XOOPS_PATH . '/smarty/xoops_plugins');
        $this->addPluginsDir(SMARTY_DIR . 'plugins');
        //$this->Smarty();
        $this->setCompileId();
        $this->assign(
            array('xoops_url' => XOOPS_URL, 'xoops_rootpath' => XOOPS_ROOT_PATH, 'xoops_langcode' => XoopsLocale::getLangCode(), 'xoops_charset' => XoopsLocale::getCharset(), 'xoops_version' => XOOPS_VERSION, 'xoops_upload_url' => XOOPS_UPLOAD_URL)
        );
    }

    /**
     * Renders output from template data
     *
     * @param string $tplSource The template to render
     * @param bool   $display   If rendered text should be output or returned
     * @param array  $vars
     * @return string Rendered output if $display was false
     */
    public function fetchFromData($tplSource, $display = false, $vars = null)
    {
        if (!function_exists('smarty_function_eval')) {
            require_once SMARTY_DIR . '/plugins/function.eval.php';
        }
        if (isset($vars)) {
            $oldVars = $this->_tpl_vars;
            $this->assign($vars);
            $out = smarty_function_eval(
                array('var' => $tplSource), $this
            );
            $this->_tpl_vars = $oldVars;
            return $out;
        }
        return smarty_function_eval(
            array('var' => $tplSource), $this
        );
    }

    /**
     * XoopsTpl::touch
     *
     * @param string $resourceName
     * @return bool
     */
    public function touch($resourceName)
    {
        $isForced = $this->force_compile;
        $this->force_compile = true;
        $this->clearCache($resourceName);
        $result = true; // $this->_compile_resource($resourceName, $this->_get_compile_path($resourceName));
        $this->force_compile = $isForced;
        return $result;
    }

    /**
     * returns an auto_id for auto-file-functions
     *
     * @param string $cache_id
     * @param string $compile_id
     * @return string|null
     */
    public function _get_auto_id($cache_id = null, $compile_id = null)
    {
        if (isset($cache_id)) {
            return (isset($compile_id)) ? $compile_id . '-' . $cache_id : $cache_id;
        } else {
            if (isset($compile_id)) {
                return $compile_id;
            } else {
                return null;
            }
        }
    }

    /**
     * XoopsTpl::setCompileId()
     *
     * @param mixed $module_dirname
     * @param mixed $theme_set
     * @param mixed $template_set
     * @return void
     */
    public function setCompileId($module_dirname = null, $theme_set = null, $template_set = null)
    {
        $xoops = Xoops::getInstance();

        $template_set = empty($template_set) ? $xoops->getConfig('template_set') : $template_set;
        $theme_set = empty($theme_set) ? $xoops->getConfig('theme_set') : $theme_set;
        $module_dirname = empty($module_dirname) ? $xoops->moduleDirname : $module_dirname;
        $this->compile_id = substr(md5(XOOPS_URL), 0, 8) . '-' . $module_dirname . '-' . $theme_set . '-' . $template_set;
        //$this->_compile_id = $this->compile_id;
    }

    /**
     * XoopsTpl::clearModuleCompileCache()
     *
     * Clean up compiled and cached templates for a module
     *
     * TODO - handle $use_sub_dirs cases
     *
     * @param mixed $module_dirname
     * @param mixed $theme_set
     * @param mixed $template_set
     * @return int number of deleted cache and compiler files
     */
    public function clearModuleCompileCache($module_dirname = null, $theme_set = null, $template_set = null)
    {
        $hold_compile_id = $this->compile_id;
        // $this->setCompileId($module_dirname, $template_set, $theme_set);
        // TODO - should handle $use_sub_dirs
        $this->setCompileId($module_dirname, '*', '*');
        $compile_id = $this->compile_id;
        $this->compile_id = $hold_compile_id;
        $compile_id = preg_replace('![^\w\|]+!', '_', $compile_id);
        $glob = $compile_id . '*.php';
        $count=0;
        $files = glob($this->compile_dir . '/' . $glob);
        foreach ($files as $filename) {
            $count += unlink($filename) ? 1 : 0;
        }
        $files = glob($this->cache_dir . '/*' . $glob);
        foreach ($files as $filename) {
            $count += unlink($filename) ? 1 : 0;
        }
        return $count;
    }

    /**
     * Empty cache for a specific template
     *
     * This is just a pass thru wrapper with a warning since this method previously existed
     * only in XoopsTpl, but now is also a regulat Smarty method.
     *
     * clearModuleCompileCache() is the replacement for the old clearCache
     *
     * @param  string  $template_name template name
     * @param  string  $cache_id      cache id
     * @param  string  $compile_id    compile id
     * @param  integer $exp_time      expiration time
     * @param  string  $type          resource type
     *
     * @return integer number of cache files deleted
     */
    public function clearCache($template_name, $cache_id = null, $compile_id = null, $exp_time = null, $type = null)
    {
        \Xoops::getInstance()->deprecated('XoopsTpl::clearCache() is potentially ambiguous');
        return parent::clearCache($template_name, $cache_id, $compile_id, $exp_time, $type);
    }
}
