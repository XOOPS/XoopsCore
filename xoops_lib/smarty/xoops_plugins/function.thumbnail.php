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
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2014 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */

/**
 * smarty_function_thumbnail
 * @param array  $params  associative array of parameters
 *                        image => xoops virtual path to image
 *                        w => thumbnail width in pixels
 *                        w => thumbnail height in pixels
 * @param object &$smarty smarty context
 *
 * @return string
 */
function smarty_function_thumbnail($params, &$smarty)
{
    $image = isset($params['image']) ? $params['image'] : '';
    $w     = isset($params['w']) ? $params['w'] : 0;
    $h     = isset($params['h']) ? $params['h'] : 0;

    return \Xoops::getInstance()->service('thumbnail')->getImgUrl($image, $w, $h)->getValue();
}
