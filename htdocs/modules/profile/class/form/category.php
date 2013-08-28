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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class ProfileCategoryForm extends XoopsThemeForm
{
    /**
     * @param ProfileCategory|XoopsObject $obj
     */
    public function __construct(ProfileCategory &$obj)
    {
        $title = $obj->isNew() ? sprintf(_PROFILE_AM_ADD, _PROFILE_AM_CATEGORY) : sprintf(_PROFILE_AM_EDIT, _PROFILE_AM_CATEGORY);

        parent::__construct($title, 'form', false, 'post', true);
        $this->addElement(new XoopsFormText(_PROFILE_AM_TITLE, 'cat_title', 5, 255, $obj->getVar('cat_title')), true);
        if (!$obj->isNew()) {
            //Load groups
            $this->addElement(new XoopsFormHidden('id', $obj->getVar('cat_id')));
        }
        $this->addElement(new XoopsFormTextArea(_PROFILE_AM_DESCRIPTION, 'cat_description', $obj->getVar('cat_description', 'e'), 5, 5));
        $weight = new XoopsFormText(_PROFILE_AM_WEIGHT, 'cat_weight', 1, 5, $obj->getVar('cat_weight', 'e'), '');
        $weight->setPattern('^\d+$', _PROFILE_AM_ERROR_WEIGHT);
        $this->addElement($weight, true);

        $this->addElement(new XoopsFormHidden('op', 'save') );
        $this->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit', 'btn primary formButton'));
    }
}