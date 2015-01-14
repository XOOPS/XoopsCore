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
 * avatars module
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class AvatarsAvatarForm extends Xoops\Form\ThemeForm
{
    public function __construct(AvatarsAvatar $obj)
    {
        if ($obj->isNew()) {
            $blank_img = 'blank.gif';
        } else {
            $blank_img = str_replace('avatars/', '', $obj->getVar('avatar_file', 'e'));
        }
        //$xoops = Xoops::getInstance();
        // New and edit form
        $title = $obj->isNew() ? XoopsLocale::A_ADD : XoopsLocale::A_EDIT;

        $action = $_SERVER['REQUEST_URI'];
        parent::__construct($title, 'avatar_form', $action, "post", true);
        $this->setExtra('enctype="multipart/form-data"');
        // Name
        $this->addElement(new Xoops\Form\Text(XoopsLocale::NAME, 'avatar_name', 5, 255, $obj->getVar('avatar_name', 'e')), true);
        // Upload part
        $imgtray_img = new Xoops\Form\ElementTray(XoopsLocale::FILE, '<br />');
        $imageselect_img = new Xoops\Form\Select(sprintf(XoopsLocale::F_FILE_EXISTS_IN, XOOPS_UPLOAD_PATH . '/avatars/'), 'avatar_file', $blank_img);
        $image_array_img = XoopsLists::getImgListAsArray(XOOPS_UPLOAD_PATH . '/avatars');
        $imageselect_img->addOption("$blank_img", $blank_img);
        foreach ($image_array_img as $image_img) {
            $imageselect_img->addOption("$image_img", $image_img);
        }
        $imageselect_img->setExtra("onchange='showImgSelected(\"xo-avatar-img\", \"avatar_file\", \"avatars\", \"\", \"" . XOOPS_UPLOAD_URL . "\")'");
        $imgtray_img->addElement($imageselect_img, false);
        $imgtray_img->addElement(new Xoops\Form\Label('', "<br /><img src='" . XOOPS_UPLOAD_URL . "/avatars/" . $blank_img . "' name='image_img' id='xo-avatar-img' alt='' />"));
        $fileseltray_img = new Xoops\Form\ElementTray('<br />', '<br /><br />');
        $fileseltray_img->addElement(new Xoops\Form\File(XoopsLocale::A_UPLOAD, 'avatar_file'), false);
        // , $xoops->getModuleConfig('avatars_imagefilesize')
        $imgtray_img->addElement($fileseltray_img);
        $this->addElement($imgtray_img);
        // Weight
        $weight = new Xoops\Form\Text(XoopsLocale::DISPLAY_ORDER, 'avatar_weight', 1, 4, $obj->getVar('avatar_weight', 'e'), '');
        $weight->setPattern('^\d+$', XoopsLocale::E_YOU_NEED_A_POSITIVE_INTEGER);
        $this->addElement($weight, true);
        // Display
        $this->addElement(new Xoops\Form\RadioYesNo(XoopsLocale::DISPLAY_THIS_ITEM, 'avatar_display', $obj->getVar('avatar_display', 'e'), XoopsLocale::YES, XoopsLocale::NO));
        // Hidden
        if ($obj->isNew()) {
            $this->addElement(new Xoops\Form\Hidden('avatar_type', 's'));
        }
        $this->addElement(new Xoops\Form\Hidden('op', 'save'));
        $this->addElement(new Xoops\Form\Hidden('avatar_id', $obj->getVar('avatar_id', 'e')));
        // Button
        $this->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    }
}
