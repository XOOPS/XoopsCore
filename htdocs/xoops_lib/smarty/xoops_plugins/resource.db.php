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
 * Smarty plugin
 *
 * Fetches templates from a database
 *
 * @copyright       2008-2015 XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */
class Smarty_Resource_Db extends Smarty_Resource_Custom
{

    /**
     * Fetch a template and its modification time from database
     *
     * @param  string  $name   template name
     * @param  string  $source template source
     * @param  integer $mtime  template modification timestamp (epoch)
     *
     * @return void
     */
    protected function fetch($name, &$source, &$mtime)
    {
        $tpl = $this->dbTplInfo($name);
        if (is_object($tpl)) {
            /* @var $tpl XoopsTplFile */
            $source = $tpl->getVar('tpl_source', 'n');
            $mtime = $tpl->getVar('tpl_lastmodified', 'n');
        } else {
            $stat = stat($tpl);

            // Did we fail to get stat information?
            if ($stat) {
                $mtime = $stat['mtime'];
                $filesize = $stat['size'];
                $fp = fopen($tpl, 'r');
                $source = ($filesize > 0) ? fread($fp, $filesize) : '';
                fclose($fp);
            } else {
                $source = null;
                $mtime = null;
            }
        }
    }

    /**
     * Get template info from db, or file name if available
     *
     * @param string $tpl_name template name
     *
     * @return XoopsTplFile|string tpl object from database or absolute file name path
     */
    private function dbTplInfo($tpl_name)
    {
        static $cache = array();
        global $xoopsConfig;
        $xoops = Xoops::getInstance();

        if (isset($cache[$tpl_name])) {
            return $cache[$tpl_name];
        }
        $tplset = $xoopsConfig['template_set'];
        $theme = isset($xoopsConfig['theme_set']) ? $xoopsConfig['theme_set'] : 'default';
        $tplfile_handler = $xoops->getHandlerTplFile();
        // If we're not using the "default" template set, then get the templates from the DB
        if ($tplset !== "default") {
            $tplobj = $tplfile_handler->find($tplset, null, null, null, $tpl_name, true);
            if (count($tplobj)) {
                return $cache[$tpl_name] = $tplobj[0];
            }
        }
        // If we'using the default tplset, get the template from the filesystem
        $tplobj = $tplfile_handler->find("default", null, null, null, $tpl_name, true);

        if (!count($tplobj)) {
            return $cache[$tpl_name] = $tpl_name;
        }
        /* @var $tplobj XoopsTplFile */
        $tplobj = $tplobj[0];
        $module = $tplobj->getVar('tpl_module', 'n');
        $type = $tplobj->getVar('tpl_type', 'n');
        // Construct template path
        switch ($type) {
            case 'block':
                $directory = \XoopsBaseConfig::get('themes-path');
                $path = 'blocks/';
                break;
            case 'admin':
                $theme = isset($xoopsConfig['cpanel']) ? $xoopsConfig['cpanel'] : 'default';
                $directory = \XoopsBaseConfig::get('adminthemes-path');
                $path = 'admin/';
                break;
            default:
                $directory = \XoopsBaseConfig::get('themes-path');
                $path = '';
                break;
        }
        // First, check for an overloaded version within the theme folder
        $filepath = $directory . "/{$theme}/modules/{$module}/{$path}{$tpl_name}";
        if (!file_exists($filepath)) {
            // If no custom version exists, get the tpl from its default location
            $filepath = \XoopsBaseConfig::get('root-path') . "/modules/{$module}/templates/{$path}{$tpl_name}";
            if (!file_exists($filepath)) {
                return $cache[$tpl_name] = $tplobj ;
            }
        }
        return $cache[$tpl_name] = $filepath;
    }
}
