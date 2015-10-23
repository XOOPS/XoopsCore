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
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage)
 * @version         $Id$
 */

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
		$avatars_path = \XoopsBaseConfig::get('uploads-path') . '/avatars';
        $imageselect_img = new Xoops\Form\Select(sprintf(XoopsLocale::F_FILE_EXISTS_IN, $avatars_path . '/'), 'avatar_file', $blank_img);
        $imageselect_img->addOption($blank_img, $blank_img);
        \Xoops\Core\Lists\ImageFile::setOptionsArray($imageselect_img, $avatars_path);
		$xoops_upload_url = \XoopsBaseConfig::get('uploads-url');
        $imageselect_img->setExtra("onchange='showImgSelected(\"xo-avatar-img\", \"avatar_file\", \"avatars\", \"\", \"" . $xoops_upload_url . "\")'");
        $imgtray_img->addElement($imageselect_img, false);
        $imgtray_img->addElement(new Xoops\Form\Label('', "<br /><img src='" . $xoops_upload_url . "/avatars/" . $blank_img . "' name='image_img' id='xo-avatar-img' alt='' />"));
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
