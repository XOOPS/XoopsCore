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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class ImagesCategoryForm extends Xoops\Form\ThemeForm
{
    /**
     * @param ImagesCategory|XoopsObject $obj
     */
    public function __construct(ImagesCategory $obj)
    {
        $xoops = Xoops::getInstance();

        $perm_handler = $xoops->getHandlerGroupperm();

        if ($obj->isNew()) {
            $title = _AM_IMAGES_CAT_ADD;
            $read = XOOPS_GROUP_ADMIN;
            $write = XOOPS_GROUP_ADMIN;
        } else {
            $title = _AM_IMAGES_CAT_EDIT;
            $read = $perm_handler->getGroupIds('imgcat_read', $obj->getVar('imgcat_id'), $xoops->module->getVar('mid'));
            $write = $perm_handler->getGroupIds('imgcat_write', $obj->getVar('imgcat_id'), $xoops->module->getVar('mid'));
        }

        parent::__construct($title, 'imagecat_form', $xoops->getEnv('PHP_SELF'), 'post', true);

        $tabtray = new Xoops\Form\TabTray('', 'uniqueid', $xoops->getModuleConfig('jquery_theme', 'system'));

        $tab1 = new Xoops\Form\Tab(_MI_IMAGES_CATEGORIES, 'tabid-1');
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_CAT_NAME, 'imgcat_name', 50, 255, $obj->getVar('imgcat_name')), true);
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_CAT_SIZE, 'imgcat_maxsize', 2, 8, $obj->getVar('imgcat_maxsize')));
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_CAT_WIDTH, 'imgcat_maxwidth', 1, 4, $obj->getVar('imgcat_maxwidth')));
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_CAT_HEIGHT, 'imgcat_maxheight', 1, 4, $obj->getVar('imgcat_maxheight')));
        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_WEIGHT, 'imgcat_weight', 1, 5, $obj->getVar('imgcat_weight')));
        $tab1->addElement(new Xoops\Form\RadioYesNo(_AM_IMAGES_CAT_DISPLAY, 'imgcat_display', $obj->getVar('imgcat_display')));
        if ($obj->isNew()) {
            $store = new Xoops\Form\Radio(_AM_IMAGES_CAT_STR_TYPE . '<div class="red">' . _AM_IMAGES_CAT_STR_TYOPENG . '</div>', 'imgcat_storetype', 'file');
            $store->addOptionArray(array('file' => _AM_IMAGES_CAT_ASFILE, 'db' => _AM_IMAGES_CAT_INDB));
            $tab1->addElement($store);
        } else {
            $store = array('db' => _AM_IMAGES_CAT_INDB, 'file' => _AM_IMAGES_CAT_ASFILE);
            $tab1->addElement(new Xoops\Form\Label(_AM_IMAGES_CAT_STR_TYPE, $store[$obj->getVar('imgcat_storetype')]));
            $this->addElement(new Xoops\Form\Hidden('imgcat_storetype', $obj->getVar('imgcat_storetype')));
        }

        $tab2 = new Xoops\Form\Tab(_MI_IMAGES_PERMISSIONS, 'tabid-2');
        $tab2->addElement(new Xoops\Form\SelectGroup(_AM_IMAGES_CAT_READ_GRP, 'readgroup', true, $read, 5, true));
        $tab2->addElement(new Xoops\Form\SelectGroup(_AM_IMAGES_CAT_WRITE_GRP, 'writegroup', true, $write, 5, true));

        $tabtray->addElement($tab1);
        $tabtray->addElement($tab2);
        $this->addElement($tabtray);

        $this->addElement(new Xoops\Form\Hidden('imgcat_id', $obj->getVar('imgcat_id')));

        /**
         * Buttons
         */
        $button_tray = new Xoops\Form\ElementTray('', '');
        $button_tray->addElement(new Xoops\Form\Hidden('op', 'save'));

        $button = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
        $button->setClass('btn btn-success');
        $button_tray->addElement($button);

        $button_2 = new Xoops\Form\Button('', 'reset', XoopsLocale::A_RESET, 'reset');
        $button_2->setClass('btn btn-warning');
        $button_tray->addElement($button_2);

        $button_3 = new Xoops\Form\Button('', 'cancel', XoopsLocale::A_CANCEL, 'button');
        $button_3->setExtra("onclick='javascript:history.go(-1);'");
        $button_3->setClass('btn btn-danger');
        $button_tray->addElement($button_3);

        $this->addElement($button_tray);
    }
}
