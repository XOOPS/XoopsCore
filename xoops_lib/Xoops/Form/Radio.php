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
 * Radio - radio button element
 *
 * @category  Xoops\Form\Radio
 * @package   Xoops\Form
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @author    Taiwen Jiang <phppp@users.sourceforge.net>
 * @copyright 2001-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Radio extends OptionElement
{
    /**
     * __construct
     *
     * @param mixed   $caption Caption or array of all attributes
     *                          Control attributes:
     *                              :inline true to render with inline style
     * @param string  $name    name attribute
     * @param string  $value   Pre-selected value
     * @param boolean $inline  true to display inline
     */
    public function __construct($caption, $name = null, $value = null, $inline = true)
    {
        if (is_array($caption)) {
            parent::__construct($caption);
        } else {
            parent::__construct([]);
            $this->setWithDefaults('caption', $caption, '');
            $this->setWithDefaults('name', $name, 'name_error');
            $this->set('value', $value);
            if ($inline) {
                $this->set(':inline');
            }
        }
        $this->set('type', 'radio');
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render()
    {
        $ele_options = $this->getOptions();
        $ele_value = $this->getValue();
        $ele_name = $this->getName();
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        $ret = "";
        $inline = $this->has(':inline');
        if ($inline) {
            $ret .= '<div class="input-group">';
        }
        static $id_ele = 0;
        foreach ($ele_options as $value => $buttonCaption) {
            $this->remove('checked');
            if (isset($ele_value) && $value == $ele_value) {
                $this->set('checked');
            }
            $this->set('value', $value);
            ++$id_ele;
            $this->set('id', $ele_name . $id_ele);
            if ($inline) {
                $ret .= '<label class="radio-inline">';
                $ret .= '<input ' . $this->renderAttributeString() . $extra . ">" . $buttonCaption . "\n";
                $ret .= "</label>\n";
            } else {
                $ret .= "<div class=\"radio\">\n<label>";
                $ret .= '<input ' . $this->renderAttributeString() . $extra . '>' . "\n";
                $ret .= $buttonCaption . "\n";
                $ret .= "</label>\n</div>\n";
            }
        }
        if ($inline) {
            $ret .= '</div>';
        }
        return $ret;
    }
}
