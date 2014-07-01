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
 * XOOPS theme form
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
 * Form that will output as a theme-enabled HTML table
 *
 * Also adds JavaScript to validate required fields
 */
class XoopsThemeForm extends XoopsForm
{
    /**
     * Insert an empty row in the table to serve as a separator.
     *
     * @param string $extra HTML to be displayed in the empty row.
     * @param string $class CSS class name for <td> tag
     */
    public function insertBreak($extra = '', $class = '')
    {
        $class = ($class != '' ? " class='" . $class . "'" : " class='break'");
        // Fix for $extra tag not showing
        if ($extra) {
            $value = '<div' . $class . '>' . $extra . '</div>';
            $ele = new XoopsFormRaw($value);
            $this->addElement($ele);
        } else {
            $value = '<div' . $class . '>&nbsp;</div>';
            $ele = new XoopsFormRaw($value);
            $this->addElement($ele);
        }
    }

    /**
     * create HTML to output the form as a theme-enabled table with validation.
     */
    public function render()
    {
        $xoops = Xoops::getInstance();
        $xoops->theme()->addStylesheet('media/xoops/css/form.css');
        switch ($this->getDisplay()) {
            case '':
            case 'horizontal':
            default:
                $xoops->tpl()->assign('type', 'horizontal');
                break;

            case 'vertical':
                $xoops->tpl()->assign('type', 'vertical');
                break;

            case 'inline':
                $xoops->tpl()->assign('type', 'inline');
                break;

            case 'personalized':
                $xoops->tpl()->assign('type', 'personalized');
                break;
        }
        $xoops->tpl()->assign('title', $this->getTitle());
        $xoops->tpl()->assign('name', $this->getName());
        $xoops->tpl()->assign('action', $this->getAction());
        $xoops->tpl()->assign('method', $this->getMethod());
        $xoops->tpl()->assign('extra', $this->getExtra());
        $hidden = '';
        foreach ($this->getElements() as $ele) {
            /* @var $ele XoopsFormElement */
            if (!$ele->isHidden()) {
                $input['name'] = $ele->getName();
                $input['caption'] = $ele->getCaption();
                $input['description'] = $ele->getDescription();
                $input['ele'] = $ele->render();
                $input['required'] = $ele->isRequired();
                $input['pattern_description'] = $ele->getPatternDescription();
                $input['datalist'] = $ele->getDatalist();
                $xoops->tpl()->append_by_ref('xo_input', $input);
                unset($input);
            } else {
                $hidden .= $ele->render(). NWLINE;
            }

        }
        $xoops->tpl()->assign('hidden', $hidden);
        $xoops->tpl()->assign('validationJS', $this->renderValidationJS(true));
        $ret = $xoops->tpl()->fetch('module:system|system_form.html');
        $xoops->tpl()->clear_assign('xo_input');
        return $ret;

    }
}