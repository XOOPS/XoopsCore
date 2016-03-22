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
 * @package   Maintenance
 * @author    Mage Grégory (AKA Mage), Cointin Maxime (AKA Kraven30)
 * @copyright 2000-2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Maintenance
{
    /**
     * @var XoopsMySQLDatabase
     */
    public $db;

    /**
     * @var string
     */
    public $prefix;

    /**
     * Constructor
     */
    public function __construct()
    {
        $db           = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->db     = $db;
        $this->prefix = $this->db->prefix . '_';
    }

    /**
     * Display Tables
     *
     * @param bool $array true to return as array, false to return as comma delimited string
     *
     * @return array|string list of tables
     */
    public function displayTables($array = true)
    {
        $tables = array();
        $result = $this->db->queryF('SHOW TABLES');
        while ($myrow = $this->db->fetchArray($result)) {
            $value = array_values($myrow);
            $value = substr($value[0], strlen($this->prefix));
            $tables[$value] = $value;
        }
        if ($array) {
            return $tables;
        } else {
            return implode(',', $tables);
        }
    }

    /**
     * Dump table structure
     *
     * @param string $table table name
     * @param int    $drop  1 to include drop table if exists
     *
     * @return array 'ret[sql_text] = dump, ret[structure] = display structure
     */
    public function dumpTableStructure($table, $drop)
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
     * @param string $table table name
     *
     * @return array 'ret[sql_text] = dump, ret[records] = display records
     */
    public function dumpTableData($table)
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
                    $field_type[] = $this->db->getFieldType($result, $i);
                    ++$i;
                }

                $sql_text .= "INSERT INTO `" . $table . "` values\n";
                $index = 0;
                while ($row = $this->db->fetchRow($result)) {
                    ++$count;
                    $sql_text .= "(";
                    for ($i = 0; $i < $num_fields; ++$i) {
                        if (is_null($row[$i])) {
                            $sql_text .= "null";
                        } else {
                            switch ($field_type[$i]) {
                                case 'int':
                                    $sql_text .= $row[$i];
                                    break;
                                default:
                                    $sql_text .= "'" . $this->db->escape($row[$i]) . "'";
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
                    ++$index;
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
     * @param string $sql_text SQL to write
     *
     * @return array 'ret[file_name] = file name, ret[write] = write
     */
    public function dump_write($sql_text)
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
