<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace Xmf\Database;

use Xmf\Language;

/**
 * Xmf\Database\Tables
 *
 * inspired by Yii CDbMigration
 *
 * Build a work queue of database changes needed to implement new and
 * changed tables. Define table(s) you are dealing with and any desired
 * change(s). If the changes are already in place (i.e. the new column
 * already exists) no work is added. Then queueExecute() to process the
 * whole set.
  *
 * @category  Xmf\Database\Tables
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2011-2013 The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 * @since     1.0
 */
class Tables
{
    /**
     * for add/alter column position
     */
    const POSITION_FIRST = 1;

    /**
     * @var XoopsDatabase
     */
    private $db;

    /**
     * @var Tables
     */
    private $tables;

    /**
     * @var Work queue
     */
    private $queue;

    /**
     * @var string last error message
     */
    protected $lastError;

    /**
     * @var int last error number
     */
    protected $lastErrNo;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        global $xoopsDB; // lock this to legacy support
        //\Xmf\Debug::dump($xoopsDB,true);
        Language::load('database', 'xmf');

        $this->db =& $xoopsDB;
        $this->queueReset();
    }

    /**
     * Return a table name, prefixed with site table prefix
     *
     * @param string $table table name to contain prefix
     *
     * @return string table name with prefix
     */
    public function name($table)
    {
        return $this->db->prefix($table);
    }

    /**
     * Add new column for table to the work queue
     *
     * @param string $table      table to contain the column
     * @param string $column     name of column to add
     * @param array  $attributes column_definition
     * @param mixed  $position   FIRST, string of column name to add new
     *                           column after, or null for natural append
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function addColumn($table, $column, $attributes, $position = null)
    {
        $columnDef=array(
            'name'=>$column,
            'position'=>$position,
            'attributes'=>$attributes
        );

        // Find table def.
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            // Is this on a table we are adding?
            if (isset($tableDef['create']) && $tableDef['create']) {
                switch ($position) {
                    case Tables::POSITION_FIRST:
                        array_unshift($tableDef['columns'], $columnDef);
                        break;
                    case '':
                    case null:
                    case false:
                        array_push($tableDef['columns'], $columnDef);
                        break;
                    default:
                        // should be a column name to add after
                        // loop thru and find that column
                        $i=0;
                        foreach ($tableDef['columns'] as $col) {
                            ++$i;
                            if (strcasecmp($col['name'], $position)==0) {
                                array_splice($tableDef['columns'], $i, 0, array($columnDef));
                                break;
                            }
                        }
                }

                return true;
            } else {
                foreach ($tableDef['columns'] as $col) {
                    if (strcasecmp($col['name'], $column)==0) {
                        return true;
                    }
                }
                switch ($position) {
                    case Tables::POSITION_FIRST:
                        $pos='FIRST';
                        break;
                    case '':
                    case null:
                    case false:
                        $pos='';
                        break;
                    default:
                        $pos="AFTER `{$position}`";
                }
                $this->queue[]="ALTER TABLE `{$tableDef['name']}`"
                    . " ADD COLUMN {$column} {$columnDef['attributes']} {$pos} ";

            }
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true; // exists or is added to queue
    }

    /**
     * Add new primary key definition for table to work queue
     *
     * @param string $table  table
     * @param string $column column or comma separated list of columns
     *                       to use a primary key
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function addPrimaryKey($table, $column)
    {
        if (isset($this->tables[$table])) {
            $this->queue[]
                = "ALTER TABLE `{$tableDef['name']}` ADD PRIMARY KEY({$column})";
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }

    /**
     * Load table schema from database, or starts new empty schema if
     * table doesn't exist
     *
     * @param string $table table
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function addTable($table)
    {
        if (isset($this->tables[$table])) {
            return true;
        }
        $tableDef=$this->getTable($table);
        if (is_array($tableDef)) {
            $this->tables[$table] = $tableDef;

            return true;
        } else {
            if ($tableDef===true) {
                $tableDef=array();
                $tableDef = array(
                      'name' => $this->db->prefix($table)
                    , 'options' => 'ENGINE=MyISAM');
                $tableDef['create'] = true;
                $this->tables[$table] = $tableDef;

                $this->queue[]=array('createtable'=>$table);

                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * AddTable only if it exists
     *
     * @param string $table table
     *
     * @return bool true if table exists, false otherwise
     */
    public function useTable($table)
    {
        if (isset($this->tables[$table])) {
            return true;
        }
        $tableDef=$this->getTable($table);
        if (is_array($tableDef)) {
            $this->tables[$table] = $tableDef;
            return true;
        }
        return false;
    }


    /**
     * Add alter column operation to the work queue
     *
     * @param string $table      table containing the column
     * @param string $column     column to alter
     * @param array  $attributes new column_definition
     * @param string $newName    new name for column, blank to keep same
     * @param mixed  $position   FIRST, string of column name to add new
     *                           column after, or null for no change
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function alterColumn($table, $column, $attributes, $newName = '', $position = null)
    {
        if (empty($newName)) {
            $newName=$column;
        }
        // Find table def.
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            // Is this on a table we are adding?
            if (isset($tableDef['create']) && $tableDef['create']
                && empty($position)
            ) {
                // loop thru and find the column
                foreach ($tableDef['columns'] as &$col) {
                    if (strcasecmp($col['name'], $column)==0) {
                        $col['name']=$newName;
                        $col['attributes']=$attributes;
                        break;
                    }
                }

                return true;
            } else {
                switch ($position) {
                    case Tables::POSITION_FIRST:
                        $pos='FIRST';
                        break;
                    case '':
                    case null:
                    case false:
                        $pos='';
                        break;
                    default:
                        $pos="AFTER `{$position}`";
                }
                $this->queue[]="ALTER TABLE `{$tableDef['name']}` " .
                    "CHANGE COLUMN `{$column}` `{$newName}` {$attributes} {$pos} ";
            }
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }

    /**
     * Loads table schema from database, and adds newTable with that
     * schema to the queue
     *
     * @param string $table    existing table
     * @param string $newTable new table
     * @param bool   $withData true to copy data, false for schema only
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function copyTable($table, $newTable, $withData = false)
    {
        if (isset($this->tables[$newTable])) {
            return true;
        }
        $tableDef=$this->getTable($table);
        $copy=$this->name($newTable);
        $original=$this->name($table);

        if (is_array($tableDef)) {
            $tableDef['name']=$copy;
            if ($withData) {
                $this->queue[] = "CREATE TABLE `{$copy}` LIKE `{$original}` ;";
                $this->queue[]
                    = "INSERT INTO `{$copy}` SELECT * FROM `{$original}` ;";
            } else {
                $tableDef['create'] = true;
                $this->queue[]=array('createtable'=>$newTable);
            }
            $this->tables[$newTable]=$tableDef;

            return true;
        } else {
            return false;
        }

    }

    /**
     * Add new index definition for index to work queue
     *
     * @param string $name   name of index to add
     * @param string $table  table indexed
     * @param string $column column or comma separated list of columns
     *                       to use as the key
     * @param bool   $unique true if index is to be unique
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function createIndex($name, $table, $column, $unique = false)
    {
        if (isset($this->tables[$table])) {
            //ALTER TABLE `table` ADD INDEX `product_id` (`product_id`)
            $add = ($unique?'ADD UNIQUE INDEX':'ADD INDEX');
            $this->queue[]
                = "ALTER TABLE `{$tableDef['name']}` {$add} {$name} ({$column})";
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }

    /**
     * Add drop column operation to the work queue
     *
     * @param string $table  table containing the column
     * @param string $column column to drop
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function dropColumn($table, $column)
    {
        // Find table def.
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $this->queue[]
                = "ALTER TABLE `{$tableDef['name']}` DROP COLUMN `{$column}`";
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }

    /**
     * Add drop index operation to the work queue
     *
     * @param string $name  name of index to drop
     * @param string $table table indexed
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function dropIndex($name, $table)
    {
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $this->queue[]="ALTER TABLE `{$tableDef['name']}` DROP INDEX `{$name}`";
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }

    /**
     * Add drop for all (non-PRIMARY) keys for a table to the work
     * queue. This can be used to clean up indexes with automatic names.
     *
     * @param string $table table indexed
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function dropIndexes($table)
    {
        // Find table def.
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            // Is this on a table we are adding?
            if (isset($tableDef['create']) && $tableDef['create']) {
                // strip everything but the PRIMARY from definition
                foreach ($tableDef['keys'] as $keyname => $key) {
                    if ($keyname!='PRIMARY') {
                        unset($tableDef['keys'][$keyname]);
                    }
                }
            } else {
                // build drops to strip everything but the PRIMARY
                foreach ($tableDef['keys'] as $keyname => $key) {
                    if ($keyname!='PRIMARY') {
                        $this->queue[] = "ALTER TABLE `{$tableDef['name']}`"
                            . " DROP INDEX {$keyname}";
                    }
                }
            }
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }

    /**
     * Add drop of PRIMARY key for a table to the work queue
     *
     * @param string $table table
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function dropPrimaryKey($table)
    {
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $this->queue[]="ALTER TABLE `{$tableDef['name']}` DROP PRIMARY KEY ";
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }

    /**
     * Add drop of table to the work queue
     *
     * @param string $table table
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function dropTable($table)
    {
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $this->queue[]="DROP TABLE `{$tableDef['name']}` ";
            unset($this->tables[$table]);
        }
        // no table is not an error since we are dropping it anyway
        return true;
    }


    /**
     * Add rename table operation to the work queue
     *
     * @param string $table   table
     * @param string $newName new table name
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function renameTable($table, $newName)
    {
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $newTable = $this->name($newName);
            $this->queue[]
                = "ALTER TABLE `{$tableDef['name']}` RENAME TO `{$newTable}`";
            $tableDef['name'] = $newTable;
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }

    /**
     * Add alter table table_options (ENGINE, DEFAULT CHARSET, etc.)
     * to work queue
     *
     * @param string $table   table
     * @param array  $options table_options
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function setTableOptions($table, $options)
    {
        // ENGINE=MEMORY DEFAULT CHARSET=utf8;
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $newTable = $this->name($newName);
            $this->queue[]="ALTER TABLE `{$tableDef['name']}` {$options} ";
            $tableDef['options'] = $options;
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }


    /**
     * Clear the work queue
     *
     * @return void
     */
    public function queueReset()
    {
        $this->tables = array();
        $this->queue  = array();
    }

    /**
     * Execute the work queue
     *
     * @param bool $force true to force updates even if this is a 'GET' request
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function queueExecute($force = false)
    {
        $this->expandQueue();
        foreach ($this->queue as &$ddl) {
            if (is_array($ddl)) {
                if (isset($ddl['createtable'])) {
                    $ddl=$this->renderTableCreate($ddl['createtable']);
                }
            }
            $result = $this->execSql($ddl, $force);
            if (!$result) {
                $this->lastError = $this->db->error();
                $this->lastErrNo = $this->db->errno();

                return false;
            }
        }

        return true;
    }


    /**
     * Create DELETE statement and add to queue
     *
     * @param string $table    table
     * @param mixed  $criteria string where clause or object criteria
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function delete($table, $criteria)
    {
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $where = '';
            if (is_scalar($criteria)) {
                $where = 'WHERE '.$criteria;
            } elseif (is_object($criteria)) {
                $where = $criteria->renderWhere();
            }
            $this->queue[]="DELETE FROM `{$tableDef['name']}` {$where}";
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }

    /** Create an INSERT SQL statement and add to queue.
     *
     * @param string $table   table
     * @param array  $columns array of 'column'=>'value' entries
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function insert($table, $columns)
    {
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $colsql = '';
            $valsql = '';
            foreach ($tableDef['columns'] as $col) {
                $comma=empty($colsql)?'':', ';
                if (isset($columns[$col['name']])) {
                    $colsql .= $comma.$col['name'];
                    $valsql .= $comma.$this->db->quote($columns[$col['name']]);
                }
            }
            $sql = "INSERT INTO `{$tableDef['name']}` ({$colsql}) VALUES({$valsql})";
            $this->queue[]=$sql;

            return true;
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return null;
        }
    }

    /**
     * Creates and executes an UPDATE SQL statement.
     *
     * @param string $table    table
     * @param array  $columns  array of 'column'=>'value' entries
     * @param mixed  $criteria string where clause or object criteria
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function update($table, $columns, $criteria)
    {
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $where = '';
            if (is_scalar($criteria)) {
                $where = 'WHERE '.$criteria;
            } elseif (is_object($criteria)) {
                $where = $criteria->renderWhere();
            }
            $colsql = '';
            foreach ($tableDef['columns'] as $col) {
                $comma=empty($colsql)?'':', ';
                if (isset($columns[$col['name']])) {
                    $colsql .= $comma . $col['name'] . ' = '
                        . $this->db->quote($columns[$col['name']]);
                }
            }
            $sql = "UPDATE `{$tableDef['name']}` SET {$colsql} {$where}";
            $this->queue[]=$sql;

            return true;
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return null;
        }
    }

    /**
     * Add statement to Empty a table to the queue
     *
     * @param string $table table
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function truncate($table)
    {
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $this->queue[]="TRUNCATE TABLE `{$tableDef['name']}`";
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return false;
        }

        return true;
    }



    /**
     * return SQL to create the table
     *
     * @param string $table    table
     * @param bool   $prefixed true to return with table name prefixed
     *
     * @return mixed string SQL to create table, or null if errors encountered
     */
    public function renderTableCreate($table, $prefixed = false)
    {
        if (isset($this->tables[$table])) {
            $tableDef = &$this->tables[$table];
            $tablename=($prefixed?$tableDef['name']:$table);
            $sql = "CREATE TABLE `{$tablename}` (\n";
            foreach ($tableDef['columns'] as $col) {
                $sql .= "    {$col['name']}  {$col['attributes']},\n";
            }
            $keysql='';
            foreach ($tableDef['keys'] as $keyname => $key) {
                $comma = empty($keysql)?'  ':', ';
                if ($keyname=='PRIMARY') {
                    $keysql .= "  {$comma}PRIMARY KEY ({$key['columns']})\n";
                } else {
                    $unique=$key['unique']?'UNIQUE ':'';
                    $keysql .= "  {$comma}{$unique}KEY {$keyname} "
                        . " ({$key['columns']})\n";
                }
            }
            $sql .= $keysql;
            $sql .= ") {$tableDef['options']};\n";

            return $sql;
        } else { // no table established
            $this->lastError = _DB_XMF_TABLE_IS_NOT_DEFINED;
            $this->lastErrNo = -1;

            return null;
        }

    }

    /**
     * execute an SQL statement
     *
     * @param string $sql   SQL statement to execute
     * @param bool   $force true to use queryF
     *
     * @return mixed result resouce if no error,
     *               true if no error but no result
     *               false if error encountered.
     *               Any error message is in $this->lastError;
     */
    private function & execSql($sql, $force = false)
    {
        if ($force) {
            $result = $this->db->queryF($sql);
        } else {
            $result = $this->db->query($sql);
        }

        if (!$result) {
            $this->lastError = $this->db->error();
            $this->lastErrNo = $this->db->errno();
        }

        return $result;

    }

    /**
     * fetch the next row of a result set
     *
     * @param resource &$result as returned by query
     *
     * @return bool true if no errors and table is loaded, false if
     *               error presented. Error message in $this->lastError;
     */
    private function fetch(&$result)
    {
        return $this->db->fetchArray($result);
    }

    /**
     * get table definition from INFORMATION_SCHEMA
     *
     * @param string $table table
     *
     * @return bool true if no errors and table is loaded, false if
     *               error presented. Error message in $this->lastError;
     */
    private function getTable($table)
    {

        $tableDef = array();

        $sql  = 'SELECT TABLE_NAME, ENGINE, CHARACTER_SET_NAME ';
        $sql .= ' FROM `INFORMATION_SCHEMA`.`TABLES` t, ';
        $sql .= ' `INFORMATION_SCHEMA`.`CHARACTER_SETS` c ';
        $sql .= ' WHERE t.TABLE_SCHEMA = \'' . XOOPS_DB_NAME . '\' ';
        $sql .= ' AND t.TABLE_NAME = \'' . $this->name($table) . '\' ';
        $sql .= ' AND t.TABLE_COLLATION  = c.DEFAULT_COLLATE_NAME ';

        $result = $this->execSql($sql);
        if (!$result) {
            return false;
        }
        $tableSchema = $this->fetch($result);
        if (empty($tableSchema)) {
            return true;
        }
        $tableDef['name'] =  $tableSchema['TABLE_NAME'];
        $tableDef['options'] = 'ENGINE=' . $tableSchema['ENGINE'] . ' '
            . 'DEFAULT CHARSET=' . $tableSchema['CHARACTER_SET_NAME'];

        $sql  = 'SELECT * ';
        $sql .= ' FROM `INFORMATION_SCHEMA`.`COLUMNS` ';
        $sql .= ' WHERE TABLE_SCHEMA = \'' . XOOPS_DB_NAME . '\' ';
        $sql .= ' AND TABLE_NAME = \'' . $this->name($table) . '\' ';
        $sql .= ' ORDER BY `ORDINAL_POSITION` ';

        $result = $this->execSql($sql);

        while ($column=$this->fetch($result)) {
            $attributes = ' ' . $column['COLUMN_TYPE'] . ' '
                . (($column['IS_NULLABLE'] == 'NO') ? ' NOT NULL ' : '' )
                . (($column['COLUMN_DEFAULT'] === null) ? '' :
                        " DEFAULT '". $column['COLUMN_DEFAULT'] . "' ")
                . $column['EXTRA'];

            $columnDef=array(
                'name'=>$column['COLUMN_NAME'],
                'position'=>$column['ORDINAL_POSITION'],
                'attributes'=>$attributes
            );

            $tableDef['columns'][] = $columnDef;
        };

        $sql  = 'SELECT `INDEX_NAME`, `SEQ_IN_INDEX`, `NON_UNIQUE`, ';
        $sql .= ' `COLUMN_NAME`, `SUB_PART` ';
        $sql .= ' FROM `INFORMATION_SCHEMA`.`STATISTICS` ';
        $sql .= ' WHERE TABLE_SCHEMA = \'' . XOOPS_DB_NAME . '\' ';
        $sql .= ' AND TABLE_NAME = \'' . $this->name($table) . '\' ';
        $sql .= ' ORDER BY `INDEX_NAME`, `SEQ_IN_INDEX` ';

        $result = $this->execSql($sql);

        $lastkey = '';
        $keycols='';
        $keyunique = false;
        while ($key=$this->fetch($result)) {
            if ($lastkey != $key['INDEX_NAME']) {
                if (!empty($lastkey)) {
                    $tableDef['keys'][$lastkey]['columns'] = $keycols;
                    $tableDef['keys'][$lastkey]['unique'] = $keyunique;
                }
                $lastkey = $key['INDEX_NAME'];
                $keycols = $key['COLUMN_NAME'];
                if (!empty($key['SUB_PART'])) {
                    $keycols .= ' (' . $key['SUB_PART'] . ')';
                }
                $keyunique = !$key['NON_UNIQUE'];
            } else {
                $keycols .= ', ' . $key['COLUMN_NAME'];
                if (!empty($key['SUB_PART'])) {
                    $keycols .= ' ('.$key['SUB_PART'].')';
                }
            }
            //$tableDef['keys'][$key['INDEX_NAME']][$key['SEQ_IN_INDEX']] = $key;
        };
        if (!empty($lastkey)) {
            $tableDef['keys'][$lastkey]['columns'] = $keycols;
            $tableDef['keys'][$lastkey]['unique'] = $keyunique;
        }

        return $tableDef;

    }

    /**
     * During processing, tables to be created are put in the queue as
     * an array('createtable'=>tablename) since the definition is not
     * complete. This method will expand those references to the full
     * ddl to create the table.
     *
     * @return void
     */
    private function expandQueue()
    {
        foreach ($this->queue as &$ddl) {
            if (is_array($ddl)) {
                if (isset($ddl['createtable'])) {
                    $ddl=$this->renderTableCreate($ddl['createtable'], true);
                }
            }
        }
    }

    /**
     * Return message from last error encountered
     *
     * @return string last error message
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Return code from last error encountered
     *
     * @return int last error number
     */
    public function getLastErrNo()
    {
        return $this->lastErrNo;
    }

    /**
     * dumpTables - development function to dump raw tables array
     * 
     * @return array tables
     */
    public function dumpTables()
    {
        return $this->tables;
    }

    /**
     * dumpQueue - development function to dump the work queue
     * 
     * @return array work queue
     */
    public function dumpQueue()
    {
        $this->expandQueue();

        return $this->queue;
    }
}
