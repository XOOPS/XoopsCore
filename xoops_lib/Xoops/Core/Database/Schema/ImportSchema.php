<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

namespace Xoops\Core\Database\Schema;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Sequence;
use Doctrine\DBAL\Schema\Table;

/**
 * ImportSchema processes an array of schema information and creates
 * a XOOPS_DB_PREFIX prefixed Schema object.
 *
 * @category  Xoops\Core\Database\Schema\ImportSchema
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013-2019 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 */
class ImportSchema
{
    private $xPrefix;
    private $schemaArray = [];

    /**
     * Constructor
     *
     * @param string $prefix Prefix to remove from table names
     */
    public function __construct(string $prefix)
    {
        $this->xPrefix = $prefix;
    }

    /**
     * Import an array into a schema
     *
     * @param array $schemaArray array version of a schema object
     *
     * @throws \Doctrine\DBAL\DBALException
     * @return Schema object built from input array
     */
    public function importSchemaArray(array $schemaArray)
    {
        $tables = [];
        $sequences = [];
        $this->schemaArray = $schemaArray;
        foreach ($schemaArray as $type => $entity) {
            switch ($type) {
                case 'tables':
                    $tables = $this->importTables($entity);
                    break;
                case 'sequence':
                    $sequences = $this->importSequences($entity);
                    break;
            }
        }

        return new Schema($tables, $sequences);
    }

    /**
     * Build array of Table objects to add to the schema
     *
     * @param array $tableArray array of table definitions
     *
     * @throws \Doctrine\DBAL\DBALException
     * @return array of Table objects
     */
    public function importTables(array $tableArray)
    {
        $tables = [];
        foreach ($tableArray as $name => $tabledef) {
            $tableName = $this->xPrefix . $name;
            $columns = [];
            $indexes = [];
            $fkConstraints = [];
            $options = [];
            $idGeneratorType = 0;
            foreach ($tabledef['columns'] as $colName => $colOptions) {
                $colType = \Doctrine\DBAL\Types\Type::getType($colOptions['type']);
                unset($colOptions['type']);
                $columns[] = new Column($colName, $colType, $colOptions);
            }

            if (isset($tabledef['indexes'])) {
                foreach ($tabledef['indexes'] as $indexName => $indexDef) {
                    $indexes[] = new Index(
                        $indexName,
                        $indexDef['columns'],
                        $indexDef['unique'],
                        $indexDef['primary']
                    );
                }
            }

            if (isset($tabledef['constraint'])) {
                foreach ($tabledef['constraint'] as $constraintDef) {
                    $fkConstraints[] = new ForeignKeyConstraint(
                        $constraintDef['localcolumns'],
                        $constraintDef['foreigntable'],
                        $constraintDef['foreigncolumns'],
                        $constraintDef['name'] = null,
                        $constraintDef['options']
                    );
                }
            }

            if (isset($tabledef['options'])) {
                $options = $tabledef['options'];
            }

            $tables[] = new Table(
                $tableName,
                $columns,
                $indexes,
                $fkConstraints,
                $idGeneratorType,
                $options
            );
        }

        return $tables;
    }

    /**
     * Build array of Sequence objects to add to the schema
     *
     * @param array $sequenceArray array of table definitions
     *
     * @return array of Sequence objects
     */
    public function importSequences(array $sequenceArray)
    {
        $sequences = [];

        foreach ($sequenceArray as $name => $sequenceDef) {
            $sequences[] = new Sequence(
                $sequenceDef['name'],
                $sequenceDef['allocationsize'],
                $sequenceDef['initialvalue']
            );
        }

        return $sequences;
    }
}
