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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
class Smarty_Resource_Admin extends Smarty_Resource_Custom
{
    /**
     * Fetch a template and its modification time from database
     *
     * @param  string  $name   template name
     * @param  string  $source template source
     * @param  int $mtime  template modification timestamp (epoch)
     *
     * @return void
     */
    protected function fetch($name, &$source, &$mtime)
    {
        $tpl = $this->adminTplInfo($name);
        $stat = stat($tpl);

        // Did we fail to get stat information?
        if ($stat) {
            $mtime = $stat['mtime'];
            $filesize = $stat['size'];
            $fp = fopen($tpl, 'rb');
            $source = ($filesize > 0) ? fread($fp, $filesize) : '';
            fclose($fp);
        } else {
            $source = null;
            $mtime = null;
        }
    }

    /**
     * Translate template name to absolute file name path
     *
     * @param string $tpl_name template name
     *
     * @return string absolute file name path
     */
    private function adminTplInfo($tpl_name)
    {
        static $cache = [];
        $xoops = Xoops::getInstance();
        $tpl_info = $xoops->getTplInfo('admin:' . $tpl_name);
        $tpl_name = $tpl_info['tpl_name'];
        $dirname = $tpl_info['module'];
        $file = $tpl_info['file'];

        $theme_set = $xoops->getConfig('theme_set') ? $xoops->getConfig('theme_set') : 'default';
        if (!file_exists($file_path = $xoops->path("themes/{$theme_set}/modules/{$dirname}/admin/{$file}"))) {
            $file_path = $xoops->path("modules/{$dirname}/templates/admin/{$file}");
        }

        return $cache[$tpl_name] = $file_path;
    }
}
