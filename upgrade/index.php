<?php
/**
 * Upgrader index file
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         upgrader
 * @since           2.3.0
 * @author          Skalpa Keo <skalpa@xoops.org>
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id$
 */
@include_once '../mainfile.php';

@set_time_limit(0);
error_reporting(0);

require './abstract.php';

defined('XOOPS_ROOT_PATH') or die('Bad installation: please add this folder to the XOOPS install you want to upgrade');

/*
 * gets list of name of directories inside a directory
 */
function getDirList($dirname)
{
    $dirlist = [];
    if (is_dir($dirname) && $handle = opendir($dirname)) {
        while (false !== ($file = readdir($handle))) {
            if ('.' != mb_substr($file, 0, 1) && 'cvs' != mb_strtolower($file)) {
                if (is_dir("{$dirname}/{$file}")) {
                    $dirlist[] = $file;
                }
            }
        }
        closedir($handle);
        asort($dirlist);
        reset($dirlist);
    }

    return $dirlist;
}

function getDbValue($db, $table, $field, $condition = '')
{
    $xoops = Xoops::getInstance();
    $table = $db->prefix($table);
    $sql = "SELECT `{$field}` FROM `{$table}`";
    if ($condition) {
        $sql .= " WHERE {$condition}";
    }
    $result = $db->query($sql);
    if ($result) {
        $row = $db->fetchRow($result);
        if ($row) {
            return $row[0];
        }
    }

    return false;
}

$upgrade_language = $xoops->getConfig('language');
// $xoopsConfig might not be able fetched
if (empty($upgrade_language)) {
    include_once './language.php';
    $upgrade_language = xoops_detectLanguage();
}

if (file_exists("./language/{$upgrade_language}/upgrade.php")) {
    include_once "./language/{$upgrade_language}/upgrade.php";
} elseif (file_exists("./language/{$upgrade_language}_utf8/upgrade.php")) {
    include_once "./language/{$upgrade_language}_utf8/upgrade.php";
    $upgrade_language .= '_utf8';
} elseif (file_exists('./language/english/upgrade.php')) {
    include_once './language/english/upgrade.php';
    $upgrade_language = 'english';
} else {
    echo 'no language file.';
    exit();
}

ob_start();
if (!$xoops->isUser() || !$xoops->user->isAdmin()) {
    include_once 'login.php';
} else {
    $op = @$_REQUEST['action'];
    if (empty($_SESSION['xoops_upgrade']['steps'])) {
        $op = '';
    }
    if (empty($op)) {
        include_once 'check_version.php';
    } else {
        $next = array_shift($_SESSION['xoops_upgrade']['steps']);
        printf('<h2>' . _PERFORMING_UPGRADE . '</h2>', $next);
        $upgrader = include_once "{$next}/index.php";
        $res = $upgrader->apply();
        if ($message = $upgrader->message()) {
            echo '<p>' . $message . '</p>';
        }

        if (!$res) {
            array_unshift($_SESSION['xoops_upgrade']['steps'], $next);
            echo '<a id="link-next" href="index.php?action=next">' . _RELOAD . '</a>';
        } else {
            if (empty($_SESSION['xoops_upgrade']['steps'])) {
                $text = _FINISH;
            } else {
                list($key, $val) = each($_SESSION['xoops_upgrade']['steps']);
                $text = sprintf(_APPLY_NEXT, $val);
            }
            echo '<a id="link-next" href="index.php?action=next">' . $text . '</a>';
        }
    }
}
$content = ob_get_contents();
ob_end_clean();

include_once 'upgrade_tpl.php';
