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

class ImagesImageForm extends Xoops\Form\ThemeForm
{
    /**
     * @param ImagesImage|XoopsObject $obj
     */
    public function __construct(ImagesImage $obj)
    {
        $xoops = Xoops::getInstance();
        $helper = Xoops\Module\Helper::getHelper('images');

        $groups = $xoops->isUser() ? $xoops->user->getGroups() : XOOPS_GROUP_ANONYMOUS;

        if ($obj->isNew()) {
            $title = _AM_IMAGES_IMG_ADD;
        } else {
            $title = _AM_IMAGES_IMG_EDIT;
        }

        parent::__construct('', 'image', $xoops->getEnv('PHP_SELF'), 'post', true);
        $this->setExtra('enctype="multipart/form-data"');

        $tabtray = new Xoops\Form\TabTray('', 'uniqueid', $xoops->getModuleConfig('jquery_theme', 'system'));
        $tab1 = new Xoops\Form\Tab($title, 'tabid-1');

        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_NAME, 'image_nicename', 50, 255, $obj->getVar('image_nicename')), true);

        if ($obj->isNew()) {
            $categories = $helper->getHandlerCategories()->getListByPermission($groups, 'imgcat_write');
            $select = new Xoops\Form\Select(_AM_IMAGES_CAT_SELECT, 'imgcat_id', $obj->getVar('imgcat_id'));
            $select->addOption('', _AM_IMAGES_CAT_SELECT);
            $select->addOptionArray($categories);
            $tab1->addElement($select, true);
        } else {
            $tab1->addElement(new Xoops\Form\Label(_AM_IMAGES_CAT_SELECT, '<span class="red bold">' . $helper->getHandlerCategories()->get($obj->getVar('imgcat_id'))->getVar('imgcat_name') . '</span>'));
            $this->addElement(new Xoops\Form\Hidden('imgcat_id', $obj->getVar('imgcat_id')));
        }

        // warning
        $category = $helper->getHandlerCategories()->get($obj->getVar('imgcat_id'));
        $upload_msg[] = _AM_IMAGES_CAT_SIZE . ' : ' . $category->getVar('imgcat_maxsize');
        $upload_msg[] = _AM_IMAGES_CAT_WIDTH . ' : ' . $category->getVar('imgcat_maxwidth');
        $upload_msg[] = _AM_IMAGES_CAT_HEIGHT . ' : ' . $category->getVar('imgcat_maxheight');

        $image_tray = new Xoops\Form\File(_AM_IMAGES_IMG_FILE, 'image_file');
        $image_tray->setDescription(self::message($upload_msg, ''));
        $tab1->addElement($image_tray);

        $tab1->addElement(new Xoops\Form\Text(_AM_IMAGES_WEIGHT, 'image_weight', 1, 4, $obj->getVar('image_weight')));

        $tab1->addElement(new Xoops\Form\RadioYesNo(_AM_IMAGES_DISPLAY, 'image_display', $obj->getVar('image_display')));

        $tabtray->addElement($tab1);
        $this->addElement($tabtray);

        $this->addElement(new Xoops\Form\Hidden('image_name', $obj->getVar('image_name')));
        $this->addElement(new Xoops\Form\Hidden('image_id', $obj->getVar('image_id')));

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

    /**
     * @param string[] $msg
     */
    public function message($msg, $title = '', $class = 'errorMsg')
    {
        $ret = "<div class='" . $class . "'>";
        if ($title != '') {
            $ret .= "<strong>" . $title . "</strong>";
        }
        if (is_array($msg) || is_object($msg)) {
            $ret .= implode('<br />', $msg);
        } else {
            $ret .= $msg;
        }
        $ret .= "</div>";
        return $ret;
    }
}
