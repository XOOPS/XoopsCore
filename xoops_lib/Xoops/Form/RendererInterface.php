<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
namespace Xoops\Form;
/**
 * Define Renderer interface for forms
 *
 * @category  Xoops\Form\RendererInterface
 * @package   Xoops\Form
 * @author    Grégory Mage
 * @copyright 2000-2020 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
interface RendererInterface
{
    /**
     * Render support for XoopsFormButton
     *
     * @param $element form element
     *
     * @return string rendered form element
     */
	public function render(\Xoops\Form\Element $element):string;

}
