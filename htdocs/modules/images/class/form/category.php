<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\FixedGroups;

/**
 * images module
 *
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @version         $Id$
 */
class ImagesCategoryForm extends Xoops\Form\ThemeForm
{
    /**
     * @param ImagesCategory|XoopsObject $obj
     */
    public function __construct(ImagesCategory $obj)
    {
        $xoops = Xoops::getInstance();

        $perm_handler = $xoops->getHandlerGroupPermission();

        if ($obj->isNew()) {
            $title = _AM_IMAGES_CAT_ADD;
            $read = FixedGroups::ADMIN;
            $write = FixedGroups::ADMIN;
        } else {
            $title = _AM_IMAGES_CAT_EDIT;
            $read = $perm_handler->getGroupIds('imgcat_read', $obj->getVar('imgcat_id'), $xoops->module->getVar('mid'));
            $write = $perm_handler->getGroupIds('imgcat_write', $obj->getVar('imgcat_id'), $xoops->module->getVar('mid'));
        }

        parent::__construct($title, 'imagecat_form', $xoops->getEnv('PHP_SELF'), 'post', true);

        $tabTray = new Xoops\Form\TabTray('', 'uniqueid');

        $tab1 = new Xoops\Form\Tab(_MI_IMAGES_CATEGORIES, 'tabid-1');
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_CAT_NAME, 'imgcat_name', 50, 255, $obj->getVar('imgcat_name')), true);
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_CAT_SIZE, 'imgcat_maxsize', 2, 8, $obj->getVar('imgcat_maxsize')));
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_CAT_WIDTH, 'imgcat_maxwidth', 1, 4, $obj->getVar('imgcat_maxwidth')));
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_CAT_HEIGHT, 'imgcat_maxheight', 1, 4, $obj->getVar('imgcat_maxheight')));
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_WEIGHT, 'imgcat_weight', 1, 5, $obj->getVar('imgcat_weight')));
        $tab1->addElement(new Xoops\Form\RadioYesNo(_AM_IMAGES_CAT_DISPLAY, 'imgcat_display', $obj->getVar('imgcat_display')));
        if ($obj->isNew()) {
            $store = new Xoops\Form\Radio(_AM_IMAGES_CAT_STR_TYPE . '<div class="red">' . _AM_IMAGES_CAT_STR_TYOPENG . '</div>', 'imgcat_storetype', 'file');
            $store->addOptionArray(['file' => _AM_IMAGES_CAT_ASFILE, 'db' => _AM_IMAGES_CAT_INDB]);
            $tab1->addElement($store);
        } else {
            $store = ['db' => _AM_IMAGES_CAT_INDB, 'file' => _AM_IMAGES_CAT_ASFILE];
            $tab1->addElement(new Xoops\Form\Label(_AM_IMAGES_CAT_STR_TYPE, $store[$obj->getVar('imgcat_storetype')]));
            $this->addElement(new Xoops\Form\Hidden('imgcat_storetype', $obj->getVar('imgcat_storetype')));
        }

        $tab2 = new Xoops\Form\Tab(_MI_IMAGES_PERMISSIONS, 'tabid-2');
        $tab2->addElement(new Xoops\Form\SelectGroup(_AM_IMAGES_CAT_READ_GRP, 'readgroup', true, $read, 5, true));
        $tab2->addElement(new Xoops\Form\SelectGroup(_AM_IMAGES_CAT_WRITE_GRP, 'writegroup', true, $write, 5, true));

        $tabTray->addElement($tab1);
        $tabTray->addElement($tab2);
        $this->addElement($tabTray);

        $this->addElement(new Xoops\Form\Hidden('imgcat_id', $obj->getVar('imgcat_id')));

        /**
         * Buttons
         */
        $buttonTray = new Xoops\Form\ElementTray('', '');
        $buttonTray->addElement(new Xoops\Form\Hidden('op', 'save'));

        $buttonSubmit = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
        $buttonSubmit->setClass('btn btn-success');
        $buttonTray->addElement($buttonSubmit);

        $buttonReset = new Xoops\Form\Button('', 'reset', XoopsLocale::A_RESET, 'reset');
        $buttonReset->setClass('btn btn-warning');
        $buttonTray->addElement($buttonReset);

        $buttonCancel = new Xoops\Form\Button('', 'cancel', XoopsLocale::A_CANCEL, 'button');
        $buttonCancel->set('onclick', 'javascript:history.go(-1);');
        $buttonCancel->setClass('btn btn-danger');
        $buttonTray->addElement($buttonCancel);

        $this->addElement($buttonTray);
    }
}
