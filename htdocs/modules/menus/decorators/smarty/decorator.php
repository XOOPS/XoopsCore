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
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Menus
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */
class MenusSmartyDecorator extends MenusDecoratorAbstract implements MenusDecoratorInterface
{
    public function hasAccess($menu, &$hasAccess)
    {
    }

    public function accessFilter(&$accessFilter)
    {
    }

    public function start()
    {
    }

    public function end(&$menus)
    {
    }

    public function decorateMenu(&$menu)
    {
        $decorations = ['link', 'image', 'title', 'alt_title'];
        foreach ($decorations as $decoration) {
            $menu[$decoration] = self::_doDecoration($menu[$decoration]);
        }
    }

    public function _doDecoration($string)
    {
        $xoops = Xoops::getInstance();
        if (!preg_match('/{(.*\|.*)}/i', $string, $reg)) {
            return $string;
        }

        $expression = $reg[0];
        list($validator, $value) = array_map('strtolower', explode('|', $reg[1]));

        if ('smarty' === $validator) {
            if (isset($xoops->tpl()->_tpl_vars[$value])) {
                $string = str_replace($expression, $xoops->tpl()->_tpl_vars[$value], $string);
            }
        }

        return $string;
    }
}
