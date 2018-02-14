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
 * @copyright   XOOPS Project (http://xoops.org)
 * @license     GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      trabis <lusopoemas@gmail.com>
 */

/**
 * Allows usage of shortcodes in templates
 *
 * Usage example: {shortcode [b]Hello World[/b]}
 * Output example: <b>Hello World<b>
 *
 * @param array $args
 * @param Smarty $smarty
 * @return string
 */
function smarty_compiler_shortcode($args, Smarty $smarty)
{
    if ($string = reset($args)) {
        $string = trim($string, " '\"\t\n\r\0\x0B");
        $shortCodes = \Xoops\Core\Text\Sanitizer::getInstance()->getShortCodes();
        return $shortCodes->process($string);
    }
}