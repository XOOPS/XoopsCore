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
 * Upgrader from 2.0.18 to 2.3.0
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright    2000-2020 XOOPS Project https://xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package     upgrader
 * @since       2.3.0
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id$
 */
include_once dirname(__FILE__) . '/pathcontroller.php';

class upgrade_230 extends xoopsUpgrade
{
    public $usedFiles = ['mainfile.php'];

    public $tasks = ['config', 'cache', 'path', 'db', 'bmlink'];

    public function upgrade_230()
    {
        $this->xoopsUpgrade(basename(dirname(__FILE__)));
    }

    /**
     * Check if cpanel config already exists
     */
    public function check_config()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $sql = 'SELECT COUNT(*) FROM `' . $db->prefix('config') . "` WHERE `conf_name` IN ('welcome_type', 'cpanel')";
        if (!$result = $db->queryF($sql)) {
            return false;
        }
        list($count) = $db->fetchRow($result);

        return (2 == $count) ? true : false;
    }

    /**
     * Check if cache_model table already exists
     */
    public function check_cache()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $sql = "SHOW TABLES LIKE '" . $db->prefix('cache_model') . "'";
        $result = $db->queryF($sql);
        if (!$result) {
            return false;
        }
        if ($db->getRowsNum($result) > 0) {
            return true;
        }

        return false;
        /*
        $sql = "SELECT COUNT(*) FROM `" . $db->prefix('cache_model') . "`";
        if ( !$result = $db->queryF( $sql ) ) {
            return false;
        }
        return true;
        */
    }

    /**
     * Check if primary key for `block_module_link` is already set
     */
    public function check_bmlink()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        // MySQL 5.0+
        //$sql = "SHOW KEYS FROM `" . $db->prefix('block_module_link') . "` WHERE `KEY_NAME` LIKE 'PRIMARY'";
        $sql = 'SHOW KEYS FROM `' . $db->prefix('block_module_link');
        if (!$result = $db->queryF($sql)) {
            return false;
        }
        while (false !== ($row = $db->fetchArray($result))) {
            if ('PRIMARY' == $row['Key_name']) {
                return true;
            }
        }

        return false;
    }

    public function apply_bmlink()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $sql = 'SHOW KEYS FROM `' . $db->prefix('block_module_link');
        if (!$result = $db->queryF($sql)) {
            return false;
        }
        $keys_drop = [];
        $primary_add = true;
        while (false !== ($row = $db->fetchArray($result))) {
            if ('PRIMARY' == $row['Key_name']) {
                $primary_add = false;
            }
            if (in_array($row['Key_name'], ['block_id', 'module_id'])) {
                $keys_drop[] = $row['Key_name'];
            }
        }
        foreach ($keys_drop as $drop) {
            $sql = 'ALTER TABLE `' . $db->prefix('block_module_link') . "` DROP KEY `{$drop}`";
            $db->queryF($sql);
        }
        if ($primary_add) {
            $sql = 'ALTER IGNORE TABLE `' . $db->prefix('block_module_link') . '` ADD PRIMARY KEY (`block_id`, `module_id`)';

            return $db->queryF($sql);
        }

        return true;
    }

    public function apply_config()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $result = true;
        if (!$xoops->getConfig('cpanel')) {
            $sql = 'INSERT INTO ' . $db->prefix('config') . ' (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) ' . ' VALUES ' . " (NULL, 0, 1, 'cpanel', '_MD_AM_CPANEL', 'default', '_MD_AM_CPANELDSC', 'cpanel', 'other', 11)";

            $result *= $db->queryF($sql);
        }

        $welcometype_installed = false;
        $sql = 'SELECT COUNT(*) FROM `' . $db->prefix('config') . "` WHERE `conf_name` = 'welcome_type'";
        if ($result = $db->queryF($sql)) {
            list($count) = $db->fetchRow($result);
            if (1 == $count) {
                $welcometype_installed = true;
            }
        }
        if (!$welcometype_installed) {
            $sql = 'INSERT INTO ' . $db->prefix('config') . ' (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) ' . ' VALUES ' . " (NULL, 0, 2, 'welcome_type', '_MD_AM_WELCOMETYPE', '1', '_MD_AM_WELCOMETYPE_DESC', 'select', 'int', 3)";

            if (!$db->queryF($sql)) {
                return false;
            }
            $config_id = $db->getInsertId();

            $sql = 'INSERT INTO ' . $db->prefix('configoption') . ' (confop_id, confop_name, confop_value, conf_id)' . ' VALUES' . " (NULL, '_NO', '0', {$config_id})," . " (NULL, '_MD_AM_WELCOMETYPE_EMAIL', '1', {$config_id})," . " (NULL, '_MD_AM_WELCOMETYPE_PM', '2', {$config_id})," . " (NULL, '_MD_AM_WELCOMETYPE_BOTH', '3', {$config_id})";
            if (!$result = $db->queryF($sql)) {
                return false;
            }
        }

        return $result;
    }

    public function apply_cache()
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $allowWebChanges = $db->allowWebChanges;
        $db->allowWebChanges = true;
        $result = $db->queryFromFile(dirname(__FILE__) . '/mysql.structure.sql');
        $db->allowWebChanges = $allowWebChanges;

        return $result;
    }

    public function check_path()
    {
        if (!(defined('XOOPS_PATH') && defined('XOOPS_VAR_PATH') && defined('XOOPS_TRUST_PATH'))) {
            return false;
        }
        $ctrl = new PathStuffController();
        if (!$ctrl->checkPath()) {
            return false;
        }
        if (!$ctrl->checkPermissions()) {
            return false;
        }

        return true;
    }

    public function apply_path()
    {
        return $this->update_configs('path');
    }

    public function check_db()
    {
        $lines = file(XOOPS_ROOT_PATH . '/mainfile.php');
        foreach ($lines as $line) {
            if (preg_match("/(define\(\s*)([\"'])(XOOPS_DB_CHARSET)\\2,\s*([\"'])([^\"']*?)\\4\s*\);/", $line)) {
                return true;
            }
        }

        return false;
    }

    public function apply_db()
    {
        return $this->update_configs('db');
    }

    public function update_configs($task)
    {
        if (!$vars = $this->set_configs($task)) {
            return false;
        }
        if ('db' == $task && !empty($vars['XOOPS_DB_COLLATION'])) {
            if ($pos = mb_strpos($vars['XOOPS_DB_COLLATION'], '_')) {
                $vars['XOOPS_DB_CHARSET'] = mb_substr($vars['XOOPS_DB_COLLATION'], 0, $pos);
                $this->convert_db($vars['XOOPS_DB_CHARSET'], $vars['XOOPS_DB_COLLATION']);
            }
        }

        return $this->write_mainfile($vars);
    }

    public function convert_db($charset, $collation)
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        $sql = 'ALTER DATABASE `' . XOOPS_DB_NAME . '` DEFAULT CHARACTER SET ' . $db->quote($charset) . ' COLLATE ' . $db->quote($collation);
        if (!$db->queryF($sql)) {
            return false;
        }
        if (!$result = $db->queryF("SHOW TABLES LIKE '" . XOOPS_DB_PREFIX . "\_%'")) {
            return false;
        }
        $tables = [];
        while (false !== (list($table) = $db->fetchRow($result))) {
            $tables[] = $table;
            //$db->queryF( "ALTER TABLE `{$table}` DEFAULT CHARACTER SET " . $db->quote($charset) . " COLLATE " . $db->quote($collation) );
            //$db->queryF( "ALTER TABLE `{$table}` CONVERT TO CHARACTER SET " . $db->quote($charset) . " COLLATE " . $db->quote($collation) );
        }
        $this->convert_table($tables, $charset, $collation);
    }

    // Some code not ready to use
    public function convert_table($tables, $charset, $collation)
    {
        $xoops = Xoops::getInstance();
        $db = $xoops->db();
        // Initialize vars.
        $string_querys = [];
        $binary_querys = [];
        $gen_index_querys = [];
        $drop_index_querys = [];
        $tables_querys = [];
        $optimize_querys = [];
        $final_querys = [];

        // Begin Converter Core
        if (!empty($tables)) {
            foreach ((array)$tables as $table) {
                // Analyze tables for string types columns and generate his binary and string correctness sql sentences.
                $resource = $db->queryF("DESCRIBE $table");
                while (false !== ($result = $db->fetchArray($resource))) {
                    if (preg_match('/(char)|(text)|(enum)|(set)/', $result['Type'])) {
                        // String Type SQL Sentence.
                        $string_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . " CHARACTER SET $charset COLLATE $collation " . (((!empty($result['Default'])) || ('0' === $result['Default']) || (0 === $result['Default'])) ? "DEFAULT '" . $result['Default'] . "' " : '') . ('YES' == $result['Null'] ? '' : 'NOT ') . 'NULL';

                        // Binary String Type SQL Sentence.
                        if (preg_match('/(enum)|(set)/', $result['Type'])) {
                            $binary_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . ' CHARACTER SET binary ' . (((!empty($result['Default'])) || ('0' === $result['Default']) || (0 === $result['Default'])) ? "DEFAULT '" . $result['Default'] . "' " : '') . ('YES' == $result['Null'] ? '' : 'NOT ') . 'NULL';
                        } else {
                            $result['Type'] = preg_replace('/char/', 'binary', $result['Type']);
                            $result['Type'] = preg_replace('/text/', 'blob', $result['Type']);
                            $binary_querys[] = "ALTER TABLE `$table` MODIFY `" . $result['Field'] . '` ' . $result['Type'] . ' ' . (((!empty($result['Default'])) || ('0' === $result['Default']) || (0 === $result['Default'])) ? "DEFAULT '" . $result['Default'] . "' " : '') . ('YES' == $result['Null'] ? '' : 'NOT ') . 'NULL';
                        }
                    }
                }

                // Analyze table indexs for any FULLTEXT-Type of index in the table.
                $fulltext_indexes = [];
                $resource = $db->queryF("SHOW INDEX FROM `$table`");
                while (false !== ($result = $db->fetchArray($resource))) {
                    if (preg_match('/FULLTEXT/', $result['Index_type'])) {
                        $fulltext_indexes[$result['Key_name']][$result['Column_name']] = 1;
                    }
                }

                // Generate the SQL Sentence for drop and add every FULLTEXT index we found previously.
                if (!empty($fulltext_indexes)) {
                    foreach ((array)$fulltext_indexes as $key_name => $column) {
                        $drop_index_querys[] = "ALTER TABLE `$table` DROP INDEX `$key_name`";
                        $tmp_gen_index_query = "ALTER TABLE `$table` ADD FULLTEXT `$key_name`(";
                        $fields_names = array_keys($column);
                        for ($i = 1; $i <= count($column); $i++) {
                            $tmp_gen_index_query .= $fields_names[$i - 1] . (($i == count($column)) ? '' : ', ');
                        }
                        $gen_index_querys[] = $tmp_gen_index_query . ')';
                    }
                }

                // Generate the SQL Sentence for change default table character set.
                $tables_querys[] = "ALTER TABLE `$table` DEFAULT CHARACTER SET $charset COLLATE $collation";

                // Generate the SQL Sentence for Optimize Table.
                $optimize_querys[] = "OPTIMIZE TABLE `$table`";
            }
        }
        // End Converter Core

        // Merge all SQL Sentences that we temporary store in arrays.
        $final_querys = array_merge((array)$drop_index_querys, (array)$binary_querys, (array)$tables_querys, (array)$string_querys, (array)$gen_index_querys, (array)$optimize_querys);

        foreach ($final_querys as $sql) {
            $db->queryF($sql);
        }

        // Time to return.
        return $final_querys;
    }

    public function write_mainfile($vars)
    {
        if (empty($vars)) {
            return false;
        }

        $file = dirname(__FILE__) . '/mainfile.dist.php';

        $lines = file($file);
        foreach (array_keys($lines) as $ln) {
            if (preg_match("/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([0-9]+)\s*\)/", $lines[$ln], $matches)) {
                $val = isset($vars[$matches[3]]) ? strval(constant($matches[3])) : (defined($matches[3]) ? strval(constant($matches[3])) : '0');
                $lines[$ln] = preg_replace("/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([0-9]+)\s*\)/", "define( '" . $matches[3] . "', " . $val . ' )', $lines[$ln]);
            } elseif (preg_match("/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([\"'])([^\"']*?)\\4\s*\)/", $lines[$ln], $matches)) {
                $val = isset($vars[$matches[3]]) ? strval($vars[$matches[3]]) : (defined($matches[3]) ? strval(constant($matches[3])) : '');
                $lines[$ln] = preg_replace("/(define\()([\"'])(XOOPS_[^\"']+)\\2,\s*([\"'])(.*?)\\4\s*\)/", "define( '" . $matches[3] . "', '" . $val . "' )", $lines[$ln]);
            }
        }

        $fp = fopen(XOOPS_ROOT_PATH . '/mainfile.php', 'wb');
        if (!$fp) {
            echo ERR_COULD_NOT_WRITE_MAINFILE;
            echo "<pre style='border: 1px solid black; width: 80%; overflow: auto;'><div style='color: #ff0000; font-weight: bold;'><div>" . implode('</div><div>', array_map('htmlspecialchars', $lines)) . '</div></div></pre>';

            return false;
        }
        $newline = defined(PHP_EOL) ? PHP_EOL : (mb_strpos(php_uname(), 'Windows') ? "\r\n" : "\n");
        $content = str_replace(["\r\n", "\n"], $newline, implode('', $lines));

        fwrite($fp, $content);
        fclose($fp);

        return true;
    }

    public function set_configs($task)
    {
        $ret = [];
        $configs = include dirname(__FILE__) . "/settings_{$task}.php";
        if (!$configs || !is_array($configs)) {
            return $ret;
        }
        if (empty($_POST['task']) || $_POST['task'] != $task) {
            return false;
        }

        foreach ($configs as $key => $val) {
            $ret['XOOPS_' . $key] = $val;
        }

        return $ret;
    }
}

$upg = new upgrade_230();

return $upg;
