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
 * XOOPS table form
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      xoopsform
 * @since           2.0.0
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Form that will output formatted as a HTML table
 *
 * No styles and no JavaScript to check for required fields.
 */
class XoopsTableForm extends XoopsForm
{
    /**
     * Insert an empty row in the table to serve as a separator.
     *
     * @param string $extra not in use.
     * @param string $class not in use
     */
    public function insertBreak($extra = '', $class = '')
    {
        $value = '<tr valign="top" align="left"><td></td></tr>';
        $ele = new XoopsFormRaw($value);
        $this->addElement($ele);
    }

    /**
     * create HTML to output the form as a table
     *
     * @return string
     */
    public function render()
    {
        $ret = $this->getTitle() . NWLINE . '<form name="' . $this->getName() . '" id="' . $this->getName() . '" action="' . $this->getAction() . '" method="' . $this->getMethod() . '"' . $this->getExtra() . '>' . NWLINE . '<table border="0" width="100%">' . NWLINE;
        $hidden = "";
        foreach ($this->getElements() as $ele) {
            /* @var $ele XoopsFormElement */
            if (!$ele->isHidden()) {
                if (!$ele instanceof XoopsFormRaw) {
                    $ret .= '<tr valign="top" align="left"><td>' . $ele->getCaption();
                    if ($ele_desc = $ele->getDescription()) {
                        $ret .= '<br /><br /><span style="font-weight: normal;">' . $ele_desc . '</span>';
                    }
                    $ret .= '</td><td>' . $ele->render() . '</td></tr>';
                } else {
                    $ret .= $ele->render();
                }
            } else {
                $hidden .= $ele->render() . NWLINE;
            }
        }
        $ret .= '</table>' . NWLINE . ' ' . $hidden . '</form>' . NWLINE;
        return $ret;
    }
}