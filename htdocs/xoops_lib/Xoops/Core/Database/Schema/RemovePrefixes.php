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

use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Sequence;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Visitor\Visitor;

/**
 * RemovePrefixes is a Schema Visitor that builds an new Schema object
 * without the XOOPS_DB_PREFIX. A table list can be optionally applied to
 * filter the Schema.
 * 
 * This depends on PrefixStripper to do a lot of the grunt work.
 * 
 * @category  Xoops\Core\Database\Schema\RemovePrefixes
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 2.6
 * @link      http://xoops.org
 * @since     2.6.0
 */
class RemovePrefixes implements Visitor
{

    protected $newSchema;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->newSchema = new PrefixStripper;
    }

    /**
     * return the generated Schema
     * 
     * @return Schema the generated schema object
     */
    public function getNewSchema()
    {
        return $this->newSchema;
    }

    /**
     * set list of tables to limit schema
     * 
     * If no list is specified, all tables will be included
     * 
     * @param array $tableList list of tables to allow
     * 
     * @return void
     */
    public function setTableFilter(array $tableList)
    {
        $this->newSchema->setTableFilter($tableList);
    }

    /**
     * Accept schema - not used in this context
     * 
     * @param Schema $schema a schema object
     * 
     * @return void
     */
    public function acceptSchema(Schema $schema)
    {

    }

    /**
     * Accept a table with all its dependencies.
     *
     * @param Table $table a table object
     * 
     * @return void
     */
    public function acceptTable(Table $table)
    {
        $this->newSchema->addTable($table);
    }

    /**
     * accept a column in the schema - not used in this context
     *
     * @param Table  $table  a table object to accept a column into
     * @param Column $column a column object to be accepted
     * 
     * @return void
     */
    public function acceptColumn(Table $table, Column $column)
    {

    }

    /**
     * Accept a foreign key in the schema - not used in this context
     * 
     * @param Table                $localTable   local table to have foreign key
     * @param ForeignKeyConstraint $fkConstraint foreign key constraint
     * 
     * @return void
     */
    public function acceptForeignKey(Table $localTable, ForeignKeyConstraint $fkConstraint)
    {

    }

    /**
     * Accept an Index - not used in this context
     * 
     * @param Table $table indexed table
     * @param Index $index index to accept
     * 
     * @return void
     */
    public function acceptIndex(Table $table, Index $index)
    {

    }

    /**
     * Accept a sequence
     * 
     * @param Sequence $sequence sequence to accept
     * 
     * @return void
     */
    public function acceptSequence(Sequence $sequence)
    {
        $this->newSchema->addSequence($sequence);
    }
}
