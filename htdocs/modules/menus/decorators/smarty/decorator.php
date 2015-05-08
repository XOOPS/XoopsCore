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
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

class MenusSmartyDecorator extends MenusDecoratorAbstract implements MenusDecoratorInterface
{
    function hasAccess($menu, &$hasAccess)
    {
    }

    function accessFilter(&$accessFilter)
    {
    }

    function start()
    {
    }

    function end(&$menus)
    {
    }

    function decorateMenu(&$menu)
    {
        $decorations = array('link', 'image', 'title', 'alt_title');
        foreach ($decorations as $decoration) {
            $menu[$decoration] = self::_doDecoration($menu[$decoration]);
        }
    }

    function _doDecoration($string)
    {
        $xoops = Xoops::getInstance();
        if (!preg_match('/{(.*\|.*)}/i', $string, $reg)) {
            return $string;
        }

        $expression = $reg[0];
        list($validator, $value) = array_map('strtolower', explode('|', $reg[1]));

        if ($validator == 'smarty') {
            if (isset($xoops->tpl()->_tpl_vars[$value])) {
               $string = str_replace($expression, $xoops->tpl()->_tpl_vars[$value], $string);
            }
        }

        return $string;
    }

}