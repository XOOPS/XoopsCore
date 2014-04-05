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
 * XOOPS simple form
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Form that will output as a simple HTML form with minimum formatting
 */
class XoopsSimpleForm extends XoopsForm
{
    /**
     * Insert an empty row in the table to serve as a separator.
     *
     * @param string $extra not in use.
     * @param string $class not in use
     */
    public function insertBreak($extra = '', $class = '')
    {
        $value = '</ br>';
        $ele = new XoopsFormRaw($value);
        $this->addElement($ele);
    }

    /**
     * create HTML to output the form with minimal formatting
     *
     * @return string
     */
    public function render()
    {
        $ret = $this->getTitle() . "\n<form name='" . $this->getName() . "' id='" . $this->getName() . "' action='" . $this->getAction() . "' method='" . $this->getMethod() . "'" . $this->getExtra() . ">\n";
        foreach ($this->getElements() as $ele) {
            /* @var $ele XoopsFormElement */
            if (!$ele->isHidden()) {
                if (!$ele instanceof XoopsFormRaw) {
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