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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author          Andricq Nicolas (AKA MusS)
 * @version         $Id$
 */

class ImagesImage_imagemanagerForm extends XoopsThemeForm
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

        $this->addElement(new XoopsFormText(_AM_IMAGES_NAME, 'image_nicename', 50, 255, $obj->getVar('image_nicename')), true);

        $categories = $helper->getHandlerCategories()->getListByPermission($groups, 'imgcat_write');
        $select = new XoopsFormSelect(_AM_IMAGES_CAT_SELECT, 'imgcat_id', $obj->getVar('imgcat_id'));
        $select->addOption('', _AM_IMAGES_CAT_SELECT);
        $select->addOptionArray($categories);
        $this->addElement($select, true);

        $this->addElement(new XoopsFormFile(_AM_IMAGES_IMG_FILE, 'image_file', 5000000));

        $this->addElement(new XoopsFormHidden('image_weight', $obj->getVar('image_weight')));
        $this->addElement(new XoopsFormHidden('image_display', $obj->getVar('image_display')));
        $this->addElement(new XoopsFormHidden('image_name', $obj->getVar('image_name')));
        $this->addElement(new XoopsFormHidden('image_id', $obj->getVar('image_id')));
        $this->addElement(new XoopsFormHidden('target', $target));

        /**
         * Buttons
         */
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('op', 'save'));

        $button = new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
        $button->setClass('btn btn-success');
        $button_tray->addElement($button);

        $button_2 = new XoopsFormButton('', 'reset', XoopsLocale::A_RESET, 'reset');
        $button_2->setClass('btn btn-warning');
        $button_tray->addElement($button_2);

        switch (basename($xoops->getEnv('PHP_SELF'), '.php')) {
            case 'xoops_images':
                $button_3 = new XoopsFormButton('', 'button', XoopsLocale::A_CLOSE, 'button');
                $button_3->setExtra('onclick="tinyMCEPopup.close();"');
                $button_3->setClass('btn btn-danger');
                $button_tray->addElement($button_3);
                break;

            case 'images':
            default:
                $button_3 = new XoopsFormButton('', 'cancel', XoopsLocale::A_CANCEL, 'button');
                $button_3->setExtra("onclick='javascript:history.go(-1);'");
                $button_3->setClass('btn btn-danger');
                $button_tray->addElement($button_3);
                break;
        }

        $this->addElement($button_tray);
    }
}
