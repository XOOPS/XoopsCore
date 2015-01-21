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
 * @version         $Id$
 */

class Comments extends Xoops\Module\Helper\HelperAbstract
{
    /**
     * Init the module
     *
     * @return null|void
     */
    public function init()
    {
        $this->setDirname('comments');
        $this->loadLanguage('main');
        define('COMMENTS_APPROVENONE', 0);
        define('COMMENTS_APPROVEALL', 1);
        define('COMMENTS_APPROVEUSER', 2);
        define('COMMENTS_APPROVEADMIN', 3);
        define('COMMENTS_PENDING', 1);
        define('COMMENTS_ACTIVE', 2);
        define('COMMENTS_HIDDEN', 3);
        define('COMMENTS_OLD1ST', 0);
        define('COMMENTS_NEW1ST', 1);
    }

    /**
     * @return string
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return CommentsCommentHandler
     */
    public function getHandlerComment()
    {
        return $this->getHandler('comment');
    }

    /**
     * @param string $config
     *
     * @return mixed
     */
    public function getUserConfig($config)
    {
        static $configs = array();
        static $fetched = false;
        /* @var $helper Userconfigs */
        if (!$fetched && $this->xoops()->isUser() && $helper = $this->xoops()->getModuleHelper('userconfigs')) {
            $config_handler = $helper->getHandlerConfig();
            $configs = $config_handler->getConfigsByUser($this->xoops()->user->getVar('uid'), $this->getModule()->getVar('mid'));
        }
        $fetched = true;
        return isset($configs[$config]) ? $configs[$config] : $this->getConfig($config);
    }

    /**
     * @param CommentsComment $obj
     */
    public function displayCommentForm(CommentsComment $obj)
    {
        $this->getForm($obj, 'comment')->display();
    }

    public function displayNew()
    {
        $xoops = Xoops::getInstance();
        /* @var $obj CommentsComment */
        $obj = $this->getHandlerComment()->create();

        $itemid = Request::getInt('com_itemid');
        $modid = Request::getInt('com_modid');

        if (empty($modid)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        $module = $xoops->getModuleById($modid);
        if (!is_object($module)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        if ((!$xoops->isAdminSide && COMMENTS_APPROVENONE == $xoops->getModuleConfig('com_rule', $module->getVar('dirname'))) || (!$xoops->isUser() && !$xoops->getModuleConfig('com_anonpost', $module->getVar('dirname'))) || !$xoops->isModule()) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }
        /* @var $plugin CommentsPluginInterface */
        if (($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'comments')) && $itemid > 0 && $modid > 0) {
            $xoops->header();
            $title = '';
            $text = '';
            $uid = 0;
            $timestamp = 0;
            if (is_array($itemInfo = $plugin->itemInfo($itemid))) {
                $title = isset($itemInfo['title']) ? $itemInfo['title'] : $title;
                $text = isset($itemInfo['text']) ? $itemInfo['text'] : $text;
                $uid = isset($itemInfo['uid']) ? $itemInfo['uid'] : $uid;
                $timestamp = isset($itemInfo['timestamp']) ? $itemInfo['timestamp'] : $timestamp;
            }

            echo $this->renderHeader($title, $text, $uid, $timestamp);

            if (!preg_match("/^" . XoopsLocale::C_RE . "/i", $title)) {
                $title = XoopsLocale::C_RE . " " . XoopsLocale::substr($title, 0, 56);
            }

            $obj->setVar('itemid', $itemid);
            $obj->setVar('title', $title);
            $obj->setVar('modid', $modid);

            $this->displayCommentForm($obj);
            $xoops->footer();
        }
        $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
    }

