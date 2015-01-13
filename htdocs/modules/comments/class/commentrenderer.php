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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Comments
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Display comments
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @package class
 */
class CommentsCommentRenderer
{
    /**
     * @var XoopsTpl
     */
    private $_tpl;

    /**
     * @var array
     */
    private $_comments = array();

    /**
     * @var bool
     */
    private $_useIcons = true;

    /**
     * @var bool
     */
    private $_doIconCheck = false;

    /**
     * @var array
     */
    private $_statusText;

    /**
     * Constructor
     *
     * @param XoopsTpl $tpl
     * @param boolean  $use_icons
     * @param boolean  $do_iconcheck
     */
    public function __construct(XoopsTpl $tpl, $use_icons = true, $do_iconcheck = false)
    {
        $this->_tpl = $tpl;
        $this->_useIcons = $use_icons;
        $this->_doIconCheck = $do_iconcheck;
        $this->_statusText = array(
            COMMENTS_PENDING => '<span style="text-decoration: none; font-weight: bold; color: #00ff00;">' . _MD_COMMENTS_PENDING . '</span>',
            COMMENTS_ACTIVE  => '<span style="text-decoration: none; font-weight: bold; color: #ff0000;">' . _MD_COMMENTS_ACTIVE . '</span>',
            COMMENTS_HIDDEN  => '<span style="text-decoration: none; font-weight: bold; color: #0000ff;">' . _MD_COMMENTS_HIDDEN . '</span>'
        );
    }

