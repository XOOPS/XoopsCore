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
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */
class CodexSearchPlugin extends Xoops\Module\Plugin\PluginAbstract implements SearchPluginInterface
{
    public function search($queries, $andor, $limit, $start, $uid)
    {
        $xoops = Xoops::getInstance();
        $queries = implode(' ', (array) $queries);

        $files = \Xoops\Core\Lists\File::getList($xoops->path('modules/codex/'));
        $res = [];
        $i = 0;
        foreach ($files as $file) {
            if (!in_array($file, ['xoops_version.php', 'index.php'])) {
                $fileName = ucfirst(str_replace('.php', '', $file));
                if (false !== mb_stripos($fileName, $queries)) {
                    $res[$i]['link'] = $file;
                    $res[$i]['title'] = $fileName;
                    ++$i;
                }
            }
        }

        return $res;
    }
}
