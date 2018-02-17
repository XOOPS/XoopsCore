<?php
/**
 * database manager for XOOPS installer
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package     upgrader
 * @author      Haruki Setoyama  <haruki@planewave.org>
 * @version     $Id$
 * @access      public
 **/

include_once XOOPS_ROOT_PATH . '/class/database/databasefactory.php';
include_once XOOPS_ROOT_PATH . '/class/database/' . XOOPS_DB_TYPE . 'database.php';
include_once XOOPS_ROOT_PATH . '/class/database/sqlutility.php';

/**
 * Database manager
 *
 * @copyright   copyright (c) 2000 XOOPS.org
 * @package     upgrader
 */
class db_manager
{
    public $s_tables = array();
    public $f_tables = array();
    public $db;

    public function __construct()
    {
        $this->db = XoopsDatabaseFactory::getDatabase();
        $this->db->setPrefix(XOOPS_DB_PREFIX);
    }

    public function isConnectable()
    {
        return ($this->db->connect(false) != false) ? true : false;
    }

    public function dbExists()
    {
        return ($this->db->connect() != false) ? true : false;
    }

    public function createDB()
    {
        $this->db->connect(false);

        $result = $this->db->query("CREATE DATABASE " . XOOPS_DB_NAME);

        return ($result != false) ? true : false;
    }

    public function queryFromFile($sql_file_path)
    {
        $tables = array();

        if (!file_exists($sql_file_path)) {
            return false;
        }
        $sql_query = trim(fread(fopen($sql_file_path, 'r'), filesize($sql_file_path)));
        SqlUtility::splitMySqlFile($pieces, $sql_query);
        $this->db->connect();
        foreach ($pieces as $piece) {
            $piece = trim($piece);
            // [0] contains the prefixed query
            // [4] contains unprefixed table name
            $prefixed_query = SqlUtility::prefixQuery($piece, $this->db->prefix());
            if ($prefixed_query != false) {
                $table = $this->db->prefix($prefixed_query[4]);
                if ($prefixed_query[1] == 'CREATE TABLE') {
                    if ($this->db->query($prefixed_query[0]) != false) {
                        if (!isset($this->s_tables['create'][$table])) {
                            $this->s_tables['create'][$table] = 1;
                        }
                    } else {
                        if (!isset($this->f_tables['create'][$table])) {
                            $this->f_tables['create'][$table] = 1;
                        }
                    }
                } elseif ($prefixed_query[1] == 'INSERT INTO') {
                    if ($this->db->query($prefixed_query[0]) != false) {
                        if (!isset($this->s_tables['insert'][$table])) {
                            $this->s_tables['insert'][$table] = 1;
                        } else {
                            $this->s_tables['insert'][$table]++;
                        }
                    } else {
                        if (!isset($this->f_tables['insert'][$table])) {
                            $this->f_tables['insert'][$table] = 1;
                        } else {
                            $this->f_tables['insert'][$table]++;
                        }
                    }
                } elseif ($prefixed_query[1] == 'ALTER TABLE') {
                    if ($this->db->query($prefixed_query[0]) != false) {
                        if (!isset($this->s_tables['alter'][$table])) {
                            $this->s_tables['alter'][$table] = 1;
                        }
                    } else {
                        if (!isset($this->s_tables['alter'][$table])) {
                            $this->f_tables['alter'][$table] = 1;
                        }
                    }
                } elseif ($prefixed_query[1] == 'DROP TABLE') {
                    if ($this->db->query('DROP TABLE '.$table) != false) {
                        if (!isset($this->s_tables['drop'][$table])) {
                            $this->s_tables['drop'][$table] = 1;
                        }
                    } else {
                        if (!isset($this->s_tables['drop'][$table])) {
                            $this->f_tables['drop'][$table] = 1;
                        }
                    }
                }
            }
        }
        return true;
    }

    public $successStrings = array(
        'create'    => 'create',
        'insert'    => 'insert',
        'alter'     => 'alter',
        'drop'      => 'drop',
        );
    public $failureStrings = array(
        'create'    => 'fail',
        'insert'    => 'fail',
        'alter'     => 'error',
        'drop'      => 'error',
        );


    public function report()
    {
        $commands = array( 'create', 'insert', 'alter', 'drop' );
        $content = '<ul class="log">';
        foreach ($commands as $cmd) {
            if (!@empty($this->s_tables[$cmd])) {
                foreach ($this->s_tables[$cmd] as $key => $val) {
                    $content .= '<li class="success">';
                    $content .= ($cmd != 'insert') ? sprintf($this->successStrings[$cmd], $key) : sprintf($this->successStrings[$cmd], $val, $key);
                    $content .= "</li>\n";
                }
            }
        }
        foreach ($commands as $cmd) {
            if (!@empty($this->f_tables[$cmd])) {
                foreach ($this->f_tables[$cmd] as $key => $val) {
                    $content .= '<li class="failure">';
                    $content .= ($cmd != 'insert') ? sprintf($this->failureStrings[$cmd], $key) : sprintf($this->failureStrings[$cmd], $val, $key);
                    $content .= "</li>\n";
                }
            }
        }
        $content .= '</ul>';
        return $content;
    }

    public function query($sql)
    {
        $this->db->connect();
        return $this->db->query($sql);
    }

    public function prefix($table)
    {
        $this->db->connect();
        return $this->db->prefix($table);
    }

    public function fetchArray($ret)
    {
        $this->db->connect();
        return $this->db->fetchArray($ret);
    }

    public function insert($table, $query)
    {
        $this->db->connect();
        $table = $this->db->prefix($table);
        $query = 'INSERT INTO ' . $table . ' '. $query;
        if (!$this->db->queryF($query)) {
            if (!isset($this->f_tables['insert'][$table])) {
                $this->f_tables['insert'][$table] = 1;
            } else {
                $this->f_tables['insert'][$table]++;
            }
            return false;
        } else {
            if (!isset($this->s_tables['insert'][$table])) {
                $this->s_tables['insert'][$table] = 1;
            } else {
                $this->s_tables['insert'][$table]++;
            }
            return $this->db->getInsertId();
        }
    }

    public function isError()
    {
        return (isset($this->f_tables)) ? true : false;
    }

    public function deleteTables($tables)
    {
        $deleted = array();
        $this->db->connect();
        foreach ($tables as $key => $val) {
            if (!$this->db->query("DROP TABLE " . $this->db->prefix($key))) {
                $deleted[] = $va;
            }
        }
        return $deleted;
    }

    public function tableExists($table)
    {
        $table = trim($table);
        $ret = false;
        if ($table != '') {
            $this->db->connect();
            $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix($table);
            $ret = (false != $this->db->query($sql)) ? true : false;
        }
        return $ret;
    }
}
