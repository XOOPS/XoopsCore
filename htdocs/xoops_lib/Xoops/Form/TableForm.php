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
 * TableForm - Form that will output formatted as a HTML table
 *
 * No styles and no JavaScript to check for required fields.
 *
 * @category  Xoops\Form\SimpleForm
 * @package   Xoops\Form
 * @author    Xoops Team
 * @copyright 2001-2015 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class TableForm extends Form
{
    /**
     * Insert an empty row in the table to serve as a separator.
     *
     * @return void
     */
    public function insertBreak()
    {
        $value = '<tr valign="top" align="left"><td></td></tr>';
        $ele = new Raw($value);
        $this->addElement($ele);
    }

    /**
     * create HTML to output the form as a table
     *
     * @return string
     */
    public function render()
    {
        $ret = $this->getTitle() . "\n" . '<form name="' . $this->getName() . '" id="'
            . $this->getName() . '" action="' . $this->getAction() . '" method="' . $this->getMethod()
            . '"' . $this->getExtra() . '>' . "\n" . '<table border="0" width="100%">' . "\n";
        $hidden = "";
        foreach ($this->getElements() as $ele) {
            /* @var $ele Element */
            if (!$ele->isHidden()) {
                if (!$ele instanceof Raw) {
                    $ret .= '<tr valign="top" align="left"><td>' . $ele->getCaption();
                    if ($ele_desc = $ele->getDescription()) {
                        $ret .= '<br /><br /><span style="font-weight: normal;">' . $ele_desc . '</span>';
                    }
                    $ret .= '</td><td>' . $ele->render() . '</td></tr>';
                } else {
                    $ret .= $ele->render();
                }
            } else {
                $hidden .= $ele->render() . "\n";
            }
        }
        $ret .= '</table>' . "\n" . ' ' . $hidden . '</form>' . "\n";
        return $ret;
    }
}
