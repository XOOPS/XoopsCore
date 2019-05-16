<?php

namespace XoopsModules\Publisher;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;
use XoopsModules\Publisher;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */
require_once \dirname(__DIR__) . '/include/common.php';

// File status
\define('_PUBLISHER_STATUS_FILE_NOTSET', -1);
\define('_PUBLISHER_STATUS_FILE_ACTIVE', 1);
\define('_PUBLISHER_STATUS_FILE_INACTIVE', 2);

/**
 * Files handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of File class objects.
 *
 * @author  marcan <marcan@notrevie.ca>
 * @package Publisher
 */
class FileHandler extends XoopsPersistableObjectHandler
{
    public function __construct(Connection $db)
    {
        parent::__construct($db, 'publisher_files', 'Publisher\File', 'fileid', 'name');
    }

    /**
     * delete a file from the database
     *
     * @param XoopsObject|Publisher\File $file reference to the file to delete
     * @param bool                       $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(XoopsObject $file, $force = false)
    {
        $ret = false;
        // Delete the actual file
        if (\is_file($file->getFilePath()) && \unlink($file->getFilePath())) {
            $ret = parent::delete($file, $force);
        }

        return $ret;
    }

    /**
     * delete files related to an item from the database
     *
     * @param object $itemObj reference to the item which files to delete
     */
    public function deleteItemFiles(&$itemObj): bool
    {
        if ('publisheritem' !== \mb_strtolower(\get_class($itemObj))) {
            return false;
        }
        $files = $this->getAllFiles($itemObj->getVar('itemid'));
        $result = true;
        foreach ($files as $file) {
            if (!$this->delete($file)) {
                $result = false;
            }
        }

        return $result;
    }

    /**
     * retrieve all files
     *
     * @param int    $itemid
     * @param int|array    $status
     * @param int    $limit
     * @param int    $start
     * @param string $sort
     * @param string $order
     * @param array  $category
     *
     * @return array array of {@link Publisher\File} objects
     */
    public function &getAllFiles($itemid = 0, $status = -1, $limit = 0, $start = 0, $sort = 'datesub', $order = 'DESC', $category = []): array
    {
        $this->table_link = $this->db2->prefix('publisher_items');
        $this->field_object = 'itemid';
        $this->field_link = 'itemid';
        $hasStatusCriteria = false;
        $criteriaStatus = new CriteriaCompo();
        if (\is_array($status)) {
            $hasStatusCriteria = true;
            foreach ($status as $v) {
                $criteriaStatus->add(new Criteria('o.status', $v), 'OR');
            }
        } elseif (-1 != $status) {
            $hasStatusCriteria = true;
            $criteriaStatus->add(new Criteria('o.status', $status), 'OR');
        }
        $hasCategoryCriteria = false;
        $criteriaCategory = new CriteriaCompo();
        $category = (array)$category;
        if (\count($category) > 0 && 0 != $category[0]) {
            $hasCategoryCriteria = true;
            foreach ($category as $cat) {
                $criteriaCategory->add(new Criteria('l.categoryid', $cat), 'OR');
            }
        }
        $criteriaItemid = new Criteria('o.itemid', $itemid);
        $criteria = new CriteriaCompo();
        if (0 != $itemid) {
            $criteria->add($criteriaItemid);
        }
        if ($hasStatusCriteria) {
            $criteria->add($criteriaStatus);
        }
        if ($hasCategoryCriteria) {
            $criteria->add($criteriaCategory);
        }
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        $criteria->setLimit($limit);
        $criteria->setStart($start);
        $files = $this->getByLink($criteria, ['o.*'], true);

        return $files;
    }
}
