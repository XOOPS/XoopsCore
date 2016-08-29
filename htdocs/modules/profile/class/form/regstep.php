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
 * @copyright 2000-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author    trabis <lusopoemas@gmail.com>
 */

class ProfileRegstepForm extends Xoops\Form\ThemeForm
{
    /**
     * @param ProfileRegstep|XoopsObject $obj
     */
    public function __construct(ProfileRegstep $obj)
    {
        parent::__construct(_PROFILE_AM_STEP, 'stepform', 'step.php', 'post', true);
        if (!$obj->isNew()) {
            $this->addElement(new Xoops\Form\Hidden('id', $obj->getVar('step_id')));
        }
        $this->addElement(new Xoops\Form\Hidden('op', 'save'));
        $this->addElement(new Xoops\Form\Text(_PROFILE_AM_STEPNAME, 'step_name', 5, 255, $obj->getVar('step_name', 'e')), true);
        $this->addElement(new Xoops\Form\Text(_PROFILE_AM_STEPINTRO, 'step_desc', 5, 255, $obj->getVar('step_desc', 'e')));
        $order = new Xoops\Form\Text(_PROFILE_AM_STEPORDER, 'step_order', 1, 10, $obj->getVar('step_order', 'e'), '');
        $order->setPattern('^\d+$', _PROFILE_AM_ERROR_WEIGHT);
        $this->addElement($order, true);

        $this->addElement(new Xoops\Form\RadioYesNo(_PROFILE_AM_STEPSAVE, 'step_save', $obj->getVar('step_save', 'e')));
        $this->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    }
}
