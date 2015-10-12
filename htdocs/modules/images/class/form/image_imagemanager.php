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
 * @copyright       XOOPS Project (http://xoops.org)
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

        $groups = $xoops->getUserGroups();

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
        $buttonTray = new Xoops\Form\ElementTray('', '');
        $buttonTray->addElement(new Xoops\Form\Hidden('op', 'save'));

        $buttonSubmit = new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit');
        $buttonSubmit->setClass('btn btn-success');
        $buttonTray->addElement($buttonSubmit);

        $buttonReset = new Xoops\Form\Button('', 'reset', XoopsLocale::A_RESET, 'reset');
        $buttonReset->setClass('btn btn-warning');
        $buttonTray->addElement($buttonReset);

        switch (basename($xoops->getEnv('PHP_SELF'), '.php')) {
            case 'xoops_images':
                $buttonClose = new Xoops\Form\Button('', 'button', XoopsLocale::A_CLOSE, 'button');
                $buttonClose->setExtra('onclick="tinyMCEPopup.close();"');
                $buttonClose->setClass('btn btn-danger');
                $buttonTray->addElement($buttonClose);
                break;

            case 'images':
            default:
                $buttonCancel = new Xoops\Form\Button('', 'cancel', XoopsLocale::A_CANCEL, 'button');
                $buttonCancel->setExtra("onclick='javascript:history.go(-1);'");
                $buttonCancel->setClass('btn btn-danger');
                $buttonTray->addElement($buttonCancel);
                break;
        }

        $this->addElement($buttonTray);
    }
}
