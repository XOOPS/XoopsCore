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

use Xmf\Module\Helper;
use Xmf\Yaml;

/**
 * Xmf\Database\Migrate
 *
 * For a given module, compare the existing tables with a defined target schema
 * and build a work queue of DDL/SQL to transform the existing tables to the
 * target definitions.
 *
 * Typically Migrate will be extended by a module specific class that will supply custom
 * logic (see preSyncActions() method.)
 *
 * @category  Xmf\Database\Migrate
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @link      http://xoops.org
 */
class Migrate
{

    /** @var false|\Xmf\Module\Helper|\Xoops\Module\Helper\HelperAbstract  */
    protected $helper;

    /** @var string[] table names used by module */
    protected $moduleTables;

    /** @var Tables */
    protected $tableHandler;

    /** @var string yaml definition file */
    protected $tableDefinitionFile;

    /** @var array target table definitions in Xmf\Database\Tables::dumpTables() format */
    protected $targetDefinitions;

    /**
     * Migrate constructor
     *
     * @param string $dirname module directory name that defines the tables to be migrated
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct($dirname)
    {
        $this->helper = Helper::getHelper($dirname);
        if (false === $this->helper) {
            throw new \InvalidArgumentException("Invalid module $dirname specified");
        }
        $module = $this->helper->getModule();
        $this->moduleTables = $module->getInfo('tables');
        if (empty($this->moduleTables)) {
            throw new \RuntimeException("No tables established in module");
        }
        $version = $module->getInfo('version');
        $this->tableDefinitionFile = $this->helper->path("sql/{$dirname}_{$version}_migrate.yml");
        $this->tableHandler = new Tables();
    }

    /**
     * Save current table definitions to a file
     *
     * This is intended for developer use when setting up the migration by using the current database state
     *
     * @internal intended for module developers only
     *
     * @return int|false count of bytes written or false on error
     */
    public function saveCurrentSchema()
    {
        $this->tableHandler = new Tables(); // start fresh

        $schema = $this->getCurrentSchema();

        foreach ($schema as $tableName => $tableData) {
            unset($schema[$tableName]['name']);
        }

        return Yaml::save($schema, $this->tableDefinitionFile);
    }

    /**
     * get the current definitions
     *
     * @return array
     */
    public function getCurrentSchema()
    {
        foreach ($this->moduleTables as $tableName) {
            $this->tableHandler->useTable($tableName);
        }

        return $this->tableHandler->dumpTables();
    }

    /**
     * Return the target database condition
     *
     * @return array|bool table structure or false on error
     *
     * @throws \RuntimeException
     */
    public function getTargetDefinitions()
    {
        if (!isset($this->targetDefinitions)) {
            $this->targetDefinitions = Yaml::read($this->tableDefinitionFile);
            if (null === $this->targetDefinitions) {
                throw new \RuntimeException("No schema definition " . $this->tableDefinitionFile);
            }
        }
        return $this->targetDefinitions;
    }

    /**
     * Execute synchronization to transform current schema to target
     *
     * @param bool $force true to force updates even if this is a 'GET' request
     *
     * @return bool true if no errors, false if errors encountered
     */
    public function synchronizeSchema($force = true)
    {
        $this->tableHandler = new Tables(); // start fresh
        $this->getSynchronizeDDL();
        return $this->tableHandler->executeQueue($force);
    }

    /**
     * Compare target and current schema, building work queue in $this->migrate to synchronized
     *
     * @return string[] array of DDL/SQL statements to transform current to target schema
     */
    public function getSynchronizeDDL()
    {
        $this->getTargetDefinitions();
        $this->preSyncActions();
        foreach ($this->moduleTables as $tableName) {
            if ($this->tableHandler->useTable($tableName)) {
                $this->synchronizeTable($tableName);
            } else {
                $this->addMissingTable($tableName);
            }
        }
        return $this->tableHandler->dumpQueue();
    }

    /**
     * Perform any upfront actions before synchronizing the schema.
     *
     * The schema comparison cannot recognize changes such as renamed columns or renamed tables. By overriding
     * this method, an implementation can provide the logic to accomplish these types of changes, and leave
     * the other details to be handled by synchronizeSchema().
     *
     * An suitable implementation should be provided by a module by extending Migrate to define any required
     * actions.
     *
     * Some typical uses include:
     *  - table and column renames
     *  - data conversions
     *  - move column data
     *
     * @return void
     */
    protected function preSyncActions()
    {
    }

