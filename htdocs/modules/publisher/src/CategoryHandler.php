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

use Xoops;
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

/**
 * Categories handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of Category class objects.
 *
 * @author  marcan <marcan@notrevie.ca>
 * @package Publisher
 */
class CategoryHandler extends XoopsPersistableObjectHandler
{
    /**
     * @var Helper
     * @access public
     */
    public $helper = null;

    /**
     * @param Xoops\Core\Database\Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->helper = Helper::getInstance();
        parent::__construct($db, 'publisher_categories', Category::class, 'categoryid', 'name');
    }

    /**
     * @param null $id
     * @param null $fields
     *
     * @return null|Publisher\Category
     */
    public function get($id = null, $fields = null)
    {
        static $cats;
        if (null === $fields && isset($cats[$id])) {
            return $cats[$id];
        }
        $obj = parent::get($id, $fields);
        if (null === $fields) {
            $cats[$id] = $obj;
        }

        return $obj;
    }

    /**
     * insert a new category in the database
     *
     * @param XoopsObject $category reference to the {@link Publisher\Category} object
     * @param bool        $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(XoopsObject $category, $force = true)
    {
        // Auto create meta tags if empty
        if (!$category->getVar('meta_keywords') || !$category->getVar('meta_description')) {
            $publisher_metagen = new Publisher\Metagen($category->getVar('name'), $category->getVar('meta_keywords'), $category->getVar('description'));
            if (!$category->getVar('meta_keywords')) {
                $category->setVar('meta_keywords', $publisher_metagen->_keywords);
            }
            if (!$category->getVar('meta_description')) {
                $category->setVar('meta_description', $publisher_metagen->_description);
            }
        }
        // Auto create short_url if empty
        if (!$category->getVar('short_url')) {
            $category->setVar('short_url', Publisher\Metagen::generateSeoTitle($category->getVar('name', 'n'), false));
        }
        $ret = parent::insert($category, $force);

        return $ret;
    }

    /**
     * delete a category from the database
     *
     * @param XoopsObject $category reference to the category to delete
     * @param bool        $force
     *
     * @return bool FALSE if failed.
     */
    public function delete(XoopsObject $category, $force = false)
    {
        $xoops = Xoops::getInstance();
        // Deleting this category ITEMs
        $criteria = new Criteria('categoryid', $category->getVar('categoryid'));
        $this->helper->getItemHandler()->deleteAll($criteria, true, true);
        unset($criteria);
        // Deleting the sub categories
        $subcats = $this->getCategories(0, 0, $category->getVar('categoryid'));
        foreach ($subcats as $subcat) {
            $this->delete($subcat);
        }
        if (!parent::delete($category, $force)) {
            $category->setErrors('An error while deleting.');

            return false;
        }
        $module_id = $this->helper->getModule()->getVar('mid');

        $xoops->getHandlerGroupPermission()->deleteByModule($module_id, 'category_read', $category->getVar('categoryid'));
        $xoops->getHandlerGroupPermission()->deleteByModule($module_id, 'item_submit', $category->getVar('categoryid'));
        $xoops->getHandlerGroupPermission()->deleteByModule($module_id, 'category_moderation', $category->getVar('categoryid'));

        return true;
    }

    /**
     * @param int    $limit
     * @param int    $start
     * @param int    $parentid
     * @param string $sort
     * @param string $order
     * @param bool   $id_as_key
     */
    public function &getCategories($limit = 0, $start = 0, $parentid = 0, $sort = 'weight', $order = 'ASC', $id_as_key = true): array
    {
        $xoops = Xoops::getInstance();
        $ret = [];
        $criteria = new CriteriaCompo();
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        if (-1 != $parentid) {
            $criteria->add(new Criteria('parentid', $parentid));
        }
        if (!Publisher\Utils::IsUserAdmin()) {
            $categoriesGranted = $this->helper->getPermissionHandler()->getGrantedItems('category_read');
            if (\count($categoriesGranted) > 0) {
                $criteria->add(new Criteria('categoryid', '(' . \implode(',', $categoriesGranted) . ')', 'IN'));
            } else {
                return $ret;
            }
            if ($xoops->isUser()) {
                $criteria->add(new Criteria('moderator', $xoops->user->getVar('uid')), 'OR');
            }
        }
        $criteria->setStart($start);
        $criteria->setLimit($limit);
        $ret = $this->getObjects($criteria, $id_as_key);

        return $ret;
    }

    /**
     * getSubCatArray
     *
     * @param array   $category
     * @param int $level
     * @param array   $cat_array
     * @param array   $cat_result
     */
    public function getSubCatArray($category, $level, $cat_array, $cat_result): void
    {
        global $theresult;
        $spaces = '';
        for ($j = 0; $j < $level; ++$j) {
            $spaces .= '--';
        }
        $theresult[$category['categoryid']] = $spaces . $category['name'];
        if (isset($cat_array[$category['categoryid']])) {
            $level = $level + 1;
            foreach ($cat_array[$category['categoryid']] as $cat) {
                $this->getSubCatArray($cat, $level, $cat_array, $cat_result);
            }
        }
    }

