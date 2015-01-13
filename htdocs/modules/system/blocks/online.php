<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

use Xoops\Core\Kernel\Criteria;

/**
 * Blocks functions
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @author      Kazumi Ono (AKA onokazu)
 * @package     system
 * @version     $Id$
 */

function b_system_online_show()
{
    $xoops = Xoops::getInstance();
    $online_handler = $xoops->getHandlerOnline();
    mt_srand((double)microtime() * 1000000);
    // set gc probabillity to 10% for now..
    if (mt_rand(1, 100) < 11) {
        $online_handler->gc(300);
    }
    if ($xoops->isUser()) {
        $uid = $xoops->user->getVar('uid');
        $uname = $xoops->user->getVar('uname');
    } else {
        $uid = 0;
        $uname = '';
    }
    if ($xoops->isModule()) {
        $online_handler->write($uid, $uname, time(), $xoops->module->getVar('mid'), $_SERVER['REMOTE_ADDR']);
    } else {
        $online_handler->write($uid, $uname, time(), 0, $_SERVER['REMOTE_ADDR']);
    }
    $onlines = $online_handler->getAll(null, null, false, false);
    if (false != $onlines) {
        $total = count($onlines);
        $block = array();
        $guests = 0;
        $members = '';
        for ($i = 0; $i < $total; $i++) {
            if ($onlines[$i]['online_uid'] > 0) {
                $members .= ' <a href="' . XOOPS_URL . '/userinfo.php?uid=' . $onlines[$i]['online_uid'] . '" title="' . $onlines[$i]['online_uname'] . '">' . $onlines[$i]['online_uname'] . '</a>,';
            } else {
                $guests++;
            }
        }
        $block['online_total'] = sprintf(XoopsLocale::F_USERS_ONLINE, $total);
        if ($xoops->isModule()) {
            $mytotal = $online_handler->getCount(new Criteria('online_module', $xoops->module->getVar('mid')));
            $block['online_total'] .= ' (' . sprintf(XoopsLocale::F_USERS_BROWSING, $mytotal, $xoops->module->getVar('name')) . ')';
        }
        $block['lang_members'] = XoopsLocale::MEMBERS;
        $block['lang_guests'] = XoopsLocale::GUESTS;
        $block['online_names'] = $members;
        $block['online_members'] = $total - $guests;
        $block['online_guests'] = $guests;
        $block['lang_more'] = XoopsLocale::MORE;
        return $block;
    } else {
        return false;
    }
}
