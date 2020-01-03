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
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 * @param mixed $params
 * @param mixed $smarty
 */
function smarty_function_translateTheme($params, &$smarty)
{
    $key = isset($params['key']) ? $params['key'] : '';
    $dirname = isset($params['dirname']) ? $params['dirname'] : '';

    return \Xoops\Locale::translateTheme($key, $dirname);
}
