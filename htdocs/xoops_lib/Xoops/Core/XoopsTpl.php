<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core;

/**
 * XOOPS template engine class
 *
 * @category  Xoops\Core
 * @package   Template
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Skalpa Keo <skalpa@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2000-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class XoopsTpl extends \Smarty
{
    use SmartyBCTrait;

    /**
     * @var \Xoops\Core\Theme\XoopsTheme
     */
    public $currentTheme = null;

    /**
     * XoopsTpl constructor
     */
    public function __construct()
    {
        parent::__construct(); // SMARTY_PLUGINS_DIR is initialized into parent
        $xoops = \Xoops::getInstance();
        $xoops->events()->triggerEvent('core.template.construct.start', array($this));

        $this->registerFilter(
            'pre',
            [$this, 'convertLegacyDelimiters']
        );

        //$this->left_delimiter = '<{';
        //$this->right_delimiter = '}>';

        $this->setTemplateDir(\XoopsBaseConfig::get('themes-path'));
        $this->setCacheDir(\XoopsBaseConfig::get('smarty-cache'));
        $this->setCompileDir(\XoopsBaseConfig::get('smarty-compile'));
        $this->compile_check = ($xoops->getConfig('theme_fromfile') == 1);
        $this->setPluginsDir(\XoopsBaseConfig::get('smarty-xoops-plugins'));
        $this->addPluginsDir(SMARTY_PLUGINS_DIR);
        $this->setCompileId();
        $this->assign(
            array('xoops_url' => \XoopsBaseConfig::get('url'),
                'xoops_rootpath' => \XoopsBaseConfig::get('root-path'),
                'xoops_langcode' => \XoopsLocale::getLangCode(),
                'xoops_charset' => \XoopsLocale::getCharset(),
                'xoops_version' => \Xoops::VERSION,
                'xoops_upload_url' => \XoopsBaseConfig::get('uploads-url'))
        );
    }

    /**
     * XOOPS legacy used '<{' and '}>' as delimiters rather than using the default '{' and '}'.
     * This prefilter function converts any legacy delimiters to Smarty default delimiters.
     *
     * The intention is to phase out the legacy delimiters entirely.
     *
     * @param string                    $tpl_source template source
     * @param \Smarty_Internal_Template $template   template object
     *
     * @return string source with any legacy delimiters converted to standard default delimiters
     */
    public function convertLegacyDelimiters($tpl_source, \Smarty_Internal_Template $template)
    {
        $countLeft = 0;
        $countRight = -1;
        $temp = str_replace('<{', '{', $tpl_source, $countLeft);
        if ($countLeft>0) {
            $temp = str_replace('}>', '}', $temp, $countRight);
        }
        return ($countLeft === $countRight) ? $temp : $tpl_source;
    }

    /**
     * XoopsTpl::touch
     *
     * @param string $resourceName name of resource
     *
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
     * XoopsTpl::setCompileId()
     *
     * @param mixed $module_dirname module directory
     * @param mixed $theme_set      theme set
     * @param mixed $template_set   template set
     *
     * @return void
     */
    public function setCompileId($module_dirname = null, $theme_set = null, $template_set = null)
    {
        $xoops = \Xoops::getInstance();

        $template_set = empty($template_set) ? $xoops->getConfig('template_set') : $template_set;
        $theme_set = empty($theme_set) ? $xoops->getConfig('theme_set') : $theme_set;
        $module_dirname = empty($module_dirname) ? $xoops->moduleDirname : $module_dirname;
        $this->compile_id = substr(md5(\XoopsBaseConfig::get('url')), 0, 8) . '-' . $module_dirname
            . '-' . $theme_set . '-' . $template_set;
        //$this->_compile_id = $this->compile_id;
    }

    /**
     * XoopsTpl::clearModuleCompileCache()
     *
     * Clean up compiled and cached templates for a module
     *
     * TODO - handle $use_sub_dirs cases
     *
     * @param mixed $module_dirname module directory
     * @param mixed $theme_set      theme set
     * @param mixed $template_set   template set
     *
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
        $files = glob($this->getCompileDir() . '/' . $glob);
        foreach ($files as $filename) {
            $count += unlink($filename) ? 1 : 0;
        }
        $files = glob($this->getCacheDir() . '/*' . $glob);
        foreach ($files as $filename) {
            $count += unlink($filename) ? 1 : 0;
        }
        return $count;
    }

    /**
     * Empty cache for a specific template
     *
     * This is just a pass-through wrapper with a warning since this method previously existed
     * only in XoopsTpl, but now is also a regular Smarty method.
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
