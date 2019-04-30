<?php
/**
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
namespace Xoops\Form\Renderer;

use Xoops\Form\RendererInterface;
/**
 * Bootstrap3Renderer style form renderer
 *
 * @author    Grégory Mage
 * @copyright 2019 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Bootstrap3Renderer implements RendererInterface
{
    public function render(\Xoops\Form\Element $element):string
    {
        $methodName = 'render' . str_replace('\\' , '', get_class($element));
        if (true === method_exists($this, $methodName)) {
            return $this->$methodName($element);
        }
        return $element->defaultRender();
    }

    /**
     * Example of an override render method for a specific element class.
     * Each method will be specific to a concrete implementation of Xoops\Form\Element
     *
     * @param \Xoops\Form\Button $element Provides access to the element we are rendering.
     *                                    The strong type makes sure we get what we expect.
     *
     * @return string the rendering of $element
     *
    protected function renderXoopsFormButton(\Xoops\Form\Button $element):string
    {
        // do the rendering and return a string
		return '';
    }*/
}