    public function displayPost()
    {
        $xoops = Xoops::getInstance();
        if (Request::getMethod()!=='POST') {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }
        $id = Request::getInt('com_id');
        $modid = Request::getInt('com_modid');
        if (empty($modid)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        /* @var $comment CommentsComment */
        $comment = $this->getHandlerComment()->get($id);
        if (!is_object($comment)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        if (!$comment->isNew()) {
            $modid = $comment->getVar('modid');
        } else {
            $comment->setVar('modid', $modid);
        }

        $module = $xoops->getModuleById($modid);
        if (!is_object($module)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        $moddir = $module->getVar('dirname');

        if ($xoops->isAdminSide) {
            if (empty($id)) {
                $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
            }
            $redirect_page = $this->url('admin/main.php?com_modid=' . $modid . '&amp;com_itemid');
        } else {
            if (COMMENTS_APPROVENONE == $xoops->getModuleConfig('com_rule', $module->getVar('dirname'))) {
                $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
            }
            $redirect_page = '';
        }

        /* @var $plugin CommentsPluginInterface */
        if ($plugin = \Xoops\Module\Plugin::getPlugin($moddir, 'comments')) {
            if (!$xoops->isAdminSide) {
                $redirect_page = $xoops->url('modules/' . $moddir . '/' . $plugin->pageName() . '?');
                if (is_array($extraParams = $plugin->extraParams())) {
                    $extra_params = '';
                    foreach ($extraParams as $extra_param) {
                        $extra_params .= isset($_POST[$extra_param]) ? $extra_param . '=' . htmlspecialchars($_POST[$extra_param]) . '&amp;' : $extra_param . '=amp;';
                    }
                    $redirect_page .= $extra_params;
                }
                $redirect_page .= $plugin->itemName();
            }
            $comment_url = $redirect_page;

            $op = Request::getBool('com_dopost') ? 'post' : '';
            $op = Request::getBool('com_dopreview') ? 'preview' : $op;
            $op = Request::getBool('com_dodelete') ? 'delete' : $op;

            if ($op == 'preview' || $op == 'post') {
                if (!$xoops->security()->check()) {
                    $op = '';
                }
            }
            if ($op == 'post' && !$xoops->isUser()) {
                $xoopsCaptcha = XoopsCaptcha::getInstance();
                if (!$xoopsCaptcha->verify()) {
                    $captcha_message = $xoopsCaptcha->getMessage();
                    $op = 'preview';
                }
            }

            $title = XoopsLocale::trim(Request::getString('com_title'));
            $text = XoopsLocale::trim(Request::getString('com_text'));
            $mode = XoopsLocale::trim(Request::getString('com_mode', 'flat'));
            $order = XoopsLocale::trim(Request::getString('com_order', COMMENTS_OLD1ST));
            $itemid = Request::getInt('com_itemid');
            $pid = Request::getInt('com_pid');
            $rootid = Request::getInt('com_rootid');
            $status = Request::getInt('com_status');
            $dosmiley = Request::getBool('com_dosmiley');
            $doxcode = Request::getBool('com_doxcode');
            $dobr = Request::getBool('com_dobr');
            $dohtml = Request::getBool('com_html');
            $doimage = Request::getBool('com_doimage');
            $icon = XoopsLocale::trim(Request::getString('com_icon'));

            $comment->setVar('title', $title);
            $comment->setVar('text', $text);
            $comment->setVar('itemid', $itemid);
            $comment->setVar('pid', $pid);
            $comment->setVar('rootid', $rootid);
            $comment->setVar('status', $status);
            $comment->setVar('dosmiley', $dosmiley);
            $comment->setVar('doxcode', $doxcode);
            $comment->setVar('dobr', $dobr);
            $comment->setVar('dohtml', $dohtml);
            $comment->setVar('doimage', $doimage);
            $comment->setVar('icon', $icon);

            switch ($op) {
                case "delete":
                    $this->displayDelete();
                    break;

                case "preview":
                    $comment->setVar('doimage', 1);
                    if ($comment->getVar('dohtml') != 0) {
                        if ($xoops->isUser()) {
                            if (!$xoops->user->isAdmin($comment->getVar('modid'))) {
                                $comment->setVar('dohtml', 0);
                            }
                        } else {
                            $comment->setVar('dohtml', 0);
                        }
                    }

                    $xoops->header();
                    if (!$xoops->isAdminSide && !empty($captcha_message)) {
                        echo $xoops->alert('error', $captcha_message);
                    }
                    echo $this->renderHeader($comment->getVar('title', 'p'), $comment->getVar('text', 'p'), false, time());
                    $this->displayCommentForm($comment);
                    $xoops->footer();
                    break;

                case "post":
                    $comment->setVar('doimage', 1);
                    $comment_handler = $this->getHandlerComment();
                    $add_userpost = false;
                    $call_approvefunc = false;
                    $call_updatefunc = false;
                    // RMV-NOTIFY - this can be set to 'comment' or 'comment_submit'
                    $notify_event = false;
                    if (!empty($id)) {
                        $accesserror = false;

                        if ($xoops->isUser()) {
                            if ($xoops->user->isAdmin($comment->getVar('modid'))) {
                                if (!empty($status) && $status != COMMENTS_PENDING) {
                                    $old_status = $comment->getVar('status');
                                    $comment->setVar('status', $status);
                                    // if changing status from pending state, increment user post
                                    if (COMMENTS_PENDING == $old_status) {
                                        $add_userpost = true;
                                        if (COMMENTS_ACTIVE == $status) {
                                            $call_updatefunc = true;
                                            $call_approvefunc = true;
                                            // RMV-NOTIFY
                                            $notify_event = 'comment';
                                        }
                                    } else {
                                        if (COMMENTS_HIDDEN == $old_status && COMMENTS_ACTIVE == $status) {
                                            $call_updatefunc = true;
                                            // Comments can not be directly posted hidden,
                                            // no need to send notification here
                                        } else {
                                            if (COMMENTS_ACTIVE == $old_status && COMMENTS_HIDDEN == $status) {
                                                $call_updatefunc = true;
                                            }
                                        }
                                    }
                                }
                            } else {
                                $comment->setVar('dohtml', 0);
                                if ($comment->getVar('uid') != $xoops->user->getVar('uid')) {
                                    $accesserror = true;
                                }
                            }
                        } else {
                            $comment->setVar('dohtml', 0);
                            $accesserror = true;
                        }
                        if (false != $accesserror) {
                            $xoops->redirect($redirect_page . '=' . $comment->getVar('itemid') . '&amp;com_id=' . $comment->getVar('id') . '&amp;com_mode=' . $mode . '&amp;com_order=' . $order, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
                        }
                    } else {
                        $comment->setVar('created', time());
                        $comment->setVar('ip', $xoops->getEnv('REMOTE_ADDR'));
                        if ($xoops->isUser()) {
                            if ($xoops->user->isAdmin($comment->getVar('modid'))) {
                                $comment->setVar('status', COMMENTS_ACTIVE);
                                $add_userpost = true;
                                $call_approvefunc = true;
                                $call_updatefunc = true;
                                // RMV-NOTIFY
                                $notify_event = 'comment';
                            } else {
                                $comment->setVar('dohtml', 0);
                                switch ($xoops->getModuleConfig('com_rule')) {
                                    case COMMENTS_APPROVEALL:
                                    case COMMENTS_APPROVEUSER:
                                        $comment->setVar('status', COMMENTS_ACTIVE);
                                        $add_userpost = true;
                                        $call_approvefunc = true;
                                        $call_updatefunc = true;
                                        // RMV-NOTIFY
                                        $notify_event = 'comment';
                                        break;
                                    case COMMENTS_APPROVEADMIN:
                                    default:
                                        $comment->setVar('status', COMMENTS_PENDING);
                                        $notify_event = 'comment_submit';
                                        break;
                                }
                            }
                            if ($xoops->getModuleConfig('com_anonpost', $module->getVar('dirname')) && $comment->getVar('noname')) {
                                $comment->setVar('uid', 0);
                            } else {
                                $comment->setVar('uid', $xoops->user->getVar('uid'));
                            }
                        } else {
                            $comment->setVar('dohtml', 0);
                            $comment->setVar('uid', 0);
                            if ($xoops->getModuleConfig('com_anonpost', $module->getVar('dirname')) != 1) {
                                $xoops->redirect($redirect_page . '=' . $comment->getVar('itemid') . '&amp;com_id=' . $comment->getVar('id') . '&amp;com_mode=' . $mode . '&amp;com_order=' . $order, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
                            }
                        }
                        if ($comment->getVar('uid') == 0) {
                            switch ($xoops->getModuleConfig('com_rule')) {
                                case COMMENTS_APPROVEALL:
                                    $comment->setVar('status', COMMENTS_ACTIVE);
                                    $add_userpost = true;
                                    $call_approvefunc = true;
                                    $call_updatefunc = true;
                                    // RMV-NOTIFY
                                    $notify_event = 'comment';
                                    break;
                                case COMMENTS_APPROVEADMIN:
                                case COMMENTS_APPROVEUSER:
                                default:
                                    $comment->setVar('status', COMMENTS_PENDING);
                                    // RMV-NOTIFY
                                    $notify_event = 'comment_submit';
                                    break;
                            }
                        }
                    }
                    if ($comment->getVar('title') == '') {
                        $comment->setVar('title', XoopsLocale::NO_TITLE);
                    }
                    $comment->setVar('modified', time());
                    if (isset($extra_params)) {
                        $comment->setVar('exparams', $extra_params);
                    }

                    if (false != $comment_handler->insert($comment)) {
                        $newcid = $comment->getVar('id');
                        // set own id as root id if this is a top comment
                        if ($comment->getVar('rootid') == 0) {
                            $comment->setVar('rootid', $newcid);
                            if (!$comment_handler->updateByField($comment, 'rootid', $comment->getVar('rootid'))) {
                                $comment_handler->delete($comment);
                                $xoops->header();
                                echo $xoops->alert('error', $comment->getHtmlErrors());
                                $xoops->footer();
                            }
                        }
                        // call custom approve function if any
                        if (false != $call_approvefunc) {
                            $plugin->approve($comment);
                        }

                        if (false != $call_updatefunc) {
                            $criteria = new CriteriaCompo(new Criteria('modid', $comment->getVar('modid')));
                            $criteria->add(new Criteria('itemid', $comment->getVar('itemid')));
                            $criteria->add(new Criteria('status', COMMENTS_ACTIVE));
                            $comment_count = $comment_handler->getCount($criteria);
                            $plugin->update($comment->getVar('itemid'), $comment_count);
                        }

                        // increment user post if needed
                        $uid = $comment->getVar('uid');
                        if ($uid > 0 && false != $add_userpost) {
                            $member_handler = $xoops->getHandlerMember();
                            $poster = $member_handler->getUser($uid);
                            if ($poster instanceof XoopsUser) {
                                $member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') + 1);
                            }
                        }

                        // RMV-NOTIFY
                        // trigger notification event if necessary
                        if ($notify_event && $xoops->isActiveModule('notifications')) {
                            $notifications = Notifications::getInstance();
                            $not_modid = $comment->getVar('modid');
                            $not_catinfo = $notifications->getCommentsCategory($module->getVar('dirname'));
                            $not_category = $not_catinfo['name'];
                            $not_itemid = $comment->getVar('itemid');
                            $not_event = $notify_event;
                            // Build an ABSOLUTE URL to view the comment.  Make sure we
                            // point to a viewable page (i.e. not the system administration
                            // module).
                            $comment_tags = array();
                            $comment_tags['X_COMMENT_URL'] = $comment_url . '=' . $comment->getVar('itemid') . '&amp;com_id=' . $comment->getVar('id') . '&amp;com_rootid=' . $comment->getVar('rootid') . '&amp;com_mode=' . $mode . '&amp;com_order=' . $order . '#comment' . $comment->getVar('id');

                            if ($xoops->isActiveModule('notifications')) {
                                Notifications::getInstance()->getHandlerNotification()->triggerEvent($not_category, $not_itemid, $not_event, $comment_tags, false, $not_modid);
                            }
                        }
                        if (!isset($comment_post_results)) {
                            // if the comment is active, redirect to posted comment
                            if ($comment->getVar('status') == COMMENTS_ACTIVE) {
                                $xoops->redirect($redirect_page . '=' . $comment->getVar('itemid') . '&amp;com_id=' . $comment->getVar('id') . '&amp;com_rootid=' . $comment->getVar('rootid') . '&amp;com_mode=' . $mode . '&amp;com_order=' . $order . '#comment' . $comment->getVar('id'), 1, _MD_COMMENTS_THANKSPOST);
                            } else {
                                // not active, so redirect to top comment page
                                $xoops->redirect($redirect_page . '=' . $comment->getVar('itemid') . '&amp;com_mode=' . $mode . '&amp;com_order=' . $order . '#comment' . $comment->getVar('id'), 1, _MD_COMMENTS_THANKSPOST);
                            }
                        }
                    } else {
                        if (!isset($purge_comment_post_results)) {
                            $xoops->header();
                            echo $xoops->alert('error', $comment->getHtmlErrors());
                            $xoops->footer();
                        } else {
                            $comment_post_results = $comment->getErrors();
                        }
                    }
                    break;
                default:
                    $xoops->redirect(XOOPS_URL . '/', 1, implode('<br />', $xoops->security()->getErrors()));
                    break;
            }
        }
    }

    public function displayReply()
    {
        $xoops = Xoops::getInstance();

        $modid = Request::getInt('com_modid', 0);

        if (empty($modid)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        $module = $xoops->getModuleById($modid);
        if (!is_object($module)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        if ((!$xoops->isAdminSide && COMMENTS_APPROVENONE == $xoops->getModuleConfig('com_rule', $module->getVar('dirname'))) || (!$xoops->isUser() && !$xoops->getModuleConfig('com_anonpost', $module->getVar('dirname'))) || !$xoops->isModule()) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        //Original comment
        $comment = $this->getHandlerComment()->get(Request::getInt('com_id', 0));

        /* @var $reply CommentsComment */
        $reply = $this->getHandlerComment()->create();

        $title = $comment->getVar('title', 'e');
        if (!preg_match("/^" . XoopsLocale::C_RE . "/i", $title)) {
            $title = XoopsLocale::C_RE . " " . XoopsLocale::substr($title, 0, 56);
        }
        $reply->setVar('title', $title);
        $reply->setVar('modid', $comment->getVar('modid'));
        $reply->setVar('pid', $comment->getVar('id'));
        $reply->setVar('rootid', $comment->getVar('rootid'));
        $reply->setVar('itemid', $comment->getVar('itemid'));

        $xoops->header();
        echo $this->renderHeader($comment->getVar('title'), $comment->getVar('text'), $comment->getVar('uid'), $comment->getVar('created'));
        $this->displayCommentForm($reply);
        $xoops->footer();
    }

    public function renderHeader($title, $text, $uid, $timestamp)
    {
        $ret = '<table cellpadding="4" cellspacing="1" width="98%" class="outer">
      <tr><td class="head">' . $title . '</td></tr><tr><td><br />';
        if ($uid) {
            $ret .= _MD_COMMENTS_POSTER . ': <strong>' . XoopsUser::getUnameFromId($uid) . '</strong>&nbsp;&nbsp;';
        }
        $ret .= _MD_COMMENTS_POSTED . ': <strong>' . XoopsLocale::formatTimestamp($timestamp) . '</strong><br /><br />' . $text . '<br /></td></tr>';
        $ret .= '</table>';
        return $ret;
    }

    public function renderView()
    {
        $xoops = Xoops::getInstance();
        /* @var $plugin CommentsPluginInterface */
        if ($xoops->isModule() && $plugin = \Xoops\Module\Plugin::getPlugin($xoops->module->getVar('dirname'), 'comments')) {
            if (COMMENTS_APPROVENONE != $xoops->getModuleConfig('com_rule')) {
                $xoops->tpl()->assign('xoops_iscommentadmin', $this->isUserAdmin());

                $itemid = (trim($plugin->itemName()) != '' && isset($_GET[$plugin->itemName()])) ? intval($_GET[$plugin->itemName()]) : 0;
                if ($itemid > 0) {
                    $modid = $xoops->module->getVar('mid');
                    $mode = Request::getString('com_mode', $this->getUserConfig('com_mode'));
                    $xoops->tpl()->assign('comment_mode', $mode);

                    $order = Request::getInt('com_order', $this->getUserConfig('com_order'));
                    if ($order != COMMENTS_OLD1ST) {
                        $xoops->tpl()->assign(array(
                            'comment_order' => COMMENTS_NEW1ST,
                            'order_other'   => COMMENTS_OLD1ST
                        ));
                        $dborder = 'DESC';
                    } else {
                        $xoops->tpl()->assign(array(
                            'comment_order' => COMMENTS_OLD1ST,
                            'order_other'   => COMMENTS_NEW1ST
                        ));
                        $dborder = 'ASC';
                    }
                    // admins can view all comments and IPs, others can only view approved(active) comments
                    if ($xoops->isUser() && $xoops->user->isAdmin($xoops->module->getVar('mid'))) {
                        $admin_view = true;
                    } else {
                        $admin_view = false;
                    }

                    $id = Request::getInt('com_id', 0);
                    $rootid = Request::getInt('com_rootid', 0);

                    $comment_handler = $this->getHandlerComment();
                    if ($mode == 'flat') {
                        $comments = $comment_handler->getByItemId($xoops->module->getVar('mid'), $itemid, $dborder);
                        $renderer = CommentsCommentRenderer::getInstance($xoops->tpl());
                        $renderer->setComments($comments);
                        $renderer->renderFlatView($admin_view);
                    } elseif ($mode == 'thread') {
                        // RMV-FIX... added extraParam stuff here
                        $comment_url = $plugin->pageName() . '?';
                        if (is_array($extraParams = $plugin->extraParams())) {
                            $extra_params = '';
                            foreach ($extraParams as $extra_param) {
                                // This page is included in the module hosting page -- param could be from anywhere

                                if (isset($_POST[$extra_param])) {
                                    $extra_params .= $extra_param . '=' . $_POST[$extra_param] . '&amp;';
                                } else {
                                    if (isset($_GET[$extra_param])) {
                                        $extra_params .= $extra_param . '=' . $_GET[$extra_param] . '&amp;';
                                    } else {
                                        $extra_params .= $extra_param . '=&amp;';
                                    }
                                }
                            }
                            $comment_url .= $extra_params;
                        }
                        $xoops->tpl()->assign('comment_url', $comment_url . $plugin->itemName() . '=' . $itemid . '&amp;com_mode=thread&amp;com_order=' . $order);
                        if (!empty($id) && !empty($rootid) && ($id != $rootid)) {
                            // Show specific thread tree
                            $comments = $comment_handler->getThread($rootid, $id);
                            if (false != $comments) {
                                $renderer = CommentsCommentRenderer::getInstance($xoops->tpl());
                                $renderer->setComments($comments);
                                $renderer->renderThreadView($id, $admin_view);
                            }
                        } else {
                            // Show all threads
                            $top_comments = $comment_handler->getTopComments($xoops->module->getVar('mid'), $itemid, $dborder);
                            $c_count = count($top_comments);
                            if ($c_count > 0) {
                                for ($i = 0; $i < $c_count; $i++) {
                                    $comments = $comment_handler->getThread($top_comments[$i]->getVar('rootid'), $top_comments[$i]->getVar('id'));
                                    if (false != $comments) {
                                        $renderer = CommentsCommentRenderer::getInstance($xoops->tpl());
                                        $renderer->setComments($comments);
                                        $renderer->renderThreadView($top_comments[$i]->getVar('id'), $admin_view);
                                    }
                                    unset($comments);
                                }
                            }
                        }
                    } else {
                        // Show all threads
                        $top_comments = $comment_handler->getTopComments($xoops->module->getVar('mid'), $itemid, $dborder);
                        $c_count = count($top_comments);
                        if ($c_count > 0) {
                            for ($i = 0; $i < $c_count; $i++) {
                                $comments = $comment_handler->getThread($top_comments[$i]->getVar('rootid'), $top_comments[$i]->getVar('id'));
                                $renderer = CommentsCommentRenderer::getInstance($xoops->tpl());
                                $renderer->setComments($comments);
                                $renderer->renderNestView($top_comments[$i]->getVar('id'), $admin_view);
                            }
                        }
                    }
                    // assign comment nav bar
                    $xoops->tpl()->assign('page_name', $plugin->pageName());
                    $xoops->tpl()->assign('order', $order);
                    $xoops->tpl()->assign('COMMENTS_OLD1ST', COMMENTS_OLD1ST);
                    $xoops->tpl()->assign('COMMENTS_NEW1ST', COMMENTS_NEW1ST);
                    $xoops->tpl()->assign('itemid', $itemid);
                    $xoops->tpl()->assign('item_name', $plugin->itemName());
                    unset($postcomment_link);
                    if ($xoops->getModuleConfig('com_anonpost') || $xoops->isUser()) {
                        $postcomment_link = $this->url('comment_new.php?com_modid=' . $modid . '&amp;com_itemid=' . $itemid . '&amp;com_order=' . $order . '&amp;com_mode=' . $mode);
                        $xoops->tpl()->assign('anon_canpost', true);
                    }
                    $link_extra = '';
                    if (is_array($extraParams = $plugin->extraParams())) {
                        foreach ($extraParams as $extra_param) {
                            if (isset($_POST[$extra_param])) {
                                $extra_param_val = $_POST[$extra_param];
                            } else {
                                if (isset($_GET[$extra_param])) {
                                    $extra_param_val = $_GET[$extra_param];
                                }
                            }
                            if (isset($extra_param_val)) {
                                $link_extra .= '&amp;' . $extra_param . '=' . $extra_param_val;
                                $hidden_value = htmlspecialchars($extra_param_val, ENT_QUOTES);
                                $xoops->tpl()->assign('extra_param', $extra_param);
                                $xoops->tpl()->assign('hidden_value', $hidden_value);
                            }
                        }
                    }
                    if (isset($postcomment_link)) {
                        $xoops->tpl()->assign('postcomment_link', $postcomment_link);
                        $xoops->tpl()->assign('link_extra', $link_extra);
                    }
                    $xoops->tpl()->assign(array(
                        'comments_editlink'   => $this->url('comment_edit.php?com_modid=' . $modid . '&amp;com_itemid=' . $itemid . '&amp;com_order=' . $order . '&amp;com_mode=' . $mode . '' . $link_extra),
                        'comments_deletelink' => $this->url('comment_delete.php?com_modid=' . $modid . '&amp;com_itemid=' . $itemid . '&amp;com_order=' . $order . '&amp;com_mode=' . $mode . '' . $link_extra),
                        'comments_replylink'  => $this->url('comment_reply.php?com_modid=' . $modid . '&amp;com_itemid=' . $itemid . '&amp;com_order=' . $order . '&amp;com_mode=' . $mode . '' . $link_extra)
                    ));

                    // assign some lang variables
                    $xoops->tpl()->assign(array(
                        'comments_lang_from'    => _MD_COMMENTS_FROM,
                        'comments_lang_joined'  => _MD_COMMENTS_JOINED,
                        'comments_lang_posts'   => _MD_COMMENTS_POSTS,
                        'comments_lang_poster'  => _MD_COMMENTS_POSTER,
                        'comments_lang_thread'  => _MD_COMMENTS_THREAD,
                        'comments_lang_edit'    => XoopsLocale::A_EDIT,
                        'comments_lang_delete'  => XoopsLocale::A_DELETE,
                        'comments_lang_reply'   => XoopsLocale::A_REPLY,
                        'comments_lang_subject' => _MD_COMMENTS_REPLIES,
                        'comments_lang_posted'  => _MD_COMMENTS_POSTED,
                        'comments_lang_updated' => _MD_COMMENTS_UPDATED,
                        'comments_lang_notice'  => _MD_COMMENTS_NOTICE
                    ));
                }
            }
        }
    }

    public function displayEdit()
    {
        $xoops = Xoops::getInstance();

        /* @var $comment CommentsComment */
        $comment = $this->getHandlerComment()->get(Request::getInt('com_id'));
        if (!is_object($comment)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }
        $module = $xoops->getModuleById($comment->getVar('modid'));
        if (!is_object($module)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        if ((!$xoops->isAdminSide && COMMENTS_APPROVENONE == $xoops->getModuleConfig('com_rule', $module->getVar('dirname'))) || (!$xoops->isUser() && !$xoops->getModuleConfig('com_anonpost', $module->getVar('dirname'))) || !$xoops->isModule()) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        /* @var $plugin CommentsPluginInterface */
        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'comments')) {
            $xoops->header();
            $this->displayCommentForm($comment);
            $xoops->footer();
        }
        $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
    }

    public function displayDelete()
    {
        $xoops = Xoops::getInstance();
        $op = Request::getCmd('op', 'delete', 'POST');
        $mode = Request::getString('com_mode', 'flat');
        $order = Request::getString('com_order', COMMENTS_OLD1ST);
        $id = Request::getInt('com_id');

        /* @var $comment CommentsComment */
        /* @var $comment_handler CommentsCommentHandler */
        $comment_handler = $this->getHandlerComment();
        $comment = $comment_handler->get($id);
        if (!is_object($comment)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }
        $module = $xoops->getModuleById($comment->getVar('modid'));
        if (!is_object($module)) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        if ((!$xoops->isAdminSide && COMMENTS_APPROVENONE == $xoops->getModuleConfig('com_rule', $module->getVar('dirname'))) || (!$xoops->isUser() && !$xoops->getModuleConfig('com_anonpost', $module->getVar('dirname'))) || !$xoops->isModule()) {
            $xoops->redirect(XOOPS_URL, 1, XoopsLocale::E_NO_ACCESS_PERMISSION);
        }

        $modid = $module->getVar('mid');
        /* @var $plugin CommentsPluginInterface */
        if ($plugin = \Xoops\Module\Plugin::getPlugin($module->getVar('dirname'), 'comments')) {
            if ($xoops->isAdminSide) {
                $redirect_page = $this->url('admin/main.php?com_modid=' . $modid . '&amp;com_itemid');
            } else {
                $redirect_page = $xoops->url('modules/' . $module->getVar('dirname') . '/' . $plugin->pageName() . '?');
                $comment_confirm_extra = array();
                if (is_array($extraParams = $plugin->extraParams())) {
                    foreach ($extraParams as $extra_param) {
                        if (isset($_GET[$extra_param])) {
                            $redirect_page .= $extra_param . '=' . $_GET[$extra_param] . '&amp;';
                            // for the confirmation page
                            $comment_confirm_extra[$extra_param] = $_GET[$extra_param];
                        }
                    }
                }
                $redirect_page .= $plugin->itemName();
            }

            $accesserror = false;
            if (!$xoops->isUser()) {
                $accesserror = true;
            } else {
                if (!$xoops->user->isAdmin($modid)) {
                    $accesserror = true;
                }
            }

            if (false != $accesserror) {
                $ref = $xoops->getEnv('HTTP_REFERER');
                if ($ref != '') {
                    $xoops->redirect($ref, 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
                } else {
                    $xoops->redirect($redirect_page . '?' . $plugin->itemName() . '=' . intval($id), 2, XoopsLocale::E_NO_ACCESS_PERMISSION);
                }
            }

            switch ($op) {
                case 'delete_one':
                    if (!$comment_handler->delete($comment)) {
                        $xoops->header();
                        echo $xoops->alert('error', _MD_COMMENTS_COMDELETENG . ' (ID: ' . $comment->getVar('id') . ')');
                        $xoops->footer();
                    }

                    $itemid = $comment->getVar('itemid');

                    $criteria = new CriteriaCompo(new Criteria('modid', $modid));
                    $criteria->add(new Criteria('itemid', $itemid));
                    $criteria->add(new Criteria('status', COMMENTS_ACTIVE));
                    $comment_count = $comment_handler->getCount($criteria);
                    $plugin->update($itemid, $comment_count);

                    // update user posts if its not an anonymous post
                    if ($comment->getVar('uid') != 0) {
                        $member_handler = $xoops->getHandlerMember();
                        $poster = $member_handler->getUser($comment->getVar('uid'));
                        if (is_object($poster)) {
                            $member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') - 1);
                        }
                    }

                    // get all comments posted later within the same thread
                    $thread_comments = $comment_handler->getThread($comment->getVar('rootid'), $id);

                    $xot = new XoopsObjectTree($thread_comments, 'id', 'pid', 'rootid');
                    $child_comments = $xot->getFirstChild($id);
                    // now set new parent ID for direct child comments
                    $new_pid = $comment->getVar('pid');
                    $errs = array();
                    foreach (array_keys($child_comments) as $i) {
                        $child_comments[$i]->setVar('pid', $new_pid);
                        // if the deleted comment is a root comment, need to change root id to own id
                        if (false != $comment->isRoot()) {
                            $new_rootid = $child_comments[$i]->getVar('id');
                            $child_comments[$i]->setVar('rootid', $child_comments[$i]->getVar('id'));
                            if (!$comment_handler->insert($child_comments[$i])) {
                                $errs[] = 'Could not change comment parent ID from <strong>' . $id . '</strong> to <strong>' . $new_pid . '</strong>. (ID: ' . $new_rootid . ')';
                            } else {
                                // need to change root id for all its child comments as well
                                $c_child_comments = $xot->getAllChild($new_rootid);
                                $cc_count = count($c_child_comments);
                                foreach (array_keys($c_child_comments) as $j) {
                                    $c_child_comments[$j]->setVar('rootid', $new_rootid);
                                    if (!$comment_handler->insert($c_child_comments[$j])) {
                                        $errs[] = 'Could not change comment root ID from <strong>' . $id . '</strong> to <strong>' . $new_rootid . '</strong>.';
                                    }
                                }
                            }
                        } else {
                            if (!$comment_handler->insert($child_comments[$i])) {
                                $errs[] = 'Could not change comment parent ID from <strong>' . $id . '</strong> to <strong>' . $new_pid . '</strong>.';
                            }
                        }
                    }
                    if (count($errs) > 0) {
                        $xoops->header();
                        echo $xoops->alert('error', $errs);
                        $xoops->footer();
                        exit();
                    }
                    $xoops->redirect($redirect_page . '=' . $itemid . '&amp;com_order=' . $order . '&amp;com_mode=' . $mode, 1, _MD_COMMENTS_COMDELETED);
                    break;

                case 'delete_all':
                    $rootid = $comment->getVar('rootid');

                    // get all comments posted later within the same thread
                    $thread_comments = $comment_handler->getThread($rootid, $id);

                    // construct a comment tree
                    $xot = new XoopsObjectTree($thread_comments, 'id', 'pid', 'rootid');
                    $child_comments = $xot->getAllChild($id);
                    // add itself here
                    $child_comments[$id] = $comment;
                    $msgs = array();
                    $deleted_num = array();
                    $member_handler = $xoops->getHandlerMember();
                    foreach (array_keys($child_comments) as $i) {
                        if (!$comment_handler->delete($child_comments[$i])) {
                            $msgs[] = _MD_COMMENTS_COMDELETENG . ' (ID: ' . $child_comments[$i]->getVar('id') . ')';
                        } else {
                            $msgs[] = _MD_COMMENTS_COMDELETED . ' (ID: ' . $child_comments[$i]->getVar('id') . ')';
                            // store poster ID and deleted post number into array for later use
                            $poster_id = $child_comments[$i]->getVar('uid');
                            if ($poster_id > 0) {
                                $deleted_num[$poster_id] = !isset($deleted_num[$poster_id]) ? 1 : ($deleted_num[$poster_id] + 1);
                            }
                        }
                    }
                    foreach ($deleted_num as $user_id => $post_num) {
                        // update user posts
                        $poster = $member_handler->getUser($user_id);
                        if (is_object($poster)) {
                            $member_handler->updateUserByField($poster, 'posts', $poster->getVar('posts') - $post_num);
                        }
                    }

                    $itemid = $comment->getVar('itemid');

                    $criteria = new CriteriaCompo(new Criteria('modid', $modid));
                    $criteria->add(new Criteria('itemid', $itemid));
                    $criteria->add(new Criteria('status', COMMENTS_ACTIVE));
                    $comment_count = $comment_handler->getCount($criteria);
                    $plugin->update($itemid, $comment_count);

                    $xoops->header();
                    echo $xoops->alert('info', $msgs);
                    echo '<br /><a href="' . $redirect_page . '=' . $itemid . '&amp;com_order=' . $order . '&amp;com_mode=' . $mode . '">' . XoopsLocale::GO_BACK . '</a>';
                    $xoops->footer();
                    break;

                case 'delete':
                default:
                    $xoops->header();
                    $comment_confirm = array(
                        'com_id'    => $id,
                        'com_mode'  => $mode,
                        'com_order' => $order,
                        'op'        => array(
                            _MD_COMMENTS_DELETEONE => 'delete_one',
                            _MD_COMMENTS_DELETEALL => 'delete_all'
                        )
                    );
                    if (!empty($comment_confirm_extra) && is_array($comment_confirm_extra)) {
                        $comment_confirm = $comment_confirm + $comment_confirm_extra;
                    }
                    $xoops->confirm($comment_confirm, 'comment_delete.php', _MD_COMMENTS_DELETESELECT);
                    $xoops->footer();
                    break;
            }
        }
    }

    /**
     * @param XoopsModule $module
     */
    public function insertModuleRelations(XoopsModule $module)
    {
        $xoops = Xoops::getInstance();
        $config_handler = $xoops->getHandlerConfig();
        $configs = $this->getPluginableConfigs();

        $order = count($xoops->getModuleConfigs($module->getVar('dirname')));
        foreach ($configs as $config) {
            $confobj = $config_handler->createConfig();
            $confobj->setVar('conf_modid', $module->getVar('mid'));
            $confobj->setVar('conf_catid', 0);
            $confobj->setVar('conf_name', $config['name']);
            $confobj->setVar('conf_title', $config['title'], true);
            $confobj->setVar('conf_desc', $config['description'], true);
            $confobj->setVar('conf_formtype', $config['formtype']);
            $confobj->setVar('conf_valuetype', $config['valuetype']);
            $confobj->setConfValueForInput($config['default'], true);
            $confobj->setVar('conf_order', $order);
            if (isset($config['options']) && is_array($config['options'])) {
                foreach ($config['options'] as $key => $value) {
                    $confop = $config_handler->createConfigOption();
                    $confop->setVar('confop_name', $key, true);
                    $confop->setVar('confop_value', $value, true);
                    $confobj->setConfOptions($confop);
                    unset($confop);
                }
            }
            $order++;
            $config_handler->insertConfig($confobj);
        }
    }

    /**
     * @param XoopsModule $module
     */
    public function deleteModuleRelations(XoopsModule $module)
    {
        $xoops = Xoops::getInstance();
        $this->getHandlerComment()->deleteByModule($module->getVar('mid'));


        $configNames = array('com_rule', 'com_anonpost');
        $config_handler = $xoops->getHandlerConfig();

        //Delete all configs
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('conf_modid', $module->getVar('mid')));
        $criteria->add(new Criteria('conf_name', "('" . implode("','", $configNames) . "')", 'IN'));
        $configs = $config_handler->getConfigs($criteria);
        /* @var $config XoopsConfigItem */
        foreach ($configs as $config) {
            $config_handler->deleteConfig($config);
        }
    }

    /**
     * @return array
     */
    public function getPluginableConfigs()
    {
        $configs = array();
        array_push($configs, array(
            'name'        => 'com_rule',
            'title'       => '_MD_COMMENTS_COMRULES',
            'description' => '',
            'formtype'    => 'select',
            'valuetype'   => 'int',
            'default'     => 1,
            'options'     => array(
                '_MD_COMMENTS_COMNOCOM'        => COMMENTS_APPROVENONE,
                '_MD_COMMENTS_COMAPPROVEALL'   => COMMENTS_APPROVEALL,
                '_MD_COMMENTS_COMAPPROVEUSER'  => COMMENTS_APPROVEUSER,
                '_MD_COMMENTS_COMAPPROVEADMIN' => COMMENTS_APPROVEADMIN
            )
        ));
        array_push($configs, array(
            'name'        => 'com_anonpost',
            'title'       => '_MD_COMMENTS_COMANONPOST',
            'description' => '',
            'formtype'    => 'yesno',
            'valuetype'   => 'int',
            'default'     => 0
        ));
        return $configs;
    }
}
