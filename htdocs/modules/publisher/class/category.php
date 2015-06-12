<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use Xoops\Core\Database\Connection;
use Xoops\Core\Kernel\XoopsObject;
use Xoops\Core\Kernel\XoopsPersistableObjectHandler;
use Xoops\Core\Kernel\Criteria;
use Xoops\Core\Kernel\CriteriaCompo;

/**
 * @copyright       The XUUPS Project http://sourceforge.net/projects/xuups/
 * @license         GNU GPL V2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package         Publisher
 * @since           1.0
 * @author          trabis <lusopoemas@gmail.com>
 * @author          The SmartFactory <www.smartfactory.ca>
 * @version         $Id$
 */

include_once dirname(__DIR__) . '/include/common.php';

class PublisherCategory extends XoopsObject
{
    /**
     * @var Publisher
     * @access public
     */
    public $publisher = null;

    /**
     * @var array
     */
    public $_categoryPath = false;

    /**
     * constructor
     */
    public function __construct()
    {
        $this->publisher = Publisher::getInstance();
        $this->initVar("categoryid", XOBJ_DTYPE_INT, null, false);
        $this->initVar("parentid", XOBJ_DTYPE_INT, null, false);
        $this->initVar("name", XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar("description", XOBJ_DTYPE_TXTAREA, null, false, 255);
        $this->initVar("image", XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar("total", XOBJ_DTYPE_INT, 1, false);
        $this->initVar("weight", XOBJ_DTYPE_INT, 1, false);
        $this->initVar("created", XOBJ_DTYPE_INT, null, false);
        $this->initVar("template", XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar("header", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("meta_keywords", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("meta_description", XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("short_url", XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar("moderator", XOBJ_DTYPE_INT, null, false, 0);
        //not persistent values
        $this->initVar("itemcount", XOBJ_DTYPE_INT, 0, false);
        $this->initVar('last_itemid', XOBJ_DTYPE_INT);
        $this->initVar('last_title_link', XOBJ_DTYPE_TXTBOX);
        $this->initVar("dohtml", XOBJ_DTYPE_INT, 1, false);
    }

    /**
     * @return bool
     */
    public function notLoaded()
    {
        return ($this->getVar('categoryid') == -1);
    }

    /**
     * @return bool
     */
    public function checkPermission()
    {
        $xoops = Xoops::getInstance();
        if ($this->publisher->isUserAdmin()) {
            return true;
        }
        if ($xoops->isUser() && $xoops->user->getVar('uid') == $this->getVar('moderator')) {
            return true;
        }
        return $this->publisher->getPermissionHandler()->isGranted('category_read', $this->getVar('categoryid'));
    }

    /**
     * @param string $format
     *
     * @return mixed|string
     */
    public function image($format = 's')
    {
        if ($this->getVar('image') != '') {
            return $this->getVar('image', $format);
        } else {
            return 'blank.png';
        }
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function template($format = 'n')
    {
        return $this->getVar("template", $format);
    }

    /**
     * @param bool $withAllLink
     *
     * @return array|bool|string
     */
    public function getCategoryPath($withAllLink = true)
    {
        if (!$this->_categoryPath) {
            if ($withAllLink) {
                $ret = $this->getCategoryLink();
            } else {
                $ret = $this->getVar('name');
            }
            $parentid = $this->getVar('parentid');
            if ($parentid != 0) {
                $parentObj = $this->publisher->getCategoryHandler()->get($parentid);
                if ($parentObj->notLoaded()) {
                    exit;
                }
                $ret = $parentObj->getCategoryPath($withAllLink) . " > " . $ret;
            }
            $this->_categoryPath = $ret;
        }
        return $this->_categoryPath;
    }

    /**
     * @return mixed|string
     */
    public function getCategoryPathForMetaTitle()
    {
        $ret = '';
        $parentid = $this->getVar('parentid');
        if ($parentid != 0) {
            $parentObj = $this->publisher->getCategoryHandler()->get($parentid);
            if ($parentObj->notLoaded()) {
                exit('NOT LOADED');
            }
            $ret = $parentObj->getCategoryPath(false);
            $ret = str_replace(' >', ' -', $ret);
        }
        return $ret;
    }

    /**
     * @return array|null
     */
    public function getGroups_read()
    {
        return $this->publisher->getPermissionHandler()->getGrantedGroupsById('category_read', $this->getVar('categoryid'));
    }

    /**
     * @return array|null
     */
    public function getGroups_submit()
    {
        return $this->publisher->getPermissionHandler()->getGrantedGroupsById('item_submit', $this->getVar('categoryid'));
    }

    /**
     * @return array|null
     */
    public function getGroups_moderation()
    {
        return $this->publisher->getPermissionHandler()->getGrantedGroupsById('category_moderation', $this->getVar('categoryid'));
    }

    /**
     * @return string
     */
    public function getCategoryUrl()
    {
        return PublisherUtils::seoGenUrl('category', $this->getVar('categoryid'), $this->getVar('short_url'));
    }

    /**
     * @param bool $class
     *
     * @return string
     */
    public function getCategoryLink($class = false)
    {
        if ($class) {
            return "<a class='$class' href='" . $this->getCategoryUrl() . "'>" . $this->getVar('name') . "</a>";
        } else {
            return "<a href='" . $this->getCategoryUrl() . "'>" . $this->getVar('name') . "</a>";
        }
    }

    /**
     * @param bool $sendNotifications
     * @param bool $force
     *
     * @return mixed
     */
    public function store($sendNotifications = true, $force = true)
    {
        $ret = $this->publisher->getCategoryHandler()->insert($this, $force);
        if ($sendNotifications && $ret && ($this->isNew())) {
            $this->sendNotifications();
        }
        $this->unsetNew();
        return $ret;
    }

    /**
     * Send notifications
     */
    public function sendNotifications()
    {
        $xoops = Xoops::getInstance();
        if ($xoops->isActiveModule('notifications')) {
            $tags = array();
            $tags['MODULE_NAME'] = $this->publisher->getModule()->getVar('name');
            $tags['CATEGORY_NAME'] = $this->getVar('name');
            $tags['CATEGORY_URL'] = $this->getCategoryUrl();
            $notification_handler = Notifications::getInstance()->getHandlerNotification();
            $notification_handler->triggerEvent('global', 0, 'category_created', $tags);
        }
    }

    /**
     * @param array $category
     *
     * @return array
     */
    public function toArray($category = array())
    {
        $category['categoryid'] = $this->getVar('categoryid');
        $category['name'] = $this->getVar('name');
        $category['categorylink'] = $this->getCategoryLink();
        $category['categoryurl'] = $this->getCategoryUrl();
        $category['total'] = ($this->getVar('itemcount') > 0) ? $this->getVar('itemcount') : '';
        $category['description'] = $this->getVar('description');
        $category['header'] = $this->getVar('header');
        $category['meta_keywords'] = $this->getVar('meta_keywords');
        $category['meta_description'] = $this->getVar('meta_description');
        $category['short_url'] = $this->getVar('short_url');
        if ($this->getVar('last_itemid') > 0) {
            $category['last_itemid'] = $this->getVar('last_itemid', 'n');
            $category['last_title_link'] = $this->getVar('last_title_link', 'n');
        }
        if ($this->image() != 'blank.png') {
            $category['image_path'] = PublisherUtils::getImageDir('category', false) . $this->image();
        } else {
            $category['image_path'] = '';
        }
        $category['lang_subcategories'] = sprintf(_CO_PUBLISHER_SUBCATEGORIES_INFO, $this->getVar('name'));
        return $category;
    }

    /**
     * @param array $category
     *
     * @return array
     */
    public function toArrayTable($category = array())
    {
        $category['categoryid'] = $this->getVar('categoryid');
        $category['categorylink'] = $this->getCategoryLink();
        $category['total'] = ($this->getVar('itemcount') > 0) ? $this->getVar('itemcount') : '';
        $category['description'] = $this->getVar('description');
        if ($this->getVar('last_itemid') > 0) {
            $category['last_itemid'] = $this->getVar('last_itemid', 'n');
            $category['last_title_link'] = $this->getVar('last_title_link', 'n');
        }
        if ($this->image() != 'blank.png') {
            $category['image_path'] = PublisherUtils::getImageDir('category', false) . $this->image();
        } else {
            $category['image_path'] = '';
        }
        $category['lang_subcategories'] = sprintf(_CO_PUBLISHER_SUBCATEGORIES_INFO, $this->getVar('name'));
        return $category;
    }

    /**
     *
     */
    public function createMetaTags()
    {
        $publisher_metagen = new PublisherMetagen($this->getVar('name'), $this->getVar('meta_keywords'), $this->getVar('meta_description'));
        $publisher_metagen->createMetaTags();
    }
}

/**
 * Categories handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of Category class objects.
 *
 * @author  marcan <marcan@notrevie.ca>
 * @package Publisher
 */
class PublisherCategoryHandler extends XoopsPersistableObjectHandler
{
    /**
     * @var Publisher
     * @access public
     */
    public $publisher = null;

    /**
     * @param Xoops\Core\Database\Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->publisher = Publisher::getInstance();
        parent::__construct($db, "publisher_categories", 'PublisherCategory', "categoryid", "name");
    }

    /**
     * @param null $id
     * @param null $fields
     *
     * @return null|PublisherCategory
     */
    public function get($id = null, $fields = null)
    {
        static $cats;
        if ($fields == null && isset($cats[$id])) {
            return $cats[$id];
        }
        $obj = parent::get($id, $fields);
        if ($fields == null) {
            $cats[$id] = $obj;
        }
        return $obj;
    }

    /**
     * insert a new category in the database
     *
     * @param XoopsObject $category reference to the {@link PublisherCategory} object
     * @param bool        $force
     *
     * @return bool FALSE if failed, TRUE if already present and unchanged or successful
     */
    public function insert(XoopsObject $category, $force = true)
    {
        // Auto create meta tags if empty
        if (!$category->getVar('meta_keywords') || !$category->getVar('meta_description')) {
            $publisher_metagen = new PublisherMetagen($category->getVar('name'), $category->getVar('meta_keywords'), $category->getVar('description'));
            if (!$category->getVar('meta_keywords')) {
                $category->setVar('meta_keywords', $publisher_metagen->_keywords);
            }
            if (!$category->getVar('meta_description')) {
                $category->setVar('meta_description', $publisher_metagen->_description);
            }
        }
        // Auto create short_url if empty
        if (!$category->getVar('short_url')) {
            $category->setVar('short_url', PublisherMetagen::generateSeoTitle($category->getVar('name', 'n'), false));
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
        $this->publisher->getItemHandler()->deleteAll($criteria, true, true);
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
        $module_id = $this->publisher->getModule()->getVar('mid');

        $xoops->getHandlerGroupperm()->deleteByModule($module_id, "category_read", $category->getVar('categoryid'));
        $xoops->getHandlerGroupperm()->deleteByModule($module_id, "item_submit", $category->getVar('categoryid'));
        $xoops->getHandlerGroupperm()->deleteByModule($module_id, "category_moderation", $category->getVar('categoryid'));
        return true;
    }

    /**
     * @param int    $limit
     * @param int    $start
     * @param int    $parentid
     * @param string $sort
     * @param string $order
     * @param bool   $id_as_key
     *
     * @return array
     */
    public function &getCategories($limit = 0, $start = 0, $parentid = 0, $sort = 'weight', $order = 'ASC', $id_as_key = true)
    {
        $xoops = Xoops::getInstance();
        $ret = array();
        $criteria = new CriteriaCompo();
        $criteria->setSort($sort);
        $criteria->setOrder($order);
        if ($parentid != -1) {
            $criteria->add(new Criteria('parentid', $parentid));
        }
        if (!PublisherUtils::IsUserAdmin()) {
            $categoriesGranted = $this->publisher->getPermissionHandler()->getGrantedItems('category_read');
            if (count($categoriesGranted) > 0) {
                $criteria->add(new Criteria('categoryid', '(' . implode(',', $categoriesGranted) . ')', 'IN'));
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
     * @param integer $level
     * @param array   $cat_array
     * @param array   $cat_result
     *
     * @return void
     */
    public function getSubCatArray($category, $level, $cat_array, $cat_result)
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

    public function &getCategoriesForSubmit()
    {
        global $theresult;
        $xoops = Xoops::getInstance();
        $ret = array();
        $criteria = new CriteriaCompo();
        $criteria->setSort('name');
        $criteria->setOrder('ASC');
        if (!PublisherUtils::IsUserAdmin()) {
            $categoriesGranted = $this->publisher->getPermissionHandler()->getGrantedItems('item_submit');
            if (count($categoriesGranted) > 0) {
                $criteria->add(new Criteria('categoryid', '(' . implode(',', $categoriesGranted) . ')', 'IN'));
            } else {
                return $ret;
            }
            if ($xoops->isUser()) {
                $criteria->add(new Criteria('moderator', $xoops->user->getVar('uid')), 'OR');
            }
        }
        $categories = $this->getAll($criteria, array('categoryid', 'parentid', 'name'), false, false);
        if (count($categories) == 0) {
            return $ret;
        }
        $cat_array = array();
        foreach ($categories as $cat) {
            $cat_array[$cat['parentid']][$cat['categoryid']] = $cat;
        }
        // Needs to have permission on at least 1 top level category
        if (!isset($cat_array[0])) {
            return $ret;
        }
        $cat_result = array();
        foreach ($cat_array[0] as $thecat) {
            $level = 0;
            $this->getSubCatArray($thecat, $level, $cat_array, $cat_result);
        }
        return $theresult; //this is a global
    }

    /**
     * @return array
     */
    public function &getCategoriesForSearch()
    {
        global $theresult;
        $xoops = Xoops::getInstance();
        $ret = array();
        $criteria = new CriteriaCompo();
        $criteria->setSort('name');
        $criteria->setOrder('ASC');
        if (!PublisherUtils::IsUserAdmin()) {
            $categoriesGranted = $this->publisher->getPermissionHandler()->getGrantedItems('category_read');
            if (count($categoriesGranted) > 0) {
                $criteria->add(new Criteria('categoryid', '(' . implode(',', $categoriesGranted) . ')', 'IN'));
            } else {
                return $ret;
            }
            if ($xoops->isUser()) {
                $criteria->add(new Criteria('moderator', $xoops->user->getVar('uid')), 'OR');
            }
        }
        $categories = $this->getAll($criteria, array('categoryid', 'parentid', 'name'), false, false);
        if (count($categories) == 0) {
            return $ret;
        }
        $cat_array = array();
        foreach ($categories as $cat) {
            $cat_array[$cat['parentid']][$cat['categoryid']] = $cat;
        }
        // Needs to have permission on at least 1 top level category
        if (!isset($cat_array[0])) {
            return $ret;
        }
        $cat_result = array();
        foreach ($cat_array[0] as $thecat) {
            $level = 0;
            $this->getSubCatArray($thecat, $level, $cat_array, $cat_result);
        }
        return $theresult; //this is a global
    }

    /**
     * @param int $parentid
     *
     * @return int
     */
    public function getCategoriesCount($parentid = 0)
    {
        $xoops = Xoops::getInstance();
        if ($parentid == -1) {
            return $this->getCount();
        }
        $criteria = new CriteriaCompo();
        if (isset($parentid) && ($parentid != -1)) {
            $criteria->add(new criteria('parentid', $parentid));
            if (!PublisherUtils::IsUserAdmin()) {
                $categoriesGranted = $this->publisher->getPermissionHandler()->getGrantedItems('category_read');
                if (count($categoriesGranted) > 0) {
                    $criteria->add(new Criteria('categoryid', '(' . implode(',', $categoriesGranted) . ')', 'IN'));
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
     *
     * @return array
     */
    public function &getSubCats($categories)
    {
        $xoops = Xoops::getInstance();
        $criteria = new CriteriaCompo(new Criteria('parentid', "(" . implode(',', array_keys($categories)) . ")", 'IN'));
        $ret = array();
        if (!PublisherUtils::IsUserAdmin()) {
            $categoriesGranted = $this->publisher->getPermissionHandler()->getGrantedItems('category_read');
            if (count($categoriesGranted) > 0) {
                $criteria->add(new Criteria('categoryid', '(' . implode(',', $categoriesGranted) . ')', 'IN'));
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
        /* @var $subcat PublisherCategory */
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
        return $this->itemsCount($cat_id, $status = array(_PUBLISHER_STATUS_PUBLISHED));
    }

    /**
     * @param int    $cat_id
     * @param string $status
     *
     * @return mixed
     */
    public function itemsCount($cat_id = 0, $status = '')
    {
        return $this->publisher->getItemHandler()->getCountsByCat($cat_id, $status);
    }
}
