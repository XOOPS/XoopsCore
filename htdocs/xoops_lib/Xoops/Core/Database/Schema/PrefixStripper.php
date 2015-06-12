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
use Doctrine\DBAL\Schema\Sequence;

/**
 * PrefixStripper extends Schema so we can easily add tables and
 * sequences selectively while visiting another schema.
 *
 * New schema will be stripped of database and prefix and optionally
 * filered by a table list
 *
 * @category  Xoops\Core\Database\Schema\PrefixStripper
 * @package   Xoops\Core
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 2.6
 * @link      http://xoops.org
 * @since     2.6.0
 */
class PrefixStripper extends Schema
{

    private $xPrefix = '';
    private $xDbName = '';
    private $tableList = array();

    /**
     * constructor
     */
    public function __construct(array $tables=array(), array $sequences=array(), SchemaConfig $schemaConfig=null)
    {
        $this->xPrefix = strtolower(\XoopsBaseConfig::get('db-prefix') . '_');
        $this->xDbName = strtolower(\XoopsBaseConfig::get('db-name'));
        parent::__construct($tables, $sequences, $schemaConfig);
    }

    /**
     * set list of tables to limit schema
     *
     * If no list is specified, all tables will be included
     *
     * @param array $tableList list of tables to include
     *
     * @return void
     */
    public function setTableFilter(array $tableList)
    {
        $this->tableList = $tableList;
    }

    /**
     * Add a table object to the schema
     *
     * @param Table $table table object to add
     *
     * @return void
     */
    public function addTable(Table $table)
    {
        //echo '<h2>addTable()</h2>';
        try {
            $name = $table->getName();
            $len = strlen($this->xPrefix);
            if (substr_compare($name, $this->xPrefix, 0, $len)===0) {
                $name = substr($name, $len);
                if (empty($this->tableList) || in_array($name, $this->tableList)) {
                    $idGeneratorType = 0; // how should we handle this?
                    $newtable = new Table(
                        $name,
                        $table->getColumns(),
                        $table->getIndexes(),
                        $table->getForeignKeys(),
                        $idGeneratorType,
                        $table->getOptions()
                    );
                    $this->_addTable($newtable);
                }
            }
            //Debug::dump($table);
        } catch (\Exception $e) {
            \Xoops::getInstance()->events()->triggerEvent('core.exception', $e);
            throw $e;
        }
    }

    /**
     * Add a sequence to the schema
     *
     * @param Sequence $sequence a sequence
     *
     * @return void
     */
    public function addSequence(Sequence $sequence)
    {
        //echo '<h2>addSequence()</h2>';
        try {
            $this->_addSequence($sequence);
            //Debug::dump($sequence);
        } catch (\Exception $e) {
            \Xoops::getInstance()->events()->triggerEvent('core.exception', $e);
            throw $e;
        }
    }
}
