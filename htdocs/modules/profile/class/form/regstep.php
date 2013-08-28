<?php
/*
 You may not change or alter any portion of obj comment or credits
 of supporting developers from obj source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 obj program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

class ProfileRegstepForm extends XoopsThemeForm
{
    /**
     * @param ProfileRegstep|XoopsObject $obj
     */
    public function __construct(ProfileRegstep &$obj)
    {
        parent::__construct(_PROFILE_AM_STEP, 'stepform', 'step.php', 'post', true);
        if (!$obj->isNew()) {
            $this->addElement(new XoopsFormHidden('id', $obj->getVar('step_id')));
        }
        $this->addElement(new XoopsFormHidden('op', 'save'));
        $this->addElement(new XoopsFormText(_PROFILE_AM_STEPNAME, 'step_name', 5, 255, $obj->getVar('step_name', 'e')), true);
        $this->addElement(new XoopsFormText(_PROFILE_AM_STEPINTRO, 'step_desc', 5, 255, $obj->getVar('step_desc', 'e')));
        $order = new XoopsFormText(_PROFILE_AM_STEPORDER, 'step_order', 1, 10, $obj->getVar('step_order', 'e'), '');
        $order->setPattern('^\d+$', _PROFILE_AM_ERROR_WEIGHT);
        $this->addElement($order, true);

        $this->addElement(new XoopsFormRadioYN(_PROFILE_AM_STEPSAVE, 'step_save', $obj->getVar('step_save', 'e')));
        $this->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit', 'btn primary formButton'));
    }
}
