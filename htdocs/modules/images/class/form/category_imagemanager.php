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
 * images module
 *
 * @copyright      2000-2020 XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @version         $Id$
 */
class ImagesCategory_imagemanagerForm extends Xoops\Form\ThemeForm
{
    /**
     * @param array $param array of parameters with these keys:
     *                      'obj'     => ImagesCategory|XoopsObject $obj
     *                      'target'  => textarea id
     */
    public function __construct($param)
    {
        $xoops = Xoops::getInstance();
        $groups = $xoops->getUserGroups();
        extract($param);
        $helper = Xoops\Module\Helper::getHelper('images');
        $categories = $helper->getHandlerCategories()->getListByPermission($groups, 'imgcat_read');

        parent::__construct('', '', $xoops->getEnv('PHP_SELF'), 'post', false, 'inline');
        $select = new Xoops\Form\Select('', 'imgcat_id', $imgcat_id);
        $select->addOption(0, _AM_IMAGES_CAT_SELECT);
        $select->addOptionArray($categories);
        if (isset($target)) {
            $select->setExtra("onchange='javascript:window.location.href=\"" . $xoops->getEnv('PHP_SELF') . '?target=' . $target . "&imgcat_id=\" + this.value'");
        } else {
            $select->setExtra("onchange='javascript:window.location.href=\"" . $xoops->getEnv('PHP_SELF') . "?imgcat_id=\" + this.value'");
        }
        $this->addElement($select);

        if (isset($target)) {
            $this->addElement(new Xoops\Form\Hidden('target', $target));
        }

        $write = $helper->getHandlerCategories()->getListByPermission($groups, 'imgcat_write');
        if ($imgcat_id > 0 && array_key_exists($imgcat_id, $write)) {
            $this->addElement(new Xoops\Form\Hidden('op', 'upload'));
            $buttonSubmit = new Xoops\Form\Button('', 'submit', _IMAGES_ADD, 'submit');
            $buttonSubmit->setClass('btn btn-success floatright');
            $this->addElement($buttonSubmit);
        }
    }
}
