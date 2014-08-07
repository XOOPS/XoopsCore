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
 * @copyright 2012-2014 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 * @since     2.4.0
*/
class ButtonTray extends Element
{

    /**
     * @var string
     */
    private $type;

    /**
     * @var boolean
     */
    private $showDelete;

    /**
     * __construct
     *
     * @param string  $name       name
     * @param string  $value      value
     * @param string  $type       Type of button. This could be either "button", "submit", or "reset"
     * @param string  $onclick    onClick JS code
     * @param boolean $showDelete show delete confirmation
     */
    public function __construct($name, $value = '', $type = '', $onclick = '', $showDelete = false)
    {
        $this->setName($name);
        $this->setValue($value);
        $this->type = (!empty($type)) ? $type : 'submit';
        $this->showDelete = $showDelete;
        if ($onclick) {
            $this->setExtra($onclick);
        } else {
            $this->setExtra('');
        }
    }

    /**
     * getType
     *
     * @return string type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * render
     *
     * @return string rendered button tray
     */
    public function render()
    {
        $ret = '';
        $class = ($this->getClass() != '' ? " class='" . $this->getClass() . "'" : " class='btn'");
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        if ($this->showDelete) {
            $ret .= '<input type="submit"' . $class . ' name="delete" id="delete" value="'
                . \XoopsLocale::A_DELETE . '" onclick="this.form.elements.op.value=\'delete\'"> ';
        }
        $ret .= '<input type="button" ' . $class . ' value="' . \XoopsLocale::A_CANCEL
            . '" onClick="history.go(-1);return true;" /> <input type="reset"' . $class
            . ' name="reset"  id="reset" value="' . \XoopsLocale::A_RESET . '" /> <input type="'
            . $this->getType() . '"' . $class . ' name="' . $this->getName() . '"  id="'
            . $this->getName() . '" value="' . $this->getValue() . '"' . $extra . '/>';
        return $ret;
    }
}
