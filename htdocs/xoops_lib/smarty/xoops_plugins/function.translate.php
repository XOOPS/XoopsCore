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

function smarty_function_translate($params, &$smarty)
{
    $key = '';
    $dirname = 'xoops';
    if (isset($params['key'])) {
        $key = $params['key'];
        unset($params['key']);
    };
    if (isset($params['dirname'])) {
        $dirname = $params['dirname'];
        unset($params['dirname']);
    };
    return \Xoops\Locale::translate($key, $dirname, $params);
    //return \Xoops\Core\Text\Sanitizer::getInstance()->escapeForJavascript(\Xoops\Locale::translate($key, $dirname));
}
