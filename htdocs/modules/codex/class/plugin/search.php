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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class CodexSearchPlugin extends Xoops\Module\Plugin\PluginAbstract implements SearchPluginInterface
{
    public function search($queries, $andor, $limit, $start, $uid)
    {
        $queries = implode(' ', (array) $queries);

        $files = XoopsLists::getFileListAsArray(dirname(dirname(__DIR__)));
        $res = array();
        $i = 0;
        foreach ($files as $file) {
            if (!in_array($file, array('xoops_version.php', 'index.php'))) {
                $fileName = ucfirst(str_replace('.php', '', $file));
                if (stripos($fileName, $queries) !== false) {
                    $res[$i]['link'] = $file;
                    $res[$i]['title'] = $fileName;
                    $i++;
                }
            }
        }
        return $res;
    }
}
