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
 * ButtonTray - button tray form element
 *
 * @category  Xoops\Form\ButtonTray
 * @package   Xoops\Form
 * @author    John Neill <catzwolf@xoops.org>
 * @copyright 2012-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class ButtonTray extends Element
{
    /**
     * Create a tray of buttons - delete (optional,) cancel, reset, and a primary button, i.e. submit
     *
     * @param string|array $name       name or array of all attributes
     *                                  Control attributes:
     *                                      :showdelete true to show delete button
     * @param string       $value      value
     * @param string       $type       Type for primary button. This could be either "button", "submit", or "reset"
     * @param string       $onclick    setExtra() javascript code for primary button. If using the array
     *                                  of attributes invocation, set event handler(s) using attributes
     * @param boolean      $showDelete show delete button, includes javascript confirmation dialog
     */
    public function __construct($name, $value = '', $type = '', $onclick = '', $showDelete = false)
    {
        if (is_array($name)) {
            parent::__construct($name);
        } else {
            parent::__construct([]);
            $this->setWithDefaults('name', $name, 'name_error');
            $this->set('value', $value);
            $this->setWithDefaults('type', $type, 'submit', ['button', 'submit', 'reset']);
            $this->setWithDefaults(':showdelete', $showDelete, false, [true, false]);
            if (!empty($onclick)) {
                $this->setExtra($onclick);
            }
        }
    }

    /**
     * getType
     *
     * @return string type
     */
    public function getType()
    {
        return (string) $this->get('type', '');
    }

    /**
     * render
     *
     * @return string rendered button tray
     */
    public function render()
    {
        $ret = '';
        $this->add('class', 'btn');
        $class = 'class="' . $this->getClass() . '"';

        $attributes = $this->renderAttributeString();

        if ((bool) $this->get(':showdelete', false)) {
            $ret .= '<input type="submit"' . $class . ' name="delete" id="delete" value="'
                . \XoopsLocale::A_DELETE . '" onclick="this.form.elements.op.value=\'delete\'">';
        }
        $ret .= ' <input type="button" ' . $class . ' value="' . \XoopsLocale::A_CANCEL
            . '" onclick="history.go(-1);return true;" />'
            . ' <input type="reset"' . $class . ' name="reset"  id="reset" value="' . \XoopsLocale::A_RESET . '" />'
            . ' <input ' . $attributes . $this->getExtra() . ' />';
        return $ret;
    }
}
