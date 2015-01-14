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
 * maintenance extensions
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         maintenance
 * @since           2.6.0
 * @author          Mage Grégory (AKA Mage), Cointin Maxime (AKA Kraven30)
 * @version         $Id$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * System Maintenance
 *
 * @copyright   copyright (c) 2000 XOOPS.org
 * @package     system
 */
class Maintenance
{
    var $db;
    var $prefix;

    /**
     * Constructor
     */
    function Maintenance()
    {
        global $xoopsDB;
        $db = $xoopsDB;
        $this->db = $db;
        $this->prefix = $this->db->prefix . '_';
    }

    /**
     * Display Tables
     *
     * @param array
     * @return
     */
    function displayTables($array = true)
    {
        $tables = array();
        $result = $this->db->queryF('SHOW TABLES');
        while ($myrow = $this->db->fetchArray($result)) {
            $value = array_values($myrow);
            $value = substr($value[0], 5);
            $tables[$value] = $value;
        }
        if ($array = true) {
            return $tables;
        } else {
            return join(',', $tables);
        }
    }

    /**
     * Dump table structure
     *
     * @param string table
     * @param int drop
     * @return array 'ret[sql_text] = dump, ret[structure] = display structure
     */
    function dump_table_structure($table, $drop)
    {
        $sql_text = '';
        $verif = false;
        $result = $this->db->queryF('SHOW create table `' . $table . '`;');
        if ($result) {
            if ($row = $this->db->fetchArray($result)) {
                $sql_text .= "# Table structure for table `" . $table . "` \n\n";
                if ($drop == 1) {
                    $sql_text .= "DROP TABLE IF EXISTS `" . $table . "`;\n\n";
                }
                $verif = true;
                $sql_text .= $row['Create Table'] . ";\n\n";
            }
        }
        $this->db->freeRecordSet($result);
        $ret['sql_text'] = $sql_text;
        $ret['structure'] = $verif;
        return $ret;
    }

    /**
     * Dump table data
     *
     * @param string table
     * @return array 'ret[sql_text] = dump, ret[records] = display records
     */
    function dump_table_datas($table)
    {
        $sql_text = '';
        $count = 0;
        $result = $this->db->queryF('SELECT * FROM ' . $table . ';');
        if ($result) {
            $num_rows = $this->db->getRowsNum($result);
            $num_fields = $this->db->getFieldsNum($result);

            if ($num_rows > 0) {
                $field_type = array();
                $i = 0;
                while ($i < $num_fields) {
                    $meta = mysql_fetch_field($result, $i);
                    array_push($field_type, $meta->type);
                    $i++;
                }

                $sql_text .= "INSERT INTO `" . $table . "` values\n";
                $index = 0;
                while ($row = $this->db->fetchRow($result)) {
                    $count++;
                    $sql_text .= "(";
                    for ($i = 0; $i < $num_fields; $i++) {
                        if (is_null($row[$i])) {
                            $sql_text .= "null";
                        } else {
                            switch ($field_type[$i]) {
                                case 'int':
                                    $sql_text .= $row[$i];
                                    break;
                                default:
                                    $sql_text .= "'" . mysql_real_escape_string($row[$i]) . "'";
                            }
                        }
                        if ($i < $num_fields - 1) {
                            $sql_text .= ",";
                        }
                    }
                    $sql_text .= ")";

                    if ($index < $num_rows - 1) {
                        $sql_text .= ",";
                    } else {
                        $sql_text .= ";";
                    }
                    $sql_text .= "\n";
                    $index++;
                }
            }
        }
        $this->db->freeRecordSet($result);
        $ret['sql_text'] = $sql_text . "\n\n";
        $ret['records'] = $count;
        return $ret;
    }

    /**
     * Dump Write
     *
     * @param string
     * @return array 'ret[file_name] = file name, ret[write] = write
     */
    function dump_write($sql_text)
    {
        $file_name = "dump_" . date("Y.m.d") . "_" . date("H.i.s") . ".sql";
        $path_file = "../dump/" . $file_name;
        if (file_put_contents($path_file, $sql_text)) {
            $write = true;
        } else {
            $write = false;
        }
        $ret['file_name'] = $file_name;
        $ret['write'] = $write;
        return $ret;
    }
}