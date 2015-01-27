<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Request;

/**
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class CommentsCommentForm extends Xoops\Form\ThemeForm
{
    /**
     * @param CommentsComment $obj
     */
    public function __construct(CommentsComment $obj)
    {
        $xoops = Xoops::getInstance();
        $helper = Comments::getInstance();
        $module = $xoops->getModuleById($obj->getVar('modid'));
        if (!is_object($module)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }
        $dirname = $module->getVar('dirname');

        // create form
        if ($xoops->isAdminSide) {
            $url = $helper->url("admin/comment_post.php");
        } else {
            $url = $helper->url("comment_post.php");
        }
        parent::__construct(_MD_COMMENTS_POSTCOMMENT, "commentform", $url, "post", true);

        switch ($xoops->getModuleConfig('com_rule', $dirname)) {
            case COMMENTS_APPROVEALL:
                $rule_text = _MD_COMMENTS_COMAPPROVEALL;
                break;
            case COMMENTS_APPROVEUSER:
                $rule_text = _MD_COMMENTS_COMAPPROVEUSER;
                break;
            case COMMENTS_APPROVEADMIN:
            default:
                $rule_text = _MD_COMMENTS_COMAPPROVEADMIN;
                break;
        }
        $this->addElement(new Xoops\Form\Label(_MD_COMMENTS_COMRULES, $rule_text));

        $this->addElement(new Xoops\Form\Text(_MD_COMMENTS_TITLE, 'com_title', 50, 255, $obj->getVar('title', 'e')), true);
        $icons_radio = new Xoops\Form\Radio(XoopsLocale::MESSAGE_ICON, 'com_icon', $obj->getVar('icon', 'e'));
        $subject_icons = XoopsLists::getSubjectsList();
        foreach ($subject_icons as $iconfile) {
            $icons_radio->addOption($iconfile, '<img src="' . XOOPS_URL . '/images/subject/' . $iconfile . '" alt="" />');
        }
        $this->addElement($icons_radio);
        // editor
        $editor = $helper->getConfig('com_editor');
        if (class_exists('Xoops\Form\Editor')) {
            $configs = array(
                'name'   => 'com_text',
                'value'  => $obj->getVar('text', 'e'),
                'rows'   => 25,
                'cols'   => 90,
                'width'  => '100%',
                'height' => '400px',
                'editor' => $editor
            );
            $this->addElement(new Xoops\Form\Editor(_MD_COMMENTS_MESSAGE, 'com_text', $configs, false, $onfailure = 'textarea'));
        } else {
            $this->addElement(new Xoops\Form\DhtmlTextArea(_MD_COMMENTS_MESSAGE, 'com_text', $obj->getVar('text', 'e'), 10, 50), true);
        }
        $option_tray = new Xoops\Form\ElementTray(XoopsLocale::OPTIONS, '<br />');
        $button_tray = new Xoops\Form\ElementTray('', '&nbsp;');

        if ($xoops->isUser()) {
            if ($xoops->getModuleConfig('com_anonpost', $dirname)) {
                    $noname = $obj->getVar('noname', 'e') ? 1 : 0;
                    $noname_checkbox = new Xoops\Form\Checkbox('', 'com_noname', $noname);
                    $noname_checkbox->addOption(1, XoopsLocale::POST_ANONYMOUSLY);
                    $option_tray->addElement($noname_checkbox);
            }
            if (false != $xoops->user->isAdmin($obj->getVar('modid'))) {
                // show status change box when editing (comment id is not empty)
                if ($obj->getVar('id', 'e')) {
                    $status_select = new Xoops\Form\Select(_MD_COMMENTS_STATUS, 'com_status', $obj->getVar('status', 'e'));
                    $status_select->addOptionArray(array(
                        COMMENTS_PENDING => _MD_COMMENTS_PENDING,
                        COMMENTS_ACTIVE  => _MD_COMMENTS_ACTIVE,
                        COMMENTS_HIDDEN  => _MD_COMMENTS_HIDDEN
                    ));
                    $this->addElement($status_select);
                    $button_tray->addElement(new Xoops\Form\Button('', 'com_dodelete', XoopsLocale::A_DELETE, 'submit'));
                }
                if (isset($editor) && in_array($editor, array('textarea', 'dhtmltextarea'))) {
                    $html_checkbox = new Xoops\Form\Checkbox('', 'com_dohtml', $obj->getVar('dohtml', 'e'));
                    $html_checkbox->addOption(1, _MD_COMMENTS_DOHTML);
                    $option_tray->addElement($html_checkbox);
                }
            }
        }
        if (isset($editor) && in_array($editor, array('textarea', 'dhtmltextarea'))) {
            //Yeah, what?
        }
        $smiley_checkbox = new Xoops\Form\Checkbox('', 'com_dosmiley', $obj->getVar('domsiley', 'e'));
        $smiley_checkbox->addOption(1, _MD_COMMENTS_DOSMILEY);
        $option_tray->addElement($smiley_checkbox);
        $xcode_checkbox = new Xoops\Form\Checkbox('', 'com_doxcode', $obj->getVar('doxcode', 'e'));
        $xcode_checkbox->addOption(1, _MD_COMMENTS_DOXCODE);
        $option_tray->addElement($xcode_checkbox);
        if (isset($editor) && in_array($editor, array('textarea', 'dhtmltextarea'))) {
            $br_checkbox = new Xoops\Form\Checkbox('', 'com_dobr', $obj->getVar('dobr', 'e'));
            $br_checkbox->addOption(1, _MD_COMMENTS_DOAUTOWRAP);
            $option_tray->addElement($br_checkbox);
        } else {
            $this->addElement(new Xoops\Form\Hidden('com_dohtml', 1));
            $this->addElement(new Xoops\Form\Hidden('com_dobr', 0));
        }
        $this->addElement($option_tray);
        if (!$xoops->isUser()) {
            $this->addElement(new Xoops\Form\Captcha());
        }
        $this->addElement(new Xoops\Form\Hidden('com_modid', $obj->getVar('modid', 'e')));
        $this->addElement(new Xoops\Form\Hidden('com_pid', $obj->getVar('pid', 'e')));
        $this->addElement(new Xoops\Form\Hidden('com_rootid', $obj->getVar('rootid', 'e')));
        $this->addElement(new Xoops\Form\Hidden('com_id', $obj->getVar('id', 'e')));
        $this->addElement(new Xoops\Form\Hidden('com_itemid', $obj->getVar('itemid', 'e')));
        $this->addElement(new Xoops\Form\Hidden('com_order', Request::getInt('com_order', $helper->getUserConfig('com_order'))));
        $this->addElement(new Xoops\Form\Hidden('com_mode', Request::getString('com_mode', $helper->getUserConfig('com_mode'))));

        // add module specific extra params
        if (!$xoops->isAdminSide) {
            /* @var $plugin CommentsPluginInterface */
            $plugin = \Xoops\Module\Plugin::getPlugin($dirname, 'comments');
            if (is_array($extraParams = $plugin->extraParams())) {
                $myts = MyTextSanitizer::getInstance();
                foreach ($extraParams as $extra_param) {
                    // This routine is included from forms accessed via both GET and POST
                    if (isset($_POST[$extra_param])) {
                        $hidden_value = $myts->stripSlashesGPC($_POST[$extra_param]);
                    } else {
                        if (isset($_GET[$extra_param])) {
                            $hidden_value = $myts->stripSlashesGPC($_GET[$extra_param]);
                        } else {
                            $hidden_value = '';
                        }
                    }
                    $this->addElement(new Xoops\Form\Hidden($extra_param, $hidden_value));
                }
            }
        }
        $button_tray->addElement(new Xoops\Form\Button('', 'com_dopreview', XoopsLocale::A_PREVIEW, 'submit'));
        $button_tray->addElement(new Xoops\Form\Button('', 'com_dopost', _MD_COMMENTS_POSTCOMMENT, 'submit'));
        $this->addElement($button_tray);
        return $this;
    }
}
