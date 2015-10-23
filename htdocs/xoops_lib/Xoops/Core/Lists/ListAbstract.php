<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xoops\Core\Lists;

use Xoops\Form\OptionElement;

/**
 * ListAbstract - return a list of something
 *
 * @category  Xoops\Core\Lists\ListAbstract
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2015 XOOPS Project (http://xoops.org)/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
abstract class ListAbstract
{
    /**
     * return a list as an array
     *
     * @return array
     */
    public static function getList()
    {
        return array();
    }

    /**
     * add list to a Xoops\Form\OptionElement
     *
     * @param OptionElement $element
     *
     * @return void
     */
    public static function setOptionsArray(OptionElement $element)
    {
        $args = func_get_args();
        array_shift($args);
        if (empty($args)) {
            $element->addOptionArray(static::getList());
        } else {
            $element->addOptionArray(call_user_func_array('static::getList', $args));
        }
    }
}
