<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\ObjectTree;
use Xoops\Core\XoopsTpl;

/**
 * @copyright       XOOPS Project (http://xoops.org)
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Comments
 * @author          trabis <lusopoemas@gmail.com>
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id$
 */

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
    private $tpl;

    /**
     * @var array
     */
    private $comments = [];

    /**
     * @var bool
     */
    private $useIcons = true;

    /**
     * @var bool
     */
    private $doIconCheck = false;

    /**
     * @var array
     */
    private $statusText;

    /**
     * Constructor
     *
     * @param bool  $use_icons
     * @param bool  $do_iconcheck
     */
    public function __construct(XoopsTpl $tpl, $use_icons = true, $do_iconcheck = false)
    {
        $this->tpl = $tpl;
        $this->useIcons = $use_icons;
        $this->doIconCheck = $do_iconcheck;
        $this->statusText = [
            Comments::STATUS_PENDING => '<span style="text-decoration: none; font-weight: bold; color: #00ff00;">'
                . _MD_COMMENTS_PENDING . '</span>',
            Comments::STATUS_ACTIVE => '<span style="text-decoration: none; font-weight: bold; color: #ff0000;">'
                . _MD_COMMENTS_ACTIVE . '</span>',
            Comments::STATUS_HIDDEN => '<span style="text-decoration: none; font-weight: bold; color: #0000ff;">'
                . _MD_COMMENTS_HIDDEN . '</span>',
        ];
    }

    /**
     * Access the only instance of this class
     *
     * @param XoopsTpl $tpl          reference to a {@link XoopsTpl} object
     * @param bool  $use_icons    use image icons
     * @param bool  $do_iconcheck do icon check
     *
     * @return CommentsCommentRenderer
     */
    public static function getInstance(XoopsTpl $tpl, $use_icons = true, $do_iconcheck = false)
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
     * @param array $comments_arr array of CommentsComment objects
     *
     * @return void
     */
    public function setComments(&$comments_arr)
    {
        if (isset($this->comments)) {
            unset($this->comments);
        }
        $this->comments = &$comments_arr;
    }

    /**
     * Render the comments in flat view
     *
     * @param bool $admin_view
     *
     * @return void
     */
    public function renderFlatView($admin_view = false)
    {
        foreach ($this->comments as $i => $comment) {
            /* @var $comment CommentsComment */
            $image = (false != $this->useIcons) ? $this->getTitleIcon($comment->getVar('icon')) : '';
            $title = $comment->getVar('title');

            $poster = $this->getPosterArray($comment->getVar('uid'));
            if (false != $admin_view) {
                $text = $comment->getVar('text')
                    . '<div style="text-align:right; margin-top: 2px; margin-bottom: 0px; margin-right: 2px;">'
                    . _MD_COMMENTS_STATUS . ': ' . $this->statusText[$comment->getVar('status')]
                    . '<br />IP: <span style="font-weight: bold;">' . $comment->getVar('ip') . '</span></div>';
            } else {
                // hide comments that are not active
                if (Comments::STATUS_ACTIVE != $comment->getVar('status')) {
                    continue;
                }
                $text = $comment->getVar('text');
            }
            $this->comments[$i] = $comment;
            $this->tpl->append('comments', [
                'id' => $comment->getVar('id'),
                'image' => $image,
                'title' => $title,
                'text' => $text,
                'date_posted' => XoopsLocale::formatTimestamp($comment->getVar('created'), 'm'),
                'date_modified' => XoopsLocale::formatTimestamp($comment->getVar('modified'), 'm'),
                'poster' => $poster,
            ]);
        }
    }

    /**
     * Render the comments in thread view
     * This method calls itself recursively
     *
     * @param int $comment_id Should be "0" when called by client
     * @param bool $admin_view
     * @param bool $show_nav
     *
     * @return void
     */
    public function renderThreadView($comment_id = 0, $admin_view = false, $show_nav = true)
    {
        // construct comment tree
        $xot = new ObjectTree($this->comments, 'id', 'pid', 'rootid');
        $tree = $xot->getTree();

        $image = (false != $this->useIcons) ? $this->getTitleIcon($tree[$comment_id]['obj']->getVar('icon')) : '';
        $title = $tree[$comment_id]['obj']->getVar('title');
        if (false != $show_nav && 0 != $tree[$comment_id]['obj']->getVar('pid')) {
            $this->tpl->assign('lang_top', _MD_COMMENTS_TOP);
            $this->tpl->assign('lang_parent', _MD_COMMENTS_PARENT);
            $this->tpl->assign('show_threadnav', true);
        } else {
            $this->tpl->assign('show_threadnav', false);
        }
        if (false != $admin_view) {
            // admins can see all
            $text = $tree[$comment_id]['obj']->getVar('text')
                . '<div style="text-align:right; margin-top: 2px; margin-bottom: 0px; margin-right: 2px;">'
                . _MD_COMMENTS_STATUS . ': ' . $this->statusText[$tree[$comment_id]['obj']->getVar('status')]
                . '<br />IP: <span style="font-weight: bold;">' . $tree[$comment_id]['obj']->getVar('ip')
                . '</span></div>';
        } else {
            // hide comments that are not active
            if (Comments::STATUS_ACTIVE != $tree[$comment_id]['obj']->getVar('status')) {
                // if there are any child comments, display them as root comments
                if (isset($tree[$comment_id]['child']) && !empty($tree[$comment_id]['child'])) {
                    foreach ($tree[$comment_id]['child'] as $child_id) {
                        $this->renderThreadView($child_id, $admin_view, false);
                    }
                }

                return;
            }
            $text = $tree[$comment_id]['obj']->getVar('text');
        }
        $replies = [];
        $this->renderThreadReplies($tree, $comment_id, $replies, '&nbsp;&nbsp;', $admin_view);
        $show_replies = (count($replies) > 0) ? true : false;
        $this->tpl->append('comments', [
            'pid' => $tree[$comment_id]['obj']->getVar('pid'),
            'id' => $tree[$comment_id]['obj']->getVar('id'),
            'itemid' => $tree[$comment_id]['obj']->getVar('itemid'),
            'rootid' => $tree[$comment_id]['obj']->getVar('rootid'),
            'image' => $image,
            'title' => $title,
            'text' => $text,
            'date_posted' => XoopsLocale::formatTimestamp($tree[$comment_id]['obj']->getVar('created'), 'm'),
            'date_modified' => XoopsLocale::formatTimestamp($tree[$comment_id]['obj']->getVar('modified'), 'm'),
            'poster' => $this->getPosterArray($tree[$comment_id]['obj']->getVar('uid')),
            'replies' => $replies,
            'show_replies' => $show_replies,
        ]);
    }

    /**
     * Render replies to a thread
     *
     * @param array   $thread
     * @param int     $key
     * @param array   $replies
     * @param string  $prefix
     * @param bool    $admin_view
     * @param int $depth
     * @param string  $current_prefix
     *
     * @return void
     */
    private function renderThreadReplies(
        &$thread,
        $key,
        &$replies,
        $prefix,
        $admin_view,
        $depth = 0,
        $current_prefix = ''
    ) {
        if ($depth > 0) {
            $image = (false != $this->useIcons) ? $this->getTitleIcon($thread[$key]['obj']->getVar('icon')) : '';
            $title = $thread[$key]['obj']->getVar('title');
            $title = (false != $admin_view)
                ? $title . ' ' . $this->statusText[$thread[$key]['obj']->getVar('status')] : $title;
            $replies[] = [
                'id' => $key,
                'prefix' => $current_prefix,
                'date_posted' => XoopsLocale::formatTimestamp($thread[$key]['obj']->getVar('created'), 'm'),
                'title' => $title,
                'image' => $image,
                'root_id' => $thread[$key]['obj']->getVar('rootid'),
                'status' => $this->statusText[$thread[$key]['obj']->getVar('status')],
                'poster' => $this->getPosterName($thread[$key]['obj']->getVar('uid')),
            ];
            $current_prefix .= $prefix;
        }
        if (isset($thread[$key]['child']) && !empty($thread[$key]['child'])) {
            ++$depth;
            foreach ($thread[$key]['child'] as $childkey) {
                if (!$admin_view && Comments::STATUS_ACTIVE != $thread[$childkey]['obj']->getVar('status')) {
                    // skip this comment if it is not active and continue on processing its child comments instead
                    if (isset($thread[$childkey]['child']) && !empty($thread[$childkey]['child'])) {
                        foreach ($thread[$childkey]['child'] as $childchildkey) {
                            $this->renderThreadReplies($thread, $childchildkey, $replies, $prefix, $admin_view, $depth);
                        }
                    }
                } else {
                    $this->renderThreadReplies($thread, $childkey, $replies, $prefix, $admin_view, $depth, $current_prefix);
                }
            }
        }
    }

    /**
     * Render comments in nested view
     * Danger: Recursive!
     *
     * @param int $comment_id Always "0" when called by client.
     * @param bool $admin_view
     *
     * @return void
     */
    public function renderNestView($comment_id = 0, $admin_view = false)
    {
        $xot = new ObjectTree($this->comments, 'id', 'pid', 'rootid');
        $tree = $xot->getTree();
        $image = (false != $this->useIcons) ? $this->getTitleIcon($tree[$comment_id]['obj']->getVar('icon')) : '';
        $title = $tree[$comment_id]['obj']->getVar('title');
        if (false != $admin_view) {
            $text = $tree[$comment_id]['obj']->getVar('text')
                . '<div style="text-align:right; margin-top: 2px; margin-bottom: 0px; margin-right: 2px;">'
                . _MD_COMMENTS_STATUS . ': ' . $this->statusText[$tree[$comment_id]['obj']->getVar('status')]
                . '<br />IP: <span style="font-weight: bold;">' . $tree[$comment_id]['obj']->getVar('ip')
                . '</span></div>';
        } else {
            // skip this comment if it is not active and continue on processing its child comments instead
            if (Comments::STATUS_ACTIVE != $tree[$comment_id]['obj']->getVar('status')) {
                // if there are any child comments, display them as root comments
                if (isset($tree[$comment_id]['child']) && !empty($tree[$comment_id]['child'])) {
                    foreach ($tree[$comment_id]['child'] as $child_id) {
                        $this->renderNestView($child_id, $admin_view);
                    }
                }

                return;
            }
            $text = $tree[$comment_id]['obj']->getVar('text');
        }
        $replies = [];
        $this->renderNestReplies($tree, $comment_id, $replies, 25, $admin_view);
        $this->tpl->append('comments', [
            'pid' => $tree[$comment_id]['obj']->getVar('pid'),
            'id' => $tree[$comment_id]['obj']->getVar('id'),
            'itemid' => $tree[$comment_id]['obj']->getVar('itemid'),
            'rootid' => $tree[$comment_id]['obj']->getVar('rootid'),
            'image' => $image,
            'title' => $title,
            'text' => $text,
            'date_posted' => XoopsLocale::formatTimestamp($tree[$comment_id]['obj']->getVar('created'), 'm'),
            'date_modified' => XoopsLocale::formatTimestamp($tree[$comment_id]['obj']->getVar('modified'), 'm'),
            'poster' => $this->getPosterArray($tree[$comment_id]['obj']->getVar('uid')),
            'replies' => $replies,
        ]);
    }

    /**
     * Render replies in nested view
     *
     * @param array   $thread
     * @param int $key
     * @param array   $replies
     * @param int $prefix     width of td element prefixed to comment display (indent)
     * @param bool    $admin_view
     * @param int $depth
     *
     * @return void
     */
    private function renderNestReplies(&$thread, $key, &$replies, $prefix, $admin_view, $depth = 0)
    {
        if ($depth > 0) {
            $image = (false != $this->useIcons) ? $this->getTitleIcon($thread[$key]['obj']->getVar('icon')) : '';
            $title = $thread[$key]['obj']->getVar('title');
            $text = (false != $admin_view) ? $thread[$key]['obj']->getVar('text')
                . '<div style="text-align:right; margin-top: 2px; margin-right: 2px;">'
                . _MD_COMMENTS_STATUS . ': ' . $this->statusText[$thread[$key]['obj']->getVar('status')]
                . '<br />IP: <span style="font-weight: bold;">' . $thread[$key]['obj']->getVar('ip')
                . '</span></div>'
                : $thread[$key]['obj']->getVar('text');
            $replies[] = [
                'id' => $key,
                'prefix' => $prefix,
                'pid' => $thread[$key]['obj']->getVar('pid'),
                'itemid' => $thread[$key]['obj']->getVar('itemid'),
                'rootid' => $thread[$key]['obj']->getVar('rootid'),
                'title' => $title,
                'image' => $image,
                'text' => $text,
                'date_posted' => XoopsLocale::formatTimestamp($thread[$key]['obj']->getVar('created'), 'm'),
                'date_modified' => XoopsLocale::formatTimestamp($thread[$key]['obj']->getVar('modified'), 'm'),
                'poster' => $this->getPosterArray($thread[$key]['obj']->getVar('uid')),
            ];

            $prefix = $prefix + 25;
        }
        if (isset($thread[$key]['child']) && !empty($thread[$key]['child'])) {
            ++$depth;
            foreach ($thread[$key]['child'] as $childkey) {
                if (!$admin_view && Comments::STATUS_ACTIVE != $thread[$childkey]['obj']->getVar('status')) {
                    // skip this comment if it is not active and continue on processing its child comments instead
                    if (isset($thread[$childkey]['child']) && !empty($thread[$childkey]['child'])) {
                        foreach ($thread[$childkey]['child'] as $childchildkey) {
                            $this->renderNestReplies($thread, $childchildkey, $replies, $prefix, $admin_view, $depth);
                        }
                    }
                } else {
                    $this->renderNestReplies($thread, $childkey, $replies, $prefix, $admin_view, $depth);
                }
            }
        }
    }

    /**
     * Get the name of the poster
     *
     * @param int $poster_id uid of poster
     *
     * @return array
     * @access private
     */
    private function getPosterName($poster_id)
    {
        $poster['id'] = (int)($poster_id);
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
    private function getPosterArray($poster_id)
    {
        $xoops = Xoops::getInstance();
        $poster['id'] = (int)($poster_id);
        if ($poster['id'] > 0) {
            $member_handler = $xoops->getHandlerMember();
            $user = $member_handler->getUser($poster['id']);
            if (is_object($user)) {
                $poster['uname'] = XoopsUserUtility::getUnameFromId($poster['id'], false, true);
                $poster_rank = $user->rank();
                $poster['rank_image'] = $poster_rank['image'];
                $poster['rank_title'] = $poster_rank['title'];
                $response = $xoops->service('Avatar')->getAvatarUrl($user);
                $avatar = $response->getValue();
                $avatar = empty($avatar) ? $xoops->url('uploads/blank.gif') : $avatar;
                $poster['avatar'] = $avatar;
                $poster['regdate'] = XoopsLocale::formatTimestamp($user->getVar('user_regdate'), 's');
                $poster['from'] = $user->getVar('user_from');
                $poster['postnum'] = $user->getVar('posts');
                $poster['status'] = $user->isOnline() ? _MD_COMMENTS_ONLINE : '';

                return $poster;
            }
            $poster['id'] = 0;
        }

        $poster['uname'] = XoopsUserUtility::getUnameFromId($poster['id'], false, true);
        $poster['rank_title'] = '';
        $poster['avatar'] = $xoops->url('uploads/blank.gif');
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
    private function getTitleIcon($icon_image)
    {
        $icon_image = htmlspecialchars(trim($icon_image));
        if ('' != $icon_image) {
            if (false != $this->doIconCheck) {
                if (!XoopsLoad::fileExists(Xoops::getInstance()->path('images/subject/' . $icon_image))) {
                    return '<img src="' . \XoopsBaseConfig::get('url')
                        . '/images/icons/no_posticon.gif" alt="" />&nbsp;';
                }

                return '<img src="' . \XoopsBaseConfig::get('url') . '/images/subject/' . $icon_image
                        . '" alt="" />&nbsp;';
            }

            return '<img src="' . \XoopsBaseConfig::get('url') . '/images/subject/' . $icon_image
                    . '" alt="" />&nbsp;';
        }

        return '<img src="' . XOOPS_URL . '/images/icons/no_posticon.gif" alt="" />&nbsp;';
    }
}
