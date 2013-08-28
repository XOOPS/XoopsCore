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
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         avatar
 * @since           2.6.0
 * @author          Mage Gregory (AKA Mage)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class AvatarsAvatar_userForm extends XoopsThemeForm
{
    public function __construct(AvatarsAvatar $obj)
    {
        $xoops = Xoops::getInstance();
        $helper = Avatars::getInstance();
        // Get avatar handler
        $avatar_handler = $helper->getHandlerAvatar();

        $action = $_SERVER['REQUEST_URI'];
        parent::__construct(AvatarsLocale::USERFORM , 'avatar_user_form', $action, "post", true);
        $this->setExtra('enctype="multipart/form-data"');
        // select and uploads
        $avatar_select = new XoopsFormSelect('', 'user_avatar', $xoops->user->getVar('user_avatar'));
        $avatar_list = $avatar_handler->getListByType('S', true);
        $avatar_selected = $xoops->user->getVar("user_avatar", "E");
        $avatar_selected = in_array($avatar_selected, array_keys($avatar_list)) ? $avatar_selected : "blank.gif";
        $avatar_select->addOptionArray($avatar_list);
        $avatar_select->setExtra("onchange='showImgSelected(\"avatar\", \"user_avatar\", \"uploads\", \"\", \"" . XOOPS_URL . "\")'");
        $avatar_tray = new XoopsFormElementTray(XoopsLocale::FILE, '&nbsp;');
        $avatar_tray->addElement($avatar_select);
        $avatar_tray->addElement(new XoopsFormLabel('', "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . "/modules/avatars/popup.php','avatars',600,400);\">" . XoopsLocale::LIST_. "</a><br />"));
        $avatar_tray->addElement(new XoopsFormLabel('', "<br /><img src='" . XOOPS_UPLOAD_URL . "/" . $avatar_selected . "' name='avatar' id='avatar' alt='' />"));
        if ($helper->getConfig('avatars_allowupload') == 1 && $xoops->user->getVar('posts') >= $helper->getConfig('avatars_postsrequired')) {
            $fileseltray_img = new XoopsFormElementTray('<br />', '<br /><br />');
            $fileseltray_img->addElement(new XoopsFormFile(XoopsLocale::A_UPLOAD, 'user_avatar'), false);
            $avatar_tray->addElement($fileseltray_img);
        }
        $this->addElement($avatar_tray);
        // Hidden
        $this->addElement(new XoopsFormHidden('avatar_type', 'c'));
        $this->addElement(new XoopsFormHidden('uid', $xoops->user->getVar('uid')));
        $this->addElement(new XoopsFormHidden('op', 'save'));
        $this->addElement(new XoopsFormHidden('avatar_id', $obj->getVar('avatar_id', 'e')));
        // Button
        $this->addElement(new XoopsFormButton('', 'submit', XoopsLocale::A_SUBMIT, 'submit'));
    }
}