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
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */

class CodexMenusPlugin extends Xoops\Module\Plugin\PluginAbstract implements MenusPluginInterface
{
    /**
     * expects an array of array containing:
     * name,      Name of the submenu
     * url,       Url of the submenu relative to the module
     * ex: return array(0 => array(
     *      'name' => _MI_PUBLISHER_SUB_SMNAME3;
     *      'url' => "search.php";
     *    ));
     *
     * @return array
     */
    public function subMenus()
    {
        $xoops = Xoops::getInstance();
        $ret = array();
        $files = \Xoops\Core\Lists\File::getList($xoops->path('modules/codex/'));
        $i = 0;
        foreach ($files as $file) {
            if (!in_array($file, array('xoops_version.php', 'index.php'))) {
                $fileName = ucfirst(str_replace('.php', '', $file));
                $ret[$i]['name'] = $fileName;
                $ret[$i]['url'] = $file;
                ++$i;
            }
        }

        return $ret;
    }
}