    /**
     * Access the only instance of this class
     *
     * @param XoopsTpl $tpl reference to a {@link XoopsTpl} object
     * @param boolean  $use_icons
     * @param boolean  $do_iconcheck
     *
     * @return CommentsCommentRenderer
     */
    static public function getInstance(XoopsTpl $tpl, $use_icons = true, $do_iconcheck = false)
    {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class($tpl, $use_icons, $do_iconcheck);
        }
        return $instance;
    }

    /**
     * Accessor
     *
     * @param array $comments_arr  array of {@link XoopsComment} objects
     */
    public function setComments(&$comments_arr)
    {
        if (isset($this->_comments)) {
            unset($this->_comments);
        }
        $this->_comments =& $comments_arr;
    }

    /**
     * Render the comments in flat view
     *
     * @param boolean $admin_view
     */
    public function renderFlatView($admin_view = false)
    {
        foreach ($this->_comments as $i => $comment) {
            /* @var $comment XoopsComment */
            if (false != $this->_useIcons) {
                $title = $this->_getTitleIcon($comment->getVar('icon')) . '&nbsp;' . $comment->getVar('title');
            } else {
                $title = $comment->getVar('title');
            }
            $poster = $this->_getPosterArray($comment->getVar('uid'));
            if (false != $admin_view) {
                $text = $comment->getVar('text') . '<div style="text-align:right; margin-top: 2px; margin-bottom: 0px; margin-right: 2px;">' . _MD_COMMENTS_STATUS . ': ' . $this->_statusText[$comment->getVar('status')] . '<br />IP: <span style="font-weight: bold;">' . $comment->getVar('ip') . '</span></div>';
            } else {
                // hide comments that are not active
                if (COMMENTS_ACTIVE != $comment->getVar('status')) {
                    continue;
                } else {
                    $text = $comment->getVar('text');
                }
            }
            $this->_comments[$i] = $comment;
            $this->_tpl->append('comments', array(
                'id'            => $comment->getVar('id'),
                'title'         => $title,
                'text'          => $text,
                'date_posted'   => XoopsLocale::formatTimestamp($comment->getVar('created'), 'm'),
                'date_modified' => XoopsLocale::formatTimestamp($comment->getVar('modified'), 'm'),
                'poster'        => $poster
            ));
        }
    }

    /**
     * Render the comments in thread view
     * This method calls itself recursively
     *
     * @param integer $comment_id Should be "0" when called by client
     * @param boolean $admin_view
     * @param boolean $show_nav
     */
    public function renderThreadView($comment_id = 0, $admin_view = false, $show_nav = true)
    {
        // construct comment tree
        $xot = new XoopsObjectTree($this->_comments, 'id', 'pid', 'rootid');
        $tree = $xot->getTree();

        if (false != $this->_useIcons) {
            $title = $this->_getTitleIcon($tree[$comment_id]['obj']->getVar('icon')) . '&nbsp;' . $tree[$comment_id]['obj']->getVar('title');
        } else {
            $title = $tree[$comment_id]['obj']->getVar('title');
        }
        if (false != $show_nav && $tree[$comment_id]['obj']->getVar('pid') != 0) {
            $this->_tpl->assign('lang_top', _MD_COMMENTS_TOP);
            $this->_tpl->assign('lang_parent', _MD_COMMENTS_PARENT);
            $this->_tpl->assign('show_threadnav', true);
        } else {
            $this->_tpl->assign('show_threadnav', false);
        }
        if (false != $admin_view) {
            // admins can see all
            $text = $tree[$comment_id]['obj']->getVar('text') . '<div style="text-align:right; margin-top: 2px; margin-bottom: 0px; margin-right: 2px;">' . _MD_COMMENTS_STATUS . ': ' . $this->_statusText[$tree[$comment_id]['obj']->getVar('status')] . '<br />IP: <span style="font-weight: bold;">' . $tree[$comment_id]['obj']->getVar('ip') . '</span></div>';
        } else {
            // hide comments that are not active
            if (COMMENTS_ACTIVE != $tree[$comment_id]['obj']->getVar('status')) {
                // if there are any child comments, display them as root comments
                if (isset($tree[$comment_id]['child']) && !empty($tree[$comment_id]['child'])) {
                    foreach ($tree[$comment_id]['child'] as $child_id) {
                        $this->renderThreadView($child_id, $admin_view, false);
                    }
                }
                return;
            } else {
                $text = $tree[$comment_id]['obj']->getVar('text');
            }
        }
        $replies = array();
        $this->_renderThreadReplies($tree, $comment_id, $replies, '&nbsp;&nbsp;', $admin_view);
        $show_replies = (count($replies) > 0) ? true : false;
        $this->_tpl->append('comments', array(
            'pid'           => $tree[$comment_id]['obj']->getVar('pid'),
            'id'            => $tree[$comment_id]['obj']->getVar('id'),
            'itemid'        => $tree[$comment_id]['obj']->getVar('itemid'),
            'rootid'        => $tree[$comment_id]['obj']->getVar('rootid'),
            'title'         => $title,
            'text'          => $text,
            'date_posted'   => XoopsLocale::formatTimestamp($tree[$comment_id]['obj']->getVar('created'), 'm'),
            'date_modified' => XoopsLocale::formatTimestamp($tree[$comment_id]['obj']->getVar('modified'), 'm'),
            'poster'        => $this->_getPosterArray($tree[$comment_id]['obj']->getVar('uid')),
            'replies'       => $replies,
            'show_replies'  => $show_replies
        ));
    }

    /**
     * Render replies to a thread
     *
     * @param array   $thread
     * @param int     $key
     * @param array   $replies
     * @param string  $prefix
     * @param bool    $admin_view
     * @param integer $depth
     * @param string  $current_prefix
     *
     * @access private
     */
    private function _renderThreadReplies(&$thread, $key, &$replies, $prefix, $admin_view, $depth = 0, $current_prefix = '')
    {
        if ($depth > 0) {
            if (false != $this->_useIcons) {
                $title = $this->_getTitleIcon($thread[$key]['obj']->getVar('icon')) . '&nbsp;' . $thread[$key]['obj']->getVar('title');
            } else {
                $title = $thread[$key]['obj']->getVar('title');
            }
            $title = (false != $admin_view) ? $title . ' ' . $this->_statusText[$thread[$key]['obj']->getVar('status')] : $title;
            $replies[] = array(
                'id'          => $key,
                'prefix'      => $current_prefix,
                'date_posted' => XoopsLocale::formatTimestamp($thread[$key]['obj']->getVar('created'), 'm'),
                'title'       => $title,
                'root_id'     => $thread[$key]['obj']->getVar('rootid'),
                'status'      => $this->_statusText[$thread[$key]['obj']->getVar('status')],
                'poster'      => $this->_getPosterName($thread[$key]['obj']->getVar('uid'))
            );
            $current_prefix .= $prefix;
        }
        if (isset($thread[$key]['child']) && !empty($thread[$key]['child'])) {
            $depth++;
            foreach ($thread[$key]['child'] as $childkey) {
                if (!$admin_view && $thread[$childkey]['obj']->getVar('status') != COMMENTS_ACTIVE) {
                    // skip this comment if it is not active and continue on processing its child comments instead
                    if (isset($thread[$childkey]['child']) && !empty($thread[$childkey]['child'])) {
                        foreach ($thread[$childkey]['child'] as $childchildkey) {
                            $this->_renderThreadReplies($thread, $childchildkey, $replies, $prefix, $admin_view, $depth);
                        }
                    }
                } else {
                    $this->_renderThreadReplies($thread, $childkey, $replies, $prefix, $admin_view, $depth, $current_prefix);
                }
            }
        }
    }

    /**
     * Render comments in nested view
     * Danger: Recursive!
     *
     * @param integer $comment_id Always "0" when called by client.
     * @param boolean $admin_view
     */
    public function renderNestView($comment_id = 0, $admin_view = false)
    {
        $xot = new XoopsObjectTree($this->_comments, 'id', 'pid', 'rootid');
        $tree = $xot->getTree();
        if (false != $this->_useIcons) {
            $title = $this->_getTitleIcon($tree[$comment_id]['obj']->getVar('icon')) . '&nbsp;' . $tree[$comment_id]['obj']->getVar('title');
        } else {
            $title = $tree[$comment_id]['obj']->getVar('title');
        }
        if (false != $admin_view) {
            $text = $tree[$comment_id]['obj']->getVar('text') . '<div style="text-align:right; margin-top: 2px; margin-bottom: 0px; margin-right: 2px;">' . _MD_COMMENTS_STATUS . ': ' . $this->_statusText[$tree[$comment_id]['obj']->getVar('status')] . '<br />IP: <span style="font-weight: bold;">' . $tree[$comment_id]['obj']->getVar('ip') . '</span></div>';
        } else {
            // skip this comment if it is not active and continue on processing its child comments instead
            if (COMMENTS_ACTIVE != $tree[$comment_id]['obj']->getVar('status')) {
                // if there are any child comments, display them as root comments
                if (isset($tree[$comment_id]['child']) && !empty($tree[$comment_id]['child'])) {
                    foreach ($tree[$comment_id]['child'] as $child_id) {
                        $this->renderNestView($child_id, $admin_view);
                    }
                }
                return;
            } else {
                $text = $tree[$comment_id]['obj']->getVar('text');
            }
        }
        $replies = array();
        $this->_renderNestReplies($tree, $comment_id, $replies, 25, $admin_view);
        $this->_tpl->append('comments', array(
            'pid'           => $tree[$comment_id]['obj']->getVar('pid'),
            'id'            => $tree[$comment_id]['obj']->getVar('id'),
            'itemid'        => $tree[$comment_id]['obj']->getVar('itemid'),
            'rootid'        => $tree[$comment_id]['obj']->getVar('rootid'),
            'title'         => $title,
            'text'          => $text,
            'date_posted'   => XoopsLocale::formatTimestamp($tree[$comment_id]['obj']->getVar('created'), 'm'),
            'date_modified' => XoopsLocale::formatTimestamp($tree[$comment_id]['obj']->getVar('modified'), 'm'),
            'poster'        => $this->_getPosterArray($tree[$comment_id]['obj']->getVar('uid')),
            'replies'       => $replies
        ));
    }

    /**
     * Render replies in nested view
     *
     * @param array   $thread
     * @param int     $key
     * @param array   $replies
     * @param string  $prefix
     * @param bool    $admin_view
     * @param integer $depth
     *
     * @access private
     */
    private function _renderNestReplies(&$thread, $key, &$replies, $prefix, $admin_view, $depth = 0)
    {
        if ($depth > 0) {
            if (false != $this->_useIcons) {
                $title = $this->_getTitleIcon($thread[$key]['obj']->getVar('icon')) . '&nbsp;' . $thread[$key]['obj']->getVar('title');
            } else {
                $title = $thread[$key]['obj']->getVar('title');
            }
            $text = (false != $admin_view) ? $thread[$key]['obj']->getVar('text') . '<div style="text-align:right; margin-top: 2px; margin-right: 2px;">' . _MD_COMMENTS_STATUS . ': ' . $this->_statusText[$thread[$key]['obj']->getVar('status')] . '<br />IP: <span style="font-weight: bold;">' . $thread[$key]['obj']->getVar('ip') . '</span></div>' : $thread[$key]['obj']->getVar('text');
            $replies[] = array(
                'id'            => $key,
                'prefix'        => $prefix,
                'pid'           => $thread[$key]['obj']->getVar('pid'),
                'itemid'        => $thread[$key]['obj']->getVar('itemid'),
                'rootid'        => $thread[$key]['obj']->getVar('rootid'),
                'title'         => $title,
                'text'          => $text,
                'date_posted'   => XoopsLocale::formatTimestamp($thread[$key]['obj']->getVar('created'), 'm'),
                'date_modified' => XoopsLocale::formatTimestamp($thread[$key]['obj']->getVar('modified'), 'm'),
                'poster'        => $this->_getPosterArray($thread[$key]['obj']->getVar('uid'))
            );

            $prefix = $prefix + 25;
        }
        if (isset($thread[$key]['child']) && !empty($thread[$key]['child'])) {
            $depth++;
            foreach ($thread[$key]['child'] as $childkey) {
                if (!$admin_view && $thread[$childkey]['obj']->getVar('status') != COMMENTS_ACTIVE) {
                    // skip this comment if it is not active and continue on processing its child comments instead
                    if (isset($thread[$childkey]['child']) && !empty($thread[$childkey]['child'])) {
                        foreach ($thread[$childkey]['child'] as $childchildkey) {
                            $this->_renderNestReplies($thread, $childchildkey, $replies, $prefix, $admin_view, $depth);
                        }
                    }
                } else {
                    $this->_renderNestReplies($thread, $childkey, $replies, $prefix, $admin_view, $depth);
                }
            }
        }
    }

    /**
     * Get the name of the poster
     *
     * @param int $poster_id
     *
     * @return array
     * @access private
     */
    private function _getPosterName($poster_id)
    {
        $poster['id'] = intval($poster_id);
        if ($poster['id'] > 0) {
            $user = Xoops::getInstance()->getHandlerMember()->getUser($poster['id']);
            if (!is_object($user)) {
                $poster['id'] = 0;
            }
        }
        $poster['uname'] = XoopsUserUtility::getUnameFromId($poster['id'], false, true);
        return $poster;
    }

    /**
     * Get an array with info about the poster
     *
     * @param int $poster_id
     *
     * @return array
     * @access private
     */
    private function _getPosterArray($poster_id)
    {
        $poster['id'] = intval($poster_id);
        if ($poster['id'] > 0) {
            $xoops = Xoops::getInstance();
            $member_handler = $xoops->getHandlerMember();
            $user = $member_handler->getUser($poster['id']);
            if (is_object($user)) {
                $poster['uname'] = XoopsUserUtility::getUnameFromId($poster['id'], false, true);
                $poster_rank = $user->rank();
                $poster['rank_image'] = ($poster_rank['image'] != '') ? $poster_rank['image'] : 'blank.gif';
                $poster['rank_title'] = $poster_rank['title'];
                $response = $xoops->service("Avatar")->getAvatarUrl($user);
                $avatar = $response->getValue();
                $avatar = empty($avatar) ? '' : $avatar;
                $poster['avatar'] = $avatar;
                $poster['regdate'] = XoopsLocale::formatTimestamp($user->getVar('user_regdate'), 's');
                $poster['from'] = $user->getVar('user_from');
                $poster['postnum'] = $user->getVar('posts');
                $poster['status'] = $user->isOnline() ? _MD_COMMENTS_ONLINE : '';
                return $poster;
            } else {
                $poster['id'] = 0;
            }
        }

        $poster['uname'] = XoopsUserUtility::getUnameFromId($poster['id'], false, true);
        $poster['rank_title'] = '';
        $poster['avatar'] = 'blank.gif';
        $poster['regdate'] = '';
        $poster['from'] = '';
        $poster['postnum'] = 0;
        $poster['status'] = '';
        return $poster;
    }

    /**
     * Get the IMG tag for the title icon
     *
     * @param string $icon_image
     *
     * @return string HTML IMG tag
     * @access private
     */
    private function _getTitleIcon($icon_image)
    {
        $icon_image = htmlspecialchars(trim($icon_image));
        if ($icon_image != '') {
            if (false != $this->_doIconCheck) {
                if (!XoopsLoad::fileExists(Xoops::getInstance()->path('images/subject/' . $icon_image))) {
                    return '<img src="' . XOOPS_URL . '/images/icons/no_posticon.gif" alt="" />';
                } else {
                    return '<img src="' . XOOPS_URL . '/images/subject/' . $icon_image . '" alt="" />';
                }
            } else {
                return '<img src="' . XOOPS_URL . '/images/subject/' . $icon_image . '" alt="" />';
            }
        }
        return '<img src="' . XOOPS_URL . '/images/icons/no_posticon.gif" alt="" />';
    }
}
