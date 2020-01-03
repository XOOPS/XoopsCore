<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\PreloadItem;

/**
 * Search core preloads
 *
 * @author          trabis <lusopoemas@gmail.com>
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 */
class SearchPreload extends PreloadItem
{
    /**
     * listen for core.header.start
     *
     * @param mixed $args not used
     *
     * @return void
     */
    public static function eventCoreHeaderEnd($args)
    {
        $xoops = Xoops::getInstance();
        $search = $xoops->getModuleHelper('search');
        if ($search->getConfig('enable_search')) {
            $xoops->tpl()->assign('search_url', $search->url('index.php'));
        }
    }
}
