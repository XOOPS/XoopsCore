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

use Xoops\Core\Yaml;

/**
 * Xmf\Database\TableLoad
 *
 * load a database table
 *
 * @category  Xmf\Database\TableLoad
 * @package   Xmf
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2013 XOOPS Project (http://xoops.org)
 * @license   GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version   Release: 1.0
 * @link      http://xoops.org
 */
class TableLoad
{

    /**
     * loadTableFromArray
     *
     * @param string $table name of table to load without prefix
     * @param array  $data  array of rows to insert
     *                      Each element of the outer array represents a single table row.
     *                      Each row is an associative array in 'column' => 'value' format.
     *
     * @return int number of rows inserted
     */
    public static function loadTableFromArray($table, $data)
    {
        $db = \Xoops::getInstance()->db();
        $count = 0;
        $db->beginTransaction();
        foreach ($data as $row) {
            $count += $db->insertPrefix($table, $row);
        }
        $db->commit();
        return $count;
    }

    /**
     * loadTableFromYamlFile
     *
     * @param string $table    name of table to load without prefix
     * @param string $yamlFile name of file containing data dump in YAML format
     *
     * @return int number of rows inserted
     */
    public static function loadTableFromYamlFile($table, $yamlFile)
    {
        $count = 0;

        $data = Yaml::read($yamlFile);
        if ($data) {
            $count = self::loadTableFromArray($table, $data);
        }

        return $count;
    }

    /**
     * truncateTable - empty a database table
     *
     * @param string $table name of table to truncate
     *
     * @return int number of affected rows
     */
    public static function truncateTable($table)
    {
        $db = \Xoops::getInstance()->db();
        $platform = $db->getDatabasePlatform();
        $sql = $platform->getTruncateTableSQL($db->prefix($table));

        return $db->exec($sql);
    }

    /**
     * rowCount - get count of rows in a table
     *
     * @param string          $table    name of table to count
     * @param CriteriaElement $criteria optional criteria
     *
     * @return int number of rows
     */
    public static function rowCount($table, $criteria = null)
    {
        $db = \Xoops::getInstance()->db();
        $qb = $db->createXoopsQueryBuilder();
        $qb ->select('COUNT(*)')
            ->fromPrefix($table, '');
        if (isset($criteria) && is_subclass_of($criteria, 'Xoops\Core\Kernel\CriteriaElement')) {
            $qb = $criteria->renderQb($qb, '');
        }
        $result = $qb->execute();
        $count = $result->fetchColumn();
        return $count;
    }
}