    /**
     * Add table create DDL to the work queue
     *
     * @param string $tableName table to add
     *
     * @return void
     */
    protected function addMissingTable($tableName)
    {
        $this->tableHandler->addTable($tableName);
        $this->tableHandler->setTableOptions($tableName, $this->targetDefinitions[$tableName]['options']);
        foreach ($this->targetDefinitions[$tableName]['columns'] as $column) {
            $this->tableHandler->addColumn($tableName, $column['name'], $column['attributes']);
        }
        foreach ($this->targetDefinitions[$tableName]['keys'] as $key => $keyData) {
            if ($key === 'PRIMARY') {
                $this->tableHandler->addPrimaryKey($tableName, $keyData['columns']);
            } else {
                $this->tableHandler->addIndex($key, $tableName, $keyData['columns'], $keyData['unique']);
            }
        }
    }

    /**
     * Build any DDL required to synchronize an existing table to match the target schema
     *
     * @param string $tableName table to synchronize
     *
     * @return void
     */
    protected function synchronizeTable($tableName)
    {
        foreach ($this->targetDefinitions[$tableName]['columns'] as $column) {
            $attributes = $this->tableHandler->getColumnAttributes($tableName, $column['name']);
            if ($attributes === false) {
                $this->tableHandler->addColumn($tableName, $column['name'], $column['attributes']);
            } elseif ($column['attributes'] !== $attributes) {
                $this->tableHandler->alterColumn($tableName, $column['name'], $column['attributes']);
            }
        }

        $tableDef = $this->tableHandler->dumpTables();
        if (isset($tableDef[$tableName])) {
            foreach ($tableDef[$tableName]['columns'] as $columnData) {
                if (!$this->targetHasColumn($tableName, $columnData['name'])) {
                    $this->tableHandler->dropColumn($tableName, $columnData['name']);
                }
            }
        }

        $existingIndexes = $this->tableHandler->getTableIndexes($tableName);
        if (isset($this->targetDefinitions[$tableName]['keys'])) {
            foreach ($this->targetDefinitions[$tableName]['keys'] as $key => $keyData) {
                if ($key === 'PRIMARY') {
                    if (!isset($existingIndexes[$key])) {
                        $this->tableHandler->addPrimaryKey($tableName, $keyData['columns']);
                    } elseif ($existingIndexes[$key]['columns'] !== $keyData['columns']) {
                        $this->tableHandler->dropPrimaryKey($tableName);
                        $this->tableHandler->addPrimaryKey($tableName, $keyData['columns']);
                    }
                } else {
                    if (!isset($existingIndexes[$key])) {
                        $this->tableHandler->addIndex($key, $tableName, $keyData['columns'], $keyData['unique']);
                    } elseif ($existingIndexes[$key]['unique'] !== $keyData['unique']
                        || $existingIndexes[$key]['columns'] !== $keyData['columns']
                    ) {
                        $this->tableHandler->dropIndex($key, $tableName);
                        $this->tableHandler->addIndex($key, $tableName, $keyData['columns'], $keyData['unique']);
                    }
                }
            }
        }
        if (false !== $existingIndexes) {
            foreach ($existingIndexes as $key => $keyData) {
                if (!isset($this->targetDefinitions[$tableName]['keys'][$key])) {
                    $this->tableHandler->dropIndex($key, $tableName);
                }
            }
        }
    }

    /**
     * determine if a column on a table exists in the target definitions
     *
     * @param string $tableName  table containing the column
     * @param string $columnName column to check
     *
     * @return bool true if table and column combination is defined, otherwise false
     */
    protected function targetHasColumn($tableName, $columnName)
    {
        if (isset($this->targetDefinitions[$tableName])) {
            foreach ($this->targetDefinitions[$tableName]['columns'] as $col) {
                if (strcasecmp($col['name'], $columnName) === 0) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * determine if a table exists in the target definitions
     *
     * @param string $tableName table containing the column
     *
     * @return bool true if table is defined, otherwise false
     */
    protected function targetHasTable($tableName)
    {
        if (isset($this->targetDefinitions[$tableName])) {
            return true;
        }
        return false;
    }

    /**
     * Return message from last error encountered
     *
     * @return string last error message
     */
    public function getLastError()
    {
        return $this->tableHandler->getLastError();
    }

    /**
     * Return code from last error encountered
     *
     * @return int last error number
     */
    public function getLastErrNo()
    {
        return $this->tableHandler->getLastErrNo();
    }
}
