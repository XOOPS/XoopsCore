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
 * XOOPS Form element of button tray
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.4.0
 * @author          John Neill <catzwolf@xoops.org>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsFormButtonTray extends XoopsFormElement
{

    /**
     * Type of the button. This could be either "button", "submit", or "reset"
     *
     * @var string
     */
    private $_type;

    /**
     * XoopsFormButtonTray::XoopsFormButtonTray()
     *
     * @param mixed $name
     * @param string $value
     * @param string $type
     * @param string $onclick
     * @param bool $showDelete
     */
    function __construct($name, $value = '', $type = '', $onclick = '', $showDelete = false)
    {
        $this->setName($name);
        $this->setValue($value);
        $this->_type = (!empty($type)) ? $type : 'submit';
        $this->_showDelete = $showDelete;
        if ($onclick) {
            $this->setExtra($onclick);
        } else {
            $this->setExtra('');
        }
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function render()
    {
        $ret = '';
        $class = ($this->getClass() != '' ? " class='" . $this->getClass() . "'" : " class='btn'");
        $extra = ($this->getExtra() != '' ? " " . $this->getExtra() : '');
        if ($this->_showDelete) {
            $ret .= '<input type="submit"' . $class . ' name="delete" id="delete" value="' . XoopsLocale::A_DELETE . '" onclick="this.form.elements.op.value=\'delete\'"> ';
        }
        $ret .= '<input type="button" ' . $class . ' value="' . XoopsLocale::A_CANCEL . '" onClick="history.go(-1);return true;" /> <input type="reset"' . $class . ' name="reset"  id="reset" value="' . XoopsLocale::A_RESET . '" /> <input type="' . $this->getType() . '"' . $class . ' name="' . $this->getName() . '"  id="' . $this->getName() . '" value="' . $this->getValue() . '"' . $extra . '/>';
        return $ret;
    }
}