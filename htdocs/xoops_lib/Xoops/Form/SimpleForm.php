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
 * SimpleForm - Form that outputs as simple HTML, with minimum formatting
 *
 * @category  Xoops\Form\SimpleForm
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
*/
class SimpleForm extends Form
{
    /**
     * Insert an empty row in the table to serve as a separator.
     *
     * @param string $extra not in use.
     * @param string $class not in use
     *
     * @return void
     */
    public function insertBreak($extra = '', $class = '')
    {
        $class = empty($class) ? '' : ' class="' . $class . '"';
        $value = '<br' . $class . ' />' . $extra;
        $ele = new Raw($value);
        $this->addElement($ele);
    }

    /**
     * create HTML to output the form with minimal formatting
     *
     * @return string
     */
    public function render()
    {
        $ret = $this->getTitle() . "\n<form name=\"" . $this->getName() . "\" id=\""
            . $this->getName() . "\" action=\"" . $this->getAction() . "\" method=\""
            . $this->getMethod() . "\"" . $this->getExtra() . ">\n";
        foreach ($this->getElements() as $ele) {
            /* @var $ele Element */
            if (!$ele->isHidden()) {
                if (!$ele instanceof Raw) {
                    $ret .= "<strong>" . $ele->getCaption() . "</strong><br />" . $ele->render() . "<br />\n";
                } else {
                    $ret .= $ele->render();
                }
            } else {
                $ret .= $ele->render() . "\n";
            }
        }
        $ret .= "</form>\n";
        return $ret;
    }
}
