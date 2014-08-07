<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Form;

/**
 * ContainerInterface - Form container
 *
 * @category  Xoops\Form\ContainerInterface
 * @package   Xoops\Form
 * @author    trabis <lusopoemas@gmail.com>
 * @copyright 2012-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.6.0
*/
interface ContainerInterface
{
    /**
     * getRequired - get an array of required form elements
     *
     * @return array array of Xoops\Form\Element
     */
    public function getRequired();

    /**
     * getElements - get an array of forms elements
     *
     * @param boolean $recurse true to get elements recursively
     *
     * @return array of Xoops\Form\Element
     */
    public function getElements($recurse = false);
}
