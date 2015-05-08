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

class ImagesImage_imagemanagerForm extends Xoops\Form\ThemeForm
{

    /**
     * @param Array
     *
     *        'obj'     => ImagesImage|XoopsObject $obj
     *        'target'  => textarea id
     */
    public function __construct($param)
    {
        $xoops = Xoops::getInstance();
        $helper = Xoops\Module\Helper::getHelper('images');

        //todo, remove extract
        extract($param);

        $groups = $xoops->isUser() ? $xoops->user->getGroups() : XOOPS_GROUP_ANONYMOUS;

        if ($obj->isNew()) {
            $title = _AM_IMAGES_IMG_ADD;
        } else {
            $title = _AM_IMAGES_IMG_EDIT;
        }

        parent::__construct($title, 'image', $xoops->getEnv('PHP_SELF'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        $this->addElement(new Xoops\Form\Text(_AM_IMAGES_NAME, 'image_nicename', 50, 255, $obj->getVar('image_nicename')), true);

        $categories = $helper->getHandlerCategories()->getListByPermission($groups, 'imgcat_write');
        $select = new Xoops\Form\Select(_AM_IMAGES_CAT_SELECT, 'imgcat_id', $obj->getVar('imgcat_id'));
        $select->addOption('', _AM_IMAGES_CAT_SELECT);
        $select->addOptionArray($categories);
        $this->addElement($select, true);

        $this->addElement(new Xoops\Form\File(_AM_IMAGES_IMG_FILE, 'image_file'));

        $this->addElement(new Xoops\Form\Hidden('image_weight', $obj->getVar('image_weight')));
        $this->addElement(new Xoops\Form\Hidden('image_display', $obj->getVar('image_display')));
        $this->addElement(new Xoops\Form\Hidden('image_name', $obj->getVar('image_name')));
        $this->addElement(new Xoops\Form\Hidden('image_id', $obj->getVar('image_id')));
        $this->addElement(new Xoops\Form\Hidden('target', $target));

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

        switch (basename($xoops->getEnv('PHP_SELF'), '.php')) {
            case 'xoops_images':
                $button_3 = new Xoops\Form\Button('', 'button', XoopsLocale::A_CLOSE, 'button');
                $button_3->setExtra('onclick="tinyMCEPopup.close();"');
                $button_3->setClass('btn btn-danger');
                $button_tray->addElement($button_3);
                break;

            case 'images':
            default:
                $button_3 = new Xoops\Form\Button('', 'cancel', XoopsLocale::A_CANCEL, 'button');
                $button_3->setExtra("onclick='javascript:history.go(-1);'");
                $button_3->setClass('btn btn-danger');
                $button_tray->addElement($button_3);
                break;
        }

        $this->addElement($button_tray);
    }
}
