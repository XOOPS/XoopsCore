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
 * AvatarsAvatar_userForm -- is this used anywhere?
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id$
 */
class AvatarsAvatar_userForm extends Xoops\Form\ThemeForm
{
    public function __construct(AvatarsAvatar $obj)
    {
        $xoops = Xoops::getInstance();
        $helper = Avatars::getInstance();
        // Get avatar handler
        $avatar_handler = $helper->getHandlerAvatar();

        $action = $_SERVER['REQUEST_URI'];
        parent::__construct(AvatarsLocale::USERFORM, 'avatar_user_form', $action, "post", true);
        $this->setExtra('enctype="multipart/form-data"');
        // select and uploads
        $avatar_select = new Xoops\Form\Select('', 'user_avatar', $xoops->user->getVar('user_avatar'));
        $avatar_list = $avatar_handler->getListByType('S', true);
        $avatar_selected = $xoops->user->getVar("user_avatar", "E");
        $avatar_selected = in_array($avatar_selected, array_keys($avatar_list)) ? $avatar_selected : "blank.gif";
        $avatar_select->addOptionArray($avatar_list);
        $avatar_select->setExtra("onchange='showImgSelected(\"avatar\", \"user_avatar\", \"uploads\", \"\", \"" . XOOPS_URL . "\")'");
        $avatar_tray = new Xoops\Form\ElementTray(XoopsLocale::FILE, '&nbsp;');
        $avatar_tray->addElement($avatar_select);
        $avatar_tray->addElement(new Xoops\Form\Label('', "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . "/modules/avatars/popup.php','avatars',600,400);\">" . XoopsLocale::LIST_. "</a><br />"));
        $avatar_tray->addElement(new Xoops\Form\Label('', "<br /><img src='" . XOOPS_UPLOAD_URL . "/" . $avatar_selected . "' name='avatar' id='avatar' alt='' />"));
        if ($helper->getConfig('avatars_allowupload') == 1 && $xoops->user->getVar('posts') >= $helper->getConfig('avatars_postsrequired')) {
            $fileseltray_img = new Xoops\Form\ElementTray('<br />', '<br /><br />');
            $fileseltray_img->addElement(new Xoops\Form\File(XoopsLocale::A_UPLOAD, 'user_avatar'), false);
            $avatar_tray->addElement($fileseltray_img);
        }
        $this->addElement($avatar_tray);
        // Hidden
        $this->addElement(new Xoops\Form\Hidden('avatar_type', 'c'));
        $this->addElement(new Xoops\Form\Hidden('uid', $xoops->user->getVar('uid')));
        $this->addElement(new Xoops\Form\Hidden('op', 'save'));
        $this->addElement(new Xoops\Form\Hidden('avatar_id', $obj->getVar('avatar_id', 'e')));
        // Button
        $this->addElement(new Xoops\Form\Button('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    }
}
