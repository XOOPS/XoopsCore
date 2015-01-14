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
 * Protector
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         protector
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id$
 */

include_once __DIR__ . '/header.php';

require_once dirname(__DIR__) . '/class/gtickets.php';

$xoops->db();
global $xoopsDB;
$db = $xoopsDB;

// GET vars
$pos = empty($_GET['pos']) ? 0 : intval($_GET['pos']);
$num = empty($_GET['num']) ? 20 : intval($_GET['num']);

// Table Name
$log_table = $db->prefix('protector_log');

// Protector object
require_once dirname(__DIR__) . '/class/protector.php';
$protector = Protector::getInstance($db->conn);
$conf = $protector->getConf();

//
// transaction stage
//

if (!empty($_POST['action'])) {

    // Ticket check
    if (!$xoopsGTicket->check(true, 'protector_admin')) {
        $xoops->redirect(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    if ($_POST['action'] == 'update_ips') {
        $error_msg = '';

        $lines = empty($_POST['bad_ips']) ? array() : explode("\n", trim($_POST['bad_ips']));
        $bad_ips = array();
        foreach ($lines as $line) {
            @list($bad_ip, $jailed_time) = explode(':', $line, 2);
            $bad_ips[trim($bad_ip)] = empty($jailed_time) ? 0x7fffffff : intval($jailed_time);
        }
        if (!$protector->write_file_badips($bad_ips)) {
            $error_msg .= _AM_MSG_BADIPSCANTOPEN;
        }

        $group1_ips = empty($_POST['group1_ips']) ? array() : explode("\n", trim($_POST['group1_ips']));
        foreach (array_keys($group1_ips) as $i) {
            $group1_ips[$i] = trim($group1_ips[$i]);
        }
        $fp = @fopen($protector->get_filepath4group1ips(), 'w');
        if ($fp) {
            @flock($fp, LOCK_EX);
            fwrite($fp, serialize(array_unique($group1_ips)) . "\n");
            @flock($fp, LOCK_UN);
            fclose($fp);
        } else {
            $error_msg .= _AM_MSG_GROUP1IPSCANTOPEN;
        }

        $redirect_msg = $error_msg ? $error_msg : _AM_MSG_IPFILESUPDATED;
        $xoops->redirect("center.php", 2, $redirect_msg);
    } else {
        if ($_POST['action'] == 'delete' && isset($_POST['ids']) && is_array($_POST['ids'])) {
            // remove selected records
            foreach ($_POST['ids'] as $lid) {
                $lid = intval($lid);
                $db->query("DELETE FROM $log_table WHERE lid='$lid'");
            }
            $xoops->redirect("center.php", 2, _AM_MSG_REMOVED);
        } else {
            if ($_POST['action'] == 'deleteall') {
                // remove all records
                $db->query("DELETE FROM $log_table");
                $xoops->redirect("center.php", 2, _AM_MSG_REMOVED);
            } else {
                if ($_POST['action'] == 'compactlog') {
                    // compactize records (removing duplicated records (ip,type)
                    $result = $db->query("SELECT `lid`,`ip`,`type` FROM $log_table ORDER BY lid DESC");
                    $buf = array();
                    $ids = array();
                    while (list($lid, $ip, $type) = $db->fetchRow($result)) {
                        if (isset($buf[$ip . $type])) {
                            $ids[] = $lid;
                        } else {
                            $buf[$ip . $type] = true;
                        }
                    }
                    $db->query("DELETE FROM $log_table WHERE lid IN (" . implode(',', $ids) . ")");
                    $xoops->redirect("center.php", 2, _AM_MSG_REMOVED);
                }
            }
        }
    }
}
// beggining of Output
$xoops->header('admin:protector/protector_center.html');

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('center.php');

// bad_ips
$bad_ips = $protector->get_bad_ips(true);
uksort($bad_ips, 'protector_ip_cmp');
$bad_ips4disp = '';
foreach ($bad_ips as $bad_ip => $jailed_time) {
    $line = $jailed_time ? $bad_ip . ':' . $jailed_time : $bad_ip;
    $line = str_replace(':2147483647', '', $line); // remove :0x7fffffff
    $bad_ips4disp .= htmlspecialchars($line, ENT_QUOTES) . "\n";
}

// group1_ips
$group1_ips = $protector->get_group1_ips();
usort($group1_ips, 'protector_ip_cmp');
$group1_ips4disp = htmlspecialchars(implode("\n", $group1_ips), ENT_QUOTES);
// edit configs about IP ban and IPs for group=1

$form = $xoops->getModuleForm(null, 'center');
$form->getPrefIp($bad_ips4disp, $group1_ips4disp);
$form->render();

// header of log listing
// query for listing
$rs = $db->query("SELECT count(lid) FROM $log_table");
list($numrows) = $db->fetchRow($rs);
$prs = $db->query("SELECT l.lid, l.uid, l.ip, l.agent, l.type, l.description, UNIX_TIMESTAMP(l.timestamp), u.uname FROM $log_table l LEFT JOIN " . $db->prefix("users") . " u ON l.uid=u.uid ORDER BY timestamp DESC LIMIT $pos,$num");
// Number selection
$num_options = '';
$num_array = array(20, 100, 500, 2000);
foreach ($num_array as $n) {
    if ($n == $num) {
        $num_options .= "<option value='$n' selected='selected'>$n</option>\n";
    } else {
        $num_options .= "<option value='$n'>$n</option>\n";
    }
}
$xoops->tpl()->assign('num_options', $num_options);
// Page Navigation
$nav = new XoopsPageNav($numrows, $num, $pos, 'pos', "num=$num");
$nav_html = $nav->renderNav(10);
$xoops->tpl()->assign('nav_html', $nav_html);
// body of log listing
$oddeven = 'odd';
while (list($lid, $uid, $ip, $agent, $type, $description, $timestamp, $uname) = $db->fetchRow($prs)) {
    $oddeven = ($oddeven == 'odd' ? 'even' : 'odd');

    $ip = htmlspecialchars($ip, ENT_QUOTES);
    $type = htmlspecialchars($type, ENT_QUOTES);
    $description = htmlspecialchars($description, ENT_QUOTES);
    $uname = htmlspecialchars(($uid ? $uname : XoopsLocale::GUESTS), ENT_QUOTES);

    // make agents shorter
    if (preg_match('/MSIE\s+([0-9.]+)/', $agent, $regs)) {
        $agent_short = 'IE ' . $regs[1];
    } else {
        if (stristr($agent, 'Gecko') !== false) {
            $agent_short = strrchr($agent, ' ');
        } else {
            $agent_short = substr($agent, 0, strpos($agent, ' '));
        }
    }
    $agent4disp = htmlspecialchars($agent, ENT_QUOTES);
    $agent_desc = $agent == $agent_short ? $agent4disp : htmlspecialchars($agent_short, ENT_QUOTES) . "<img src='../images/dotdotdot.gif' alt='$agent4disp' title='$agent4disp' />";

    $log_arr['lid'] = $lid;
    $log_arr['date'] = XoopsLocale::formatTimestamp($timestamp);
    $log_arr['uname'] = $uname;
    $log_arr['ip'] = $ip;
    $log_arr['agent_desc'] = $agent_desc;
    $log_arr['type'] = $type;
    $log_arr['description'] = $description;

    $xoops->tpl()->appendByRef('log', $log_arr);
    unset($table_arr);
}

$xoops->footer();

function protector_ip_cmp($a, $b)
{
    $as = explode('.', $a);
    $aval = @$as[0] * 167777216 + @$as[1] * 65536 + @$as[2] * 256 + @$as[3];
    $bs = explode('.', $b);
    $bval = @$bs[0] * 167777216 + @$bs[1] * 65536 + @$bs[2] * 256 + @$bs[3];

    return $aval > $bval ? 1 : -1;
}