    public function &getCategoriesForSubmit(): array
    {
        global $theresult;
        $xoops = Xoops::getInstance();
        $ret = [];
        $criteria = new CriteriaCompo();
        $criteria->setSort('name');
        $criteria->setOrder('ASC');
        if (!Publisher\Utils::IsUserAdmin()) {
            $categoriesGranted = $this->helper->getPermissionHandler()->getGrantedItems('item_submit');
            if (\count($categoriesGranted) > 0) {
                $criteria->add(new Criteria('categoryid', '(' . \implode(',', $categoriesGranted) . ')', 'IN'));
            } else {
                return $ret;
            }
            if ($xoops->isUser()) {
                $criteria->add(new Criteria('moderator', $xoops->user->getVar('uid')), 'OR');
            }
        }
        $categories = $this->getAll($criteria, ['categoryid', 'parentid', 'name'], false, false);
        if (0 == \count($categories)) {
            return $ret;
        }
        $cat_array = [];
        foreach ($categories as $cat) {
            $cat_array[$cat['parentid']][$cat['categoryid']] = $cat;
        }
        // Needs to have permission on at least 1 top level category
        if (!isset($cat_array[0])) {
            return $ret;
        }
        $cat_result = [];
        foreach ($cat_array[0] as $thecat) {
            $level = 0;
            $this->getSubCatArray($thecat, $level, $cat_array, $cat_result);
        }

        return $theresult; //this is a global
    }

    public function &getCategoriesForSearch(): array
    {
        global $theresult;
        $xoops = Xoops::getInstance();
        $ret = [];
        $criteria = new CriteriaCompo();
        $criteria->setSort('name');
        $criteria->setOrder('ASC');
        if (!Publisher\Utils::IsUserAdmin()) {
            $categoriesGranted = $this->helper->getPermissionHandler()->getGrantedItems('category_read');
            if (\count($categoriesGranted) > 0) {
                $criteria->add(new Criteria('categoryid', '(' . \implode(',', $categoriesGranted) . ')', 'IN'));
            } else {
                return $ret;
            }
            if ($xoops->isUser()) {
                $criteria->add(new Criteria('moderator', $xoops->user->getVar('uid')), 'OR');
            }
        }
        $categories = $this->getAll($criteria, ['categoryid', 'parentid', 'name'], false, false);
        if (0 == \count($categories)) {
            return $ret;
        }
        $cat_array = [];
        foreach ($categories as $cat) {
            $cat_array[$cat['parentid']][$cat['categoryid']] = $cat;
        }
        // Needs to have permission on at least 1 top level category
        if (!isset($cat_array[0])) {
            return $ret;
        }
        $cat_result = [];
        foreach ($cat_array[0] as $thecat) {
            $level = 0;
            $this->getSubCatArray($thecat, $level, $cat_array, $cat_result);
        }

        return $theresult; //this is a global
    }

    /**
     * @param int $parentid
     */
    public function getCategoriesCount($parentid = 0): int
    {
        $xoops = Xoops::getInstance();
        if (-1 == $parentid) {
            return $this->getCount();
        }
        $criteria = new CriteriaCompo();
        if (isset($parentid) && (-1 != $parentid)) {
            $criteria->add(new criteria('parentid', $parentid));
            if (!Publisher\Utils::IsUserAdmin()) {
                $categoriesGranted = $this->helper->getPermissionHandler()->getGrantedItems('category_read');
                if (\count($categoriesGranted) > 0) {
                    $criteria->add(new Criteria('categoryid', '(' . \implode(',', $categoriesGranted) . ')', 'IN'));
                } else {
                    return 0;
                }
                if ($xoops->isUser()) {
                    $criteria->add(new Criteria('moderator', $xoops->user->getVar('uid')), 'OR');
                }
            }
        }

        return $this->getCount($criteria);
    }

    /**
     * Get all subcats and put them in an array indexed by parent id
     *
     * @param array $categories
     */
    public function &getSubCats($categories): array
    {
        $xoops = Xoops::getInstance();
        $criteria = new CriteriaCompo(new Criteria('parentid', '(' . \implode(',', \array_keys($categories)) . ')', 'IN'));
        $ret = [];
        if (!Publisher\Utils::IsUserAdmin()) {
            $categoriesGranted = $this->helper->getPermissionHandler()->getGrantedItems('category_read');
            if (\count($categoriesGranted) > 0) {
                $criteria->add(new Criteria('categoryid', '(' . \implode(',', $categoriesGranted) . ')', 'IN'));
            } else {
                return $ret;
            }
            if ($xoops->isUser()) {
                $criteria->add(new Criteria('moderator', $xoops->user->getVar('uid')), 'OR');
            }
        }
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        $subcats = $this->getObjects($criteria, true);
        /* @var Publisher\Category $subcat */
        foreach ($subcats as $subcat) {
            $ret[$subcat->getVar('parentid')][$subcat->getVar('categoryid')] = $subcat;
        }

        return $ret;
    }

    /**
     * @param int $cat_id
     *
     * @return mixed
     */
    public function publishedItemsCount($cat_id = 0)
    {
        return $this->itemsCount($cat_id, $status = [\_PUBLISHER_STATUS_PUBLISHED]);
    }

    /**
     * @param int    $cat_id
     * @param string $status
     *
     * @return mixed
     */
    public function itemsCount($cat_id = 0, $status = '')
    {
        return $this->helper->getItemHandler()->getCountsByCat($cat_id, $status);
    }
}
