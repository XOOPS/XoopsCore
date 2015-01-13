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

$xoops = Xoops::getInstance();
$xoops->db();
global $xoopsDB;
$db = $xoopsDB;
$xoops->header('admin:protector/protector_prefix.html');

$error = '';
// COPY TABLES
if (!empty($_POST['copy']) && !empty($_POST['old_prefix'])) {

    if (preg_match('/[^0-9A-Za-z_-]/', $_POST['new_prefix'])) {
        $xoops->tpl()->assign('error', _AM_PROTECTOR_PREFIX_ERROR_WP);
        $xoops->footer();
        exit;
    }

    // Ticket check
    if (!$xoopsGTicket->check(true, 'protector_admin')) {
        $xoops->redirect(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    $new_prefix = empty($_POST['new_prefix']) ? 'x' . substr(md5(time()), -5) : $_POST['new_prefix'];
    $old_prefix = $_POST['old_prefix'];

    $srs = $db->queryF('SHOW TABLE STATUS FROM `' . XOOPS_DB_NAME . '`');

    if (!$db->getRowsNum($srs)) {
        $xoops->tpl()->assign('error', _AM_PROTECTOR_PREFIX_ERROR_NACT);
        $xoops->footer();
        exit;
    }

    $count = 0;
    while ($row_table = $db->fetchArray($srs)) {
        $count++;
        $old_table = $row_table['Name'];
        if (substr($old_table, 0, strlen($old_prefix) + 1) !== $old_prefix . '_') {
            continue;
        }

        $new_table = $new_prefix . substr($old_table, strlen($old_prefix));

        $crs = $db->queryF('SHOW CREATE TABLE ' . $old_table);
        if (!$db->getRowsNum($crs)) {
            $error .= sprintf(_AM_PROTECTOR_PREFIX_ERROR_SCT, $old_table) . '<br />';
            continue;
        }
        $row_create = $db->fetchArray($crs);
        $create_sql = preg_replace("/^CREATE TABLE `$old_table`/", "CREATE TABLE `$new_table`", $row_create['Create Table'], 1);

        $crs = $db->queryF($create_sql);
        if (!$crs) {
            $error .= sprintf(_AM_PROTECTOR_PREFIX_ERROR_CT, $new_table) . '<br />';
            continue;
        }

        $irs = $db->queryF("INSERT INTO `$new_table` SELECT * FROM `$old_table`");
        if (!$irs) {
            $error .= sprintf(_AM_PROTECTOR_PREFIX_ERROR_II, $new_table) . '<br />';
            continue;
        }
    }

    if ($xoops->isActiveModule('logger')) {
        $_SESSION['protector_logger'] = Logger::getInstance()->dump('queries');
    }

    if ($error != '') {
        $xoops->tpl()->assign('error', $error);
        $xoops->footer();
    } else {
        $xoops->redirect('prefix_manager.php', 1, _AM_MSG_DBUPDATED);
    }
    // DUMP INTO A LOCAL FILE
} else {
    if (!empty($_POST['backup']) && !empty($_POST['prefix'])) {
        if (preg_match('/[^0-9A-Za-z_-]/', $_POST['prefix'])) {
            $xoops->tpl()->assign('error', _AM_PROTECTOR_PREFIX_ERROR_WP);
            $xoops->footer();
            exit;
        }

        // Ticket check
        if (!$xoopsGTicket->check(true, 'protector_admin')) {
            $xoops->redirect(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
        }

        $prefix = $_POST['prefix'];

        // get table list
        $srs = $db->queryF('SHOW TABLE STATUS FROM `' . XOOPS_DB_NAME . '`');
        if (!$db->getRowsNum($srs)) {
            $xoops->tpl()->assign('error', _AM_PROTECTOR_PREFIX_ERROR_NADT);
            $xoops->footer();
            exit;
        }

        $export_string = '';

        while ($row_table = $db->fetchArray($srs)) {
            $table = $row_table['Name'];
            if (substr($table, 0, strlen($prefix) + 1) !== $prefix . '_') {
                continue;
            }
            $drs = $db->queryF("SHOW CREATE TABLE `$table`");
            $export_string .= "\nDROP TABLE IF EXISTS `$table`;\n" . mysql_result($drs, 0, 1) . ";\n\n";
            $result = mysql_query("SELECT * FROM `$table`");
            $fields_cnt = mysql_num_fields($result);
            $field_flags = array();
            for ($j = 0; $j < $fields_cnt; $j++) {
                $field_flags[$j] = mysql_field_flags($result, $j);
            }
            $search = array("\x00", "\x0a", "\x0d", "\x1a");
            $replace = array('\0', '\n', '\r', '\Z');
            $current_row = 0;
            while ($row = mysql_fetch_row($result)) {
                $current_row++;
                for ($j = 0; $j < $fields_cnt; $j++) {
                    $fields_meta = mysql_fetch_field($result, $j);
                    // NULL
                    if (!isset($row[$j]) || is_null($row[$j])) {
                        $values[] = 'NULL';
                        // a number
                        // timestamp is numeric on some MySQL 4.1
                    } elseif ($fields_meta->numeric && $fields_meta->type != 'timestamp') {
                        $values[] = $row[$j];
                        // a binary field
                        // Note: with mysqli, under MySQL 4.1.3, we get the flag
                        // "binary" for those field types (I don't know why)
                    } else {
                        if (stristr($field_flags[$j], 'BINARY') && $fields_meta->type != 'datetime' && $fields_meta->type != 'date' && $fields_meta->type != 'time' && $fields_meta->type != 'timestamp'
                        ) {
                            // empty blobs need to be different, but '0' is also empty :-(
                            if (empty($row[$j]) && $row[$j] != '0') {
                                $values[] = '\'\'';
                            } else {
                                $values[] = '0x' . bin2hex($row[$j]);
                            }
                            // something else -> treat as a string
                        } else {
                            $values[] = '\'' . str_replace($search, $replace, addslashes($row[$j])) . '\'';
                        }
                    } // end if
                } // end for

                $export_string .= "INSERT INTO `$table` VALUES (" . implode(', ', $values) . ");\n";
                unset($values);
            } // end while
            mysql_free_result($result);
        }

        header('Content-Type: Application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $prefix . '_' . date('YmdHis') . '.sql"');
        header('Content-Length: ' . strlen($export_string));
        set_time_limit(0);
        echo $export_string;
        exit;
        // DROP TABLES
    } else {
        if (!empty($_POST['delete']) && !empty($_POST['prefix'])) {

            if (preg_match('/[^0-9A-Za-z_-]/', $_POST['prefix'])) {
                $xoops->tpl()->assign('error', _AM_PROTECTOR_PREFIX_ERROR_WP);
                $xoops->footer();
                exit;
            }

            // Ticket check
            if (!$xoopsGTicket->check(true, 'protector_admin')) {
                $xoops->redirect(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
            }

            $prefix = $_POST['prefix'];

            // check if prefix is working
            if ($prefix == XOOPS_DB_PREFIX) {
                $xoops->tpl()->assign('error', _AM_PROTECTOR_PREFIX_ERROR_CTDWT);
                $xoops->footer();
                exit;
            }

            // check if prefix_xoopscomments exists
            $check_rs = $db->queryF("SELECT * FROM {$prefix}_xoopscomments LIMIT 1");
            if (!$check_rs) {
                $xoops->tpl()->assign('error', _AM_PROTECTOR_PREFIX_ERROR_NPX);
                $xoops->footer();
                exit;
            }

            // get table list
            $srs = $db->queryF('SHOW TABLE STATUS FROM `' . XOOPS_DB_NAME . '`');
            if (!$db->getRowsNum($srs)) {
                $xoops->tpl()->assign('error', _AM_PROTECTOR_PREFIX_ERROR_NADT);
                $xoops->footer();
                exit;
            }

            while ($row_table = $db->fetchArray($srs)) {
                $table = $row_table['Name'];
                if (substr($table, 0, strlen($prefix) + 1) !== $prefix . '_') {
                    continue;
                }
                $drs = $db->queryF("DROP TABLE `$table`");
            }
            if ($xoops->isActiveModule('logger')) {
                $_SESSION['protector_logger'] = Logger::getInstance()->dump('queries');
            }

            $xoops->redirect('prefix_manager.php', 1, _AM_MSG_DBUPDATED);
        }
    }
}

// beggining of Output

$admin_page = new \Xoops\Module\Admin();
$admin_page->renderNavigation('prefix_manager.php');

$xoops->tpl()->assign('prefix', sprintf(_AM_TXT_HOWTOCHANGEDB, XOOPS_VAR_PATH . "/data/secure.php"));
$xoops->tpl()->assign('prefix_line', sprintf(_AM_PROTECTOR_PREFIX_CHANGEDBLINE, XOOPS_DB_PREFIX));

// Display Log if exists
if (!empty($_SESSION['protector_logger'])) {
    $xoops->tpl()->assign('protector_logger', $_SESSION['protector_logger']);
    $_SESSION['protector_logger'] = '';
    unset($_SESSION['protector_logger']);
}

// query
$srs = $db->queryF("SHOW TABLE STATUS FROM `" . XOOPS_DB_NAME . '`');
if (!$db->getRowsNum($srs)) {
    $xoops->tpl()->assign('error', '_AM_PROTECTOR_PREFIX_ERROR_NACT');
    $xoops->footer();
    exit;
}

// search prefixes
$tables = array();
$prefixes = array();
while ($row_table = $db->fetchArray($srs)) {
    if (substr($row_table["Name"], -6) === '_users') {
        $prefixes[] = array(
            'name'    => substr($row_table["Name"], 0, -6),
            'updated' => $row_table["Update_time"]
        );
    }
    $tables[] = $row_table["Name"];
}

$xoops->tpl()->assign('aff_table', true);
foreach ($prefixes as $prefix) {
    // count the number of tables with the prefix
    $table_count = 0;
    $has_xoopscomments = false;
    foreach ($tables as $table) {
        if ($table == $prefix['name'] . '_xoopscomments') {
            $has_xoopscomments = true;
        }
        if (substr($table, 0, strlen($prefix['name']) + 1) === $prefix['name'] . '_') {
            $table_count++;
        }
    }
    // check if prefix_xoopscomments exists
    if (!$has_xoopscomments) {
        continue;
    }

    $prefix4disp = htmlspecialchars($prefix['name'], ENT_QUOTES);
    $ticket_input = $xoopsGTicket->getTicketHtml(__LINE__, 1800, 'protector_admin');

    if ($prefix['name'] == XOOPS_DB_PREFIX) {
        $del_button = false;
    } else {
        $del_button = true;
    }

    $table_arr['prefix'] = $prefix4disp;
    $table_arr['count'] = $table_count;
    $table_arr['update'] = $prefix['updated'];
    $table_arr['ticket'] = $ticket_input;
    $table_arr['del'] = $del_button;

    $xoops->tpl()->appendByRef('table', $table_arr);
    unset($table_arr);
}
$xoops->footer();
